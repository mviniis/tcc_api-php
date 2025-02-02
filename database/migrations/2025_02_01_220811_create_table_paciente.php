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
		Schema::create('paciente', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->unsignedBigInteger('id_pessoa')->index('id_pessoa');
			$table->enum('ativo', ['s', 'n'])->default('s')->index('ativo');

			$table->foreign('id_pessoa')->references('id')->on('pessoa');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('paciente');
	}
};
