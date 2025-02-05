<?php

namespace App\Exceptions;

use App\Core\System\Configuration;
use Exception;

/**
 * class ActionRepositoryException
 *
 * Classe responsável por lançar uma excessão de erro ao executar a validação de uma requisição de API
 */
class ActionRepositoryException extends Exception {
	/**
	 * Construtor da classe de exceção
	 * @param string 			$message 			Mensagem de exceção
	 * @param int    			$code			    Código de erro
	 */
	public function __construct(string $message = '', int $code = 400) {
		parent::__construct($message, $code);
	}

	public function render() {
		$response = [
			'mensagem' => $this->getMessage(),
			'status'   => false
		];

		if(Configuration::permitirDebug()) $response['backtrace'] = $this->getTrace();

		return response()->json($response, $this->getCode());
	}
}
