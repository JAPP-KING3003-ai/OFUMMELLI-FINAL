<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movimiento extends Model
{
    protected $fillable = [
        'lote_id',
        'inventario_id',
        'user_id',
        'tipo',
        'cantidad',
        'detalle',
        'precio_costo',
    ];

    // Relación: Cada movimiento pertenece a un lote
    public function lote()
    {
        return $this->belongsTo(Lote::class);
    }

    // ✅ Relación: Cada Movimiento pertenece a un InventarioProducto
    public function inventario()
    {
        return $this->belongsTo(\App\Models\InventarioProducto::class, 'inventario_id');
    }
}