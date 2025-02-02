<?php

namespace App\Models\Repository;

use App\Core\Database\Converter;
use App\Models\Instances\Modulo;
use Illuminate\Support\Facades\DB;

/**
 * class ModuloRepository
 *
 * Classe responsável por centralizar as manipulaçãoes de banco de dados da tabela 'modulo'
 */
class ModuloRepository {
	/**
	 * Método responsável por realizar o cadastro completo de vários registros
	 * @param  array 			$modulos 			Módulos que serão cadastrados
	 * @return bool
	 */
	public function cadastroCompletoModulos(array $modulos = []): bool {
		if(empty($modulos)) return false;

		// VALIDAÇÃO DOS DADOS
		$cadastro = [];
		foreach($modulos as $dado) {
			// VERIFICA SE A QUANTIDADE DE CAMPOS EXISTE
			if(count(array_values($dado)) < 6) continue;

			$cadastro[] = (new Converter((new Modulo), $dado))->arrayClassToArrayDb();
		}

		return DB::table(Modulo::NOME_TABELA)->insertOrIgnore($cadastro) > 0;
	}

	/**
	 * Método responsável por remover os módulos pelos seus IDs
	 * @param  int[] 			$idsModulos 			IDs dos módulos que serão removidos
	 * @return bool
	 */
	public function removerModulosPorIds(array $idsModulos = []): bool {
		if(empty($idsModulos)) return false;

		return DB::table(Modulo::NOME_TABELA)->whereIn('id', $idsModulos)->delete() > 0;
	}
}
