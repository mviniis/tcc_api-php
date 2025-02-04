<?php

namespace App\Core\Api;

use App\Exceptions\ApiValidationException;

/**
 * interface RuleValidateRequest
 *
 * Interface responsável por definir o padrão da classe de validação de um item de uma requisição na API
 */
abstract class RuleValidateRequest {
	/**
	 * Contrutor da classe
	 * @param array 			$request 			Dados da requisição
	 * @param array 			$request 			Define se o item de validação possui dependência
	 */
	public function __construct(
		protected array $request = [],
		protected bool $isDependency = false
	) {}

	/**
	 * Método responsável por definir a regra de validação
	 * @throws ApiValidationException
	 * @return void
	 */
	abstract public function validar(): void;
}
