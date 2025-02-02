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
		Schema::create('pessoa_juridica', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->unsignedBigInteger('id_pessoa')->index('id_pessoa');
			$table->string('razao_social', 255);
			$table->string('nome_fantasia', 255);
			$table->string('cnpj', 11)->index('cnpj');

			$table->foreign('id_pessoa')->references('id')->on('pessoa');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('pessoa_juridica');
	}
};
