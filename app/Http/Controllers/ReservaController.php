<?php

namespace App\Http\Controllers;

use App\Models\Clase;
use App\Models\Reserva;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ReservaController extends Controller
{

    public function index()
    {
        $reservas = Reserva::with(['usuario', 'clase'])->get();

        return response()->json([
            'success' => true,
            'data' => $reservas
        ]);
    }


    public function misClases(Request $request)
    {

        $usuario = $request->user();

        if (!$usuario) {

            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado'
            ], 401);
        }

        $reservas = Reserva::with('clase')
            ->where('idUsuario', $usuario->id)
            ->get();

        $resultado = $reservas->map(function ($reserva) {

            return [

                'idReserva' =>
                $reserva->id,

                'nombreClase' =>
                $reserva->clase->nombre ?? '',

                'capacidad' =>
                $reserva->clase->capacidad ?? '',

                'fechaReserva' =>
                $reserva->fechaReserva ?? '',

                'horario' =>
                $reserva->clase->horario ?? '',

                'estado' =>
                $reserva->estado ?? ''
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $resultado
        ]);
    }


    public function store(Request $request)
    {

        $validated = $request->validate([

            'idClase' =>
            'required|integer|exists:clases,id',

            'estado' =>
            'sometimes|string|max:50',
        ]);

        $validated['idUsuario'] = $request->user()->id;
        $validated['fechaReserva'] = now();
        $validated['estado'] = 'ACTIVA';

        $reserva = DB::transaction(function () use ($validated) {
            $clase = Clase::whereKey($validated['idClase'])
                ->lockForUpdate()
                ->firstOrFail();

            if ($clase->capacidad <= 0) {
                throw ValidationException::withMessages([
                    'idClase' => 'La clase no tiene cupos disponibles',
                ]);
            }

            $reserva = Reserva::create($validated);

            $clase->decrement('capacidad');

            return $reserva;
        });

        return response()->json([
            'success' => true,
            'message' => 'Reserva creada correctamente',
            'data' => $reserva
        ], 201);
    }


    public function show(string $id)
    {

        $reserva = Reserva::with(['usuario', 'clase'])
            ->find($id);

        if (!$reserva) {

            return response()->json([
                'success' => false,
                'message' => 'Reserva no encontrada'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $reserva
        ]);
    }


    public function update(Request $request, string $id)
    {

        $reserva = Reserva::find($id);

        if (!$reserva) {

            return response()->json([
                'success' => false,
                'message' => 'Reserva no encontrada'
            ], 404);
        }

        $validated = $request->validate([
            'estado' => 'required|string|max:50',
        ]);

        $reserva->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Reserva actualizada correctamente',
            'data' => $reserva
        ]);
    }


    public function destroy(string $id)
    {

        $reserva = Reserva::find($id);

        if (!$reserva) {

            return response()->json([
                'success' => false,
                'message' => 'Reserva no encontrada'
            ], 404);
        }

        $reserva->delete();

        return response()->json([
            'success' => true,
            'message' => 'Reserva eliminada correctamente'
        ]);
    }

    public function filtrarEstado($estado)
    {

        $reservas = Reserva::with(['usuario', 'clase'])
            ->where('estado', $estado)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $reservas
        ]);
    }


    public function cancelar(Request $request, $id)
    {

        $reserva = Reserva::find($id);

        if (!$reserva) {

            return response()->json([
                'success' => false,
                'message' => 'Reserva no encontrada'
            ], 404);
        }

        $usuario = $request->user();
        $esAdmin = $usuario && $usuario->rol && $usuario->rol->nombre === 'ROLE_ADMIN';

        if (!$esAdmin && $reserva->idUsuario !== $usuario->id) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permisos para cancelar esta reserva'
            ], 403);
        }

        $reserva->estado = 'CANCELADA';

        $reserva->save();

        return response()->json([
            'success' => true,
            'message' => 'Reserva cancelada correctamente',
            'data' => $reserva
        ]);
    }
}
