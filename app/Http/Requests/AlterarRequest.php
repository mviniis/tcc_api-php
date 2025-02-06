<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * class AlterarRequest
 *
 * Classe responsável por validar os dados enviados na atualização de partes das informações de usuários
 */
class AlterarRequest extends FormRequest {
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
	 */
	public function rules(): array {
		return [
			'ativo' => [ 'boolean' ],
			'icone' => [ 'string', 'min:1', 'max:50' ]
		];
	}

	public function messages(): array {
		return [
			"ativo.boolean" => "Somente valores boleanos são aceitos",
			"icone.min"     => "O campo deve possuir pelo menos 1 caracter",
			"icone.max"     => "O campo deve possuir no máximo 50 caracteres",
		];
	}
}
