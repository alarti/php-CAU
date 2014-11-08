<?php
/*
* @author Alberto Arce y Esteban García
* @version 	002
*/
class oracle {

    /**
     * almacena el link de oracle
     */
    private $enlace = 0;
    /**
     * almacena el resultado de una consulta
     */
    private $resultado = 0;
    /**
     * almacena el nombre de la base de datos
     */
    private $bd = '';
    /**
     * almacena el USUARIO de la base de datos
     */
    private $usuariobd = '';
    /**
     * almacena el pass de la base de datos
     */
    private $clavebd = '';
    /**
     * almacena el pass de la base de datos
     */
    private $master_file_state= false;

//------------------------------------------------------------------------------------------
//
//------------------------------------------------------------------------------------------	
// clase constructor
// esta clase que conecta con oracle
// @var instancia puede ser:
//      1-""-->lee la cookie almacenada en el login para saber la empresa actual
//      2-"root"-->se conecta como root
//      3-"$variable"--> se conecta con los datos leidos del fichero $variable.cfg
//------------------------------------------------------------------------------------------	
    function oracle($instancia="") {
        if($instancia=="root") {
            $fdatos = "../dat/root";
        }
        else {
            if ($instancia=="") {

                $instancia= $_COOKIE['empresa'];
                /*$fdatos = "../dat/ea".$sesion;
                $archivo = fopen($fdatos, "r");
                if ($archivo) {
                    $cadena = fgets($archivo, 255);
                    $array_datos = split(';',$cadena);
                    $instancia = $array_datos[0];
                }
                fclose ($archivo);*/

            }
            $fdatos = "../dat/".$instancia.".cfg";
        }

        if (file_exists($fdatos)) {
            $archivo = fopen($fdatos , "r");
            if ($archivo) {
                $cadena = fgets($archivo, 255);
                $array_datos = split(';',$cadena);
                $this->bd = $array_datos[0];
                $this->usuariobd = $array_datos[1];
                $this->clavebd = $array_datos[2];
                //echo $this->bd;//echo $this->Equipobd;//echo $this->clavebd;
                fclose ($archivo);
            }
            //si la comprobación del fichero es buena establecemos la empresa ok
            //Generamos la cokkie para indicar la empresa en este usuario
            //setcookie("empresa",$inst_user,0,"../inc/");
            $this->master_file_state= true;
            // conecta con el servidor oracle
            $this->enlace = oci_connect($this->usuariobd, $this->clavebd, $this->bd);
        }
        else {
            $this->master_file_state= false;
        }

        //@oracle_query("SET NAMES 'utf8'");
        // comprueba que está estabecida la conexón con el servidor
        if ((! $this->master_file_state) OR (! $this->enlace)) {
            if (! $this->enlace) {
                ?><meta http-equiv='Content-Type' content='text/html; charset=UTF-8'><title>Asistente de instalación</title>
<link href='../css/Estilo.css' rel='stylesheet' type='text/css'>
<table width="550" border="0" align="center">
    <tr>
        <td height="24"><p align="center" class="Error"><img src="../img/warning_blue.png" alt="logo" name="logo" class="logo" id="logo" longdesc="../img/warning_blue.png" dir="ltr" /></p>
            <p class="Error">La empresa facilitada no existe o no dispone de acceso a la misma.</p></td>
    </tr>
</table>
                <?php
            }
            ?>
<table width="552" border="0" align="center">
    <tr>
        <td width="546">

            <p><span class="Error">No se han detectado el espacio de trabajo
                    necesario para que la aplicación funcione.</span></p></td>
    </tr>
    <tr>
        <td height="50"><p><a href='../install/install.form.php' class="Estilo">Puede iniciar el asistente de creación de empresa pulsando aquí. (Necesario acceso ROOT) </a></p>
        </td>
    </tr>
    <tr>
        <td><input type='button' value='Volver atras' onClick='history.go(-1);'>
        </td>
    </tr>
</table>
<p><?php
                exit;
            }
        }
        function consulta($sql) {
            $this->resultado = oci_parse($this->enlace, $sql);
            oci_execute($this->resultado, OCI_DEFAULT);
            if (!$this->resultado) {
                $error = oci_error();
                echo "$error";
                exit;
            }
            oci_commit($this->enlace);

        }

        function get_rows() {
            $rows= oci_fetch_array($this->resultado);
            //oci_free_statement($this->resultado);
            return $rows;
        }

        function get_num_rows(&$rows=null) {
            //return  ocirowcount($this->resultado);
            //oci_free_statement($this->resultado);
            $nrows=oci_fetch_all($this->resultado,$rows);
            return $nrows;
            //return $nrows;
        }

        function get_bd() {
            return $this->usuariobd;
        }

        function get_affected_rows() {
            return oci_affected_rows($this->enlace);
        }

        function autoincrementa($primary,$tabla) {

            $sql = "SELECT $primary FROM $tabla ORDER BY $primary";

            //oci_select_db($this->bd, $this->enlace);
            //$this->resultado = oci_query($sql, $this->enlace);
            $this->resultado = oci_parse($this->enlace, $sql);
            oci_execute($this->resultado,OCI_DEFAULT);
            if (!$this->resultado) {
                $error = oci_error();
                //echo "$error";
                exit;
            }

            $i = 1;
            $asignado = false;
            while ($row = oci_fetch_array($this->resultado)) {
                if (!$asignado) {

                    if ($i != $row[0]) {
                        $num = $i;
                        $asignado = true;
                    }
                    $i++;
                }
            }
            if (!$asignado) {
                $num = $i;
            }

            return $num;
        }


        /*
	función que devuelve las propiedades de un campo en el siguiente orden:
			[0] => nombre_campos, [1] => tipo, [2] => longitud, [3] => banderas;

	parametros: nombre de la tabla: string
	salida: matriz
	filas: cada uno de los campos
	columnas: las propiedades
        */
        function get_type($nombre_tabla) {

            $query = oci_list_fields($this->bd, $nombre_tabla);
            $camposTabla = oci_num_fields($query);

            for($i=0;$i<$camposTabla;$i++) {

                $salida[$i][0] = oci_field_name($query,$i);
                $salida[$i][1] = oci_field_type($query,$i);
                $salida[$i][2] = oci_field_len($query,$i);
                $salida[$i][3] = oci_field_flags($query,$i);

            }
            return $salida;
        }
    }



    ?>