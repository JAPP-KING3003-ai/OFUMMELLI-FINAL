<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPendienteToCuentasTable extends Migration
{
    public function up()
    {
        Schema::table('cuentas', function (Blueprint $table) {
            $table->boolean('pendiente')->default(false)->after('pagada');
        });
    }

    public function down()
    {
        Schema::table('cuentas', function (Blueprint $table) {
            $table->dropColumn('pendiente');
        });
    }
}