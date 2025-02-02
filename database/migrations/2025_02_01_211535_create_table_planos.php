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
		Schema::create('planos', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedBigInteger('id_perfil')->index('id_perfil');
			$table->float('preco', 2);
			$table->enum('disponivel', ['s', 'n'])->default('s')->index('disponivel');

			$table->foreign('id_perfil')->references('id')->on('perfil');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('planos');
	}
};
