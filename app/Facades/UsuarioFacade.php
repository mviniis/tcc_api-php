<?php

namespace App\Facades;

use App\Core\Database\Converter;
use App\Core\Security\PasswordEncryptor;
use App\Models\DTOs\UsuarioDTO;
use App\Models\Instances\Usuario\Usuario;
use App\Models\Repository\Pessoa\PessoaRepository;
use App\Models\Repository\Usuario\UsuarioRepository;
use App\Exceptions\ActionRepositoryException as Exception;

/**
 * class UsuarioFacades
 *
 * Classe responssável por abstrair as ações de manipulação de um usuário
 */
abstract class UsuarioFacade {
	/**
	 * Método responsável por realizar a implementação do cadastro de um usuário
	 * @param  array 			$dadosCadastro 			Dados do novo usuário
	 * @return UsuarioDTO
	 */
	public static function novoUsuario(array $dadosCadastro): UsuarioDTO {
		$obUsuarioCadastro = self::gerarObjetoUsuarioParaCadastro($dadosCadastro);

		// REALIZA O CADASTRO DOS DADOS PESSOAIS
		$sucesso = PessoaFacade::cadastrarDadosPessoais($obUsuarioCadastro->pessoa, $erro, $code);
		if($sucesso) $sucesso = self::gerarNovoUsuario($obUsuarioCadastro, $erro, $code);

		// LANÇA A EXCESSÃO SE HOUVER ALGUM ERRO
		if(!$sucesso) throw new Exception($erro, $code);

		return $obUsuarioCadastro;
	}

	/**
	 * Método responsável por realizar a implementação da atualização de todos os dados de um usuário
	 * @param  array 			$dadosAtualizar 			Dados do usuário que serão atualizados
	 * @return array
	 */
	public static function atualizarUsuario(array $dadosAtualizar): array {
		$obUsuarioDTO = self::gerarObjetoUsuarioParaAtualizacao($dadosAtualizar);

		// REALIZA AS ATUALIZAÇÕES
		$obUsuarioDTO->pessoa  = PessoaRepository::atualizar($obUsuarioDTO->pessoa);
		UsuarioRepository::atualizar($obUsuarioDTO->usuario);

		return self::montarResponseCompletaUsuario($obUsuarioDTO);
	}

	/**
	 * Método responsável por realizar a implementação da alteração de algumas informações do usuário
	 * @param  array 			$dadosAlterar 			Informações do usuário que serão modificadas
	 * @return array
	 */
	public static function alteracaoParcialUsuario(array $dadosAlterar): array {
		if(isset($dadosAlterar['ativo'])) $dadosAlterar['ativo'] = ($dadosAlterar['ativo']) ? 's': 'n';

		// REALIZA A ATUALIZAÇÃO
		if(!UsuarioRepository::atualizar(self::gerarObjetoUsuario($dadosAlterar))) {
			throw new Exception('Não foi possível alterar o usuário. Tente novamente mais tarde');
		}

		return self::verUsuario($dadosAlterar['id']);
	}

	/**
	 * Mètodo responsável por realizar a implementação da visualização de um usuário
	 * @param  int 			$idUsuario 			ID do usuário a ser visualizado
	 * @return array
	 */
	public static function verUsuario(int $idUsuario): array {
		$obUsuario = UsuarioRepository::getUsuarioPorId($idUsuario);
		if(!is_numeric($obUsuario->id)) {
			throw new Exception("O usuário com ID '{$idUsuario}', não foi encontrado.", code: 404);
		}

		$obUsuarioDTO = self::gerarObjetoUsuarioCadastrado($obUsuario);
		return self::montarResponseCompletaUsuario($obUsuarioDTO);
	}

	/**
	 * Método responsável por realizar a implementação da remoção completa de um usuário
	 * @param  int 			$idUsuario 			ID do usuário a ser removido
	 * @return void
	 */
	public static function removerUsuario(int $idUsuario): void {
		$obUsuario = UsuarioRepository::getUsuarioPorId($idUsuario, ['id', 'id_pessoa']);
		if(!is_numeric($obUsuario->id)) {
			throw new Exception(
				message: "O usuário informado não foi encontrado!", code: 404
			);
		}

		// REALIZA AS REMOÇÕES
		UsuarioRepository::limpezaCompleta($obUsuario);
	}

