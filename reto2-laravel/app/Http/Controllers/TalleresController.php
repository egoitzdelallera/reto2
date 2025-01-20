<?php

namespace App\Http\Controllers;

use App\Models\Taller;
use Illuminate\Http\Request;

class TalleresController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $talleres = Taller::all();
        return response()->json($talleres);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
            'email' => 'required|email|max:255',
        ]);

        $taller = Taller::create($request->all());
        return response()->json($taller, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $taller = Taller::find($id);

        if (!$taller) {
            return response()->json(['message' => 'Taller no encontrado'], 404);
        }

        return response()->json($taller);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $taller = Taller::find($id);

        if (!$taller) {
            return response()->json(['message' => 'Taller no encontrado'], 404);
        }

        $request->validate([
            'nombre' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
            'email' => 'required|email|max:255',
        ]);

        $taller->update($request->all());
        return response()->json($taller);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $taller = Taller::find($id);

        if (!$taller) {
            return response()->json(['message' => 'Taller no encontrado'], 404);
        }

        $taller->delete();
        return response()->json(['message' => 'Taller eliminado']);
    }
}