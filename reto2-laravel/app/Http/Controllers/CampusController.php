<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Campus;
use App\Models\Incidencia;

class CampusController extends Controller
{
    public function index()
    {
        $campus = Campus::all();
        return response()->json($campus);
    }

    public function getIncidenciasByCampus($id_campus)
    {
        $incidencias = Incidencia::whereHas('maquina', function ($query) use ($id_campus) {
            $query->where('id_campus', $id_campus);
        })->with(['maquina', 'creador', 'tecnico'])->get();

        return response()->json($incidencias);
    }
}