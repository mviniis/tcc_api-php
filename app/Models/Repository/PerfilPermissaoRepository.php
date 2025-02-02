<?php

namespace App\Models\Repository;

use App\Core\Database\Converter;
use App\Models\Instances\PerfilPermissao;
use Illuminate\Support\Facades\DB;

/**
 * class PerfilPermissaoRepository
 *
 * Classe responsável por centralizar as manipulaçãoes de banco de dados da tabela 'perfil_permissao'
 */
class PerfilPermissaoRepository {
	/**
	 * Método responsável por realizar o cadastro completo de vários registros
	 * @param  array 			$permissoes 			Permissões que serão cadastradas
	 * @return bool
	 */
	public function cadastroCompletoPermissoesPerfis(array $permissoes = []): bool {
		if(empty($permissoes)) return false;

		// VALIDAÇÃO DOS DADOS
		$cadastro = [];
		foreach($permissoes as $dado) {
			// VERIFICA SE A QUANTIDADE DE CAMPOS EXISTE (COM EXCESSÃO DO ID)
			if(count(array_values($dado)) < 6) continue;

			$cadastro[] = (new Converter((new PerfilPermissao), $dado))->arrayClassToArrayDb();
		}

		return DB::table(PerfilPermissao::NOME_TABELA)->insertOrIgnore($cadastro) > 0;
	}

	/**
	 * Método responsável por remover as permissões de perfis pelos IDs dos perfis
	 * @param  int[] 			$idsPerfis 			IDs dos perfis que terão as permissões removidas
	 * @return bool
	 */
	public function removerPermissoesPerifsPorIdsPerfis(array $idsPerfis = []): bool {
		if(empty($idsPerfis)) return false;

		return DB::table(PerfilPermissao::NOME_TABELA)->whereIn('id_perfil', $idsPerfis)->delete() > 0;
	}
}
