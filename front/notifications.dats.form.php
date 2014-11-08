<?php
	include("menu.form.php");
        require("../inc/notifications.class.php");

        $n= &new notifications();
        $n->load_dats();

?>
<head>
<link href="../css/Estilo.css" rel="stylesheet" type="text/css" />
<script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryCollapsiblePanel.js" type="text/javascript"></script>
<link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<link href="../SpryAssets/SpryCollapsiblePanel.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="CollapsiblePanel1" class="CollapsiblePanel">
  <div class="CollapsiblePanelTab" tabindex="0">Servicio de notificaciones de empresa  SMTP Relay</div>
  <div class="CollapsiblePanelContent">
    <div id="datos">
      <form id="form1" name="form1" method="post" action="notifications.alter.php">
        <table width="100%" border="0">
          <tr>
            <td width="20%" class="Estilo"><label for="label">Codificación de Caracteres:</label></td>
            <td width="80%"><span id="js_CharSet">
                    <input type="text" name="CharSet" id="CharSet" value="<?php echo $n->get_CharSet() ?>"/>
            <span class="textfieldRequiredMsg">Se necesita un valor.</span></span></td>
          </tr>
          <tr>
            <td class="Estilo">Autentificación SMTP activa:</td>
            <td width="80%"><input type="checkbox" name="SMTPAuth" id="SMTPAuth" <?php if ($n->get_SMTPAuth()) echo "checked" ?> /></td>
          </tr>
          <tr>
            <td class="Estilo">Método de Conexión SMTP segura:</td>
            <td width="80%"><span id="js_SMTPSecure">
                    <input type="text" name="SMTPSecure" id="SMTPSecure" value="<?php echo $n->get_SMTPSecure() ?>"/>
            <span class="textfieldRequiredMsg">Se necesita un valor.</span></span></td>
          </tr>
          <tr>
            <td class="Estilo">Host SMTP:</td>
            <td width="80%"><span id="js_Host">
                    <input type="text" name="Host" id="Host" value="<?php echo $n->get_Host() ?>"/>
            <span class="textfieldRequiredMsg">Se necesita un valor.</span></span></td>
          </tr>
          <tr>
            <td class="Estilo">Puerto SMTP:</td>
            <td width="80%"><span id="js_Port">
                    <input type="text" name="Port" id="Port" value="<?php echo $n->get_Port() ?>" />
            <span class="textfieldRequiredMsg">Se necesita un valor.</span><span class="textfieldInvalidFormatMsg">Formato no válido.</span></span></td>
          </tr>
          <tr>
            <td class="Estilo">Usuario SMTP:</td>
            <td width="80%"><span id="js_Username">
                    <input type="text" name="Username" id="Username" value="<?php echo $n->get_Username() ?>"/>
            <span class="textfieldRequiredMsg">Se necesita un valor.</span></span></td>
          </tr>
          <tr>
            <td class="Estilo">Contraseña SMTP:</td>
            <td width="80%"><span id="js_Password">
                    <input type="password" name="Password" id="Password" value="<?php echo $n->get_Password() ?>"/>
            <span class="textfieldRequiredMsg">Se necesita un valor.</span></span></td>
          </tr>
          <tr>
            <td class="Estilo">Alias del correo:</td>
            <td width="80%"><span id="js_FromName">
                    <input type="text" name="FromName" id="FromName" value="<?php echo $n->get_FromName() ?>"/>
            <span class="textfieldRequiredMsg">Se necesita un valor.</span></span></td>
          </tr>
          <tr>
            <td class="Estilo">Codificación HTML activa:</td>
            <td width="80%"><input type="checkbox" name="IsHTML" id="IsHTML" <?php if ($n->get_IsHTML()) echo "checked" ?>/></td>
          </tr>
</table>
        <table width="100%" border="0">
          <tr>
            <td><input type="submit" name="Submit" value="Guardar" /></td>
          </tr>
        </table>
        <table width="100%" border="0">
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
var sprytextfield1 = new Spry.Widget.ValidationTextField("js_Port", "integer", {validateOn:["blur", "change"]});
var sprytextfield2 = new Spry.Widget.ValidationTextField("js_Username", "none", {validateOn:["blur", "change"]});
var sprytextfield3 = new Spry.Widget.ValidationTextField("js_Password", "none", {validateOn:["blur", "change"]});
var sprytextfield4 = new Spry.Widget.ValidationTextField("js_FromName", "none", {validateOn:["blur", "change"]});
var sprytextfield5 = new Spry.Widget.ValidationTextField("js_CharSet", "none", {validateOn:["blur", "change"]});
var sprytextfield6 = new Spry.Widget.ValidationTextField("js_SMTPSecure", "none", {validateOn:["blur", "change"]});
var sprytextfield7 = new Spry.Widget.ValidationTextField("js_Host", "none", {validateOn:["blur", "change"]});
//-->
</script>
