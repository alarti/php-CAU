<?php
include("menu.form.php");
        require("../inc/mail.class.php");

        $nIncidencia=$_GET['ref'];
        $m= & new mail();
        $m->genera_reporte($nIncidencia);
        $m->enviar();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//ES" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Configuración</title>
<link href="../css/Estilo.css" rel="stylesheet" type="text/css" />
</head>

<body class="Estilo">
<form id="form1" name="form1" method="post" action="issue.dats.form.php?nIncidencia=<?php echo($_GET['ref'])?>">
  <table width="618" border="0" align="center">
    <tr>
      <td width="612" height="95"><div align="center"><img src="../img/ok.png" alt="logo" name="logo" width="94" height="69" class="logo" id="logo" longdesc="../img/ok.png" dir="ltr" /></div></td>
    </tr>
    <tr>
      <td align="center"><p>la incidencia <?php echo($_GET['ref'])?> ha sido modificada correctamente. </p>
      <label></label></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="center"><input type="submit" name="Submit" value="Aceptar" /></td>
    </tr>
  </table>
</form>
</body>
</html>
