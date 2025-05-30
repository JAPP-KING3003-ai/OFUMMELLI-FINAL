<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
        protected $fillable = [
        'producto_id',      // Añadir este campo
        'cantidad_inicial',
        'cantidad_actual',
        'precio_costo',
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}