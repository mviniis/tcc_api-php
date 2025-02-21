<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
	UsuarioController, CuidadorController
};

/**
 * Rotas para a manipulação dos dados de usuários
 */
Route::controller(UsuarioController::class)->group(function() {
	Route::get('/users', 'index');
	Route::get('/users/{id}', 'show');
	Route::post('/users', 'store');
	Route::put('/users/{id}', 'edit');
	Route::patch('/users/{id}', 'update');
	Route::delete('/users/{id}', 'destroy');
})->name('usuarios');

/**
 * Rotas para a manipulação dos dados de cuidadores
 */
Route::controller(CuidadorController::class)->group(function() {
	Route::post('/caregiver', 'store');
	Route::put('/caregiver/{id}', 'edit');
	Route::patch('/caregiver/{id}', 'update');
})->name('cuidadores');
