<?php

namespace App\Core\System;

use Throwable;
use App\Core\Api\ResponseApi;
use Illuminate\Http\JsonResponse;

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
	 * @return JsonResponse
	 */
	public static function render(Throwable $th, int $codigo = 0): JsonResponse {
		$mensagem = $th->getMessage();

		// MONTA O CÓDIGO DE ERRO
		$codigo   = $th->getCode();
		$detalhes = [];
		if($codigo >= 600) {
			if(Configuration::permitirDebug()) $detalhes['internalCode'] = $codigo;
			$codigo = 500;
		}

		// ADICIONA O TRACE DE ERRO
		if(Configuration::permitirDebug()) $detalhes['trace'] = $th->getTrace();

		// NÃO RENDERIZA O CAMPO DETALHES QUANDO NÃO EXISTIR NENHUM CONTEÚDO
		if(empty($detalhes)) $detalhes = null;

		return ResponseApi::render(
			sucesso: false, mensagem: $mensagem, codigo: $codigo, detalhes: $detalhes
		);
	}
}
