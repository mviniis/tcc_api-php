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
		Schema::create('usuario_responsavel', function (Blueprint $table) {
			$table->unsignedBigInteger('id_usuario_pai');
			$table->unsignedBigInteger('id_usuario_filho');

			$table->primary(['id_usuario_pai', 'id_usuario_filho'], 'id_usuario_responsavel');
			$table->foreign('id_usuario_pai')->references('id')->on('usuario');
			$table->foreign('id_usuario_filho')->references('id')->on('usuario');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('usuario_responsavel');
	}
};
