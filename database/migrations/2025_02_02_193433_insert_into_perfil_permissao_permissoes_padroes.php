<?php

use App\Exceptions\MigrationDmlException;
use Illuminate\Database\Migrations\Migration;
use App\Models\Repository\PerfilPermissaoRepository;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 */
	public function up(): void {
		if(!(new PerfilPermissaoRepository)->cadastroCompletoPermissoesPerfis([
			[ 'id_perfil' => 1, 'id_modulo' => 1, 'visualizar' => 's', 'criar' => 's', 'editar' => 's', 'remover' => 's' ],
			[ 'id_perfil' => 1, 'id_modulo' => 2, 'visualizar' => 's', 'criar' => 's', 'editar' => 's', 'remover' => 's' ],
			[ 'id_perfil' => 1, 'id_modulo' => 3, 'visualizar' => 's', 'criar' => 's', 'editar' => 's', 'remover' => 's' ],
			[ 'id_perfil' => 1, 'id_modulo' => 4, 'visualizar' => 's', 'criar' => 's', 'editar' => 's', 'remover' => 's' ],
			[ 'id_perfil' => 1, 'id_modulo' => 5, 'visualizar' => 's', 'criar' => 's', 'editar' => 's', 'remover' => 's' ],
			[ 'id_perfil' => 2, 'id_modulo' => 3, 'visualizar' => 's', 'criar' => 's', 'editar' => 's', 'remover' => 's' ],
			[ 'id_perfil' => 2, 'id_modulo' => 4, 'visualizar' => 's', 'criar' => 's', 'editar' => 's', 'remover' => 's' ],
			[ 'id_perfil' => 3, 'id_modulo' => 4, 'visualizar' => 's', 'criar' => 'n', 'editar' => 'n', 'remover' => 'n' ],
		])) {
			throw new MigrationDmlException('Não foi possível cadastrar as permissões de acesso dos perfis padrões!');
		}
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void {
		(new PerfilPermissaoRepository)->removerPermissoesPerifsPorIdsPerfis([1, 2, 3]);
	}
};
