<?php

namespace App\Http\Requests\Usuario;

use App\Exceptions\ApiValidationException;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\Repository\Usuario\UsuarioRepository;

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
		$idUsuario = $this->route('id');
		if(!is_numeric($idUsuario)) $idUsuario = 0;
		if(!UsuarioRepository::usuarioExiste($idUsuario)) {
			throw new ApiValidationException(
				message: "O usuário informado não foi encontrado!", code: 404
			);
		}

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
