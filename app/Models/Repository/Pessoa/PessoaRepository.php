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
	public static function removerPessoa(int $id): bool {
		return DB::table(Pessoa::NOME_TABELA)->delete($id) > 0;
	}

	/**
	 * Método responsável por cadastrar os dados de uma pessoa
	 * @param  PessoaFisica|PessoaJuridica 			$obPessoa 			Dados da pessoa a ser cadastrada
	 * @param  bool                             $vincular       Define se deve criar o vínculo de pessoa
	 * @throws ActionRepositoryException
	 * @return Pessoa
	 */
	public static function cadastrar(PessoaInterface &$obPessoa, bool $vincular = true): PessoaInterface {
		$idPessoa = $vincular ? self::novaPessoa(): $obPessoa->idPessoa;
		if($vincular) $obPessoa->idPessoa = $idPessoa;

		// VERIFICA SE FOI POSSÍVEL CADASTRAR A NOVA PESSOA
		if($idPessoa <= 0) {
			throw new Exception('Não foi possível realizar o cadastro. Tente novamente mais tarde', 400);
		}

		// REALIZA O CADASTRO
		$objetoPf 		= self::objetoRepresentaPessoaFisica($obPessoa);
		$idPessoaTipo = $objetoPf ? PessoaFisicaRepository::cadastrar($obPessoa)
															: PessoaJuridicaRepository::cadastrar($obPessoa);

		// SALVA O ID DA NOVA PESSOA
		$obPessoa->id = $idPessoaTipo;
		return $obPessoa;
	}

	/**
	 * Método responsável por atualizar os dados de uma pessoa
	 * @param  PessoaFisica|PessoaJuridica 			$obPessoaEnviada 			Dados enviados na requisição de atualização
	 * @return PessoaFisica|PessoaJuridica
	 */
	public static function atualizar(PessoaInterface $obPessoaEnviada) {
		$obPessoaCadastrada = self::getPessoaPorId($obPessoaEnviada->idPessoa);

		// VALIDA QUAL O TIPO DE MANIPULAÇÃO DOS DADOS
		$pessoaFisicaEnviada     = self::objetoRepresentaPessoaFisica($obPessoaEnviada);
		$pessoaFisicaCadastrada  = self::objetoRepresentaPessoaFisica($obPessoaCadastrada);
		$atualizarCadastroPessoa = $pessoaFisicaEnviada === $pessoaFisicaCadastrada;

		// REALIZA A ATUALIZAÇÃO DOS DADOS DA PESSOA
		if($atualizarCadastroPessoa) {
			$obPessoaEnviada->id = $obPessoaCadastrada->id;
			self::atualizarPorTipo($obPessoaEnviada);
			return $obPessoaEnviada;
		}

		// REMOVE O CADASTRO DE TIPO DE PESSOA E CRIA O NOVO TIPO
		self::remover($obPessoaCadastrada, false);
		return self::cadastrar($obPessoaEnviada, false);
	}

	/**
	 * Método responsável por realizar a chamada da atualização das pessoas
	 * @param  PessoaInterface 			$obPessoa 			Dados da pessoa a ser atualizada
	 * @return bool
	 */
	private static function atualizarPorTipo(PessoaInterface $obPessoa) {
		return self::objetoRepresentaPessoaFisica($obPessoa) ? PessoaFisicaRepository::atualizar($obPessoa)
																												 : PessoaJuridicaRepository::atualizar($obPessoa);
	}

	/**
	 * Método responsável por cadastrar os dados de uma pessoa
	 * @param  PessoaFisica|PessoaJuridica 			$obPessoa 			  Dados da pessoa a ser cadastrada
	 * @param  bool 														$desvincular 			Define se irá desfazer o vínculo de pessoa
	 * @return bool
	 */
	public static function remover(PessoaInterface $obPessoa, bool $desvincular = true): bool {
		if(!is_numeric($obPessoa->id)) return false;

		// REMOVE OS DADOS DA PESSOA
		self::objetoRepresentaPessoaFisica($obPessoa) ? PessoaFisicaRepository::remover($obPessoa->id)
																									: PessoaJuridicaRepository::remover($obPessoa->id);

		// REMOVE OS DADOS DE VÍNCULO
		return $desvincular ? self::removerPessoa($obPessoa->idPessoa): true;
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
