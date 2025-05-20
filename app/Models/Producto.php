<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'productos'; // Asegura que el modelo use la tabla correcta

    protected $fillable = [
        'codigo',
        'nombre',
        'unidad_medida',
        'precio_venta', // ✅ Este es el campo correcto que usas en tu Seeder
        'area_id',
    ];

     // Relación: Un producto tiene muchos lotes
    public function lotes()
    {
        return $this->hasMany(Lote::class);
    }

    /**
     * Accesor para retornar el precio como float (por si lo necesitas en vistas o controladores).
     */
    public function getPrecioVentaAttribute($value)
    {
        return floatval($value);
    }
}