<?php

use App\Http\Controllers\ConfiguracionController;
use Illuminate\Support\Facades\Route;

Route::get('/configuracion', [ConfiguracionController::class, 'edit'])->name('configuracion.edit');
Route::put('/configuracion', [ConfiguracionController::class, 'update'])->name('configuracion.update');
Route::post('/configuracion/catalogos/{tipo}', [ConfiguracionController::class, 'storeCatalogo'])->name('configuracion.catalogos.store');
Route::patch('/configuracion/catalogos/{tipo}/{id}/toggle', [ConfiguracionController::class, 'toggleCatalogoItem'])->name('configuracion.catalogos.toggle');
Route::post('/configuracion/link-publico/regenerar', [ConfiguracionController::class, 'regenerarTokenPublico'])->name('configuracion.regenerarTokenPublico');
