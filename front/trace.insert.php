<?php
require_once("../inc/security.class.php");
require_once("../inc/oracle.class.php");
require_once("../inc/text.class.php");
require_once("../inc/tracking.class.php");

// seguridad
$s =& new security();
$autor = $s->get_nombre();
$clave = $s->get_clave();
$privilegios = $s->get_privilegios();

// oracle
$bd =& new oracle();

// texto
$txt =& new text();


// comprueba si se tienen privilegios para la operación
if (substr($privilegios,9,1)!='1') {
    include("trace.insert.form.php");
    echo "<div id='salida' class='Error'>No tiene suficientes privilegios para insertar nuevos Seguimientos</div>";
    exit;
}
//recogemos los campos
$nIncidencia = $_POST['nIncidencia'];
$nSeguimiento = $bd->autoincrementa('nSeguimiento','Seguimientos');
//comprobamos que la incidencia no esté cerrada previamente
$sql="Select estado
      from Incidencias
      WHERE nIncidencia='".$nIncidencia."'";
$bd->consulta($sql);
$rows=$bd->get_rows();
$estado_prev=$rows['ESTADO'];

if (substr($estado_prev,0,7)=="Cerrada") {
    $_GET['nIncidencia']=$nIncidencia;
    include("issue.dats.form.php");
    echo "<div id='salida' class='Error'>No se pueden añadir seguimientos a la incidencia ".$nIncidencia." no se puede modificar. Su estado es CERRADO</div>";
    exit;
}
$titulo = $_POST['titulo'];
$descripcion=$_POST['descrip'];
$fecha = date('dmYHis');

if (
!$txt->alfanumerico($titulo) ||
        !$txt->alfanumerico($descripcion)
) {
    include("trace.insert.form.php");
    echo "<div id='salida' class='Error'>Los caracteres válidos para el título y descripción son: A-Z.a-z.0-9.áéíóú.àèìòù.âêîôû.@.€.?.¿.!.¡.{.}.[.].<.>-._. .,.0.1.2.3.4.5.6.7.8.9.</div>";
    exit;
}

// Introduce la nueva incidencia
$sql = "INSERT INTO Seguimientos
(nSeguimiento,nIncidencia, titulo, descripcion,autor, fecha, fecha_modif)
VALUES (
'$nSeguimiento',
'$nIncidencia',
'$titulo',
'$descripcion',
'$autor',
'$fecha',
'$fecha'
)";

$bd->consulta($sql);
$m = new tracking('Nuevo Seguimiento '.$nSeguimiento,'Seguimiento');

header("location: trace.insert.ok.php?ref=".$nSeguimiento."&nIncidencia=".$nIncidencia);
?>