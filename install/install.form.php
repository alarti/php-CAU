<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//ES">
<html><head>
<script language="javascript" src="../js/xp_progress.js" type="text/javascript">

</script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"><title>Asistente de instalación</title>

<link href="../css/Estilo.css" rel="stylesheet" type="text/css">
<script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationPassword.js" type="text/javascript"></script>
<link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css">
<link href="../SpryAssets/SpryValidationPassword.css" rel="stylesheet" type="text/css">

<style type="text/css">
<!--
body {
	background-color: #52A2FF;
}
-->
</style></head>
<body  style="background-color: #336699" class="Estilo">
<form id="instalar" name="instalar"  method="post" action="./install.php"><br>
<div style="text-align: center;"> </div>
<table width="792" border="0" style="background-color: white; width: 800px; height: 197px; text-align: left; margin-left: auto; margin-right: auto;">
<tbody>
<tr>
<td style="text-align: center; height: 5px;" colspan="3"><p class="titulo"><span class="Titulo">"Crear Base de
    datos y tablas ---&gt;
    Paso 1/3"</span></p>
    <p class="titulo">&nbsp;</p>
</td>
</tr>
<tr>
<td style="font-family: Arial; font-weight: normal;" colspan="3"><p>Se
  va a proceder a la instalación de las tablas del sistema. Puede tardar
  unos segundos:</p>
  <fieldset>
    <p class="mainlevel">
      <legend><em>Informacion Servidor de Base de Datos:</em></legend>
    <table width="688" border="0" align="center">
      <tr>
        <td width="177"><label for="bd_root_user"><strong>Usuario ROOT:</strong></label>
          <td width="501" style="text-align: left"><span id="js_bd_root_user">
          <input name="bd_root_user" type="text" id="bd_root_user" value="system">
          <span class="textfieldRequiredMsg">Campo obligatorio.</span></span></td>
      </tr>
      <tr>
        <td>
          <span style="text-align: center">
          <label for="bd_root_pwd"><strong>Contraseña ROOT:</strong></label>
          <span class="passwordRequiredMsg">Campo obligatorio.</span></span></td>
        <td style="text-align: left"><div align="left"><span id="js_bd_root_pwd">
          <input type="password" name="bd_root_pwd" id="bd_root_pwd">
          <span class="passwordRequiredMsg">Campo obligatorio.</span></span></div></td>
      </tr>
      <tr>
        <td><label for="bd_server"><strong>Servidor de Base de Datos:</strong></label></td>
        <td style="text-align: left"><div align="left"><span id="js_bd_server">
          <input name="bd_server" type="text" id="bd_server" value="localhost">
          <span class="textfieldRequiredMsg">Campo obligatorio.</span></span></div></td>
      </tr>
    </table>
    <p><span style="text-align: left"></span><span style="text-align: center"></span></p>
  </fieldset>
  <fieldset>
    <p class="mainlevel">
    <legend><em>Datos para la nueva instalación:</em></legend>
    <table width="688" border="0" align="center">
      <tr>
        <td width="177"><strong>Nombre de la Instancia / Empresa:</strong></td>
        <td width="501"><span id="js_inst_user">
          <input name="inst_user" type="text" id="inst_user">
          <span class="textfieldRequiredMsg">Campo obligatorio.</span><span class="textfieldMinCharsMsg">Mínimo de caracteres(2).</span><span class="textfieldMaxCharsMsg">Máximo de caracteres(5).</span></span></td>
      </tr>
      <tr>
        <td><strong>Contraseña Maestra:</strong></td>
        <td><span id="js_inst_pwd">
        <input type="password" name="inst_pwd" id="inst_pwd">
       <span class="passwordRequiredMsg">Campo obligatorio.</span><span class="passwordMinCharsMsg">La contraseña es menor de 6 caractéres.</span><span class="passwordInvalidStrengthMsg">Se necesita al menos 1 mayúscula y 2 números.</span></span></td>
      </tr>
      <tr>
        <td><strong>Repetir Contraseña:</strong></td>
        <td><span id="js_inst_pwd2">
        <input type="password" name="inst_pwd2" id="inst_pwd2">
        <span class="passwordRequiredMsg">Campo obligatorio.</span><span class="passwordMinCharsMsg">La contraseña es menor de 6 caractéres.</span><span class="passwordInvalidStrengthMsg">Se necesita al menos 1 mayúscula y 2 números.</span></span></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>
            <script type="text/javascript">
                var bar1= createBar(300,15,'white',1,'black','blue',85,7,3,"");
                bar1.hideBar();
            </script>
        </td>
      </tr>
    </table>
  </fieldset>
  <p><span style="text-align: center;">
    <input name="Submit" value="Comenzar instalación" align="center" type="submit" onClick="bar1.showBar()">
  </span></p></td>
</tr>
<tr>
  <td width="78"></td>
  <td width="665" style="text-align: center;">
  </td>
  <td width="35"></td>
</tr>
<tr>
<td></td>
<td style="text-align: center;"></td>
</tbody>
</table>
</form>
<script type="text/javascript">
<!--
var sprytextfield3 = new Spry.Widget.ValidationTextField("js_bd_server", "none", {validateOn:["blur", "change"]});
var sprypassword3 = new Spry.Widget.ValidationPassword("js_bd_root_pwd", {validateOn:["blur", "change"]});
var sprytextfield1 = new Spry.Widget.ValidationTextField("js_inst_user", "none", {validateOn:["blur", "change"], minChars:2, maxChars:10});
var sprypassword1 = new Spry.Widget.ValidationPassword("js_inst_pwd", {minChars:6, minNumbers:2, minUpperAlphaChars:1, validateOn:["blur", "change"]});
var sprypassword1 = new Spry.Widget.ValidationPassword("js_inst_pwd2", {minChars:6, minNumbers:2, minUpperAlphaChars:1, validateOn:["blur", "change"]});
var sprytextfield2 = new Spry.Widget.ValidationTextField("js_bd_root_user", "none", {validateOn:["blur", "change"]});
//-->
</script>
</body></html>