<?php

namespace App\Models\Rules\Usuarios\Api;

use App\Core\Api\Pagination;
use App\Core\Api\ValidateRequest;
use App\Core\Database\Converter;
use App\Models\Instances\Usuario;
use Illuminate\Support\Facades\DB;

/**
 * class ListagemUsuarios
 *
 * Classe reponsável por validar os dados enviados na requisição de listagem
 */
class ListagemUsuarios extends ValidateRequest {
	private \Illuminate\Database\Query\Builder $db;

	public array $validacoes = [
		\App\Core\Api\Validation\LimitData::class,
		\App\Models\Rules\Usuarios\Validacoes\ParametroOrdenacao::class,
		\App\Models\Rules\Usuarios\Validacoes\ParametroBusca::class,
		\App\Models\Rules\Usuarios\Validacoes\ParametroFiltro::class
	];

	/**
	 * Método responsável por consultar a listagem de usuários
	 * @return array
	 */
	public function listar(): array {
		$this->db = DB::table(Usuario::NOME_TABELA);

		$this->addJoins()->addCondicaoBusca()->addCondicaoFiltro();

		// VERIFICA O TOTAL DE USUÁRIOS
		$total = $this->db->count(['usuario.id']);

		// ADICIONA AS CONDIÇÕES DE LIMITES E PAGINAÇÕES
		$this->addLimitAndPagination()->addOrder();

		// BUSCA OS USUÁRIOS DA LISTAGEM
		$usuarios = (new Converter((new Usuario)))->listArrayDbToListArrayClass($this->db->get()->all());

		return [
			'usuarios'  => $usuarios,
			'paginacao' => (new Pagination(
				total: $total,
				ipp: $this->request['ipp'] ?? 10,
				page: $this->request['page'] ?? 0,
				itens: count($usuarios),
				parametros: $this->request,
				path: 'users'
			))->get()
		];
	}

	/**
	 * Método responsável por definir os campos retornados
	 * @return self
	 */
	private function defineFields(): self {
		$this->db->selectRaw('usuario.id')
						 ->selectRaw('usuario.email')
						 ->selectRaw('usuario.ativo')
				     ->selectRaw('IFNULL(pj.nome_fantasia, pf.nome) as nome');

		return $this;
	}

	/**
	 * Método responsável por adicionar os limitadoes de dados retornados
	 * @return self
	 */
	private function addLimitAndPagination(): self {
		$limit  = $this->request['ipp'] ?? 10;
		$offset = $this->request['page'] ?? 0;

		$this->db->limit($limit)->offset($offset);

		return $this;
	}

	/**
	 * Método responsável por adicionar a ordenação dos dados
	 * @return self
	 */
	private function addOrder(): self {
		$order 		 = $this->request['order'] ?? 'id';
		$direction = $this->request['direction'] ?? 'asc';

		switch($order) {
			case 'id':
				$this->db->orderBy('usuario.id', $direction);
				break;

			case 'nome':
				$this->db->orderBy('pf.nome', $direction)->orderBy('pj.razao_social', $direction);
				break;
		}

		return $this;
	}

	/**
	 * Método responsável por adicionar as junções com outras tabelas
	 * @return self
	 */
	private function addJoins(): self {
		$this->db->leftJoin('pessoa_fisica as pf', 'pf.id_pessoa', '=', 'usuario.id_pessoa')
						 ->leftJoin('pessoa_juridica as pj', 'pj.id_pessoa', '=', 'usuario.id_pessoa');

		return $this;
	}

	/**
	 * Método responsável por adicionar as condições da busca dos usuários
	 * @return self
	 */
	private function addCondicaoBusca(): self {
		if(!isset($this->request['campoBusca'])) return $this;

		$valor = $this->request['valorBusca'];
		switch($this->request['campoBusca']) {
			case 'email':
				$this->db->where('usuario.email', '=', $valor);
				break;

			case 'nome':
				$this->db->whereLike('pf.nome', $valor);
				break;

			case 'fantasia':
				$this->db->whereLike('pj.nome_fantasia', $valor);
				break;

			case 'cpf':
				$this->db->where('pf.cpf', '=', $valor);
				break;

			case 'cnpj':
				$this->db->where('pj.cnpj', '=', $valor);
				break;
		}

		return $this;
	}

	/**
	 * Método responsável por adicionar as condições de filtros dos usuários
	 * @return self
	 */
	private function addCondicaoFiltro(): self {
		if(!isset($this->request['campoFiltro'])) return $this;

		$valor = $this->request['valorFiltro'];
		switch($this->request['campoFiltro']) {
			case 'ativo':
				$this->db->where('usuario.ativo', '=', $valor);
				break;

			case 'tipoPessoa':
				$this->db->whereNotNull("{$valor}.id");
				break;
		}

		return $this;
	}
}
