<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cobros_pacifico', function (Blueprint $table) {
            $table->string('numero_lote', 20)->nullable()->after('referencia');
            $table->timestamp('fecha_lote')->nullable()->after('numero_lote');
        });
    }

    public function down(): void
    {
        Schema::table('cobros_pacifico', function (Blueprint $table) {
            $table->dropColumn(['numero_lote', 'fecha_lote']);
        });
    }
};