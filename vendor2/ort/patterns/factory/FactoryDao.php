<?php
namespace patterns\factory;
use patterns\ServiceLocator;
use dao\Dao;

class FactoryDao extends FactoryMethod{

    static private $instance = null;

    private function __construct() {
    	return;
    }

    public function create($class){
        $Config = ServiceLocator::getConfig();
        $class_name = $class;
        if($pos = strripos($class_name,'\\')){
            $name_space = substr($class, 0, $pos);
            $name_space = str_replace('\\', DS, $name_space);
            $name_space = $name_space.DS;
            $class_name = substr($class, $pos + 1);
        }
        $file_name = 'Dao'.$class_name.'.php';
        $path = PROJECT.DS."daos".DS.$file_name;        
        if(\Zend_Loader::isReadable($path)){            
            require_once($path);
            $class_name = 'Dao'.$class;
            return new $class_name();
        }
        return new Dao();
    }

   public static function getInstance(){
        if(!isset(self::$instance)){
            $C = __CLASS__;
            self::$instance = new $C;
        }
        return self::$instance;
    }
}
