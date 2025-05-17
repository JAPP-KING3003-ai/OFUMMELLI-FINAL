<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('detalle_cuentas', function (Blueprint $table) {
        $table->timestamp('printed_at')->nullable()->after('producto_id');
    });
}

public function down()
{
    Schema::table('detalle_cuentas', function (Blueprint $table) {
        $table->dropColumn('printed_at');
    });
}
};
