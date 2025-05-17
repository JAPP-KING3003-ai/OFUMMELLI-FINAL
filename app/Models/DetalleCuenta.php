<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleCuenta extends Model
{
    protected $table = 'detalle_cuentas';

    protected $fillable = ['cuenta_id', 'producto_id', 'cantidad', 'printed_at', 'area_id', 'precio_unitario', 'subtotal'];

    // Relación con la cuenta
    public function cuenta()
    {
        return $this->belongsTo(Cuenta::class);
    }

    // Relación con el producto
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    // Método para verificar si el producto ya fue impreso
    public function isPrinted()
    {
        return !is_null($this->printed_at);
    }
}