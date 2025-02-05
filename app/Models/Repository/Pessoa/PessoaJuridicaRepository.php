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
	 * @param  int 			$idPessoa 			ID da pessoa que será removida
	 * @return bool
	 */
	public static function remover(int $idPessoa): bool {
		return DB::table(PessoaJuridica::NOME_TABELA)->delete($idPessoa) > 0;
	}

	/**
	 * Método responsável por verificar se o CNPJ já foi cadastrado
	 * @param  string 			$cpf 			CPF que será validado
	 * @return bool
	 */
	public static function verificarDuplicidadeCnpj(string $cpf): bool {
		return DB::table(PessoaJuridica::NOME_TABELA)->where('cnpj', '=', $cpf)->count('id') > 0;
	}
}
