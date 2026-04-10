<?php

namespace App\Http\Controllers;

use App\Models\CobroPacifico;
use App\Models\EnvioLog;
use App\Services\PacificoFileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CobroPacificoController extends Controller
{
    public function __construct(
        protected PacificoFileService $fileService
    ) {}

    public function index(Request $request): View
    {
        $query = CobroPacifico::query();

        if ($request->has('codigo_tercero') && $request->codigo_tercero) {
            $query->where('codigo_tercero', 'like', '%' . $request->codigo_tercero . '%');
        }

        if ($request->has('identificacion') && $request->identificacion) {
            $query->where('identificacion', 'like', '%' . $request->identificacion . '%');
        }

        if ($request->has('nombre') && $request->nombre) {
            $query->where('nombre_tercero', 'like', '%' . $request->nombre . '%');
        }

        if ($request->has('tipo_id') && $request->tipo_id) {
            $query->where('tipo_id_tercero', $request->tipo_id);
        }

        if ($request->has('valor_min') && $request->valor_min) {
            $query->where('valor', '>=', floatval($request->valor_min));
        }

        if ($request->has('valor_max') && $request->valor_max) {
            $query->where('valor', '<=', floatval($request->valor_max));
        }

        if ($request->has('fecha_creacion_inicio') && $request->fecha_creacion_inicio) {
            $query->whereDate('created_at', '>=', $request->fecha_creacion_inicio);
        }

        if ($request->has('fecha_creacion_fin') && $request->fecha_creacion_fin) {
            $query->whereDate('created_at', '<=', $request->fecha_creacion_fin);
        }

        if ($request->has('numero_lote') && $request->numero_lote) {
            $query->where('numero_lote', 'like', '%' . $request->numero_lote . '%');
        }

        $sortField = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');
        $allowedSorts = ['codigo_tercero', 'tipo_id_tercero', 'identificacion', 'nombre_tercero', 'valor', 'numero_lote', 'fecha_lote', 'created_at'];
        
        if (!in_array($sortField, $allowedSorts)) {
            $sortField = 'created_at';
        }

        $perPage = $request->input('per_page', 10);
        $cobros = $query->orderBy($sortField, $sortDirection)->paginate($perPage)->withQueryString();

        return view('cobros.index', compact('cobros'));
    }

    public function create(): View
    {
        return view('cobros.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate(CobroPacifico::rules(), CobroPacifico::messages());
        $validated = CobroPacifico::normalizeData($validated);

        CobroPacifico::create([
            'transaccion' => 'OCP',
            'codigo_servicio' => 'ZG',
            'numero_cuenta' => $validated['numero_cuenta'] ?? '',
            'valor' => $validated['valor'],
            'codigo_tercero' => $validated['codigo_tercero'],
            'referencia' => $validated['referencia'],
            'forma_pago' => 'RE',
            'moneda' => 'USD',
            'nombre_tercero' => $validated['nombre_tercero'] ?? '',
            'tipo_id_tercero' => $validated['tipo_id_tercero'],
            'identificacion' => $validated['identificacion'],
            'valor_iva_servicios' => $validated['valor_iva_servicios'] ?? 0,
            'tipo_prestacion' => 'A',
            'valor_iva_bienes' => $validated['valor_iva_bienes'] ?? 0,
            'base_imponible_servicios' => $validated['base_imponible_servicios'] ?? 0,
            'base_imponible_bienes' => $validated['base_imponible_bienes'] ?? 0,
        ]);

        return redirect()->route('cobros.index')
            ->with('success', 'Cobro registrado exitosamente.');
    }

    public function destroy(CobroPacifico $cobro)
    {
        $cobro->delete();
        return redirect()->route('cobros.index')
            ->with('success', 'Cobro eliminado exitosamente.');
    }

    public function edit(CobroPacifico $cobro): View
    {
        return view('cobros.edit', compact('cobro'));
    }

    public function update(Request $request, CobroPacifico $cobro)
    {
        $validated = $request->validate(CobroPacifico::rules(), CobroPacifico::messages());
        $validated = CobroPacifico::normalizeData($validated);

        $cobro->update([
            'transaccion' => 'OCP',
            'codigo_servicio' => 'ZG',
            'numero_cuenta' => $validated['numero_cuenta'] ?? '',
            'valor' => $validated['valor'],
            'codigo_tercero' => $validated['codigo_tercero'],
            'referencia' => $validated['referencia'],
            'forma_pago' => 'RE',
            'moneda' => 'USD',
            'nombre_tercero' => $validated['nombre_tercero'] ?? '',
            'tipo_id_tercero' => $validated['tipo_id_tercero'],
            'identificacion' => $validated['identificacion'],
            'valor_iva_servicios' => $validated['valor_iva_servicios'] ?? 0,
            'tipo_prestacion' => 'A',
            'valor_iva_bienes' => $validated['valor_iva_bienes'] ?? 0,
            'base_imponible_servicios' => $validated['base_imponible_servicios'] ?? 0,
            'base_imponible_bienes' => $validated['base_imponible_bienes'] ?? 0,
        ]);

        return redirect()->route('cobros.index')
            ->with('success', 'Cobro actualizado exitosamente.');
    }

    public function export(Request $request)
    {
        try {
            $tipo = $request->input('tipo', 'pendientes');
            $idsInput = $request->input('ids', '');
            
            $ids = [];
            if ($idsInput) {
                $ids = is_array($idsInput) ? $idsInput : explode(',', $idsInput);
            }

            if ($tipo === 'seleccionados' && !empty($ids)) {
                $cobros = CobroPacifico::whereIn('id', $ids)->get();
            } else {
                $cobros = CobroPacifico::whereNull('numero_lote')->get();
            }

            if ($cobros->isEmpty()) {
                if ($request->ajax() || $request->header('Accept') === 'application/json') {
                    return response()->json(['error' => 'No hay registros para exportar'], 422);
                }
                return back()->with('error', 'No hay registros para exportar');
            }

            $valorTotal = $cobros->sum('valor');
            $totalRegistros = $cobros->count();
            $numeroLote = 'LOTE-' . date('YmdHis');
            $timestamp = now();
            $filename = 'cobros_pacifico_' . $numeroLote . '.txt';

            EnvioLog::create([
                'numero_lote' => $numeroLote,
                'timestamp_generacion' => $timestamp,
                'valor_total' => $valorTotal,
                'total_registros' => $totalRegistros,
                'filename' => $filename,
                'tipo_envio' => $tipo,
                'registros_ids' => $cobros->pluck('id')->implode(','),
            ]);

            CobroPacifico::whereIn('id', $cobros->pluck('id'))->update([
                'numero_lote' => $numeroLote,
                'fecha_lote' => $timestamp,
            ]);

            return $this->fileService->generateAndDownload($cobros, $filename);
        } catch (\Exception $e) {
            \Log::error('Error en export: ' . $e->getMessage());
            return back()->with('error', 'Error al exportar: ' . $e->getMessage());
        }
    }

    public function regenerateExport($cobros, string $filename)
    {
        return $this->fileService->generateAndDownload($cobros, $filename);
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);
        
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No hay registros seleccionados']);
        }

        CobroPacifico::whereIn('id', $ids)->delete();

        return response()->json(['success' => true, 'message' => 'Registros eliminados correctamente']);
    }
}
