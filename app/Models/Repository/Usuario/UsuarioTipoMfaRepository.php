<?php

namespace App\Models\Repository\Usuario;

use App\Models\Instances\Usuario\UsuarioTipoMfa;
use Illuminate\Support\Facades\DB;

/**
 * class UsuarioTipoMfaRepository
 *
 * Classe responsável por realizar as ações de manipulação da tabela 'usuario_tipo_mfa'
 */
class UsuarioTipoMfaRepository {
	/**
	 * Método responsável por realizar a remoção das configurações de MFA de um usuário
	 * @param  int 			$idUsuario 			ID do usuário vinculado a ou as MFAs
	 * @return bool
	 */
	public static function limparConfiguracoesMfaPorUsuario(int $idUsuario): bool {
		return DB::table(UsuarioTipoMfa::NOME_TABELA)->where('id_usuario', '=', $idUsuario)->delete();
	}
}
