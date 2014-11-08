<?php
	// Función que devuelve el numero de IP:
	function getRealIP(){
		if( $_SERVER['HTTP_X_FORWARDED_FOR'] != '' ){
			
			$client_ip = ( !empty($_SERVER['REMOTE_ADDR']) ) ? $_SERVER['REMOTE_ADDR']:
				( ( !empty($_ENV['REMOTE_ADDR']) ) ? $_ENV['REMOTE_ADDR']:
					"unknown" );
   
			// los proxys van añadiendo al final de esta cabecera
			// las Prioridades ip que van "ocultando". Para localizar la ip real
			// del Equipo se comienza a mirar por el principio hasta encontrar
			// una dirección ip que no sea del rango privado. En caso de no
			// encontrarse ninguna se toma como valor el REMOTE_ADDR
   
			$entries = split('[, ]', $_SERVER['HTTP_X_FORWARDED_FOR']);
   
			reset($entries);
			while (list(, $entry) = each($entries)){
				$entry = trim($entry);
				if ( preg_match("/^([0-9]+\.[0-9]+\.[0-9]+\.[0-9]+)/", $entry, $ip_list) ){
					$private_ip = array(
						'/^0\./',
						'/^127\.0\.0\.1/',
						'/^192\.168\..*/',
						'/^172\.((1[6-9])|(2[0-9])|(3[0-1]))\..*/',
						'/^10\..*/');
   
					$found_ip = preg_replace($private_ip, $client_ip, $ip_list[1]);
   
					if ($client_ip != $found_ip){
						$client_ip = $found_ip;
						break;
					}
				}
			}
		}
		else{
			$client_ip = ( !empty($_SERVER['REMOTE_ADDR']) ) ? $_SERVER['REMOTE_ADDR']:
				( ( !empty($_ENV['REMOTE_ADDR']) ) ? $_ENV['REMOTE_ADDR']:
					"unknown" );
		}
  
	return $client_ip;
	}

	require_once("../inc/security.class.php");
	require_once("../inc/oracle.class.php");
	
	// seguridad
	$s =& new security();
	$nombre = $s->get_nombre();
	$clave = $s->get_clave();
	
	//oracle
	$bd =& new oracle();


	// Se registra la salida en la tabla movimientos en consulta guarda la IP
	require_once("../inc/tracking.class.php");
	$m =& new tracking(getRealIP(),'Salida');

	$sql = "UPDATE Usuarios SET contrasenaTmp = '' WHERE nombre = '$nombre'";

	$bd->consulta($sql);
	
	// borra la sesión
	session_register('nombre');
	session_unset();
	session_destroy();
        //setcookie("empresa");

	// vuelve al inicio
	header("location: ../login/login.form.php");
?>