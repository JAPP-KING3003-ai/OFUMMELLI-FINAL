<?php

namespace App\Http\Controllers;

use App\Models\Movimiento;
use App\Models\Producto;
use Illuminate\Http\Request;

class MovimientoController extends Controller
{
    public function index(Request $request)
{
    $sort = $request->input('sort', 'created_at');
    $direction = $request->input('direction', 'desc');

    // Base query
    $query = \App\Models\Movimiento::query();

    // Si ordena por el nombre del inventario (producto)
    if ($sort === 'inventario_nombre') {
        $query->join('inventario_productos', 'movimientos.inventario_id', '=', 'inventario_productos.id')
              ->orderBy('inventario_productos.nombre', $direction)
              ->select('movimientos.*'); // Importante para no perder los campos del modelo
    } else {
        $query->orderBy($sort, $direction);
    }

    // Filtros
    if ($request->filled('fecha_inicio')) {
        $query->whereDate('movimientos.created_at', '>=', $request->fecha_inicio);
    }
    if ($request->filled('fecha_fin')) {
        $query->whereDate('movimientos.created_at', '<=', $request->fecha_fin);
    }

    // Filtrar por producto (opcional, solo si quieres mantenerlo)
    if ($request->filled('producto_id')) {
        $productoIds = $request->producto_id;
        $query->whereIn('inventario_id', (array) $productoIds);
    }

    $movimientos = $query->with('inventario')->paginate(10)->appends($request->all());

    $productos = \App\Models\InventarioProducto::orderBy('nombre')->get();

    return view('movimientos.index', compact('movimientos', 'productos'));
}

    public function store(Request $request)
{
    $request->validate([
        'lote_id' => 'required|exists:lotes,id',
        'tipo' => 'required|in:entrada,salida',
        'cantidad' => 'required|integer|min:1',
        'detalle' => 'nullable|string|max:255',
    ]);

    $lote = \App\Models\Lote::findOrFail($request->lote_id);

    if ($request->tipo === 'salida' && $request->cantidad > $lote->cantidad_actual) {
        return back()->withErrors(['cantidad' => 'No hay suficiente stock en este lote.']);
    }

    // Actualizar la cantidad actual en el lote
    $lote->cantidad_actual += $request->tipo === 'entrada' ? $request->cantidad : -$request->cantidad;
    $lote->save();

    $lote = \App\Models\Lote::findOrFail($request->lote_id);

    // Aquí obtenemos el InventarioProducto correcto
    $inventarioProducto = \App\Models\InventarioProducto::where('producto_id', $lote->producto_id)->first();

    \App\Models\Movimiento::create([
        'lote_id' => $lote->id,
        'inventario_id' => $lote->producto->id, // ¡Ahora sí es el id de inventario_productos!
        'tipo' => $request->tipo,
        'cantidad' => $request->cantidad,
        'detalle' => $request->detalle,
    ]);

    return redirect()->route('movimientos.index')->with('success', 'Movimiento registrado con éxito.');
}
}