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
		Schema::create('coordenadas', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->unsignedBigInteger('id_paciente')->index('id_paciente');
			$table->string('longitude', 100)->index('longitude');
			$table->string('latitude', 100)->index('latitude');
			$table->dateTime('data_hora_criacao')->index('data_hora_criacao');

			$table->foreign('id_paciente')->references('id')->on('paciente');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('coordenadas');
	}
};
