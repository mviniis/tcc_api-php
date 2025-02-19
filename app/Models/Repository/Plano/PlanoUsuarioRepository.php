<?php

namespace App\Models\Repository\Plano;

use App\Models\Instances\Plano\PlanoUsuario;
use Illuminate\Support\Facades\DB;

/**
 * class PlanoUsuarioRepository
 *
 * Classe responsável por centralizar as manipulaçãoes de banco de dados da tabela 'plano_usuario'
 */
class PlanoUsuarioRepository {
	/**
	 * Método responsável por realizar a remoção do vínculo de um usuário com um plano
	 * @param  int 			$idUsuario 			ID do usuário
	 * @return bool
	 */
	public static function removerVinculoComUsuario(int $idUsuario): bool {
		return DB::table(PlanoUsuario::NOME_TABELA)->where('id_usuario', '=', $idUsuario)->delete();
	}
}
