<?php

use App\Http\Controllers\CobroPacificoController;
use App\Http\Controllers\ConsultarController;
use App\Http\Controllers\EnvioLogController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('cobros.index');
});

Route::resource('cobros', CobroPacificoController::class)->except(['show']);
Route::get('/cobros/export', [CobroPacificoController::class, 'export'])->name('cobros.export');
Route::post('/cobros/bulk-destroy', [CobroPacificoController::class, 'bulkDestroy'])->name('cobros.bulkDestroy');

Route::get('/consultar', [ConsultarController::class, 'index'])->name('consultar.index');
Route::get('/consultar/obtener', [ConsultarController::class, 'obtener'])->name('consultar.obtener');
Route::post('/consultar/guardar', [ConsultarController::class, 'guardar'])->name('consultar.guardar');

Route::get('/envios', [EnvioLogController::class, 'index'])->name('envios.index');
Route::get('/envios/{envio}/regenerate', [EnvioLogController::class, 'regenerate'])->name('envios.regenerate');
Route::delete('/envios/{envio}', [EnvioLogController::class, 'destroy'])->name('envios.destroy');
