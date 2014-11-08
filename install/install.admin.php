<?php
//ORACLE
require_once("../inc/text.class.php");
require_once("../inc/oracle.class.php");

//Recuperamos la empresa del input oculto en creaadmin
$empresa=trim($_POST["empresa"]);

$bd = new oracle($empresa);

// Creamos la clase de control para evitar caracteres no permitidos.
$txt = new text();

/*
	Inserta el administrador en la bbdd, ademas genera una clave con la que se encriptaran los datos 
	en la tabla Equipos e Incidencias
*/
$nombre = $_POST['nombre'];
$contrasena = $_POST['contrasena'];
$contrasena2 = $_POST['contrasena2'];
$correo = $_POST['correo'];
$correo2 = $_POST['correo2'];
$clave = $_POST['clave'];

if ($nombre == '' || $contrasena == '' || $correo == ''|| $clave == '') {
    include("install.admin.form.php");
    echo "<div id='salida' class='Error'>Debe rellenar todos los campos</div>";
    exit;
}
if ($contrasena != $contrasena2) {
    include("install.admin.form.php");
    echo "<div id='salida' class='Error'>Las constraseñas no conciden</div>";
    exit;
}
if ($correo != $correo2) {
    include("install.admin.form.php");
    echo "<div id='salida' class='Error'>Las direcciones de email no conciden. Debe facilitar un email válido para poder recuperar su contraseña</div>";
    exit;
}
if (
!$txt->alfabeticoNet($nombre) || 
        !$txt->alfabeticoNet($contrasena) ||
        !$txt->alfabeticoNet($correo) ||
        !$txt->alfabeticoNet($clave)
) {
    include("install.admin.form.php");
    echo "<p align='center' class='Error'>Los caracteres válidos son: A-Z.a-z.0-9.@._-</p>";
    exit;
}

//Comprobamos si el login ya existe en la tabla usuarios
$sql="SELECT * FROM usuarios WHERE nombre='".$nombre."'";
$bd->consulta($sql);

$num_rows = $bd->get_num_rows();
if ($num_rows > 0) {
    include("install.admin.form.php");
    echo "<p align='center' class='Error'>El usuario ".$nombre." ya existe. Seleccione otro nombre</p>";
    exit;
}

/*
	inserta el nombre de Equipo, la contraseña y la clave con la que
	se encriptaran los datos de Equipos y Incidencias. Todos los privilegios a 1
*/

$sql = "INSERT INTO Usuarios (nombre, contrasena, correo, clave, privilegios) VALUES ('".$nombre."','".$contrasena."','".$correo."','".$clave."','1111111111')";
$bd->consulta($sql);
header("location: ./install.ok.php");
?>