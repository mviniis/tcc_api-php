<?php

namespace App\Models\Instances\Pessoa;

use App\Core\Database\InstanceInterface;

/**
 * class PessoaJuridica
 *
 * Classe responsável por representar a tabela 'pessoa_juridica'
 */
class PessoaJuridica implements InstanceInterface, PessoaInterface {
	public $id;
	public $idPessoa;
	public $razaoSocial;
	public $nomeFantasia;
	public $cnpj;

	public const NOME_TABELA = 'pessoa_juridica';

	/**
	 * Método responsável por retornar o mapeamento de campos de dados vindos do banco de dados [campoClasse => campo_tabela]
	 * @return array
	 */
	public function getSchema(): array {
		return [
			'id'    	     => 'id',
			'idPessoa'     => 'id_pessoa',
			'razaoSocial'  => 'razao_social',
			'nomeFantasia' => 'nome_fantasia',
			'cnpj'         => 'cnpj'
		];
	}
}
