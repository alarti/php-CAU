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


// comprueba si se tienen privilegios para la operación
if (substr($privilegios,5,1)!='1') {
    include("issue.insert.form.php");
    echo "<div id='salida' class='Error'>No tiene suficientes privilegios para insertar nuevas Incidencias</div>";
    exit;
}
//recogemos los campos
$nIncidencia = $bd->autoincrementa('nIncidencia','incidencias');

$prioridad = $_POST['prioridad'];
$categoria = $_POST['categoria'];
$estado = $_POST['estado'];
$tecnico_asig = $_POST['tecnico_asig'];

if(!isset($estado))
    $estado="Nueva";
if(!isset($tecnico_asig))
    $tecnico_asig='-1';
else if ($estado=="Nueva")
    $estado="Asignada";

$titulo = $_POST['titulo'];
$descripcion=$_POST['descrip'];
$fecha_alta = date('dmYHis');
$fecha_modif = date('dmYHis');
$fecha_cierre="";
$solicitante=$nombre;
$email_resp=$_POST['email'];
if (
!$txt->alfanumerico($titulo) ||
        !$txt->alfanumerico($descripcion) ||
        !$txt->alfabeticoNet($email_resp)
) {
    $_GET['nIncidencia']=$nIncidencia;
    include("issue.insert.form.php");

    echo "<div id='salida' class='Error'>Los caracteres válidos para el título y descripción son: A-Z.a-z.0-9.áéíóú.àèìòù.âêîôû.@.€.-._. .,.0.1.2.3.4.5.6.7.8.9.</div>";
    echo "<div id='salida' class='Error'>Los caracteres válidos para el email son: A-Z.a-z.0-9.@._-</div>";
    exit;
}
if ($estado!="Nueva" && $tecnico_asig==-1) {
    $_GET['nIncidencia']=$nIncidencia;
    include("issue.insert.form.php");
    echo "<div id='salida' class='Error'>El estado propuesto necesita un Técnico Asignado </div>";
    exit;
}
//si se asigna un técnico pasa a estado=asinada
if ($estado=="Nueva" && $tecnico_asig!=-1) {
    $estado="Asignada";
}

// Introduce la nueva incidencia
$sql = "INSERT INTO Incidencias
(nIncidencia, prioridad, categoria, estado, titulo, descripcion, fecha_alta, fecha_modif, fecha_cierre, solicitante, tecnico_asig, email_resp)
VALUES (
'$nIncidencia',
'$prioridad',
'$categoria',
'$estado',
'$titulo',
'$descripcion',
'$fecha_alta',
'$fecha_modif',
'$fecha_cierre',
'$solicitante',
'$tecnico_asig',
'$email_resp'
)";

$bd->consulta($sql);

$m = new tracking('Nueva Incidencia '.$nIncidencia,'Incidencias');

header("location: issue.insert.ok.php?ref=".$nIncidencia);
?>