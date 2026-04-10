<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('envio_logs', function (Blueprint $table) {
            $table->id();
            $table->string('numero_lote', 20)->unique();
            $table->timestamp('timestamp_generacion');
            $table->decimal('valor_total', 15, 2);
            $table->integer('total_registros');
            $table->string('filename', 100);
            $table->enum('tipo_envio', ['pendientes', 'seleccionados'])->default('pendientes');
            $table->text('registros_ids')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('envio_logs');
    }
};