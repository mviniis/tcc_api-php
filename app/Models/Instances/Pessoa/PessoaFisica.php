<?php

namespace App\Models\Instances\Pessoa;

use App\Core\Database\InstanceInterface;

/**
 * class PessoaFisica
 *
 * Classe responsável por representar a tabela 'pessoa_fisica'
 */
class PessoaFisica implements InstanceInterface, PessoaInterface {
	public $id;
	public $idPessoa;
	public $nome;
	public $cpf;

	public const NOME_TABELA = 'pessoa_fisica';

	/**
	 * Método responsável por retornar o mapeamento de campos de dados vindos do banco de dados [campoClasse => campo_tabela]
	 * @return array
	 */
	public function getSchema(): array {
		return [
			'id'    	 => 'id',
			'idPessoa' => 'id_pessoa',
			'nome' 	   => 'nome',
			'cpf'      => 'cpf'
		];
	}
}
