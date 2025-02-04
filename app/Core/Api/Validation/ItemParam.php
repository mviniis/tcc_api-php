<?php

namespace App\Core\Api\Validation;

use App\Core\Api\RuleValidateRequest;
use App\Exceptions\ApiValidationException;

/**
 * class ItemParam
 *
 * Classe responsável por ser o modelo para validação de um parâmetro de uma requisição
 */
abstract class ItemParam extends RuleValidateRequest {
	/**
	 * Parâmetro de dependência
	 * @var string
	 */
	protected string $dependency;

	/**
	 * Método responsável por retornar o nome do parâmetro aplicado
	 * @return string
	 */
	abstract protected function getNameParam(): string;

	/**
	 * Método responsável por validar os valores de um parâmetro
	 * @throws ApiValidationException
	 * @return void
	 */
	abstract protected function validateValuesParam(): void;

	/**
	 * Método responsável por definir a regra de validação
	 * @throws ApiValidationException
	 * @return void
	 */
	public function validar(): void {
		$parametroExiste = in_array($this->getNameParam(), array_keys($this->request));
		if(!$parametroExiste && !$this->isDependency) return;

		if(!$parametroExiste && $this->isDependency) {
			throw new ApiValidationException("O parâmetro '{$this->getNameParam()}' é obrigatório!");
		}

		$this->validateValuesParam();
		if(!$this->isDependency) $this->validateDependency();
	}

	/**
	 * Método responsável por validar a dependência de um outro parâmetro na requisição
	 * @throws ApiValidationException
	 * @return void
	 */
	private function validateDependency(): void {
		$dependency = trim($this->dependency);
		if(!strlen($dependency)) return;

		(new $dependency($this->request, true))->validar();
	}
}
