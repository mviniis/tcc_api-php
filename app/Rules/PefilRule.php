<?php

namespace App\Rules;

use App\Models\Repository\PerfilRepository;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * class PerfilRule
 *
 * Classe responsável pela validação de um pefil
 */
class PefilRule implements ValidationRule
{
	/**
	 * Run the validation rule.
	 *
	 * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
	 */
	public function validate(string $attribute, mixed $value, Closure $fail): void {
		$obRepository = new PerfilRepository;

		$ids    = [];
		$labels = [];
		foreach($obRepository->getPerfisDisponiveis(['id', 'nome']) as $perfil) {
			$ids[] 		= $perfil->id;
			$labels[] = "({$perfil->id}: {$perfil->nome})";
		}

		if(!in_array($value, $ids)) {
			$fail("O ID de perfil não é válido. Os perfis válidos são: ". implode(", ", $labels));
		}
	}
}
