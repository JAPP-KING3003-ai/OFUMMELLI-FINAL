<?php

namespace App\Http\Controllers;

use App\Models\Inventario;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use App\Models\InventarioProducto;
use App\Models\Lote;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log; 
use Illuminate\Support\Facades\Auth;



class InventarioController extends Controller
{
    /**
     * Mostrar listado de inventarios.
     */
public function index(Request $request)
{
    $search = $request->input('search');
    $sort = $request->input('sort', 'updated_at'); // Cambia a updated_at por defecto
    $direction = $request->input('direction', 'desc');

    $query = \App\Models\InventarioProducto::with('lotes')
        ->when($search, function ($query, $search) {
            $query->where('nombre', 'like', "%{$search}%")
                ->orWhere('codigo', 'like', "%{$search}%");
        });

    // Permite ordenar por columnas válidas, incluyendo updated_at
    $sortable = ['codigo', 'nombre', 'created_at', 'updated_at'];
    if (in_array($sort, $sortable)) {
        $query->orderBy($sort, $direction);
    } else {
        $query->orderBy('updated_at', 'desc');
    }

    $productos = $query->paginate(10)->appends($request->all());

    return view('inventarios.index', compact('productos', 'sort', 'direction'));
}

    /**
     * Exportar datos de inventarios a Excel.
     */

public function exportarExcel()
{
    $productos = \App\Models\InventarioProducto::with(['lotes.movimientos'])->get();

    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

    // ===========================
    // HOJA 1: INVENTARIO DETALLADO
    // ===========================
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('INVENTARIO');

    $encabezadosInv = [
        'Código',
        'Nombre del Producto',
        'Cantidad Inicial',
        'Cantidad Actual',
        'Cantidad Total',
        'Total Gastado',
        'Precio Promedio Ponderado',
        'Último Precio Entrada',
    ];
    $col = 'A';
    foreach ($encabezadosInv as $titulo) {
        $sheet->setCellValue("{$col}1", $titulo);
        $col++;
    }
    $sheet->getStyle("A1:H1")->applyFromArray([
        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 12],
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => ['rgb' => '00B050'],
        ],
        'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
        'borders' => [
            'allBorders' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                'color' => ['rgb' => '000000'],
            ],
        ],
    ]);
    $sheet->setAutoFilter("A1:H1");

    $fila = 2;
    foreach ($productos as $producto) {
        $lotes = $producto->lotes;
        $cantidad_inicial = $lotes->sum('cantidad_inicial');
        $cantidad_actual = $lotes->sum('cantidad_actual');
        $cantidad_total = $cantidad_actual; // Si quieres que sea la suma actual de todos los lotes
        $total_gastado = $lotes->sum(fn($l) => $l->movimientos->where('tipo', 'entrada')->sum(fn($m) => $m->cantidad * $l->precio_costo));
        // Precio promedio ponderado (solo entradas)
        $total_entradas = $lotes->sum(fn($l) => $l->movimientos->where('tipo', 'entrada')->sum('cantidad'));
        $precio_promedio_ponderado = $total_entradas > 0 ? $total_gastado / $total_entradas : 0;
        // Último precio de entrada registrado (última entrada de cualquier lote)
        $ultimo_precio_entrada = $lotes->flatMap(function ($lote) {
            return $lote->movimientos->where('tipo', 'entrada')->map(function ($m) use ($lote) {
                return [
                    'fecha' => $m->created_at,
                    'precio' => $lote->precio_costo
                ];
            });
        })->sortByDesc('fecha')->first();
        $ultimo_precio_valor = $ultimo_precio_entrada ? number_format($ultimo_precio_entrada['precio'], 2) : '—';

        $sheet->setCellValue("A{$fila}", $producto->codigo);
        $sheet->setCellValue("B{$fila}", $producto->nombre);
        $sheet->setCellValue("C{$fila}", $cantidad_inicial);
        $sheet->setCellValue("D{$fila}", $cantidad_actual);
        $sheet->setCellValue("E{$fila}", $cantidad_total);
        $sheet->setCellValue("F{$fila}", number_format($total_gastado, 2));
        $sheet->setCellValue("G{$fila}", number_format($precio_promedio_ponderado, 2));
        $sheet->setCellValue("H{$fila}", $ultimo_precio_valor);

        if ($fila % 2 == 0) {
            $sheet->getStyle("A{$fila}:H{$fila}")->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setRGB('F6F6F6');
        }
        $sheet->getStyle("A{$fila}:H{$fila}")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $fila++;
    }
    $sheet->getStyle("A1:H" . ($fila - 1))->applyFromArray([
        'borders' => [
            'allBorders' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                'color' => ['rgb' => 'CCCCCC'],
            ],
        ],
    ]);
    foreach (range('A', 'H') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    // ========================================
    // HOJA 2: LOTES Y MOVIMIENTOS (con merge para nombre/código)
    // ========================================
    $sheet2 = $spreadsheet->createSheet();
    $sheet2->setTitle('LOTES Y MOVIMIENTOS');

    $encabezadosLotes = [
        'Código Producto',
        'Nombre Producto',
        'Lote #',
        'Fecha Lote',
        'Inicial Lote',
        'Actual Lote',
        'Precio Lote',
        'Entradas',
        'Salidas',
    ];
    $col = 'A';
    foreach ($encabezadosLotes as $titulo) {
        $sheet2->setCellValue("{$col}1", $titulo);
        $col++;
    }
    $sheet2->getStyle("A1:I1")->applyFromArray([
        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 12],
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => ['rgb' => '00B050'],
        ],
        'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
        'borders' => [
            'allBorders' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                'color' => ['rgb' => '000000'],
            ],
        ],
    ]);
    $sheet2->setAutoFilter("A1:I1");

    $fila2 = 2;
    foreach ($productos as $producto) {
        $lotes = $producto->lotes;
        $mergeRowStart = $fila2;
        $mergeRows = count($lotes);

        foreach ($lotes as $i => $lote) {
            $entradas = $lote->movimientos->where('tipo', 'entrada')->map(fn($m) =>
                "Cantidad: {$m->cantidad} => " . \Carbon\Carbon::parse($m->created_at)->format('d/m/Y H:i')
            )->implode("\n");
            $salidas = $lote->movimientos->where('tipo', 'salida')->map(fn($m) =>
                "Cantidad: {$m->cantidad} => " . \Carbon\Carbon::parse($m->created_at)->format('d/m/Y H:i')
            )->implode("\n");

            // Solo la primera fila muestra el producto, luego se hace merge
            $sheet2->setCellValue("A{$fila2}", $i == 0 ? $producto->codigo : '');
            $sheet2->setCellValue("B{$fila2}", $i == 0 ? $producto->nombre : '');
            $sheet2->setCellValue("C{$fila2}", $lote->id);
            $sheet2->setCellValue("D{$fila2}", \Carbon\Carbon::parse($lote->fecha_ingreso)->format('d/m/Y'));
            $sheet2->setCellValue("E{$fila2}", $lote->cantidad_inicial);
            $sheet2->setCellValue("F{$fila2}", $lote->cantidad_actual);
            $sheet2->setCellValue("G{$fila2}", number_format($lote->precio_costo,2));
            $sheet2->setCellValue("H{$fila2}", $entradas ?: '—');
            $sheet2->setCellValue("I{$fila2}", $salidas ?: '—');

            if ($fila2 % 2 == 0) {
                $sheet2->getStyle("A{$fila2}:I{$fila2}")->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('F6F6F6');
            }
            $sheet2->getStyle("A{$fila2}:I{$fila2}")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $sheet2->getStyle("H{$fila2}:I{$fila2}")->getAlignment()->setWrapText(true);
            $fila2++;
        }
        // Merge para código y nombre si hay más de un lote
        if ($mergeRows > 1) {
            $sheet2->mergeCells("A{$mergeRowStart}:A" . ($mergeRowStart + $mergeRows - 1));
            $sheet2->mergeCells("B{$mergeRowStart}:B" . ($mergeRowStart + $mergeRows - 1));
            $sheet2->getStyle("A{$mergeRowStart}:B" . ($mergeRowStart + $mergeRows - 1))->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $sheet2->getStyle("A{$mergeRowStart}:B" . ($mergeRowStart + $mergeRows - 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        }
    }
    $sheet2->getStyle("A1:I" . ($fila2 - 1))->applyFromArray([
        'borders' => [
            'allBorders' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                'color' => ['rgb' => 'CCCCCC'],
            ],
        ],
    ]);
    foreach (range('A', 'I') as $col) {
        $sheet2->getColumnDimension($col)->setAutoSize(true);
    }

    // =======================================
    // Descargar archivo
    // =======================================
    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    $fileName = 'inventarios_' . now()->format('Y-m-d') . '.xlsx';
    $tempFile = tempnam(sys_get_temp_dir(), $fileName);
    $writer->save($tempFile);

    return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
}

