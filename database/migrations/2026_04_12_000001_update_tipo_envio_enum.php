<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE envio_logs MODIFY COLUMN tipo_envio ENUM('pendientes', 'seleccionados') DEFAULT 'pendientes'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE envio_logs MODIFY COLUMN tipo_envio ENUM('todos', 'seleccionados') DEFAULT 'todos'");
    }
};