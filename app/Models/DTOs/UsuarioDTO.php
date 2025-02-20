<?php

namespace App\Models\DTOs;

use App\Core\Database\InstanceInterface;
use App\Models\Instances\Pessoa\{
	Pessoa, PessoaFisica, PessoaJuridica
};
use App\Models\Instances\Usuario\Usuario;

/**
 * class UsuarioDTO
 *
 * Classe responsável por representar um usuário dentro do sistema
 */
class UsuarioDTO {
	/**
	 * Construtor da classe
	 * @param PessoaFisica|PessoaJuridica  			$pessoa					Objeto com os dados pessoais do usuário
	 * @param Usuario 													$usuario  			Objeto com os dados de usuário de uma pessoa
	 */
	public function __construct(
		public InstanceInterface $pessoa,
		public Usuario $usuario,
	) {}
}
