<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ClaseController;
use App\Http\Controllers\ReservaController;
use App\Http\Middleware\SetLocaleFromHeader;

Route::middleware([SetLocaleFromHeader::class])->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/usuarios', [UsuarioController::class, 'store']);
Route::get('/usuarios/buscar/{username}', [UsuarioController::class, 'buscar']);

Route::get('/clases', [ClaseController::class, 'index']);
Route::get('/clases/{clase}', [ClaseController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/perfil', [AuthController::class, 'me']);

    Route::get('/reservas/mis-clases', [ReservaController::class, 'misClases']);
    Route::post('/reservas', [ReservaController::class, 'store']);
    Route::put('/reservas/cancelar/{id}', [ReservaController::class, 'cancelar']);
});

Route::middleware(['auth:sanctum', 'role:ROLE_ADMIN'])->group(function () {

    Route::get('/usuarios', [UsuarioController::class, 'index']);
    Route::get('/usuarios/{usuario}', [UsuarioController::class, 'show']);
    Route::put('/usuarios/{usuario}', [UsuarioController::class, 'update']);
    Route::delete('/usuarios/{usuario}', [UsuarioController::class, 'destroy']);

    Route::post('/clases', [ClaseController::class, 'store']);
    Route::put('/clases/{clase}', [ClaseController::class, 'update']);
    Route::delete('/clases/{clase}', [ClaseController::class, 'destroy']);

    Route::get('/reservas', [ReservaController::class, 'index']);
    Route::get('/reservas/estado/{estado}', [ReservaController::class, 'filtrarEstado']);
    Route::get('/reservas/{reserva}', [ReservaController::class, 'show']);
    Route::put('/reservas/{reserva}', [ReservaController::class, 'update']);
    Route::delete('/reservas/{reserva}', [ReservaController::class, 'destroy']);
});
