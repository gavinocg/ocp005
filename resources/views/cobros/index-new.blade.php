@extends('layouts.app')
@section('title', 'Listar Cobros - OCP-005')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="bi bi-list-ul"></i> Lista de Cobros</h5>
        <div class="d-flex gap-2">
            <a href="{{ route('cobros.export') }}" class="btn btn-success btn-sm">
                <i class="bi bi-download"></i> Exportar TXT
            </a>
            <a href="{{ route('cobros.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-circle"></i> Nuevo Cobro
            </a>
        </div>
    </div>
    
    <div class="card-body">
        <!-- Filtros de búsqueda -->
        <form method="GET" action="{{ route('cobros.index') }}" class="mb-4">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="search" class="form-label">Buscar</label>
                    <input type="text" name="search" id="search" class="form-control" 
                        value="{{ request('search') }}" placeholder="ID, Código, Nombre...">
                </div>
                <div class="col-md-2">
                    <label for="tipo_id" class="form-label">Tipo ID</label>
                    <select name="tipo_id" id="tipo_id" class="form-select">
                        <option value="">Todos</option>
                        <option value="C" {{ request('tipo_id')=='C' ? 'selected' : '' }}>C - Cédula</option>
                        <option value="R" {{ request('tipo_id')=='R' ? 'selected' : '' }}>R - RUC</option>
                        <option value="P" {{ request('tipo_id')=='P' ? 'selected' : '' }}>P - Pasaporte</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="fecha_inicio" class="form-label">Desde</label>
                    <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" 
                        value="{{ request('fecha_inicio') }}">
                </div>
                <div class="col-md-2">
                    <label for="fecha_fin" class="form-label">Hasta</label>
                    <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" 
                        value="{{ request('fecha_fin') }}">
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
                @if(request('search') || request('tipo_id') || request('fecha_inicio') || request('fecha_fin'))
                    <div class="col-md-2">
                        <a href="{{ route('cobros.index') }}" class="btn btn-secondary w-100">
                            <i class="bi bi-arrow-clockwise"></i> Limpiar
                        </a>
                    </div>
                @endif
            </div>
        </form>

        <!-- Tabla de cobros -->
        @if($cobros->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Código Tercero</th>
                            <th>Tipo ID</th>
                            <th>Identificación</th>
                            <th>Nombre</th>
                            <th class="text-end">Valor</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cobros as $cobro)
                            <tr>
                                <td>
                                    <small class="fw-600">{{ $cobro->codigo_tercero }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $cobro->tipo_id_tercero }}</span>
                                </td>
                                <td>
                                    <small>{{ $cobro->identificacion }}</small>
                                </td>
                                <td>
                                    <small>{{ $cobro->nombre_tercero }}</small>
                                </td>
                                <td class="text-end">
                                    <strong>${{ number_format($cobro->valor, 2, ',', '.') }}</strong>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('cobros.edit', $cobro) }}" class="btn btn-warning" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('cobros.destroy', $cobro) }}" method="POST" class="d-inline" 
                                            onsubmit="return confirm('¿Eliminar este cobro?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-danger" title="Eliminar">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Paginación y resumen -->
            <div style="margin-top: 2rem;">
                {{ $cobros->links() }}
            </div>

            <!-- Resumen de datos -->
            <div class="row mt-3">
                <div class="col-md-6">
                    <div style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; padding: 1.5rem; border-radius: 0.5rem;">
                        <div style="font-size: 0.9rem; opacity: 0.9;">Total de Registros</div>
                        <div style="font-size: 2rem; font-weight: 700;">{{ $totalRegistros }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 1.5rem; border-radius: 0.5rem;">
                        <div style="font-size: 0.9rem; opacity: 0.9;">Valor Total</div>
                        <div style="font-size: 2rem; font-weight: 700;">${{ number_format($valorTotal, 2, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        @else
            <div style="text-align: center; padding: 3rem 1rem;">
                <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc; display: block; margin-bottom: 1rem;"></i>
                <h5 style="color: #999;">No se encontraron cobros</h5>
                <p style="color: #bbb;">Intenta con otros criterios de búsqueda o crea uno nuevo.</p>
                <a href="{{ route('cobros.create') }}" class="btn btn-primary mt-3">
                    <i class="bi bi-plus-circle"></i> Crear Nuevo Cobro
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
