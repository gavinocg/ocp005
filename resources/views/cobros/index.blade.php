@extends('layouts.app')
@section('title', 'Listar Cobros - OCP-005')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h5 class="mb-0"><i class="bi bi-list-ul"></i> Lista de Cobros</h5>
        <div class="d-flex gap-2 align-items-center">
            @if($cobros->count() > 0)
            <div class="d-flex align-items-center gap-2">
                <label class="text-white small mb-0">Mostrar</label>
                <select class="form-select form-select-sm" style="width: auto;" onchange="window.location.href = this.value">
                    <option value="{{ $cobros->appends(request()->except('page'))->url(1) . '&per_page=10' }}" {{ $cobros->perPage() == 10 ? 'selected' : '' }}>10</option>
                    <option value="{{ $cobros->appends(request()->except('page'))->url(1) . '&per_page=20' }}" {{ $cobros->perPage() == 20 ? 'selected' : '' }}>20</option>
                    <option value="{{ $cobros->appends(request()->except('page'))->url(1) . '&per_page=50' }}" {{ $cobros->perPage() == 50 ? 'selected' : '' }}>50</option>
                    <option value="{{ $cobros->appends(request()->except('page'))->url(1) . '&per_page=99999' }}" {{ $cobros->perPage() >= 9999 ? 'selected' : '' }}>Todos</option>
                </select>
            </div>
            @endif
            <button type="button" class="btn btn-success btn-sm" onclick="exportCobros()">
                <i class="bi bi-download"></i> Exportar TXT
            </button>
            <a href="{{ route('envios.index') }}" class="btn btn-info btn-sm">
                <i class="bi bi-file-earmark-text"></i> Log Envíos
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
                <div class="col-md-2">
                    <label for="codigo_tercero" class="form-label">Código Tercero</label>
                    <input type="text" name="codigo_tercero" id="codigo_tercero" class="form-control" 
                        value="{{ request('codigo_tercero') }}" placeholder="Código">
                </div>
                <div class="col-md-2">
                    <label for="identificacion" class="form-label">Identificación</label>
                    <input type="text" name="identificacion" id="identificacion" class="form-control" 
                        value="{{ request('identificacion') }}" placeholder="Identificación">
                </div>
                <div class="col-md-2">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" name="nombre" id="nombre" class="form-control" 
                        value="{{ request('nombre') }}" placeholder="Nombre">
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
                    <label for="valor_min" class="form-label">Valor Desde</label>
                    <input type="number" name="valor_min" id="valor_min" class="form-control" 
                        value="{{ request('valor_min') }}" placeholder="0.00" step="0.01">
                </div>
                <div class="col-md-2">
                    <label for="valor_max" class="form-label">Valor Hasta</label>
                    <input type="number" name="valor_max" id="valor_max" class="form-control" 
                        value="{{ request('valor_max') }}" placeholder="0.00" step="0.01">
                </div>
                <div class="col-md-2">
                    <label for="fecha_creacion_inicio" class="form-label">Fecha Creación Desde</label>
                    <input type="date" name="fecha_creacion_inicio" id="fecha_creacion_inicio" class="form-control" 
                        value="{{ request('fecha_creacion_inicio') }}">
                </div>
                <div class="col-md-2">
                    <label for="fecha_creacion_fin" class="form-label">Fecha Creación Hasta</label>
                    <input type="date" name="fecha_creacion_fin" id="fecha_creacion_fin" class="form-control" 
                        value="{{ request('fecha_creacion_fin') }}">
                </div>
                <div class="col-md-2">
                    <label for="numero_lote" class="form-label">Nº Lote</label>
                    <input type="text" name="numero_lote" id="numero_lote" class="form-control" 
                        value="{{ request('numero_lote') }}" placeholder="Nº Lote">
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
                @if(request()->hasAny(['codigo_tercero', 'identificacion', 'nombre', 'tipo_id', 'valor_min', 'valor_max', 'fecha_creacion_inicio', 'fecha_creacion_fin', 'numero_lote']))
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
            <!-- Barra de acciones en lote -->
            <div class="mb-3 d-flex gap-2" id="bulkActionsBar" style="display: none;">
                <span class="text-muted align-self-center" id="selectedCount">0 seleccionados</span>
                <button type="button" class="btn btn-danger btn-sm" id="bulkDeleteBtn">
                    <i class="bi bi-trash"></i> Eliminar seleccionados
                </button>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th width="40">
                                <input type="checkbox" id="selectAll" class="form-check-input">
                            </th>
                            <th>
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'codigo_tercero', 'direction' => request('sort') == 'codigo_tercero' && request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="text-decoration-none text-dark">
                                    Código Tercero <i class="bi bi-arrow-down-up"></i>
                                </a>
                            </th>
                            <th>
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'tipo_id_tercero', 'direction' => request('sort') == 'tipo_id_tercero' && request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="text-decoration-none text-dark">
                                    Tipo ID <i class="bi bi-arrow-down-up"></i>
                                </a>
                            </th>
                            <th>
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'identificacion', 'direction' => request('sort') == 'identificacion' && request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="text-decoration-none text-dark">
                                    Identificación <i class="bi bi-arrow-down-up"></i>
                                </a>
                            </th>
                            <th>
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'nombre_tercero', 'direction' => request('sort') == 'nombre_tercero' && request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="text-decoration-none text-dark">
                                    Nombre <i class="bi bi-arrow-down-up"></i>
                                </a>
                            </th>
                            <th class="text-end">
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'valor', 'direction' => request('sort') == 'valor' && request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="text-decoration-none text-dark">
                                    Valor <i class="bi bi-arrow-down-up"></i>
                                </a>
                            </th>
                            <th>
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'direction' => request('sort') == 'created_at' && request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="text-decoration-none text-dark">
                                    Fecha Creación <i class="bi bi-arrow-down-up"></i>
                                </a>
                            </th>
                            <th>
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'numero_lote', 'direction' => request('sort') == 'numero_lote' && request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="text-decoration-none text-dark">
                                    N° Lote <i class="bi bi-arrow-down-up"></i>
                                </a>
                            </th>
                            <th>
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'fecha_lote', 'direction' => request('sort') == 'fecha_lote' && request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="text-decoration-none text-dark">
                                    Fecha Lote <i class="bi bi-arrow-down-up"></i>
                                </a>
                            </th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cobros as $cobro)
                            <tr>
                                <td>
                                    @if($cobro->numero_lote)
                                        <input type="checkbox" class="form-check-input" disabled title="Ya enviado en lote {{ $cobro->numero_lote }}">
                                    @else
                                        <input type="checkbox" class="form-check-input row-checkbox" value="{{ $cobro->id }}">
                                    @endif
                                </td>
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
                                <td>
                                    <small class="text-muted">{{ $cobro->created_at->format('d/m/Y H:i') }}</small>
                                </td>
                                <td>
                                    @if($cobro->numero_lote)
                                        <span class="badge bg-success">{{ $cobro->numero_lote }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($cobro->fecha_lote)
                                        <small class="text-muted">{{ $cobro->fecha_lote->format('d/m/Y H:i') }}</small>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a href="{{ route('cobros.edit', $cobro) }}" class="dropdown-item">
                                                    <i class="bi bi-pencil text-warning"></i> Editar
                                                </a>
                                            </li>
                                            <li>
                                                <form action="{{ route('cobros.destroy', $cobro) }}" method="POST" class="d-inline">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger" onclick="return confirm('¿Eliminar este cobro?')">
                                                        <i class="bi bi-trash"></i> Eliminar
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Paginación y resumen -->
            <div class="pagination-wrapper">
                <div class="pagination-info">
                    <span class="info-text">
                        Mostrando <strong>{{ $cobros->firstItem() ?? 0 }}</strong> a 
                        <strong>{{ $cobros->lastItem() ?? 0 }}</strong> de 
                        <strong>{{ $cobros->total() }}</strong> registros
                    </span>
                </div>
                <nav aria-label="Paginación">
                    <ul class="pagination-list">
                        {{-- First Page --}}
                        @if ($cobros->onFirstPage())
                            <li class="pagination-item disabled">
                                <button type="button" class="pagination-btn" disabled>««</button>
                            </li>
                        @else
                            <li class="pagination-item">
                                <a href="{{ $cobros->url(1) }}" class="pagination-btn">««</a>
                            </li>
                        @endif

                        {{-- Previous --}}
                        @if ($cobros->onFirstPage())
                            <li class="pagination-item disabled">
                                <button type="button" class="pagination-btn" disabled>«</button>
                            </li>
                        @else
                            <li class="pagination-item">
                                <a href="{{ $cobros->previousPageUrl() }}" class="pagination-btn">«</a>
                            </li>
                        @endif

                        {{-- Pages --}}
                        @foreach ($cobros->getUrlRange(max(1, $cobros->currentPage() - 2), min($cobros->lastPage(), $cobros->currentPage() + 2)) as $page => $url)
                            @if ($page == $cobros->currentPage())
                                <li class="pagination-item active">
                                    <button type="button" class="pagination-btn active">{{ $page }}</button>
                                </li>
                            @else
                                <li class="pagination-item">
                                    <a href="{{ $url }}" class="pagination-btn">{{ $page }}</a>
                                </li>
                            @endif
                        @endforeach

                        {{-- Next --}}
                        @if ($cobros->hasMorePages())
                            <li class="pagination-item">
                                <a href="{{ $cobros->nextPageUrl() }}" class="pagination-btn">»</a>
                            </li>
                        @else
                            <li class="pagination-item disabled">
                                <button type="button" class="pagination-btn" disabled>»</button>
                            </li>
                        @endif

                        {{-- Last Page --}}
                        @if ($cobros->hasMorePages())
                            <li class="pagination-item">
                                <a href="{{ $cobros->url($cobros->lastPage()) }}" class="pagination-btn">»»</a>
                            </li>
                        @else
                            <li class="pagination-item disabled">
                                <button type="button" class="pagination-btn" disabled>»»</button>
                            </li>
                        @endif
                    </ul>
                    </nav>
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

@push('scripts')
<script>
(function() {
    var bulkBar = document.getElementById('bulkActionsBar');
    var selectedCount = document.getElementById('selectedCount');
    var bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
    var selectAll = document.getElementById('selectAll');

    function updateBulkBar() {
        var checkboxes = document.querySelectorAll('.row-checkbox:not([disabled])');
        var count = 0;
        for (var i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i].checked) count++;
        }
        console.log('Update:', checkboxes.length, 'Count:', count);
        selectedCount.textContent = count + ' seleccionado' + (count > 1 ? 's' : '');
        if (count > 0) {
            bulkBar.style.display = 'flex';
        } else {
            bulkBar.style.display = 'none';
        }
    }

    if (selectAll) {
        selectAll.addEventListener('change', function() {
            var checkboxes = document.querySelectorAll('.row-checkbox:not([disabled])');
            for (var i = 0; i < checkboxes.length; i++) {
                checkboxes[i].checked = selectAll.checked;
            }
            updateBulkBar();
        });
    }

    var tbody = document.querySelector('tbody');
    if (tbody) {
        tbody.onchange = function(e) {
            if (e.target && e.target.classList.contains('row-checkbox')) {
                updateBulkBar();
            }
        };
    }

    setTimeout(updateBulkBar, 100);

    bulkDeleteBtn.addEventListener('click', function() {
        const selected = Array.from(document.querySelectorAll('.row-checkbox:checked')).map(cb => cb.value);
        if (selected.length === 0) return;
        
        if (confirm('¿Eliminar los ' + selected.length + ' registros seleccionados?')) {
            fetch('{{ route("cobros.bulkDestroy") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ ids: selected })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert(data.message || 'Error al eliminar');
                }
            })
            .catch(err => alert('Error: ' + err.message));
        }
    });

    window.exportCobros = function() {
        const selected = Array.from(document.querySelectorAll('.row-checkbox:checked')).map(cb => cb.value);
        
        if (selected.length > 0) {
            if (!confirm('¿Exportar solo los ' + selected.length + ' registros seleccionados?')) {
                return;
            }
            fetch('{{ route("cobros.export") }}?tipo=seleccionados&ids=' + selected.join(','))
            .then(response => response.blob())
            .then(blob => {
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'cobros_pacifico_LOTE-' + new Date().toISOString().replace(/[-:]/g, '').slice(0, 15) + '.txt';
                a.click();
                window.URL.revokeObjectURL(url);
                window.location.reload();
            })
            .catch(err => alert('Error: ' + err.message));
        } else {
            const link = document.createElement('a');
            link.href = '{{ route("cobros.export") }}?tipo=pendientes';
            link.download = 'cobros_pacifico_LOTE-' + new Date().toISOString().replace(/[-:]/g, '').slice(0, 15) + '.txt';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            setTimeout(() => window.location.reload(), 1000);
        }
    };
})();
</script>
@endpush
@endsection
