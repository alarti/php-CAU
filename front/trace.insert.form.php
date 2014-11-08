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


?>  
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>HelpDesk</title>
        <link href="../css/Estilo.css" rel="stylesheet" type="text/css" />
        <script src="../SpryAssets/SpryValidationTextarea.js" type="text/javascript"></script>
        <script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
    
<script src="../SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
        <link href="../SpryAssets/SpryValidationTextarea.css" rel="stylesheet" type="text/css" />
        <link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
    
    <link href="../SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />
        <form action="trace.insert.php" method="post" name="form_incidencia" id="form_incidencia" dir="ltr" lang="es" xml:lang="es">
            <table width="100%" border="0" align="center">
                <tr>
                    <td width="100%"><div id="TabbedPanels1" class="TabbedPanels" >
                            <ul class="TabbedPanelsTabGroup">
                                <li class="TabbedPanelsTab">Nuevo Seguimiento para la Incidencia <?php echo $nIncidencia ?></li>
                            </ul>
                            <div class="TabbedPanelsContentGroup">
                                <div class="TabbedPanelsContent">
                                    <div align="left">
                                        <table width="100%" border="0">
                                            <tr>
                                                <td width="20%">Título:</td>
                                                <td width="80%"><span id="js_titulo">
                                                        <input name="titulo" type="text" id="titulo" size="60" maxlength="60" />
                                                        <span class="textfieldRequiredMsg">El título es obligatorio.</span><span class="textfieldMaxCharsMsg">Se ha superado el número máximo de caracteres.</span></span>
                                                  <input type="hidden" name="nIncidencia" id="nIncidencia" value="<?php echo $nIncidencia ?>">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td height="104">Descripción:</td>
                                                 <td><span id="js_descrip">
                                                        <textarea name="descrip" cols="80" rows="10" id="descrip" ></textarea>
                                                        <span id="countjs_descrip">&nbsp;</span> <span class="textareaRequiredMsg">Se necesita un valor.</span><span class="textareaMinCharsMsg">No se cumple el mínimo de caracteres requerido.</span><span class="textareaMaxCharsMsg">Se ha superado el número máximo de caracteres.</span></span>
                                                 </td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td><input type="submit" name="Enviar" id="Enviar" value="Enviar" /></td>
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
            var sprytextarea1 = new Spry.Widget.ValidationTextarea("js_descrip", {counterId:"countjs_descrip", counterType:"chars_remaining", validateOn:["blur", "change"], minChars:1, maxChars:800, hint:"Describe el problema de la forma m\xE1s clara posible"});
            var sprytextfield1 = new Spry.Widget.ValidationTextField("js_titulo", "none", {validateOn:["blur", "change"], maxChars:60});
            var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1");
            //-->
        </script>