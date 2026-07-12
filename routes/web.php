<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActividadController;
use App\Http\Controllers\CalendarioController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TrimestreController;
use App\Http\Controllers\OrganizacionController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/calendario', [CalendarioController::class, 'index'])->name('calendario.index');
    Route::get('/calendario/eventos', [CalendarioController::class, 'eventos'])->name('calendario.eventos');

    Route::get('/actividades/crear', [ActividadController::class, 'create'])->name('actividades.create');
    Route::get('/actividades', [ActividadController::class, 'index'])->name('actividades.index');
    Route::get('/actividades/crear', [ActividadController::class, 'create'])->name('actividades.create');
    Route::post('/actividades', [ActividadController::class, 'store'])->name('actividades.store');
    Route::get('/actividades/{actividad}', [ActividadController::class, 'show'])->name('actividades.show');
    Route::post('/actividades/{actividad}/aprobar', [ActividadController::class, 'aprobar'])->name('actividades.aprobar');
    Route::post('/actividades/{actividad}/rechazar', [ActividadController::class, 'rechazar'])->name('actividades.rechazar');
    Route::post('/actividades/{actividad}/migrar', [ActividadController::class, 'migrar'])->name('actividades.migrar');
    Route::post('/actividades/{actividad}/comentarios', [ActividadController::class, 'comentar'])->name('actividades.comentar');

    Route::get('/usuarios', [UserController::class, 'index'])->name('usuarios.index');
    Route::get('/usuarios/crear', [UserController::class, 'create'])->name('usuarios.create');
    Route::post('/usuarios', [UserController::class, 'store'])->name('usuarios.store');
    Route::get('/usuarios/{usuario}/editar', [UserController::class, 'edit'])->name('usuarios.edit');
    Route::put('/usuarios/{usuario}', [UserController::class, 'update'])->name('usuarios.update');
    Route::patch('/usuarios/{usuario}/toggle-activo', [UserController::class, 'toggleActivo'])->name('usuarios.toggleActivo');

    Route::get('/trimestres', [TrimestreController::class, 'index'])->name('trimestres.index');
    Route::get('/trimestres/crear', [TrimestreController::class, 'create'])->name('trimestres.create');
    Route::post('/trimestres', [TrimestreController::class, 'store'])->name('trimestres.store');
    Route::post('/trimestres/{trimestre}/cerrar', [TrimestreController::class, 'cerrar'])->name('trimestres.cerrar');

    Route::get('/organizaciones', [OrganizacionController::class, 'index'])->name('organizaciones.index');
    Route::get('/organizaciones/crear', [OrganizacionController::class, 'create'])->name('organizaciones.create');
    Route::post('/organizaciones', [OrganizacionController::class, 'store'])->name('organizaciones.store');
    Route::get('/organizaciones/{organizacion}/editar', [OrganizacionController::class, 'edit'])->name('organizaciones.edit');
    Route::put('/organizaciones/{organizacion}', [OrganizacionController::class, 'update'])->name('organizaciones.update');
    Route::patch('/organizaciones/{organizacion}/toggle-activo', [OrganizacionController::class, 'toggleActivo'])->name('organizaciones.toggleActivo');
});

require __DIR__.'/auth.php';
