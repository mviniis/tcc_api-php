<?php

namespace App\Core\Database;

/**
 * interface InstanceInterface
 *
 * Interface responsável por definir o tipo das representações de tabelas de bancos
 */
interface InstanceInterface {
	/**
	 * Método responsável por retornar o mapeamento de campos de dados vindos do banco de dados [campoClasse => campo_tabela]
	 * @return array
	 */
	public function getSchema(): array;
}
