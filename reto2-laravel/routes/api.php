<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MaquinaController;  // Asegúrate de que la línea use sea correcta


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');




Route::middleware('api')->group(function () {
    Route::get('/maquinas', [MaquinaController::class, 'index']);
    Route::post('/maquinas', [MaquinaController::class, 'store']);
    Route::get('/maquinas/{maquina}', [MaquinaController::class, 'show']);
    Route::put('/maquinas/{maquina}', [MaquinaController::class, 'update']);
    Route::delete('/maquinas/{maquina}', [MaquinaController::class, 'destroy']);
});