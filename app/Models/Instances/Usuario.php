<?php

namespace App\Models\Instances;

use App\Core\Database\InstanceInterface;

/**
 * class Usuario
 *
 * Classe responsável por representar a tabela 'usuario'
 */
class Usuario implements InstanceInterface {
	public $id;
	public $email;
	public $senha;
	public $icone;
	public $idPessoa;
	public $idPerfil;
	public $ativo;
	public $dataHoraCriacao;

	public const NOME_TABELA = 'usuario';

	/**
	 * Método responsável por retornar o mapeamento de campos de dados vindos do banco de dados [campoClasse => campo_tabela]
	 * @return array
	 */
	public function getSchema(): array {
		return [
			'id'              => 'id',
			'email'           => 'email',
			'senha'           => 'senha',
			'icone'           => 'icone',
			'idPessoa'        => 'id_pessoa',
			'idPerfil'        => 'id_perfil',
			'ativo'           => 'ativo',
			'dataHoraCriacao' => 'data_hora_criacao',
		];
	}
}
