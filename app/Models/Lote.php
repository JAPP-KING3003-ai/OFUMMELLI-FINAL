<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lote extends Model
{
    protected $fillable = [
        'producto_id',
        'cantidad_inicial',
        'cantidad_actual',
        'precio_costo',
        'fecha_ingreso',
    ];

    // Relación: Un lote pertenece a un producto del inventario
    public function producto()
    {
        return $this->belongsTo(\App\Models\InventarioProducto::class, 'producto_id');
    }

    // Relación: Un lote puede tener muchos movimientos
    public function movimientos()
    {
        return $this->hasMany(Movimiento::class);
    }
}