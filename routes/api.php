<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ClaseController;
use App\Http\Controllers\ReservaController;

Route::apiResource('api/clases', ClaseController::class);
Route::apiResource('api/reservas', ReservaController::class);
