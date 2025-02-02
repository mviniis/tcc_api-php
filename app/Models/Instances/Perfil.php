<?php

namespace App\Models\Instances;

use App\Core\Database\InstanceInterface;

/**
 * class Perfil
 *
 * Classe responsável por representar a tabela 'perfil'
 */
class Perfil implements InstanceInterface {
	public $id;
	public $nome;
	public $ativo;
	public $permitirRemocao;

	public const NOME_TABELA = 'usuario';

	/**
	 * Método responsável por retornar o mapeamento de campos de dados vindos do banco de dados [campoClasse => campo_tabela]
	 * @return array
	 */
	public function getSchema(): array {
		return [
			'id'    					=> 'id',
			'nome'  					=> 'nome',
			'ativo' 					=> 'ativo',
			'permitirRemocao' => 'permitir_remocao'
		];
	}
}
