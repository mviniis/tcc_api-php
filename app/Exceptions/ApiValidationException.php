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
	 * @param int    			$code		    	Código HTTP de erro
	 */
	public function __construct(string $message = '', private array $details = [], int $code = 400) {
		parent::__construct($message, $code);
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
