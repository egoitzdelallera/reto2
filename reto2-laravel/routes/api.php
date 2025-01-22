<?php
// filepath: /c:/Users/inigo.LAPTOP-VN7GFM8A/REPO_INIGO/reto2/reto2-laravel/routes/api.php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MaquinaController;
use App\Http\Controllers\IncidenciaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CampusController;
use App\Http\Controllers\TalleresController;

// Rutas protegidas por JWT
Route::middleware('jwt.auth')->group(function () {
    Route::get('/user', [UserController::class, 'user']);
    Route::get('/perfil', [UserController::class, 'user']);
    Route::post('logout', [UserController::class, 'logout']);
    Route::get('/campus', [CampusController::class, 'index']);
    Route::patch('/users/{id}', [UserController::class, 'update']); // Changed from post to patch
    Route::post('/users', [UserController::class, 'store']);
     Route::patch('/users/{id}/toggle-status', [UserController::class, 'toggleStatus']);
      Route::get('/incidenciasPerfil', [IncidenciaController::class, 'index']); //Added this line

});

// Rutas para máquinas
Route::middleware('api')->group(function () {
    Route::get('/maquinas', [MaquinaController::class, 'index']);
    Route::post('/maquinas', [MaquinaController::class, 'store']);
    Route::get('/maquinas/{maquina}', [MaquinaController::class, 'show']);
    Route::put('/maquinas/{maquina}', [MaquinaController::class, 'update']);
    Route::delete('/maquinas/{maquina}', [MaquinaController::class, 'destroy']);
});
Route::middleware('api')->group(function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
    Route::get('/user/{user}', [UserController::class, 'show']);
    Route::delete('/user/{user}', [UserController::class, 'destroy']);
});

Route::middleware('api')->group(function () {
    Route::get('/talleres', [TalleresController::class, 'index']);
    Route::get('/talleres/{id}', [TalleresController::class, 'show']);
    Route::post('/talleres', [TalleresController::class, 'store']);
    Route::put('/talleres/{id}', [TalleresController::class, 'update']);
    Route::delete('/talleres/{id}', [TalleresController::class, 'destroy']);
});

// Ruta para inicio de sesión
Route::post('login', [UserController::class, 'login']);

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
    Route::get('/incidenciasPerfil', [IncidenciaController::class, 'index2']);
    Route::get('/incidencias/{id_incidencia}', [IncidenciaController::class, 'show']);
    Route::post('/incidencias', [IncidenciaController::class, 'store']);
    Route::put('/incidencias/{id}', [IncidenciaController::class, 'update']);
    Route::delete('/incidencias/{id}', [IncidenciaController::class, 'destroy']);
});