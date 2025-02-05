<?php

namespace App\Models\Repository\Pessoa;

use App\Core\Database\Converter;
use Illuminate\Support\Facades\DB;
use App\Exceptions\ActionRepositoryException as Exception;
use App\Models\Repository\Pessoa\{PessoaFisicaRepository, PessoaJuridicaRepository};
use App\Models\Instances\Pessoa\{Pessoa, PessoaInterface, PessoaFisica, PessoaJuridica};

/**
 * class PessoaRepository
 *
 * Classe responsável por manipular os dados da tabela 'pessoa'
 */
class PessoaRepository {
	/**
	 * Método responsável por verificar se os dados de uma pessoa representa uma pessoa física
	 * @param  PessoaFisica|PessoaJuridica 			$obPessoa 			Dados da pessoa
	 * @return bool
	 */
	private static function objetoRepresentaPessoaFisica(PessoaInterface $obPessoa): bool {
		return $obPessoa instanceof PessoaFisica;
	}

	/**
	 * Método responsável por realizar o cadastro de uma nova pessoa
	 * @return int
	 */
	private static function novaPessoa(): int {
	 return DB::table(Pessoa::NOME_TABELA)->insertGetId([]);
	}

	/**
	 * Método responsável por remover os dados de vínculo de pessoa
	 * @param  int 			$id 			ID da pessoa a ser removida
	 * @return bool
	 */
	private static function removerPessoa(int $id): bool {
		return DB::table(Pessoa::NOME_TABELA)->delete($id) > 0;
	}

	/**
	 * Método responsável por cadastrar os dados de uma pessoa
	 * @param  PessoaFisica|PessoaJuridica 			$obPessoa 			Dados da pessoa a ser cadastrada
	 * @throws ActionRepositoryException
	 * @return Pessoa
	 */
	public static function cadastrar(PessoaInterface $obPessoa): PessoaInterface {
		$idPessoa 					= self::novaPessoa();
		$obPessoa->idPessoa = $idPessoa;

		// REALIZA O CADASTRO
		$objetoPf 		= self::objetoRepresentaPessoaFisica($obPessoa);
		$idPessoaTipo = $objetoPf ? PessoaFisicaRepository::cadastrar($obPessoa)
															: PessoaJuridicaRepository::cadastrar($obPessoa);

		// VERIFICA SE FOI POSSÍVEL CADASTRAR A NOVA PESSOA
		if($idPessoa <= 0) {
			throw new Exception('Não foi possível realizar o cadastro. Tente novamente mais tarde', 400);
		}

		// SALVA O ID DA NOVA PESSOA
		$obPessoa->id = $idPessoaTipo;
		return $obPessoa;
	}

	/**
	 * Método responsável por cadastrar os dados de uma pessoa
	 * @param  PessoaFisica|PessoaJuridica 			$obPessoa 			Dados da pessoa a ser cadastrada
	 * @return void
	 */
	public static function remover(PessoaInterface $obPessoa): bool {
		if(!is_numeric($obPessoa->idPessoa)) return false;

		// REMOVE OS DADOS DA PESSOA
		self::objetoRepresentaPessoaFisica($obPessoa) ? PessoaFisicaRepository::remover($obPessoa->idPessoa)
																									: PessoaJuridicaRepository::remover($obPessoa->idPessoa);

		// REMOVE OS DADOS DE VÍNCULO
		return self::removerPessoa($obPessoa->id);
	}

	/**
	 * Método responsável por buscar os dados de uma pessoa pelo seu ID
	 * @param  int 			$id 			ID da pessoa consultada
	 * @return Pessoa|PessoaFisica|PessoaJuridica
	 */
	public static function getPessoaPorId(int $id): object {
		$tabelaP  = Pessoa::NOME_TABELA;
		$tabelaPf = PessoaFisica::NOME_TABELA;
		$tabelaPj = PessoaJuridica::NOME_TABELA;

		// MONTA A QUERY
		$db = DB::table($tabelaP)->where("{$tabelaP}.id", "=", $id);

		// JOINS
		$db->leftJoin($tabelaPf, "{$tabelaPf}.id_pessoa", "=", "{$tabelaP}.id")
			 ->leftJoin($tabelaPj, "{$tabelaPj}.id_pessoa", "=", "{$tabelaP}.id");

		// CAMPOS RETORNADOS
		$campos = [
			"IFNULL({$tabelaPf}.id, {$tabelaPj}.id) as id",
			"{$tabelaP}.id as id_pessoa",
			"{$tabelaPf}.nome", "{$tabelaPf}.cpf",
			"{$tabelaPj}.razao_social", "{$tabelaPj}.nome_fantasia", "{$tabelaPj}.cnpj"
		];
		foreach($campos as $campo) $db->selectRaw($campo);

		// CONSULTA DOS DADOS
		$dados = $db->get()->first() ?? [];

		// REALIZA A CONVERSÃO DOS DADOS
		$obPessoa = new Pessoa;
		if(!empty($dados)) $obPessoa = isset($dados->cpf) ? new PessoaFisica: new PessoaJuridica;

		return (new Converter($obPessoa, arrayDb: $dados, validos: true))->arrayDbToObject();
	}
}
