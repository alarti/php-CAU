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

// en este archivo se necesita la id de la sesion, para poder cambaiar contrasenaTmp
$id = $s->get_id();

// oracle
$bd =& new oracle();

// texto
$txt =& new text();

// realizaCambios.php

$borrar = $_POST['borrar'];
$modificar = $_POST['modificar'];

$nombreActual = $_POST['nombre'];
$contrasena0 = $_POST['contrasena0'];

// la contraseña actual se requiere siempre
if ($contrasena0 == '') {
    include("user.dats.form.php");
    echo "<div id='salida' class='Error'>La contraseña actual se requiere siempre</div>";
    exit;
}

// comprueba que la contrasena0 corresponde con el usuario conectado actualmente
//$sql = "SELECT AES_DECRYPT(contrasena,'$contrasena0') FROM Usuarios WHERE nombre = '".$nombre."'";
$sql = "SELECT contrasena FROM Usuarios WHERE nombre = '".$nombre."'";
$bd->consulta($sql);

$row = $bd->get_rows();

if ($row[0] != $contrasena0) {
    include("user.dats.form.php");
    
    echo "<div id='salida' class='Error'>La contraseña actual proporcionada no es correcta</div>";
    exit;
}

// comprueba si estamos modificando o borrando
if (isset($borrar)) {
    // Borra un Usuario de la base de datos

    // comprueba si existen privilegios para escribir Usuarios wE (3)
    if (substr($privilegios,3,1)=='1') {

        // comprueba que queda algun administrador
        //$sql = "SELECT nombre FROM Usuarios WHERE (privilegios = AES_ENCRYPT('1111111111','".$clave."') OR nombre = '".$nombreActual."');";
        $sql = "SELECT nombre FROM Usuarios WHERE (privilegios = '1111111111' OR nombre = '".$nombreActual."')";
        $bd->consulta($sql);

        if ($bd->get_num_rows($rows)>1) {
            $sql = "DELETE FROM Usuarios WHERE nombre='".$nombreActual."'";
            $bd->consulta($sql);
            $m = new tracking('Usuario eliminado '.$nombreActual,'Usuarios');
            header("location: user.delete.ok.php?ref=".$nombreActual);

        } else {
            include("user.dats.form.php");
            echo "<div id='salida' class='Error'>Es el último administrador y no se puede borrar</div>";
            exit;
        }
    } else {
        include("user.dats.form.php");
        echo "<div id='salida' class='Error'>No tiene privilegios suficientes para borrar otros Usuarios</div>";
        exit;

    }

} else if (isset($modificar)) {
    // Modifica un Usuario de la base de datos


    // comprueba si existen privilegios para escribir Usuarios wE (3)
    if (substr($privilegios,3,1)=='1') {

        $nombreActual = $_POST['nombre'];
        $contrasena1 = $_POST['contrasena1'];
        $contrasena2 =  $_POST['contrasena2'];
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

        if($lA != 1) {
            $lA = 0;
        }
        if($lE != 1) {
            $lE = 0;
        }
        if($lS != 1) {
            $lS = 0;
        }
        if($lU != 1) {
            $lU = 0;
        }
        if($lC != 1) {
            $lC = 0;
        }

        if($wA != 1) {
            $wA = 0;
        }
        if($wE != 1) {
            $wE = 0;
        }
        if($wS != 1) {
            $wS = 0;
        }
        if($wU != 1) {
            $wU = 0;
        }
        if($wC != 1) {
            $wC = 0;
        }

        $privilegiosActual = $lA.$wA.$lE.$wE.$lS.$wS.$lU.$wU.$lC.$wC;


        if (
        !$txt->alfabeticoNet($nombreActual) ||
                !$txt->alfabeticoNet($contrasena1) ||
                !$txt->alfabeticoNet($correo)
        ) {
            include("user.dats.form.php");
            echo "<div id='salida' class='Error'>Los caracteres válidos son: A-Z.a-z.0-9.@._-</div>";
            exit;
        }

        if ($contrasena1 == '' || $contrasena2 == '' || $correo == '') {
            include("user.dats.form.php");
            echo "<div id='salida' class='Error'>Debe rellenar todos los campos</div>";
            exit;
        }

        if ($contrasena1 != $contrasena2) {
            include("user.dats.form.php");
            echo "<div id='salida' class='Error'>Las contraseñas deben ser iguales</div>";
            exit;
        }

        /*
				al modificar la contrase�a, es necesario tambien cambiar la contrasenaTmp,
				que debe ser la misma que contrasena, solo que �sta esta encriptada con la id de
				la sesi�n. Y la clave se debe encriptar con la contrase�a nueva
        */

        //$sql = "UPDATE Usuarios SET contrasena = AES_ENCRYPT('".$contrasena1."','".$contrasena1."'), correo = '".$correo."', clave = AES_ENCRYPT('".$clave."','".$contrasena1."'), contrasenaTmp = AES_ENCRYPT('".$contrasena1."','".$id."'), privilegios = AES_ENCRYPT('".$privilegiosActual."','".$clave."') WHERE nombre='".$nombreActual."';";
        $sql = "UPDATE Usuarios SET contrasena = '".$contrasena1."', correo = '".$correo."', clave = '".$clave."', contrasenaTmp ='".$contrasena1."', privilegios ='".$privilegiosActual."' WHERE nombre='".$nombreActual."'";
        $bd->consulta($sql);
        $m = new tracking('Usuario modificado '.$nombreActual,'Usuarios');
        header("location:user.alter.ok.php?ref=".$nombreActual);

    }

    // un usuario puede modificarse a si mismo

    else if ($nombre==$nombreActual) {


        $nombreActual = $_POST['nombre'];
        $contrasena1 = $_POST['contrasena1'];
        $contrasena2 =  $_POST['contrasena2'];
        $correo = $_POST['correo'];

        if (
        !$txt->alfabeticoNet($nombreActual) ||
                !$txt->alfabeticoNet($contrasena1) ||
                !$txt->alfabeticoNet($correo)
        ) {
            include("user.dats.form.php");
            echo "<div id='salida' class='Error'>Los caracteres válidos son: A-Z.a-z.0-9.@._-</div>";
            exit;
        }

        if ($contrasena1 == '' || $contrasena2 == '' || $correo == '') {
            include("user.dats.form.php");
            echo "<div id='salida' class='Error'>Debe rellenar todos los campos</div>";
            exit;
        }

        if ($contrasena1 != $contrasena2) {
            include("user.dats.form.php");
            echo "<div id='salida' class='Error'>Las contraseñas deben ser iguales</div>";
            exit;
        }

        /*
				al modificar la contraseña, es necesario tambien cambiar la contrasenaTmp,
				que debe ser la misma que contrasena, solo que �sta esta encriptada con la id de
				la sesión. Y la clave se debe encriptar con la contrase�a nueva
        */

        //$sql = "UPDATE Usuarios SET contrasena = AES_ENCRYPT('".$contrasena1."','".$contrasena1."'), correo = '".$correo."', clave = AES_ENCRYPT('".$clave."','".$contrasena1."'), contrasenaTmp = AES_ENCRYPT('".$contrasena1."','".$id."') WHERE nombre='".$nombreActual."';";
        $sql = "UPDATE Usuarios SET contrasena ='".$contrasena1."', correo = '".$correo."', clave ='".$clave."', contrasenaTmp = '".$contrasena1."' WHERE nombre='".$nombreActual."'";

        $bd->consulta($sql);
        $m = new tracking('Usuario modificado '.$nombreActual,'Usuarios');
        header("location:./user.alter.ok.php?ref=".$nombreActual);


    } else {
        include("user.dats.form.php");
        echo "<div id='salida' class='Error'>No tiene privilegios suficientes para modificar Usuarios</div>";
        exit;
    }

}
?>