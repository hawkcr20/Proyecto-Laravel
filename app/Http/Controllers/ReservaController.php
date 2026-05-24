<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use Illuminate\Http\Request;

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
                $reserva->idReserva,

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

            'idUsuario' =>
            'required|integer|exists:usuarios,id',

            'idClase' =>
            'required|integer|exists:clases,id',

            'estado' =>
            'required|string|max:50',
        ]);

        $validated['fechaReserva'] = now();

        $reserva = Reserva::create($validated);

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


    public function cancelar($id)
    {

        $reserva = Reserva::find($id);

        if (!$reserva) {

            return response()->json([
                'success' => false,
                'message' => 'Reserva no encontrada'
            ], 404);
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