/**
 * Mostrar formulario para registrar entrada de inventario.
 *
 * @param int $id
 * @return \Illuminate\View\View
 */
public function entrada($id)
{
    Log::info('ID recibido en entrada: ' . $id);

    // Buscar el inventario por su ID y cargar la relación 'producto'
    $inventario = \App\Models\InventarioProducto::with('producto')->findOrFail($id);

    // if (!$inventario->producto) {
    //     abort(404, 'El producto asociado no existe.');
    // }

    // Retornar una vista con el inventario encontrado
    return view('inventarios.entrada', compact('inventario'));
}

/**
 * Registrar la entrada de inventario.
 *
 * @param \Illuminate\Http\Request $request
 * @param int $id
 * @return \Illuminate\Http\RedirectResponse
 */
public function storeEntrada(Request $request, $id)
{
        // Validar los datos del formulario
    $request->validate([
        'cantidad' => 'required|integer|min:1',
        'costo_total' => 'required|numeric|min:0',
    ]);

    $cantidad = $request->input('cantidad');
    $costo_total = $request->input('costo_total');
    $precio_unitario = $cantidad > 0 ? $costo_total / $cantidad : 0;

    // Buscar un lote asociado al producto
    $lote = Lote::where('producto_id', $id)->first();

    if (!$lote) {
        // Crear un nuevo lote si no existe
        $lote = new Lote();
        $lote->producto_id = $id;
        $lote->cantidad_inicial = 0;
        $lote->cantidad_actual = 0;
        $lote->precio_costo = $request->input('precio_costo');
        $lote->fecha_ingreso = now();
    }

    // Siempre crear un nuevo lote para cada entrada
    $lote = Lote::create([
        'producto_id' => $id,
        'cantidad_inicial' => $cantidad,
        'cantidad_actual' => $cantidad,
        'precio_costo' => $precio_unitario, // <-- ¡USAS EL CALCULADO!
        'fecha_ingreso' => now(),
    ]);

    // Registrar el movimiento de entrada
    \App\Models\Movimiento::create([
        'lote_id' => $lote->id,
        'inventario_id' => $id,
        'user_id' => auth()->id(),
        'tipo' => 'entrada',
        'cantidad' => $request->input('cantidad'),
        'detalle' => 'Entrada de inventario', // Puedes personalizar este detalle según el caso
        'costo_total' => $costo_total,        // Si tienes este campo en la tabla movimientos (opcional)
    ]);

    // Toca el producto para actualizar su updated_at
    $producto = \App\Models\InventarioProducto::findOrFail($id);
    $producto->touch();

    return redirect()->route('inventarios.index')->with('success', 'Entrada registrada con éxito.');
}

