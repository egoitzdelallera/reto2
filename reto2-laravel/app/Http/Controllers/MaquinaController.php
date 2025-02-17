<?php

namespace App\Http\Controllers;

use App\Models\Maquina;
use Illuminate\Http\Request;

class MaquinaController extends Controller
{
    public function index(Request $request)
    {
        return response()->json(Maquina::all());
    }
    
    public function store(Request $request)
    {
        $maquina = Maquina::create($request->all());
        return response()->json(['message' => 'Maquina creada correctamente', 'maquina' =>$maquina], 201);
    }

    public function show(Maquina $maquina)
    {
        return response()->json($maquina);
    }

    public function update(Request $request, Maquina $maquina){
        $maquina->update($request->all());
         return response()->json(['message' => 'Maquina actualizada correctamente', 'maquina' => $maquina], 200);
    }

    public function destroy(Maquina $maquina)
    {
        $maquina->delete();
        return response()->json(null, 204);
    }
       public function toggleStatus(Request $request, Maquina $maquina)
    {
          
        $maquina->estado = $maquina->estado === 'Habilitado' ? "Deshabilitado" : "Habilitado";
        $maquina->save();
       
         return response()->json(['message' => 'Estado de la maquina actualizado correctamente', 'maquina' => $maquina]);
    }
}