	/**
	 * Método responsável por montar a response completa de um usuário
	 * @param  UsuarioDTO 			$obUsuarioDTO 			Dados do usuário que serão retornados
	 * @return array
	 */
	public static function montarResponseCompletaUsuario(UsuarioDTO $obUsuarioDTO): array {
		$dados = array_merge(
			(new Converter($obUsuarioDTO->usuario))->objectToArrayClass(),
			(new Converter($obUsuarioDTO->pessoa))->objectToArrayClass()
		);

		// CONVERSÃO DO CAMPO DE ATIVAÇÃO
		$dados['ativo'] = $dados['ativo'] == 's';

		// CORRIGE O ID DO USUÁRIO
		$dados['id'] = $obUsuarioDTO->usuario->id;

		// CAMPOS NÃO RETORNADOS
		unset($dados['idPessoa'], $dados['senha']);

		return $dados;
	}

	/**
	 * Método responsável por gerar um objeto DTO para o cadastro de um usuário
	 * @param  array 			$dados 			Dados de cadastro do novo usuário
	 * @return UsuarioDTO
	 */
	public static function gerarObjetoUsuarioParaCadastro(array $dados): UsuarioDTO {
		$obUsuarioDTO = new UsuarioDTO(
			PessoaFacade::gerarPessoaParaCadastro($dados),
			self::gerarObjetoUsuario($dados)
		);

		$obUsuarioDTO->usuario->ativo = 's';
		$obUsuarioDTO->usuario->icone = '';
		if(!empty($obUsuarioDTO->usuario->senha)) {
			$obUsuarioDTO->usuario->senha = PasswordEncryptor::encrypt($obUsuarioDTO->usuario->senha);
		}

		return $obUsuarioDTO;
	}

	/**
	 * Método responsável por montar o DTO para a atualização de um usuário
	 * @param  array 			$dados 			Dados para a atualizção do usuário
	 * @return UsuarioDTO
	 */
	public static function gerarObjetoUsuarioParaAtualizacao(array $dados): UsuarioDTO {
		$obUsuarioDTO 						= self::gerarObjetoUsuarioParaCadastro($dados);
		$obUsuarioDTO->pessoa->id = null;

		$existeCampoAtivo = isset($dados['ativo']);
		if(!$existeCampoAtivo) $obUsuarioDTO->usuario->ativo = null;

		// BUSCA OS DADOS PESSOAIS DO USUÁRIO
		$obCadastrado = UsuarioRepository::getUsuarioPorId(
			$obUsuarioDTO->usuario->id, ['id_pessoa', 'data_hora_criacao', 'ativo']
		);
		$obUsuarioDTO->pessoa->idPessoa         = $obCadastrado->idPessoa;
		$obUsuarioDTO->usuario->idPessoa        = $obCadastrado->idPessoa;
		$obUsuarioDTO->usuario->dataHoraCriacao = $obCadastrado->dataHoraCriacao;

		// EXIBE OS DADOS DE ATIVAÇÃO DO USUÁRIO
		if(!$existeCampoAtivo) $obUsuarioDTO->usuario->ativo = $obCadastrado->ativo;

		return $obUsuarioDTO;
	}

	/**
	 * Método responsável por montar os dados de DTO de um usuário já cadastrado
	 * @param  Usuario 			$obUsuario 			Dados do usuário
	 * @return UsuarioDTO
	 */
	public static function gerarObjetoUsuarioCadastrado(Usuario $obUsuario): UsuarioDTO {
		// BUSCA OS DADOS PESSOAIS DO USUÁRIO
		$obPessoa = PessoaRepository::getPessoaPorId($obUsuario->idPessoa);

		return new UsuarioDTO($obPessoa, $obUsuario);
	}

	/**
	 * Método responsável por gerar um objeto de usuário somente com os dados informados
	 * @param  array 			$dados 			Dados que poderão ser aplicados no objeto
	 * @return Usuario
	 */
	private static function gerarObjetoUsuario(array $dados): Usuario {
		$converter = new Converter(new Usuario, arrayClass: $dados, validos: true);
		return $converter->arrayClassToObject();
	}

	/**
	 * Método responsável por realizar as chamadas das funções de cadastro de um novo usuário
	 * @param  UsuarioDTO 			$obUsuario 			Dados do novo usuário
	 * @param  string 					$erro						Erro que pode ser gerado
	 * @param  int 							$code						Código do erro
	 * @return bool
	 */
	private static function gerarNovoUsuario(UsuarioDTO &$obUsuario, &$erro = "", &$code = 0): bool {
		try {
			UsuarioRepository::cadastrar($obUsuario);
			return true;
		} catch (\Throwable $th) {
			// REMOVE OS DADOS PESSOAIS
			PessoaRepository::remover($obUsuario->pessoa);

			$erro = $th->getMessage();
			$code = $th->getCode();
			return false;
		}
	}
}
