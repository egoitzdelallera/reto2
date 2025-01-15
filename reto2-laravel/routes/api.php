<?php

use App\Http\Controllers\MaquinaController; 
use Illuminate\Routing\Route;// Ejemplo

Route::get('/maquinas', [MaquinaController::class, 'index']);      // Listar máquinas
Route::post('/maquinas', [MaquinaController::class, 'store']);      // Crear máquina
Route::get('/maquinas/{maquina}', [MaquinaController::class, 'show']);    // Mostrar una máquina
Route::put('/maquinas/{maquina}', [MaquinaController::class, 'update']);   // Actualizar máquina
Route::delete('/maquinas/{maquina}', [MaquinaController::class, 'destroy']); // Eliminar máquina