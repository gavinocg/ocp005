<?php

namespace App\Http\Controllers;

use App\Models\CobroPacifico;
use App\Models\EnvioLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EnvioLogController extends Controller
{
    public function index(Request $request): View
    {
        $logs = EnvioLog::orderBy('created_at', 'desc')->paginate(10);

        return view('envios.index', compact('logs'));
    }

    public function regenerate(EnvioLog $envio)
    {
        if ($envio->registros_ids) {
            $ids = explode(',', $envio->registros_ids);
            $cobros = CobroPacifico::whereIn('id', $ids)->get();
            
            if ($cobros->isNotEmpty()) {
                $fileService = app(\App\Services\PacificoFileService::class);
                return $fileService->generateAndDownload($cobros, $envio->filename);
            }
        }
        
        return back()->with('error', 'No se encontraron registros para regenerar');
    }

    public function destroy(EnvioLog $envio)
    {
        if ($envio->registros_ids) {
            $ids = explode(',', $envio->registros_ids);
            CobroPacifico::whereIn('id', $ids)->update([
                'numero_lote' => null,
                'fecha_lote' => null,
            ]);
        }

        $envio->delete();

        return redirect()->route('envios.index')->with('success', 'Log de envío eliminado');
    }
}