<?php
namespace patterns\strategy;

abstract class QueryAbstract{
	
	public $conditions = null;
	
	public function add($field,$value){
		$this->conditions[$field] = $value;
	}
	
	public abstract function prepare($Q, $pos = 0);
}
