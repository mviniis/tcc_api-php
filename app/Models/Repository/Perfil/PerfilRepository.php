<?php

namespace App\Models\Repository\Perfil;

use App\Core\Database\Converter;
use App\Models\Instances\Perfil\Perfil;
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

	/**
	 * Método responsável por retornar os perfis disponíveis
	 * @param  array 			$campos 			Campos que serão retornados na requisição
	 * @return Perfil[]
	 */
	public function getPerfisDisponiveis(array $campos = ['*']): array {
		$perfis = DB::table(Perfil::NOME_TABELA)->where('ativo', '=', 's')->get($campos)->all();
		return (new Converter((new Perfil)))->listArrayDbToListObject($perfis);
	}

	/**
	 * Método responsável por realizar o cadastro completo de vários registros
	 * @param  array 			$perfis 			Perfis que serão cadastrados
	 * @return bool
	 */
	public function cadastroCompletoPerfis(array $perfis = []): bool {
		if(empty($perfis)) return false;

		// VALIDAÇÃO DOS DADOS
		$cadastro = [];
		foreach($perfis as $dado) {
			// VERIFICA SE A QUANTIDADE DE CAMPOS EXISTE
			if(count(array_values($dado)) < 4) continue;

			$cadastro[] = (new Converter((new Perfil), $dado))->arrayClassToArrayDb();
		}

		return DB::table(Perfil::NOME_TABELA)->insertOrIgnore($cadastro) > 0;
	}

	/**
	 * Método responsável por remover os perfis pelos seus IDs
	 * @param  int[] 			$idsPerfis 			IDs dos perfis que serão removidos
	 * @return bool
	 */
	public function removerPerifsPorIds(array $idsPerfis = []): bool {
		if(empty($idsPerfis)) return false;

		return DB::table(Perfil::NOME_TABELA)->whereIn('id', $idsPerfis)->delete() > 0;
	}
}
