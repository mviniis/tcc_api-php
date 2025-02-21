<?php

namespace App\Http\Requests\Cuidador;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\{CnpjRule, CpfRule, EmailRule, PefilRule};

/**
 * class CadastroRequest
 */
class CadastroRequest extends FormRequest {
	/**
	 * Método responsável por realizar a validação da requisição de cadastro de um responsável
	 * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
	 */
	public function rules(): array {
		return [
			'email' => [
				'required', new EmailRule
			],
			'tipoPessoa' => [
				'required', Rule::in(['fisica', 'juridica'])
			],
			'nome' => [
				'required_if:tipoPessoa,fisica', 'min:3'
			],
			'cpf' => [
				'required_if:tipoPessoa,fisica', 'integer', 'min_digits:11', 'max_digits:11', new CpfRule
			],
			'nomeFantasia' => [
				'required_if:tipoPessoa,juridica', 'min:3'
			],
			'razaoSocial' => [
				'required_if:tipoPessoa,juridica', 'min:3'
			],
			'cnpj' => [
				'required_if:tipoPessoa,juridica', 'integer', 'min_digits:14', 'max_digits:14', new CnpjRule
			],
			'senha' => [
				'required', 'same:confirmacaoSenha', 'min:6'
			],
			'confirmacaoSenha' => 'required'
		];
	}

	public function messages(): array{
		return [
			"email.required"           	=> "O campo 'email' é obrigatório",
			"tipoPessoa.required"      	=> "O campo 'tipoPessoa' é obrigatório",
			"tipoPessoa.in"            	=> "O tipo da pessoa deve ser 'fisica' ou 'juridica'",
			"nome.required_if"         	=> "O nome é obrigatório para pessoas físicas",
			"nome.min"       			     	=> "O nome deve possuir no mínimo 3 caracteres",
			"cpf.required_if"          	=> "O CPF é obrigatório para pessoas físicas",
			"cpf.integer"       	     	=> "O valor do campo 'cpf' deve ser numérico",
			"cpf.min_digits" 			     	=> "O CPF deve possuir no mínimo 11 caracteres",
			"cpf.max_digits" 			     	=> "O CPF deve possuir no máximo 11 caracteres",
			"nomeFantasia.required_if" 	=> "O nome fantasia é obrigatório para pessoas jurídicas",
			"nomeFantasia.min"       	 	=> "O nome fantasia deve possuir no mínimo 3 caracteres",
			"razaoSocial.required_if"  	=> "A razão social é obrigatória para pessoas jurídicas",
			"razaoSocial.min"       	 	=> "A razão social deve possuir no mínimo 3 caracteres",
			"cnpj.required_if"         	=> "O CNPJ é obrigatório para pessoas jurídicas",
			"cnpj.integer"       	     	=> "O valor do campo 'cnpj' deve ser numérico",
			"cnpj.min_digits"			     	=> "O CNPJ deve possuir no mínimo 14 caracteres",
			"cnpj.max_digits"			     	=> "O CNPJ deve possuir no máximo 14 caracteres",
			"cnpj.integer"       			 	=> "O ID do perfil deve ser numérico",
			"senha.required"       		 	=> "A senha do novo usuário deve ser informada",
			"senha.same"       			   	=> "A confirmação de senha não corresponde a senha informada",
			"senha.min"       			   	=> "A senha deve possuir pelo menos 6 caracteres",
			"confirmacaoSenha.required" => "A confirmação de senha é obrigatória",
		];
	}
}
