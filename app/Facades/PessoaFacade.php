<?php

namespace App\Facades;

use App\Core\Database\Converter;
use App\Core\Database\InstanceInterface;
use App\Models\Repository\Pessoa\PessoaRepository;
use App\Models\Instances\Pessoa\{PessoaFisica, PessoaJuridica};

/**
 * class PessoaFacade
 *
 * Classe responssável por abstrair as ações de manipulação de uma pessoa (física ou jurídica)
 */
abstract class PessoaFacade {
	/**
	 * Método responsável por gerar o objeto de uma pessoa (física ou jurídica)
	 * @param  array 			$dadadosCadastros 			Dados pessoais
	 * @return PessoaFisica|PessoaJuridica
	 */
	public static function gerarPessoaParaCadastro(array $dadadosCadastros): InstanceInterface {
		$gerarPessoaFisica = isset($dadadosCadastros['tipoPessoa']) && $dadadosCadastros['tipoPessoa'] == 'fisica';
		return self::gerarObjetoPessoa($dadadosCadastros, $gerarPessoaFisica);
	}

	/**
	 * Método responsável por realizar o cadastro dos dados pessoais de uma pessoa
	 * @param  PessoaFisica|PessoaJuridica 			$obPessoa 			Dados pessoais
	 * @param  string 										 			$erro						Mensagem de erro no processamento
	 * @param  int 										 			    $code						Código de erro da primeira excessão
	 * @return bool
	 */
	public static function cadastrarDadosPessoais(
		InstanceInterface &$obPessoa, &$erro = "", &$code = 0
	): bool {
		$sucesso = true;

		try {
			$obPessoa = PessoaRepository::cadastrar($obPessoa);
		} catch (\Throwable $th) {
			// TIPO REMOÇÃO
			(!is_numeric($obPessoa->id)) ? PessoaRepository::removerPessoa($obPessoa->idPessoa)
																	 : PessoaRepository::remover($obPessoa);

			// MONTA OS DADOS DE RESPOSTA
			$erro    = $th->getMessage();
			$code    = $th->getCode();
			$sucesso = false;
		}

		return $sucesso;
	}

	/**
	 * Método responsável por gerar o objeto que representa uma pessoa somente com os dados informados
	 * @param  array 			$dados
	 * @param  bool  			$pessoaFisica 			Define se a pessoa é física ou jurídica
	 * @return PessoaFisica|PessoaJuridica
	 */
	private static function gerarObjetoPessoa(array $dados, bool $pessoaFisica): InstanceInterface {
		$obPessoa  = $pessoaFisica ? new PessoaFisica: new PessoaJuridica;
		$converter = new Converter($obPessoa, arrayClass: $dados, validos: true);
		return $converter->arrayClassToObject();
	}
}
