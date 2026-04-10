@extends('layouts.app')
@section('title', 'Nuevo Cobro - OCP-005')
@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-12">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-plus-circle"></i> Registrar Nuevo Cobro</h5>
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

                <form action="{{ route('cobros.store') }}" method="POST" id="cobroForm">
                    @csrf
                    <div class="row g-3">

                        <div class="col-12 mt-3">
                            <h6 class="border-bottom pb-2 mb-3 text-primary"><i class="bi bi-credit-card"></i> Datos de Cuenta</h6>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tipo de Cuenta</label>
                            <select name="tipo_cuenta" class="form-select">
                                <option value="">-- Sin cuenta --</option>
                                <option value="00" {{ old('tipo_cuenta')=='00'?'selected':'' }}>00 - Cuenta de Ahorros</option>
                                <option value="10" {{ old('tipo_cuenta')=='10'?'selected':'' }}>10 - Cuenta Corriente</option>
                            </select>
                            <small class="text-muted">Posicion 7-8 (2 chars)</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Numero de Cuenta</label>
                            <input type="text" name="numero_cuenta" class="form-control" value="{{ old('numero_cuenta') }}" maxlength="10" placeholder="10 digitos (completar con ceros para recaudaciones)">
                            <small class="text-muted">Posicion 7-16 (10 chars). Para recaudaciones enviar 10 ceros.</small>
                        </div>

                        <div class="col-12 mt-3">
                            <h6 class="border-bottom pb-2 mb-3 text-primary"><i class="bi bi-currency-dollar"></i> Informacion del Pago</h6>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Valor <i class="bi bi-check-circle-fill text-success"></i></label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" name="valor" id="valor" class="form-control @error('valor') is-invalid @enderror" value="{{ old('valor') }}" step="0.01" min="0" required placeholder="0.00">
                            </div>
                            @error('valor')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <small class="text-muted">Posicion 17-31 (13 enteros, 2 decimales sin separacion)</small>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Codigo de Tercero <i class="bi bi-check-circle-fill text-success"></i></label>
                            <input type="number" name="codigo_tercero" id="codigo_tercero" class="form-control @error('codigo_tercero') is-invalid @enderror" value="{{ old('codigo_tercero') }}" min="0" max="999999999999999" required placeholder="Hasta 15 dígitos">
                            @error('codigo_tercero')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <small class="text-muted">Posicion 32-46 (hasta 15 digitos - se rellena con espacios en el reporte)</small>
                        </div>
                        <input type="hidden" name="referencia" id="referencia" value="{{ old('referencia') }}">
                        <div class="col-md-4">
                            <label class="form-label">Referencia de pago <i class="bi bi-check-circle-fill text-success"></i></label>
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
                                <input type="number" name="base_imponible_servicios" id="base_imponible_servicios" class="form-control" value="{{ old('base_imponible_servicios','0.00') }}" step="0.01" min="0" placeholder="0.00">
                            </div>
                            <small class="text-muted">Posicion 223-232 (8 enteros, 2 decimales)</small>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Valor IVA Servicios</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" name="valor_iva_servicios" id="valor_iva_servicios" class="form-control" value="{{ old('valor_iva_servicios','0.00') }}" step="0.01" min="0" placeholder="0.00">
                            </div>
                            <small class="text-muted">Posicion 204-212 (7 enteros, 2 decimales)</small>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Base Imponible Bienes</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" name="base_imponible_bienes" id="base_imponible_bienes" class="form-control" value="{{ old('base_imponible_bienes','0.00') }}" step="0.01" min="0" placeholder="0.00">
                            </div>
                            <small class="text-muted">Posicion 233-242 (8 enteros, 2 decimales)</small>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Valor IVA Bienes</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" name="valor_iva_bienes" id="valor_iva_bienes" class="form-control" value="{{ old('valor_iva_bienes','0.00') }}" step="0.01" min="0" placeholder="0.00">
                            </div>
                            <small class="text-muted">Posicion 214-222 (7 enteros, 2 decimales)</small>
                        </div>

                        <div class="col-12 mt-3">
                            <h6 class="border-bottom pb-2 mb-3 text-primary"><i class="bi bi-person"></i> Datos del Contribuyente</h6>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tipo de Identificacion <i class="bi bi-check-circle-fill text-success"></i></label>
                            <select name="tipo_id_tercero" id="tipo_id_tercero" class="form-select @error('tipo_id_tercero') is-invalid @enderror" required>
                                <option value="">Seleccione...</option>
                                <option value="C" {{ old('tipo_id_tercero')=='C'?'selected':'' }}>C - Cedula</option>
                                <option value="R" {{ old('tipo_id_tercero')=='R'?'selected':'' }}>R - RUC</option>
                                <option value="P" {{ old('tipo_id_tercero')=='P'?'selected':'' }}>P - Pasaporte</option>
                            </select>
                            @error('tipo_id_tercero')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <small class="text-muted">Posicion 106 (1 char)</small>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Numero de Identificacion <i class="bi bi-check-circle-fill text-success"></i></label>
                            <input type="text" name="identificacion" id="identificacion" class="form-control @error('identificacion') is-invalid @enderror" value="{{ old('identificacion') }}" maxlength="14" required placeholder="Hasta 14 digitos">
                            @error('identificacion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <small class="text-muted">Posicion 107-120 (14 chars)</small>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Nombre del Contribuyente</label>
                            <input type="text" name="nombre_tercero" class="form-control" value="{{ old('nombre_tercero') }}" maxlength="30" placeholder="Nombre completo">
                            <small class="text-muted">Posicion 72-101 (30 chars)</small>
                        </div>

                        <div class="col-12 mt-3">
                            <div class="alert alert-secondary">
                                <h6><i class="bi bi-file-text"></i> Formato del Archivo TXT (242 caracteres por registro)</h6>
                                <div class="row small">
                                    <div class="col-md-3"><strong>Pos 1:</strong> Tipo Archivo = "1" (fijo)</div>
                                    <div class="col-md-3"><strong>Pos 2-4:</strong> Transaccion = "OCP" (fijo)</div>
                                    <div class="col-md-3"><strong>Pos 5-6:</strong> Servicio (2)</div>
                                    <div class="col-md-3"><strong>Pos 7-16:</strong> Numero Cuenta (10)</div>
                                </div>
                                <div class="row small mt-1">
                                    <div class="col-md-3"><strong>Pos 17-31:</strong> Valor (15)</div>
                                    <div class="col-md-3"><strong>Pos 32-46:</strong> CodTercero (15)</div>
                                    <div class="col-md-3"><strong>Pos 47-66:</strong> Referencia (20)</div>
                                    <div class="col-md-3"><strong>Pos 67-68:</strong> FormaPago (2)</div>
                                </div>
                                <div class="row small mt-1">
                                    <div class="col-md-3"><strong>Pos 69-71:</strong> Moneda (3)</div>
                                    <div class="col-md-3"><strong>Pos 72-101:</strong> Nombre (30)</div>
                                    <div class="col-md-3"><strong>Pos 102-105:</strong> No usado (espacios)</div>
                                    <div class="col-md-3"><strong>Pos 106:</strong> TipoID (1)</div>
                                </div>
                                <div class="row small mt-1">
                                    <div class="col-md-3"><strong>Pos 107-120:</strong> Identificacion (14)</div>
                                    <div class="col-md-3"><strong>Pos 121-203:</strong> No usado (espacios)</div>
                                    <div class="col-md-3"><strong>Pos 204-212:</strong> Valor IVA Svcs (9)</div>
                                    <div class="col-md-3"><strong>Pos 213:</strong> Tipo Prestacion = "A"</div>
                                </div>
                                <div class="row small mt-1">
                                    <div class="col-md-3"><strong>Pos 214-222:</strong> Valor IVA Bienes (9)</div>
                                    <div class="col-md-3"><strong>Pos 223-232:</strong> Base Imponible Svcs (10)</div>
                                    <div class="col-md-3"><strong>Pos 233-242:</strong> Base Imponible Bienes (10)</div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 mt-3">
                            <div class="d-flex gap-2 justify-content-end">
                                <a href="{{ route('cobros.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Cancelar</a>
                                <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Guardar Cobro</button>
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
