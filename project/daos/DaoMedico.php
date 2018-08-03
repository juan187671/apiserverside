<?php
	use dao\Dao;
	
	class DaoMedico extends Dao {
		
		public function cargarPuntuacionMedico($id){
			$retorno = -1;
			$sql = "SELECT puntuacion FROM consulta WHERE medicoId=$id";
			$DAR = $this->DataAccess->retrieve($sql, array());
			$rows = $DAR->fetchAll();
			if($rows != null){
				$acumulador = 0;
				$nroConsultas = 0;
				foreach ($rows as $row){
					$nroConsultas = $nroConsultas + 1;
					$acumulador = $acumulador + $row[puntuacion];
				}
				$retorno = $acumulador / $nroConsultas;
				$retorno = round($retorno);
			}
			return $retorno;
		}
	}