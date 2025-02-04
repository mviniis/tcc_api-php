<?php

namespace App\Core\Api;

/**
 * class Pagination
 *
 * Classe responsável por realizar a paginação dos resultados
 */
class Pagination {
	/**
	 * Guarda a paginação formatada com array
	 * @var array
	 */
	private array $paginacao = [
		'total'    			 => 0,
		'exibindo' 			 => 0,
		'paginas'        => 0,
		'itensPorPagina' => 0,
		'navegacao' 		 => []
	];

	/**
	 * Construtor da classe
	 * @param int 			  $total 					Total de itens da listagem
	 * @param int 			  $ipp						Quantidade de itens por página
	 * @param int 			  $page						Página que está sendo exibita
	 * @param int 			  $itens					Quantidade de itens sendo exibido na listagem
	 * @param array 			$parametros			Filtros aplicados na requisição
	 * @param string 			$path						Path da requisição
	 */
	public function __construct(
		private int $total = 0,
		private int $ipp = 0,
		private int $page = 0,
		private int $itens = 0,
		private array $parametros = [],
		private string $path = ''
	) {
		$this->processar();
	}

	/**
	 * Método responsável por retornar a paginação formatada
	 * @return array
	 */
	public function get(): array {
		return $this->paginacao;
	}

	/**
	 * Método responsável por processar os dados da paginação
	 * @return void
	 */
	private function processar(): void {
		$this->paginacao['total']          = $this->total;
		$this->paginacao['itensPorPagina'] = $this->ipp;
		$this->paginacao['exibindo'] 			 = $this->itens;

		// PROCESSA A QUANTIDADE DE PÁGINAS
		$paginas 										= ($this->total < $this->ipp) ? 1: round($this->total / $this->ipp);
		$this->paginacao['paginas'] = $paginas;

		// EVITA ERROS DE EXIBIÇÃO DA PÁGINA ATIVA
		if($this->page < 0) $this->page = 0;
		if($this->page >= $paginas) $this->page = ($paginas - 1);

		// MONTAGEM DAS PAGINAÇÕES DE ACORDO COM AS REGRAS
		$this->menosOuIgualTresPaginas()
				 ->primeiraPagina()
				 ->ultimaPagina()
				 ->demaisPaginas();
	}

	/**
	 * Método responsável por montar um item da paginação
	 * @param  int   			$pagina			Valor da página atual
	 * @param  string			$label			Label que será exibida
	 * @param  bool  			$ativo			Define se a página está sendo acessada
	 * @return array
	 */
	private function montarItem(int $pagina, string $label, bool $ativo): array {
		return [
			'label' => $label,
			'ativo' => $ativo,
			'url'   => env('APP_URL') . '/api/' . $this->path,
			'query' => $this->montarQuery($pagina)
		];
	}

	/**
	 * Método responsável por montar os parâmetros da requisição em forma de string
	 * @param  int			$pagina			Página que a query vai acessar
	 * @return string
	 */
	private function montarQuery(int $pagina): string {
		$query = [];
		foreach($this->parametros as $item => $valor) {
			if($item == 'page') $valor = $pagina;
			$query[] = "{$item}={$valor}";
		}

		return implode('&', $query);
	}

	/**
	 * Método responsável por processar os itens da paginação quando tiver menos ou igual a três páginas
	 * @return self
	 */
	private function menosOuIgualTresPaginas(): self {
		$paginas = $this->paginacao['paginas'];
		if($paginas > 3) return $this;

		for($i = 0; $i < $paginas; $i++) {
			$item = $this->montarItem($i, ($i + 1), $i == $this->page);
			$this->paginacao['navegacao'][] = $item;
		}

		return $this;
	}

	/**
	 * Método responsável por monta a paginação dos dados no início
	 * @return self
	 */
	private function primeiraPagina(): self {
		if($this->page != 0 || !empty($this->paginacao['navegacao'])) return $this;

		// MONTA AS DEMAIS PÁGINAS
		for($i = 0; $i < 3; $i++) {
			$this->paginacao['navegacao'][] = $this->montarItem($i, $i + 1, ($this->page == $i));
		}

		// ÚLTIMA PÁGINA
		$this->paginacao['navegacao'][] = $this->montarItem(($this->paginacao['paginas'] -1), 'Última', false);

		return $this;
	}

	/**
	 * Método responsável por montar a paginação dos dados no final
	 * @return self
	 */
	private function ultimaPagina(): self {
		$paginas = $this->paginacao['paginas'];
		if($this->page != ($paginas - 1) || !empty($this->paginacao['navegacao'])) return $this;

		// PRIMEIRA PÁGINA
		$this->paginacao['navegacao'][] = $this->montarItem(0, 'Primeira', false);

		// DEMAIS PÁGINAS
		for($i = ($paginas - 3); $i < $paginas; $i++) {
			$this->paginacao['navegacao'][] = $this->montarItem($i, $i + 1, ($this->page == $i));
		}

		return $this;
	}

	/**
	 * Método responsável por manipular a navegação das páginas soltas
	 * @return self
	 */
	private function demaisPaginas(): self {
		if(!empty($this->paginacao['navegacao'])) return $this;

		// MONTAGEM DAS PÁGINAS
		$atual 												  = $this->page;
		$this->paginacao['navegacao'][] = $this->montarItem(0, 'Primeira', false);
		$this->paginacao['navegacao'][] = $this->montarItem(($atual -1), $atual, false);
		$this->paginacao['navegacao'][] = $this->montarItem($atual, ($atual + 1), true);
		$this->paginacao['navegacao'][] = $this->montarItem(($atual + 1), ($atual + 2), false);
		$this->paginacao['navegacao'][] = $this->montarItem(($this->paginacao['paginas'] - 1), 'Última', false);

		return $this;
	}
}
