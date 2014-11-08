<?php
require_once ("../inc/oracle.class.php");
require_once ("../inc/security.class.php");
require_once("../inc/tracking.class.php");
$bd= & new oracle();
$s =& new security();

$permisos=$s->get_privilegios();

if (substr($permisos,0,1)==0){
    echo '<link href="../css/Estilo.css" rel="stylesheet" type="text/css" />';
    echo "<div id='salida' class='Error'>No tiene privilegios suficientes para acceder a esta Incidencia</div>";
    include("stats.banned.php");
    exit;
}

$ip=$_GET['ip'];
$id=$_GET['id'];
$nombreUtilizado=$_GET['nombreUtilizado'];

$sql="Delete from bloqueo where (ip='".$ip."') and (id='".$id."') and (nombreUtilizado='".$nombreUtilizado."')";
$bd->consulta($sql);

$m = new tracking('Usuario '.$nombreUtilizado.' desbloqueado','Usuarios');
header("location: stats.banned.ok.php");
?>