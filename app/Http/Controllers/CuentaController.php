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
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;




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
    // ... (tu código previo y funciones arriba) ...

    /**
     * Imprime una factura agrupando los productos iguales por cantidad.
     * Visualmente centrada como bloque y con márgenes superior, inferior, izquierda y derecha.
     * 
     * --- CONFIGURACIÓN DE MÁRGENES ---
     * Modifica los valores de $margen_lineas y $margen_espacios para ajustar los márgenes superior/inferior y lateral.
     */
    public function imprimirFactura($cuentaId)
    {

// PARA HACER QUE SE VEA CENTRADO EN LA IMPRESORA DE 58MM TENGO QUE COLOCAR $margen_lineas = 0; y $margen_espacios = 0;

        // === CONFIGURA AQUÍ TUS MÁRGENES ===
        $margen_lineas = 4;   // Número de líneas en blanco arriba/abajo del bloque (vertical)
        $margen_espacios = 9; // Número de espacios en blanco izquierda/derecha del bloque (horizontal)
        // Si tu impresora tiene 32 columnas efectivas y dejas 8 espacios en cada lado, el contenido real centrado tendrá 16 caracteres de ancho

        $cuenta = \App\Models\Cuenta::with('cliente')->findOrFail($cuentaId);
        $productos = collect(json_decode($cuenta->productos, true));
        $productos_db = \App\Models\Producto::all()->keyBy('id');

        // Agrupa productos
        $agrupados = $productos->groupBy('producto_id')->map(function ($items) use ($productos_db) {
            $primero = $items->first();
            $producto = $productos_db[$primero['producto_id']] ?? null;
            return [
                'nombre' => $primero['nombre'] ?? ($producto ? $producto->nombre : 'Producto'),
                'cantidad' => $items->sum('cantidad'),
                'precio' => $primero['precio'],
                'subtotal' => $items->sum(fn($item) => $item['precio'] * $item['cantidad']),
            ];
        });

        $total = $agrupados->sum('subtotal');
        $tasa = $cuenta->tasa_dia ?? 0;
        $total_bs = $tasa > 0 ? $total * $tasa : 0;
        $width = 32;

        // Datos
        $fecha = $cuenta->fecha_apertura instanceof \Carbon\Carbon
            ? $cuenta->fecha_apertura->format('d/m/Y H:i')
            : date('d/m/Y H:i', strtotime($cuenta->fecha_apertura));
        $cliente = $cuenta->cliente_nombre ?? ($cuenta->cliente->nombre ?? 'No especificado');
        $cajera = Auth::user()->name ?? '---';
        $responsable = $cuenta->responsable_pedido ?? '---';
        $area = $cuenta->estacion ?? '---';
        $idCuenta = $cuenta->id;

        // ---- Bloque visual centrado con márgenes ----
        $contenidoBloque = "";
        $contenidoBloque .= "▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓\n";
        $contenidoBloque .= "BODEGON RESTAURANTE\n";
        $contenidoBloque .= "OFUMMELLI\n";
        $contenidoBloque .= "▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒\n";
        $contenidoBloque .= $this->prettySeparator('═', $width);

        $contenidoBloque .= "Fecha: $fecha\n";
        $contenidoBloque .= "Cuenta #: $idCuenta\n";
        $contenidoBloque .= "Cliente: $cliente\n";
        $contenidoBloque .= "Cajer@: $cajera\n";
        $contenidoBloque .= "Responsable: $responsable\n";
        $contenidoBloque .= $this->prettySeparator('─', $width);

        $contenidoBloque .= ">> PEDIDO <<\n";
        $contenidoBloque .= $this->formatTableHeader(['Cant', 'Producto', 'Subtotal'], [5, 18, 9]);
        foreach ($agrupados as $prod) {
            $contenidoBloque .= $this->formatTableRow([
                $prod['cantidad'],
                mb_strimwidth($prod['nombre'], 0, 18, "..."),
                '$' . number_format($prod['subtotal'], 2)
            ], [5, 18, 9]);
        }
        $contenidoBloque .= $this->prettySeparator('─', $width);

        $contenidoBloque .= "TOTAL: $" . number_format($total, 2) . " (Bs. " . number_format($total_bs, 2, ',', '.') . ")\n";
        $contenidoBloque .= "Tasa usada: " . number_format($tasa, 2, ',', '.') . " Bs/USD\n";
        $contenidoBloque .= "Área: " . $area . "\n";
        $contenidoBloque .= $this->prettySeparator('═', $width);
        $contenidoBloque .= "¡Gracias por su visita!\n";
        $contenidoBloque .= "▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓\n";

        // --- Centra el bloque y agrega márgenes configurables ---
        $factura = "";
        $factura .= str_repeat("\n", $margen_lineas); // margen superior

        // Centrado y margen lateral configurable
        $internalWidth = $width - (2 * $margen_espacios);
        $espacios = str_repeat(' ', $margen_espacios);
        $lineas = explode("\n", $contenidoBloque);

        foreach ($lineas as $line) {
            if (trim($line) === "") {
                $factura .= "\n";
            } else {
                $factura .= $espacios . rtrim($this->centerText($line, $internalWidth), "\n") . "\n";
            }
        }

        $factura .= str_repeat("\n", $margen_lineas); // margen inferior

        // ---- Imprimir ----
        $area_trabajo = session('area_trabajo') ?? 'facturacion';
        $config = $this->getImpresoraConfig($area_trabajo);
        $usb = $config['usb'];
        $printer = new Printer(new WindowsPrintConnector($usb['win_name']));
        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text($factura);
        $printer->cut();
        $printer->close();

        return back()->with('success', 'Factura impresa correctamente.');
    }

    // FUNCIONES AUXILIARES

    private function centerText($text, $width = 32) {
        $text = trim($text);
        $len = mb_strlen($text, 'UTF-8');
        if ($len >= $width) return $text . "\n";
        $spaces = floor(($width - $len) / 2);
        return str_repeat(' ', $spaces) . $text . "\n";
    }
    private function leftText($text, $width = 32) {
        return $text . "\n";
    }
    private function prettySeparator($char = '═', $width = 32) {
        return str_repeat($char, $width) . "\n";
    }
    private function formatTableHeader($headers, $widths) {
        $line = '';
        foreach ($headers as $i => $header) {
            $line .= str_pad($header, $widths[$i], ' ', STR_PAD_BOTH);
        }
        return $line . "\n";
    }
    private function formatTableRow($cols, $widths) {
        $line = '';
        foreach ($cols as $i => $col) {
            $line .= str_pad($col, $widths[$i], ' ', STR_PAD_BOTH);
        }
        return $line . "\n";
    }
    private function getImpresoraConfig($area)
    {
        $config = config("impresoras.$area");
        if (!$config) {
            throw new \Exception("No hay configuración de impresora para el área: $area");
        }
        return $config;
    }


