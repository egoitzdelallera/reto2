<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Campus;
use App\Models\Taller;
use App\Models\TipoAveria;
use App\Models\TipoMantenimiento;

class GestionTodoController extends Controller
{
    // Campus
    public function createCampus(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'ubicacion' => 'required|string|max:255',
        ]);

        $campus = Campus::create($request->all());
        return response()->json($campus, 201);
    }

    public function updateCampus(Request $request, $id)
    {
        try {
            $campus = Campus::findOrFail($id);
            $campus->update($request->all());
            return response()->json($campus);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al actualizar el campus: ' . $e->getMessage()], 500);
        }
    }

    // Talleres
    public function createTaller(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'id_campus' => 'required|integer', // Aseguramos que id_campus sea un entero y requerido
        ]);

        // Creamos un nuevo array con los datos del request y asignamos id_responsable = 1 por defecto
        $tallerData = $request->all();
        $tallerData['id_responsable'] = 1;

        $taller = Taller::create($tallerData);
        return response()->json($taller, 201);
    }

    public function updateTaller(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'id_campus' => 'required|integer',
        ]);

        $taller = Taller::findOrFail($id);
        $taller->update($request->all());
        return response()->json($taller, 200);
    }

    // Tipo de Avería
    public function createTipoAveria(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $tipoAveria = TipoAveria::create($request->all());
        return response()->json($tipoAveria, 201);
    }

    public function updateTipoAveria(Request $request, $id)
    {
        $tipoAveria = TipoAveria::findOrFail($id);
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $tipoAveria->update($request->all());
        return response()->json($tipoAveria, 200);
    }

    // Tipo de Mantenimiento
    public function createTipoMantenimiento(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string', // Permitimos la descripcion opcional
        ]);

        $tipoMantenimiento = TipoMantenimiento::create($request->all());
        return response()->json($tipoMantenimiento, 201);
    }

    public function updateTipoMantenimiento(Request $request, $id)
    {
        $tipoMantenimiento = TipoMantenimiento::findOrFail($id);
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        $tipoMantenimiento->update($request->all());
        return response()->json($tipoMantenimiento, 200);
    }
}
