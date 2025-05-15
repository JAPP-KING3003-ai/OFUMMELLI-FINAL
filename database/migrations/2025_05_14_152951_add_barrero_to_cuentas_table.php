<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBarreroToCuentasTable extends Migration
{
    public function up()
    {
        Schema::table('cuentas', function (Blueprint $table) {
            $table->string('barrero')->nullable(); // Nombre del barrero
        });
    }

    public function down()
    {
        Schema::table('cuentas', function (Blueprint $table) {
            $table->dropColumn('barrero');
        });
    }
}