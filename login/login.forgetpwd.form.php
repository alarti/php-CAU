<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//ES" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Administrador de Bases de Datos</title>
        <link href="../css/Estilo.css" rel="stylesheet" type="text/css" />
        <style type="text/css">
            <!--
            #salida {
                position:absolute;
                width:537px;
                height:21px;
                z-index:2;
                left: 154px;
                top: 313px;
            }
            #datos {
                position:absolute;
                width:697px;
                height:257px;
                z-index:1;
                left: 155px;
                top: 50px;
                background-color: #FFFFFF;
            }
            -->
        </style>
    </head>

    <body style="background-color: #336699">

        <div id="salida"></div>
        <div id="datos">
            <form id="form1" name="form1" method="post" action="login.mail.php">
                <table width="582" border="0" align="center" bgcolor="#FFFFFF">
                    <tr>
                        <td width="525"><div align="center" class="Titulo">Recordarme Contraseña</div>
                            <p>&nbsp;</p></td>
                    </tr>
                    <tr>
                        <td><div align="center" class="Estilo">
                                <p align="left">Se enviará su contraseña al correo electrónico proporcionado </p>
                                <fieldset>
                                    <legend>Datos del usuario:</legend>
                                    <table width="373" border="0" align="center">
                                        <tr>
                                            <td><div align="right">Usuario:</div></td>
                                            <td><input type="nombre" name="nombre" /></td>
                                        </tr>
                                        <tr>
                                            <td><div align="right">empresa:</div></td>
                                            <td><input type="text" name="empresa" id="empresa" /></td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td><input type="submit" name="Submit" value="Recordar" /></td>
                                        </tr>
                                    </table>
                                </fieldset>
                                <div align="right"></div>
                            </div></td>
                    </tr>
                </table>
            </form>
        </div>
    </body>
</html>
