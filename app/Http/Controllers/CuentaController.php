<?php

namespace App\Http\Controllers;

use App\Models\Cuenta;
use App\Models\Cliente;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Illuminate\Support\Facades\Response;

class CuentaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
    
        // Consulta para obtener solo las cuentas NO pagadas con búsqueda opcional
        $cuentas = Cuenta::where('pagada', false) // Solo cuentas no pagadas
            ->when($search, function ($query, $search) {
                $query->where('cliente_nombre', 'like', "%$search%")
                      ->orWhere('responsable_pedido', 'like', "%$search%")
                      ->orWhere('estacion', 'like', "%$search%");
            })
            ->orderBy('updated_at', 'desc') // Ordenar por la fecha de actualización más reciente
            ->paginate(10); // Asegúrate de usar paginación
    
        return view('cuentas.index', compact('cuentas'));
    }


    public function create()
    {
        $productos = Producto::orderBy('nombre')->get();
        $clientes = Cliente::all();

        $productosJS = $productos->mapWithKeys(function ($producto) {
            return [$producto->id => [
                'nombre' => $producto->nombre,
                'precio' => (float) $producto->precio_venta,
            ]];
        });

        return view('cuentas.create', compact('productos', 'productosJS', 'clientes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cliente_id'       => 'nullable|exists:clientes,id',
            'cliente_nombre'   => 'nullable|string|max:255',
            'responsable'      => 'nullable|string|max:255',
            'estacion'         => 'required|string|max:255',
            'fecha_hora'       => 'required|date',
            'productos'        => 'required|array',
            'productos.*'      => 'exists:productos,id',
            'cantidades'       => 'required|array',
            'cantidades.*'     => 'numeric|min:1',
            'metodo_pago'      => 'required|array',
            'monto_pago'       => 'required|array',
            'referencia_pago'  => 'nullable|array',
            // 'tasa_dia'         => 'nullable|numeric|min:0',
            'tasa_dia'         => 'nullable|numeric|min:0', // Validar la tasa ingresada
        ]);

        if (empty($request->cliente_id) && empty($request->cliente_nombre)) {
            return back()->withErrors([
                'cliente_nombre' => 'Debe seleccionar un cliente o ingresar un nombre manual.',
            ])->withInput();
        }

        DB::beginTransaction();

        try {
            $tasa = $request->tasa_dia ?? 1;
            $total = 0;
            $productos_array = [];

            foreach ($request->productos as $index => $producto_id) {
                $producto = Producto::findOrFail($producto_id);
                $cantidad = $request->cantidades[$index] ?? 1;
                $subtotal = $producto->precio_venta * $cantidad;

                $productos_array[] = [
                    'producto_id' => $producto_id,
                    'cantidad'    => $cantidad,
                    'precio'      => $producto->precio_venta,
                    'subtotal'    => $subtotal
                ];

                $total += $subtotal;
            }

            $cuenta = Cuenta::create([
                'cliente_id'         => $request->cliente_id,
                'cliente_nombre'     => $request->cliente_nombre ?? null,
                'usuario_id'         => Auth::id(),
                'responsable_pedido' => $request->responsable,
                'estacion'           => $request->estacion,
                'fecha_apertura'     => $request->fecha_hora,
                'total_estimado'     => $total,
                'productos'          => json_encode($productos_array),
                'metodos_pago'       => json_encode([]), // provisional para luego actualizar
            ]);

            $metodos_pago_array = [];
            foreach ($request->metodo_pago as $index => $metodo) {
                $metodos_pago_array[] = [
                    'metodo'     => $metodo,
                    'monto'      => $request->monto_pago[$index] ?? 0,
                    'referencia' => $request->referencia_pago[$index] ?? null,
                ];
            }

            $cuenta->metodos_pago = json_encode($metodos_pago_array);
            $cuenta->save();

            DB::commit();
            return redirect()->route('cuentas.index')->with('success', 'Cuenta registrada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al registrar la cuenta: ' . $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $cuenta = Cuenta::with('cliente')->findOrFail($id);

        $cuenta->productos = json_decode($cuenta->productos, true);
        $cuenta->metodos_pago = json_decode($cuenta->metodos_pago, true);

        $tasaUsada = $cuenta->tasa_dia ?? null; // Asegúrate de que `tasa_dia` esté almacenada en la cuenta

        $productosIds = array_column($cuenta->productos, 'producto_id');
        $productos = Producto::whereIn('id', $productosIds)->get()->keyBy('id');

        return view('cuentas.show', compact('cuenta', 'productos', 'tasaUsada'));
    }

    public function edit($id)
{
    $cuenta = Cuenta::findOrFail($id);
    $clientes = Cliente::all();
    $productos = Producto::orderBy('nombre')->get();

    // Decodificar los productos seleccionados almacenados en la cuenta
    $productosSeleccionados = json_decode($cuenta->productos, true) ?? [];
    $metodosPago = json_decode($cuenta->metodos_pago, true) ?? [];

    // Obtener la tasa de cambio actual
    $tasaActual = DB::table('config')->where('key', 'tasa_cambio')->value('value');

    // Convertir productos a un formato útil para JavaScript
    $productosJS = $productos->mapWithKeys(function ($producto) {
        return [$producto->id => [
            'nombre' => $producto->nombre,
            'precio' => (float) $producto->precio_venta,
        ]];
    });

    return view('cuentas.edit', compact(
        'cuenta',
        'clientes',
        'productos',
        'productosJS',
        'productosSeleccionados',
        'metodosPago',
        'tasaActual'
    ));
}
    public function update(Request $request, Cuenta $cuenta)
    {
        $request->validate([
            'cliente_id'       => 'nullable|exists:clientes,id',
            'cliente_nombre'   => 'nullable|string|max:255',
            'responsable'      => 'nullable|string|max:255',
            'estacion'         => 'required|string|max:255',
            'fecha_hora'       => 'required|date',
            'productos'        => 'required|array',
            'productos.*'      => 'exists:productos,id',
            'cantidades'       => 'required|array',
            'cantidades.*'     => 'numeric|min:1',
            'metodo_pago'      => 'required|array',
            'monto_pago.*'     => 'numeric|min:0', // Validar cada monto como número decimal
            'referencia_pago'  => 'nullable|array',
            'tasa_dia'         => 'nullable|numeric|min:0', // Validar la tasa ingresada
        ]);

        if (empty($request->cliente_id) && empty($request->cliente_nombre)) {
            return back()->withErrors([
                'cliente_nombre' => 'Debe seleccionar un cliente o ingresar un nombre manual.',
            ])->withInput();
        }

        DB::beginTransaction();

        try {
            $tasa = $request->tasa_dia ?? 1; // Usar 1 como predeterminado si no se especifica
            $total = 0;
            $productos_array = [];

            foreach ($request->productos as $index => $producto_id) {
                $producto = Producto::findOrFail($producto_id);
                $cantidad = $request->cantidades[$index] ?? 1;
                $subtotal = $producto->precio_venta * $cantidad;

                $productos_array[] = [
                    'producto_id' => $producto_id,
                    'cantidad'    => $cantidad,
                    'precio'      => $producto->precio_venta,
                    'subtotal'    => $subtotal
                ];

                $total += $subtotal;
            }

            $metodos_pago_array = [];
            foreach ($request->metodo_pago as $index => $metodo) {
                $metodos_pago_array[] = [
                    'metodo'     => $metodo,
                    'monto'      => number_format((float)$request->monto_pago[$index], 2, '.', ''), // Formatear como decimal
                    'referencia' => $request->referencia_pago[$index] ?? null,
                ];
            }

            $cuenta->update([
                'cliente_id'         => $request->cliente_id,
                'cliente_nombre'     => $request->cliente_nombre,
                'responsable_pedido' => $request->responsable,
                'estacion'           => $request->estacion,
                'fecha_apertura'     => $request->fecha_hora,
                'total_estimado'     => $total,
                // 'tasa_dia'           => $request->tasa_dia ?? $cuenta->tasa_dia,
                // 'tasa_dia'           => $tasa, // Actualizar la tasa del día
                // 'tasa_dia'           => $request->tasa_dia ?? $cuenta->tasa_dia, // Usar el valor enviado o conservar el existente
                'tasa_dia'           => $request->tasa_dia ?? $cuenta->tasa_dia, // Usar el valor enviado o conservar el existente
                'productos'          => json_encode($productos_array),
                'metodos_pago'       => json_encode($metodos_pago_array),
            ]);


            DB::commit();
            return redirect()->route('cuentas.index', $cuenta->id)
                     ->with('success', 'La cuenta se actualizó correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'No se pudo actualizar la cuenta: ' . $e->getMessage()]);
        }
    }

    public function destroy(Cuenta $cuenta)
    {
        $cuenta->delete();
        return redirect()->route('cuentas.index')->with('success', 'Cuenta eliminada correctamente.');
    }

    public function cerrar(Cuenta $cuenta)
    {
        $cuenta->update([
            'fecha_cierre' => now(),
        ]);

        return redirect()->route('cuentas.index')->with('success', 'Cuenta cerrada correctamente.');
    }

    public function marcarPagada(Cuenta $cuenta)
    {
        $cuenta->pagada = true;
        $cuenta->fecha_cierre = now();
        $cuenta->save();

        return redirect()->route('cuentas.index')->with('success', 'Cuenta marcada como pagada.');
    }

    public function pagadas()
    {
        $cuentas = Cuenta::where('pagada', true)
            ->with('cliente')
            ->orderBy('fecha_apertura', 'desc')
            ->paginate(10);

        return view('cuentas.pagadas', compact('cuentas'));
    }

    public function buscar(Request $request)
    {
        $search = $request->input('term'); // Término que escribe el usuario

        $productos = Producto::where('nombre', 'LIKE', "%{$search}%")
            ->select('id', 'nombre')
            ->limit(20)
            ->get();

        $results = [];

        foreach ($productos as $producto) {
            $results[] = [
                'id' => $producto->id,
                'text' => $producto->nombre
            ];
        }

        return response()->json($results);

        $search = $request->input('search');

$cuentas = Cuenta::query()
    ->when($search, function ($query, $search) {
        $query->where('cliente_nombre', 'like', "%{$search}%")
              ->orWhere('responsable_pedido', 'like', "%{$search}%")
              ->orWhere('estacion', 'like', "%{$search}%");
    })
    ->orderByDesc('fecha_apertura')
    ->paginate(10)
    ->appends(['search' => $search]);

    }

public function exportarCuentasPagadas()
{
    $cuentasPagadas = Cuenta::where('pagada', true)->get();
    $productos_db = Producto::all()->keyBy('id'); // Cache de productos

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Encabezados
    $encabezados = ['ID', 'Cliente', 'Responsable', 'Estación', 'Total', 'Fecha', 'Método de Pago', 'Pedido Hecho'];
    $col = 'A';
    foreach ($encabezados as $titulo) {
        $sheet->setCellValue("{$col}1", $titulo);
        $col++;
    }

    // Estilo de encabezado
    $headerStyle = [
        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4F81BD']],
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
    ];
    $sheet->getStyle('A1:H1')->applyFromArray($headerStyle);

    $fila = 2;
    foreach ($cuentasPagadas as $cuenta) {
        $sheet->setCellValue("A{$fila}", $cuenta->id);
        $sheet->setCellValue("B{$fila}", $cuenta->cliente_nombre ?? 'Desconocido');
        $sheet->setCellValue("C{$fila}", $cuenta->responsable_pedido);
        $sheet->setCellValue("D{$fila}", $cuenta->estacion);
        $sheet->setCellValue("E{$fila}", $cuenta->total_estimado);
        $sheet->setCellValue("F{$fila}", $cuenta->fecha_cierre ?? 'No especificado');

        // Formatear métodos de pago
        $metodos_pago = json_decode($cuenta->metodos_pago, true);
        $metodos_pago_str = '';
        if ($metodos_pago) {
            foreach ($metodos_pago as $metodo) {
                $nombre_metodo = strtolower($metodo['metodo']);
                $monto = $metodo['monto'];
                $referencia = $metodo['referencia'] ?? null;
                $simbolo = '';

                if (str_contains($nombre_metodo, 'divisa')) {
                    $simbolo = '$';
                } elseif (str_contains($nombre_metodo, 'bolivar') || str_contains($nombre_metodo, 'pago') || str_contains($nombre_metodo, 'tarjeta')) {
                    $simbolo = 'Bs';
                } elseif (str_contains($nombre_metodo, 'euro')) {
                    $simbolo = '€';
                }

                $metodos_pago_str .= "{$metodo['metodo']} {$simbolo}{$monto}";
                if ($referencia) {
                    $metodos_pago_str .= " ref:{$referencia}";
                }
                $metodos_pago_str .= "\n"; // Añadir salto de línea entre métodos
            }
        } else {
            $metodos_pago_str = 'No especificado';
        }
        $sheet->setCellValue("G{$fila}", trim($metodos_pago_str));

        // Detalle del pedido
        $productos = json_decode($cuenta->productos, true);
        $detalle_pedido = '';
        if ($productos) {
            foreach ($productos as $p) {
                $producto = $productos_db->get($p['producto_id']);
                $nombre = $producto ? $producto->nombre : "ID: {$p['producto_id']}";
                $precio = number_format($p['precio'], 2, '.', '');
                $subtotal = number_format($p['subtotal'], 2, '.', '');
                $detalle_pedido .= "Cantidad: {$p['cantidad']} /// {$nombre} /// Precio Unitario: \${$precio} /// SUBTOTAL: \${$subtotal}\n\n";
            }
        } else {
            $detalle_pedido = 'No especificado';
        }
        $sheet->setCellValue("H{$fila}", trim($detalle_pedido));

        // Estilo de celdas con bordes y ajuste de texto
        $sheet->getStyle("A{$fila}:H{$fila}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle("A{$fila}:H{$fila}")->getAlignment()->setWrapText(true);

        $fila++;
    }

    // Autoajuste de columnas
    foreach (range('A', 'H') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    // Guardar y descargar
    $writer = new Xlsx($spreadsheet);
    $fileName = 'cuentas_pagadas_' . now()->format('Y-m-d') . '.xlsx';
    $tempFile = tempnam(sys_get_temp_dir(), $fileName);
    $writer->save($tempFile);

    return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
}

}