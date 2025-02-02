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
		Schema::create('tipo_mfa', function (Blueprint $table) {
			$table->increments('id');
			$table->string('nome', 100);
			$table->text('descricao');
			$table->enum('disponivel', ['s', 'n'])->default('s')->index('disponivel');
			$table->string('classe_implementacao', 150);
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('tipo_mfa');
	}
};
