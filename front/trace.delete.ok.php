<?php
	include("menu.form.php");
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Finalizado...</title>
<link href="../css/Estilo.css" rel="stylesheet" type="text/css" />
</head>

<body class="Estilo">
<form id="form1" name="form1" method="post" action="issue.dats.form.php?nIncidencia=<?php echo($_GET['nIncidencia'])?>">
  <table width="618" border="0" align="center">

    <tr>
      <td width="612" height="95"><div align="center"><img src="../img/ok.png" alt="logo" name="logo" width="94" height="69" class="logo" id="logo" longdesc="../../..img/ok.png" dir="ltr" /></div></td>
    </tr>
    <tr>
      <td align="center"><p>Seguimiento número <?php echo($_GET['ref'])?> de la incidencia <?php 
      echo($_GET['nIncidencia'])
      ?> eliminado correctamente.
        </p>
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
