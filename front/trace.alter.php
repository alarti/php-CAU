<?php
require_once("../inc/security.class.php");
require_once("../inc/oracle.class.php");
require_once("../inc/text.class.php");
require_once("../inc/tracking.class.php");

// seguridad
$s =& new security();
$autor = $s->get_nombre();
$privilegios = $s->get_privilegios();

// oracle
$bd =& new oracle();

// texto
$txt =& new text();


$borrar = $_POST['borrar'];
$modificar = $_POST['modificar'];

$nIncidencia = $_POST['nIncidencia'];
$nSeguimiento = $_POST['nSeguimiento'];

//comprobamos que la incidencia no esté cerrada previamente
$sql="Select estado
     from Incidencias
     WHERE nIncidencia='".$nIncidencia."'";
$bd->consulta($sql);
$rows=$bd->get_rows();
$estado_prev=$rows['ESTADO'];

if (substr($estado_prev,0,7)=="Cerrada") {
    $_GET['nIncidencia']=$nIncidencia;
    $_GET['nSeguimiento']=$nSeguimiento;
    include("trace.dats.form.php");
    echo "<div id='salida' class='Error'>No se pueden modificar seguimientos de la incidencia ".$nIncidencia." no se puede modificar. Su estado es CERRADO</div>";
    exit;
}
//comprobamos el autor del seguimiento si no es técnico o admin y no coincide con el usuario que la creo nos echa
$sql="Select autor
     from Seguimientos
     WHERE nSeguimiento='".$nSeguimiento."'";
$bd->consulta($sql);
$rows=$bd->get_rows();
$autor_ant=$rows['AUTOR'];

if ($autor_ant<>$autor && substr($privilegios,3,1)<>'1') {
    $_GET['nIncidencia']=$nIncidencia;
    $_GET['nSeguimiento']=$nSeguimiento;
    include("trace.dats.form.php");
    echo "<div id='salida' class='Error'>El seguimiento ".$nSeguimiento." fue creado por el usuario ".$autor_ant." y no tiene permisos para modificarlo.</div>";
    exit;
}

// comprueba si estamos modificando o borrando seguimiento
if (isset($borrar)) {
    // Borra una Seguimiento

    // comprueba si existen privilegios para escribir Seguimientos WS (5)
    if (substr($privilegios,9,1)=='1') {
        $sql = "DELETE FROM Seguimientos WHERE nSeguimiento=".$nSeguimiento;
        $bd->consulta($sql);
        $m = new tracking('Seguimiento eliminado '.$nSeguimiento,'Seguimiento');
        header("location: trace.delete.ok.php?ref=".$nSeguimiento."&nIncidencia=".$nIncidencia);

    } else {
        $_GET['nIncidencia']=$nIncidencia;
        $_GET['nSeguimiento']=$nSeguimiento;
        include("trace.dats.form.php");
        echo "<div id='salida' class='Error'>No tiene privilegios suficientes para borrar Seguimientos</div>";
        exit;
    }

} else if (isset($modificar)) {
    //comprobamos si el autor del seguimiento es el del login
    //
    // comprueba si existen privilegios para escribir Seguimientos wS (9)
    if (substr($privilegios,9,1)=='1') {
        $titulo= $_POST['titulo'];
        $descripcion = $_POST['descripcion'];

        if (
        !$txt->alfanumerico($titulo) ||
                !$txt->alfanumerico($descripcion)
        ) {
            $_GET['nSeguimiento']=$nSeguimiento;
            $_GET['nIncidencia']=$nIncidencia;
            include("trace.dats.form.php");
            echo "<div id='salida' class='Error'>Los caracteres válidos para el título y descripción son: A-Z.a-z.0-9.áéíóú.àèìòù.âêîôû.@.€.-._. .,.0.1.2.3.4.5.6.7.8.9.</div>";
            exit;
        }

        if ($titulo == '' || $descripcion == '') {
            $_GET['nIncidencia']=$nIncidencia;
            $_GET['nSeguimiento']=$nSeguimiento;
            include("trace.dats.form.php");
            echo "<div id='salida' class='Error'>Debe rellenar todos los campos</div>";
            exit;
        }
        $fecha = date('dmYHis');
        $sql = "UPDATE Seguimientos SET
                    titulo ='".$titulo."',
                    descripcion ='".$descripcion."',
                    autor ='".$autor."',
                    fecha_modif ='".$fecha."'
                WHERE nSeguimiento='".$nSeguimiento."'";
        $bd->consulta($sql);
        $m = new tracking('Seguimiento modificado '.$nSeguimiento,'Seguimiento');
        header("location: trace.alter.ok.php?ref=".$nSeguimiento."& nIncidencia=".$nIncidencia);
    }

    else {
        $_GET['nIncidencia']=$nIncidencia;
        $_GET['nSeguimiento']=$nSeguimiento;
        include("trace.dats.form.php");
        echo "<div id='salida' class='Error'>No tiene privilegios suficientes para modificar Seguimientos</div>";
        exit;
    }
}
?>