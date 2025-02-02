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
		Schema::create('usuario_tipo_mfa', function (Blueprint $table) {
			$table->unsignedBigInteger('id_usuario');
			$table->unsignedInteger('id_tipo_mfa');
			$table->string('codigo_verificacao', 50)->index('codigo_verificacao');
			$table->dateTime('data_hora_expirar')->index('data_hora_expirar');

			$table->foreign('id_usuario')->references('id')->on('usuario');
			$table->foreign('id_tipo_mfa')->references('id')->on('tipo_mfa');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('usuario_tipo_mfa');
	}
};
