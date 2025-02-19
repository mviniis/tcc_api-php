<?php

namespace App\Models\Instances\Usuario;

use App\Core\Database\InstanceInterface;

/**
 * class UsuarioResponsavel
 *
 * Classe responsável por representar a tabela 'usuario_responsavel'
 */
class UsuarioResponsavel implements InstanceInterface {
	public $idUsuarioPai;
	public $idUsuarioFilho;

	public const NOME_TABELA = 'usuario_responsavel';

	/**
	 * Método responsável por retornar o mapeamento de campos de dados vindos do banco de dados [campoClasse => campo_tabela]
	 * @return array
	 */
	public function getSchema(): array {
		return [
			'idUsuarioPai'   => 'id_usuario_pai',
			'idUsuarioFilho' => 'id_usuario_filho',
		];
	}
}
