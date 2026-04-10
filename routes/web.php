<?php

use App\Http\Controllers\CobroPacificoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('cobros.index');
});

Route::resource('cobros', CobroPacificoController::class)->except(['show']);
Route::get('/cobros/export', [CobroPacificoController::class, 'export'])->name('cobros.export');
