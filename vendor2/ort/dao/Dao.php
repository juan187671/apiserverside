<?php
namespace dao;
use patterns\ServiceLocator;
class Dao{
	
	public function __construct(){
		$Config = ServiceLocator::getConfig();
		$this->DataAccess = DataAccess::getInstance($Config->database->toArray());
	}
	
	public function fetch($Q, $Model){
		$rows = array();
		try{
			$result = $Q->prepare($Model);
			$Dar = $this->DataAccess->retrieve($result->select.$result->query, $result->binds);
			if($rows = $Dar->fetchAll()){
				return $rows;
			}			
			return $rows;			
		}
		catch(\Exception $e){
			throw new \Exception($e);
		}
	}
	
	public function load($Q, $Model){
		try{
			$result = $Q->prepare($Model);			
			$Dar = $this->DataAccess->retrieve($result->select.$result->query, $result->binds);
			if($row = $Dar->fetch()){
				foreach($row as $atributo=>$valor){
					$Model->$atributo = $valor;
				}
				return true;
			}
			return false;
		}
		catch(\Exception $e){
			
		}
	}
	
	public function delete($Model){
		$binds = array($Model->id);
		$reflect 	= new \ReflectionClass($Model);
		$tabla 		= $reflect->getShortName();
		$tabla 		= strtolower($tabla);
		$sql   = "DELETE FROM $tabla WHERE id = ?";
		$this->DataAccess->execute($sql, $binds);
	}
	
	public function update($Model){
		$binds = array();
		foreach($Model->toArray() as $atributo=>$valor){
			$binds[$atributo] = $valor;
		}

		$reflect 	= new \ReflectionClass($Model);
		$tabla 		= $reflect->getShortName();
		$tabla 		= strtolower($tabla);

		$id = $Model->id;
		$this->DataAccess->update($tabla, $binds, "id='$id'");		
	}
	
	public function create($Model){
		$binds = array();
		foreach($Model->toArray() as $atributo=>$valor){
			//binds['os'] = 'ios'
			$binds[$atributo] = $valor;			
		}
		//$sql = "INSERT INTO tabla ('os','estatus') VALUES (?,?)"
		$reflect 	= new \ReflectionClass($Model);
		$tabla 		= $reflect->getShortName();
		$tabla 		= strtolower($tabla);
		
		//$this->DataAccess->execute($sql, $binds);
		$this->DataAccess->insert($tabla, $binds);
		$Model->id = $this->DataAccess->lastInsertId();
	}
	
}