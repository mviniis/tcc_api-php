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
		Schema::create('topicos', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('nome', 150);
			$table->string('path', 255);
			$table->unsignedInteger('id_tipo')->index('id_tipo');
			$table->enum('ativo', ['s', 'n'])->default('s')->index('ativo');
			$table->enum('bloqueado', ['s', 'n'])->default('n')->index('bloqueado');

			$table->foreign('id_tipo')->references('id')->on('tipo_topico');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('topicos');
	}
};
