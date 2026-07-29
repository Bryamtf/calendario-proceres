<?php

use App\Http\Controllers\PublicoController;
use Illuminate\Support\Facades\Route;

Route::get('/publico/{token}', [PublicoController::class, 'index'])
    ->middleware('throttle:60,1')
    ->name('publico.index');
