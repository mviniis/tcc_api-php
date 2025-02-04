<?php

namespace App\Core\Api\Validation;

use App\Core\Api\RuleValidateRequest;
use App\Exceptions\ApiValidationException;

/**
 * class LimitData
 *
 * Classe responsável por validar se existem parâmetros de limites na requisição
 */
class LimitData extends RuleValidateRequest {
	/**
	 * Método responsável por definir a regra de validação
	 * @throws ApiValidationException
	 * @return void
	 */
	public function validar(): void {
		// VERIFICA SE OS CAMPOS EXISTEM
		if(!isset($this->request['ipp']) || !isset($this->request['page'])) {
			throw new ApiValidationException("Os campos 'page' e 'ipp' são obrigatórios");
		}

		$page = $this->request['page'];
		$ipp  = $this->request['ipp'];

		// VERIFICA SE SÃO NUMÉRICOS
		if(!is_numeric($page) || !is_numeric($ipp)) {
			throw new ApiValidationException("Os valores de 'page' e 'ipp', devem ser valores numéricos!");
		}
	}
}
