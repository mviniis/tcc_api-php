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
		Schema::create('equipamento', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->macAddress('mac_address')->index('mac_address');
			$table->unsignedBigInteger('id_paciente')->index('id_paciente');
			$table->string('nome', 150)->index('nome');
			$table->enum('ativo', ['s', 'n'])->default('s')->index('ativo');
			$table->integer('distancia_maxima');
			$table->dateTime('data_hora_criacao')->index('data_hora_criacao')->useCurrent();

			$table->foreign('id_paciente')->references('id')->on('paciente');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('equipamento');
	}
};
