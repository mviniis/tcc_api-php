<?php

namespace App\Core\Security;

use Illuminate\Support\Facades\Hash;

/**
 * class PasswordEncryptor
 *
 * Classe responsável por realizar a criptografia e validação de senhas
 */
class PasswordEncryptor {
	/**
	 * Método para criptografar uma senha
	 * @param  string 			$password 			Senha que deverá ser criptografada
	 * @return string
	 */
	public static function encrypt(string $password): string {
		return Hash::make($password, ['driver' => 'argon2id']);
	}

	/**
	 * Método para verificar se a senha informada corresponde ao hash armazenado
	 * @param  string 			$password							Senha sem criptografica usada para a validação
	 * @param  string 			$hashedPassword 			Senha criptografada que será validada
	 * @return bool
	 */
	public static function verify(string $password, string $hashedPassword): bool {
		return Hash::check($password, $hashedPassword, ['driver' => 'argon2id']);
	}
}
