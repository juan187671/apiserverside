<?php
use rest\controller\Rest;
use models\Usuario;
use patterns\strategy\Query;
use patterns\strategy\Qand;

class UsuarioController extends Rest {
	
	public $estados_validos = array ();
	
	public function init() {
		parent::init ();
		$this->estados_validos = array (
				"ACTIVO",
				"INACTIVO" 
		);
	}

	public function indexAction() {
		try {

			$nombre = $this->getParam("nombre");
			$documento = $this->getParam("documento");
			$telefono = $this->getParam("telefono");
			$email = $this->getParam("email");

			$usuario = new Usuario ();
			$q = new Query ( $usuario );
			
			if (! empty ( $nombre )) {
				$q->add ( new Qand ( 'nombre', $nombre ) );
			}
			
			if (! empty ( $documento )) {
				$q->add ( new Qand ( 'documento', $documento ) );
			}

			if(! empty ( $telefono )) {
				$q->add ( new Qand ( 'telefono', $telefono ) );
			}

			if(! empty ( $email )) {
				$q->add ( new Qand ( 'email', $email ) );
			}
			
			$rows = $usuario->fetch ( $q );
			$this->getResponse ()->setHttpResponseCode ( 200 );
			$respuesta = array (
					"descripcion" => $rows,
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
	
	public function getAction() {
		try {
			
			$id = $this->getRequest ()->getHeader ( "id" );
			$this->validarUsuarioAutorizado ( $id );
			
			$id = $this->getParam ( "id" );
			if (empty ( $id )) {
				throw new Exception ( "nombre requerido para continuar", 409 );
			}
			
			$usuario = new Usuario ();
			$q = new Query ( $usuario );
			
			if (! empty ( $id )) {
				$q->add ( new Qand ( 'id', $id ) );
			}
			
			$row = $usuario->fetch ( $q );
			
			if (! $row) {
				throw new Exception ( "Recurso no encontrado", 404 );
			}
			
			// envio respuesta al cliente.
			$this->getResponse ()->setHttpResponseCode ( 200 );
			$respuesta = array (
					"descripcion" => $row,
					"estatus" => 0 
			);
			exit ( $this->getResponse ()->appendBody ( \Zend_Json::encode ( $respuesta ) ) );
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
			
			$raw = $this->getRequest ()->getRawBody ();
			$raw = \Zend_Json::decode ( $raw );
			
			$nombre = $raw ["nombre"];
			$apellido = $raw ["apellido"];
			$email = $raw ["email"];
			$documento = $raw ["documento"];
			$telefono = $raw ["telefono"];
			$estado = $raw ["estado"];
			
			if (empty ( $nombre ) || strtoupper($nombre) == "NULL") {
				throw new Exception ( "nombre requerido para continuar", 409 );
			}
			
			if (empty ( $apellido ) || strtoupper($apellido) == "NULL" ) {
				throw new Exception ( "apellido requerido para continuar", 409 );
			}
			
			if (empty ( $email ) || strtoupper($email) == "NULL" ) {
				throw new Exception ( "email requerido para continuar", 409 );
			}
			
			if (empty ( $documento ) || strtoupper($documento) == "NULL" ) {
				throw new Exception ( "documento requerido para continuar", 409 );
			}
			
			if (empty ( $telefono ) || strtoupper($telefono) == "NULL" ) {
				throw new Exception ( "telefono requerido para continuar", 409 );
			}
			
			if (empty ( $estado ) || strtoupper($estado) == "NULL" ) {
				throw new Exception ( "estado requerido para continuar", 409 );
			}
			
			$this->validarEstado ( $estado );
			$this->validarEmail ( $email );
			$this->validarTelefono ( $telefono );
			
			$usuario = new Usuario ();
			
			$usuario->nombre = $nombre;
			$usuario->apellido = $apellido;
			$usuario->email = $email;
			$usuario->documento = $documento;
			$usuario->telefono = $telefono;
			$usuario->estado = $estado;
			$usuario->save ();
			
			$this->getResponse ()->setHttpResponseCode ( 200 );
			$respuesta = array (
					"descripcion" => $usuario->toArray (),
					"estatus" => 0 
			);
			exit ( $this->getResponse ()->appendBody ( \Zend_Json::encode ( $respuesta ) ) );
		} catch ( \Exception $e ) {
			$http_code = ($e->getCode ()) ? $e->getCode () : 409;
			
			$this->evaluarCodeStatus($http_code);
			
			$this->getResponse ()->setHttpResponseCode ( $http_code );
			exit ( $this->getResponse ()->appendBody ( \Zend_Json::encode ( array (
					'description' => $e->getMessage (),
					"estatus" => 409 
			) ) ) );
		}
	}
	
	public function putAction() {
		try {
			
			$id = $this->getRequest ()->getHeader ( "id" );
			$this->validarUsuarioAutorizado ( $id );
			
			$raw = $this->getRequest ()->getRawBody ();
			$raw = \Zend_Json::decode ( $raw );
			
			$id = $raw ["id"];
			$nombre = $raw ["nombre"];
			$apellido = $raw ["apellido"];
			$email = $raw ["email"];
			$documento = $raw ["documento"];
			$telefono = $raw ["telefono"];
			$estado = $raw ["estado"];
			
			if ( empty($id) || strtoupper($id) == "NULL" ) {
				throw new Exception ( "id requerido para continuar", 409 );
			}
			
			if (! empty ( $estado )) {
				$this->validarEstado ( $estado );
			}
			
			if (! empty ( $email )) {
				$this->validarEmail ( $email );
			}
			
			if (! empty ( $telefono )) {
				$this->validarTelefono ( $telefono );
			}
			
			$usuario = new Usuario ();
			$usuario->id = $id;
			$q = new Query ( $usuario );
			$q->add ( new Qand ( 'id', $id ) );
			if ($usuario->load ( $q )) {
				$usuario->id = $id;
				$usuario->nombre = $nombre;
				$usuario->apellido = $apellido;
				$usuario->email = $email;
				$usuario->documento = $documento;
				$usuario->telefono = $telefono;
				$usuario->estado = $estado;
				$usuario->save ();
			} else {
				throw new Exception ( "el recurso no existe", 409 );
			}
			
			$this->getResponse ()->setHttpResponseCode ( 200 );
			$respuesta = array (
					"descripcion" => $usuario->toArray (),
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
	
	public function validarEstado($estado) {
		if (! in_array ( $estado, $this->estados_validos )) {
			throw new Exception ( "Estado invalido (ACTIVO,INACTIVO)", 409 );
		}
	}
	
	public function validarUsuarioAutorizado($id) {
		if (empty ( $id )) {
			throw new Exception ( "usuario no autorizado, id debe viajar en el cabezal", 409 );
		}
	}
	
	public function validarEmail($email) {
		$usuario = new Usuario ();
		$row = $usuario->consultaValidarEmail ( $email );
		if ($row) {
			throw new Exception ( "email ya registrado", 409 );
		}
	}
	
	public function validarTelefono($telefono) {
		$usuario = new Usuario ();
		$row = $usuario->consultaValidarTelefono ( $telefono );
		if ($row) {
			throw new Exception ( "telefono ya registrado", 409 );
		}
	}	
}