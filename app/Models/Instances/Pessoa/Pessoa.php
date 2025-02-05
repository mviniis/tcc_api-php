<?php

namespace App\Models\Instances\Pessoa;

use App\Core\Database\InstanceInterface;

/**
 * class Pessoa
 *
 * Classe responsável por representar um objeto de pessoa
 */
class Pessoa implements InstanceInterface, PessoaInterface {
	public $id;

	public const NOME_TABELA = 'pessoa';

	/**
	 * Método responsável por retornar o mapeamento de campos de dados vindos do banco de dados [campoClasse => campo_tabela]
	 * @return array
	 */
	public function getSchema(): array {
		return [
			'id' => 'id',
		];
	}
}
