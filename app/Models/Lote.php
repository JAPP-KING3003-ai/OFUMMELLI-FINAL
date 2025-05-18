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

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}