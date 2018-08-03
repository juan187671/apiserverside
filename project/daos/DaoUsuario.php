<?php
use dao\Dao;

class DaoUsuario extends Dao {

	public function consultaValidarTelefono($telefono){
		$sql = "SELECT 1 FROM usuario WHERE telefono=$telefono";
		$DAR = $this->DataAccess->retrieve($sql, array());
		return $DAR->fetchAll();
	}

	public function consultaValidarEmail($email){
		$sql = "SELECT 1 FROM usuario WHERE email='$email'";
		$DAR = $this->DataAccess->retrieve($sql, array());
		return $DAR->fetchAll();
	}
	
	public function consultaValidarUsuarioActivo($id){
		$sql = "SELECT 1 FROM usuario WHERE estado='ACTIVO' AND id='$id'";
		$DAR = $this->DataAccess->retrieve($sql, array());
		return $DAR->fetchAll();
	}

}