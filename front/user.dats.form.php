<?php
require_once("../inc/security.class.php");
require_once("../inc/oracle.class.php");
require_once("../inc/text.class.php");
include("menu.form.php");
// seguridad
$s =& new security();
$nombre = $s->get_nombre();
$clave = $s->get_clave();
$logged_privilegios=$s->get_privilegios();
// oracle
$bd =& new oracle();

// texto
$txt =& new text();

// el numero de Usuario se le pasa mediante parametro el la barra de Prioridades

$nombreUsuario=$_REQUEST['Usuario'];
//si no recibe nada es un rollback de  desde include y cojemos el user actual
if(!isset($nombreUsuario))
    $nombreUsuario=$nombreActual;


// Busca el Usuario para mostrar todos los datos
$sql = "SELECT nombre,correo,privilegios,contrasena FROM Usuarios WHERE nombre = '".$nombreUsuario."'";
//$sql = "SELECT nombre, correo, AES_DECRYPT(privilegios,'".$clave."'), contrasena  FROM Usuarios WHERE nombre = '".$nombreUsuario."';";
$bd->consulta($sql);


$row = $bd->get_rows();
$nombreActual = $row[0];
$correo = $row[1];
$privilegios = $row[2];
$pass=$row[3];

?>
<link href="../css/Estilo.css" rel="stylesheet" type="text/css" />
<script src="../SpryAssets/SpryCollapsiblePanel.js" type="text/javascript"></script>
<link href="../SpryAssets/SpryCollapsiblePanel.css" rel="stylesheet" type="text/css" />
<script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<div id="CollapsiblePanel1" class="CollapsiblePanel">
    <div class="CollapsiblePanelTab">Datos del usuario --&gt; <?php echo $nombreActual; ?></div>
    <div class="CollapsiblePanelContent">
        <div id="salida">
            <form id="form1" name="form1" method="post" action="user.alter.php">
                <table width="100%" border="0" align="center">
                    <tr>
                        <td width="20%" bgcolor="#CCCCCC" class="Estilo"><label for="label">
                                <div align="left">Nombre:</div><input name="nombre" type="hidden" id="nombre" value="<?php echo $nombreActual; ?>">
                            </label></td>
                        <td width="80%" class="Estilo"><div align="left"><?php echo $nombreActual; ?></div>
                            <label for="label"></label>
                            <div align="left"></div></td>
                    </tr>
                    <tr>
                        <td width='20%' bgcolor="#CCCCCC" class='Estilo'><label for='label'>
                                <div align="left">Contraseña de  <?php echo $nombre; ?>:</div>
                            </label></td>
                        <td><span id="js_contrasena0">
                                <input name="contrasena0" type="password" id="contrasena0" size="30"
                                       maxlength="16" />
                                <span class="textfieldRequiredMsg">Se necesita un valor.</span></span></tr>
                    <tr>
                        <td width="20%" bgcolor="#CCCCCC" class="Estilo"><label for="label">
                                <div align="left">Contraseña nueva para <?php echo $nombreActual; ?>:</div>
                            </label></td>
                        <td><span id="js_contrasena">
                                <input name="contrasena1" type="password" id="contrasena1" size="30" />
                                <span class="textfieldRequiredMsg">Se necesita un valor.</span></span></td>
                    </tr>
                    <tr>
                        <td width="20%" bgcolor="#CCCCCC" class="Estilo"><label for="label">
                                <div align="left">Repetir contraseña para <?php echo $nombreActual; ?>:</div>
                            </label></td>
                        <td><span id="js_contrasena2">
                                <input name="contrasena2" type="password" id="contrasena2" size="30" />
                                <span class="textfieldRequiredMsg">Se necesita un valor.</span></span></td>
                    </tr>
                    <tr>
                        <td width="20%" bgcolor="#CCCCCC" class="Estilo"><label for="label">
                                <div align="left">Correo:</div>
                            </label></td>
                        <td><span id="js_correo">
                                <input name="correo" type="text" id="correo" value="<?php echo $correo ?>" size="30" />
                                <span class="textfieldRequiredMsg">Se necesita un valor.</span><span class="textfieldInvalidFormatMsg">Formato no válido.</span></span></td>
                    </tr>
                </table>
                <hr />
                <table width="100%" border="0" align="center">
                    <tr>
                        <td class="Estilo">&nbsp;</td>
                        <td width="64" class="Estilo">&nbsp;</td>
                        <td width="86" bgcolor="#CCCCCC" class="Estilo">Administrador</td>
                        <td width="65" bgcolor="#CCCCCC" class="Estilo">Gestor</td>
                        <td width="37" bgcolor="#CCCCCC" class="Estilo">Incidencia</td>
                        <td width="50" bgcolor="#CCCCCC" class="Estilo">Inventario</td>
                        <td width="45" bgcolor="#CCCCCC" class="Estilo">Seguimiento</td>
                    </tr>
                    <tr>
                        <td rowspan="2" class="Estilo">Privilegios:</td>
                        <td bgcolor="#CCCCCC" class="Estilo">Lectura:</td>
                        <td class="Estilo"><div align="center">
                                <?php
                                //si el usuario logeado no tiene privligeios de escitura en usuarios no puede modificar los permisos
                                if (substr($logged_privilegios,3,1)=='0')
                                    $disabled="disabled";
                                else $disabled="";
                                //caso especial para no permitir a un usuario con permisos de usuario escalar a admin
                                if (substr($logged_privilegios,0,1)=='0')
                                    $disabled_root_acces="disabled";
                                else $disabled_root_acces="";

                                if (substr($privilegios,0,1)=='1') {
                                    echo '<input name="lA" type="checkbox" id="lA" value="1" checked="checked" '.$disabled_root_acces.'/>';
                                } else {
                                    echo '<input name="lA" type="checkbox" id="lA" value="1" '.$disabled_root_acces.'/>';
                                }
                                ?>
                            </div></td>

                        <td width="10%"  class="Estilo"><div align="center">
                                <?php
                                if (substr($privilegios,2,1)=='1') {
                                    echo '<input name="lE" type="checkbox" id="lE" value="1" checked="checked" '.$disabled.'/>';
                                } else {
                                    echo '<input name="lE" type="checkbox" id="lE" value="1" '.$disabled.'/>';
                                }
                                ?>
                            </div></td>
                        <td width="10%" class="Estilo"><div align="center">
                                <?php
                                if (substr($privilegios,4,1)=='1') {
                                    echo '<input name="lS" type="checkbox" id="lS" value="1" checked="checked" '.$disabled.'/>';
                                } else {
                                    echo '<input name="lS" type="checkbox" id="lS" value="1" '.$disabled.'/>';
                                }
                                ?>
                            </div></td>
                        <td width="10%" class="Estilo"><div align="center">
                                <?php
                                if (substr($privilegios,6,1)=='1') {
                                    echo '<input name="lU" type="checkbox" id="lU" value="1" checked="checked" '.$disabled.'/>';
                                } else {
                                    echo '<input name="lU" type="checkbox" id="lU" value="1" '.$disabled.'/>';
                                }
                                ?>
                            </div></td>
                        <td width="10%" class="Estilo"><div align="center">
                                <?php
                                if (substr($privilegios,8,1)=='1') {
                                    echo '<input name="lC" type="checkbox" id="lC" value="1" checked="checked" '.$disabled.'/>';
                                } else {
                                    echo '<input name="lC" type="checkbox" id="lC" value="1" '.$disabled.'/>';
                                }
                                ?>
                            </div></td>
                    </tr>
                    <tr>
                        <td bgcolor="#CCCCCC" class="Estilo">Escritura:</td>
                        <td width="10%" class="Estilo"><div align="center">
                                <?php
                                if (substr($privilegios,1,1)=='1') {
                                    echo '<input name="wA" type="checkbox" id="wA" value="1" checked="checked" '.$disabled_root_acces.'/>';
                                } else {
                                    echo '<input name="wA" type="checkbox" id="wA" value="1" '.$disabled_root_acces.'/>';
                                }
                                ?>
                            </div></td>
                        <td width="10%" class="Estilo"><div align="center">
                                <?php
                                if (substr($privilegios,3,1)=='1') {
                                    echo '<input name="wE" type="checkbox" id="wE" value="1" checked="checked" '.$disabled.'/>';
                                } else {
                                    echo '<input name="wE" type="checkbox" id="wE" value="1" '.$disabled.'/>';
                                }
                                ?>
                            </div></td>
                        <td width="10%" class="Estilo"><div align="center">
                                <?php
                                if (substr($privilegios,5,1)=='1') {
                                    echo '<input name="wS" type="checkbox" id="wS" value="1" checked="checked" '.$disabled.'/>';
                                } else {
                                    echo '<input name="wS" type="checkbox" id="wS" value="1" '.$disabled.'/>';
                                }
                                ?>
                            </div></td>
                        <td width="10%" class="Estilo"><div align="center">
                                <?php
                                if (substr($privilegios,7,1)=='1') {
                                    echo '<input name="wU" type="checkbox" id="wU" value="1" checked="checked" '.$disabled.'/>';
                                } else {
                                    echo '<input name="wU" type="checkbox" id="wU" value="1" '.$disabled.'/>';
                                }
                                ?>
                            </div></td>
                        <td width="10%" class="Estilo"><div align="center">
                                <?php
                                if (substr($privilegios,9,1)=='1') {
                                    echo '<input name="wC" type="checkbox" id="wC" value="1" checked="checked" '.$disabled.'/>';
                                } else {
                                    echo '<input name="wC" type="checkbox" id="wC" value="1" '.$disabled.'/>';
                                }
                                ?>
                            </div></td>
                    </tr>
                </table>
                <hr />
                <table width="100%" border="0" align="center">
                    <tr>
                        <td><input type="submit" name="modificar" value="Modificar" />
                            <input type="submit" name="borrar" value="Borrar" />
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</div>
<?	
//include ("buscaUsuario.php");
?>
<script type="text/javascript">
    <!--
    var CollapsiblePanel1 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel1");
    var sprytextfield1 = new Spry.Widget.ValidationTextField("js_correo", "email", {validateOn:["blur", "change"]});
    var sprytextfield2 = new Spry.Widget.ValidationTextField("js_contrasena0", "none", {validateOn:["blur", "change"]});
    var sprytextfield3 = new Spry.Widget.ValidationTextField("js_contrasena", "none", {validateOn:["blur", "change"]});
    var sprytextfield4 = new Spry.Widget.ValidationTextField("js_contrasena2", "none", {validateOn:["blur", "change"]});
    //-->
</script>
