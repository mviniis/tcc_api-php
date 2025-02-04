<?php

namespace APp\Core\Api\Validation;

use App\Core\Api\Validation\ItemParam;
use App\Exceptions\ApiValidationException;

/**
 * class OrdenationValueParam
 *
 * Classe responsável por validar os valores de ordenação de uma requisição
 */
class OrdenationValueParam extends ItemParam {
	protected function getNameParam(): string {
		return 'direction';
	}

	protected function validateValuesParam(): void {
		$valoresAceitos = ['asc', 'desc'];
		$valorInformado = trim(strtolower($this->request[$this->getNameParam()]));
		if(!in_array($valorInformado, $valoresAceitos)) {
			throw new ApiValidationException(
				"O valor do parâmetro '{$this->getNameParam()}', é inválido!", [
					"description" => "Valores aceitos:",
					"values"      => $valoresAceitos
				]
			);
		}
	}
}
