<?php

namespace App\Core\System;

use Throwable;

/**
 * class RenderDefaultException
 *
 * Classe responsável por padronizar as mensagens de erros defaults
 */
abstract class RenderDefaultException {
	/**
	 * Método responsálvel por monta a mensagem de response quando uma exceção for lancada
	 * @param  Throwable 			$th 					Exceção gerada pelo sistema
	 * @param  int 	      		$codigo 			Código da exceção
	 * @return array
	 */
	public static function render(Throwable $th, int &$codigo): array {
		$response 					 = [];
		$response['sucess']  = false;
		$response['message'] = $th->getMessage();

		// MONTA O CÓDIGO DE ERRO
		$codigo = $th->getCode();
		if($codigo >= 600) {
			$codigo = 500;
			if(Configuration::permitirDebug()) $response['internalCode'] = $th->getCode();
		}

		// ADICIONA O TRACE DE ERRO
		if(Configuration::permitirDebug()) $response['trace'] = $th->getTrace();

		return $response;
	}
}
