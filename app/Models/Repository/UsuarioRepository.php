<?php

namespace App\Models\Repository;

use App\Core\Database\Converter;
use App\Models\Instances\Usuario;
use Illuminate\Support\Facades\DB;
use App\Models\Instances\Pessoa\{PessoaFisica, PessoaJuridica, PessoaInterface};

/**
 * class UsuarioRepository
 *
 * Classe responsável por centralizar as manipulaçãoes de banco de dados da tabela 'usuario'
 */
class UsuarioRepository {
	/**
	 * Método responsável por verificar se um e-mail já existe no banco de dados
	 * @param  string 			$email 			E-mail a ser verificado
	 * @return bool
	 */
	public function verificarEmailDuplicado(string $email): bool {
		return DB::table(Usuario::NOME_TABELA)->where('email', '=', $email)->count('id') > 0;
	}

	/**
	 * Método responsável por cadastrar um novo usuário
	 * @param  Usuario 				 									$obUsuario 			Dados do novo usuário
	 * @param  PessoaFisica|PessoaJuridica 			$obPessoa				Dados da pessoa
	 * @return Usuario
	 */
	public static function cadastrar(Usuario $obUsuario, PessoaInterface $obPessoa): Usuario {
		$obUsuario->idPessoa        = $obPessoa->idPessoa;
		$obUsuario->dataHoraCriacao = now()->format('Y-m-d H:i:s');

		// REALIZA O CADASTRO
		$obUsuario->id = DB::table(Usuario::NOME_TABELA)->insertGetId(
			(new Converter($obUsuario))->objectToArrayDb()
		);
		return $obUsuario;
	}

	/**
	 * Método responsável por remover os dados de um usuário
	 * @param  Usuario 			$obUsuario 			Dados do usuário a ser removido
	 * @return bool
	 */
	public static function remover(Usuario $obUsuario): bool {
		if(!is_numeric($obUsuario->id)) return false;

		return DB::table(Usuario::NOME_TABELA)->delete($obUsuario->id) > 0;
	}
}
