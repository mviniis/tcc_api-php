<?php

namespace App\Core\Database;

use ReflectionClass;
use App\Core\Database\InstanceInterface;
use stdClass;

/**
 * class Converter
 *
 * Classe responsável por realizar a conversão de dados
 */
class Converter {
	/**
	 * Construtor da classe
	 * @param InstanceInterface 	$object	  			Objeto base que será usado para conversão (com ou sem dados)
	 * @param array 			      	$arrayClass 		Dados com os índices formatados para classe
	 * @param array|object 	    	$arrayDb	  		Dados com os índices formatados para banco
	 * @param array|object 	    	$validos	  		Retorna os dados somente dos campos da classe
	 */
	public function __construct(
		private ?InstanceInterface $object = null,
		private array $arrayClass = [],
		private array|object $arrayDb = [],
		private bool $validos = false
	) {}

	/**
	 * Método responsável por converter um objeto para uma array formatada no padrão da classe
	 * @return array
	 */
	public function objectToArrayClass(): array {
		return $this->mapKeys((array) $this->object, $this->object->getSchema(), 'class');
	}

	/**
	 * Método responsável por converter um objeto para uma array formatada no padrão do banco de dados
	 * @return array
	 */
	public function objectToArrayDb(): array {
		return $this->mapKeys((array) $this->object, $this->object->getSchema(), 'db');
	}

	/**
	 * Método responsável por transformar uma array no formato da classe para um objeto
	 * @return InstanceInterface
	 */
	public function arrayClassToObject(): InstanceInterface {
		$mappedData = $this->mapKeys($this->arrayClass, $this->object->getSchema(), 'class');
		return $this->createObject($mappedData);
	}

	/**
	 * Método responsável por converter um array no formato da classe para um array formatado para o banco de dados
	 * @return array
	 */
	public function arrayClassToArrayDb(): array {
		return $this->mapKeys($this->arrayClass, $this->object->getSchema(), 'db');
	}

	/**
	 * Método responsável por transformar uma array no formato do banco para um objeto
	 * @return InstanceInterface
	 */
	public function arrayDbToObject(): InstanceInterface {
		$data 			= $this->convertToArray($this->arrayDb);
		$mappedData = $this->mapKeys($data, $this->object->getSchema(), 'class');

		return $this->createObject($mappedData);
	}

	/**
	 * Método responsável por converter um array no formato do banco para um array formatado para a classe
	 * @return array
	 */
	public function arrayDbToArrayClass(): array {
		$data = $this->convertToArray($this->arrayDb);
		return $this->mapKeys($data, $this->object->getSchema(), 'class');
	}

	/**
	 * Método responsável por converter uma lista de objetos para uma lista de arrays formatados para a classe
	 * @param  InstanceInterface[] $objects 	Objetos com valores que deverão ser formatados
	 * @return array
	 */
	public function listObjectToListArrayClass(array $objects): array {
		return array_map(fn($obj) => $this->mapKeys((array) $obj, $this->object->getSchema(), 'class'), $objects);
	}

	/**
	 * Método responsável por converter uma lista de objetos para uma lista de arrays formatados para o banco de dados
	 * @param  InstanceInterface[] $objects 	Objetos com valores que deverão ser formatados
	 * @return array
	 */
	public function listObjectToListArrayDb(array $objects): array {
		return array_map(fn($obj) => $this->mapKeys((array) $obj, $this->object->getSchema(), 'db'), $objects);
	}

	/**
	 * Método responsável por uma lista de arrays do formato da classe para uma lista de objetos
	 * @param  array[] $data 	Dados de uma array
	 * @return InstanceInterface[]
	 */
	public function listArrayClassToListObject(array $data): array {
		return array_map(fn($item) => $this->createObject($this->mapKeys($item, $this->object->getSchema(), 'class')), $data);
	}

	/**
	 * Método responsável por uma lista de arrays do formato da classe para uma lista de objetos
	 * @param  array[] $data 	Dados de uma array
	 * @return InstanceInterface[]
	 */
	public function listArrayClassToListArrayDb(array $data): array {
		return array_map(fn($item) => $this->mapKeys($item, $this->object->getSchema(), 'db'), $data);
	}

	/**
	 * Método responsável por converter uma lista de arrays do formato do banco para uma lista de objetos
	 * @param  array[]|stdClass[] $data 	Dados de uma array ou stdClass
	 * @return InstanceInterface[]
	 */
	public function listArrayDbToListObject(array $data): array {
		return array_map(function($item) {
			return $this->createObject(
				$this->mapKeys($this->convertToArray($item), $this->object->getSchema(), 'class')
			);
		}, $data);
	}

	/**
	 * Método responsável por converter uma lista de arrays do formato do banco para uma lista de objetos
	 * @param  array[]|stdClass[] $data 	Dados de uma array ou stdClass
	 * @return InstanceInterface[]
	 */
	public function listArrayDbToListArrayClass(array $data): array {
		return array_map(function($item) {
			return $this->mapKeys($this->convertToArray($item), $this->object->getSchema(), 'class');
		}, $data);
	}

	/**
	 * Método responsável por mapear os dados de acordo com o esquema fornecido, preservando valores extras
	 * @param array  $data    Dados que serão usados para mapeamento
	 * @param array  $schema  Schema dos dados que serão convertidos
	 * @param string $mode    Define se deve considerar o esquema da classe ('class') ou do banco ('db')
	 * @return array
	 */
	private function mapKeys(array $data, array $schema, string $mode): array {
		$mapped = [];
		$map 		= ($mode === 'db') ? $schema: array_flip($schema);

		foreach($data as $key => $value) $mapped[$map[$key] ?? $key] = $value;

		return $mapped;
	}

	/**
	 * Método responsável por criar um objeto dinâmico, adicionando atributos extras caso necessário
	 * @param  array $data 	Dados que serão usados para popular o objeto
	 * @return InstanceInterface
	 */
	private function createObject(array $data): InstanceInterface {
		$object = new ($this->object::class)();

		foreach ($data as $key => $value) {
			$this->setProperty($object, $key, $value);
		}

		return $object;
	}

	/**
	 * Método responsável por definir dinamicamente um atributo em um objeto
	 * @param  object $object		Objeto que está sendo manipulado
	 * @param  string $property 	Propriedades do objeto
	 * @param  mixed  $value		Valores que deverão ser populados
	 * @return void
	 */
	private function setProperty(object $object, string $property, mixed $value): void {
		$reflection = new ReflectionClass($object);

		// VERIFICA SE O CAMPO EXISTE
		if($reflection->hasProperty($property)) {
			$prop = $reflection->getProperty($property);
			$prop->setAccessible(true);
			$prop->setValue($object, $value);
		}

		// VERIFICA SE SOMENTE OS CAMPOS VÁLIDOS SERÃO RETORNADOS
		if(!$reflection->hasProperty($property) && !$this->validos) {
			$object->$property = $value;
		}
	}

	/**
	 * Método responsável por converter um stdClass em array, se necessário
	 * @param  array|stdClass $data 	Dados que podem ser array ou stdClass
	 * @return array
	 */
	private function convertToArray(array|stdClass $data): array {
		return $data instanceof stdClass ? (array) $data : $data;
	}
}
