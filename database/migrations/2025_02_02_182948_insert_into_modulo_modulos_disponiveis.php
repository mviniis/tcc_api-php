<?php

use App\Exceptions\MigrationDmlException;
use App\Models\Repository\ModuloRepository;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 */
	public function up(): void {
		if(!(new ModuloRepository)->cadastroCompletoModulos([
			[ 'id' => 1, 'idPai' => 0, 'nome' => 'Usuários', 					 'path' => 'usuarios', 				'icone' => '', 'ativo' => 's' ],
			[ 'id' => 2, 'idPai' => 0, 'nome' => 'Perfis de usuários', 'path' => 'perfis-usuarios', 'icone' => '', 'ativo' => 's' ],
			[ 'id' => 3, 'idPai' => 0, 'nome' => 'Cuidadores', 				 'path' => 'cuidadores', 			'icone' => '', 'ativo' => 's' ],
			[ 'id' => 4, 'idPai' => 0, 'nome' => 'Pacientes', 				 'path' => 'pacientes', 			'icone' => '', 'ativo' => 's' ],
			[ 'id' => 5, 'idPai' => 0, 'nome' => 'Equipamentos', 			 'path' => 'equipamentos', 		'icone' => '', 'ativo' => 's' ]
		])) {
			throw new MigrationDmlException('Não foi possível cadastrar os módulos!');
		}
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void {
		(new ModuloRepository)->removerModulosPorIds([1, 2, 3, 4, 5]);
	}
};
