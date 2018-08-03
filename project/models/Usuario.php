<?php
    namespace models;

    use model\Model;

    class Usuario extends Model {
        
        public $id = null;
        public $nombre = null;
        public $apellido = null;
        public $email = null;
        public $documento = null;
        public $telefono = null;
        public $estado = null;

        public function consultaValidarTelefono($telefono){
            return $this->Dao->consultaValidarTelefono($telefono);
        }

        public function consultaValidarEmail($email){
            return $this->Dao->consultaValidarEmail($email);
        }
        
        public function consultaValidarUsuarioActivo($id){
        	return $this->Dao->consultaValidarUsuarioActivo($id);
        }

    }