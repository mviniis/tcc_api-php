<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('perfil_permissao', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->unsignedBigInteger('id_perfil')->index('id_perfil');
			$table->unsignedBigInteger('id_modulo')->index('id_modulo');
			$table->enum('visualizar', ['s', 'n'])->default('n')->index('visualizar');
			$table->enum('criar', ['s', 'n'])->default('n')->index('criar');
			$table->enum('editar', ['s', 'n'])->default('n')->index('editar');
			$table->enum('remover', ['s', 'n'])->default('n')->index('remover');

			$table->foreign('id_perfil')->references('id')->on('perfil');
			$table->foreign('id_modulo')->references('id')->on('modulo');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('perfil_permissao');
	}
};
