<?php

namespace App\Http\Controllers;

use App\Models\Maquina;
use Illuminate\Http\Request;

class MaquinaController extends Controller
{
    public function index()
    {
        $maquinas = Maquina::all();
        return response()->json($maquinas);
    }
    
    public function store(Request $request)
    {
        $maquina = Maquina::create($request->all());
        return response()->json($maquina, 201);
    }

    public function show(Maquina $maquina)
    {
        return response()->json($maquina);
    }

    public function update(Request $request, Maquina $maquina){
        $maquina->update($request->all());
        return response()->json($maquina, 200);
    }

    public function destroy(Maquina $maquina)
    {
        $maquina->delete();
        return response()->json(null, 204);
    }
}
