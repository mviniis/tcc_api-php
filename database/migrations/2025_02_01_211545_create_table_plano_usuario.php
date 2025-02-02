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
		Schema::create('plano_usuario', function (Blueprint $table) {
			$table->unsignedBigInteger('id_usuario');
			$table->unsignedInteger('id_plano');

			$table->primary(['id_usuario', 'id_plano'], 'id_usuario_plano');
			$table->foreign('id_usuario')->references('id')->on('usuario');
			$table->foreign('id_plano')->references('id')->on('planos');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('plano_usuario');
	}
};
