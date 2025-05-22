<?php

namespace App\Http\Controllers;

use App\Models\Cuenta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\DetalleCuenta;
use Illuminate\Support\Facades\Auth;

use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

class TicketController extends Controller
{
    private function getImpresoraConfig($area)
    {
        $config = config("impresoras.$area");
        if (!$config) {
            throw new \Exception("No hay configuración de impresora para el área: $area");
        }
        return $config;
    }

    private function imprimirTicketConFallback($ticket, $area)
    {
        $config = $this->getImpresoraConfig($area);
        $error = null;

        // 1. Intenta por red
        try {
            $net = $config['network'];
            if ($net['type'] === 'network') {
                $connector = new NetworkPrintConnector($net['ip'], $net['port']);
                $printer = new Printer($connector);
                $printer->text($ticket);
                $printer->feed(4);
                $printer->cut();
                $printer->close();
                return true;
            }
        } catch (\Exception $e) {
            $error = $e->getMessage();
            Log::warning("Impresión por red falló en $area: $error. Intentando por USB...");
        }

        // 2. Si falla, intenta por USB/Windows
        try {
            $usb = $config['usb'];
            if ($usb['type'] === 'windows') {
                $connector = new WindowsPrintConnector($usb['win_name']);
            } else {
                throw new \Exception('Tipo de impresora USB no soportado');
            }
            $printer = new Printer($connector);
            $printer->text($ticket);
            $printer->feed(4);
            $printer->cut();
            $printer->close();
            return true;
        } catch (\Exception $e) {
            $error .= " | Fallback USB también falló: " . $e->getMessage();
            Log::error("Impresión falló en $area: $error");
            return false;
        }
    }

    private function getPrintableWidth()
    {
        // Ajusta este número según el ancho de tu impresora. 32 es común en tickets de 58mm
        return 32;
    }

    private function centerText($text, $width = null)
    {
        $width = $width ?? $this->getPrintableWidth();
        $text = trim($text);
        $len = mb_strlen($text, 'UTF-8');
        if ($len >= $width) return $text . "\n";
        $spaces = floor(($width - $len) / 2);
        return str_repeat(' ', $spaces) . $text . "\n";
    }

    private function prettySeparator($char = '═', $width = null)
    {
        $width = $width ?? $this->getPrintableWidth();
        return str_repeat($char, $width) . "\n";
    }

    private function prettyTitle($title, $width = null)
    {
        $width = $width ?? $this->getPrintableWidth();
        // Usa ░▒▓ para decorar el título
        $symbol = '░▒▓';
        $lenTitle = mb_strlen($title, 'UTF-8');
        $side = ($width - $lenTitle - 2) / 2; // 2 espacios para márgenes
        $sideSym = str_repeat($symbol, ceil($side / mb_strlen($symbol, 'UTF-8')));
        $sideSym = mb_substr($sideSym, 0, floor($side));
        return $sideSym . ' ' . $title . ' ' . $sideSym . "\n";
    }

