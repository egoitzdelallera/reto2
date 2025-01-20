<?php
// filepath: /c:/Users/inigo.LAPTOP-VN7GFM8A/REPO_INIGO/reto2/reto2-laravel/routes/api.php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MaquinaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\IncidenciaController;
use App\Http\Controllers\TalleresController;

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

// Rutas para talleres
Route::middleware('api')->group(function () {
    Route::get('/talleres', [TalleresController::class, 'index']);
    Route::get('/talleres/{id}', [TalleresController::class, 'show']);
    Route::post('/talleres', [TalleresController::class, 'store']);
    Route::put('/talleres/{id}', [TalleresController::class, 'update']);
    Route::delete('/talleres/{id}', [TalleresController::class, 'destroy']);
});

// Rutas para incidencias
Route::middleware('api')->group(function () {
    Route::get('/incidencias', [IncidenciaController::class, 'index']);
    Route::get('/incidencias/{id_incidencia}', [IncidenciaController::class, 'show']);
    Route::post('/incidencias', [IncidenciaController::class, 'store']);
    Route::put('/incidencias/{id}', [IncidenciaController::class, 'update']);
    Route::delete('/incidencias/{id}', [IncidenciaController::class, 'destroy']);
});

// Rutas protegidas por JWT
Route::middleware('jwt.auth')->post('logout', [AuthController::class, 'logout']);
Route::middleware('jwt.auth')->get('user', [AuthController::class, 'user']);