<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CobroPacifico extends Model
{
    use HasFactory;

    protected $table = 'cobros_pacifico';

    protected $fillable = [
        'transaccion',
        'codigo_servicio',
        'numero_cuenta',
        'valor',
        'codigo_tercero',
        'referencia',
        'numero_lote',
        'fecha_lote',
        'forma_pago',
        'moneda',
        'nombre_tercero',
        'tipo_id_tercero',
        'identificacion',
        'valor_iva_servicios',
        'tipo_prestacion',
        'valor_iva_bienes',
        'base_imponible_servicios',
        'base_imponible_bienes',
    ];

    protected $casts = [
        'fecha_lote' => 'datetime',
    ];

    public static function rules(): array
    {
        return [
            'transaccion' => 'sometimes|string|size:3',
            'codigo_servicio' => 'sometimes|string|size:2',
            'numero_cuenta' => 'nullable|string|size:10',
            'valor' => 'required|numeric|min:0|max:99999999999.99',
            'codigo_tercero' => 'required|numeric|digits_between:1,15',
            'referencia' => 'required|string|size:20',
            'forma_pago' => 'sometimes|string|size:2',
            'moneda' => 'sometimes|string|size:3',
            'nombre_tercero' => 'nullable|string|max:30',
            'tipo_id_tercero' => 'required|string|size:1|in:C,R,P',
            'identificacion' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $tipo = request()->input('tipo_id_tercero');

                    if ($tipo === 'C') {
                        if (!preg_match('/^[0-9]{10}$/', $value)) {
                            $fail('Para cédula (C) la identificación debe ser exactamente 10 dígitos numéricos.');
                        }
                    } elseif ($tipo === 'R') {
                        if (!preg_match('/^[0-9]{13}$/', $value)) {
                            $fail('Para RUC (R) la identificación debe ser exactamente 13 dígitos numéricos.');
                        }
                    } elseif ($tipo === 'P') {
                        if (!preg_match('/^[A-Za-z0-9]{1,14}$/', $value)) {
                            $fail('Para pasaporte (P) la identificación debe tener hasta 14 caracteres alfanuméricos.');
                        }
                    }
                },
            ],
            'valor_iva_servicios' => 'nullable|numeric|min:0|max:9999999.99',
            'tipo_prestacion' => 'sometimes|string|size:1',
            'valor_iva_bienes' => 'nullable|numeric|min:0|max:9999999.99',
            'base_imponible_servicios' => 'nullable|numeric|min:0|max:99999999.99',
            'base_imponible_bienes' => 'nullable|numeric|min:0|max:99999999.99',
        ];
    }

    public static function messages(): array
    {
        return [
            'transaccion.size' => 'La transaccion debe ser exactamente 3 caracteres',
            'codigo_servicio.size' => 'El codigo de servicio debe ser exactamente 2 caracteres',
            'numero_cuenta.size' => 'El numero de cuenta debe ser exactamente 10 caracteres',
            'valor.required' => 'El valor es obligatorio',
            'valor.numeric' => 'El valor debe ser numerico',
            'codigo_tercero.required' => 'El codigo de tercero es obligatorio',
            'codigo_tercero.numeric' => 'El codigo de tercero debe ser numerico',
            'codigo_tercero.digits_between' => 'El codigo de tercero debe tener entre 1 y 15 dígitos',
            'referencia.required' => 'La referencia es obligatoria',
            'referencia.size' => 'La referencia debe ser exactamente 20 caracteres',
            'forma_pago.size' => 'La forma de pago debe ser exactamente 2 caracteres',
            'moneda.size' => 'La moneda debe ser exactamente 3 caracteres',
            'nombre_tercero.max' => 'El nombre no puede exceder 30 caracteres',
            'tipo_id_tercero.required' => 'El tipo de identificacion es obligatorio',
            'tipo_id_tercero.in' => 'El tipo de identificacion debe ser C, R o P',
            'identificacion.required' => 'La identificacion es obligatoria',
            'identificacion.string' => 'La identificacion debe ser un texto valido',
        ];
    }

    public static function asciiNormalize(string $value): string
    {
        $map = [
            'Á'=>'A','É'=>'E','Í'=>'I','Ó'=>'O','Ú'=>'U','Ü'=>'U','Ñ'=>'N',
            'á'=>'a','é'=>'e','í'=>'i','ó'=>'o','ú'=>'u','ü'=>'u','ñ'=>'n',
            'À'=>'A','È'=>'E','Ì'=>'I','Ò'=>'O','Ù'=>'U','à'=>'a','è'=>'e','ì'=>'i','ò'=>'o','ù'=>'u',
            'Â'=>'A','Ê'=>'E','Î'=>'I','Ô'=>'O','Û'=>'U','â'=>'a','ê'=>'e','î'=>'i','ô'=>'o','û'=>'u',
            'Ä'=>'A','Ë'=>'E','Ï'=>'I','Ö'=>'O','Ÿ'=>'Y','ä'=>'a','ë'=>'e','ï'=>'i','ö'=>'o','ÿ'=>'y',
            'Ç'=>'C','ç'=>'c',
        ];

        $value = strtr($value, $map);
        return preg_replace('/[^\x00-\x7F]/', ' ', $value);
    }

    public static function normalizeData(array $data): array
    {
        $fields = [
            'transaccion', 'codigo_servicio', 'numero_cuenta',
            'codigo_tercero', 'referencia', 'forma_pago', 'moneda',
            'nombre_tercero', 'tipo_id_tercero', 'identificacion',
            'tipo_prestacion',
        ];

        foreach ($fields as $key) {
            if (isset($data[$key]) && is_string($data[$key])) {
                $data[$key] = self::asciiNormalize($data[$key]);
            }
        }

        return $data;
    }
}
