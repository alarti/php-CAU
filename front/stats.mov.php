<?php
include("menu.form.php");
require_once("../inc/oracle.class.php");

// oracle
$bd =& new oracle();

?>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Incidencias</title>
        <link href="../css/Estilo.css" rel="stylesheet" type="text/css" />
        <script type='text/JavaScript' src='../js/scw.js'></script>
        <script src="../SpryAssets/SpryCollapsiblePanel.js" type="text/javascript"></script>
        <link href="../SpryAssets/SpryCollapsiblePanel.css" rel="stylesheet" type="text/css" />

        <?php
        $order=$_GET['order'];

        if(!isset($order)) {
            $order='nMov';
        }

        $sql="select
             nMov,
             TO_CHAR(fecha, 'DD/MM/YYYY HH24:MI:SS' ),
             nombre,
             consulta,
             DESCRIPCION
             from Movimientos
             order by ".$order;
        $bd->consulta($sql);
        ?>
        <div id="js_resultados" class="CollapsiblePanel">
            <div class="CollapsiblePanelTab">Resultados de la Búsqueda:</div>
            <div class="CollapsiblePanelContent">
                <table width="100%" border="0">
                    <tr>
                        <td width="100%"><div>
                                <div>
                                    <table width='100%' border='0' align="center">
                                        <tr>
                                            <td colspan="9" bgcolor="#CCCCCC" >&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td width="5%" bgcolor="#CCCCCC" ><div align="center"><strong><a href="stats.mov.php?order=nMov" class="Utiles">Nº Movimientos</a></strong></div></td>
                                            <td width="20%" bgcolor="#CCCCCC"><div align="center"><strong><a href="stats.mov.php?order=fecha" class="Utiles">Fecha</a></strong></div></td>
                                            <td width="15%" bgcolor="#CCCCCC"><div align="center"><strong><a href="stats.mov.php?order=nombre" class="Utiles">Nombre</a></strong></div></td>
                                            <td width="30%" bgcolor="#CCCCCC"><div align="center"><strong><a href="stats.mov.php?order=consulta" class="Utiles">Consulta</a></strong></div></td>
                                            <td width="30%" bgcolor="#CCCCCC"><div align="center"><strong><a href="stats.mov.php?order=Descripcion" class="Utiles">Descripcion</a></strong></div></td>
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
                                            echo "<tr>
                                                      <td ".$estilo_fila.">".$row[0]."</td>
                                                      <td ".$estilo_fila.">".$row[1]."</td>
                                                      <td ".$estilo_fila.">".$row[2]."</td>
                                                      <td ".$estilo_fila.">".$row[3]."</td>
                                                      <td ".$estilo_fila.">".$row[4]."</td>
                                                      </tr>";

                                            //aumentamos en uno el número de filas
                                            $num_fila++;
                                        }
                                        ?>
                                        <tr>
                                            <td colspan="9" bgcolor="#CCCCCC">&nbsp;</td>
                                        </tr>
                                    </table>
                                </div>
                            </div></td>
                    </tr>
                </table>
            </div>
        </div>
        <script type="text/javascript">
            <!--
            var CollapsiblePanel1 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel1");
            var CollapsiblePanel3 = new Spry.Widget.CollapsiblePanel("js_resultados");
            //-->
        </script>