/**
 * Mostrar formulario para editar un inventario.
 *
 * @param int $id
 * @return \Illuminate\View\View
 */
public function edit($id)
{

    Log::info('ID recibido en edit: ' . $id);


    // Buscar el inventario por su ID y cargar la relación 'producto'
    $inventario = \App\Models\InventarioProducto::with('producto')->findOrFail($id);

    // if (!$inventario->producto) {
    //     abort(404, 'El producto asociado no existe.');
    // }

    // Retornar una vista con el inventario encontrado
    return view('inventarios.edit', compact('inventario'));
}

/**
 * Actualizar los datos de un inventario.
 *
 * @param \Illuminate\Http\Request $request
 * @param int $id
 * @return \Illuminate\Http\RedirectResponse
 */
public function update(Request $request, $id)
{
    // Validar los datos del formulario
    $request->validate([
        'nombre' => 'required|string|max:255',
        'codigo' => 'required|string|max:255|unique:inventario_productos,codigo,' . $id . ',id',
        'cantidad_inicial' => 'required|numeric|min:0',
        'cantidad_actual' => 'required|numeric|min:0',
        'costo_total' => 'required|numeric|min:0',
        'unidad_medida' => 'required|string|max:255',
        'unidad_personalizada' => 'nullable|string|max:255',
    ]);

    $unidad_medida = $request->unidad_medida === 'personalizada'
        ? $request->unidad_personalizada
        : $request->unidad_medida;

    // Asegura que el código siempre empiece con "#", sin duplicarlo
    $codigo = $request->codigo;
    if (substr($codigo, 0, 1) !== '#') {
        $codigo = '#' . $codigo;
    }

        $precio_unitario = $request->cantidad_inicial > 0 ? $request->costo_total / $request->cantidad_inicial : 0;

    // Buscar el producto por su ID
    $inventario = InventarioProducto::findOrFail($id);

    // Actualizar los datos del producto
    $inventario = \App\Models\InventarioProducto::findOrFail($id);
    $inventario->nombre = $request->nombre;
    $inventario->codigo = $codigo; // <- Usa el código corregido aquí
    $inventario->unidad_medida = $unidad_medida;
    $inventario->save();

    // Actualiza el lote principal (puedes adaptar si tienes varios lotes)
    $lote = $inventario->lotes()->first();
    if ($lote) {
        $lote->cantidad_inicial = $request->cantidad_inicial;
        $lote->cantidad_actual = $request->cantidad_actual;
        $lote->precio_costo = $precio_unitario;
        $lote->save();
    }

    // Redirigir con un mensaje de éxito
    return redirect()->route('inventarios.index')->with('success', 'Inventario actualizado con éxito.');
}

