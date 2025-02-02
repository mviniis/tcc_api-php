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
		Schema::create('modulo', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->bigInteger('id_pai')->index('id_pai');
			$table->string('nome', 255);
			$table->string('path', 100);
			$table->string('icone', 100);
			$table->enum('ativo', ['s', 'n'])->default('s')->index('ativo');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('modulo');
	}
};
