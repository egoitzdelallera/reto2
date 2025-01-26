<?php

namespace App\Http\Controllers;

use App\Models\FasesIncidencia;
use App\Models\Incidencia;
use Illuminate\Http\Request;
use App\Models\TecnicosFasesIncidencia;
use Illuminate\Support\Facades\Log;

class FasesIncidenciaController extends Controller
{
    public function finalizarFase(Request $request, $faseId)
    {

        error_log('Datos recibidos:' . json_encode($request->all()));

        $request->validate([
            'descripcion' => 'required|string',
            'userId' => 'required|integer',
        ]);

        $fase = FasesIncidencia::findOrFail($faseId);

        error_log('Estado de la fase antes de completar: ' . $fase->estado);

        if($fase->estado === 'Completada') {
            return response()->json(['error' => 'La fase ya esta completada'], 400);
        }

        $asignacion = TecnicosFasesIncidencia::where('id_fase_incidencia', $faseId)
            ->where('id_tecnico', $request->input('userId'))
            ->exists();

        if (!$asignacion) {
            return response()->json(['error' => 'No estás asignado a esta fase'], 403);
        }

        $fase->estado = 'Completada';
        $fase->descripcion = $request->input('descripcion');
        $fase->fecha_fin = now();
        $fase->save();

        return response()->json([
            'message' => 'Fase completada exitosamente',
            'fase' => $fase,
        ]);
    }

    public function crearFase(Request $request)
    {

        $request->validate([
            'id_incidencia' => 'required|exists:incidencias,id_incidencia',
        ]);

        $incidencia = Incidencia::findOrFail($request->input('id_incidencia'));

        // Verificar si hay una fase incompleta
        $faseIncompleta = $incidencia->fasesIncidencias()->where('estado', '!=', 'Completada')->exists();
        if ($faseIncompleta) {
            return response()->json(['error' => 'No se puede crear una nueva fase porque hay una sin completar'], 400);
        }

        $nuevaFase = FasesIncidencia::create([
            'id_incidencia' => $incidencia->id_incidencia,
            'estado' => 'Pendiente',
            'descripcion' => null,
            'fecha_inicio' => now(),
            'fecha_fin' => null,
        ]);

        return response()->json([
            'message' => 'Nueva fase creada exitosamente',
            'fase' => $nuevaFase,
        ]);
    }

    public function asignarTecnicoAFase(Request $request, $faseId)
    {
        // Validar los datos recibidos
        $request->validate([
            'id_tecnico' => 'required|exists:users,id_usuario', 
        ]);

        
        $idTecnico = $request->input('id_tecnico');
        Log::info("Asignando técnico a fase", ['faseId' => $faseId, 'idTecnico' => $idTecnico]);


        // Validar si la relación ya existe
        $existe = TecnicosFasesIncidencia::where('id_fase_incidencia', $faseId)
            ->where('id_tecnico', $idTecnico)
            ->exists();

        if ($existe) {
            Log::info("El técnico ya está asignado a la fase", ['faseId' => $faseId, 'idTecnico' => $idTecnico]);
            return response()->json(['message' => 'El técnico ya está asignado a esta fase'], 400);
        }

        try {
            $relacion = TecnicosFasesIncidencia::create([
                'id_fase_incidencia' => $faseId,
                'id_tecnico' => $idTecnico,
            ]);
    
            Log::info("Técnico asignado con éxito", ['relacion' => $relacion]);
    
            return response()->json([
                'message' => 'Técnico asignado con éxito a la fase',
                'relacion' => $relacion,
            ], 201);
        } catch (\Exception $e) {
            Log::error("Error al asignar técnico a la fase", ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Error al asignar el técnico'], 500);
        }
    }
}
