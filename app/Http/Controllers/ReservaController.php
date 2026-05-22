<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use Illuminate\Http\Request;

class ReservaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reservas = Reserva::with(['usuario', 'clase'])->get();

        return response()->json([

            'success' => true,
            'data' => $reservas
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'user_id' => 'required|exists:users,id',
            'clase_id' => 'required|exists:clases,id',
            'fechaReserva' => 'required|date',
            'estado' => 'required|string|max:50',
        ]);

        $reserva = Reserva::create($validated);

        return response()->json([

            'success' => true,
            'message' => 'Reserva creada correctamente',
            'data' => $reserva
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $reserva = Reserva::with(['user', 'clase'])->find($id);

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

    /**
     * Update the specified resource in storage.
     */
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

            'user_id' => 'required|exists:users,id',
            'clase_id' => 'required|exists:clases,id',
            'fechaReserva' => 'required|date',
            'estado' => 'required|string|max:50',
        ]);

        $reserva->update($validated);

        return response()->json([

            'success' => true,
            'message' => 'Reserva actualizada correctamente',
            'data' => $reserva
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
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
}
