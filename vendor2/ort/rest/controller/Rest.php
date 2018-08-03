<?php
namespace rest\controller;
class Rest extends \Zend_Rest_Controller{
	
	public $Config = null;
	public $status_code_validos = array();
	
	public function init(){
		$this->getResponse()->setHeader('Content-type','application/json');
		//similar a como se ejecuta constructor
		$this->Config = new \Zend_Config_Ini(APP.DS."config.ini",APPLICATION_ENV);
		$this->status_code_validos = $this->cargarStatusCodeValidos();
	}
	
	public function getParam($value,$default = NULL){
		return parent::getParam($value,$default = NULL);
		//return $_REQUEST[$value];
	}
	
	public function indexAction(){
		$this->getResponse()->setHeader('Content-type','application/json');
		$this->getResponse()->setHttpResponseCode(404);
		$result = array("status"=>0,"description"=>"Can not INDEX");
		$response = \Zend_Json::encode($result);
		exit($this->getResponse()->appendBody($response));		
	}
	
	public function getAction(){
		$this->getResponse()->setHeader('Content-type','application/json');
		$this->getResponse()->setHttpResponseCode(404);
		$result = array("status"=>0,"description"=>"Can not GET");
		$response = \Zend_Json::encode($result);
		exit($this->getResponse()->appendBody($response));
	}
	
	public function postAction(){
		$this->getResponse()->setHeader('Content-type','application/json');
		$this->getResponse()->setHttpResponseCode(404);
		$result = array("status"=>0,"description"=>"Can not POST");
		$response = \Zend_Json::encode($result);
		exit($this->getResponse()->appendBody($response));
	}
	
	public function putAction(){
		$this->getResponse()->setHeader('Content-type','application/json');
		$this->getResponse()->setHttpResponseCode(404);
		$result = array("status"=>0,"description"=>"Can not PUT");
		$response = \Zend_Json::encode($result);
		exit($this->getResponse()->appendBody($response));
	}
	
	public function deleteAction(){
		$this->getResponse()->setHeader('Content-type','application/json');
		$this->getResponse()->setHttpResponseCode(404);
		$result = array("status"=>0,"description"=>"Can not DELETE");
		$response = \Zend_Json::encode($result);
		exit($this->getResponse()->appendBody($response));
	}
	
	public function headAction(){
		
	}
	
	public function cargarStatusCodeValidos() {
		// status codes validos para retornar al usuario.
		$status_codes = array(100, 101, 200, 201, 202, 203, 204, 205,
				206, 300, 301, 302, 303, 304, 305, 306,
				307, 400, 401, 402, 403, 404, 405, 406,
				407, 408, 409, 410, 411, 412, 413, 414,
				415, 416, 417, 500, 501, 502, 503, 504,505);
		return $status_codes;
	}
	
	public function evaluarCodeStatus($http_code) {
		// Si el codigo no esta en la lista se asume error de sql
		if( ! in_array( $http_code , $this->status_code_validos) ) {
			$mensaje = 'Los parametros de solicitud no son validos. Los parametros de consulta indican etiquetas de columna no validas.';
			$this->getResponse ()->setHttpResponseCode ( 400 );
			exit ( $this->getResponse ()->appendBody ( \Zend_Json::encode ( array (
					'description' => $mensaje
			) ) ) );
		}
	}
	
}