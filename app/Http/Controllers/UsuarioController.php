<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Core\Api\ResponseApi;
use App\Core\Database\Converter;
use App\Models\Instances\Usuario;
use Illuminate\Http\JsonResponse;
use App\Core\Security\PasswordEncryptor;
use App\Exceptions\ApiValidationException;
use App\Core\System\RenderDefaultException;
use App\Models\Rules\Usuarios\Api\ListagemUsuarios;
use App\Http\Requests\Usuario\{CadastroRequest, AtualizarRequest};
use App\Models\Repository\{UsuarioRepository, Pessoa\PessoaRepository};
use App\Models\Instances\Pessoa\{PessoaInterface as Pessoa, PessoaFisica, PessoaInterface, PessoaJuridica};

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
		$dados     = $request->validated();
		$obPessoa  = null;
		$obUsuario = null;

		try {
			$obPessoa  = PessoaRepository::cadastrar($this->obterObjetoPessoa($dados));
			$obUsuario = UsuarioRepository::cadastrar($this->obterObjetoUsuario($dados), $obPessoa);

			// MONTA A RESPONSE
			$mensagem = 'Usuário cadastrado com sucesso!';
			$conteudo = $this->montarResponseUsuario($obUsuario, $obPessoa);

			return ResponseApi::render(
				sucesso: true, mensagem: $mensagem, conteudo: $conteudo, indice: 'usuario'
			);
		} catch(\Throwable $th) {
			PessoaRepository::remover($obPessoa);
			UsuarioRepository::remover($obUsuario);

			return RenderDefaultException::render($th);
		}
	}

	/**
	 * Método responsável por consultar os dados de um usuário
	 * @param  string 			$id 			ID do usuário consultado
	 * @return JsonResponse
	 */
	public function show(string $id) {
		$obPessoa  = null;
		$obUsuario = null;

		try {
			if(!is_numeric($id)) {
				throw new ApiValidationException('O ID de usuário informado deve ser numérico!');
			}

			$obUsuario = UsuarioRepository::getUsuarioPorId($id);
			if(!is_numeric($obUsuario->id)) {
				throw new ApiValidationException("O usuário com ID '{$id}', não foi encontrado.", code: 404);
			}

			// BUSCA OS DADOS PESSOAIS DO USUÁRIO
			$obPessoa = PessoaRepository::getPessoaPorId($obUsuario->idPessoa);

			// MONTA A RESPONSE
			$mensagem = "Usuário encontrado!";
			$conteudo = $this->montarResponseUsuario($obUsuario, $obPessoa);

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
	 * @param  Request 			$request 			Dados da requisição de atualização
	 * @param  string  			$id						ID do usuário que está sendo atualizado
	 * @return JsonResponse
	 */
	public function update(Request $request, string $id) {
		//
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

		$obPessoa  = $this->obterObjetoPessoa($dados);
		$obUsuario = $this->obterObjetoUsuario($dados, false);

		// SALVA O ID DA PESSOA
		$obPessoa->idPessoa = $obUsuario->idPessoa;

		// REALIZA AS ATUALIZAÇÕES
		$obPessoa  = PessoaRepository::atualizar($obPessoa);
		UsuarioRepository::atualizar($obUsuario);

		// RESPONSE DA ATUALIZAÇÃO
		$mensagem = 'Atualização do usuário efetuada com sucesso!';
		$conteudo = $this->montarResponseUsuario($obUsuario, $obPessoa);

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
		//
	}

	/**
	 * Método responsável por converter os dados de uma pessoa enviados na request em objeto
	 * @param  array 			$dados 			Dados enviados na requisição
	 * @return Pessoa
	 */
	private function obterObjetoPessoa(array $dados): Pessoa {
		$obPessoa = null;
		switch($dados['tipoPessoa']) {
			case 'fisica':
				$obPessoa       = new PessoaFisica;
				$obPessoa->nome = $dados['nome'];
				$obPessoa->cpf  = $dados['cpf'];
				break;

			case 'juridica':
				$obPessoa               = new PessoaJuridica;
				$obPessoa->nomeFantasia = $dados['nomeFantasia'];
				$obPessoa->razaoSocial  = $dados['razaoSocial'];
				$obPessoa->cnpj         = $dados['cnpj'];
				break;
		}

		return $obPessoa;
	}

	/**
	 * Método responsável por converter os dados de usuário enviados na request em objeto
	 * @param  array 			$dados 					Dados enviados na requisição
	 * @param  bool       $cadastro 			Define se está retornando um objeto para o cadastro ou atualização
	 * @return Usuario
	 */
	private function obterObjetoUsuario(array $dados, bool $cadastro = true): Usuario {
		$obUsuario           = new Usuario;
		$obUsuario->email    = $dados['email'];
		$obUsuario->idPerfil = $dados['idPerfil'];
		$obUsuario->icone    = '';

		// SALVA O ID DO USUÁRIO
		if(!$cadastro) $obUsuario->id = $dados['id'];

		// STATUS DE ATIVAÇÃO DO USUÁRIO
		$obUsuario->ativo = $cadastro ? 's': ($dados['ativo'] ?? null);

		// SALVA A SENHA DO USUÁRIO
		if(isset($dados['senha'])) $obUsuario->senha = PasswordEncryptor::encrypt($dados['senha']);

		// OBTÉM O ID DA PESSOA CADASTRADA
		if(!$cadastro) {
			$obCadastrado = UsuarioRepository::getUsuarioPorId($obUsuario->id, ['id_pessoa', 'data_hora_criacao']);
			$obUsuario->idPessoa        = $obCadastrado->idPessoa;
			$obUsuario->dataHoraCriacao = $obCadastrado->dataHoraCriacao;
		}

		return $obUsuario;
	}

	/**
	 * Método responsável por montar a response do usuário
	 * @param  Usuario 													$obUsuario 			Objeto do usuário
	 * @param  PessoaFisica|PessoaJuridica 			$obPessoa				Objeto dos dados pessoais do usuário
	 * @return array
	 */
	private function montarResponseUsuario(Usuario $obUsuario, PessoaInterface $obPessoa): array {
		$dados = array_merge(
			(new Converter($obUsuario))->objectToArrayClass(),
			(new Converter($obPessoa))->objectToArrayClass()
		);

		// CONVERSÃO DO CAMPO DE ATIVAÇÃO
		$dados['ativo'] = $dados['ativo'] == 's';

		// CORRIGE O ID DO USUÁRIO
		$dados['id'] = $obUsuario->id;

		// CAMPOS NÃO RETORNADOS
		unset($dados['idPessoa'], $dados['senha']);

		return $dados;
	}
}
