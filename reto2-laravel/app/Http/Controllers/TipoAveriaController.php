<?php

namespace App\Http\Controllers;

use App\Models\TipoAveria; // Assuming you have a TipoAveria model
use Illuminate\Http\JsonResponse;

class TipoAveriaController extends Controller
{
    /**
     * Display a listing of the resource (all tipoAveria records).
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $tipoAverias = TipoAveria::all();
        return response()->json($tipoAverias);
    }
}