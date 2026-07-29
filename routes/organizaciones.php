<?php

use App\Http\Controllers\OrganizacionController;
use Illuminate\Support\Facades\Route;

Route::get('/organizaciones', [OrganizacionController::class, 'index'])->name('organizaciones.index');
Route::get('/organizaciones/crear', [OrganizacionController::class, 'create'])->name('organizaciones.create');
Route::post('/organizaciones', [OrganizacionController::class, 'store'])->name('organizaciones.store');
Route::get('/organizaciones/{organizacion}/editar', [OrganizacionController::class, 'edit'])->name('organizaciones.edit');
Route::put('/organizaciones/{organizacion}', [OrganizacionController::class, 'update'])->name('organizaciones.update');
Route::patch('/organizaciones/{organizacion}/toggle-activo', [OrganizacionController::class, 'toggleActivo'])->name('organizaciones.toggleActivo');
