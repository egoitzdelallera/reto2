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
             $request->validate([
                 'nombre' => 'nullable|string|max:255',
                 'ubicacion' => 'nullable|string|max:255',
                 'disabled' => 'nullable|boolean',
             ]);
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
            'nombre' => 'nullable|string|max:255',
            'id_campus' => 'nullable|integer',
            'disabled' => 'nullable|boolean',
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
    public function updateTipoMantenimiento(Request $request, $id)
    {
         $request->validate([
             'nombre' => 'nullable|string|max:255',
             'descripcion' => 'nullable|string',
             'disabled' => 'nullable|boolean',
         ]);
        try {
            $tipoMantenimiento = TipoMantenimiento::findOrFail($id);
              $tipoMantenimiento->update($request->all());
                return response()->json($tipoMantenimiento, 200);
          } catch (\Exception $e) {
           return response()->json(['message' => 'Error al actualizar el tipo de mantenimiento: ' . $e->getMessage()], 500);
        }
    }
    public function updateTipoAveria(Request $request, $id)
    {
         $request->validate([
            'nombre' => 'nullable|string|max:255',
           'disabled' => 'nullable|boolean',
        ]);
        $tipoAveria = TipoAveria::findOrFail($id);


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
    public function toggleCampusStatus(Request $request, $id)
    {
        try {
            $campus = Campus::findOrFail($id);
    
            // Toggle the status
            $newEstado = $campus->estado === 'Habilitado' ? 'Deshabilitado' : 'Habilitado';
    
            $campus->update(['estado' => $newEstado]);
    
            \Log::info('Campus updated:', ['id' => $id, 'estado' => $newEstado]);
    
            return response()->json($campus);
    
        } catch (\Exception $e) {
            \Log::error('Error in toggleCampusStatus:', ['id' => $id, 'error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['message' => 'Error al actualizar el estado del campus: ' . $e->getMessage()], 500);
        }
    }
   
    //tallere
    public function toggleTallerStatus(Request $request, $id)
    {
        try {
            $taller = Taller::findOrFail($id);

            // Toggle the status
            $newEstado = $taller->estado === 'Habilitado' ? 'Deshabilitado' : 'Habilitado';

            $taller->update(['estado' => $newEstado]);

            \Log::info('Taller updated:', ['id' => $id, 'estado' => $newEstado]);
            return response()->json($taller);
        } catch (\Exception $e) {
            \Log::error('Error in toggleTallerStatus:', ['id' => $id, 'error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['message' => 'Error al actualizar el estado del taller: ' . $e->getMessage()], 500);
        }
    }


    public function toggleTipoMantenimientoStatus(Request $request, $id)
    {
        try {
            $tipoMantenimiento = TipoMantenimiento::findOrFail($id);

            // Toggle the status
            $newEstado = $tipoMantenimiento->estado === 'Habilitado' ? 'Deshabilitado' : 'Habilitado';

            $tipoMantenimiento->update(['estado' => $newEstado]);

            \Log::info('TipoMantenimiento updated:', ['id' => $id, 'estado' => $newEstado]);

            return response()->json($tipoMantenimiento);

        } catch (\Exception $e) {
            \Log::error('Error in toggleTipoMantenimientoStatus:', ['id' => $id, 'error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['message' => 'Error al actualizar el estado del tipo de mantenimiento: ' . $e->getMessage()], 500);
        }
    }
    
    
      // Tipo de Avería
      public function toggleTipoAveriaStatus(Request $request, $id)
    {
        try {
            $tipoAveria = TipoAveria::findOrFail($id);

            // Toggle the status
            $newEstado = $tipoAveria->estado === 'Habilitado' ? 'Deshabilitado' : 'Habilitado';

            $tipoAveria->update(['estado' => $newEstado]);
            \Log::info('TipoAveria updated:', ['id' => $id, 'estado' => $newEstado]);

            return response()->json($tipoAveria);
        } catch (\Exception $e) {
            \Log::error('Error in toggleTipoAveriaStatus:', ['id' => $id, 'error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['message' => 'Error al actualizar el estado del tipo de avería: ' . $e->getMessage()], 500);
        }
    }

}