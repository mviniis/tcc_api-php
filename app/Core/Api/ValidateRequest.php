<?php

namespace App\Core\Api;

/**
 * class ValidateRequest
 *
 * Classe responsável por facilitar as validações de uma requisição a API
 */
abstract class ValidateRequest {
	/**
	 * Guarda as classes de validações
	 * @var array
	 */
	protected array $validacoes = [];

	/**
	 * Guarda os dados da requisição
	 * @var array
	 */
	protected array $request = [];

	/**
	 * Construtor da classe
	 * @param array				$request				Dados da requisição
	 */
	public function __construct(array $request = []) {
		$this->request = $request;
		$this->validar();
	}

	/**
	 * Método responsável por realizar a validação dos dados
	 * @return void
	 */
	protected function validar(): void {
		if(empty($this->validacoes)) return;

		/** @var RuleValidateRequest $classeValidacao */
		foreach($this->validacoes as $classeValidacao) (new $classeValidacao($this->request))->validar();
	}
}
