<?php

namespace App\Models\Rules\Usuarios\Validacoes;

use App\Core\Api\Validation\ItemParam;
use App\Exceptions\ApiValidationException;

/**
 * class ParametroValorFiltro
 *
 * Classe responsável por realizar a validação do parâmetro do valor de um filtro na busca de usuários
 */
class ParametroValorFiltro extends ItemParam {
	protected function getNameParam(): string {
		return 'valorFiltro';
	}

	protected function validateValuesParam(): void {
		$valores = [
			'ativo'      => ['s', 'n'],
			'tipoPessoa' => ['pf', 'pj']
		];
		if(!in_array($this->request[$this->getNameParam()], $valores[$this->request['campoFiltro']])) {
			throw new ApiValidationException("O valor enviado no campo '{$this->getNameParam()}' não é válido!", [
				"description" => "Os valores aceitos para o filtro '". $this->request['campoFiltro'] ."' são",
				"values"			=> $valores[$this->request['campoFiltro']]
			]);
		}
	}
}
