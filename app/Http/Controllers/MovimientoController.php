<?php

namespace App\Http\Controllers;

use App\Models\Movimiento;
use App\Models\Producto;
use Illuminate\Http\Request;

class MovimientoController extends Controller
{
    public function index(Request $request)
    {
        $query = Movimiento::with('inventario.producto')->orderBy('created_at', 'desc');

        // Filtrar por fecha
        if ($request->filled('fecha_inicio')) {
            $query->whereDate('created_at', '>=', $request->fecha_inicio);
        }
        if ($request->filled('fecha_fin')) {
            $query->whereDate('created_at', '<=', $request->fecha_fin);
        }

        // Filtrar por varios productos
        if ($request->filled('producto_id')) {
            $productoIds = $request->producto_id;
            $query->whereHas('inventario', function ($q) use ($productoIds) {
                $q->whereIn('producto_id', (array) $productoIds);
            });
        }

        $movimientos = $query->paginate(10)->withQueryString(); // Paginación con filtros

        $productos = Producto::orderBy('nombre')->get(); // Listar productos

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

    // Registrar el movimiento
    \App\Models\Movimiento::create([
        'inventario_id' => $lote->producto->id,
        'tipo' => $request->tipo,
        'cantidad' => $request->cantidad,
        'detalle' => $request->detalle,
    ]);

    return redirect()->route('movimientos.index')->with('success', 'Movimiento registrado con éxito.');
}
}