<?php

namespace App\Models\Repository\Pessoa;

use App\Core\Database\Converter;
use Illuminate\Support\Facades\DB;
use App\Models\Instances\Pessoa\PessoaJuridica;

/**
 * class PessoaJuridicaRepository
 *
 * Classe responsável por manipular os dados da tabela 'pessoa_juridica'
 */
class PessoaJuridicaRepository {
	/**
	 * Método responsável por cadastrar uma pessoa física
	 * @param  PessoaJuridica 			$obPessoa 			Dados da pessoa
	 * @return int
	 */
	public static function cadastrar(PessoaJuridica $obPessoa): int {
		$dados = (new Converter($obPessoa))->objectToArrayDb();
		return DB::table(PessoaJuridica::NOME_TABELA)->insertGetId($dados);
	}

	/**
	 * Método responsável por remover uma pessoa física
	 * @param  int 			$id 			ID da pessoa que será removida
	 * @return bool
	 */
	public static function remover(int $id): bool {
		return DB::table(PessoaJuridica::NOME_TABELA)->delete($id) > 0;
	}

	/**
	 * Método responsável por verificar se o CNPJ já foi cadastrado
	 * @param  string 			$cnpj 					CNPJ que será validado
	 * @param  int          $idPessoa 			ID da pessoa que será verificada
	 * @return bool
	 */
	public static function verificarDuplicidadeCnpj(string $cnpj, ?int $idPessoa = null): bool {
		$db = DB::table(PessoaJuridica::NOME_TABELA)->where('cnpj', '=', $cnpj);
		if(!is_null($idPessoa)) $db->where('id_pessoa', '!=', $idPessoa);
		return $db->exists();
	}

	/**
	 * Método responsável por realizar a atualização de uma pessoa jurídica
	 * @param  PessoaJuridica 			$obPessoa 			Dados da pessoa que serão atualizados
	 * @return bool
	 */
	public static function atualizar(PessoaJuridica $obPessoa): bool {
		if(!is_numeric($obPessoa->id)) return false;

		// CONVERSÃO PARA OS DADOS DE INSERÇÃO
		$dados = (new Converter($obPessoa))->objectToArrayDb();

		// EVITA DE ATUALIZAR OS IDS
		unset($dados['id'], $dados['id_pessoa']);

		// EVITA DE SALVAR CAMPOS COM NULO OU VAZIOS
		foreach($dados as $campo => $valor) {
			if(!is_null($valor) && strlen($valor)) continue;

			unset($dados[$campo]);
		}

		$id = $obPessoa->id;
		return DB::table(PessoaJuridica::NOME_TABELA)->where('id', '=', $id)->update($dados) > 0;
	}
}
