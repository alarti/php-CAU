<?php

require_once("../inc/text.class.php");
require_once("../inc/tracking.class.php");

// texto
$txt =new text();

/*
	En esta página se autentifica el Usuario, el nombre de Usuario debe pertenecer a algún Usuario
	de la base de datos, si se introduce un nombre de usario incorrecto 3 veces se bloquea el usuario para esa IP,
	y si se introduce la clave incorrecta 3 veces también.
*/

// Función que devuelve el numero de IP:
function getRealIP() {
    if($_SERVER['HTTP_X_FORWARDED_FOR'] != '' ) {

        $client_ip = ( !empty($_SERVER['REMOTE_ADDR']) ) ? $_SERVER['REMOTE_ADDR']:
                ( ( !empty($_ENV['REMOTE_ADDR']) ) ? $_ENV['REMOTE_ADDR']:
                "unknown" );

        // los proxys van añadiendo al final de esta cabecera
        // las Prioridades ip que van "ocultando". Para localizar la ip real
        // del Usuario se comienza a mirar por el principio hasta encontrar
        // una direcci�n ip que no sea del rango privado. En caso de no
        // encontrarse ninguna se toma como valor el REMOTE_ADDR

        $entries = split('[, ]', $_SERVER['HTTP_X_FORWARDED_FOR']);

        reset($entries);
        while (list(, $entry) = each($entries)) {
            $entry = trim($entry);
            if ( preg_match("/^([0-9]+\.[0-9]+\.[0-9]+\.[0-9]+)/", $entry, $ip_list) ) {
                $private_ip = array(
                        '/^0\./',
                        '/^127\.0\.0\.1/',
                        '/^192\.168\..*/',
                        '/^172\.((1[6-9])|(2[0-9])|(3[0-1]))\..*/',
                        '/^10\..*/');

                $found_ip = preg_replace($private_ip, $client_ip, $ip_list[1]);

                if ($client_ip != $found_ip) {
                    $client_ip = $found_ip;
                    break;
                }
            }
        }
    }
    else {
        $client_ip = ( !empty($_SERVER['REMOTE_ADDR']) ) ? $_SERVER['REMOTE_ADDR']:
                ( ( !empty($_ENV['REMOTE_ADDR']) ) ? $_ENV['REMOTE_ADDR']:
                "unknown" );
    }

    return $client_ip;
}

// función principal:
$nIncidencia= $_POST['nIncidencia'];
$nombre = $_POST['nombre'];
$contrasena = $_POST['contrasena'];
$empresa = $_POST['empresa'];

if (!$txt->alfabeticoNet($nombre) || !$txt->alfabeticoNet($contrasena) || !$txt->alfabeticoNet($empresa)) {
    include("login.form.php");
    echo "<p id='error_message' align='center' class='Error'>Los caracteres validos son: A-Z.a-z.0-9.@._-</p>";
    exit;
}

$RealIP = getRealIP();
$intentosFallidos = 0;

if ($nombre == '' || $contrasena == '' || $empresa == '') {
    include("login.form.php");
    echo "<p id='error_message' align='center' >Debe rellenar todos los campos</p>";
    exit;
}

// crea la sesión con el nombre proporcionado
session_start();
$_SESSION['nombre'] = $nombre;
$id = session_id();

//obtenemos el id de empresa--> accedemos a su fichero de datos y creamos la conexión con este fichero
require_once("../inc/oracle.class.php");
$bd =new oracle($empresa);

//Generamos la cookie para indicar la empresa en este usuario
setcookie("empresa", $empresa,0,"/","");


// Realiza una consulta a la tabla de Ips bloqueadas
//$sql = "SELECT * FROM bloqueo WHERE ip='".$RealIP."' AND nombreUtilizado LIKE '".$nombre."'";
$sql="SELECT * FROM bloqueo WHERE ip='".$RealIP."' AND ID ='".$id."' AND nombreUtilizado ='".$nombre."'";
$bd->consulta($sql);

$num_row = $bd->get_num_rows($row);

// Si existe algun registro es que ya habia sido bloqueado
if ($num_row != 0) {


    $intentosFallidos = $row['INTENTOS'][0];

    // en caso de que la ip se encuentre, debe de tener un numero de fallos > 3
    if ($intentosFallidos > 2) {

        include ("login.form.php");
        echo "<p id='error_message' align='center' class='error_message_title'><b>Sesión bloqueada por exceso de intentos"
                ." incorrectos, consulte con el administrador</b></p>";
        exit;
    }
}



