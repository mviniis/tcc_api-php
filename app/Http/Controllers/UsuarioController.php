<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exceptions\ApiValidationException;
use App\Models\Rules\Usuarios\Api\ListagemUsuarios;
use Illuminate\Support\Facades\DB;

/**
 * class UsuarioController
 *
 * Classe responsável por manipular as requisições para a rota de usuários
 */
class UsuarioController extends Controller {
	/**
	 * Método responsável por montar a listagem de usuários
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function index(Request $request) {
		$obListagem = (new ListagemUsuarios($request->all()));
		return response()->json($obListagem->listar());
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(Request $request)
	{
		//
	}

	/**
	 * Display the specified resource.
	 */
	public function show(string $id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(Request $request, string $id)
	{
		//
	}

	public function edit() {

	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(string $id)
	{
		//
	}
}
