<?php

namespace App\Exceptions;

use Exception;

/**
 * class ApiValidationException
 *
 * Classe responsável por lançar uma excessão de erro ao executar a validação de uma requisição de API
 */
class ApiValidationException extends Exception {
	/**
	 * Construtor da classe de exceção
	 * @param string 			$message 			Mensagem de exceção
	 * @param array  			$details			Detalhes da exceção
	 */
	public function __construct(string $message = '', private array $details = []) {
		parent::__construct($message, 400);
	}

	public function render() {
		$response = [
			'titulo' => "Requisição inválida!",
			'mensagem' => $this->getMessage()
		];

		if(!empty($this->details)) $response['details'] = $this->details;

		return response()->json($response, $this->getCode());
	}
}