/*
	Para autentificar a un Usuario, primero se comprueba que existe
	el nombre de Usuario que se proporciona
*/
//$sql = "SELECT AES_DECRYPT(contrasena, '".$contrasena."') FROM Usuarios WHERE nombre LIKE '".$nombre."'";
$sql = "SELECT * FROM Usuarios WHERE nombre LIKE '".$nombre."'";

$bd->consulta($sql);

$num_row = $bd->get_num_rows($resultado);

if ($num_row == 1) {

    /*
		Cuando se comprueba que el Usuario existe, el siguiente paso es
		comprobar que la contraseña proporcionada coincide con la guardada
		en mysql
    */

    // Cuando la contraseña es correcta
    if ($resultado['CONTRASENA'][0] == $contrasena) {


        /*
			Se guarda en oracle la id de la sesión de manera que
			si el nombre de la sesión no conincide con el guardado, no se permite el acceso
        */
        // inserta la contrasena utilizada, encriptada con el numero de sesi�n, para obtener
        // mayor seguridad
        //$sql = "UPDATE Usuarios SET contrasenaTmp = AES_ENCRYPT('".$contrasena."','".$id."') WHERE nombre = '".$nombre."';";
        $sql = "UPDATE Usuarios SET contrasenaTmp = '".$contrasena."' WHERE nombre = '".$nombre."'";


        $bd->consulta($sql);

        /*
			Por último se desbloquea la IP de la tabla de IP's bloqueadas, en caso de que esté
        */
        if ($intentosFallidos > 0) {
            $sql = "DELETE FROM bloqueo WHERE ip='".$RealIP."' AND id='".$id."' AND nombreUtilizado ='".$nombre."'";
            $bd->consulta($sql);
        }

        // registra la entrada del usuario en la tabla trazabilidad
        //para que trague la cookie antes de haber mostrado headers la simulamos
        $_COOKIE['empresa']=$empresa;
        $m = new tracking($RealIP,'Entrada');

        // Se dirige al menu de control o la incidencia en caso de que esta exista
        if (!$nIncidencia=="")
            header("location: ../front/issue.dats.form.php?nIncidencia=".$nIncidencia);
        else 
            header("location: ../front/control.form.php");

    }
    else {
        // si la contraseña es incorrecta

        if ($intentosFallidos == 0) {
            // es la primera vez que se introduce una clave incorrecta

            $intentosFallidos ++;

            $fecha = date('dmYHis');

            $sql = "INSERT INTO bloqueo (ip, id, intentos, nombreUtilizado, contrasenaUtilizada, fecha) VALUES ('".$RealIP."', '".$id."', '".$intentosFallidos."', '".$nombre."', '".$contrasena."', '".$fecha."')";

            $bd->consulta($sql);
        }
        else {
            // ya ha introducido más claves incorrectas
            $intentosFallidos ++;

            $sql = "UPDATE bloqueo SET intentos='".$intentosFallidos."' WHERE ip='".$RealIP."' AND id='".$id."'";

            $bd->consulta($sql);
        }
        include("login.form.php");
        echo "<p id='error_message' align='center' class='Error'>El nombre de Usuario  no corresponde con la"
                ." contraseña</p>";
        echo "<p id='error_message' align='center' class='Error'>El número de intentos fallidos es"
                ." $intentosFallidos, con 3 se bloquea la cuenta</p>";
    }
}
else if ($num_row == 0) {

    // si el nombre de cliente es incorrecto

    if ($intentosFallidos == 0) {
        // es la primera vez que se introduce una clave incorrecta

        $intentosFallidos ++;

        $fecha = date('dmYHis');

        $sql = "INSERT INTO bloqueo (ip, id, intentos, nombreUtilizado, contrasenaUtilizada, fecha) VALUES ('".$RealIP."', '".$id."', '".$intentosFallidos."', '".$nombre."', '".$contrasena."', '".$fecha."')";

        $bd->consulta($sql);
    }
    else {
        // ya ha introducido más claves incorrectas
        $intentosFallidos ++;

        $sql = "UPDATE bloqueo SET intentos='".$intentosFallidos."' WHERE ip='".$RealIP."' AND id='".$id."'";

        $bd->consulta($sql);
    }

    include("login.form.php");
    echo "<p id='error_message' align='center' class='Error'>La cuenta no corresponde con ningún "
            ."Usuario</p>";
    echo "<p id='error_message' align='center' class='Error'>El númmero de intentos fallidos es"
            ." $intentosFallidos, con 3 se bloquea la cuenta</p>";
}


?>
