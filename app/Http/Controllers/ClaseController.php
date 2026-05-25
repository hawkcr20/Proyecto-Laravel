<?php

namespace App\Http\Controllers;

use App\Models\Clase;
use Illuminate\Http\Request;

class ClaseController extends Controller
{
    
    public function index(Request $request)
    {
        $query = Clase::query();

        if ($request->filled('diaSemana')) {
            $query->where('diaSemana', $request->diaSemana);
        }

        return response()->json([
            'success' => true,
            'data' => $query->get()
        ]);
    }

    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'diaSemana' => 'required|string|max:50',
            'horario' => 'required',
            'capacidad' => 'required|integer|min:1',
        ]);

        $clase = Clase::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Clase creada correctamente',
            'data' => $clase
        ], 201);
    }

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

    public function destroy(string $id)
{
    try {

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

    } catch (\Exception $e) {

        return response()->json([
            'success' => false,
            'message' => 'No se puede eliminar la clase porque tiene reservas asociadas',
            'error' => $e->getMessage()
        ], 500);
    }
}
}
