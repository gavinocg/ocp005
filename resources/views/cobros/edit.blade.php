@extends('layouts.app')
@section('title', 'Editar Cobro - OCP-005')
@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-12">
        <div class="card shadow">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0"><i class="bi bi-pencil"></i> Editar Cobro #{{ $cobro->id }}</h5>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('cobros.update', $cobro) }}" method="POST" id="cobroForm">
                    @csrf @method('PUT')
                    <div class="row g-3">

                        <div class="col-12 mt-3">
                            <h6 class="border-bottom pb-2 mb-3 text-primary"><i class="bi bi-credit-card"></i> Datos de Cuenta</h6>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tipo de Cuenta</label>
                            <select name="tipo_cuenta" class="form-select">
                                <option value="">-- Sin cuenta --</option>
                                <option value="00" {{ $cobro->tipo_cuenta=='00'?'selected':'' }}>00 - Cuenta de Ahorros</option>
                                <option value="10" {{ $cobro->tipo_cuenta=='10'?'selected':'' }}>10 - Cuenta Corriente</option>
                            </select>
                            <small class="text-muted">Posicion 7-8 (2 chars)</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Numero de Cuenta</label>
                            <input type="text" name="numero_cuenta" class="form-control" value="{{ old('numero_cuenta', $cobro->numero_cuenta) }}" maxlength="10" placeholder="10 digitos">
                            <small class="text-muted">Posicion 7-16 (10 chars)</small>
                        </div>

                        <div class="col-12 mt-3">
                            <h6 class="border-bottom pb-2 mb-3 text-primary"><i class="bi bi-currency-dollar"></i> Informacion del Pago</h6>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Valor <i class="bi bi-check-circle-fill text-success"></i></label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" name="valor" id="valor" class="form-control" value="{{ old('valor', $cobro->valor) }}" step="0.01" min="0" required placeholder="0.00">
                            </div>
                            <small class="text-muted">Posicion 17-31 (13 enteros, 2 decimales)</small>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Codigo de Tercero <i class="bi bi-check-circle-fill text-success"></i></label>
                            <input type="number" name="codigo_tercero" id="codigo_tercero" class="form-control" value="{{ old('codigo_tercero', $cobro->codigo_tercero) }}" min="0" max="999999999999999" required placeholder="Hasta 15 dígitos">
                            <small class="text-muted">Posicion 32-46 (hasta 15 digitos - se rellena con espacios en el reporte)</small>
                        </div>
                        <input type="hidden" name="referencia" id="referencia" value="{{ $cobro->referencia }}">
                        <div class="col-md-4">
                            <label class="form-label">Referencia de pago (Número de trámite) <i class="bi bi-check-circle-fill text-success"></i></label>
                            <input type="text" name="referencia_pago" id="referencia_pago" class="form-control bg-light" value="REG_PROP_MERC_CAYAMB" readonly>
                            <small class="text-muted">Referencia constante</small>
                        </div>

                        <div class="col-12 mt-3">
                            <h6 class="border-bottom pb-2 mb-3 text-primary"><i class="bi bi-receipt"></i> Informacion Fiscal (IVA)</h6>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Base Imponible Servicios</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" name="base_imponible_servicios" id="base_imponible_servicios" class="form-control" value="{{ old('base_imponible_servicios', $cobro->base_imponible_servicios) }}" step="0.01" min="0" placeholder="0.00">
                            </div>
                            <small class="text-muted">Posicion 223-232 (8 enteros, 2 decimales)</small>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Valor IVA Servicios</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" name="valor_iva_servicios" id="valor_iva_servicios" class="form-control" value="{{ old('valor_iva_servicios', $cobro->valor_iva_servicios) }}" step="0.01" min="0" placeholder="0.00">
                            </div>
                            <small class="text-muted">Posicion 204-212 (7 enteros, 2 decimales)</small>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Base Imponible Bienes</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" name="base_imponible_bienes" id="base_imponible_bienes" class="form-control" value="{{ old('base_imponible_bienes', $cobro->base_imponible_bienes) }}" step="0.01" min="0" placeholder="0.00">
                            </div>
                            <small class="text-muted">Posicion 233-242 (8 enteros, 2 decimales)</small>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Valor IVA Bienes</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" name="valor_iva_bienes" id="valor_iva_bienes" class="form-control" value="{{ old('valor_iva_bienes', $cobro->valor_iva_bienes) }}" step="0.01" min="0" placeholder="0.00">
                            </div>
                            <small class="text-muted">Posicion 214-222 (7 enteros, 2 decimales)</small>
                        </div>

                        <div class="col-12 mt-3">
                            <h6 class="border-bottom pb-2 mb-3 text-primary"><i class="bi bi-person"></i> Datos del Contribuyente</h6>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tipo de Identificacion <i class="bi bi-check-circle-fill text-success"></i></label>
                            <select name="tipo_id_tercero" id="tipo_id_tercero" class="form-select" required>
                                <option value="C" {{ $cobro->tipo_id_tercero=='C'?'selected':'' }}>C - Cedula</option>
                                <option value="R" {{ $cobro->tipo_id_tercero=='R'?'selected':'' }}>R - RUC</option>
                                <option value="P" {{ $cobro->tipo_id_tercero=='P'?'selected':'' }}>P - Pasaporte</option>
                            </select>
                            <small class="text-muted">Posicion 106 (1 char)</small>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Numero de Identificacion <i class="bi bi-check-circle-fill text-success"></i></label>
                            <input type="text" name="identificacion" id="identificacion" class="form-control" value="{{ old('identificacion', $cobro->identificacion) }}" maxlength="14" required>
                            <small class="text-muted">Posicion 107-120 (14 chars)</small>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Nombre del Contribuyente</label>
                            <input type="text" name="nombre_tercero" class="form-control" value="{{ old('nombre_tercero', $cobro->nombre_tercero) }}" maxlength="30" placeholder="Nombre completo">
                            <small class="text-muted">Posicion 72-101 (30 chars)</small>
                        </div>

                        <div class="col-12 mt-3">
                            <div class="d-flex gap-2 justify-content-end">
                                <a href="{{ route('cobros.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Cancelar</a>
                                <button type="submit" class="btn btn-warning"><i class="bi bi-check-circle"></i> Actualizar Cobro</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded',function(){
    const codigoTercero=document.getElementById('codigo_tercero');
    const referenciaPago=document.getElementById('referencia_pago');
    const referenciaHidden=document.getElementById('referencia');

    function syncReferencia(){
        referenciaHidden.value=referenciaPago.value;
    }

    syncReferencia();

    document.getElementById('cobroForm').addEventListener('submit',function(e){
        syncReferencia();
        if(!codigoTercero.value || codigoTercero.value.length===0){
            e.preventDefault();
            alert('El Codigo de Tercero es obligatorio');
            return false;
        }
        if(codigoTercero.value.length>15){
            e.preventDefault();
            alert('El Codigo de Tercero no puede exceder 15 caracteres');
            return false;
        }
    });
});
</script>
@endpush
