<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCantidadInicialToInventarioProductosTable extends Migration
{
    public function up()
    {
        Schema::table('inventario_productos', function (Blueprint $table) {
            $table->integer('cantidad_inicial')->default(0)->after('codigo');
            $table->integer('cantidad_actual')->default(0)->after('cantidad_inicial');
            $table->decimal('precio_costo', 10, 2)->default(0.00)->after('cantidad_actual');
        });
    }

    public function down()
    {
        Schema::table('inventario_productos', function (Blueprint $table) {
            $table->dropColumn(['cantidad_inicial', 'cantidad_actual', 'precio_costo']);
        });
    }
}
