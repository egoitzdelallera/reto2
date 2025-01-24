<?php

namespace App\Http\Controllers;

use App\Models\TipoMantenimiento;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TipoMantenimientoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $tiposMantenimiento = TipoMantenimiento::all();
        return response()->json($tiposMantenimiento);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);
        $tipoMantenimiento = TipoMantenimiento::create($request->all());
        return response()->json($tipoMantenimiento, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): JsonResponse
    {
        $tipoMantenimiento = TipoMantenimiento::findOrFail($id);
        return response()->json($tipoMantenimiento);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);
        $tipoMantenimiento = TipoMantenimiento::findOrFail($id);
        $tipoMantenimiento->update($request->all());
        return response()->json($tipoMantenimiento, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $tipoMantenimiento = TipoMantenimiento::findOrFail($id);
        $tipoMantenimiento->delete();
        return response()->json(null, 204);
    }
}