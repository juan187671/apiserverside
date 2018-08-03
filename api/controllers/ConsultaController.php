<?php
use rest\controller\Rest;
use models\Consulta;
use models\Usuario;
use patterns\strategy\Query;
use patterns\strategy\Qand;

class ConsultaController extends Rest {
	
	public function init() {
		parent::init ();
	}

	public function indexAction() {
		try {
			
			$id = $this->getRequest ()->getHeader ( "id" );
			$this->validarUsuarioAutorizado ( $id );
			
			$usuarioid = $this->getParam ( "usuarioid" );
			
			if (empty($usuarioid) || strtoupper($usuarioid) == "NULL" ) {
				throw new Exception ( "usuarioid requerido para continuar", 409 );
			}
			
			$this->validarUsuarioActivo($usuarioid);
			
			$consulta = new Consulta ();
			$q = new Query ( $consulta );
			
			$q->add ( new Qand ( 'usuarioid', $usuarioid ) );
			
			$rows = $consulta->fetch ( $q );
			$this->getResponse ()->setHttpResponseCode ( 200 );
			$respuesta = array (
					"descripcion" => $rows,
					"estatus" => 0
			);
			exit ( $this->getResponse ()->appendBody ( \Zend_Json::encode ( $rows ) ) );
		} catch ( \Exception $e ) {
			$http_code = ($e->getCode ()) ? $e->getCode () : 500;
			$this->getResponse ()->setHttpResponseCode ( $http_code );
			exit ( $this->getResponse ()->appendBody ( \Zend_Json::encode ( array (
					'description' => $e->getMessage ()
			) ) ) );
		}
	}
	
	public function postAction() {
		try {
			
			$id = $this->getRequest ()->getHeader ( "id" );
			$this->validarUsuarioAutorizado ( $id );
			
			$raw = $this->getRequest ()->getRawBody ();
			$raw = \Zend_Json::decode ( $raw );
			
			$usuarioid = $raw ["usuarioid"];
			$medicoid = $raw ["medicoid"];
			$fecha = $raw ["fecha"];
			$hora = $raw ["hora"];
			
			if (empty ( $usuarioid) || strtoupper($usuarioid) == "NULL" ) {
				throw new Exception ( "usuarioid requerido para continuar", 409 );
			}
			
			if (empty ( $medicoid) || strtoupper($medicoid) == "NULL" ) {
				throw new Exception ( "medicoid requerido para continuar", 409 );
			}
			
			if (empty ( $fecha) || strtoupper($fecha) == "NULL" || !$this->validarFormatoFecha($fecha) ) {
				throw new Exception ( "fecha requerido para continuar. Formato yyyy-mm-dd", 409 );
			}
			
			if (empty ( $hora) || strtoupper($hora) == "NULL" || !$this->validarFormatoHora($hora) ) {
				throw new Exception ( "hora requerido para continuar. Formato hh:mm:ss", 409 );
			}
			
			$this->validarUsuarioActivo($id);
			
			$this->validarConsulta($usuarioid,$medicoid,$fecha,$hora);
			
			$consulta = new consulta ();
			$consulta->usuarioid = $usuarioid;
			$consulta->medicoid = $medicoid;
			$consulta->fecha= $fecha;
			$consulta->hora = $hora;			
			$consulta->save();
			
			$this->getResponse ()->setHttpResponseCode ( 200 );
			$respuesta = array (
					"descripcion" => $consulta->toArray (),
					"estatus" => 0
			);
			exit ( $this->getResponse ()->appendBody ( \Zend_Json::encode ( $respuesta ) ) );
		} catch ( \Exception $e ) {
			$http_code = ($e->getCode ()) ? $e->getCode () : 500;
			
			$this->evaluarCodeStatus($http_code);
			
			$this->getResponse ()->setHttpResponseCode ( $http_code );
			exit ( $this->getResponse ()->appendBody ( \Zend_Json::encode ( array (
					'description' => $e->getMessage ()
			) ) ) );
		}
	}
	
	private function validarUsuarioAutorizado($id) {
		if (empty ( $id )) {
			throw new Exception ( "usuario no autorizado, id debe viajar en el cabezal", 409 );
		}
	}
	
	private function validarUsuarioActivo($id){
		if(empty($id)) {
			throw new Exception ( "usuario no autorizado, id debe viajar en el cabezal", 409 );
		}
		
		$usuario = new Usuario();
		$rows = $usuario->consultaValidarUsuarioActivo($id);
		if($rows == null || empty($rows) || count($rows) == 0){
			throw new Exception ( "usuario no autorizado.", 409 );
		}		
	}
	
	private function validarConsulta($usuarioid, $medicoid, $fecha, $hora) {
		
		$consulta = new Consulta();
		$q = new Query ( $consulta );
		$q->add ( new Qand ( 'usuarioid', $usuarioid ) );
		$q->add ( new Qand ( 'medicoid', $medicoid ) );
		$q->add ( new Qand ( 'fecha', $fecha ) );
		$q->add ( new Qand ( 'hora', $hora ) );
		if( $consulta->load ( $q, $consulta )) {
			throw new Exception ( "recurso ya existe", 409 );
		}
	}
	
	private function validarFormatoFecha($fecha){
		if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$fecha)) {
			return true;
		} else {
			return false;
		}
	}
	
	private function validarFormatoHora($fecha){
		if (preg_match("/^[0-9]{2}:[0-5][0-9]:[0-5][0-9]$/",$fecha)) {
			return true;
		} else {
			return false;
		}
	}	
}