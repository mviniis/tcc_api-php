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
		Schema::create('perfil', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('nome', 255)->index('nome');
			$table->enum('ativo', ['s', 'n'])->default('s')->index('ativo');
			$table->enum('permitir_remocao', ['s', 'n'])->default('s')->index('permitir_remocao');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('perfil');
	}
};
