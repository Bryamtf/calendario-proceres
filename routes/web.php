<?php

use App\Http\Controllers\CalendarioController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/calendario', [CalendarioController::class, 'index'])->name('calendario.index');
    Route::get('/calendario/eventos', [CalendarioController::class, 'eventos'])->name('calendario.eventos');
});

Route::middleware('auth')->group(base_path('routes/actividades.php'));
Route::middleware('auth')->group(base_path('routes/usuarios.php'));
Route::middleware('auth')->group(base_path('routes/trimestres.php'));
Route::middleware('auth')->group(base_path('routes/organizaciones.php'));
Route::middleware('auth')->group(base_path('routes/reportes.php'));
Route::middleware('auth')->group(base_path('routes/configuracion.php'));
Route::middleware('auth')->group(base_path('routes/presupuesto.php'));
Route::middleware('auth')->group(base_path('routes/notificaciones.php'));
Route::middleware('auth')->group(base_path('routes/fechas-especiales.php'));

require base_path('routes/publico.php');

require __DIR__ . '/auth.php';
