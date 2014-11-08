<?php
//include("menu.form.php");
require_once("../inc/oracle.class.php");
require_once("../inc/text.class.php");
require_once("../inc/security.class.php");

$s =& new security();

$privilegios = $s->get_privilegios();
$usuario_actual=$s->get_nombre();

// comprueba si se tienen privilegios para la operación
if (substr($privilegios,4,1)!='1') {
    //include("control.form.php");
    echo('<link href="../css/Estilo.css" rel="stylesheet" type="text/css" />');
    echo "<div id='salida' class='Error'>No tiene suficientes privilegios para leer incidencias ajenas</div>";
    exit;
}
// oracle
$bd =& new oracle();

// texto
$txt =& new text();

//recuperamos el filtro en caso de existir
$order=$_GET['order'];
if(!isset($order)) {
    $order='nIncidencia';
}
$sqlorder=" order by ".$order;

//comprobamos si venimos de una busqueda anterior
$sql_previo=base64_decode($_GET['sql_previo']);

if ($sql_previo<>"") {
    $sql=$sql_previo;
    $sql_previo=base64_encode($sql);
}
else {
    //recogemos los valores de la busqueda
    $nIncidencia=$_POST['nIncidencia'];
    $prioridad =$_POST['prioridad'];
    $categoria =$_POST['categoria'];
    $estado =$_POST['estado'];
    $solicitante= $_POST['solicitante'];
    $tecnico_asig= $_POST['tecnico_asig'];
    $titulo =$_POST['titulo'];
    $descripcion =$_POST['descripcion'];
    $alta_date_ini =$_POST['alta_date_ini'];
    $alta_date_fin =$_POST['alta_date_fin'];
    $modif_date_ini =$_POST['modif_date_ini'];
    $modif_date_fin =$_POST['modif_date_fin'];
    $close_date_ini =$_POST['close_date_ini'];
    $close_date_fin =$_POST['close_date_fin'];
    if (substr($privilegios,3,1)<>'1')
            $solicitante=$usuario_actual;

    if (!$txt->alfabeticoNet($titulo) || !$txt->alfabeticoNet($descripcion)) {
        include("issue.find.form.php");
        echo "<div id='salida' class='Error'>Los caracteres válidos son: A-Z.a-z.0-9.@._-</div>";
        exit;
    }

    if($alta_date_ini>$alta_date_fin || $modif_date_ini>$modif_date_fin ||$close_date_ini>$close_date_fin) {
       include("issue.find.form.php");
       echo "<div id='salida' class='Error'>Los intervalos de fechas no son correctos</div>";
       exit;
    }

    $sqlbase="select nIncidencia, prioridad, categoria, estado, titulo, TO_CHAR(fecha_alta, 'DD/MM/YYYY HH24:MI:SS' ),TO_CHAR(fecha_modif, 'DD/MM/YYYY HH24:MI:SS' ),TO_CHAR(fecha_cierre, 'DD/MM/YYYY HH24:MI:SS' ), solicitante, tecnico_asig, email_resp from Incidencias";

    $sqlwhereini=" where (";
    $sqlwherefin=")";
    $sqlnIncidencia="nIncidencia=".$nIncidencia;
    $sqlprioridad="prioridad = '".$prioridad."'";
    $sqlcategoria="categoria = '".$categoria."'";
    $sqlestado="estado = '".$estado."'";
    $sqlsolicitante="solicitante = '".$solicitante."'";
    $sqltecnico_asig="tecnico_asig = '".$tecnico_asig."'";
    $sqltitulo="titulo like '%".$titulo."%'";
    $sqldescripcion="descripcion like '%".$descripcion."%'";
    $sqlfecha_alta="fecha_alta BETWEEN TO_DATE('".$alta_date_ini."','dd/mm/yyyy') and TO_DATE('".$alta_date_fin."','dd/mm/yyyy')";
    $sqlfecha_modif="fecha_modif BETWEEN TO_DATE('".$modif_date_ini."','dd/mm/yyyy') and TO_DATE('".$modif_date_fin."','dd/mm/yyyy')";
    $sqlfecha_cierre="fecha_cierre BETWEEN TO_DATE('".$close_date_ini."','dd/mm/yyyy') and TO_DATE('".$close_date_fin."','dd/mm/yyyy')";

    $sqland= " AND ";
    $filtroant=false;
    $sql=$sqlbase;

//construimos la consulta
    if(isset($nIncidencia) || isset($prioridad) || isset($categoria) || isset($estado) || isset($titulo) || isset($descripcion)|| isset($solicitante)|| isset($tecnico_asig)|| isset($alta_date_ini)|| isset($alta_date_fin)|| isset($modif_date_ini)|| isset($modif_date_fin)|| isset($close_date_ini)|| isset($close_date_fin))
        if($nIncidencia>0 || $prioridad<>-1 || $categoria<>-1 ||$estado<>-1 || $solicitante<>-1 || $tecnico_asig<>-1 || $titulo<>"" || $descripcion <>"" || $alta_date_ini <>""|| $alta_date_fin <>""|| $modif_date_ini <>""|| $modif_date_fin <>""|| $close_date_ini <>""|| $close_date_fin <>"") {
            $sql=$sql.$sqlwhereini;
            if($nIncidencia>0) {
                $sql=$sql.$sqlnIncidencia;
                $filtroant=true;
            }
            if($prioridad<>-1)
                if  ($filtroant) {
                    $sql=$sql.$sqland.$sqlprioridad;
                }
                else {
                    $sql=$sql.$sqlprioridad;
                    $filtroant=true;
                }
            if($categoria<>-1)
                if  ($filtroant) {
                    $sql=$sql.$sqland.$sqlcategoria;
                }
                else {
                    $sql=$sql.$sqlcategoria;
                    $filtroant=true;
                }
            if($estado<>-1)
                if  ($filtroant) {
                    if($estado=="No Cerrada")
                        $sql=$sql.$sqland."estado Not like 'Cerrada%'";
                    else
                        $sql=$sql.$sqland.$sqlestado;
                }
                else {
                    if($estado=="No Cerrada")
                        $sql=$sql."estado Not like 'Cerrada%'";
                    else
                        $sql=$sql.$sqlestado;
                    $filtroant=true;

                }
            if($solicitante<>-1)
                if  ($filtroant) {
                    $sql=$sql.$sqland.$sqlsolicitante;
                }
                else {
                    $sql=$sql.$sqlsolicitante;
                    $filtroant=true;
                }
            if($tecnico_asig<>-1)
                if  ($filtroant) {
                    if($tecnico_asig=="Sin Asignar")
                        $sql=$sql.$sqland."tecnico_asig ='-1'";
                    else
                        $sql=$sql.$sqland.$sqltecnico_asig;
                }
                else {
                    if($tecnico_asig=="Sin Asignar")
                        $sql=$sql."tecnico_asig ='-1'";
                    else
                        $sql=$sql.$sqltecnico_asig;
                    $filtroant=true;
                }
            if($titulo<>"")
                if  ($filtroant) {
                    $sql=$sql.$sqland.$sqltitulo;
                }
                else {
                    $sql=$sql.$sqltitulo;
                    $filtroant=true;
                }
            if($descripcion<>"")
                if  ($filtroant) {
                    $sql=$sql.$sqland.$sqldescripcion;
                }
                else {
                    $sql=$sql.$sqldescripcion;
                    $filtroant=true;
                }
            if($alta_date_ini<>"" && $alta_date_fin<>"")
                if  ($filtroant) {
                    $sql=$sql.$sqland.$sqlfecha_alta;
                }
                else {
                    $sql=$sql.$sqlfecha_alta;
                    $filtroant=true;
                }
          if($modif_date_ini<>"" && $modif_date_fin<>"")
                if  ($filtroant) {
                    $sql=$sql.$sqland.$sqlfecha_modif;
                }
                else {
                    $sql=$sql.$sqlfecha_modif;
                    $filtroant=true;
                }
          if($close_date_ini<>"" && $close_date_fin<>"")
                if  ($filtroant) {
                    $sql=$sql.$sqland.$sqlfecha_cierre;
                }
                else {
                    $sql=$sql.$sqlfecha_cierre;
                    $filtroant=true;
                }

            //cerramos el filtro
            $sql=$sql.$sqlwherefin;
        }

    $sql_previo=base64_encode($sql);
}
//ponemos el orden en las columnas
$sql=$sql.$sqlorder;
$bd->consulta($sql);
include("menu.form.php");
?>

