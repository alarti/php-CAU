<?php
//recuperamos los parametros por si viene de una redirección
$nIncidencia=$_POST['nIncidencia'];
//echo $nIncidencia;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//ES" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"</meta>
        <title>Soporte</title>
        <link rel="stylesheet" type="text/css" href="../css/view.css" media="all"</link>
        <?//Ejecutamos el javascrip correspondiente al coloreado de input seleccionado y al posicionamiento de foco?>
        <script type='text/javascript' src="../js/view.js"></script>
        <style type="text/css">
            <!--
            .Estilo3 {
                font-size: 24px;
                font-family: "Times New Roman", Times, serif;
            }
            -->
        </style>
    </head>

    <body id="main_body">

        <img id="top" src="../img/top.png" alt=""/>

        <div id="form_container">

            <h1>&nbsp;</h1>
            <form id="form1" method="post" action="login.php"><div class="form_description">
                    <p align="center">&nbsp;</p>
                    </div>
                <table align="center" style="width: 629px">
                  <tr>
                    <td style="width: 348px"><ul style="width: 53%; font-weight: bold;" >
                        <li id="li_1" style="left: 0px; width: 323px; top: 0px">
                          <label class="description" for="nombre">Usuario </label>
                          <div style="width: 311px">
                            <input id="nombre" name="nombre" class="element text small" type="text" maxlength="255" value="" style="width: 53%"/>
                          </div>
                        </li>
                      <li id="li_2" style="left: 0px; width: 321px; top: 0px" >
                          <label class="description" for="contrasena">Password </label>
                          <div style="width: 311px">
                            <input id="contrasena" type='password' name="contrasena" class="element text small" maxlength="255" value="" style="width: 52%"/>
                          <a href="login.forgetpwd.form.php" tabindex="1">Recordar Password</a></div>
                      </li>
                    </ul>
                        <p>&nbsp;</p>
                      <ul style="width: 53%; font-weight: bold;" >
                          <li >Empresa
                            <div style="width: 311px">
                                <input id="empresa" type='text' name="empresa" class="element text small" maxlength="255" value="" style="width: 52%" />
                              </div>
                          </li>
                      </ul>
                      <div align="right"><span style="width: 311px"><span class="buttons">
                          <input id="saveForm" class="button_text" type="submit" name="submit" value="Acceder" />
                      </span></span></div></td>
                    <td bgcolor="#31659C"><div align="center"><img src="../img/logo.png" alt="logo" name="logo" class="logo" id="logo" longdesc="../img/logo.png" dir="ltr" /></div></td>
                    <td></td>
                  </tr>
                  <tr>
                    <td style="width: 348px"></td>
                    <td></td>
                    <td></td>
                  </tr>
                  <tr>
                    <td height="2" style="width: 348px"></td>
                    <td></td>
                    <td align="center"><input type="hidden" name="nIncidencia" value="<?php echo $nIncidencia ?>" /></td>
                  </tr>
                </table>
              <div align="center">Desarrollado para <a href="http://www.unileon.es">Ingeniería del Software (Unileon 2009)</a>
                </div>
            </form>
        </div>
        <img id="bottom" src="../img/bottom.png" alt=""/>
    </body>

</html>