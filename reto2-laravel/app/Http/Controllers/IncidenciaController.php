<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Incidencia;

class IncidenciaController extends Controller
{
    public function index()
    {
         $incidencias = Incidencia::with(['maquina', 'maquina.taller', 'creador:id_usuario,nombre,apellido,correo,rol,estado,imagen_perfil,id_campus', 'creador.campus:id_campus,nombre', 'tecnico'])
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
        $incidencia = Incidencia::with(['maquina', 'maquina.taller', 'maquina.taller.campus', 'creador', 'fasesIncidencias', 'fasesIncidencias.tecnicosFasesIncidencias', 'fasesIncidencias.tecnicosFasesIncidencias.tecnico', 'tipoAveria'])->find($id_incidencia);
        if (!$incidencia) {
            return response()->json(['message' => 'Incidencia no encontrada'], 404);
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
   ]);
   
  try {
         // Obtener la fecha y hora actual
         $fecha_ini = now();  // La función `now()` obtiene la fecha y hora actual

        // Crear la incidencia
        $incidencia = Incidencia::create([
           'id_maquina' => $request->id_maquina,
            'descripcion' => $request->descripcion,
             'gravedad' => $request->gravedad,
             'estado' => "Abierta",
            'id_creador' => $request->id_creador,
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
            'fecha_ini' => 'required|date',
            'fecha_fin' => 'nullable|date',
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