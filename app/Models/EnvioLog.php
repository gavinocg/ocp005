<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnvioLog extends Model
{
    protected $table = 'envio_logs';
    
    protected $fillable = [
        'numero_lote',
        'timestamp_generacion',
        'valor_total',
        'total_registros',
        'filename',
        'tipo_envio',
        'registros_ids',
    ];

    protected $casts = [
        'timestamp_generacion' => 'datetime',
        'valor_total' => 'decimal:2',
    ];
}