<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Core\Api\ResponseApi;
use App\Facades\UsuarioFacade;
use Illuminate\Http\JsonResponse;
use App\Models\Instances\Perfil\Perfil;
use App\Exceptions\ApiValidationException;
use App\Core\System\RenderDefaultException;
use App\Http\Requests\Cuidador\{CadastroRequest, AtualizarRequest, AlterarRequest};

/**
 * class CuidadorController
 *
 * Classe responsável por manipular as requisições para a rota de cuidadores
 */
class CuidadorController extends Controller {
	/**
	 * Método responsável por realizar o cadastro de um cuidador
	 * @param  CadastroRequest			$request			Dados da requisição de cadastro
	 * @return JsonResponse
	 */
	public function store(CadastroRequest $request) {
		$dados = $request->validated();

		try {
			// FORÇA O ID DO CUIDADOR
			$dados['idPerfil'] = Perfil::ID_CUIDADOR;

			// REALIZA O CADASTRO
			$mensagem 		= 'Cuidador cadastrado com sucesso!';
			$obUsuarioDTO = UsuarioFacade::novoCuidador($dados);

			return ResponseApi::render(
				sucesso: true, mensagem: $mensagem, indice: 'cuidador',
				conteudo: UsuarioFacade::montarResponseCompletaUsuario($obUsuarioDTO)
			);
		} catch(\Throwable $th) {
			return RenderDefaultException::render($th);
		}
	}

	/**
	 * Método responsável por realizar a atualização dos dados de um cuidador
	 * @param  AtualizarRequest 			$request 			Dados da requisição de atualização
	 * @param  string 								$id						ID do cuidador que está sendo atualizado
	 * @return JsonResponse
	 */
	public function edit(AtualizarRequest $request, string $id) {
		// OBTÉM OS DADOS DA REQUISIÇÃO
		$dados       = $request->validated();
		$dados['id'] = (int) $id;

		// RESPONSE DA ATUALIZAÇÃO
		$mensagem = 'Atualização do cuidador efetuada com sucesso!';
		$conteudo = UsuarioFacade::atualizarCuidador($dados);

		return ResponseApi::render(
			sucesso: true, mensagem: $mensagem, conteudo: $conteudo, indice: 'cuidador'
		);
	}

	/**
	 * Método responsável por realizar a atualização de alguns dados de um cuidador
	 * @param  AlterarRequest 			$request 			Dados da requisição de atualização
	 * @param  string  							$id						ID do cuidador que está sendo atualizado
	 * @return JsonResponse
	 */
	public function update(AlterarRequest $request, string $id) {
		$dados       = $request->validated();
		$dados['id'] = $id;

		try {
			// MONTA A RESPONSE
			$mensagem = "Cuidador alterado com sucesso!";
			$conteudo = UsuarioFacade::alteracaoParcialUsuario($dados);

			return ResponseApi::render(
				sucesso: true, mensagem: $mensagem, codigo: 200,
				conteudo: $conteudo, indice: 'cuidador'
			);
		} catch (\Throwable $th) {
			return RenderDefaultException::render($th);
		}
	}
}
