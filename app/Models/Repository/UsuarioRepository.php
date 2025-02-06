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
	 * @param  string 				$email 					E-mail a ser verificado
	 * @param  int|null 			$idUsuario 			ID do usuário que deve ser desconsiderado
	 * @return bool
	 */
	public function verificarEmailDuplicado(string $email, ?int $idUsuario = null): bool {
		$db = DB::table(Usuario::NOME_TABELA)->where('email', '=', $email);
		if(!is_null($idUsuario)) $db->where('id', '!=', $idUsuario);
		return $db->exists();
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
	 * Método responsável por realizar a atualização dos dados do usuário
	 * @param  Usuario 			$obUsuario 			Dados do usuário que serão atualizados
	 * @return bool
	 */
	public static function atualizar(Usuario $obUsuario): bool {
		if(!is_numeric($obUsuario->id)) return false;

		// EVITA REALIZAR A ATUALIZAÇÃO DE CAMPOS VAZIOS E DOS CAMPOS DE PESSOA
		$dados = (new Converter($obUsuario))->objectToArrayDb();
		foreach($dados as $campo => $valor) {
			if(!is_null($valor) && strlen($valor)) continue;

			unset($dados[$campo]);
		}

		// EVITA A ATUALIZAÇÃO DOS CAMPOS DE VINCULO COM PESSOA
		unset($dados['id'], $dados['id_pessoa']);

		$id = $obUsuario->id;
		return DB::table(Usuario::NOME_TABELA)->where('id', '=', $id)->update($dados) > 0;
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

	/**
	 * Método responsável por consultar um usário pelo seu ID
	 * @param  int 			  $id 			    ID do usuário consultado
	 * @param  array 			$campos 			Campos que serão retornados
	 * @return Usuario
	 */
	public static function getUsuarioPorId(int $id, array $campos = ['*']): Usuario {
		$dados = DB::table(Usuario::NOME_TABELA)->where('id', '=', $id)->get($campos)->first() ?? [];
		return (new Converter(new Usuario, arrayDb: $dados))->arrayDbToObject();
	}

	/**
	 * Método responsável por verificar se um usuário existe
	 * @param  int 			$id 			ID do usuário que está sendo verificado
	 * @return bool
	 */
	public static function usuarioExiste(int $id): bool {
		return DB::table(Usuario::NOME_TABELA)->where('id', '=', $id)->exists();
	}
}
