@extends('layouts.app')
@section('title', 'Listar Cobros - OCP-005')
@section('content')
<div class="card shadow">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="bi bi-list-ul"></i> Lista de Cobros</h5>
        <div class="d-flex gap-2">
            <a href="{{ route('cobros.export') }}" class="btn btn-light btn-sm"><i class="bi bi-download"></i> Exportar TXT</a>
            <a href="{{ route('cobros.create') }}" class="btn btn-light btn-sm"><i class="bi bi-plus-circle"></i> Nuevo</a>
        </div>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('cobros.index') }}" class="mb-4">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="search" class="form-label small">Buscar</label>
                    <input type="text" name="search" id="search" class="form-control form-control-sm" value="{{ request('search') }}" placeholder="Identificacion, Codigo...">
                </div>
                <div class="col-md-2">
                    <label for="tipo_id" class="form-label small">Tipo ID</label>
                    <select name="tipo_id" id="tipo_id" class="form-select form-select-sm">
                        <option value="">Todos</option>
                        <option value="C" {{ request('tipo_id')=='C' ? 'selected' : '' }}>C - Cedula</option>
                        <option value="R" {{ request('tipo_id')=='R' ? 'selected' : '' }}>R - RUC</option>
                        <option value="P" {{ request('tipo_id')=='P' ? 'selected' : '' }}>P - Pasaporte</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="fecha_inicio" class="form-label small">Desde</label>
                    <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control form-control-sm" value="{{ request('fecha_inicio') }}">
                </div>
                <div class="col-md-2">
                    <label for="fecha_fin" class="form-label small">Hasta</label>
                    <input type="date" name="fecha_fin" id="fecha_fin" class="form-control form-control-sm" value="{{ request('fecha_fin') }}">
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary btn-sm w-100"><i class="bi bi-search"></i></button>
                </div>
            </div>
        </form>

        @if($cobros->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover table-bordered table-sm">
                <thead class="table-light">
                    <tr>
                        <th>ID</th><th>Valor</th><th>Cod. Tercero</th><th>Referencia</th><th>Tipo ID</th><th>Identificacion</th><th>Nombre</th><th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cobros as $cobro)
                    <tr>
                        <td>{{ $cobro->id }}</td>
                        <td class="text-end">${{ number_format($cobro->valor, 2) }}</td>
                        <td><small>{{ $cobro->codigo_tercero }}</small></td>
                        <td><small class="text-muted">{{ Str::limit($cobro->referencia, 10) }}</small></td>
                        <td><span class="badge bg-secondary">{{ $cobro->tipo_id_tercero }}</span></td>
                        <td><small>{{ $cobro->identificacion }}</small></td>
                        <td><small>{{ $cobro->nombre_tercero }}</small></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('cobros.edit', $cobro) }}" class="btn btn-warning"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('cobros.destroy', $cobro) }}" method="POST" class="d-inline" onsubmit="return confirm('Eliminar?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="text-muted small">Mostrando {{ $cobros->firstItem()??0 }} - {{ $cobros->lastItem()??0 }} de {{ $cobros->total() }} registros</div>
            <div>{{ $cobros->links() }}</div>
        </div>
        <div class="alert alert-info mb-0 mt-3">
            <strong>Total:</strong> {{ $totalRegistros }} registros | <strong>Valor:</strong> ${{ number_format($valorTotal, 2) }}
        </div>
        @else
        <div class="alert alert-warning text-center mb-0">
            <i class="bi bi-search"></i> No se encontraron cobros.
            <a href="{{ route('cobros.create') }}" class="btn btn-primary mt-2"><i class="bi bi-plus-circle"></i> Crear</a>
        </div>
        @endif
    </div>
</div>
@endsection
