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

    public function show($id)
    {
        $incidencia = Incidencia::with([]);
        if (!$incidencia) {
            return response()->json(['message' => 'Incidencia no encontrada'], 404);
        }
        return response()->json($incidencia);
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