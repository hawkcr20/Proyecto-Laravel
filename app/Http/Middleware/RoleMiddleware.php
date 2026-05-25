<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role)
    {
        $user = $request->user();

        if (!$user || !$user->rol || $user->rol->nombre !== $role) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permisos para realizar esta accion',
            ], 403);
        }

        return $next($request);
    }
}
