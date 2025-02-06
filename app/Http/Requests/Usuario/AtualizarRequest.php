<?php

namespace App\Http\Requests\Usuario;

use App\Exceptions\ApiValidationException;
use App\Models\Repository\UsuarioRepository;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\{CnpjRule, CpfRule, EmailRule, PefilRule};

/**
 * class AtualizarRequest
 *
 * Classe responsável por realizar a validação da requisição de atualização de um usuário
 */
class AtualizarRequest extends FormRequest {
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
	 */
	public function rules(): array {
		$idUsuario = $this->route('id');
		if(!is_numeric($idUsuario)) {
			throw new ApiValidationException(
				"Para atualizar um usuário, deve-se informar um ID válido!", code: 422
			);
		}

		// VERIFICA SE O USUÁRIO EXISTE
		$obUsuario = UsuarioRepository::getUsuarioPorId($idUsuario, ['id', 'id_pessoa']);
		if(!is_numeric($obUsuario->id)) {
			throw new ApiValidationException(
				"O usuário informado não foi encontrado!", code: 404
			);
		}

		$idPessoa = $obUsuario->idPessoa;

		return  [
			'email' => [
				'required', new EmailRule($idUsuario)
			],
			'tipoPessoa' => [
				'required', Rule::in(['fisica', 'juridica'])
			],
			'nome' => [
				'required_if:tipoPessoa,fisica', 'min:3'
			],
			'cpf' => [
				'required_if:tipoPessoa,fisica', 'integer', 'min_digits:11', 'max_digits:11', new CpfRule($idPessoa)
			],
			'nomeFantasia' => [
				'required_if:tipoPessoa,juridica', 'min:3'
			],
			'razaoSocial' => [
				'required_if:tipoPessoa,juridica', 'min:3'
			],
			'cnpj' => [
				'required_if:tipoPessoa,juridica', 'integer', 'min_digits:14', 'max_digits:14', new CnpjRule($idPessoa)
			],
			'idPerfil' => [
				'required', 'integer', new PefilRule
			],
			'senha' => [ 'min:6' ],
			'confirmacaoSenha' => [ 'required_with:senha', 'same:senha' ]
		];
	}

	public function messages(): array{
		return [
			"email.required"           			 => "O campo 'email' é obrigatório",
			"tipoPessoa.required"      			 => "O campo 'tipoPessoa' é obrigatório",
			"tipoPessoa.in"            			 => "O tipo da pessoa deve ser 'fisica' ou 'juridica'",
			"nome.required_if"         			 => "O nome é obrigatório para pessoas físicas",
			"nome.min"       			     			 => "O nome deve possuir no mínimo 3 caracteres",
			"cpf.required_if"          			 => "O CPF é obrigatório para pessoas físicas",
			"cpf.integer"       	     			 => "O valor do campo 'cpf' deve ser numérico",
			"cpf.min_digits" 			     			 => "O CPF deve possuir no mínimo 11 caracteres",
			"cpf.max_digits" 			     			 => "O CPF deve possuir no máximo 11 caracteres",
			"nomeFantasia.required_if" 			 => "O nome fantasia é obrigatório para pessoas jurídicas",
			"nomeFantasia.min"       	 			 => "O nome fantasia deve possuir no mínimo 3 caracteres",
			"razaoSocial.required_if"  			 => "A razão social é obrigatória para pessoas jurídicas",
			"razaoSocial.min"       	 			 => "A razão social deve possuir no mínimo 3 caracteres",
			"cnpj.required_if"         			 => "O CNPJ é obrigatório para pessoas jurídicas",
			"cnpj.integer"       	     			 => "O valor do campo 'cnpj' deve ser numérico",
			"cnpj.min_digits"			     			 => "O CNPJ deve possuir no mínimo 14 caracteres",
			"cnpj.max_digits"			     			 => "O CNPJ deve possuir no máximo 14 caracteres",
			"idPerfil.required"        			 => "O ID do perfil do novo usuário deve ser informado",
			"cnpj.integer"       			 			 => "O ID do perfil deve ser numérico",
			"senha.min"       			   			 => "A senha deve possuir pelo menos 6 caracteres",
			"confirmacaoSenha.required_with" => "A confirmação de senha é obrigatória",
			"confirmacaoSenha.same"	   			 => "A confirmação de senha não corresponde a senha informada",
		];
	}
}
