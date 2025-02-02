<?php

namespace App\Models\Instances;

use App\Core\Database\InstanceInterface;

/**
 * class Modulo
 *
 * Classe responsável por representar a tabela 'modulo'
 */
class Modulo implements InstanceInterface {
	public $id;
	public $idPai;
	public $nome;
	public $path;
	public $icone;
	public $ativo;

	public const NOME_TABELA = 'modulo';

	/**
	 * Método responsável por retornar o mapeamento de campos de dados vindos do banco de dados [campoClasse => campo_tabela]
	 * @return array
	 */
	public function getSchema(): array {
		return [
			'id'    => 'id',
			'idPai' => 'id_pai',
			'nome'  => 'nome',
			'path'  => 'path',
			'icone' => 'icone',
			'ativo' => 'ativo',
		];
	}
}
