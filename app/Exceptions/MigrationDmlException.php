<?php

namespace App\Exceptions;

use Exception;

/**
 * class MigrationDmlExceptio
 *
 * Classe responsável por lançar uma excessão de erro ao executar uma migration DML (manipulação de dados)
 */
class MigrationDmlException extends Exception {
	public function __construct(string $message = '') {
		parent::__construct($message, 500);
	}
}
