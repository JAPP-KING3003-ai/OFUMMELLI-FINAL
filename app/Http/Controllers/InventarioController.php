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
}