<?php

namespace App\Http\Controllers;

use App\Models\Inventario;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class InventarioController extends Controller
{
    /**
     * Mostrar listado de inventarios.
     */
    public function index(Request $request)
    {
        $query = Inventario::with('producto');

        // Aplicar búsqueda
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('producto', function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('codigo', 'like', "%{$search}%");
            });
        }

        $inventarios = $query->paginate(10); // Paginación

        return view('inventarios.index', compact('inventarios'));
    }

    /**
     * Exportar datos de inventarios a Excel.
     */
    public function exportarExcel()
{
    $inventarios = Inventario::with('producto')->get();

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Encabezados con estilo
    $encabezados = ['Código', 'Nombre del Producto', 'Cantidad Inicial', 'Cantidad Actual', 'Precio Costo'];
    $col = 'A';

    // Aplicar estilos a los encabezados
    foreach ($encabezados as $titulo) {
        $sheet->setCellValue("{$col}1", $titulo);
        $sheet->getStyle("{$col}1")->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '00B050'], // Verde
            ],
            'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
        ]);
        $col++;
    }

    // Datos de inventario
    $fila = 2;
    foreach ($inventarios as $inventario) {
        $sheet->setCellValue("A{$fila}", $inventario->producto->codigo);
        $sheet->setCellValue("B{$fila}", $inventario->producto->nombre);
        $sheet->setCellValue("C{$fila}", $inventario->cantidad_inicial);
        $sheet->setCellValue("D{$fila}", $inventario->cantidad_actual);
        $sheet->setCellValue("E{$fila}", number_format($inventario->precio_costo, 2));
        $fila++;
    }

    // Aplicar bordes a toda la tabla
    $sheet->getStyle("A1:E{$fila}")->applyFromArray([
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['rgb' => '000000'],
            ],
        ],
    ]);

    // Autoajustar columnas
    foreach (range('A', 'E') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    // Descargar archivo
    $writer = new Xlsx($spreadsheet);
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
    // Buscar el inventario por su ID
    $inventario = Inventario::with('producto')->findOrFail($id);

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
    ]);

    // Buscar el inventario por su ID
    $inventario = Inventario::findOrFail($id);

    // Actualizar la cantidad actual
    $inventario->cantidad_actual += $request->input('cantidad');
    $inventario->save();

    // Redirigir con un mensaje de éxito
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
    // Buscar el inventario por su ID
    $inventario = Inventario::with('producto')->findOrFail($id);

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
        'cantidad_inicial' => 'required|integer|min:0',
        'cantidad_actual' => 'required|integer|min:0',
        'precio_costo' => 'required|numeric|min:0',
    ]);

    // Buscar el inventario por su ID
    $inventario = Inventario::findOrFail($id);

    // Actualizar los datos del inventario
    $inventario->cantidad_inicial = $request->input('cantidad_inicial');
    $inventario->cantidad_actual = $request->input('cantidad_actual');
    $inventario->precio_costo = $request->input('precio_costo');
    $inventario->save();

    // Redirigir con un mensaje de éxito
    return redirect()->route('inventarios.index')->with('success', 'Inventario actualizado con éxito.');
}

public function destroy($id)
{
    $inventario = Inventario::findOrFail($id);

    // Eliminar el inventario y su producto asociado
    $producto = $inventario->producto;
    $inventario->delete();
    $producto->delete();

    return redirect()->route('inventarios.index')->with('success', 'Producto eliminado del inventario exitosamente.');
}

public function create()
{
    return view('inventarios.create'); // Asegúrate de que la vista 'inventarios.create' exista
}

public function store(Request $request)
{
    // Validar los datos enviados desde el formulario
    $request->validate([
        'nombre' => 'required|string|max:255',
        'codigo' => 'required|string|max:255|unique:productos,codigo',
        'cantidad_inicial' => 'required|integer|min:0',
        'precio_costo' => 'required|numeric|min:0',
        'unidad_medida' => 'required|string|max:255', // Validar unidad de medida
    ]);

    // Crear un nuevo producto
    $producto = \App\Models\Producto::create([
        'nombre' => $request->nombre,
        'codigo' => $request->codigo,
        'unidad_medida' => $request->unidad_medida, // Guardar unidad de medida
    ]);

    // Crear un nuevo registro en el inventario vinculado al producto
    \App\Models\Inventario::create([
        'producto_id' => $producto->id,
        'cantidad_inicial' => $request->cantidad_inicial,
        'cantidad_actual' => $request->cantidad_inicial,
        'precio_costo' => $request->precio_costo,
    ]);

    // Redirigir al listado de inventario con un mensaje de éxito
    return redirect()->route('inventarios.index')->with('success', 'Producto agregado al inventario correctamente.');
}
}