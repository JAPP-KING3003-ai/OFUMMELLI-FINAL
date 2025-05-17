<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('detalle_cuentas', function (Blueprint $table) {
            $table->unsignedBigInteger('area_id')->nullable()->after('producto_id'); // Agregar campo area_id
        });
    }

    public function down(): void
    {
        Schema::table('detalle_cuentas', function (Blueprint $table) {
            $table->dropColumn('area_id');
        });
    }
};