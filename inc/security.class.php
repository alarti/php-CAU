<?php
/**
 * Clase que crea registra la sesión del navegador web para establecer una seguridad
 * en torno a la misma. Se crea una relación entre la sesion y el nombre del usuario
 * logueado. Permite recuperar los datos del usuario para comprobar parametros de seguridad
 * desde cualquier parte.
 * @author Alberto Arce, Esteban García
 */

class security {
 
    private $nombre = '';
    private $id = '';
    private $clave = '';
    private $privilegios = '';
    private $empresa='';

    function security() {
        /*
			Al entrar en una pagina segura como es el menu de los diferentes
			Usuarios, comprueba que exista una sesión con un nombre, sino es
			así, entonces significaica que se está intentando acceder a la pagina
			sin haber pasado por la autentificación y se prohibe el acceso
        */
        session_register('nombre');

        if (!isset($_SESSION['nombre'])) {


            // borra la sesión erronea
            session_register('nombre');
            session_unset();
            session_destroy();
?>
<link href='../css/Estilo.css' rel='stylesheet' type='text/css' />
            <form action="../login/login.form.php" method="post">
            <table width="618" border="0" align="center">
              <tr>
                <td width="612" height="95"><div align="center"><img src="../img/security-low.png" alt="logo" name="logo" width="94" height="94" class="logo" id="logo" longdesc="../img/security-low.png" dir="ltr" /></div></td>
              </tr>
              <tr>
                <td align="center"><p><span class="Error">Acceso no autorizado</span></p>
               </td>
              </tr>
              <tr>
                <td align="center"><input type="hidden" name="nIncidencia" value="<?php echo $_GET['nIncidencia'] ?>" /></td>
                </tr>
              <tr>
                <td align="center"><input type="submit" name="Submit" value="Autentificar de nuevo" /></td>
              </tr>
            </table>
            </form>
<?php
            exit;
        }
        else {
            /*
				Una vez comprobado que existe la sesión se comprueba que además
				tenga como id el que se ha grabado en la bd mientras se autentificaba
				sino es el mismo, significaica que se ha manipulado el archivo de sesión
				y por tanto se prohibe el acceso
            */
            $this->nombre = $_SESSION['nombre'];
            $this->id = session_id();


            // mysql
            //require_once("mysql.php");
            require_once("oracle.class.php");
            //$bd =& new mysql();
            
            $bd = new oracle();

            //$sql = "SELECT AES_DECRYPT(contrasenaTmp,'".$this->id."') FROM Usuarios WHERE nombre='".$this->nombre."';";
            $sql = "SELECT contrasenaTmp FROM Usuarios WHERE nombre='".$this->nombre."'";


            $bd->consulta($sql);
            $row = $bd->get_rows();

            $contrasena = $row['CONTRASENATMP'];

            if (!isset($contrasena)) {

                // borra la sesión erronea
                session_register('nombre');
                session_unset();
                session_destroy();

                echo "<link href='/is/Estilo.css' rel='stylesheet' type='text/css' />";
                echo "<p class='Error'>Problemas: Alguien puede haberse conectado con su nombre, o se intenta acceder incorrectamente</p>";
                echo "<a class='Utiles' href='/is/index.php'
								title='Click para autentificar'>
								Autentificar de nuevo</a>";
                exit;
            }
        }

        // consigue la clave general
        //$sql = "SELECT AES_DECRYPT(clave, '".$contrasena."') FROM Usuarios WHERE nombre='$this->nombre';";
        $sql = "SELECT clave FROM Usuarios WHERE nombre='$this->nombre'";
        $bd->consulta($sql);
        $row = $bd->get_rows();
        $this->clave = $row['CLAVE'];



        // consigue los privilegios
        //$sql = "SELECT AES_DECRYPT(privilegios, '".$this->clave."') FROM Usuarios WHERE nombre='$this->nombre';";
        $sql = "SELECT privilegios FROM Usuarios WHERE nombre='$this->nombre'";
        $bd->consulta($sql);
        $row = $bd->get_rows();
        $this->privilegios = $row['PRIVILEGIOS'];


    }

    function get_nombre() {
        return $this->nombre;
    }
    function get_id() {
        return $this->id;
    }
    function get_clave() {
        return $this->clave;
    }
    function get_privilegios() {
        return $this->privilegios;
    }
    function get_empresa() {
        $this->empresa= $_COOKIE['empresa'];
        return $this->empresa;
    }
}
?>
