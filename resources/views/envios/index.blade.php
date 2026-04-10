@extends('layouts.app')
@section('title', 'Log de Envíos - OCP-005')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h5 class="mb-0"><i class="bi bi-file-earmark-text"></i> Log de Envíos</h5>
        <a href="{{ route('cobros.index') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-arrow-left"></i> Volver a Cobros
        </a>
    </div>
    
    <div class="card-body">
        @if($logs->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>N° Lote</th>
                            <th>Tipo</th>
                            <th>Registros</th>
                            <th>Valor Total</th>
                            <th>Archivo</th>
                            <th>Fecha Generación</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($logs as $log)
                            <tr>
                                <td>
                                    <strong>{{ $log->numero_lote }}</strong>
                                </td>
                                <td>
                                    @if($log->tipo_envio === 'todos')
                                        <span class="badge bg-primary">Todos</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Seleccionados</span>
                                    @endif
                                </td>
                                <td>{{ $log->total_registros }}</td>
                                <td>${{ number_format($log->valor_total, 2, ',', '.') }}</td>
                                <td>
                                    <small class="text-muted">{{ $log->filename }}</small>
                                </td>
                                <td>{{ $log->timestamp_generacion->format('d/m/Y H:i:s') }}</td>
                                <td class="text-center">
                                    <a href="{{ route('envios.regenerate', $log) }}" class="btn btn-primary btn-sm" title="Regenerar">
                                        <i class="bi bi-download"></i>
                                    </a>
                                    <form action="{{ route('envios.destroy', $log) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Deshacer este envío? Los registros serán liberados para un nuevo envío.')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pagination-wrapper">
                <div class="pagination-info">
                    <span class="info-text">
                        Mostrando <strong>{{ $logs->firstItem() ?? 0 }}</strong> a 
                        <strong>{{ $logs->lastItem() ?? 0 }}</strong> de 
                        <strong>{{ $logs->total() }}</strong> registros
                    </span>
                </div>
                <nav aria-label="Paginación">
                    <ul class="pagination-list">
                        @if ($logs->onFirstPage())
                            <li class="pagination-item disabled">
                                <button type="button" class="pagination-btn" disabled>«</button>
                            </li>
                        @else
                            <li class="pagination-item">
                                <a href="{{ $logs->previousPageUrl() }}" class="pagination-btn">«</a>
                            </li>
                        @endif

                        @foreach ($logs->getUrlRange(max(1, $logs->currentPage() - 2), min($logs->lastPage(), $logs->currentPage() + 2)) as $page => $url)
                            @if ($page == $logs->currentPage())
                                <li class="pagination-item active">
                                    <button type="button" class="pagination-btn active">{{ $page }}</button>
                                </li>
                            @else
                                <li class="pagination-item">
                                    <a href="{{ $url }}" class="pagination-btn">{{ $page }}</a>
                                </li>
                            @endif
                        @endforeach

                        @if ($logs->hasMorePages())
                            <li class="pagination-item">
                                <a href="{{ $logs->nextPageUrl() }}" class="pagination-btn">»</a>
                            </li>
                        @else
                            <li class="pagination-item disabled">
                                <button type="button" class="pagination-btn" disabled">»</button>
                            </li>
                        @endif
                    </ul>
                </nav>
            </div>
        @else
            <div style="text-align: center; padding: 3rem 1rem;">
                <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc; display: block; margin-bottom: 1rem;"></i>
                <h5 style="color: #999;">No hay envíos registrados</h5>
            </div>
        @endif
    </div>
</div>
@endsection