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
		Schema::create('api_auth', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->unsignedBigInteger('id_usuario')->index('id_usuario');
			$table->text('token')->fulltext('token');
			$table->dateTime('data_hora_criacao')->index('data_hora_criacao');
			$table->dateTime('data_hora_vencimento')->index('data_hora_vencimento');

			$table->foreign('id_usuario')->references('id')->on('usuario');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('api_auth');
	}
};
