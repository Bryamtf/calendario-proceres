<?php

use App\Http\Controllers\NotificacionController;
use Illuminate\Support\Facades\Route;

Route::get('/notificaciones', [NotificacionController::class, 'index'])->name('notificaciones.index');
Route::post('/notificaciones/{id}/leer', [NotificacionController::class, 'leer'])->name('notificaciones.leer');
Route::post('/notificaciones/marcar-todas', [NotificacionController::class, 'marcarTodas'])->name('notificaciones.marcarTodas');
