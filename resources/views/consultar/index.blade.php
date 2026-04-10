@extends('layouts.app')
@section('title', 'Consultar - OCP-005')
@section('content')
<div class="card shadow">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="bi bi-database-search"></i> Consultar Registros desde Oracle</h5>
    </div>
    <div class="card-body">
        <form id="consultarForm" class="mb-4">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="search" class="form-label small">Buscar</label>
                    <input type="text" name="search" id="search" class="form-control form-control-sm" placeholder="Todos">
                </div>
                <div class="col-md-2">
                    <label for="tipo_id" class="form-label small">Tipo ID</label>
                    <select name="tipo_id" id="tipo_id" class="form-select form-select-sm">
                        <option value="">Todos</option>
                        <option value="CEDULA">Cedula</option>
                        <option value="RUC">RUC</option>
                        <option value="PASAPORTE">Pasaporte</option>
                    </select>
                </div>
                <!--
                <div class="col-md-2">
                    <label for="fecha_inicio" class="form-label small">Desde</label>
                    <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control form-control-sm">
                </div>
                <div class="col-md-2">
                    <label for="fecha_fin" class="form-label small">Hasta</label>
                    <input type="date" name="fecha_fin" id="fecha_fin" class="form-control form-control-sm">
                </div>
                -->
                <div class="col-md-2">
                    <button type="button" id="btnObtener" class="btn btn-primary btn-sm w-100"><i class="bi bi-database"></i> Obtener</button>
                </div>
                <div class="col-md-2">
                    <button type="button" id="btnGuardar" class="btn btn-success btn-sm w-100" disabled><i class="bi bi-save"></i> Guardar</button>
                </div>
            </div>
        </form>

        <div id="resultsArea" style="display: none;">
            <div class="table-responsive">
                <table class="table table-hover table-bordered table-sm" id="resultsTable">
                    <thead class="table-light">
                        <tr>
                            <th width="40">
                                <input type="checkbox" id="selectAll">
                            </th>
                            <th>TRAMITE</th>
                            <th>TIPO ID</th>
                            <th>IDENTIFICACION</th>
                            <th>NOMBRE</th>                            
                            <th>VALOR</th>
                        </tr>
                    </thead>
                    <tbody id="resultsBody">
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted small" id="totalCount"></div>
            </div>
        </div>

        <div id="emptyMessage" class="alert alert-warning text-center mb-0" style="display: none;">
            <i class="bi bi-info-circle"></i> Haga clic en "Obtener" para buscar registros desde Oracle.
        </div>

        <div id="loadingMessage" class="alert alert-info text-center mb-0" style="display: none;">
            <i class="bi bi-arrow-repeat animate-spin"></i> Consultando Oracle...
        </div>
        
        <!-- Overlay de carga para guardar -->
        <div id="saveOverlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; justify-content: center; align-items: center;">
            <div class="text-center bg-white p-4 rounded shadow-lg" style="min-width: 280px;">
                <div id="saveSpinner" class="spinner-border text-primary mb-3" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <div id="saveMessage" class="fw-bold text-primary">Guardando... por favor espere</div>
                <div id="saveSuccess" class="fw-bold text-success" style="display: none;">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <div id="saveError" class="fw-bold text-danger" style="display: none;">
                    <i class="bi bi-exclamation-circle-fill"></i>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function() {
    let tramitesData = [];

    document.getElementById('btnObtener').addEventListener('click', function() {
        const search = document.getElementById('search').value;
        const tipoId = document.getElementById('tipo_id').value;
        const btn = this;

        document.getElementById('loadingMessage').style.display = 'block';
        document.getElementById('resultsArea').style.display = 'none';
        document.getElementById('emptyMessage').style.display = 'none';
        btn.disabled = true;

        fetch('{{ route("consultar.obtener") }}?' + new URLSearchParams({
            search: search,
            tipo_id: tipoId
        }).toString())
        .then(response => response.json())
        .then(data => {
            document.getElementById('loadingMessage').style.display = 'none';
            btn.disabled = false;

            if (data.success && data.data.length > 0) {
                tramitesData = data.data;
                renderTable(data.data);
                document.getElementById('resultsArea').style.display = 'block';
                document.getElementById('emptyMessage').style.display = 'none';
            } else {
                tramitesData = [];
                renderTable([]);
                document.getElementById('emptyMessage').innerHTML = '<i class="bi bi-exclamation-circle"></i> ' + (data.message || 'No se encontraron registros');
                document.getElementById('emptyMessage').style.display = 'block';
            }
        })
        .catch(error => {
            document.getElementById('loadingMessage').style.display = 'none';
            btn.disabled = false;
            alert('Error al consultar: ' + error.message);
        });
    });

    function renderTable(data) {
        const tbody = document.getElementById('resultsBody');
        tbody.innerHTML = '';

        data.forEach(function(item, index) {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td><input type="checkbox" class="row-checkbox" data-index="${index}"></td>
                <td><small>${item.TRAMITE || ''}</small></td>
                <td><span class="badge bg-secondary">${item.TIP_IDENTIFICACION || ''}</span></td>
                <td><small>${item.IDENTIFICACION || ''}</small></td>
                <td><small>${item.NOMBRE || ''}</small></td>                
                <td class="text-end">${parseFloat(item.VALOR || 0).toFixed(2)}</td>
            `;
            tbody.appendChild(tr);
        });

        document.getElementById('totalCount').textContent = data.length + ' registros';

        document.querySelectorAll('.row-checkbox').forEach(cb => {
            cb.addEventListener('change', updateGuardarButton);
        });
    }

    document.getElementById('selectAll').addEventListener('change', function() {
        const checked = this.checked;
        document.querySelectorAll('.row-checkbox').forEach(cb => {
            cb.checked = checked;
        });
        updateGuardarButton();
    });

    function updateGuardarButton() {
        const checkedCount = document.querySelectorAll('.row-checkbox:checked').length;
        document.getElementById('btnGuardar').disabled = checkedCount === 0;
    }

    document.getElementById('btnGuardar').addEventListener('click', function() {
        const selected = [];
        document.querySelectorAll('.row-checkbox:checked').forEach(cb => {
            const index = cb.dataset.index;
            selected.push(tramitesData[index]);
        });

        if (selected.length === 0) {
            alert('No hay registros seleccionados');
            return;
        }

        document.getElementById('saveOverlay').style.display = 'flex';

        fetch('{{ route("consultar.guardar") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ selected: selected })
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('saveSpinner').style.display = 'none';
            document.getElementById('saveMessage').style.display = 'none';
            
            if (data.success) {
                document.getElementById('saveSuccess').textContent = data.count + ' registros guardados correctamente';
                document.getElementById('saveSuccess').style.display = 'block';
                setTimeout(() => {
                    window.location.href = '{{ route("cobros.index") }}';
                }, 1500);
            } else {
                document.getElementById('saveError').textContent = data.message || 'Error al guardar';
                document.getElementById('saveError').style.display = 'block';
            }
        })
        .catch(error => {
            document.getElementById('saveSpinner').style.display = 'none';
            document.getElementById('saveMessage').style.display = 'none';
            document.getElementById('saveError').textContent = 'Error al guardar: ' + error.message;
            document.getElementById('saveError').style.display = 'block';
        });
    });
})();
</script>
@endpush