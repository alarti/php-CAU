<?php
include("menu.form.php");
require_once("../inc/oracle.class.php");

// oracle
$bd =& new oracle();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//ES" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Incidencias</title>
        <link href="../css/Estilo.css" rel="stylesheet" type="text/css" />

        <script type='text/JavaScript' src='../js/scw.js'></script>
        <script src="../SpryAssets/SpryCollapsiblePanel.js" type="text/javascript"></script>
        <script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>

        <link href="../SpryAssets/SpryCollapsiblePanel.css" rel="stylesheet" type="text/css" />

        <link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <div id="CollapsiblePanel1" class="CollapsiblePanel">
            <div class="CollapsiblePanelTab">
                <div align="left" style="font-size: small">Tabla de usuarios:</div>
            </div>
            <div class="CollapsiblePanelContent">
                <form id="form1" name="form1" method="post" action="user.insert.form.php">
                    <input type="submit" name="Buscar" id="Buscar" value="Nuevo Usuario" />
                </form>
            </div>
        </div>
        <?php
        $order=$_GET['order'];

        if(!isset($order)) {
            $order='nombre';
        }

        $sql="select
                nombre,
                correo,
                privilegios
             from Usuarios
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
                                            <td width="20%" bgcolor="#CCCCCC" ><div align="center"><strong><a href="user.find.form.php?order=nombre" class="Utiles">Nombre</a></strong></div></td>
                                            <td width="40%" bgcolor="#CCCCCC"><div align="center"><strong><a href="user.find.form.php?order=correo" class="Utiles">Email</a></strong></div></td>
                                            <td width="40%" bgcolor="#CCCCCC"><div align="center"><strong><a href="user.find.form.php?order=privilegios" class="Utiles">Privilegios</a></strong></div></td>
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
                                            if($row['PRIVILEGIOS']=="1111111111")
                                                $estilo_celda="bgcolor=#66cc66 align='center'";
                                            else
                                                if($row['PRIVILEGIOS']=="0011111111")
                                                    $estilo_celda="bgcolor=#ffcc66 align='center'";
                                                else 
                                                    $estilo_celda="bgcolor=#D77171 align='center'";
                                            echo "<tr>
                                                      <td ".$estilo_fila."<div><a href='user.dats.form.php?Usuario=".$row[0]."'>".$row[0]."</div></td>
                                                      <td ".$estilo_fila.">".$row[1]."</td>
                                                      <td ".$estilo_celda.">".$row[2]."</td>
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
            var sprytextfield4 = new Spry.Widget.ValidationTextField("js_correo", "email", {validateOn:["blur", "change"]});
            var sprytextfield1 = new Spry.Widget.ValidationTextField("js_nombre", "none", {validateOn:["blur", "change"], minChars:4});
            //-->
        </script>


    </body>
</html>

