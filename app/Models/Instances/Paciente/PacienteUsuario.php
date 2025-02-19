<?php

namespace App\Models\Instances\Paciente;

use App\Core\Database\InstanceInterface;

/**
 * class PacienteUsuario
 *
 * Classe responsável por representar a tabela 'paciente_usuario'
 */
class PacienteUsuario implements InstanceInterface {
	public $idUsuario;
	public $idPaciente;

	public const NOME_TABELA = 'paciente_usuario';

	/**
	 * Método responsável por retornar o mapeamento de campos de dados vindos do banco de dados [campoClasse => campo_tabela]
	 * @return array
	 */
	public function getSchema(): array {
		return [
			'idUsuario'  => 'id_usuario',
			'idPaciente' => 'id_paciente',
		];
	}
}
