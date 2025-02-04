<?php

namespace App\Models\Rules\Usuarios\Validacoes;

use App\Core\Api\Validation\ItemParam;
use App\Exceptions\ApiValidationException;

/**
 * class ParametroBusca
 *
 * Classe responsável por realizar a validação dos parâmetros de busca de usuários
 */
class ParametroBusca extends ItemParam {
	protected string $dependency = ParametroValorBusca::class;

	protected function getNameParam(): string {
		return 'campoBusca';
	}

	protected function validateValuesParam(): void {
		$valoresCampoBusca = ["email", "nome", "fantasia", "cpf", "cnpj"];
		if(!in_array($this->request[$this->getNameParam()], $valoresCampoBusca)) {
			throw new ApiValidationException(
				"O parâmetro '{$this->getNameParam()}' deve ser preenchido!",
				[
					"description" => "Esse campo aceita os seguintes valores",
					"values"      => [
						"email"    => "E-mail de um usuário",
						"nome"     => "Nome de um usuário PF",
						"fantasia" => "Nome fantasia de um usuário PJ",
						"cpf"      => "CPF de um usuário PF",
						"cnpj"     => "CPNJ de um usuário PJ"
					]
				]
			);
		}
	}
}
