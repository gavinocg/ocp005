<?php

namespace App\Http\Controllers;

use App\Models\CobroPacifico;
use App\Services\OracleService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ConsultarController extends Controller
{
    public function index(): View
    {
        return view('consultar.index');
    }

    public function obtener(Request $request)
    {
        $oracle = new OracleService();
        $tramites = $oracle->getTramites();
        $oracle->disconnect();

        $tramitesExistentes = CobroPacifico::pluck('codigo_tercero')->toArray();

        if ($tramites) {
            $filtered = collect($tramites)->filter(function ($item) use ($request, $tramitesExistentes) {
                if (in_array($item['TRAMITE'], $tramitesExistentes)) {
                    return false;
                }

                $match = true;

                if ($request->has('search') && $request->search) {
                    $search = strtolower($request->search);
                    $match = $match && (
                        stripos($item['TRAMITE'], $search) !== false ||
                        stripos($item['IDENTIFICACION'], $search) !== false ||
                        stripos($item['NOMBRE'], $search) !== false
                    );
                }

                if ($request->has('tipo_id') && $request->tipo_id) {
                    $match = $match && ($item['TIP_IDENTIFICACION'] === $request->tipo_id);
                }

                return $match;
            })->values()->all();

            return response()->json([
                'success' => true,
                'data' => $filtered,
                'total' => count($filtered)
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No se encontraron registros',
            'data' => [],
            'total' => 0
        ]);
    }

    public function guardar(Request $request)
    {
        $selected = $request->input('selected', []);

        if (empty($selected)) {
            return response()->json([
                'success' => false,
                'message' => 'No hay registros seleccionados'
            ]);
        }

        try {
            $saved = 0;
            foreach ($selected as $item) {
                $tipId = strtoupper($item['TIP_IDENTIFICACION'] ?? '');
                $tipIdMap = [
                    'R' => 'R',
                    'RUC' => 'R',
                    'C' => 'C',
                    'CEDULA' => 'C',
                    'CED' => 'C',
                    'P' => 'P',
                    'PASAPORTE' => 'P',
                    'PAS' => 'P',
                ];
                $tipoId = $tipIdMap[$tipId] ?? 'C';

                CobroPacifico::create([
                    'transaccion' => 'OCP',
                    'codigo_servicio' => 'ZG',
                    'numero_cuenta' => '',
                    'valor' => floatval($item['VALOR'] ?? 0),
                    'codigo_tercero' => $this->normalizeText($item['TRAMITE'] ?? ''),
                    'referencia' => 'REG_PROP_MERC_CAYAMB',
                    'forma_pago' => 'RE',
                    'moneda' => 'USD',
                    'nombre_tercero' => $this->normalizeText($item['NOMBRE'] ?? ''),
                    'tipo_id_tercero' => $tipoId,
                    'identificacion' => $this->normalizeText($item['IDENTIFICACION'] ?? ''),
                    'valor_iva_servicios' => 0.00,
                    'valor_iva_bienes' => 0.00,
                    'base_imponible_servicios' => 0.00,
                    'base_imponible_bienes' => 0.00,
                ]);
                $saved++;
            }

            return response()->json([
                'success' => true,
                'message' => "Se guardaron {$saved} registros exitosamente",
                'count' => $saved
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar: ' . $e->getMessage()
            ]);
        }
    }

    private function normalizeText(string $text): string
    {
        $map = [
            'Á' => 'A', 'É' => 'E', 'Í' => 'I', 'Ó' => 'O', 'Ú' => 'U', 'Ü' => 'U', 'Ñ' => 'N',
            'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u', 'ü' => 'u', 'ñ' => 'n',
            'À' => 'A', 'È' => 'E', 'Ì' => 'I', 'Ò' => 'O', 'Ù' => 'U', 'à' => 'a', 'è' => 'e', 'ì' => 'i', 'ò' => 'o', 'ù' => 'u',
            'Â' => 'A', 'Ê' => 'E', 'Î' => 'I', 'Ô' => 'O', 'Û' => 'U', 'â' => 'a', 'ê' => 'e', 'î' => 'i', 'ô' => 'o', 'û' => 'u',
            'Ä' => 'A', 'Ë' => 'E', 'Ï' => 'I', 'Ö' => 'O', 'Ÿ' => 'Y', 'ä' => 'a', 'ë' => 'e', 'ï' => 'i', 'ö' => 'o', 'ÿ' => 'y',
            'Ç' => 'C', 'ç' => 'c'
        ];

        $text = strtr($text, $map);
        $text = preg_replace('/\s+/', ' ', $text);
        return trim($text);
    }
}