<?php

namespace App\Exceptions;

use App\Core\Api\ResponseApi;
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
		$indice    = null;
		$backtrace = null;
		if(Configuration::permitirDebug()) {
			$indice    = 'backtrace';
			$backtrace = $this->getTrace();
		}

		return ResponseApi::render(
			sucesso: false, mensagem: $this->getMessage(), indice: $indice,
			conteudo: $backtrace, codigo: $this->getCode()
		);
	}
}
