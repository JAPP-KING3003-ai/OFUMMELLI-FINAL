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
    if (!in_array(Auth::user()->role, ['Admin', 'Supervisor', 'Cajero'])) {
        abort(403, 'No tienes permiso para acceder a esta sección.');
    }

    $search = $request->get('search');
    $sort = $request->input('sort', 'updated_at'); // Por defecto 'updated_at'
    $direction = $request->input('direction', 'desc');

    $sortable = ['id', 'cliente_nombre', 'responsable_pedido', 'estacion', 'total_estimado', 'fecha_apertura', 'updated_at'];

    $query = Cuenta::where('pagada', false)
        ->when($search, function ($query, $search) {
            $query->where('cliente_nombre', 'like', "%$search%")
                  ->orWhere('responsable_pedido', 'like', "%$search%")
                  ->orWhere('estacion', 'like', "%$search%");
        });

    // Solo permitir ordenar por columnas válidas
    if (in_array($sort, $sortable)) {
        $query->orderBy($sort, $direction);
    } else {
        $query->orderBy('updated_at', 'desc');
    }

    $cuentas = $query->paginate(10)->appends($request->all());

    return view('cuentas.index', compact('cuentas', 'sort', 'direction'));
}


    public function create()
{
    // Validación: Solo Administradores y Cajeros pueden acceder
    if (!in_array(Auth::user()->role, ['Admin', 'Cajero'])) {
        abort(403, 'No tienes permiso para acceder a esta sección.');
    }

    $productos = Producto::orderBy('nombre')->get();
    $clientes = Cliente::all();

    // Obtener la tasa de cambio actual desde la configuración global (si existe)
    $tasaActual = DB::table('config')->where('key', 'tasa_cambio')->value('value') ?? null;

    // Incluir el área (area_id) en los detalles de los productos enviados al frontend
    $productosJS = $productos->mapWithKeys(function ($producto) {
        return [$producto->id => [
            'nombre' => $producto->nombre,
            'precio' => (float) $producto->precio_venta,
            'area_id' => $producto->area_id, // Incluir el area_id
        ]];
    });

    return view('cuentas.create', compact('productos', 'productosJS', 'clientes', 'tasaActual'));
}

