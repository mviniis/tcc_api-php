<?php

namespace App\Models\Instances\Plano;

use App\Core\Database\InstanceInterface;

/**
 * class PlanoUsuario
 *
 * Classe responsável por representar a tabela 'plano_usuario'
 */
class PlanoUsuario implements InstanceInterface {
	public $idUsuario;
	public $idPlano;

	public const NOME_TABELA = 'plano_usuario';

	/**
	 * Método responsável por retornar o mapeamento de campos de dados vindos do banco de dados [campoClasse => campo_tabela]
	 * @return array
	 */
	public function getSchema(): array {
		return [
			'idUsuario' => 'id_usuario',
			'idPlano'   => 'id_plano',
		];
	}
}
