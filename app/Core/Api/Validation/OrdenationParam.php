<?php

namespace App\Core\Api\Validation;

use App\Exceptions\ApiValidationException;

/**
 * class OrdenationParam
 *
 * Classe responsável por validar o parâmetro de ordenação dos registros
 */
abstract class OrdenationParam extends ItemParam {
	protected string $dependency = OrdenationValueParam::class;

	protected function getNameParam(): string {
		return 'order';
	}

	protected function validateValuesParam(): void {
		$valor = trim($this->request[$this->getNameParam()]);
		if(!in_array($valor, $this->getValuesParams())) {
			throw new ApiValidationException(
				"O valor do parâmetro '{$this->getNameParam()}' é inválido!", [
					"description" => "Os valoes aceitos do parâmetro de ordenação são",
					"values"      => $this->getValuesParams()
				]
			);
		}
	}

	/**
	 * Método responsável por recuperar os valores válidos para o campo de ordenação
	 * @return array
	 */
	abstract protected function getValuesParams(): array;
}
