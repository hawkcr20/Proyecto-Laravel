<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        $request->validate([
            'userName' => 'required',
            'password' => 'required',
        ]);

        $user = Usuario::with('rol')
            ->where('userName', $request->userName)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Credenciales inválidas'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'usuario' => $user
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()
            ->currentAccessToken()
            ->delete();

        return response()->json([
            'message' => 'Sesión cerrada correctamente'
        ]);
    }

    
    public function me(Request $request)
    {
        return response()->json(
            $request->user()->load('rol')
        );
    }
}