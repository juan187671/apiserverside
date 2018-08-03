<?php
namespace model;
//clase que sirve para dar atributos y comportamiento
//generico a todos los modelos de cualquier proyecto.
use dao\Dao;
use patterns\factory\Factory;
use patterns\factory\FactoryDao;

class Model{
	
	public $id = null;
	public $creado = null;
	public $actualizado = null;
	
	public function __construct(){
		$reflect 	= new \ReflectionClass($this);
		$class 		= $reflect->getShortName();
		$this->Dao = Factory::create(FactoryDao::getInstance(), $class);
	}

	public function save(){
		if($this->id){
			$this->update();
		}
		else{
			$this->create();
		}
	}
	
	public function update(){
		$this->actualizado = date("Y-m-d H:i:s");
		$this->Dao->update($this);
	}

	public function create(){
		$this->creado = date("Y-m-d H:i:s");
		$this->Dao->create($this);
	}
	
	public function delete(){
		$this->Dao->delete($this);
	}
	
	//carga un solo modelo1
	public function load($Q){
		return $this->Dao->load($Q, $this);
	}
	//lista de modelos o rows
	public function fetch($Q){
		return $this->Dao->fetch($Q, $this);
	}
	
	//pasar atributo/valor de objeto a array
	public function toArray(){
		$ret = array();
		foreach(get_object_vars($this) as $key=>$value){
			if(!is_object($value)){
				$ret[$key] = $value;
			}
		}
		return $ret;
	}
}