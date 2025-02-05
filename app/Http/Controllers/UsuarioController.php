<?php

namespace App\Http\Controllers;

use App\Core\Database\Converter;
use Illuminate\Http\Request;
use App\Models\Instances\Usuario;
use App\Core\Security\PasswordEncryptor;
use App\Core\System\RenderDefaultException;
use App\Exceptions\ApiValidationException;
use App\Http\Requests\Usuario\CadastroRequest;
use App\Models\Rules\Usuarios\Api\ListagemUsuarios;
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
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function index(Request $request) {
		$obListagem = (new ListagemUsuarios($request->all()));
		return response()->json($obListagem->listar());
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(CadastroRequest $request) {
		$dados     = $request->validated();
		$obPessoa  = null;
		$obUsuario = null;
		$response  = [ 'sucesso' => true, 'mensagem' => '' ];
		$codigo    = 201;

		try {
			$obPessoa  = PessoaRepository::cadastrar($this->obterObjetoPessoa($dados));
			$obUsuario = UsuarioRepository::cadastrar($this->obterObjetoUsuario($dados), $obPessoa);

			// MONTA A RESPONSE
			$response['mensagem'] = 'Usuário cadastrado com sucesso!';

			// DADOS DO NOVO USUÁRIO
			$response['usuario'] = $this->montarResponseUsuario($obUsuario, $obPessoa);
		} catch(\Throwable $th) {
			PessoaRepository::remover($obPessoa);
			UsuarioRepository::remover($obUsuario);

			$response = RenderDefaultException::render($th, $codigo);
		}

		return response()->json($response, $codigo);
	}

	/**
	 * Display the specified resource.
	 */
	public function show(string $id) {
		$codigo    = 200;
		$response  = [];
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
			$response['status']  = true;
			$response['message'] = "Usuário encontrado!";
			$response['usuario'] = $this->montarResponseUsuario($obUsuario, $obPessoa);
		} catch(\Throwable $th) {
			$response = RenderDefaultException::render($th, $codigo);
		}

		return response()->json($response, $codigo);
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
	 * @param  array 			$dados 			Dados enviados na requisição
	 * @return Usuario
	 */
	private function obterObjetoUsuario(array $dados): Usuario {
		$obUsuario           = new Usuario;
		$obUsuario->email    = $dados['email'];
		$obUsuario->senha    = PasswordEncryptor::encrypt($dados['senha']);
		$obUsuario->idPerfil = $dados['idPerfil'];
		$obUsuario->icone    = '';
		$obUsuario->ativo    = 's';
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
