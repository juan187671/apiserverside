<?php
namespace dao;

class DataAccess extends \Zend_Db_Adapter_Mysqli{
	
	static private $instance = null;
	
	//singleton conectarse por unica vez a db
	public static function getInstance($params){
		
		if(!isset(self::$instance)){
			$C = __CLASS__;
			//invoco constructor de padre
			self::$instance = new $C($params);
		}
		return self::$instance;		
	}
	
	public function execute($sql, $binds = array()){		
		$Dar = new DataAccessResult($this, $sql);
		$Dar->_execute($binds);
	}
	
	public function retrieve($sql, $binds = array()){
		$Dar = new DataAccessResult($this, $sql);
		$Dar->_execute($binds);		
		return $Dar;
	}
}