public function destroy($id)
{
    // Buscar el inventario por su ID
    $inventario = \App\Models\InventarioProducto::findOrFail($id);

    // Eliminar el inventario y sus relaciones asociadas
    $inventario->delete();

    // Redirigir con un mensaje de éxito
    return redirect()->route('inventarios.index')->with('success', 'Producto eliminado correctamente.');
}

public function create()
{
    // $productos = \App\Models\Producto::orderBy('nombre')->get();
    // return view('inventarios.create', compact('productos'));

    return view('inventarios.create');
}

public function store(Request $request)
{
    // Validar los datos enviados desde el formulario
    $request->validate([
        'nombre' => 'required|string|max:255',
        'codigo' => 'required|string|max:255|unique:inventario_productos,codigo',
        'cantidad_inicial' => 'required|numeric|min:0',
        'costo_total' => 'required|numeric|min:0',
        'unidad_medida' => 'required|string|max:255',
        'unidad_personalizada' => 'nullable|string|max:255',
    ]);

    // Si la unidad es "personalizada", usa el valor que el usuario ingresó en unidad_personalizada
    $unidad_medida = $request->unidad_medida === 'personalizada'
        ? $request->unidad_personalizada
        : $request->unidad_medida;

      // Asegura que el código siempre empiece con "#"
    $codigo = $request->codigo;
    if (substr($codigo, 0, 1) !== '#') {
        $codigo = '#' . $codigo;
    }

    // Calcula el precio por unidad
    $precio_unitario = $request->cantidad_inicial > 0 ? $request->costo_total / $request->cantidad_inicial : 0;

    try {
        // Crear un nuevo producto
        $producto = \App\Models\InventarioProducto::create([
            'nombre' => $request->nombre,
            'codigo' => $codigo,
            'unidad_medida' => $unidad_medida, // Guardar unidad de medida
            'producto_id' => $request->producto_id,
        ]);

        // Crear un nuevo registro en el inventario vinculado al producto
        \App\Models\Lote::create([
            'producto_id' => $producto->id,
            'cantidad_inicial' => $request->cantidad_inicial,
            'cantidad_actual' => $request->cantidad_inicial,
            'precio_costo' => $precio_unitario,
            'fecha_ingreso' => now(),
        ]);

        // Redirigir al listado de inventario con un mensaje de éxito
        return redirect()->route('inventarios.index')->with('success', 'Producto agregado al inventario correctamente.');
    } catch (QueryException $e) {
        // Si ocurre un error de duplicado, redirigir con un mensaje de error
        if ($e->getCode() === '23000') { // Código de error para violación de unicidad
            return redirect()->back()->withErrors(['codigo' => 'El código ya está registrado.']);
        }

        // Si es otro tipo de error, simplemente vuelve a lanzar la excepción
        throw $e;
    }

}

