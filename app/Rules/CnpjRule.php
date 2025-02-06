<?php

namespace App\Rules;

use Closure;
use App\Models\Repository\Pessoa\PessoaJuridicaRepository;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * class CnpjRule
 *
 * Classe responsável por validar a duplicidade de um CNPJ
 */
class CnpjRule implements ValidationRule {
	/**
	 * Construtor da classe de regra de CPF
	 * @param int|null 			$idPessoa 			ID da pessoa que está sendo validado
	 */
	public function __construct( private ?int $idPessoa = null ) {}

	/**
	 * Run the validation rule.
	 *
	 * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
	 */
	public function validate(string $attribute, mixed $value, Closure $fail): void {
		if(PessoaJuridicaRepository::verificarDuplicidadeCnpj($value, $this->idPessoa)) {
			$fail("O CNPJ informado não é válido!");
		}
	}
}
