<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Core\Api\ResponseApi;
use App\Facades\UsuarioFacade;
use Illuminate\Http\JsonResponse;
use App\Exceptions\ApiValidationException;
use App\Core\System\RenderDefaultException;
use App\Models\Rules\Usuarios\Api\ListagemUsuarios;
use App\Http\Requests\Usuario\{CadastroRequest, AtualizarRequest, AlterarRequest};

/**
 * class UsuarioController
 *
 * Classe responsável por manipular as requisições para a rota de usuários
 */
class UsuarioController extends Controller {
	/**
	 * Método responsável por montar a listagem de usuários
	 * @return JsonResponse
	 */
	public function index(Request $request) {
		$obListagem = (new ListagemUsuarios($request->all()));
		return ResponseApi::render(conteudo: $obListagem->listar(), codigo: 200);
	}

	/**
	 * Método responsável por realizar o cadastro de um usuário
	 * @param  CadastroRequest			$request			Dados da requisição de cadastro
	 * @return JsonResponse
	 */
	public function store(CadastroRequest $request) {
		$dados        = $request->validated();
		$obUsuarioDTO = null;

		try {
			$mensagem 		= 'Usuário cadastrado com sucesso!';
			$obUsuarioDTO = UsuarioFacade::novoUsuario($dados);

			return ResponseApi::render(
				sucesso: true, mensagem: $mensagem, indice: 'usuario',
				conteudo: UsuarioFacade::montarResponseCompletaUsuario($obUsuarioDTO)
			);
		} catch(\Throwable $th) {
			return RenderDefaultException::render($th);
		}
	}

	/**
	 * Método responsável por consultar os dados de um usuário
	 * @param  string 			$id 			ID do usuário consultado
	 * @return JsonResponse
	 */
	public function show(string $id) {
		try {
			if(!is_numeric($id)) {
				throw new ApiValidationException('O ID de usuário informado deve ser numérico!');
			}

			// MONTA A RESPONSE
			$mensagem = "Usuário encontrado!";
			$conteudo = UsuarioFacade::verUsuario($id);

			return ResponseApi::render(
				sucesso: true, mensagem: $mensagem, codigo: 200,
				conteudo: $conteudo, indice: 'usuario'
			);
		} catch(\Throwable $th) {
			return RenderDefaultException::render($th);
		}
	}

	/**
	 * Método responsável por realizar a atualização de alguns dados de um usuário
	 * @param  AlterarRequest 			$request 			Dados da requisição de atualização
	 * @param  string  							$id						ID do usuário que está sendo atualizado
	 * @return JsonResponse
	 */
	public function update(AlterarRequest $request, string $id) {
		$dados       = $request->validated();
		$dados['id'] = $id;

		try {
			// MONTA A RESPONSE
			$mensagem = "Usuário alterado com sucesso!";
			$conteudo = UsuarioFacade::alteracaoParcialUsuario($dados);

			return ResponseApi::render(
				sucesso: true, mensagem: $mensagem, codigo: 200,
				conteudo: $conteudo, indice: 'usuario'
			);
		} catch (\Throwable $th) {
			return RenderDefaultException::render($th);
		}
	}

	/**
	 * Método responsável por realizar a atualização dos dados de um usuário
	 * @param  AtualizarRequest 			$request 			Dados da requisição de atualização
	 * @param  string 								$id						ID do usuário que está sendo atualizado
	 * @return JsonResponse
	 */
	public function edit(AtualizarRequest $request, string $id) {
		// OBTÉM OS DADOS DA REQUISIÇÃO
		$dados       = $request->validated();
		$dados['id'] = (int) $id;

		// RESPONSE DA ATUALIZAÇÃO
		$mensagem = 'Atualização do usuário efetuada com sucesso!';
		$conteudo = UsuarioFacade::atualizarUsuario($dados);

		return ResponseApi::render(
			sucesso: true, mensagem: $mensagem, conteudo: $conteudo, indice: 'usuario'
		);
	}

	/**
	 * Método responsável por realizar a remoção de um usuário
	 * @param  string 			$id 			ID do usuário que será removido
	 * @return JsonResposne
	 */
	public function destroy(string $id) {
		try {
			// VERIFICA A EXISTÊNCIA DO USUÁRIO
			if(!is_numeric($id)) $id = 0;

			UsuarioFacade::removerUsuario($id);

			return ResponseApi::render(codigo: 204);
		} catch (\Throwable $th) {
			return RenderDefaultException::render($th);
		}
	}
}
