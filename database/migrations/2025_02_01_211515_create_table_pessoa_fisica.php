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
		Schema::create('pessoa_fisica', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->unsignedBigInteger('id_pessoa')->index('id_pessoa');
			$table->string('nome', 255)->index('nome');
			$table->string('cpf', 11)->index('cpf');

			$table->foreign('id_pessoa')->references('id')->on('pessoa');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('pessoa_fisica');
	}
};
