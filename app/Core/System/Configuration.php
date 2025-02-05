<?php

namespace App\Core\System;

/**
 * class Configuration
 *
 * Classe responsável por centralizar as validações de configuraçõe do sistema
 */
class Configuration {
	/**
	 * Método responsável por verificar se a aplicação está online
	 * @return bool
	 */
	public static function estaOnline(): bool {
		return env('APP_ENV') === 'production';
	}

	/**
	 * Método responsável por verificar se os erros podem ser exibidos
	 * @return bool
	 */
	public static function permitirDebug(): bool {
		return !self::estaOnline() && env('APP_DEBUG') == 'true';
	}
}
