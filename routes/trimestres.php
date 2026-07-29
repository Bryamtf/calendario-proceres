<?php

use App\Http\Controllers\TrimestreController;
use Illuminate\Support\Facades\Route;

Route::get('/trimestres', [TrimestreController::class, 'index'])->name('trimestres.index');
Route::get('/trimestres/crear', [TrimestreController::class, 'create'])->name('trimestres.create');
Route::post('/trimestres', [TrimestreController::class, 'store'])->name('trimestres.store');
Route::post('/trimestres/{trimestre}/cerrar', [TrimestreController::class, 'cerrar'])->name('trimestres.cerrar');
