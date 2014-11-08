<?php
	require_once("../inc/security.class.php");
	require_once("../inc/oracle.class.php");
	require_once("../inc/text.class.php");
	require_once("../inc/tracking.class.php");
        
	// seguridad
	$s =& new security();
	$nombre = $s->get_nombre();
	$clave = $s->get_clave();
	$privilegios = $s->get_privilegios();
	
	// oracle
	$bd =& new oracle();

	// texto
	$txt =& new text();
	
	/*
	@todo Inserta el administrador en oracle, ademas genera una clave con la que se encriptaran los datos
	en la tabla Equipos y Incidencias
	*/
	$nombreActual = $_POST['nombre'];
	$contrasena1 = $_POST['contrasena1'];
	$contrasena2 = $_POST['contrasena2'];
	$correo = $_POST['correo'];

	$lA = $_POST['lA'];
	$lE = $_POST['lE'];
	$lS = $_POST['lS'];
	$lU = $_POST['lU'];
	$lC = $_POST['lC'];

	$wA = $_POST['wA'];
	$wE = $_POST['wE'];
	$wS = $_POST['wS'];
	$wU = $_POST['wU'];
	$wC = $_POST['wC'];

	if($lA != 1){ $lA = 0; }
	if($lE != 1){ $lE = 0; }
	if($lS != 1){ $lS = 0; }
	if($lU != 1){ $lU = 0; }
	if($lC != 1){ $lC = 0; }

	if($wA != 1){ $wA = 0; }
	if($wE != 1){ $wE = 0; }
	if($wS != 1){ $wS = 0; }
	if($wU != 1){ $wU = 0; }
	if($wC != 1){ $wC = 0; }

	$privilegiosActual = $lA.$wA.$lE.$wE.$lS.$wS.$lU.$wU.$lC.$wC;


	if (
		!$txt->alfabeticoNet($nombreActual) || 
		!$txt->alfabeticoNet($contrasena1) || 
		!$txt->alfabeticoNet($correo)
		){
		include("user.insert.form.php");
		echo "<div id='salida' class='Error'>Los caracteres válidos son: A-Z.a-z.0-9.@._-</div>";
		exit;
	}
	
	// comprueba si se tienen privilegios para la operación
	if (substr($privilegios,3,1)!='1'){
		include("user.insert.form.php");
		echo "<div id='salida' class='Error'>No tiene suficientes privilegios para insertar nuevos Usuarios</div>";
		exit;
	}
	
	if ($nombreActual == '' || $contrasena1 == '' || $contrasena2 == '' || $correo == ''){
		include("user.insert.form.php");
		echo "<div id='salida' class='Error'>Debe rellenar todos los campos</div>";
		exit;
	}
	
	if ($contrasena1 != $contrasena2){
		include("user.insert.form.php");
		echo "<div id='salida' class='Error'>Las contraseñas deben ser iguales</div>";
		exit;
	}
	
	// Comprueba que el usuario no exista ya
	$sql = "SELECT * FROM Usuarios WHERE nombre='".$nombreActual."'";
	$bd->consulta($sql);
	$num_row = $bd->get_num_rows($rows);

	if ($num_row != 0){
		include("user.insert.form.php");
		echo "<div id='salida' class='Error'>El usuario ya existe, elija otro nombre</div>";
		exit;
	}
	
	
	// Introduce el nuevo Usuario
	//$sql = "INSERT INTO Usuarios (nombre, contrasena, correo, clave, privilegios) VALUES ('".$nombreActual."', AES_ENCRYPT('".$contrasena1."','".$contrasena1."'), '".$correo."', AES_ENCRYPT('".$clave."','".$contrasena1."'), AES_ENCRYPT('".$privilegiosActual."','".$clave."'));";
        $sql = "INSERT INTO Usuarios (nombre, contrasena, correo, clave, privilegios) VALUES ('".$nombreActual."','".$contrasena1."', '".$correo."', '".$clave."', '".$privilegiosActual."')";
	$bd->consulta($sql);
	$m = new tracking('Usuario nuevo '.$nombreActual,'Usuarios');
	header("location: user.insert.ok.php?ref=".$nombreActual);
?>