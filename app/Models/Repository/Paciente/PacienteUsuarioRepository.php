<?php

namespace App\Models\Repository\Paciente;

use Illuminate\Support\Facades\DB;
use App\Models\Instances\Paciente\PacienteUsuario;

/**
 * class PacienteUsuarioRepository
 *
 * Classe responsável por realizar as manipulações de banco da tabela 'paciente_usuario'
 */
abstract class PacienteUsuarioRepository {
	/**
	 * Método responsável por verificar se um usuário possui vinculo com algum paciente
	 * @param  int 			$idUsuario 			ID do usuário
	 * @return bool
	 */
	public static function usuarioPossuiVinculosComPacientes(int $idUsuario): bool {
		return DB::table(PacienteUsuario::NOME_TABELA)->where('id_usuario', '=', $idUsuario)->exists();
	}
}
