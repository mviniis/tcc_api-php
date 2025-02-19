<?php

namespace App\Rules;

use Closure;
use App\Models\Repository\Usuario\UsuarioRepository;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * class EmailRule
 *
 * Classe responsável por validar um e-mail
 */
class EmailRule implements ValidationRule {
	/**
	 * Construtor da classe de regras de e-mails
	 * @param int|null 			$idUsuario 			ID do usuário que está sendo validado
	 */
	public function __construct(
		private ?int $idUsuario = null
	) {}

	/**
	 * Run the validation rule.
	 *
	 * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
	 */
	public function validate(string $attribute, mixed $value, Closure $fail): void {
		$continue = true;

		// VERIFICA SE O E-MAIL É VÁLIDO
		if(!filter_var($value, FILTER_VALIDATE_EMAIL)) {
			$fail("O e-mail informado é inválido");
			$continue = false;
		}

		// VERIFICA SE O E-MAIL NÃO É DUPLICADO
		if($continue) {
			if((new UsuarioRepository)->verificarEmailDuplicado($value, $this->idUsuario)) {
				$fail("O e-mail informado é inválido");
			}
		}
	}
}
