<?php
require_once("../inc/security.class.php");

// Seguridad
$s =& new security();
$NOMBRE_USUARIO = $s->get_nombre();
$privilegios = $s->get_privilegios();
$ADMIN_OK = '1';
$sql_inci="select
    nIncidencia,
    prioridad,
    categoria,
    estado,
    titulo,
    TO_CHAR(fecha_alta, 'DD/MM/YYYY HH24:MI:SS' ),
    TO_CHAR(fecha_modif, 'DD/MM/YYYY HH24:MI:SS' ),
    TO_CHAR(fecha_cierre, 'DD/MM/YYYY HH24:MI:SS' ),
    solicitante,
    tecnico_asig,
    email_resp
from Incidencias";

//CABECERA Y ESTILOS
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//ES" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Menu</title>

    <style media="all" type="text/css">@import "../css/menu_style.css";</style>
    <!--[if lt IE 7]>
		<link rel="stylesheet" type="text/css" href="../css/ie6.css" media="screen"/>
	<![endif]-->
</head>
<table width="100%">
    <tr>
    <div class="wrapper1">
        <div class="wrapper"  style="width:1000px;">
            <div class="nav-wrapper">
                <div class="nav-left"></div>
                <div class="nav">
                    <ul id="navigation" style="width:900px">
                        <?php
                        //MENU: CENTRAL (SIN CONTROL DE SEGURIDAD)
                        echo'
                        <li class="#">
                            <a href="control.form.php" target="_self">
                                <span class="menu-left"></span>
                                <span class="menu-mid">Central</span>
                                <span class="menu-right"></span>
                            </a>
                        </li>
                        ';

                        //@todo MENU: INVENTARIO
                        /*if (substr($privilegios,0,1)==$ADMIN_OK) {
                        ?>*/

                        //MENU: CAU

                        echo'
                        <li class="#">
                            <a href="#" target="_self">
                                <span class="menu-left"></span>
                                <span class="menu-mid">C  A  U</span>
                                <span class="menu-right"></span>
                            </a>
                            <div class="sub">
                                <ul>';

                        echo '
                        <li>
                            <a href="issue.insert.form.php">
                                Añadir Incidencia</a>
                        </li>';

                        //si tiene permisos de gestor
                        if (substr($privilegios,3,1)==$ADMIN_OK) {
                            $sql_previo=$sql_inci.
                                " where (tecnico_asig='-1' and not estado like 'Cerrada%')";
                            $sql_previo=base64_encode($sql_previo);
                            echo '
                            <li>
                                <a href="issue.find.php?sql_previo='.$sql_previo.'">
                                   Incidencias sin Asignar</a>
                            </li>';
                        }

                        //si tiene permisos de gestor
                        if (substr($privilegios,3,1)==$ADMIN_OK) {
                            $sql_previo=$sql_inci." where (tecnico_asig='".$NOMBRE_USUARIO."' and not estado like 'Cerrada%')";
                            $sql_previo=base64_encode($sql_previo);
                            echo '
                            <li>
                                <a href="issue.find.php?sql_previo='.$sql_previo.'">
                                   Mis Tareas Pendientes</a>
                            </li>';                                       
                        }
                        //si tiene permisos de escritura de incidencias
                        if (substr($privilegios,5,1)==$ADMIN_OK) {
                            $sql_previo=$sql_inci." where (solicitante='".$NOMBRE_USUARIO."' and not estado like 'Cerrada%')";
                            $sql_previo=base64_encode($sql_previo);
                            echo'
                            <li>
                                <a href="issue.find.php?sql_previo='.$sql_previo.'">
                                  Mis Incidencias</a>
                            </li>';
                        }
                        //si tiene permisos de escritura de incidencias
                        if (substr($privilegios,5,1)==$ADMIN_OK) {
                            echo'
                            <li>
                                <a href="issue.find.form.php">
                                  Busqueda Avanzada</a>
                            </li>';
                        }
                        echo'
                        </ul>
                        </div>
                        </li>';


                        // @todo MENU: INFORMES

                        if (substr($privilegios,0,1)==$ADMIN_OK) {
                            ?>
                        <li class="#">
                            <a href="#" target="_self">
                                <span class="menu-left"></span>
                                <span class="menu-mid">Informes</span>
                                <span class="menu-right"></span>
                            </a>
                            <div class="sub">
                                <ul>
                                    <li>
                                        <a href="stats.mov.php" >Trazabilidad del Sistema</a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                            <?php
                        }
                        //MENU: CONFIGURACION
                        if (substr($privilegios,0,1)==$ADMIN_OK) {
                            ?>
                        <li class="#">
                            <a href="#" target="_self">
                                <span class="menu-left"></span>
                                <span class="menu-mid">Configuración</span>
                                <span class="menu-right"></span>
                            </a>
                            <div class="sub">
                                <ul>
                                    <li>
                                        <a href="user.find.form.php" target="_self">Configurar usuarios</a>
                                    </li>
                                    <li>
                                        <a href="stats.banned.php" target="_self">Desbloquear Usuarios</a>
                                    </li>
                                    <li>
                                        <a href="notifications.dats.form.php" target="_self">Notificaciones</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                            <?php
                        }
//MENU: MI CUENTA (SIN CONTROL DE SEGURIDAD)
                        ?>
                        <li class="#">
                            <a href="user.dats.form.php?Usuario=<?php echo $NOMBRE_USUARIO;?>
                               " target="_self">
                                <span class="menu-left"></span>
                                <span class="menu-mid">Mi cuenta
                                    <?php
                                    echo "(".$NOMBRE_USUARIO.")";
                                    ?>
                                </span>
                                <span class="menu-right"></span>
                            </a>
                        </li>
                        <?php

                        //MENU: AYUDA (SIN CONTROL DE SEGURIDAD)
                        //si es admin
                        if (substr($privilegios,0,1)==$ADMIN_OK)
                        echo '<li class="#">
                            <a href="../doc/admin_guide.pdf" target="_self">
                                <span class="menu-left"></span>
                                <span class="menu-mid">AYUDA
                                </span>
                                <span class="menu-right"></span>
                            </a>
                        </li>';
                        else
                            //si el perfil es técnico muestra ayuda tecnica
                            if (substr($privilegios,2,1)==$ADMIN_OK)
                        echo '<li class="#">
                            <a href="../doc/tecni_guide.pdf" target="_self">
                                <span class="menu-left"></span>
                                <span class="menu-mid">AYUDA
                                </span>
                                <span class="menu-right"></span>
                            </a>
                        </li>';
                        else  //si el perfil es usuario base muestra ayuda usuario
                            if (substr($privilegios,4,1)==$ADMIN_OK)
                        echo '<li class="#">
                            <a href="../doc/user_guide.pdf" target="_self">
                                <span class="menu-left"></span>
                                <span class="menu-mid">AYUDA
                                </span>
                                <span class="menu-right"></span>
                            </a>
                        </li>';
                        else
                            //si el perfil no cumple no muestra ayuda
                        echo '<li class="#">
                            <a href="#" target="_self">
                                <span class="menu-left"></span>
                                <span class="menu-mid">AYUDA
                                </span>
                                <span class="menu-right"></span>
                            </a>
                        </li>';
                        
                        //MENU: SALIR (SIN CONTROL DE SEGURIDAD)
                        ?>
                        <li class="#">
                            <a href="../logout/logout.php" target="_self">
                                <span class="menu-left"></span>
                                <span class="menu-mid">Log off</span>
                                <span class="menu-right"></span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="nav-right"></div>

            </div>
        </div>
    </div>
</tr>
</table>