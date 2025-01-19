<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MaquinaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\IncidenciaController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('jwt.auth');

// Rutas para máquinas
Route::middleware('api')->group(function () {
    Route::get('/maquinas', [MaquinaController::class, 'index']);
    Route::post('/maquinas', [MaquinaController::class, 'store']);
    Route::get('/maquinas/{maquina}', [MaquinaController::class, 'show']);
    Route::put('/maquinas/{maquina}', [MaquinaController::class, 'update']);
    Route::delete('/maquinas/{maquina}', [MaquinaController::class, 'destroy']);
});

// Ruta para inicio de sesión
Route::post('login', [AuthController::class, 'login']);

Route::get('/incidencias', [IncidenciaController::class, 'index']);


// Rutas para incidencias
Route::middleware('api')->group(function () {
    Route::get('/incidencias/{id}', [IncidenciaController::class, 'show']);
    Route::post('/incidencias', [IncidenciaController::class, 'store']);
    Route::put('/incidencias/{id}', [IncidenciaController::class, 'update']);
    Route::delete('/incidencias/{id}', [IncidenciaController::class, 'destroy']);
});


// Rutas protegidas por JWT
Route::middleware('jwt.auth')->post('logout', [AuthController::class, 'logout']);
Route::middleware('jwt.auth')->get('user', [AuthController::class, 'user']);

