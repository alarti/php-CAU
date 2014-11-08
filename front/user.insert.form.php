<?php
	include("menu.form.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//ES" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Insertar Usuario</title>
<link href="../css/Estilo.css" rel="stylesheet" type="text/css" />
<script src="../SpryAssets/SpryCollapsiblePanel.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<link href="../SpryAssets/SpryCollapsiblePanel.css" rel="stylesheet" type="text/css" />
<link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
</head>

<body >
<div id="CollapsiblePanel1" class="CollapsiblePanel">
  <div class="CollapsiblePanelTab" tabindex="0">Nuevo Usuario</div>
  <div class="CollapsiblePanelContent">
    <div id="datos">
      <form id="form1" name="form1" method="post" action="user.insert.php">
        <table width="100%" border="0">
          <tr>
            <td width="20%" class="Estilo"><label for="label">Nombre del nuevo Usuario:</label></td>
            <td width="80%"><label for="textfield"><span id="js_nombre">
              <input type="text" name="nombre" id="nombre" />
              <span class="textfieldRequiredMsg">Se necesita un valor.</span><span class="textfieldMinCharsMsg">No se cumple el mínimo de caracteres requerido.</span></span></label></td>
          </tr>
          <tr>
            <td class="Estilo">Contraseña del nuevo Usuario:</td>
            <td><span id="js_contrasena">
              <input type="password" name="contrasena1" id="contrasena1" />
              <span class="textfieldRequiredMsg">Se necesita un valor.</span><span class="textfieldMinCharsMsg">No se cumple el mínimo de caracteres requerido.</span></span></td>
          </tr>
          <tr>
            <td class="Estilo">Repetir la contraseña:</td>
            <td><span id="js_contrasena2">
              <input type="password" name="contrasena2" id="contrasena2" />
              <span class="textfieldRequiredMsg">Se necesita un valor.</span><span class="textfieldMinCharsMsg">No se cumple el mínimo de caracteres requerido.</span></span></td>
          </tr>
          <tr>
            <td class="Estilo">Correo:</td>
            <td><span id="js_correo">
              <input type="text" name="correo" id="correo" />
              <span class="textfieldRequiredMsg">Se necesita un valor.</span><span class="textfieldInvalidFormatMsg">Formato no válido.</span></span></td>
          </tr>
</table>
        <hr />
        <table width="100%" border="0">
          <tr>
            <td  class="Estilo">&nbsp;</td>
            <td class="Estilo">&nbsp;</td>
            <td  class="Estilo"><div align="center"><strong>Administrador</strong></div></td>
            <td class="Estilo"><div align="center"><strong>Técnico</strong></div></td>
            <td class="Estilo"><div align="center"><strong>Incidencias</strong></div></td>
            <td  class="Estilo"><div align="center"><strong>Inventarios</strong></div></td>
            <td  class="Estilo"><div align="center"><strong>Seguimientos</strong></div></td>
          </tr>
          <tr>
            <td width="25%" rowspan="2" class="Estilo">Privilegios:</td>
            <td width="25%" class="Estilo"><strong><label></label>
              <label>              </label>
              </strong>              <label>
              <div align="right"><strong>Lectura:</strong></div>
              </label>            </td>
            <td width="10%" class="Estilo"><label>
              <div align="center">
                <input name="lA" type="checkbox" id="lA" value="1" />
                </div>
            </label></td>
            <td width="10%"  class="Estilo"><div align="center">
              <input name="lE" type="checkbox" id="lE" value="1" />
            </div></td>
            <td width="10%" class="Estilo">              <p align="center">
                  <input name="lS" type="checkbox" id="lS" value="1" checked="checked" />
                                          </p></td>
            <td width="10%"  class="Estilo"><div align="center">
              <input name="lU" type="checkbox" id="lU" value="1" checked="checked" />
            </div></td>
            <td width="10%" class="Estilo"><div align="center">
              <input name="lC" type="checkbox" id="lC" value="1" checked="checked" />
            </div></td>
          </tr>
          <tr>
            <td class="Estilo"><div align="right"><strong>Escritura:</strong></div></td>
            <td class="Estilo"><div align="center">
              <input name="wA" type="checkbox" id="wA" value="1" />
            </div></td>
            <td  class="Estilo"><div align="center">
              <input name="wE" type="checkbox" id="wE" value="1" />
            </div></td>
            <td  class="Estilo"> <div align="center">
              <input name="wS" type="checkbox" id="wS" value="1" checked="checked" />
            </div></td>
            <td class="Estilo"><div align="center">
              <input name="wU" type="checkbox" id="wU" value="1" checked="checked" />
            </div></td>
            <td class="Estilo"><div align="center">
              <input name="wC" type="checkbox" id="wC" value="1" checked="checked" />
            </div></td>
          </tr>
        </table>
        <hr />
        <table width="100%" border="0">
          <tr>
            <td><input type="submit" name="Submit" value="Continuar" /></td>
          </tr>
        </table>
        <table width="100%" border="0">
        </table>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript">
<!--
var CollapsiblePanel1 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel1");
var sprytextfield4 = new Spry.Widget.ValidationTextField("js_correo", "email", {validateOn:["blur", "change"]});
var sprytextfield3 = new Spry.Widget.ValidationTextField("js_contrasena2", "none", {validateOn:["blur", "change"], minChars:4});
var sprytextfield2 = new Spry.Widget.ValidationTextField("js_contrasena", "none", {validateOn:["blur", "change"], minChars:4});
var sprytextfield1 = new Spry.Widget.ValidationTextField("js_nombre", "none", {validateOn:["blur", "change"], minChars:4});
//-->
</script>
</body>
</html>
