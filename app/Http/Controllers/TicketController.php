<?php

namespace App\Http\Controllers;

use App\Models\Cuenta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\DetalleCuenta;

class TicketController extends Controller
{
public function imprimirProducto(Cuenta $cuenta, $productoId)
{
    // Decodificar los productos si están en formato JSON
    $productos = is_string($cuenta->productos)
        ? json_decode($cuenta->productos, true)
        : $cuenta->productos;

    // Buscar el índice del producto específico
    $productoKey = array_search($productoId, array_column($productos, 'producto_id'));
    if ($productoKey === false) {
        return response()->json(['error' => 'No se encontró el producto.'], 404);
    }

    $producto = $productos[$productoKey];

    // Marcar el producto como impreso
    $productos[$productoKey]['printed_at'] = now()->toDateTimeString();
    $cuenta->productos = json_encode($productos);
    $cuenta->save();

    // Mapeo de áreas a nombres amigables
    $nombresAreas = [
        1 => 'Carne en Vara',
        2 => 'Cachapas',
        3 => 'Barra',
        4 => 'Cocina'
    ];

    $areaNombre = $nombresAreas[$producto['area_id']] ?? "Área {$producto['area_id']}";
    $fecha = now()->format('Y-m-d H:i:s');
    $ticket = "Estacion: $areaNombre\r\n";
    $ticket .= "ID Cuenta: {$cuenta->id}\r\n";
    $ticket .= "Cliente: {$cuenta->cliente_nombre}\r\n";
    $ticket .= "Fecha//Hora: $fecha\r\n";
    $ticket .= "--------------------------\r\n";
    $ticket .= "{$producto['nombre']} - Cantidad: {$producto['cantidad']}\r\n";
    $ticket .= "--------------------------\r\n";
    $ticket .= "Gracias por su pedido.\r\n";
    $ticket .= "\r\n"; // Agregar un salto de línea adicional
    $ticket .= "\r\n"; // Agregar un salto de línea adicional

    // Enviar el ticket a la impresora
    try {
        $archivoTemporal = "C:\\Users\\japp3\\Downloads\\ticket_{$producto['producto_id']}.txt";
        file_put_contents($archivoTemporal, $ticket);
        exec("print /d:\\\\JAPP-KING\\POS-58 $archivoTemporal");
        sleep(2); // Esperar para evitar conflictos
        unlink($archivoTemporal);

        return response()->json(['success' => 'Ticket enviado correctamente a la impresora.']);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Error al imprimir el ticket: ' . $e->getMessage()], 500);
    }
}

    public function imprimir(Request $request, $cuentaId)
    {
        $cuenta = Cuenta::with('productos')->findOrFail($cuentaId);

        $area = $request->area; // Área solicitada (Barra, Cocina, etc.)
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

        // Genera el ticket (ej. PDF o impresión directa)
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
}