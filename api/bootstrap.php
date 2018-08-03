<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap{
	
	protected function _initEnv(){
		//despues que se ejecuto archivo config.ini...
		$this->bootstrap("frontcontroller");
		$FrontController = Zend_Controller_Front::getInstance();
		$restRoute = new Zend_Rest_Route($FrontController);
		//defino que es una app de tipo REST (el ruteo se hace diferente)
		$FrontController->getRouter()->addRoute("default",$restRoute);
		//buscar si hay archivo de configuracion de errores
		$file = "error_".APPLICATION_ENV;
		$path = APP.DS.$file.'.php';
		if(file_exists($path)){
			require_once $path;
		}		
	}	
}