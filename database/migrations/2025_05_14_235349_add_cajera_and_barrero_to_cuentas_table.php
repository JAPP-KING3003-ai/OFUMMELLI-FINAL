<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCajeraAndBarreroToCuentasTable extends Migration
{
    public function up()
    {
        Schema::table('cuentas', function (Blueprint $table) {
            // Solo agregar columnas si no existen
            if (!Schema::hasColumn('cuentas', 'cajera')) {
                $table->string('cajera')->nullable()->after('usuario_id');
            }
            if (!Schema::hasColumn('cuentas', 'barrero')) {
                $table->string('barrero')->nullable()->after('cajera');
            }
        });
    }

    public function down()
    {
        Schema::table('cuentas', function (Blueprint $table) {
            if (Schema::hasColumn('cuentas', 'cajera')) {
                $table->dropColumn('cajera');
            }
            if (Schema::hasColumn('cuentas', 'barrero')) {
                $table->dropColumn('barrero');
            }
        });
    }
}