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
		Schema::create('configuracao', function (Blueprint $table) {
			$table->string('hash', 200)->primary(true)->autoIncrement();
			$table->unsignedInteger('id_tipo')->index('id_tipo');
			$table->string('titulo', 100);
			$table->string('valor', 255);
			$table->string('padrao', 255);
			$table->string('descricao', 255);

			$table->foreign('id_tipo')->references('id')->on('tipo_configuracao');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('configuracao');
	}
};
