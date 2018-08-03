<?php
namespace patterns\strategy;
use dao\Dao;

class Query{
	//aca van Qand,Qor,Qlike,Qin,etc
	public $statements 	= array();
	public $Model		= null;
	public $result		= null;
	//se lo modifica cada Qand,Qor,Qlike,etc
	public $binds 		= array();
	public $query		= null;

	//$Statement es Qand,Qor,Qlike,Qin,etc
	public function add($Statement){
		$this->statements[] = $Statement;
	}

	public function __construct($Model){
		$this->Model = $Model;
		//voy a retornar un objeto con varios atributos para manipularlos
		$this->result = new \stdClass();//crea una variable de tipo object
	}

	//este metodo es el strategy
	public function prepare(){		
		$this->binds = array();
		$this->query = null;

		if(is_array($this->statements) && !empty($this->statements)){
			foreach($this->statements as $pos=>$Statment){
				//Escribime tu parte de consulta, estas en la pos $pos
				$Statment->prepare($this,$pos);
			}
		}		
		//retorno objeto resultado
		$reflect 	= new \ReflectionClass($this->Model);
		$tabla 		= $reflect->getShortName();
		$tabla      = strtolower($tabla);
		$this->result->query 	= $this->query;
		$this->result->table 	= $tabla;		
		$this->result->select 	= "SELECT * FROM ".$this->result->table;
		$this->result->binds	= $this->binds;		
		return $this->result;
	}

}
