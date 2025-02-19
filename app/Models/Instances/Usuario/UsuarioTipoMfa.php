<?php

namespace App\Models\Instances\Usuario;

use App\Core\Database\InstanceInterface;

/**
 * class UsuarioTipoMfa
 *
 * Classe responsável por representar a tabela 'usuario_tipo_mfa'
 */
class UsuarioTipoMfa implements InstanceInterface {
	public $idUsuario;
	public $idTipoMfa;
	public $codigoVerificacao;
	public $dataHoraExpirar;

	public const NOME_TABELA = 'usuario_tipo_mfa';

	/**
	 * Método responsável por retornar o mapeamento de campos de dados vindos do banco de dados [campoClasse => campo_tabela]
	 * @return array
	 */
	public function getSchema(): array {
		return [
			'idUsuario'         => 'id_usuario',
			'idTipoMfa'         => 'id_tipo_mfa',
			'codigoVerificacao' => 'codigo_verificacao',
			'dataHoraExpirar'   => 'data_hora_expirar',
		];
	}
}
