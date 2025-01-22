<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Incidencia;

class IncidenciaController extends Controller
{
    public function index()
    {
        $incidencias = Incidencia::with(['maquina', 'maquina.taller', 'creador', 'tecnico'])->get();
        return response()->json($incidencias);
    }

    public function index2(Request $request)
    {
          $idTecnico = $request->query('id_tecnico');
        
          $incidencias = Incidencia::select('incidencias.*', 'maquinas.nombre as maquina_nombre')
                ->join('maquinas', 'incidencias.id_maquina', '=', 'maquinas.id_maquina')
                ->join('fases_incidencias', 'incidencias.id_incidencia', '=', 'fases_incidencias.id_incidencia')
                ->join('tecnicos_fases_incidencias', 'fases_incidencias.id_fase_incidencia', '=', 'tecnicos_fases_incidencias.id_fase_incidencia')
                 ->where('tecnicos_fases_incidencias.id_tecnico', $idTecnico)
                  ->distinct()
                   ->with(['maquina:id_maquina,nombre', 'maquina.taller:id_taller,nombre',])
                 ->get();
           $incidencias->each(function ($incidencia) {
                $incidencia->maquina_nombre = $incidencia->maquina->nombre;
           });

         return response()->json($incidencias);
    }

    public function show()
    {
        
      
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_maquina' => 'required|integer',
            'descripcion' => 'required|string',
            'gravedad' => 'required|in:Maquina parada, Maquina en Marcha, Mantenimiento, Aviso',
            'id_tipo_mantenimiento' => 'nullable|integer',
            'id_tipo_averia' => 'nullable|integer',
            'estado' => 'required|in:Abierta, En progreso, Resuelta, Cancelada',
            'frecuencia' => 'nullable|in:Diaria, Semanal, Mensual, Anual',
            'multimedia' => 'nullable|string',
            'fecha_cierre' => 'nullable|date',
        ]);

        // Si el estado no se proporciona, se asigna 'Abierta' por defecto
        $estado = $request->estado ?: 'Abierta';

        // Obtener la fecha y hora actual
        $fecha_reporte = now();  // La función `now()` obtiene la fecha y hora actual

        // Crear la incidencia
        $incidencia = Incidencia::create([
            'id_maquina' => $request->id_maquina,
            'descripcion' => $request->descripcion,
            'gravedad' => $request->gravedad,
            'id_tipo_mantenimiento' => $request->id_tipo_mantenimiento, // Puede ser null si no se especifica
            'id_tipo_averia' => $request->id_tipo_averia, // Puede ser null si no se especifica
            'estado' => $estado,
            'frecuencia' => $request->frecuencia,
            'multimedia' => $request->multimedia,
            'fecha_reporte' => $fecha_reporte,
            'fecha_cierre' => $request->fecha_cierre, // Se puede dejar null si no está cerrada
        ]);

        // Retornar la respuesta con la incidencia creada
        return response()->json($incidencia, 201);
    }

    public function update(Request $request, $id)
    {
        $incidencia = Incidencia::find($id);

        if (!$incidencia) {
            return response()->json(['message' => 'Incidencia no encontrada'], 404);
        }

        $request->validate([
            'id_maquina' => 'required|integer',
            'descripcion' => 'required|string',
            'gravedad' => 'required|in:Maquina parada, Maquina en Marcha, Mantenimiento, Aviso',
            'id_tipo_mantenimiento' => 'nullable|integer',
            'id_tipo_averia' => 'nullable|integer',
            'estado' => 'required|in:Abierta, En progreso, Resuelta, Cancelada',
            'frecuencia' => 'nullable|in:Diaria, Semanal, Mensual, Anual',
            'multimedia' => 'nullable|string',
            'fecha_reporte' => 'required|date',
            'fecha_cierre' => 'nullable|date',
        ]);

        $incidencia->update([
            'id_maquina' => $request->id_maquina,
            'descripcion' => $request->descripcion,
            'gravedad' => $request->gravedad,
            'id_tipo_mantenimiento' => $request->id_tipo_mantenimiento,
            'id_tipo_averia' => $request->id_tipo_averia,
            'estado' => $request->estado,
            'frecuencia' => $request->frecuencia,
            'multimedia' => $request->multimedia,
            'fecha_reporte' => $request->fecha_reporte,
            'fecha_cierre' => $request->fecha_cierre,
        ]);
        return response()->json($incidencia);
    }

    public function destroy($id)
    {
        $incidencia = Incidencia::find($id);

        if (!$incidencia) {
            return response()->json(['message' => 'Incidencia no encontrada'], 404);
        }

        $incidencia->delete();

        return response()->json(['message' => 'Incidencia eliminada correctamente']);
    }
}