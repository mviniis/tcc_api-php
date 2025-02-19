<?php

namespace App\Models\Repository\Usuario;

use Illuminate\Support\Facades\DB;
use App\Models\Instances\Usuario\UsuarioResponsavel;

/**
 * class UsuarioResponsavelRepository
 *
 * Classe responsável por manipular os dados da tabela 'usuario_responsavel'
 */
abstract class UsuarioResponsavelRepository {
	/**
	 * Método responsável por verificar se um usário possui algum outro usuário sobre sua tutela
	 * @param  int 			$idUsuario 			ID do usuário que será validado
	 * @return bool
	 */
	public static function usuarioPossuiUsuariosVinculados(int $idUsuario): bool {
		return DB::table(UsuarioResponsavel::NOME_TABELA)->where('id_usuario_pai', '=', $idUsuario)->exists();
	}

	/**
	 * Método responsável por realizar a remoção de um usuário de outros usuários
	 * @param  int 			$idUsuarioFilho 			ID do usuário que será desvinculado
	 * @return bool
	 */
	public static function desvincularDeUsuariosPai(int $idUsuarioFilho): bool {
		return DB::table(UsuarioResponsavel::NOME_TABELA)
						 ->where('id_usuario_filho', '=', $idUsuarioFilho)
						 ->delete();
	}
}
