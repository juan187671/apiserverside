<?php
namespace patterns;

class ServiceLocator{
	
	public static function getTranslator($lang = 'es'){
		//Devuelvo Instancia de Lang configurada
		$translate = new \Zend_Translate(
				array(	"adapter"=>'tmx',
						"content"=>PROJECT.DS.'lang.tmx',
						"locale" =>$lang
				)
				);
		return $translate;
	}
	
	public static function getConfig(){
		try{
			$config = new \Zend_Config_Ini(APP.DS.'config.ini', APPLICATION_ENV);
		}
		catch (\Exception $e){
			try{
				$config = new \Zend_Config_Ini(APP.DS.'config.ini', getenv('SERVER_ADDR'));
			}
			catch (\Exception $e){
				//no mostrar $e->getMessage, muestra el path de donde esta buscando config.ini
				throw new \Exception(__CLASS__.':'.get_class($e).' file config.ini not found');
			}
		}
		return $config;
	}
}	