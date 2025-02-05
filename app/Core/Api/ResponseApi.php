<?php

namespace App\Core\Api;

use \Illuminate\Http\JsonResponse;

/**
 * class ResponseApi
 *
 * Classe responsável por centralizar a criação das responses das rotas
 */
abstract class ResponseApi {
	/**
	 * Método responsável por montar a response da API
	 * @param  bool|null 				$sucesso			Define se a response é de sucesso ou não
	 * @param  string|null			$mensagem			Mensagem que será exibida na response
	 * @param  array|null 			$detalhes			Detalhes que devem ser retornados
	 * @param  array|null 			$conteudo			Conteúdo da response
	 * @param  string|null			$indice			  Índice personalizado da response que o conteúdo será exibido
	 * @param  int        			$codigo				Código HTTP da response
	 * @return JsonResponse
	 */
	public static function render(
		?bool $sucesso = null,
		?string $mensagem = null,
		?array $detalhes = null,
		?array $conteudo = null,
		?string $indice = null,
		int $codigo = 200
	): JsonResponse {
		$response = [];

		$mostrarCampoSucesso        = is_bool($sucesso);
		$mostrarCampoMensagem       = !is_null($mensagem) && strlen($mensagem);
		$mostrarCampoDetalhes       = is_array($detalhes) && !empty($detalhes);
		$mostrarConteudo			      = is_array($conteudo) && !empty($conteudo);
		$personalizarIndiceConteudo = !is_null($indice) && strlen($indice);

		if($mostrarCampoSucesso) $response['status'] = $sucesso;
		if($mostrarCampoMensagem) $response['message'] = $mensagem;
		if($mostrarCampoDetalhes) $response['details'] = $detalhes;
		if($mostrarConteudo) {
			if(!$personalizarIndiceConteudo) $indice = 'data';

			(empty($response)) ? ($response = $conteudo): ($response[$indice] = $conteudo);
		}

		return response()->json($response, $codigo);
	}

}
