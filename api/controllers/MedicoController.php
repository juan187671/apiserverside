<?php
use rest\controller\Rest;
use models\Medico;
use patterns\strategy\Query;
use patterns\strategy\Qand;
use patterns\strategy\QLike;

class MedicoController extends Rest {
	
	public function init() {
		parent::init ();
	}
	
	public function indexAction() {
		try {
			
			$id = $this->getRequest ()->getHeader ( "id" );
			$this->validarUsuarioAutorizado ( $id );
			
			$nombre = $this->getParam("nombre");
			$apellido = $this->getParam("apellido");
			$titulo = $this->getParam("titulo");
			$especialidad = $this->getParam("especialidad");
			$centromedico = $this->getParam("centromedico");
			
			$medico = new Medico ();
			$q = new Query ( $medico );
			
			if (! empty ( $nombre )) {
				$q->add ( new Qand ( 'nombre', $nombre ) );
			}
			
			if (! empty ( $apellido )) {
				$q->add ( new Qand ( 'apellido', $apellido ) );
			}
			
			if (! empty ( $titulo )) {
				$q->add ( new Qand ( 'titulo', $titulo ) );
			}
			
			if (! empty ( $especialidad)) {
				$q->add ( new QLike ( 'especialidad', $especialidad) );
			}
			
			if (! empty ( $centromedico)) {
				$q->add ( new Qand ( 'centromedico', $centromedico) );
			}
			
			$rows = $medico->fetch ( $q );
			$rows= $this->cargarPuntuacionMedico($rows);
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
	
	public function postAction(){
		try {
			
			$id = $this->getRequest ()->getHeader ( "id" );
			$this->validarUsuarioAutorizado ( $id );
			
			$raw = $this->getRequest ()->getRawBody ();
			$raw = \Zend_Json::decode ( $raw );
			
			$nombre = $raw ["nombre"];
			$apellido = $raw ["apellido"];
			$titulo = $raw ["titulo"];
			$especialidad = $raw ["especialidad"];
			$centromedico = $raw ["centromedico"];
			
			if (empty ( $nombre ) || strtoupper($nombre) == "NULL" ) {
				throw new Exception ( "nombre requerido para continuar", 409 );
			}
			
			if (empty ( $apellido ) || strtoupper($apellido) == "NULL" ) {
				throw new Exception ( "apellido requerido para continuar", 409 );
			}
			
			if (empty ( $titulo) || strtoupper($titulo) == "NULL" ) {
				throw new Exception ( "titulo requerido para continuar", 409 );
			}
			
			if (empty ( $especialidad) || strtoupper($especialidad) == "NULL" ) {
				throw new Exception ( "especialidad requerido para continuar", 409 );
			}
			
			if (empty ( $centromedico) || strtoupper($centromedico) == "NULL" ) {
				throw new Exception ( "centromedico requerido para continuar", 409 );
			}
			
			$medico = new Medico ();
			
			$medico->nombre = $nombre;
			$medico->apellido = $apellido;
			$medico->titulo= $titulo;
			$medico->especialidad= $especialidad;
			$medico->centromedico= $centromedico;
			$medico->save ();
			
			$this->getResponse ()->setHttpResponseCode ( 200 );
			$respuesta = array (
					"descripcion" => $medico->toArray (),
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
	
	public function validarUsuarioAutorizado($id) {
		if (empty ( $id )) {
			throw new Exception ( "usuario no autorizado, id debe viajar en el cabezal", 409 );
		}
	}
	
	private function cargarPuntuacionMedico($rows) {
		
		if($rows != null){
			$retorno = array();
			foreach ($rows as $row){
				$id = $row["id"];
				$medico = new Medico();				
				$row["puntacion"] = $medico->cargarPuntuacionMedico($id);
				$retorno[] = $row;
			}
			return $retorno;
		}
		return $rows;
		
	}
	
}