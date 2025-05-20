<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventarioProducto extends Model
{
    protected $fillable = [
        'nombre',
        'codigo',
        'unidad_medida',
    ];

    // Relación: Un inventario puede estar relacionado con un producto
    public function producto()
    {
        return $this->belongsTo(\App\Models\Producto::class, 'producto_id'); // Cambia 'producto_id' si el FK tiene otro nombre
    }

    // Relación: Un inventario puede tener muchos lotes
    public function lotes()
    {
        return $this->hasMany(Lote::class, 'producto_id');
    }
}