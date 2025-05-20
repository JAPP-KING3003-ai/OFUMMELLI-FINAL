<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lotes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('producto_id')->index(); // Relación con el producto
            $table->integer('cantidad_inicial'); // Cantidad agregada originalmente
            $table->integer('cantidad_actual'); // Cantidad restante
            $table->decimal('precio_costo', 10, 2); // Precio de costo del lote
            $table->datetime('fecha_ingreso'); // Fecha y hora en la que se añadió el lote
            $table->timestamps();

            // Llave foránea
            $table->foreign('producto_id')->references('id')->on('productos')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lotes');
    }
};