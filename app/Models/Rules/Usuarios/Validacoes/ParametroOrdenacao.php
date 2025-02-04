<?php

namespace App\Models\Rules\Usuarios\Validacoes;

use App\Core\Api\Validation\OrdenationParam;

/**
 * class ParametroOrdenacao
 *
 * Classe responsável por configurar os campos de ordenação da listagem de usuários
 */
class ParametroOrdenacao extends OrdenationParam {
	protected function getValuesParams(): array {
		return ['id', 'nome'];
	}
}
