<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cobros_pacifico', function (Blueprint $table) {
            $table->id();
            $table->char('localidad', 1)->comment("'1' Guayaquil, '5' Quito");
            $table->char('transaccion', 3)->default('OCP');
            $table->char('codigo_servicio', 2)->default('ZG');
            $table->char('tipo_cuenta', 2)->default('')->comment("00=Ahres, 10=Corriente");
            $table->char('numero_cuenta', 8)->default('')->comment("8 digitos para debito a cuenta");
            $table->decimal('valor', 13, 2);
            $table->char('codigo_tercero', 15);
            $table->char('referencia', 20)->comment("13 chars desc + 7 chars IVA");
            $table->char('forma_pago', 2)->default('RE');
            $table->char('moneda', 3)->default('USD');
            $table->char('nombre_tercero', 30)->nullable();
            $table->char('localidad_cheque', 2)->default('')->comment("Localidad del cheque");
            $table->char('agencia_cheque', 2)->default('')->comment("Agencia del cheque");
            $table->char('tipo_id_tercero', 1)->comment("'C' Cedula, 'R' RUC, 'P' Pasaporte");
            $table->char('identificacion', 14);
            $table->char('telefono', 10)->default('')->comment("10 digitos");
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cobros_pacifico');
    }
};
