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
		Schema::create('usuario', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('email', 255)->unique('email');
			$table->text('senha')->fulltext('senha');
			$table->string('icone', 50);
			$table->unsignedBigInteger('id_pessoa')->index('id_pessoa');
			$table->unsignedBigInteger('id_perfil')->index('id_perfil');
			$table->enum('ativo', ['s', 'n'])->default('s')->index('ativo');
			$table->dateTime('data_hora_criacao')->index('data_hora_criacao')->useCurrent();

			$table->foreign('id_pessoa')->references('id')->on('pessoa');
			$table->foreign('id_perfil')->references('id')->on('perfil');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('usuario');
	}
};
