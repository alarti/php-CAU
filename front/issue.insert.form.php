<?php
include("menu.form.php");

require_once("../inc/security.class.php");
require_once("../inc/oracle.class.php");

// oracle
$bd =& new oracle();

// seguridad
$s =& new security();
$nombre = $s->get_nombre();
$privilegios = $s->get_privilegios();


// Busca el Usuario para mostrar todos los datos
$sql = "SELECT correo FROM Usuarios WHERE nombre = '".$nombre."'";
//$sql = "SELECT nombre, correo, AES_DECRYPT(privilegios,'".$clave."'), contrasena  FROM Usuarios WHERE nombre = '".$nombreUsuario."';";
$bd->consulta($sql);

$row = $bd->get_rows();
$correo = $row['CORREO'];
if(!substr($privilegios,3,1)=='1')
    $estado_state="disabled";
else
    $estado_state="";
?>  
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//ES" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>HelpDesk</title>
        <link href="../css/Estilo.css" rel="stylesheet" type="text/css" />
        
<script src="../SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationTextarea.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationSelect.js" type="text/javascript"></script>
    
    <link href="../SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />
    <link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
    <link href="../SpryAssets/SpryValidationTextarea.css" rel="stylesheet" type="text/css" />
    <link href="../SpryAssets/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <form action="issue.insert.php" method="post" name="form_incidencia" id="form_incidencia" dir="ltr" lang="es" xml:lang="es">
            <table width="888" border="0" align="center">
                <tr>
                    <td width="100%"><div id="TabbedPanels1" class="TabbedPanels" >
                            <ul class="TabbedPanelsTabGroup">
                                <li class="TabbedPanelsTab">Nueva Incidencia</li>
                            </ul>
                            <div class="TabbedPanelsContentGroup">
                                <div class="TabbedPanelsContent">
                                  <table width="100%" border="0">
                                    <tr>
                                      <td width="20%">Prioridad:</td>
                                      <td width="80%"><span id="js_prioridad">
                                        <select name="prioridad" id="prioridad">
                                          <?php
                                                            $sql="select IdPrioridad from Prioridades";
                                                            $bd->consulta($sql);
                                                            while ($row = $bd->get_rows()) {
                                                                echo "<option>$row[0]</option>";
                                                            }
                                                            ?>
                                        </select>
                                      </span></td>
                                    </tr>
                                    <tr>
                                      <td width="20%">Categoría:</td>
                                      <td width="80%"><span id="js_categoría">
                                        <select name="categoria" id="categoria" >
                                          <option value="-1" selected="selected"></option>
                                          <?php
                                                            $sql="select Idcategoria from Categorias";
                                                            $bd->consulta($sql);
                                                            while ($row = $bd->get_rows()) {
                                                                echo "<option>$row[0]</option>";
                                                            }
                                                            ?>
                                        </select>
                                        <span class="selectInvalidMsg">Seleccione un elemento válido.</span> </span></td>
                                    </tr>
                                    <tr>
                                      <td width="20%">Estado:</td>
                                      <td width="80%"><span id="js_estado">
                                        <select name="estado" id="estado" <?php echo $estado_state ?> >
                                          <?php
                                                            $sql="select Idestado from Estados";
                                                            $bd->consulta($sql);
                                                            while ($row = $bd->get_rows()) {
                                                                echo "<option>$row[0]</option>";
                                                            }
                                                            ?>
                                        </select>
                                        <span class="selectInvalidMsg">Seleccione un elemento válido.</span> </span></td>
                                    </tr>
                                    <tr>
                                        <td>Técnico asignado</td>
                                        <td><span id="js_tecnico_asig">
                                                <select name="tecnico_asig" id="tecnico_asig" <?php echo$estado_state ?>>
                                                    <option value="-1" selected="selected"></option>
                                                    <?php
                                                    $sql="select nombre from Usuarios WHERE (privilegios like '__11111111')";
                                                    $bd->consulta($sql);
                                                    while ($row = $bd->get_rows()) {
                                                        if ($row[0]==$tecnico_asig)
                                                            echo "<option selected>$row[0]</option>";
                                                        else
                                                            echo "<option>$row[0]</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </span></td>
                                    </tr>
                                    <tr>
                                      <td width="20%">Título:</td>
                                      <td width="80%"><span id="js_titulo">
                                        <input name="titulo" type="text" id="titulo" size="60" maxlength="60" />
                                        <span class="textfieldRequiredMsg">El título es obligatorio.</span><span class="textfieldMaxCharsMsg">Se ha superado el número máximo de caracteres.</span></span></td>
                                    </tr>
                                    <tr>
                                      <td width="20%" height="104">Descripción:</td>
                                      <td width="80%"><span id="js_descrip">
                                        <textarea name="descrip" id="descrip" cols="80" rows="5"></textarea>
                                        <span id="countjs_descrip">&nbsp;</span> <span class="textareaRequiredMsg">Se necesita un valor.</span><span class="textareaMinCharsMsg">No se cumple el mínimo de caracteres requerido.</span><span class="textareaMaxCharsMsg">Se ha superado el número máximo de caracteres.</span></span></td>
                                    </tr>
                                    <tr>
                                      <td width="20%">Email:</td>
                                      <td width="80%"><span id="js_email">
                                        <input type="text" name="email" id="email" value="<?php echo $correo?>" />
                                        <span class="textfieldRequiredMsg">Se necesita un valor.</span><span class="textfieldInvalidFormatMsg">Formato no válido.</span></span></td>
                                    </tr>
                                    <tr>
                                      <td width="20%">&nbsp;</td>
                                      <td width="80%"><input type="submit" name="Enviar" id="Enviar" value="Enviar" /></td>
                                    </tr>
                                                                                                                                                                                                                                              </table>
                              </div>
                            </div>
                            <div class="TabbedPanelsContent">
                                <div align="left">
                                    <table width="619" border="0">
                                    </table>
                                </div>
                            </div>
                        </div>
                  </td>
                  <td width="38">&nbsp;</td>
                </tr>
            </table>
        </form>

        <script type="text/javascript">
<!--
var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1");
var sprytextfield2 = new Spry.Widget.ValidationTextField("js_email", "email", {validateOn:["blur", "change"]});
var sprytextarea1 = new Spry.Widget.ValidationTextarea("js_descrip", {counterId:"countjs_descrip", counterType:"chars_remaining", validateOn:["blur", "change"], minChars:1, maxChars:800, hint:"Describe el problema de la forma m\xE1s clara posible"});
var sprytextfield1 = new Spry.Widget.ValidationTextField("js_titulo", "none", {validateOn:["blur", "change"], maxChars:60});
var spryselect3 = new Spry.Widget.ValidationSelect("js_estado", {validateOn:["blur", "change"], isRequired:false, invalidValue:"-1"});
var spryselect4 = new Spry.Widget.ValidationSelect("js_tecnico_asig", {validateOn:["blur", "change"], isRequired:false});
var spryselect2 = new Spry.Widget.ValidationSelect("js_categoría", {validateOn:["blur", "change"], isRequired:false, invalidValue:"-1"});
var spryselect1 = new Spry.Widget.ValidationSelect("js_prioridad", {validateOn:["blur", "change"], isRequired:false});
//-->
</script>
    </body>
</html>
