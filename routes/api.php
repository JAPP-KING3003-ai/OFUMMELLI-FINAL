<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DolarPromedioController;

// Ruta para el dólar promedio
Route::get('/dolar-promedio', [DolarPromedioController::class, 'obtenerDolarPromedio']);