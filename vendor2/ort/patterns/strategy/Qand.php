<?php
namespace patterns\strategy;
use patterns\strategy\QueryAbstract;

class Qand extends QueryAbstract{
	
	public function __construct($field,$value){
		if(is_array($field) && !empty($field)){
			$this->conditions = $field;
		}
		elseif(!is_null($field) && !is_null($value)){
			$this->conditions[$field] = $value;
		}
	}
	//Ej:
	//Qand = new Qand('nombre','juan') o 
	//Qand = new Qand(array('nombre'=>'juan','appellido'=>'perez')
	
	//Q es el cliente
	public function prepare($Q, $pos = 0){
		$result = null;
		if(is_array($this->conditions) && !empty($this->conditions)){
			//recorro campo,valor
			foreach($this->conditions as $field=>$value){
				$result .= " AND $field = ?";
				$Q->binds[] = $value;
			}
		//si es el primero no utilizo el primer AND
		$result = ($pos == 0)?substr($result, 4):$result;
		//si es la primer sentencia escribe el WHERE
		$result = (is_null($Q->query))?" WHERE $result":$result;
		}
		//le agrego a Q porcion de consulta
		$Q->query .= $result;
	}
}