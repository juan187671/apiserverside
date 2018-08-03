<?php
    namespace models;

    use model\Model;

    class Medico extends Model {
    	
    	public $id = null;
    	public $nombre = null;
    	public $apellido = null;
    	public $titulo = null;
    	public $especialidad = null;
    	public $centromedico = null;
    	
    	public function cargarPuntuacionMedico($id){
    		return $this->Dao->cargarPuntuacionMedico($id);
    	}
    	
    }