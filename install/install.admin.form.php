<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//ES">
<html><head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><title>Creando usuario Administrador</title>
        <link href="../css/Estilo.css" rel="stylesheet" type="text/css" />
        <script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
        <script src="../SpryAssets/SpryValidationPassword.js" type="text/javascript"></script>
        <link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css">
        <link href="../SpryAssets/SpryValidationPassword.css" rel="stylesheet" type="text/css">
    </head>

    <body style="background-color: #336699" >
        <table width="800" border="0" align="center">
            <form id="form1" name="form1" method="post" action="install.admin.php">
            <tr>
                <td width="794" height="134">
                    <div id="datos2" class="Estilo">
                        
                            <table width="700" border="0" align="center">
                                <tr>
                                    <td width="694" height="99"><table width="733" border="0" align="center">
                                            <tr>
                                                <td width="691" height="71"><span class="Titulo">Instalación de soporte:	"Crear un Administrador" --&gt; Paso 2/3</span></td>
                                            </tr>
                                            <td height="20"><p>Cree un usuario administrador y apunte el nombre, la contraseña y el correo asignado.</p></td>
                                        </table>
                                </tr>
                            </table>

                    </div></td></tr><tr bgcolor="#FFFFFF">
                <td height="331"><fieldset>
                        <legend><em>Datos para usuario Administrador de la aplicación:</em></legend>
                        <table width="691" border="0" align="center">
                            <tr>
                                <td width="342"><strong>Nombre del administrador(*):</strong></td>
                                <td width="339"><span id="js_nombre">
                                        <input type="text" name="nombre" id="nombre">
                                        <span class="textfieldRequiredMsg">Se necesita un valor.</span><span class="textfieldMinCharsMsg">No se cumple el mínimo de caracteres requerido.</span></span></td>
                            </tr>
                            <tr>
                                <td><strong>Contraseña del administrador(*): </strong></td>
                                <td><span id="js_contrasena">
                                        <input type="password" name="contrasena" id="contrasena">
                                        <span class="passwordRequiredMsg">Se necesita un valor.</span><span class="passwordMinCharsMsg">No se cumple el mínimo de caracteres requerido.</span><span class="passwordInvalidStrengthMsg">La contraseña no cumple la longitud especificada.</span></span></td>
                            </tr>
                            <tr>
                                <td><strong>Repetir Contraseña(*): </strong></td>
                                <td><span id="js_contrasena2">
                                        <input type="password" name="contrasena2" id="contrasena2">
                                        <span class="passwordRequiredMsg">Se necesita un valor.</span><span class="passwordInvalidStrengthMsg">La contraseña no cumple la longitud especificada.</span></span></td>
                            </tr>
                            <tr>
                                <td><strong>Correo del administrador(*):</strong></td>
                                <td width="339"><span id="js_correo">
                                        <input type="text" name="correo" id="correo">
                                        <span class="textfieldRequiredMsg">Se necesita un valor.</span><span class="textfieldInvalidFormatMsg">Formato no válido.</span></span></td>
                            </tr>
                            <tr>
                                <td><strong>Repetir Correo(*):</strong></td>
                                <td width="339"><span id="js_correo2">
                                        <input type="text" name="correo2" id="correo2">
                                        <span class="textfieldRequiredMsg">Se necesita un valor.</span><span class="textfieldInvalidFormatMsg">Formato no válido.</span></span></td>
                            </tr>
                            <tr>
                                <td height="64"><p><strong>Clave de cifrado de datos(*):</strong></p>
                                    <p>
                                        <input type="submit" name="Submit2" value="Restablecer campos" />
                                    </p></td>
                                <td><label>
                                        <input name="clave" type="text" id="clave" maxlength="16" value="<?php
                                        $frase = "";
                                        for($i=0;$i<16;$i++) {
                                            /* Consigue la frase a partir de caracteres escogidos aleatoriamente. Solo escoge los siguientes:
			A-Z, a-z, 0-9, @
                                            */
                                            $tipo = mt_rand(0,2);
                                            if ($tipo == 0) {
                                                $num = mt_rand(48,57);
                                            }else if ($tipo == 1) {
                                                $num = mt_rand(64,90);
                                            }else if ($tipo == 2) {
                                                $num = mt_rand(97,122);
                                            }
                                            $frase .= chr($num);
                                        }
                                        echo $frase;
                                               ?>"/>
                                        <br>
                                    </label></td>
                            </tr>
                            <tr>
                                <td height="32" colspan="2"><p>
                                        <input type="submit" name="Submit" value="Crear Usuario Administrador" />
                                    </p></td>
                            </tr>
                            <tr>
                                <td height="30" colspan="2" nowrap>Los campos marcados con (*) son obligatorios.                </td>
                            </tr>
                            <tr>
                                <td height="31" colspan="2" nowrap>&nbsp;</td>
                            </tr>
                        </table>
                        <input name="empresa" type="hidden" id="empresa" value="<?php
                        $empresa=base64_decode($_GET["empresa"]);
                        echo $empresa;
                        ?>
                               ">
                    </fieldset></td>
            </tr>
        </form>
    </table>
    <table width="700" border="0"><tr>

        </tr>
    </table>
    <script type="text/javascript">
        <!--
        var sprytextfield1 = new Spry.Widget.ValidationTextField("js_nombre", "none", {validateOn:["blur", "change"], minChars:4});
        var sprytextfield4 = new Spry.Widget.ValidationTextField("js_correo", "email", {validateOn:["blur", "change"]});
        var sprytextfield5 = new Spry.Widget.ValidationTextField("js_correo2", "email", {validateOn:["blur", "change"]});
        var sprypassword1 = new Spry.Widget.ValidationPassword("js_contrasena2", {validateOn:["blur", "change"], minNumbers:2, minUpperAlphaChars:1});
        var sprypassword2 = new Spry.Widget.ValidationPassword("js_contrasena", {validateOn:["blur", "change"], minChars:6, minUpperAlphaChars:1, minNumbers:2});
        //-->
    </script>
</body><body style="background-color: #336699"><table width="200" border="0">
    </table>
</body>
</html>
