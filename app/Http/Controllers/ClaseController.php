<?php

namespace App\Http\Controllers;

use App\Models\Clase;
use Illuminate\Http\Request;

class ClaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clases = Clase::all();

        return response()->json([
            'success' => true,
            'data' => $clases
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([

            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'diaSemana' => 'required|string|max:50',
            'horario' => 'required',
            'capacidad' => 'required|integer|min:1',
        ]);

        $clase = Clase::create($validate);

        return response()->json([

            'success' => true,
            'message' => 'Clase creada correctamente',
            'data' => $clase
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $clase = Clase::find($id);

        if (!$clase) {

            return response()->json([

                'success' => false,
                'message' => 'Clase no encontrada'
            ], 404);
        }
        return response()->json([
            
            'success' => true,
            'data' => $clase
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $clase = Clase::find($id);

        if (!$clase) {

            return response()->json([
                'success' => false,
                'message' => 'Clase no encontrada'
            ], 404);
        }

        $validated = $request->validate([

            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'diaSemana' => 'required|string|max:50',
            'horario' => 'required',
            'capacidad' => 'required|integer|min:1',
        ]);

        $clase->update($validated);

        return response()->json([

            'success' => true,
            'message' => 'Clase actualizada correctamente',
            'data' => $clase
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $clase = Clase::find($id);

        if (!$clase) {

            return response()->json([

                'success' => false,
                'message' => 'Clase no encontrada'
            ], 404);
        }

        $clase->delete();

        return response()->json([
            
            'success' => true,
            'message' => 'Clase eliminada correctamente'
        ]);
    }
}
