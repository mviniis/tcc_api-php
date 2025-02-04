<?php

namespace App\Models\Rules\Usuarios\Validacoes;

use App\Core\Api\Validation\ItemParam;
use App\Exceptions\ApiValidationException;

/**
 * class ParametroFiltro
 *
 * Classe responsável por validar os filtros da listagem de usuários
 */
class ParametroFiltro extends ItemParam {
	protected string $dependency = ParametroValorFiltro::class;

	protected function getNameParam(): string {
		return 'campoFiltro';
	}

	protected function validateValuesParam(): void {
		$valoresCampoBusca = ["ativo", "tipoPessoa"];
		if(!in_array($this->request[$this->getNameParam()], $valoresCampoBusca)) {
			throw new ApiValidationException(
				"O parâmetro '{$this->getNameParam()}' possui um valor inválido!",
				[
					"description" => "Esse campo aceita os seguintes valores",
					"values"      => [
						"ativo"      => "Filtra pelos usuários ativos ou não",
						"tipoPessoa" => "Filtra pelo tipo da pessoa",
					]
				]
			);
		}
	}
}