public function store(Request $request)
{
    // dd($request->all());
    
    // Validación: Solo Administradores y Cajeros pueden almacenar cuentas
    if (!in_array(Auth::user()->role, ['Admin', 'Cajero'])) {
        abort(403, 'No tienes permiso para realizar esta acción.');
    }

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
        'metodo_pago'      => 'nullable|array',
        'monto_pago.*'     => 'numeric|min:0',
        'referencia_pago'  => 'nullable|array',
        'tasa_dia'         => 'nullable|numeric|min:0',
        'barrero'          => 'nullable|string|max:255', // Validar el campo barrero
    ]);

    if (empty($request->cliente_id) && empty($request->cliente_nombre)) {
        return back()->withErrors([
            'cliente_nombre' => 'Debe seleccionar un cliente o ingresar un nombre manual.',
        ])->withInput();
    }

    DB::beginTransaction();

    try {
        $productos_array = [];
        $total = 0;

        foreach ($request->productos as $index => $producto_id) {
            $producto = Producto::select('id', 'nombre', 'precio_venta', 'area_id')->findOrFail($producto_id);
            $cantidad = $request->cantidades[$index] ?? 1;
            $subtotal = $producto->precio_venta * $cantidad;

            // Utilizar el area_id del producto recibido o el área desde la base de datos
            $area_id = $request->areas[$index];

            $id_unico = $request->id_unico[$index] ?? uniqid('prod-');

            $productos_array[] = [
                'id_unico'   => $id_unico,
                'producto_id' => $producto_id,
                'cantidad'    => $cantidad,
                'precio'      => $producto->precio_venta,
                'subtotal'    => $subtotal,
                'area_id'     => $area_id, // Asegurar que el área asociada se incluye
            ];

            $total += $subtotal;
        }

        // Procesar métodos de pago solo si existen
        $metodos_pago_array = [];
        if ($request->has('metodo_pago')) {
            foreach ($request->metodo_pago as $index => $metodo) {
                $metodos_pago_array[] = [
                    'metodo'     => $metodo,
                    'monto'      => number_format((float) $request->monto_pago[$index], 2, '.', ''),
                    'referencia' => $request->referencia_pago[$index] ?? null,
                ];
            }
        }

        $cuenta = Cuenta::create([
            'cliente_id'         => $request->cliente_id,
            'cliente_nombre'     => $request->cliente_nombre,
            'usuario_id'         => Auth::id(),
            'cajera'             => Auth::user()->name, // Guardar el nombre de la cajera logueada
            'barrero'            => $request->barrero, // Guardar el nombre del barrero
            'responsable_pedido' => $request->responsable,
            'estacion'           => $request->estacion,
            'fecha_apertura'     => $request->fecha_hora,
            'total_estimado'     => $total,
            'productos'          => json_encode($productos_array), // Guardar productos como JSON
            'metodos_pago'       => json_encode($metodos_pago_array), // Guardar métodos de pago como JSON
            'tasa_dia'           => $request->tasa_dia ?? 1,
        ]);

        DB::commit();
        return redirect()->route('cuentas.index')->with('success', 'Cuenta registrada exitosamente.');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withErrors(['error' => 'Error al registrar la cuenta: ' . $e->getMessage()]);
    }
}

    public function show($id)
    {
        // Validación: Todos los roles pueden ver cuentas
        if (!in_array(Auth::user()->role, ['Admin', 'Supervisor', 'Cajero'])) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        $cuenta = Cuenta::with('cliente')->findOrFail($id);

        $cuenta->productos = json_decode($cuenta->productos, true);
        $cuenta->metodos_pago = json_decode($cuenta->metodos_pago, true);

        $tasaUsada = $cuenta->tasa_dia ?? null; // Asegúrate de que `tasa_dia` esté almacenada en la cuenta

            // Asegurarse de que el vuelto está disponible
        if (!isset($cuenta->vuelto)) {
            $cuenta->vuelto = 0; // Valor por defecto en caso de que no exista
        }

        $productosIds = array_column($cuenta->productos, 'producto_id');
        $productos = Producto::whereIn('id', $productosIds)->get()->keyBy('id');

        // dd($cuenta);

        return view('cuentas.show', compact('cuenta', 'productos', 'tasaUsada'));
    }

    public function edit($id)
{
    $cuenta = Cuenta::findOrFail($id);
    $clientes = Cliente::all();
    $productos = Producto::orderBy('nombre')->get();

    // Decodificar los productos seleccionados almacenados en la cuenta
     $productosSeleccionados = collect(json_decode($cuenta->productos, true) ?? [])
        ->map(function ($producto, $index) {
            return [
                'id_unico'    => $producto['id_unico'] ?? ('prod-'.$producto['producto_id'].'-'.uniqid()), // <-- SOLO ASÍ
                'producto_id' => $producto['producto_id'],
                'cantidad' => $producto['cantidad'],
                'area_id' => $producto['area_id'] ?? null, // Asegurarse de que "area_id" esté definido
                'printed_at' => $producto['printed_at'] ?? null,
                'nombre'      => $producto['nombre'] ?? null,
            ];
        })
        ->toArray();

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

    $cuenta = Cuenta::with(['productos' => function ($query) {
        $query->select('id', 'nombre', 'area_id', 'printed_at'); // Asegúrate de incluir printed_at
    }])->findOrFail($id);

    return view('cuentas.edit', compact('cuenta', 'productos', 'productosSeleccionados'));
}

    public function update(Request $request, Cuenta $cuenta)
{

    // Validaciones del Request
    $request->validate([
        // 'cliente_id'       => 'nullable|exists:clientes,id',
        // 'cliente_nombre'   => 'nullable|string|max:255',
        // 'responsable'      => 'nullable|string|max:255',
        // 'estacion'         => 'required|string|max:255',
        // 'fecha_hora'       => 'required|date',
        // 'productos'        => 'required|array',
        // 'productos.*'      => 'exists:productos,id',
        // 'cantidades'       => 'required|array',
        // 'cantidades.*'     => 'numeric|min:1',
        // 'metodo_pago'      => 'required|array',
        // 'monto_pago.*'     => 'numeric|min:0', // Validar cada monto como número decimal
        // 'referencia_pago'  => 'nullable|array',
        // 'total_pagado'     => 'nullable|numeric|min:0', // Validar que total_pagado sea un número válido
        // 'vuelto'           => 'nullable|numeric|min:0', // Validar que vuelto sea un número válido
        // 'cuenta_pago_movil.*' => 'nullable|string',
        // 'banco_punto_venta.*' => 'nullable|string',
        // 'cuenta_casa_autorizado.*' => 'nullable|string',
        // 'tasa_dia'         => 'nullable|numeric|min:0', // Validar la tasa ingresada
        // 'barrero' => 'nullable|string|max:255', // Validar el barrero

        'cliente_id'       => 'nullable|exists:clientes,id',
        'cliente_nombre'   => 'nullable|string|max:255',
        'responsable'      => 'nullable|string|max:255',
        'estacion'         => 'required|string|max:255',
        'fecha_hora'       => 'required|date',
        'productos'        => 'required|array',
        'productos.*'      => 'exists:productos,id',
        'cantidades'       => 'required|array',
        'cantidades.*'     => 'numeric|min:1',
        'areas'            => 'required|array',
        'areas.*'          => 'nullable|string',
        // Métodos de pago ya no son obligatorios
        'metodo_pago'      => 'nullable|array',
        'monto_pago.*'     => 'nullable|numeric|min:0',
        'referencia_pago'  => 'nullable|array',
        'total_pagado'     => 'nullable|numeric|min:0',
        'vuelto'           => 'nullable|numeric|min:0',
        'tasa_dia'         => 'nullable|numeric|min:0',
        'barrero'          => 'nullable|string|max:255',
        'cuenta_pago_movil.*' => 'nullable|string',
        'banco_punto_venta.*' => 'nullable|string',
        'cuenta_casa_autorizado.*' => 'nullable|string',

    ]);

    // Validar que se haya seleccionado un cliente o ingresado un nombre manual
    if (empty($request->cliente_id) && empty($request->cliente_nombre)) {
        return back()->withErrors([
            'cliente_nombre' => 'Debe seleccionar un cliente o ingresar un nombre manual.',
        ])->withInput();
    }

    // Iniciar una Transacción
    DB::beginTransaction();

    try {


        // Decodificar los productos existentes
        $productosExistentes = json_decode($cuenta->productos, true) ?? [];
        $productosExistentesIndexed = collect($productosExistentes)->keyBy('producto_id');


        // Calcular el Total Estimado de la Cuenta
        $totalEstimado = 0; // Inicializar el total estimado
        $productos_array = []; // Arreglo para almacenar los productos procesados

        foreach ($request->productos as $index => $producto_id) {
            $producto = Producto::findOrFail($producto_id);
            $cantidad = $request->cantidades[$index] ?? 1;
            $area_id = $request->areas[$index] ?? $producto->area_id;
            $subtotal = $producto->precio_venta * $cantidad;
            $id_unico = $request->id_unico[$index] ?? uniqid('prod-');

            $productos_array[] = [
                'id_unico'   => $id_unico,
                'producto_id'=> $producto_id,
                'nombre'     => $producto->nombre,
                'cantidad'   => $cantidad,
                'precio'     => $producto->precio_venta,
                'subtotal'   => $subtotal,
                'area_id'    => $area_id,
                'printed_at' => $request->printed_at[$index] ?? null,
    ];

            $totalEstimado += $subtotal; // Acumular el subtotal en el total estimado
        }

        // Luego, guarda los productos como JSON en la cuenta

        // Calcular el Total Pagado y el Vuelto
        $totalPagado = array_sum($request->monto_pago ?? []); // Sumar todos los montos de métodos de pago
        $vuelto = $totalPagado > $totalEstimado ? $totalPagado - $totalEstimado : 0; // Calcular el vuelto si aplica

        // Procesar los Métodos de Pago
        $metodos_pago_array = [];
        $metodo_pago = $request->metodo_pago ?? []; // Asegurar que sea un array
        foreach ($metodo_pago as $index => $metodo) {
            $metodos_pago_array[] = [
                'metodo'     => $metodo,
                'monto'      => number_format((float) ($request->monto_pago[$index] ?? 0), 2, '.', ''), // Formatear como decimal
                'referencia' => $request->referencia_pago[$index] ?? null,
                'cuenta'     => $request->cuenta_pago_movil[$index] ?? null, // Para Pago Móvil
                'banco'      => $request->banco_punto_venta[$index] ?? null, // Para Punto de Venta
                'autorizado_por' => $request->cuenta_casa_autorizado[$index] ?? null, // Para Cuenta Por la Casa
            ];
        }

        // Actualizar los Datos de la Cuenta
        $cuenta->update([
            'cliente_id'         => $request->cliente_id,
            'cliente_nombre'     => $request->cliente_nombre,
            'responsable_pedido' => $request->responsable,
            'estacion'           => $request->estacion,
            'fecha_apertura'     => $request->fecha_hora,
            'total_estimado'     => $totalEstimado, // Actualizar el total estimado
            'tasa_dia'           => $request->tasa_dia ?? $cuenta->tasa_dia, // Usar la tasa enviada o la existente
            'productos'          => json_encode($productos_array), // Guardar los productos seleccionados
            'metodos_pago'       => !empty($metodos_pago_array) ? json_encode($metodos_pago_array) : null, // Guardar los métodos de pago
            'total_pagado'       => $totalPagado, // Guardar el total pagado
            'vuelto'             => $vuelto, // Guardar el vuelto calculado
            'barrero' => $request->barrero, // Actualizar el nombre del barrero
        ]);

        // Confirmar la Transacción
        DB::commit();
        

        // Redirigir al índice de cuentas con mensaje de éxito
        return redirect()->route('cuentas.index')
                 ->with('success', 'La cuenta se actualizó correctamente.');
    } catch (\Exception $e) {
        // Revertir la Transacción en caso de error
        DB::rollBack();
        return back()->withErrors(['error' => 'No se pudo actualizar la cuenta: ' . $e->getMessage()]);
    }
}

    public function destroy(Cuenta $cuenta)
    {
        // Validación: Solo Administradores pueden eliminar cuentas
        if (Auth::user()->role !== 'Admin') {
            abort(403, 'No tienes permiso para eliminar esta cuenta.');
        }

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

    // Encabezados mejorados
    $encabezados = [
        'ID', 'Cliente', 'Responsable', 'Cajera', 'Barrero', 'Estación',
        'Total', 'Total Pagado', 'Vuelto', 'Fecha de Apertura', 'Fecha de Cierre',
        'Métodos de Pago', 'Detalle del Pedido'
    ];

    // Poner encabezados y aplicar estilos
    $col = 'A';
    foreach ($encabezados as $i => $titulo) {
        $sheet->setCellValue("{$col}1", $titulo);
        $col++;
    }

    $lastCol = chr(ord('A') + count($encabezados) - 1);

    // Estilo de encabezado
    $headerStyle = [
        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 12],
        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0070C0']],
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
    ];
    $sheet->getStyle("A1:{$lastCol}1")->applyFromArray($headerStyle);
    $sheet->getRowDimension(1)->setRowHeight(30);

    // Cuerpo de la tabla
    $fila = 2;
    foreach ($cuentasPagadas as $cuenta) {
        // Decodificar productos y métodos de pago
        $productos = json_decode($cuenta->productos, true);
        $metodos_pago = json_decode($cuenta->metodos_pago, true);

        // Cálculo de totales y vuelto (igual que en la vista)
        $totalPagadoEnDolares = 0;
        $propinasEnDolares = 0;

        if ($metodos_pago) {
            foreach ($metodos_pago as $pago) {
                $monto = $pago['monto'] ?? 0;

                if (in_array($pago['metodo'], ['divisas', 'zelle', 'tarjeta_credito_dolares'])) {
                    $totalPagadoEnDolares += $monto;
                } elseif (in_array($pago['metodo'], ['pago_movil', 'bs_efectivo', 'debito', 'punto_venta', 'tarjeta_credito_bolivares'])) {
                    $totalPagadoEnDolares += $cuenta->tasa_dia > 0 ? $monto / $cuenta->tasa_dia : 0;
                } elseif ($pago['metodo'] === 'euros') {
                    $totalPagadoEnDolares += $monto * 1.1;
                }

                // Propinas
                if ($pago['metodo'] === 'propina_divisas') {
                    $propinasEnDolares += $monto;
                } elseif ($pago['metodo'] === 'propina_bolivares') {
                    $propinasEnDolares += $cuenta->tasa_dia > 0 ? $monto / $cuenta->tasa_dia : 0;
                }
            }
        }
        $pagosSinPropinas = $totalPagadoEnDolares - $propinasEnDolares;
        $vuelto = max(0, $pagosSinPropinas - $cuenta->total_estimado);

        // Métodos de pago en string
        $metodos_pago_str = '';
        if ($metodos_pago) {
            foreach ($metodos_pago as $metodo) {
                $nombre_metodo = strtolower($metodo['metodo']);
                $monto = $metodo['monto'];
                $referencia = $metodo['referencia'] ?? null;
                $simbolo = '';
                if (str_contains($nombre_metodo, 'divisa')) $simbolo = '$';
                elseif (str_contains($nombre_metodo, 'bolivar') || str_contains($nombre_metodo, 'pago') || str_contains($nombre_metodo, 'tarjeta')) $simbolo = 'Bs';
                elseif (str_contains($nombre_metodo, 'euro')) $simbolo = '€';
                $metodos_pago_str .= "{$metodo['metodo']} {$simbolo}{$monto}";
                if ($referencia) $metodos_pago_str .= " ref:{$referencia}";
                $metodos_pago_str .= "\n";
            }
        } else {
            $metodos_pago_str = 'No especificado';
        }

        // Detalle del pedido
        $detalle_pedido = '';
        if ($productos) {
            foreach ($productos as $p) {
                $producto = $productos_db->get($p['producto_id']);
                $nombre = $producto ? $producto->nombre : "ID: {$p['producto_id']}";
                $precio = number_format($p['precio'], 2, '.', '');
                $subtotal = number_format($p['subtotal'], 2, '.', '');
                $detalle_pedido .= "Cantidad: {$p['cantidad']} - {$nombre} - Unit: \${$precio} - Subtotal: \${$subtotal}\n";
            }
        } else {
            $detalle_pedido = 'No especificado';
        }

        // Escribir fila con formato bonito
        $sheet->setCellValue("A{$fila}", $cuenta->id);
        $sheet->setCellValue("B{$fila}", $cuenta->cliente_nombre ?? 'Desconocido');
        $sheet->setCellValue("C{$fila}", $cuenta->responsable_pedido ?? 'No especificado');
        $sheet->setCellValue("D{$fila}", $cuenta->cajera ?? 'No especificado');
        $sheet->setCellValue("E{$fila}", $cuenta->barrero ?? 'No especificado');
        $sheet->setCellValue("F{$fila}", $cuenta->estacion ?? 'No especificada');
        $sheet->setCellValue("G{$fila}", '$' . number_format($cuenta->total_estimado, 2));
        $sheet->setCellValue("H{$fila}", '$' . number_format($totalPagadoEnDolares, 2));
        $sheet->setCellValue("I{$fila}", '$' . number_format($vuelto, 2));
        $sheet->setCellValue("J{$fila}", $cuenta->fecha_apertura ? $cuenta->fecha_apertura->format('d/m/Y H:i') : '-');
        $sheet->setCellValue("K{$fila}", $cuenta->fecha_cierre ? $cuenta->fecha_cierre->format('d/m/Y H:i') : '-');
        $sheet->setCellValue("L{$fila}", trim($metodos_pago_str));
        $sheet->setCellValue("M{$fila}", trim($detalle_pedido));

        // Estilos de fila
        $sheet->getStyle("A{$fila}:{$lastCol}{$fila}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle("A{$fila}:{$lastCol}{$fila}")->getAlignment()->setWrapText(true);
        $sheet->getStyle("A{$fila}:{$lastCol}{$fila}")->getAlignment()->setVertical(Alignment::VERTICAL_TOP);

        $fila++;
    }

    // Autoajuste de columnas y ancho mínimo para detalles
    foreach (range('A', $lastCol) as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }
    $sheet->getColumnDimension('M')->setWidth(45);

    // Fondo alterno para filas
    for ($row = 2; $row < $fila; $row++) {
        if ($row % 2 === 0) {
            $sheet->getStyle("A{$row}:{$lastCol}{$row}")->applyFromArray([
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E6F2FF']
                ]
            ]);
        }
    }

    // Congelar encabezado
    $sheet->freezePane('A2');

    // Guardar y descargar
    $writer = new Xlsx($spreadsheet);
    $fileName = 'CUENTAS_PAGADAS_' . now()->format('Y-m-d') . '.xlsx';
    $tempFile = tempnam(sys_get_temp_dir(), $fileName);
    $writer->save($tempFile);

    return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
}

}