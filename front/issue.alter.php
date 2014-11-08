<?php
require_once("../inc/security.class.php");
require_once("../inc/oracle.class.php");
require_once("../inc/text.class.php");
require_once("../inc/tracking.class.php");

// seguridad
$s =& new security();
$nombre = $s->get_nombre();
$privilegios = $s->get_privilegios();

// oracle
$bd =& new oracle();

// texto
$txt =& new text();


$borrar = $_POST['borrar'];
$modificar = $_POST['modificar'];
$agregar_trace = $_POST['agregar_trace'];

$nIncidencia = $_POST['nIncidencia'];

//comprobamos que la incidencia no esté cerrada previamente
$sql="Select estado
                from Incidencias
                WHERE nIncidencia='".$nIncidencia."'";
$bd->consulta($sql);
$rows=$bd->get_rows();
$estado_prev=$rows['ESTADO'];
//si no tiene permisos de gestor y la incidencia esta cerrada nos echa
if ((substr($privilegios,3,1)=='0')&& (substr($estado_prev,0,7)=="Cerrada")) {
    $_GET['nIncidencia']=$nIncidencia;
    include("issue.dats.form.php");
    echo "<div id='salida' class='Error'>La incidencia ".$nIncidencia." no se puede modificar ni borrar. Su estado es CERRADO</div>";
    exit;
}
// comprueba si estamos modificando o borrando o agregando incidencia
if (isset($agregar_trace)) {
    include("trace.insert.form.php");
}else
if (isset($borrar)) {
    // Borra una incidencia

    // comprueba si existen privilegios para escribir Incidencias WS (5)
    if (substr($privilegios,5,1)=='1') {
        //Borramos las incidencias
        $sql = "DELETE FROM Incidencias WHERE nIncidencia=".$nIncidencia;
        $bd->consulta($sql);
        //Borramos los seguimientos
        $sql = "DELETE FROM Seguimientos WHERE nIncidencia=".$nIncidencia;
        $bd->consulta($sql);
        $m = new tracking('Incidencia eliminada '.$nIncidencia,'Incidencias');
        header("location: issue.delete.ok.php?ref=".$nIncidencia);

    } else {
        $_GET['nIncidencia']=$nIncidencia;
        include("issue.dats.form.php");
        echo "<div id='salida' class='Error'>No tiene privilegios suficientes para borrar Incidencias</div>";
        exit;
    }

} else if (isset($modificar)) {

    // comprueba si existen privilegios para escribir Incidencias wS (5)
    if (substr($privilegios,5,1)=='1') {
        //si no tiene permisos de gestor y la incidencia esta cerrada nos echa
        if ((substr($privilegios,3,1)=='0')&& (substr($estado_prev,0,7)=="Cerrada")) {
            $_GET['nIncidencia']=$nIncidencia;
            include("issue.dats.form.php");
            echo "<div id='salida' class='Error'>La incidencia ".$nIncidencia." no se puede modificar ni borrar. Su estado es CERRADO</div>";
            exit;
        }
        $prioridad = $_POST['prioridad'];
        $categoria = $_POST['categoria'];
        $estado = $_POST['estado'];
        $titulo= $_POST['titulo'];
        $descripcion = $_POST['descripcion'];
        $tecnico_asig = $_POST['tecnico_asig'];
        $email_resp = $_POST['email_resp'];

        //si la modificacion viene de un user sin derechos recogemos los campos
        //que faltan
        if(!isset($estado)||!isset($tecnico_asig)) {
            $sql="Select
                    estado,
                    tecnico_asig
                  from Incidencias
                  WHERE nIncidencia='".$nIncidencia."'";
            $bd->consulta($sql);
            $rows=$bd->get_rows();
            $estado=$rows['ESTADO'];
            $tecnico_asig=$rows['TECNICO_ASIG'];
        }
        if (
        !$txt->alfanumerico($titulo) ||
                !$txt->alfanumerico($descripcion)/* ||
                !$txt->alfabeticoNet($email_resp)*/
        ) {
            $_GET['nIncidencia']=$nIncidencia;
            include("issue.dats.form.php");
            echo "<div id='salida' class='Error'>Los caracteres válidos para el título y descripción son: A-Z.a-z.0-9.áéíóú.àèìòù.âêîôû.@.€.-._. .,.0.1.2.3.4.5.6.7.8.9.</div>";
            echo "<div id='salida' class='Error'>Los caracteres válidos para el email son: A-Z.a-z.0-9.@._-</div>";
            exit;
        }

        if ($titulo == '' || $descripcion == '' || $email_resp == '') {
            $_GET['nIncidencia']=$nIncidencia;
            include("issue.dats.form.php");
            echo "<div id='salida' class='Error'>Debe rellenar todos los campos</div>";
            exit;
        }

        if ($estado!="Nueva" && $tecnico_asig==-1) {
            $_GET['nIncidencia']=$nIncidencia;
            include("issue.dats.form.php");
            echo "<div id='salida' class='Error'>El estado propuesto necesita un Técnico Asignado </div>";
            exit;
        }
        //si se asigna un técnico pasa a estado=asinada
        if ($estado=="Nueva" && $tecnico_asig!=-1) {
            $estado="Asignada";
        }
        //modificamos la fecha de modificación
        $fecha_modif = date('dmYHis');
        //si se cierra la incidencia ingresamos la fecha en fecha_cierre
        if (substr($estado,0,7)=="Cerrada") {
            $fecha_cierre = $fecha_modif;
        }

        $sql = "UPDATE Incidencias SET
                    prioridad = '".$prioridad."',
                    categoria = '".$categoria."',
                    estado = '".$estado."',
                    titulo ='".$titulo."',
                    fecha_modif ='".$fecha_modif."',
                    fecha_cierre ='".$fecha_cierre."',
                    descripcion ='".$descripcion."',
                    tecnico_asig ='".$tecnico_asig."',
                    email_resp ='".$email_resp."'
                WHERE nIncidencia='".$nIncidencia."'";
        $bd->consulta($sql);
        $m = new tracking('Incidencia modificada '.$nIncidencia,'Incidencias');
        header("location: issue.alter.ok.php?ref=".$nIncidencia);
    }

    else {
        $_GET['nIncidencia']=$InIncidencia;
        include("issue.dats.form.php");
        echo "<div id='salida' class='Error'>No tiene privilegios suficientes para modificar Incidencias</div>";
        exit;
    }
}
?>