<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('cobros_pacifico');

        Schema::create('cobros_pacifico', function (Blueprint $table) {
            $table->id();
            $table->char('transaccion', 3)->default('OCP');
            $table->char('codigo_servicio', 2)->default('ZG');
            $table->char('numero_cuenta', 10)->default('')->comment('10 digitos (completar con ceros). Para recaudaciones enviar 10 ceros.');
            $table->decimal('valor', 13, 2)->comment('13 enteros, 2 decimales (sin separacion)');
            $table->char('codigo_tercero', 15)->comment('Codigo del cliente, numero de contrato, etc.');
            $table->char('referencia', 20)->comment('Referencia corta para el estado de cuenta del tercero.');
            $table->char('forma_pago', 2)->default('RE');
            $table->char('moneda', 3)->default('USD');
            $table->char('nombre_tercero', 30)->nullable()->comment('Nombre de quien debita o realiza el pago.');
            $table->char('tipo_id_tercero', 1)->comment("'C' Cedula, 'R' RUC, 'P' Pasaporte");
            $table->char('identificacion', 14)->comment('Identificacion del tercero (ej: 0915342391).');
            $table->decimal('valor_iva_servicios', 9, 2)->default(0)->comment('7 enteros, 2 decimales (sin separacion)');
            $table->char('tipo_prestacion', 1)->default('A')->comment('Siempre enviar A.');
            $table->decimal('valor_iva_bienes', 9, 2)->default(0)->comment('7 enteros, 2 decimales (sin separacion)');
            $table->decimal('base_imponible_servicios', 10, 2)->default(0)->comment('8 enteros, 2 decimales (sin separacion)');
            $table->decimal('base_imponible_bienes', 10, 2)->default(0)->comment('8 enteros, 2 decimales (sin separacion)');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cobros_pacifico');
    }
};
