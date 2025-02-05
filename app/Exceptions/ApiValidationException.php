<?php

namespace App\Exceptions;

use App\Core\Api\ResponseApi;
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
		$detalhes = [
			'motivo' => $this->getMessage()
		];

		if(!empty($this->details)) $detalhes['infos'] = $this->details;

		return ResponseApi::render(
			mensagem: 'Requisição inválida!', codigo: $this->getCode(), detalhes: $detalhes
		);
	}
}
