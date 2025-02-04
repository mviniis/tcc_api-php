<?php

namespace App\Models\Rules\Usuarios\Validacoes;

use App\Core\Api\Validation\ItemParam;
use App\Exceptions\ApiValidationException;

/**
 * class ParametroValorBusca
 *
 * Classe responsável por realizar a validação do parâmetro do valor da busca busca de usuários
 */
class ParametroValorBusca extends ItemParam {
	protected function getNameParam(): string {
		return 'valorBusca';
	}

	protected function validateValuesParam(): void {
		$valor = trim($this->request['valorBusca']);
		if(!strlen($valor)) throw new ApiValidationException("O parâmetro 'valorBusca' deve ser preenchido.");
	}
}
