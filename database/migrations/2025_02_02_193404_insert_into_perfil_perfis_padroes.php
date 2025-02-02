<?php

use App\Exceptions\MigrationDmlException;
use App\Models\Repository\PerfilRepository;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 */
	public function up(): void {
		if(!(new PerfilRepository)->cadastroCompletoPerfis([
			[ 'id' => 1, 'nome' => 'Administrador', 'ativo' => 's', 'permitir_remocao' => 'n' ],
			[ 'id' => 2, 'nome' => 'Responsável', 	'ativo' => 's', 'permitir_remocao' => 'n' ],
			[ 'id' => 3, 'nome' => 'Cuidador',    	'ativo' => 's', 'permitir_remocao' => 'n' ]
		])) {
			throw new MigrationDmlException('Não foi possível cadastrar os perfis padrões!');
		}
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void {
		(new PerfilRepository)->removerPerifsPorIds([1, 2, 3]);
	}
};
