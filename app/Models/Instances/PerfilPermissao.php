<?php

namespace App\Models\Instances;

use App\Core\Database\InstanceInterface;

/**
 * class PerfilPermissao
 *
 * Classe responsável por representar a tabela 'perfil_permissao'
 */
class PerfilPermissao implements InstanceInterface {
	public $id;
	public $idPerfil;
	public $idModulo;
	public $visualizar;
	public $criar;
	public $editar;
	public $remover;

	public const NOME_TABELA = 'perfil_permissao';

	/**
	 * Método responsável por retornar o mapeamento de campos de dados vindos do banco de dados [campoClasse => campo_tabela]
	 * @return array
	 */
	public function getSchema(): array {
		return [
			'id'    		 => 'id',
			'idPerfil'   => 'id_perfil',
			'idModulo' 	 => 'id_modulo',
			'visualizar' => 'visualizar',
			'criar'      => 'criar',
			'editar'     => 'editar',
			'remover'    => 'remover'
		];
	}
}
