<?php

namespace App\Rules;

use Closure;
use App\Models\Repository\Pessoa\PessoaFisicaRepository;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * class CnpjRule
 *
 * Classe responsável por validar a duplicidade de um CNPJ
 */
class CpfRule implements ValidationRule {
	/**
	 * Run the validation rule.
	 *
	 * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
	 */
	public function validate(string $attribute, mixed $value, Closure $fail): void {
		if(PessoaFisicaRepository::verificarDuplicidadeCpf($value)) {
			$fail("O CPF informado não é válido!");
		}
	}
}
