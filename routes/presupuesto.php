<?php

use App\Http\Controllers\PresupuestoController;
use Illuminate\Support\Facades\Route;

Route::get('/presupuesto', [PresupuestoController::class, 'index'])->name('presupuesto.index');
Route::put('/presupuesto/{organizacion}', [PresupuestoController::class, 'update'])->name('presupuesto.update');
