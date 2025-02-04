<?php

use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;

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
