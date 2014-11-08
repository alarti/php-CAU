<?php
$nombre = $_POST['nombre'];
$empresa = $_POST['empresa'];
//guardamo la cookie de empresa necesaria para que security pase de largo
$_COOKIE['empresa']=$empresa;

require_once("../inc/oracle.class.php");
require_once("../inc/phpmailer.class.php");
require_once("../inc/smtp.class.php");
require_once("../inc/notifications.class.php");

// oracle
$bd =& new oracle($empresa);
$n= &new notifications();
$n->load_dats();

$sql = "SELECT * FROM Usuarios WHERE nombre='$nombre'";
$bd->consulta($sql);

$num_row = $bd->get_num_rows($row);
if ($num_row == 1) {
    $destinatario = $row["CORREO"][0];
    $asunto = 'Recordatorio de password en empresa '.$empresa;
    $cuerpo = '
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//ES" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Recordatorio de contraseña</title>
    </head>
    <body>
        <b>Hola '.$nombre.'</b>
        <p>
            La contraseña para '.$nombre.' es '.$row["CONTRASENA"][0].', rogamos la apunte en un lugar seguro.
                Gracias
        </p>
    </body>
</html>';

    $mail = new PHPMailer();
    $mail->IsSMTP();
    $mail->SMTPAuth = $n->get_SMTPAuth();
    $mail->SMTPSecure =$n->get_SMTPSecure();
    $mail->Host = $n->get_Host();
    $mail->Port = $n->get_Port();
    $mail->Username = $n->get_Username();
    $mail->Password = $n->get_Password();
    $mail->From = "pwd@soporte".$empresa.".com";
    $mail->FromName = $n->get_FromName();
    $mail->Subject = $asunto;
    $mail->AltBody = $cuerpo;
    $mail->MsgHTML($cuerpo);
//$mail->AddAttachment("files/files.zip");
//$mail->AddAttachment("files/img03.jpg");
    $mail->AddAddress($destinatario, "Destinatario");
    $mail->IsHTML=$n->get_IsHTML();

    if(!$mail->Send()) {
        echo "Error: " . $mail->ErrorInfo;
    } else {
        ?>
<form id="form1" name="form1" method="post" action="login.form.php">
    <div align="center">
        <table width="550" border="0" align="center">
            <tr>
                <td height="24"><p align="center" class="Error"><img src="../img/email_send.png" alt="logo" name="logo" class="logo" id="logo" longdesc="../img/email_send.png" dir="ltr" /></p>
                    <p align="center" class="Error">El correo ha sido enviado correctamente</p>
                    <p align="center" class="Error">
                        <input  type="submit" name="Volver a inicio" id="inicio" value="Volver al Login" />
                    </p></td>
            </tr>
        </table>
    </div>
</form>        
        <?php
    }
}
else {
    include("login.forgetpwd.form.php");
    echo "<div id='salida' class='Error'>El nombre proporcionado no está en la base de datos</div>";
}
?>