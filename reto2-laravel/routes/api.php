<?php
// filepath: /c:/Users/inigo.LAPTOP-VN7GFM8A/REPO_INIGO/reto2/reto2-laravel/routes/api.php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MaquinaController;
use App\Http\Controllers\IncidenciaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CampusController;
use App\Http\Controllers\TipoAveriaController;
use App\Http\Controllers\TipoMantenimientoController;
use App\Http\Controllers\MantenimientoController;
use App\Http\Controllers\FasesIncidenciaController;
use App\Http\Controllers\TalleresController;
use App\Http\Controllers\GestionTodoController;


// Rutas protegidas por JWT
Route::middleware('jwt.auth')->group(function () {
    Route::get('/user', [UserController::class, 'user']);
    Route::get('/perfil', [UserController::class, 'user']);
    Route::post('logout', [UserController::class, 'logout']);
    Route::get('/campus', [CampusController::class, 'index']);
    Route::patch('/users/{id}', [UserController::class, 'update']); 
    Route::post('/users', [UserController::class, 'store']);
     Route::patch('/users/{id}/toggle-status', [UserController::class, 'toggleStatus']);
     //Route::get('/incidenciasPerfil', [IncidenciaController::class, 'index']); //Removed This line
});

// Rutas para máquinas
//Route::middleware('api')->group(function () { //Remove This line
Route::get('/maquinas', [MaquinaController::class, 'index']);
Route::post('/maquinas', [MaquinaController::class, 'store']);
Route::get('/maquinas/{maquina}', [MaquinaController::class, 'show']);
Route::patch('/maquinas/{maquina}', [MaquinaController::class, 'update']); // Changed from put to patch
Route::delete('/maquinas/{maquina}', [MaquinaController::class, 'destroy']);
Route::patch('/maquinas/{maquina}/toggle-status', [MaquinaController::class, 'toggleStatus']);
//}); //Remove This line
//Route::middleware('api')->group(function () { //Remove This line
Route::get('/users', [UserController::class, 'index']);
Route::post('/users', [UserController::class, 'store']);
Route::get('/user/{user}', [UserController::class, 'show']);
Route::delete('/user/{user}', [UserController::class, 'destroy']);
//}); //Remove This line

//Route::middleware('api')->group(function () { //Remove This line
Route::get('/talleres', [TalleresController::class, 'index']);
Route::get('/talleres/{id}', [TalleresController::class, 'show']);
Route::post('/talleres', [TalleresController::class, 'store']);
Route::put('/talleres/{id}', [TalleresController::class, 'update']);
Route::delete('/talleres/{id}', [TalleresController::class, 'destroy']);
//}); //Remove This line

// Ruta para inicio de sesión
Route::post('login', [UserController::class, 'login']);

// Rutas para incidencias
//Route::middleware('api')->group(function () { //Remove This line
Route::get('/incidencias', [IncidenciaController::class, 'index']);
Route::get('/incidenciasPerfil', [IncidenciaController::class, 'index2']);
Route::get('/incidencias/{id_incidencia}', [IncidenciaController::class, 'show']);
Route::post('/incidencias', [IncidenciaController::class, 'store']); // This line is crucial
Route::put('/incidencias/{id}', [IncidenciaController::class, 'update']);
Route::delete('/incidencias/{id}', [IncidenciaController::class, 'destroy']);
Route::get('/tipo-averia-options', [IncidenciaController::class, 'getTipoAveriaOptions']);
    //Tipos Averia Resource (Protected)
Route::get('/tipos-averia', [TipoAveriaController::class, 'index']);
    //Rutas para tipo mantenimiento
//Route::middleware('api')->group(function () { //Remove This line
Route::get('/tipos-mantenimiento', [TipoMantenimientoController::class, 'index']);
Route::get('/tipos-mantenimiento/{id}', [TipoMantenimientoController::class, 'show']);
Route::post('/tipos-mantenimiento', [TipoMantenimientoController::class, 'store']);
Route::delete('/tipos-mantenimiento/{id}', [TipoMantenimientoController::class, 'destroy']);


//});


Route::post('/mantenimientos', [MantenimientoController::class, 'store']); // Nueva ruta
Route::middleware('api')->group(function () {
    Route::get('/incidencias', [IncidenciaController::class, 'index']);
    Route::get('/incidencias/{id_incidencia}', [IncidenciaController::class, 'show']);
    Route::post('/incidencias', [IncidenciaController::class, 'store']);
    Route::put('/incidencias/{id}', [IncidenciaController::class, 'update']);
    Route::put('incidencias/{incidenciaId}/finalizar', [IncidenciaController::class, 'finalizarIncidencia']);
});

// Rutas para las fases
Route::post('/fases/{faseId}/finalizar', [FasesIncidenciaController::class, 'finalizarFase']);
Route::post('/fases/{faseId}/tecnicos', [FasesIncidenciaController::class, 'asignarTecnicoAFase']);
Route::post('/fases', [FasesIncidenciaController::class, 'crearFase']);

// Rutas protegidas por JWT

Route::middleware('api')->post('logout', [UserController::class, 'logout']);
Route::middleware('jwt.auth')->get('user', [UserController::class, 'user']);

//Rutas campus
Route::middleware('jwt.auth')->group(function () {
   Route::get('/campus', [CampusController::class, 'index']);
   Route::patch('/campus/{id}', [GestionTodoController::class, 'updateCampus']);
   Route::patch('/campus/{id}/toggle-status', [GestionTodoController::class, 'toggleCampusStatus']);
   Route::patch('/talleres/{id}', [GestionTodoController::class, 'updateTaller']);
   Route::patch('/tipo-averias/{id}/toggle-status', [GestionTodoController::class, 'toggleTipoAveriaStatus']);   
   Route::patch('/tipo-mantenimientos/{id}/toggle-status', [GestionTodoController::class, 'toggleTipoMantenimientoStatus']);   
   Route::patch('/talleres/{id}/toggle-status', [GestionTodoController::class, 'toggleTallerStatus']);
   Route::patch('/tipo-mantenimiento/{id}', [GestionTodoController::class, 'updateTipoMantenimiento']);

});
   Route::post('/campus', [GestionTodoController::class, 'createCampus']);
   Route::put('/tipo-averias/{id}', [GestionTodoController::class, 'updateTipoAveria']);
Route::post('/talleres', [GestionTodoController::class, 'createTaller']);

Route::post('/tipo-averias', [GestionTodoController::class, 'createTipoAveria']);
Route::get('/tipos-averia', [TipoAveriaController::class, 'index']);

Route::post('/tipo-mantenimientos', [GestionTodoController::class, 'createTipoMantenimiento']);



Route::post('/campus', [GestionTodoController::class, 'createCampus']);
Route::put('/campus/{id}', [GestionTodoController::class, 'updateCampus']);
Route::post('/talleres', [GestionTodoController::class, 'createTaller']);
Route::put('/talleres/{id}', [GestionTodoController::class, 'updateTaller']);

Route::post('/tipo-averias', [GestionTodoController::class, 'createTipoAveria']);
Route::put('/tipo-averias/{id}', [GestionTodoController::class, 'updateTipoAveria']);
Route::get('/tipos-averia', [TipoAveriaController::class, 'index']);


Route::post('/tipo-mantenimientos', [GestionTodoController::class, 'createTipoMantenimiento']);
Route::put('/tipo-mantenimientos/{id}', [GestionTodoController::class, 'updateTipoMantenimiento']);
