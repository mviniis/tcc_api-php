<?php

namespace App\Models\Repository\Pessoa;

use App\Core\Database\Converter;
use Illuminate\Support\Facades\DB;
use App\Models\Instances\Pessoa\PessoaFisica;

/**
 * class PessoaFisicaRepository
 *
 * Classe responsável por manipular os dados da tabela 'pessoa_fisica'
 */
class PessoaFisicaRepository {
	/**
	 * Método responsável por cadastrar uma pessoa física
	 * @param  PessoaFisica 			$obPessoa 			Dados da pessoa
	 * @return int
	 */
	public static function cadastrar(PessoaFisica $obPessoa): int {
		$dados = (new Converter($obPessoa))->objectToArrayDb();
		return DB::table(PessoaFisica::NOME_TABELA)->insertGetId($dados);
	}

	/**
	 * Método responsável por remover uma pessoa física
	 * @param  int 			$idPessoa 			ID da pessoa que será removida
	 * @return bool
	 */
	public static function remover(int $idPessoa): bool {
		return DB::table(PessoaFisica::NOME_TABELA)->delete($idPessoa) > 0;
	}

	/**
	 * Método responsável por verificar se o CPF já foi cadastrado
	 * @param  string 			$cpf 			CPF que será validado
	 * @return bool
	 */
	public static function verificarDuplicidadeCpf(string $cpf): bool {
		return DB::table(PessoaFisica::NOME_TABELA)->where('cpf', '=', $cpf)->count('id') > 0;
	}
}
