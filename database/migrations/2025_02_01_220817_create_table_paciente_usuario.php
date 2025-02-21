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
		Schema::create('paciente_usuario', function (Blueprint $table) {
			$table->unsignedBigInteger('id_usuario');
			$table->unsignedBigInteger('id_paciente');

			$table->primary(['id_usuario', 'id_paciente'], 'id_paciente_usuario');
			$table->foreign('id_usuario')->references('id')->on('usuario');
			$table->foreign('id_paciente')->references('id')->on('paciente');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('paciente_usuario');
	}
};
