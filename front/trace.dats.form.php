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


// Comprueba que tiene acceso

if(!substr($privilegios,8,1)=='1') {
    echo '<link href="../css/Estilo.css" rel="stylesheet" type="text/css" />';
    echo "<div id='salida' class='Error'>No tiene privilegios suficientes para acceder a este Seguimiento</div>";
    exit;
}
//recogemos el numero de incidencia y seguimiento
$nIncidencia=$_GET['nIncidencia'];
$nSeguimiento=$_GET['nSeguimiento'];

if(!isset($nSeguimiento)){
    //include("");
    echo '<link href="../css/Estilo.css" rel="stylesheet" type="text/css" />';
    echo "<div id='salida' class='Error'>No se ha obtenido código de seguimiento para mostrar</div>";
    exit;
}


$sql = "SELECT * FROM Seguimientos WHERE nSeguimiento=".$nSeguimiento;
//$sql = "SELECT nombre, correo, AES_DECRYPT(privilegios,'".$clave."'), contrasena  FROM Usuarios WHERE nombre = '".$nombreUsuario."';";
$bd->consulta($sql);
$row = $bd->get_rows();
// si no tiene permisos de lectura de incidencias o es el que abrió la incidencia sale fuera
if(!substr($privilegios,8,1)=='1') {
    echo("ERROR no tiene acceso a este seguimiento");
    exit;
}
$titulo=$row['TITULO'];
$descripcion=$row['DESCRIPCION'];
?>  
        <link href="../css/Estilo.css" rel="stylesheet" type="text/css" />
        
        <script src="../SpryAssets/SpryValidationTextarea.js" type="text/javascript"></script>
        <script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>

<script src="../SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
        <script src="../SpryAssets/SpryCollapsiblePanel.js" type="text/javascript"></script>
        <link href="../SpryAssets/SpryValidationTextarea.css" rel="stylesheet" type="text/css" />
        <link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />

<link href="../SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />
        <link href="../SpryAssets/SpryCollapsiblePanel.css" rel="stylesheet" type="text/css" />
<form name="form_incidencia" id="form_incidencia" method="post" action="trace.alter.php" dir="ltr" lang="es" xml:lang="es">
            <table width="100%" border="0" align="center">
                <tr>
                    <td width="100%"><div id="TabbedPanels1" class="TabbedPanels" >
                            <ul class="TabbedPanelsTabGroup">
                              <li class="TabbedPanelsTab">Datos de la Incidencia <?php echo $nIncidencia ?> Seguimiento Nº <?php echo $nSeguimiento ?></li>
                            </ul>
                            <div class="TabbedPanelsContentGroup">
                              <div class="TabbedPanelsContent">
                                    <div align="left">
                                        <table width="100%" border="0">
                                            <tr>
                                                <td width="20%">Título:</td>
                                                <td width="80%"><span id="js_titulo">
                                                        <input name="titulo" type="text" id="titulo" size="60" maxlength="60" value="<?php echo $titulo ?>"/>
                                                <span class="textfieldRequiredMsg">El título es obligatorio.</span><span class="textfieldMaxCharsMsg">Se ha superado el número máximo de caracteres.</span></span></td>
                                            </tr>
                                            <tr>
                                                <td height="88">Descripción:</td>
                                                <td><span id="js_descrip">
                                                        <textarea name="descripcion" id="descripcion" cols="80" rows="10"><?php echo $descripcion ?></textarea>
                                                        <span id="countjs_descrip">&nbsp;</span> <span class="textareaRequiredMsg">Se necesita un valor.</span><span class="textareaMinCharsMsg">No se cumple el mínimo de caracteres requerido.</span><span class="textareaMaxCharsMsg">Se ha superado el número máximo de caracteres.</span></span></td>
                                            </tr>
                                            <tr>
                                                <td <input type="hidden" name="nSeguimiento" id="nSeguimiento" value="<?php echo $nSeguimiento ?>" /></td>
                                                <td <input type="hidden" name="nIncidencia" id="nIncidencia" value="<?php echo $nIncidencia ?>" /></td>
                                            </tr>
                                            <tr>
                                                <td rowspan="2">&nbsp;</td>
                                                <td><input type="submit" name="modificar" value="Modificar Seguimiento" /></td>
                                            </tr>
                                            <tr>
                                              <td><input type="submit" name="borrar" value="Borrar Seguimiento" /></td>
                                            </tr>
                                        </table>
                                </div>
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
var sprytextarea1 = new Spry.Widget.ValidationTextarea("js_descrip", {counterId:"countjs_descrip", counterType:"chars_remaining", validateOn:["blur", "change"], minChars:1, maxChars:800});
            var sprytextfield1 = new Spry.Widget.ValidationTextField("js_titulo", "none", {validateOn:["blur", "change"], maxChars:60});
            var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1");
					
//-->
</script>

