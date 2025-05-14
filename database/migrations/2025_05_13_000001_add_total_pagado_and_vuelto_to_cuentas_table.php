<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTotalPagadoAndVueltoToCuentasTable extends Migration
{
    public function up()
    {
        Schema::table('cuentas', function (Blueprint $table) {
            $table->decimal('total_pagado', 10, 2)->default(0)->after('total_estimado'); // Campo para total pagado
            $table->decimal('vuelto', 10, 2)->default(0)->after('total_pagado'); // Campo para vuelto
        });
    }

    public function down()
    {
        Schema::table('cuentas', function (Blueprint $table) {
            $table->dropColumn('total_pagado');
            $table->dropColumn('vuelto');
        });
    }
}