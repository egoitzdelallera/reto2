<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mantenimiento;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MantenimientoController extends Controller
{
    public function store(Request $request)
    {
        Log::info('Inicio del método store en MantenimientoController');
        Log::info('Datos de la solicitud: ' . json_encode($request->all()));

        try {
            $validatedData = $request->validate([
                'maquinas' => 'required|array',
                'maquinas.*.id_maquina' => 'required|integer|exists:maquinas,id_maquina',
                // Removed id_creador from validation since its being fetched from the token
                'id_tipo_mantenimiento' => 'required|integer|exists:tipo_mantenimiento,id_tipo_mantenimiento',
                'descripcion' => 'required|string|max:300',
                'gravedad' => 'required|in:Mantenimiento',
                'estado' => 'required|in:Abierta,En progreso,Resuelta,Cancelada',
                'frecuencia' => 'nullable|integer',
                'multimedia' => 'nullable|string|max:300',
            ]);

            Log::info('Validación exitosa');

            $idCreador = Auth::id(); // Get the ID from the authenticated user

            if (!$idCreador) {
                throw new \Exception('No se pudo determinar el id del creador');
            }
            $incidenciasCreadas = [];

            foreach ($validatedData['maquinas'] as $maquina) {
                $incidencia = Mantenimiento::create([
                    'id_maquina' => $maquina['id_maquina'],
                    'id_creador' => $idCreador, //Use id from the token
                    'id_tipo_mantenimiento' => $validatedData['id_tipo_mantenimiento'],
                    'descripcion' => $validatedData['descripcion'],
                    'gravedad' => 'Mantenimiento',
                    'estado' => $validatedData['estado'],
                    'frecuencia' => $validatedData['frecuencia'] ?? null,
                    'multimedia' => $validatedData['multimedia'] ?? null,
                ]);

                $incidenciasCreadas[] = $incidencia;
                Log::info('Incidencia creada con éxito: ' . $incidencia->id_incidencia);
            }

            return response()->json(['message' => 'Mantenimientos creados con éxito', 'incidencias' => $incidenciasCreadas], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Error de validación: ' . json_encode($e->errors()));
            return response()->json(['message' => 'Error de validación', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Error al crear las incidencias: ' . $e->getMessage());
            return response()->json(['message' => 'Error al crear las incidencias', 'error' => $e->getMessage()], 500);
        }
    }
}