public function show($id)
{
    $producto = \App\Models\InventarioProducto::with('lotes')->findOrFail($id);
    return view('inventarios.show', compact('producto'));
}

public function showLotes(Request $request, $id)
{
    $producto = \App\Models\InventarioProducto::findOrFail($id);

    // Filtros y ordenación
    $query = $producto->lotes();

    // Búsqueda
    if ($request->filled('search')) {
        $busqueda = $request->input('search');
        $query->where(function ($q) use ($busqueda) {
            $q->where('cantidad_inicial', 'like', "%$busqueda%")
              ->orWhere('cantidad_actual', 'like', "%$busqueda%")
              ->orWhere('precio_costo', 'like', "%$busqueda%")
              ->orWhere('fecha_ingreso', 'like', "%$busqueda%");
        });
    }

    // Ordenación
    $sort = $request->input('sort', 'fecha_ingreso');
    $direction = $request->input('direction', 'desc');
    $query->orderBy($sort, $direction);

    // Paginación
    $lotes = $query->paginate(10)->appends($request->query());

    return view('inventarios.show', compact('producto', 'lotes', 'sort', 'direction'));
}

public function storeLote(Request $request, $productoId)
{
    $request->validate([
        'cantidad_inicial' => 'required|integer|min:1',
        'precio_costo' => 'required|numeric|min:0',
        'fecha_ingreso' => 'required|date',
    ]);

    \App\Models\Lote::create([
        'producto_id' => $productoId,
        'cantidad_inicial' => $request->cantidad_inicial,
        'cantidad_actual' => $request->cantidad_inicial,
        'precio_costo' => $request->precio_costo,
        'fecha_ingreso' => $request->fecha_ingreso,
    ]);

    return redirect()->route('inventarios.index')->with('success', 'Lote registrado con éxito.');
}

public function destroyLote($id)
{
    $lote = \App\Models\Lote::findOrFail($id);
    $lote->delete();
    return redirect()->back()->with('success', 'Lote eliminado correctamente.');
}

public function formSalida($id)
{
    $producto = \App\Models\InventarioProducto::findOrFail($id);
    return view('inventarios.salida', compact('producto'));
}

public function registrarSalida(Request $request, $id)
{
    $request->validate([
        'cantidad' => 'required|integer|min:1',
        'detalle' => 'required|string|max:255',
    ]);

    $cantidadPorDescontar = $request->cantidad;
    $producto = \App\Models\InventarioProducto::findOrFail($id);

    $lotes = $producto->lotes()->where('cantidad_actual', '>', 0)->orderBy('fecha_ingreso', 'asc')->get();

    foreach ($lotes as $lote) {
        if ($cantidadPorDescontar <= 0) break;

        $descontar = min($lote->cantidad_actual, $cantidadPorDescontar);

        $lote->cantidad_actual -= $descontar;
        $lote->save();

        $userId = auth()->id();
        \App\Models\Movimiento::create([
            'lote_id' => $lote->id,
            'inventario_id' => $lote->producto_id,
            'user_id' => $userId,
            'tipo' => 'salida',
            'cantidad' => $descontar,
            'detalle' => $request->detalle,
        ]);

        $cantidadPorDescontar -= $descontar;
    }

    if ($cantidadPorDescontar > 0) {
        return back()->withErrors(['cantidad' => 'No hay suficiente stock para descontar esa cantidad.']);
    }

    return redirect()->route('movimientos.index')->with('success', 'Salida registrada con éxito.');
}
}