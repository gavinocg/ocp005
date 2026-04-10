<?php

use App\Http\Controllers\CobroPacificoController;
use App\Http\Controllers\ConsultarController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('cobros.index');
});

Route::resource('cobros', CobroPacificoController::class)->except(['show']);
Route::get('/cobros/export', [CobroPacificoController::class, 'export'])->name('cobros.export');

Route::get('/consultar', [ConsultarController::class, 'index'])->name('consultar.index');
Route::get('/consultar/obtener', [ConsultarController::class, 'obtener'])->name('consultar.obtener');
Route::post('/consultar/guardar', [ConsultarController::class, 'guardar'])->name('consultar.guardar');
