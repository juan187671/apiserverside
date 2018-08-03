<?php
//http://www.php-fig.org/psr/psr-4/
class Autoloader{

	public static function CargarZend($class){
		$file_name = "";
		$name_space = "";
		$dir_name   = "";
		$class_name = "";
		if($pos = strripos($class,"_")){
			//por ejemplo si viene Zend_Validation_Email
			//me quedo con Email.
			$class_name = substr($class, $pos + 1);
			//me quedo con Zend_Validation
			$name_space = substr($class, 0, $pos);
			$dir_name 	= str_replace("_", DS, $name_space);
			//despues de esto quedaria
			//Zend/Validation
		}
		$dir_name .= DS."$class_name.php";
		//despues de esto quedaria
		//Zend/Validation/Email.php		
		$path 	 	= VENDOR.DS.$dir_name;
		if(file_exists($path)){
			require_once $path;
		}
	}
	
	public static function CargarClase($class){
		$file_name = "";
		$name_space = "";
		$dir_name   = "";
		if($pos = strripos($class,"\\")){
			$class_name = substr($class, $pos + 1);
			$name_space = substr($class, 0, $pos);
			$dir_name 	= str_replace("\\", DS, $name_space);
		}
		$dir_name .= DS."$class_name.php";
		$path = PROJECT.DS.$dir_name;
		if(file_exists($path)){
			require_once $path;
		}
	}
	
	public static function CargarPDP($class){
		$file_name = "";
		$name_space = "";
		$dir_name   = "";
		if($pos = strripos($class,"\\")){
			$class_name = substr($class, $pos + 1);
			$name_space = substr($class, 0, $pos);
			$dir_name 	= str_replace("\\", DS, $name_space);
		}
		$dir_name .= DS."$class_name.php";
		$path = VENDOR.DS.'ort'.DS.$dir_name;
		if(file_exists($path)){
			require_once $path;
		}
	}
}

spl_autoload_register('Autoloader::CargarZend');
spl_autoload_register('Autoloader::CargarPDP');
spl_autoload_register('Autoloader::CargarClase');