<?php

use App\Http\Controllers\ReporteController;
use Illuminate\Support\Facades\Route;

Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');
Route::get('/reportes/excel', [ReporteController::class, 'excel'])->name('reportes.excel');
Route::get('/reportes/pdf', [ReporteController::class, 'pdf'])->name('reportes.pdf');
