<?php

namespace App\Models\Repository;

use App\Core\Database\Converter;
use App\Models\Instances\Perfil;
use Illuminate\Support\Facades\DB;

/**
 * class PerfilRepository
 *
 * Classe responsável por centralizar as manipulaçãoes de banco de dados da tabela 'perfil'
 */
class PerfilRepository {
	/**
	 * Método responsável por buscar um perfil pelo seu ID
	 * @param  int 			$id 			ID do perfil
	 * @return Perfil
	 */
	public function getPerfilById(int $id): Perfil {
		$perfil = (DB::table(Perfil::NOME_TABELA)->where('id', $id)->first()) ?? [];

		return (new Converter((new Perfil), arrayDb: $perfil))->arrayDbToObject();
	}
}
