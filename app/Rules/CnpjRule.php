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
	 * Run the validation rule.
	 *
	 * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
	 */
	public function validate(string $attribute, mixed $value, Closure $fail): void {
		if(PessoaJuridicaRepository::verificarDuplicidadeCnpj($value)) {
			$fail("O CNPJ informado não é válido!");
		}
	}
}
