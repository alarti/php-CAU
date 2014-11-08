<?php
include("menu.form.php");
require_once("../inc/oracle.class.php");
require_once("../inc/security.class.php");
require_once("../inc/text.class.php");

// seguridad
$s =& new security();
$nombre = $s->get_nombre();
$seguridad=$s->get_privilegios();
if (substr($privilegios,3,1)<>'1')
    $estado_disable="disabled";
else
    $estado_disable="";

// oracle
$bd =& new oracle();

?>

<link href="../css/Estilo.css" rel="stylesheet" type="text/css" />

<script type='text/JavaScript' src='../js/scw.js'></script>
<script src="../SpryAssets/SpryCollapsiblePanel.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>

<link href="../SpryAssets/SpryCollapsiblePanel.css" rel="stylesheet" type="text/css" />
<link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />

<form id="form1" name="form1" method="post" action="issue.find.php">
    <table width="100%" border="0">
        <tr>
            <td height="100%" class="CollapsiblePanelTab">HelpDesk &gt; </td>
        </tr>
        <tr>
            <td><div id="CollapsiblePanel1" class="CollapsiblePanel">
                    <div class="CollapsiblePanelTab">
                        <div align="left" style="font-size: small">Opciones de Búsqueda:</div>
                    </div>
                    <div class="CollapsiblePanelContent">
                        <table width="100%" border="0">
                            <tr>
                                <td width="100%">
                                    <table width="100%" border="0">
                                        <tr>
                                            <td width="25%" bgcolor="#CCCCCC">Nº de Incidencia</td>
                                            <td width="25%" bgcolor="#CCCCCC">Prioridad</td>
                                            <td width="25%" bgcolor="#CCCCCC">Categoría</td>
                                            <td width="25%" bgcolor="#CCCCCC">Estado</td>
                                        </tr>
                                        <tr>
                                            <td><span id="js_nIncidencia">
                                                    <input type="text" name="nIncidencia" id="nIncidencia" />
                                                    <span class="textfieldInvalidFormatMsg">Sólo son válidos numeros enteros.</span></span></td>
                                            <td><select name="prioridad" id="prioridad">
                                                    <option value="-1" selected="selected"></option>
                                                    <?php
                                                    $sql="select IdPrioridad from Prioridades";
                                                    $bd->consulta($sql);
                                                    while ($row = $bd->get_rows()) {
                                                        echo "<option>$row[0]</option>";
                                                    }
                                                    ?>
                                                </select>                            </td>
                                            <td><select name="categoria" id="categoria">
                                                    <option value="-1" selected="selected"></option>
                                                    <?php
                                                    $sql="select Idcategoria from Categorias";
                                                    $bd->consulta($sql);
                                                    while ($row = $bd->get_rows()) {
                                                        echo "<option>$row[0]</option>";
                                                    }
                                                    ?>
                                                </select>                            </td>
                                            <td><select name="estado" id="estado">
                                                    <option value="-1" selected="selected"></option>
                                                    <option>No Cerrada</option>
                                                    <?php
                                                    $sql="select Idestado from Estados";
                                                    $bd->consulta($sql);
                                                    while ($row = $bd->get_rows()) {
                                                        echo "<option>$row[0]</option>";
                                                    }
                                                    ?>
                                                </select>                            </td>
                                        </tr>
                                        <tr>
                                            <td bgcolor="#CCCCCC">Título:</td>
                                            <td bgcolor="#CCCCCC">Solicitante:</td>
                                            <td bgcolor="#CCCCCC">Técnico Asignado:</td>
                                            <td bgcolor="#CCCCCC">&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td><input name="titulo" type="text" id="titulo" size="20" /></td>
                                            <td><select name="solicitante" id="solicitante" <?php echo $estado_disable ?> >
                                                    <option value="-1" selected="selected"></option>
                                                    <?php
                                                    $sql="select nombre from Usuarios";
                                                    $bd->consulta($sql);
                                                    while ($row = $bd->get_rows()) {
                                                        echo "<option>$row[0]</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                            <td>
                                                <select name="tecnico_asig" id="tecnico_asig">
                                                    <option value="-1" selected="selected"></option>
                                                    <option>Sin Asignar</option>
                                                    <?php
                                                    $sql="select nombre from Usuarios WHERE (privilegios = '1111111111')";
                                                    $bd->consulta($sql);
                                                    while ($row = $bd->get_rows()) {
                                                        echo "<option>$row[0]</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                            <td>&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" bgcolor="#CCCCCC">Descripción: </td>
                                        </tr>
                                        <tr>
                                            <td colspan="4"><input name="descripcion" type="text" id="descripcion" size="75" />
                                                <input type="submit" name="Buscar" id="Buscar" value="Buscar" />
                                                <div align="center"></div></td>
                                        </tr>
                                    </table></td>
                            </tr>
                            <tr>
                                <td><div id="js_opciones_avanzadas" class="CollapsiblePanel">
                                        <div class="CollapsiblePanelTab" style="font-size: small">Opciones Avanzadas</div>
                                        <div class="CollapsiblePanelContent">
                                            <table width="100%" border="0">
                                                <tr>
                                                    <th  width="33%" colspan="2" bgcolor="#CCCCCC"><div align="center">Fecha de alta:</div></th>
                                                    <th  width="33%" colspan="2" bgcolor="#CCCCCC"><div align="center">Fecha de última modificación:</div></th>
                                                    <th  width="33%" colspan="2" bgcolor="#CCCCCC"><div align="center">Fecha de cierre:</div></th>
                                                </tr>
                                                <tr>
                                                    <td>Inicial</td>
                                                    <td><input name="alta_date_ini" id="alta_date_ini" onclick='scwShow(this,event);' value='' size="10" /></td>
                                                    <td>Inicial:</td>
                                                    <td><input name="modif_date_ini" id="modif_date_ini" onclick='scwShow(this,event);' value='' size="10" /></td>
                                                    <td>Inicial:</td>
                                                    <td><input name="close_date_ini" id="close_date_ini" onclick='scwShow(this,event);' value='' size="10" /></td>
                                                </tr>
                                                <tr>
                                                    <td>Final</td>
                                                    <td><input name="alta_date_fin" id="alta_date_fin" onclick='scwShow(this,event);' value='' size="10" /></td>
                                                    <td>Final:</td>
                                                    <td><input name="modif_date_fin" id="modif_date_fin" onclick='scwShow(this,event);' value='' size="10" /></td>
                                                    <td>Final:</td>
                                                    <td><input name="close_date_fin" id="close_date_fin" onclick='scwShow(this,event);' value='' size="10" /></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div></td>
                            </tr>

                            <tr>
                                <td width="949"><table width="657" border="0">
                                    </table></td>
                            </tr>
                        </table>
                    </div>
                </div></td>
        </tr>
    </table>

</form>
<script type="text/javascript">
    <!--
    var CollapsiblePanel2 = new Spry.Widget.CollapsiblePanel("js_opciones_avanzadas");
    var CollapsiblePanel1 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel1");
    var sprytextfield1 = new Spry.Widget.ValidationTextField("js_nIncidencia", "integer", {validateOn:["blur", "change"], isRequired:false});
    //-->
</script>

