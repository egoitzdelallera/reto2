<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Incidencia;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class IncidenciaController extends Controller
{
    public function index()
    {
        $incidencias = Incidencia::with(['maquina', 'maquina.taller', 'creador:id_usuario,nombre,apellido,correo,rol,estado,imagen_perfil,id_campus', 'creador.campus:id_campus,nombre', 'fasesIncidencias', 'fasesIncidencias.tecnicosFasesIncidencias', 'fasesIncidencias.tecnicosFasesIncidencias.tecnico'])
        ->get();
        
        $incidencias->each(function ($incidencia) {
            if ($incidencia->creador) {
              $incidencia->creador->campus_nombre = $incidencia->creador->campus ? $incidencia->creador->campus->nombre : null;
            } else {
              $incidencia->creador_campus = null;
            }
        });
        return response()->json($incidencias);
    }


    public function show($id_incidencia)
    {
        $incidencia = Incidencia::with(['maquina', 'maquina.taller', 'maquina.taller.campus', 'creador', 'fasesIncidencias', 'fasesIncidencias.tecnicosFasesIncidencias', 'fasesIncidencias.tecnicosFasesIncidencias.tecnico', 'tipoAveria', 'tipoMantenimiento'])->find($id_incidencia);
        if (!$incidencia) {
            return response()->json(['message' => 'Incidencia no encontrada'], 404);
        }

        if($incidencia->multimedia){
            $incidencia->multimedia = Storage::url($incidencia->multimedia);
        }

        return response()->json($incidencia);
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



    public function store(Request $request)
    {
        $request->validate([
            'id_maquina' => 'required|integer',
            'descripcion' => 'required|string',
            'gravedad' => 'in:Maquina parada,Maquina en Marcha,Aviso,Mantenimiento',
            'id_tipo_averia' => 'required|integer',
            'multimedia' => 'nullable|file',
            'id_creador' => 'required|integer',
        ]);
    
        try {
            $multimediaPath = null;
            if($request->hasFile('multimedia')) {
                $multimediaPath = $request->file('multimedia')->store('multimedia', 'public');
            }

            // Log para ver la ruta guardada
            Log::info('Archivo guardado en:', [
                'path' => $multimediaPath
            ]);

            // Crear la incidencia
            $incidencia = Incidencia::create([
                'id_maquina' => $request->id_maquina,
                'descripcion' => $request->descripcion,
                'gravedad' => $request->gravedad,
                'estado' => "Abierta",
                'id_creador' => $request->id_creador,
                'id_tipo_averia' => $request->id_tipo_averia,
                'multimedia' => $multimediaPath, 
         ]);
         // Retornar la respuesta con la incidencia creada
        return response()->json($incidencia, 201);
        } catch (\Exception $e) {
            // Retornar un mensaje de error
            return response()->json(['message' => 'Error al crear la incidencia'], 500);
        }
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
            'gravedad' => 'in:Maquina parada,Maquina en Marcha,Aviso,Mantenimiento',
            'id_tipo_mantenimiento' => 'nullable|integer',
            'id_tipo_averia' => 'nullable|integer',
            'estado' => 'required|in:Abierta, Pendiente, En Proceso, Resuelta',
            'frecuencia' => 'nullable|in:diario, semanal, mensual, anual',
            'multimedia' => 'nullable|string',
            'fecha_reporte' => 'required|date',
            'fecha_cierre' => 'nullable|date',
        ]);

        $incidencia->update([
            'id_maquina' => $request->id_maquina,
            'descripcion' => $request->descripcion,
            'gravedad' => $request->gravedad,
            'estado' => "Abierta",
             'id_creador' => 1,
        ]);
        return response()->json($incidencia);
    }

    public function finalizarIncidencia(Request $request, $idIncidencia)
    {
        // Buscar la incidencia
        $incidencia = Incidencia::findOrFail($idIncidencia);

        // Verificar si todas las fases están completadas
        $fasesPendientes = $incidencia->fasesIncidencias()->where('estado', '!=', 'Completada')->count();

        if ($fasesPendientes > 0) {
            return response()->json(['error' => 'No se puede finalizar la incidencia porque hay fases pendientes'], 400);
        }

        // Cambiar el estado de la incidencia a 'Finalizada'
        $incidencia->estado = 'Resuelta';
        $incidencia->fecha_cierre = now();
        $incidencia->save();

        if ($incidencia->id_tipo_mantenimiento && $incidencia->frecuencia > 0) {
            $nuevaIncidencia = new Incidencia([
                'id_maquina' => $incidencia->id_maquina,
                'id_creador' => $incidencia->id_creador,
                'descripcion' => $incidencia->descripcion,
                'gravedad' => $incidencia->gravedad,
                'id_tipo_mantenimiento' => $incidencia->id_tipo_mantenimiento,
                'estado' => 'Abierta',
                'frecuencia' => $incidencia->frecuencia,
                'fecha_reporte' => Carbon::parse($incidencia->fecha_cierre)->copy()->addDays($incidencia->frecuencia),
                // ... otros campos que necesites copiar
            ]);
    
            $nuevaIncidencia->save();
        }

        return response()->json([
            'message' => 'Incidencia finalizada exitosamente',
            'incidencia' => $incidencia,
        ]);
    }


}