public function resumenPorArea(Request $request)
{
    // Control de acceso
    if (!in_array(Auth::user()->role, ['Admin', 'Supervisor', 'Cajero'])) {
        abort(403, 'No tienes permiso para acceder a esta sección.');
    }

    $fechaInicio = $request->input('fecha_inicio');
    $fechaFin = $request->input('fecha_fin');

    // Consulta de cuentas pagadas con filtro de fecha opcional
    $cuentas = \App\Models\Cuenta::where('pagada', true)
        ->when($fechaInicio, function($q) use ($fechaInicio) {
            return $q->where('fecha_apertura', '>=', $fechaInicio);
        })
        ->when($fechaFin, function($q) use ($fechaFin) {
            return $q->where('fecha_apertura', '<=', $fechaFin);
        })
        ->get();

    // Cargar catálogo de productos (para obtener nombre y área)
    $productos_catalogo = \App\Models\Producto::all()->keyBy('id');

    // Estructura: [area_id][producto_id] => [nombre, categoria, unidad_medida, precio_venta, cantidad, subtotal]
    $resumen = [];

    foreach ($cuentas as $cuenta) {
        $items = json_decode($cuenta->productos, true) ?? [];
        foreach ($items as $item) {
            $producto = $productos_catalogo[$item['producto_id']] ?? null;
            if ($producto) {
                $area_id = $producto->area_id;
                if (!isset($resumen[$area_id][$producto->id])) {
                    $resumen[$area_id][$producto->id] = [
                        'id' => $producto->id,
                        'nombre' => $producto->nombre,
                        'categoria' => $producto->categoria ?? 'N/A',
                        'unidad_medida' => $producto->unidad_medida ?? 'N/A',
                        'precio_venta' => $producto->precio_venta ?? 0,
                        'cantidad' => 0,
                        'subtotal' => 0,
                    ];
                }
                $resumen[$area_id][$producto->id]['cantidad'] += $item['cantidad'];
                $resumen[$area_id][$producto->id]['subtotal'] += $item['subtotal'];
            }
        }
    }

    // Mapeo de área_id a nombre (ajusta según tu modelo o tabla de áreas)
    $areas = [
        1 => 'Carne en Vara',
        2 => 'Cachapa',
        3 => 'Barra',
        4 => 'Cocina',
    ];

    // Preparar datos para los gráficos (Chart.js)
    $jsonGraficos = [];
    foreach ($areas as $area_id => $area_nombre) {
        $nombres = [];
        $cantidades = [];
        $ventas = [];
        if (!empty($resumen[$area_id])) {
            foreach ($resumen[$area_id] as $prod) {
                $nombres[] = $prod['nombre'];
                $cantidades[] = $prod['cantidad'];
                $ventas[] = $prod['subtotal'];
            }
        }
        $jsonGraficos[$area_id] = [
            'nombres' => $nombres,
            'cantidades' => $cantidades,
            'ventas' => $ventas,
        ];
    }

    // Exportar a Excel si lo solicita el usuario
    if ($request->has('export')) {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $row = 1;
        foreach ($areas as $area_id => $area_nombre) {
            $sheet->setCellValue("A{$row}", strtoupper($area_nombre));
            $row++;
            $sheet->setCellValue("A{$row}", 'Producto');
            $sheet->setCellValue("B{$row}", 'Cantidad');
            $sheet->setCellValue("C{$row}", 'Total $');
            $row++;
            if (!empty($resumen[$area_id])) {
                foreach ($resumen[$area_id] as $prod) {
                    $sheet->setCellValue("A{$row}", $prod['nombre']);
                    $sheet->setCellValue("B{$row}", $prod['cantidad']);
                    $sheet->setCellValue("C{$row}", $prod['subtotal']);
                    $row++;
                }
            }
            $row++; // Espacio entre áreas
        }
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = 'resumen_ventas_area_' . now()->format('Ymd_His') . '.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), $filename);
        $writer->save($tempFile);
        return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
    }

    return view('cuentas.resumen_area', [
        'resumen' => $resumen,
        'areas' => $areas,
        'fechaInicio' => $fechaInicio,
        'fechaFin' => $fechaFin,
        'jsonGraficos' => $jsonGraficos,
    ]);
}

}