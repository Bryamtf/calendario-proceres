<?php

use App\Http\Controllers\FechaEspecialController;
use Illuminate\Support\Facades\Route;

Route::get('/fechas-especiales', [FechaEspecialController::class, 'index'])->name('fechas-especiales.index');
Route::get('/fechas-especiales/crear', [FechaEspecialController::class, 'create'])->name('fechas-especiales.create');
Route::post('/fechas-especiales', [FechaEspecialController::class, 'store'])->name('fechas-especiales.store');
Route::get('/fechas-especiales/{fechaEspecial}/editar', [FechaEspecialController::class, 'edit'])->name('fechas-especiales.edit');
Route::put('/fechas-especiales/{fechaEspecial}', [FechaEspecialController::class, 'update'])->name('fechas-especiales.update');
Route::delete('/fechas-especiales/{fechaEspecial}', [FechaEspecialController::class, 'destroy'])->name('fechas-especiales.destroy');