    public function imprimirProducto(Cuenta $cuenta, $productoId, $idUnico, Request $request)
    {
        $productos = is_string($cuenta->productos)
            ? json_decode($cuenta->productos, true)
            : $cuenta->productos;

        // Busca por id_unico
        $productoKey = array_search($idUnico, array_column($productos, 'id_unico'));
        if ($productoKey === false || $productos[$productoKey]['producto_id'] != $productoId) {
            return response()->json(['error' => 'No se encontró el producto.'], 404);
        }

        $producto = $productos[$productoKey];
        $productos[$productoKey]['printed_at'] = now()->toDateTimeString();
        $cuenta->productos = json_encode($productos);
        $cuenta->save();

        $nombresAreas = [
            1 => 'Carne en Vara',
            2 => 'Cachapas',
            3 => 'Barra',
            4 => 'Cocina'
        ];

        $areaNombre = $nombresAreas[$producto['area_id']] ?? "Área {$producto['area_id']}";
        $areaNombreMayus = mb_strtoupper($areaNombre, 'UTF-8');
        $fecha = now()->format('Y-m-d H:i:s');
        $cajera = $cuenta->cajera ?? (Auth::user()->name ?? '');
        $responsable = $cuenta->responsable_pedido ?? '';
        $width = $this->getPrintableWidth();

        $tituloDecorado = $this->prettyTitle($areaNombreMayus, $width);
        $separadorBonito = $this->prettySeparator('═', $width);
        $separadorSimple = $this->prettySeparator('─', $width);
        $productoLinea = "{$producto['nombre']} - Cantidad: {$producto['cantidad']}";

        // Obtiene el área seleccionada al loguear (de la sesión)
        $area = session('area_trabajo');
        if (!$area) {
            return response()->json(['error' => 'No se detectó el área de trabajo en la sesión.'], 400);
        }

        // Conéctate a la impresora y usa ESC/POS para centrar de verdad
        $config = $this->getImpresoraConfig($area);
        $usb = $config['usb'];
        $printer = new Printer(new WindowsPrintConnector($usb['win_name']));

        // Título decorado y centrado
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text($tituloDecorado . "\n");

        // Datos alineados a AL CENTRO
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text("Cajer@ : $cajera\n");
        $printer->text("Responsable: $responsable\n");

        

        // Datos principales centrados
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text("\n");
        $printer->text("ID Cuenta: {$cuenta->id}\n");
        $printer->text("Fecha y Hora: $fecha\n");
        $printer->text($separadorBonito);

        // Datos alineados a la izquierda
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text($productoLinea . "\n");

        // Datos alineados a AL CENTRO
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text($separadorBonito);
        $printer->text("Gracias por su pedido.");

        $printer->feed(2);
        $printer->cut();
        $printer->close();

        return response()->json(['success' => 'Ticket enviado correctamente a la impresora.']);
    }

    public function imprimir(Request $request, $cuentaId)
    {
        $cuenta = Cuenta::with('productos')->findOrFail($cuentaId);

        $area = $request->area;
        $productosDelArea = $cuenta->productos->filter(function ($producto) use ($area) {
            return $producto->area === $area;
        });

        $ticketData = [
            'cuenta_id' => $cuenta->id,
            'cliente' => $cuenta->cliente_nombre ?? 'Sin cliente',
            'fecha_hora' => $cuenta->fecha_apertura,
            'estacion' => $cuenta->estacion,
            'productos' => $productosDelArea
        ];

        return view('tickets.detalle', compact('ticketData'));
    }

    public function marcarProductoImpreso(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|exists:detalle_cuentas,id',
        ]);

        $producto = DetalleCuenta::find($request->producto_id);
        $producto->printed_at = now();
        $producto->save();

        return response()->json(['message' => 'Producto marcado como impreso.']);
    }

    private function imprimirTicketUsbOnly($ticket, $area)
    {
        $config = $this->getImpresoraConfig($area);
        try {
            $usb = $config['usb'];
            if ($usb['type'] === 'windows') {
                $connector = new WindowsPrintConnector($usb['win_name']);
            } else {
                throw new \Exception('Tipo de impresora USB no soportado');
            }
            $printer = new Printer($connector);
            $printer->text($ticket);
            $printer->feed(4);
            $printer->cut();
            $printer->close();
            return true;
        } catch (\Exception $e) {
            Log::error("Impresión falló en $area: " . $e->getMessage());
            return false;
        }
    }

    private function guardarTicketTxt($ticket, $area)
    {
        $area = strtoupper(str_replace(' ', '_', $area));
        $carpeta = '\\\\Japp-king\\tickets\\' . $area; // Cambia por tu nombre de servidor

        if (!file_exists($carpeta)) {
            mkdir($carpeta, 0777, true);
        }

        $archivo = $carpeta . '\\ticket_' . date('Ymd_His') . '_' . uniqid() . '.txt';
        file_put_contents($archivo, $ticket);
        return $archivo;
    }
}