<?php

namespace App\Http\Controllers;

use App\Models\CobroPacifico;
use App\Services\PacificoFileService;
use Illuminate\Http\Request;
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

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('identificacion', 'like', '%' . $search . '%')
                  ->orWhere('codigo_tercero', 'like', '%' . $search . '%')
                  ->orWhere('referencia', 'like', '%' . $search . '%')
                  ->orWhere('nombre_tercero', 'like', '%' . $search . '%');
            });
        }

        if ($request->has('tipo_id') && $request->tipo_id) {
            $query->where('tipo_id_tercero', $request->tipo_id);
        }

        if ($request->has('fecha_inicio') && $request->fecha_inicio) {
            $query->whereDate('created_at', '>=', $request->fecha_inicio);
        }

        if ($request->has('fecha_fin') && $request->fecha_fin) {
            $query->whereDate('created_at', '<=', $request->fecha_fin);
        }

        $cobros = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        $totalRegistros = $query->count();
        $valorTotal = $query->sum('valor');

        return view('cobros.index', compact('cobros', 'totalRegistros', 'valorTotal'));
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

    public function export(): StreamedResponse
    {
        $cobros = CobroPacifico::all();
        $filename = 'cobros_pacifico_' . date('Ymd_His') . '.txt';

        return $this->fileService->generateAndDownload($cobros, $filename);
    }
}