<link href="../css/Estilo.css" rel="stylesheet" type="text/css" />

<script src="../SpryAssets/SpryCollapsiblePanel.js" type="text/javascript"></script>
<link href="../SpryAssets/SpryCollapsiblePanel.css" rel="stylesheet" type="text/css" />
<table width="100%" border="0">
    <tr>
        <td width="100%" height="31" class="CollapsiblePanelTab">HelpDesk > <a href="issue.find.form.php" class="Utiles">Buscar</a></td>
    </tr>
    <tr>
        <td><div id="CollapsiblePanel1" class="CollapsiblePanel">
                <div class="CollapsiblePanelTab">Resultados de la Búsqueda:</div>
                <div class="CollapsiblePanelContent">
                    <div>
                        <div>
                            <table width='100%' border='0' align="center">
<tr>
                                    <td colspan="11" bgcolor="#CCCCCC" >&nbsp;</td>
                                </tr>
                                <tr>
                                    <td width="5%" bgcolor="#CCCCCC" ><div align="center"><strong><a href="issue.find.php?order=nIncidencia&sql_previo=<?php echo $sql_previo ?>" class="Utiles">Nº</a></strong></div></td>
                                    <td width="5%" bgcolor="#CCCCCC"><div align="center"><strong><a href="issue.find.php?order=prioridad&sql_previo=<?php echo $sql_previo ?>" class="Utiles">Prioridad</a></strong></div></td>
                                    <td width="10%" bgcolor="#CCCCCC"><div align="center"><strong><a href="issue.find.php?order=categoria&sql_previo=<?php echo $sql_previo ?>" class="Utiles">Categoría</a></strong></div></td>
                                    <td width="5%" bgcolor="#CCCCCC"><div align="center"><strong><a href="issue.find.php?order=estado&sql_previo=<?php echo $sql_previo ?>" class="Utiles">Estado</a></strong></div></td>
                                    <td width="25%" bgcolor="#CCCCCC"><div align="center"><strong><a href="issue.find.php?order=titulo&sql_previo=<?php echo $sql_previo ?>" class="Utiles">Título</a></strong></div></td>
                                  <td width="5%" bgcolor="#CCCCCC"><div align="center"><strong><a href="issue.find.php?order=fecha_alta&sql_previo=<?php echo $sql_previo ?>" class="Utiles">Fecha de Alta</a></strong></div></td>
                                  <td width="5%" bgcolor="#CCCCCC"><div align="center"><strong><a href="issue.find.php?order=fecha_alta&sql_previo=<?php echo $sql_previo ?>" class="Utiles">Ultima Modif.</a></strong></div></td>
                                  <td width="5%" bgcolor="#CCCCCC"><div align="center"><strong><a href="issue.find.php?order=fecha_alta&sql_previo=<?php echo $sql_previo ?>" class="Utiles">Fecha de Cierre</a></strong></div></td>
                                    <td width="10%" bgcolor="#CCCCCC"><div align="center"><strong><a href="issue.find.php?order=solicitante&sql_previo=<?php echo $sql_previo ?>" class="Utiles">Solicitante</a></strong></div></td>
                                  <td width="10%" bgcolor="#CCCCCC"><div align="center"><strong><a href="issue.find.php?order=tecnico_asig&sql_previo=<?php echo $sql_previo ?>" class="Utiles">Técnico Asignado</a></strong></div></td>
                                    <td width="25%" bgcolor="#CCCCCC"><div align="center"><strong><a href="issue.find.php?order=email_resp&sql_previo=<?php echo $sql_previo ?>" class="Utiles">Email</a></strong></div></td>
                              </tr>
                                <?php
                                //creo e inicializo la variable para contar el número de filas
                                $num_fila = 0;

                                while ($row = $bd->get_rows()) {
                                    //pares e impares de diferente color
                                    if ($num_fila%2==0)
                                        $estilo_fila="align='center'";
                                    else
                                        $estilo_fila="bgcolor=#A2CADD align='center'";
                                    //diferenciamos por colores la prioridad de las incidencias
                                    if($row['PRIORIDAD']=="Urgente")
                                        $estilo_celda="bgcolor=#D77171 align='center'";
                                    else
                                    if($row['PRIORIDAD']=="Alta")
                                        $estilo_celda="bgcolor=#ffcc66 align='center'";
                                    else
                                        $estilo_celda="bgcolor=#66cc66 align='center'";
                                    //añadimos el enlace para modificar las incidencias
                                    
                                    //los detalles con link a cada incidencia
                                    echo "<tr>
                                                      <td ".$estilo_fila.">".$row[0]."</td>
                                                      <td ".$estilo_celda.">".$row[1]."</td>
                                                      <td ".$estilo_fila.">".$row[2]."</td>
                                                      <td ".$estilo_fila.">".$row[3]."</td>
                                                      <td ".$estilo_fila."<div><a href='issue.dats.form.php?nIncidencia=".$row[0]."'>".$row[4]."</div></td>
                                                      <td ".$estilo_fila.">".$row[5]."</td>
                                                      <td ".$estilo_fila.">".$row[6]."</td>
                                                      <td ".$estilo_fila.">".$row[7]."</td>
                                                      <td ".$estilo_fila.">".$row[8]."</td>
                                                      <td ".$estilo_fila.">".$row[9]."</td>
                                                      <td ".$estilo_fila.">".$row[10]."</td>
                                                      </tr>";

                                    //aumentamos en uno el número de filas
                                    $num_fila++;
                                }
                                ?>
                                <tr>
                                    <td colspan="11" bgcolor="#CCCCCC" >&nbsp;</td>
                                </tr>
                            </table>
                      </div>
                    </div>
                </div>
            </div></td>
    </tr>
</table>
<script type="text/javascript">
    <!--
    var CollapsiblePanel1 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel1");
    //-->
</script>
