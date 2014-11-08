<?php

	/*
		Esta clase sirve para guardar posibles movimientos útiles para el programador o para el
		registro de los accesos
	*/

	class tracking {
	
		var $nombre = '';
		//var $id = '';
		//var $clave = '';
                //var $empresa='';

		function tracking($consulta,$DESCRIPCION){

			require_once("security.class.php");
			require_once("oracle.class.php");

			// seguridad
			$s = new security();
			$this->nombre = $s->get_nombre();
			//$this->clave = $s->get_clave();
                        //@todo
			//$this->empresa= $s->get_empresa();
			
			$bd = new oracle();
                        //Sin error en autoincrementa
			$nMov = $bd->autoincrementa('nMov','movimientos');
			$fecha = date('dmYHis');
			$sql = "INSERT INTO movimientos (nMov,fecha,nombre,consulta,DESCRIPCION) VALUES ('$nMov','$fecha','$this->nombre','$consulta','$DESCRIPCION')";
			

			$bd->consulta($sql);
		}
		
	}
?>