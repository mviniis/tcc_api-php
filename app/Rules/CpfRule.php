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
		if(PessoaFisicaRepository::verificarDuplicidadeCpf($value, $this->idPessoa)) {
			$fail("O CPF informado não é válido!");
		}
	}
}
