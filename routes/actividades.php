<?php

use App\Http\Controllers\ActividadController;
use Illuminate\Support\Facades\Route;

Route::get('/actividades', [ActividadController::class, 'index'])->name('actividades.index');
Route::get('/actividades/crear', [ActividadController::class, 'create'])->name('actividades.create');
Route::post('/actividades', [ActividadController::class, 'store'])->name('actividades.store');
Route::post('/actividades/{actividad}/aprobar', [ActividadController::class, 'aprobar'])->name('actividades.aprobar');
Route::post('/actividades/{actividad}/rechazar', [ActividadController::class, 'rechazar'])->name('actividades.rechazar');
Route::post('/actividades/{actividad}/cancelar', [ActividadController::class, 'cancelar'])->name('actividades.cancelar');
Route::post('/actividades/{actividad}/migrar', [ActividadController::class, 'migrar'])->name('actividades.migrar');
Route::post('/actividades/{actividad}/comentarios', [ActividadController::class, 'comentar'])->name('actividades.comentar');
Route::get('/actividades/{actividad}/resumen', [ActividadController::class, 'resumen'])->name('actividades.resumen');
Route::get('/actividades/{actividad}/editar', [ActividadController::class, 'edit'])->name('actividades.edit');
Route::put('/actividades/{actividad}', [ActividadController::class, 'update'])->name('actividades.update');
Route::post('/actividades/{actividad}/realizar', [ActividadController::class, 'realizar'])->name('actividades.realizar');
Route::post('/actividades/{actividad}/solicitud-pdf', [ActividadController::class, 'descargarSolicitudPdf'])->name('actividades.solicitudPdf');

// El wildcard {actividad} va AL FINAL: si se registra antes que "crear", Laravel
// intentaría interpretar "crear" como un id de actividad.
Route::get('/actividades/{actividad}', [ActividadController::class, 'show'])->name('actividades.show');
