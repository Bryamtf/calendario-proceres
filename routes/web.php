<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ActividadController;
use App\Http\Controllers\CalendarioController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TrimestreController;
use App\Http\Controllers\OrganizacionController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\PresupuestoController;
use Illuminate\Support\Facades\Route;


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
    Route::post('/actividades/{actividad}/cancelar', [ActividadController::class, 'cancelar'])->name('actividades.cancelar');
    Route::post('/actividades/{actividad}/migrar', [ActividadController::class, 'migrar'])->name('actividades.migrar');
    Route::post('/actividades/{actividad}/comentarios', [ActividadController::class, 'comentar'])->name('actividades.comentar');
    Route::get('/actividades/{actividad}/resumen', [ActividadController::class, 'resumen'])->name('actividades.resumen');
    Route::get('/actividades/{actividad}/editar', [ActividadController::class, 'edit'])->name('actividades.edit');
    Route::put('/actividades/{actividad}', [ActividadController::class, 'update'])->name('actividades.update');

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


    Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');
    Route::get('/reportes/excel', [ReporteController::class, 'excel'])->name('reportes.excel');
    Route::get('/reportes/pdf', [ReporteController::class, 'pdf'])->name('reportes.pdf');

    Route::get('/configuracion', [ConfiguracionController::class, 'edit'])->name('configuracion.edit');
    Route::put('/configuracion', [ConfiguracionController::class, 'update'])->name('configuracion.update');
    Route::post('/configuracion/catalogos/{tipo}', [ConfiguracionController::class, 'storeCatalogo'])->name('configuracion.catalogos.store');
    Route::patch('/configuracion/catalogos/{tipo}/{id}/toggle', [ConfiguracionController::class, 'toggleCatalogoItem'])->name('configuracion.catalogos.toggle');

    Route::get('/presupuesto', [PresupuestoController::class, 'index'])->name('presupuesto.index');
    Route::put('/presupuesto/{organizacion}', [PresupuestoController::class, 'update'])->name('presupuesto.update');

});

require __DIR__.'/auth.php';
