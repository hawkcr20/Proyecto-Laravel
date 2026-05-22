<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;

use App\Models\Usuario;

use App\Http\Controllers\ClaseController;
use App\Http\Controllers\ReservaController;



Route::post('/login', function (Request $request) {

    $request->validate([
        'userName' => 'required',
        'password' => 'required',
    ]);

    $user = Usuario::where('userName', $request->userName)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {

        return response()->json([
            'message' => 'Credenciales inválidas'
        ], 401);
    }

    $token = $user->createToken('token')->plainTextToken;

    return response()->json([
        'token' => $token,
        'usuario' => $user
    ]);
});



Route::middleware('auth:sanctum')->group(function () {


    Route::get('/perfil', function (Request $request) {

        return $request->user();
    });

    

    Route::post('/logout', function (Request $request) {

        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Sesión cerrada correctamente'
        ]);
    });



    Route::apiResource('clases', ClaseController::class);

    Route::apiResource('reservas', ReservaController::class);
});