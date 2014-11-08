<?php

require_once("../inc/security.class.php");

// Seguridad
$s =& new security();
$NOMBRE_USUARIO = $s->get_nombre();
$privilegios = $s->get_privilegios();

//CABECERA Y ESTILOS

//MENU: CENTRAL

if (substr($privilegios,2,2)<>'11') {
    //echo "<p id='error_message' align='center' class='error_message_title'><b>USUARIO BÁSICO</b></p>";
    include("issue.insert.form.php");
    exit;
}

include("menu.form.php");
require_once("../inc/oracle.class.php");

$bd = new oracle();
?>
<script type='text/JavaScript' src='../js/scw.js'></script>
<script src="../SpryAssets/SpryCollapsiblePanel.js" type="text/javascript"></script>
<link href="../SpryAssets/SpryCollapsiblePanel.css" rel="stylesheet" type="text/css" />
<div id="Central" class="CollapsiblePanel">
    <div class="CollapsiblePanelTab">Control Central</div>
    <div class="CollapsiblePanelContent">
        <table width="100%" border="0" align="center">
            <tr>
                <td width="100%">
                    <table width='100%' border='0' align="center">
                        <?php

                        $sql = "SELECT * FROM Usuarios";
                        $bd->consulta($sql);
                        $num_row = $bd->get_num_rows();
                        ?>
                        <tr>
                            <td class="Estilo">El número de Usuarios es: <?php echo $num_row; ?></td>
                        </tr>
                        <?php
                        $sql = "SELECT * FROM Incidencias";
                        $bd->consulta($sql);
                        $num_row = $bd->get_num_rows();
                        ?>
                        <tr>
                            <td class="Estilo">El número de Incidencias es: <?php echo $num_row; ?></td>
                        </tr>
                        <?php /*
	$sql = "SELECT * FROM Equipos";
	$bd->consulta($sql);
	$num_row = $bd->get_num_rows();
?>
          <tr>
            <td class="Estilo">El número de Equipos es: <?php echo $num_row; ?></td>
          </tr>
          <?php*/
                        $sql = "SELECT * FROM bloqueo";
                        $bd->consulta($sql);
                        $num_row = $bd->get_num_rows();
                        ?>
                        <tr>
                            <td><span class="Estilo">El número de IP's bloqueadas es: <?php echo $num_row; ?></span></td>
                        </tr>
                        <?php
                        $sql = "SELECT NMOV FROM movimientos";
                        $bd->consulta($sql);
                        $num_row = $bd->get_num_rows();
                        ?>
                        <tr>
                            <td><span class="Estilo">El número de movimientos es: <?php echo $num_row; ?></span></td>
                        </tr>
                        <tr ><td colspan="2">
                        <table width='100%' border='0' align="center">
                            <tr>
                                <td colspan="11" bgcolor="#CCCCCC" >Mis Tareas asignadas pendientes:</td>
                            </tr>
                            <tr>
                                <td width="5%" bgcolor="#CCCCCC" ><div align="center"><strong><a href="control.form.php?order=nIncidencia&sql_previo=<?php echo $sql_previo ?>" class="Utiles">Nº</a></strong></div></td>
                                <td width="5%" bgcolor="#CCCCCC"><div align="center"><strong><a href="control.form.php?order=prioridad&sql_previo=<?php echo $sql_previo ?>" class="Utiles">Prioridad</a></strong></div></td>
                                <td width="10%" bgcolor="#CCCCCC"><div align="center"><strong><a href="control.form.php?order=categoria&sql_previo=<?php echo $sql_previo ?>" class="Utiles">Categoría</a></strong></div></td>
                                <td width="5%" bgcolor="#CCCCCC"><div align="center"><strong><a href="control.form.php?order=estado&sql_previo=<?php echo $sql_previo ?>" class="Utiles">Estado</a></strong></div></td>
                                <td width="25%" bgcolor="#CCCCCC"><div align="center"><strong><a href="control.form.php?order=titulo&sql_previo=<?php echo $sql_previo ?>" class="Utiles">Título</a></strong></div></td>
                                <td width="5%" bgcolor="#CCCCCC"><div align="center"><strong><a href="control.form.php?order=fecha_alta&sql_previo=<?php echo $sql_previo ?>" class="Utiles">Fecha de Alta</a></strong></div></td>
                                <td width="5%" bgcolor="#CCCCCC"><div align="center"><strong><a href="control.form.php?order=fecha_alta&sql_previo=<?php echo $sql_previo ?>" class="Utiles">Ultima Modif.</a></strong></div></td>
                                <td width="5%" bgcolor="#CCCCCC"><div align="center"><strong><a href="control.form.php?order=fecha_alta&sql_previo=<?php echo $sql_previo ?>" class="Utiles">Fecha de Cierre</a></strong></div></td>
                                <td width="10%" bgcolor="#CCCCCC"><div align="center"><strong><a href="control.form.php?order=solicitante&sql_previo=<?php echo $sql_previo ?>" class="Utiles">Solicitante</a></strong></div></td>
                                <td width="25%" bgcolor="#CCCCCC"><div align="center"><strong><a href="control.form.php?order=email_resp&sql_previo=<?php echo $sql_previo ?>" class="Utiles">Email</a></strong></div></td>
                            </tr>
                            <?php
                            //recuperamos el filtro en caso de existir
                            $order=$_GET['order'];
                            if(!isset($order)) {
                                $order='nIncidencia';
                            }

                            $sql="select
                                        nIncidencia,
                                        prioridad,
                                        categoria,
                                        estado,
                                        titulo,
                                        TO_CHAR(fecha_alta, 'DD/MM/YYYY HH24:MI:SS' ),
                                        TO_CHAR(fecha_modif, 'DD/MM/YYYY HH24:MI:SS' ),
                                        TO_CHAR(fecha_cierre, 'DD/MM/YYYY HH24:MI:SS' ),
                                        solicitante,
                                        email_resp
                                    from Incidencias
                                    where (tecnico_asig='".$NOMBRE_USUARIO."' and
                                            not estado like 'Cerrada%')
                                    order by ".$order;
                            $bd->consulta($sql);
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
                                       </tr>";
                                //aumentamos en uno el número de filas
                                $num_fila++;
                            }
                            ?>
                            <tr>
                                <td colspan="11" bgcolor="#CCCCCC" >&nbsp;</td>
                            </tr>
                        </table>
                            </td>
            </tr>
        </table></td>
        <td width="528">
            </tr>
            </table>
    </div>
</div>
<script type="text/javascript">
    <!--
    var CollapsiblePanel1 = new Spry.Widget.CollapsiblePanel("Central");
    //-->
</script>
