<?php
//Se almacenan los datos del root y de los users creados en ficheros dentro de la
//@todo Ficheros en carpeta dat. Método temporal antes de cifrar/descifrar estos datos-->md5? al guardar y leer en disco
//Recogemos los datos de conexión a oracle en modo root
$bd_root_user = $_POST['bd_root_user'];
$bd_root_pwd = $_POST['bd_root_pwd'];
$bd_server = $_POST['bd_server'];

//Recogemos datos de nueva instancia en oracle
$inst_user =$_POST['inst_user'];
$inst_pwd = $_POST['inst_pwd'];
$inst_pwd2 = $_POST['inst_pwd2'];

//Filtramos las dos contraseñas para ver si son iguales
if ($inst_pwd != $inst_pwd2) {
    include("install.form.php");
    echo "<div id='salida' class='Error'>Las constraseñas no conciden</div>";
    exit;
}

//Datos del root de sgdb oracle
$archivo_root = fopen( "../dat/root" , "w");
if ($archivo_root) {
    fputs ($archivo_root, $bd_server.";");
    fputs ($archivo_root, $bd_root_user.";");
    fputs ($archivo_root, $bd_root_pwd.";");
}
fclose ($archivo_root);

//Datos de new_user=empresa en sgdb oracle
$archivo = fopen( "../dat/".$inst_user.".cfg", "w");
if ($archivo) {
    fputs ($archivo, $bd_server.";");
    fputs ($archivo, $inst_user.";");
    fputs ($archivo, $inst_pwd.";");
}
fclose ($archivo);

/* Lo primero a crear es el usuario $inst_user para la BBDD de oracle
 * que nos permitira crear las tablas y acceder a ellas
 * Accedemos con el usuario facilitado modo root para crear otro root=$inst_user
 * O de ahora en adelante "empresa"
*/

//Comprobamos los datos de nueva instancia conectando como root
require_once("../inc/oracle.class.php");
$bd = new oracle("root");

//obtenemos una lista de los esquemas del servidor que se llamen como $inst_user
$bd->consulta("select * from DBA_USERS where USERNAME = '".mb_strtoupper($inst_user)."'");
$numbd = $bd->get_num_rows($rows);
if ($numbd > 0) {
    include("install.form.php");
    echo "<p align='center' class='Error'>La Empresa ".$inst_user." ya existe. Seleccione otro nombre</p>";
    exit;
}
//establecemos la ruta para el directorio de la tablespaces
chdir('../dat/');
$ruta_tablespace=getcwd();

//creamos el tablespace
$sql = "create tablespace ".$inst_user."_tablespace logging datafile '".$ruta_tablespace."/".$inst_user."_tablespace.dbf' size 32m autoextend on next 32m maxsize 2048m extent management local";
$bd->consulta($sql);
//... el usuario
$sql = "CREATE USER ".$inst_user." IDENTIFIED BY ".$inst_pwd." DEFAULT TABLESPACE ".$inst_user."_tablespace QUOTA UNLIMITED ON ".$inst_user."_tablespace";
$bd->consulta($sql);
// .. y le damos permisos
$sql = "GRANT ALL PRIVILEGES TO ".$inst_user." IDENTIFIED BY ".$inst_pwd." WITH ADMIN OPTION";
$bd->consulta($sql);

//------------------------------------------------------------------------------
//Restablecemos la conexion con oracle y el nuevo  esquema/usuario/empresa
//------------------------------------------------------------------------------
$bd =new oracle($inst_user);
/*
	// crea la tabla de los Usuarios
	/*
	La tabla de Usuarios de la base de datos indica el nombre de
	Usuario que se conecta, la contraseña que va a utilizar para
	autentificarse, el correo electronico al que se devolvera una
	nueva contraseña si se pierde la anterior, una clave con la que
	se encriptan todos los datos de los Equipos y Incidencias. Esta clave
	la genera el primer Usuario y pasa a los siguientes
	Usuarios según los vayan creando. La contrasenaTmp es la
	contraseña original encriptada con el numero de sesión que devuelve la
	function session_id, de esta manera se comprueba que no se ha manipulado
	el archivo de la sesión

	privilegios:
					lA (lectura Admin), wA (escritura Admin),
					lE (lectura Usuarios), wE (escritura Usuarios),
					lS (lectura Incidencias), wS (escritura Incidencias),
					lU (lectura Equipos), wU (escritura Equipos),
					lC (lectura Seguimiento), wC (escritura Seguimiento),

			Se almacenan con un bit 1 ó 0 cada uno en el siguiente orden:

			lA, wA, lE, wE, lS, wS, lU, wU, lC, wC

			por lo que la longitud de este campo es: 10 bits
*/
$sql = "CREATE TABLE Usuarios (
		nombre VARCHAR(20) NOT NULL,
		contrasena VARCHAR(50) NOT NULL,
		correo VARCHAR(50) NOT NULL,
		clave VARCHAR(50) NOT NULL,
		contrasenaTmp VARCHAR(50),
		privilegios VARCHAR(50) NOT NULL,
		PRIMARY KEY (nombre)
	)";

$bd->consulta($sql);

// crea la tabla de Incidencias
$sql = "CREATE TABLE Incidencias (
		nIncidencia INT NOT NULL,
                prioridad VARCHAR(50),
                categoria varchar(50),
                estado varchar(50),
                titulo VARCHAR(100) NOT NULL,
                descripcion VARCHAR(1000) NOT NULL,
                fecha_alta TIMESTAMP,
                fecha_modif TIMESTAMP,
                fecha_cierre TIMESTAMP,
                solicitante VARCHAR(20),
                tecnico_asig VARCHAR(20),
                email_resp VARCHAR(50),
		PRIMARY KEY (nIncidencia)
	)";

$bd->consulta($sql);

// crea la tabla de seguimientos
$sql = "CREATE TABLE Seguimientos (
		nSeguimiento INT NOT NULL,
                nIncidencia INT NOT NULL,
                titulo VARCHAR(100) NOT NULL,
                descripcion VARCHAR(1000) NOT NULL,
                autor VARCHAR(20) NOT NULL,
                fecha TIMESTAMP,
                fecha_modif TIMESTAMP,
		PRIMARY KEY (nSeguimiento)
	)";

$bd->consulta($sql);

// crea la tabla de Equipos
$sql = "CREATE TABLE Equipos (
		nINVENTARIO INT NOT NULL,
		nIncidencia INT NOT NULL,
		ESTADO_EQUIPO int not null,
		nombre varchar(20),
		apellidos varchar(20),
		TECNICO_ASIGNADO varchar(20),
		ESTADO varchar(20),
		alta varchar(20),
		fAlta varchar(20),
		servicio varchar(20),
		observaciones varchar(100),
		PRIMARY KEY ( nINVENTARIO )
	)";

$bd->consulta($sql);


// crea una tabla para guardar las posibles IP's bloqueadas por un acceso incorrecto
$sql =  "CREATE TABLE bloqueo (
		ip VARCHAR(15) NOT NULL,
		id VARCHAR(32) NOT NULL,
		intentos INT,
		nombreUtilizado VARCHAR(20) NOT NULL,
		contrasenaUtilizada VARCHAR(50) NOT NULL,
		fecha TIMESTAMP,		
		PRIMARY KEY (fecha)
	)";

$bd->consulta($sql);

// crea una tabla para guardar todos los movimientos que se van haciendo en las tablas Usuarios, Incidencias o Equipos. Asi es posible llevar un control de las operaciones o realizar una funci�n que deshaga los cambios realizados
$sql = "CREATE TABLE movimientos (
		nMov int NOT NULL,
		fecha TIMESTAMP,
		nombre VARCHAR(20) NOT NULL,
		consulta VARCHAR(100),
		DESCRIPCION VARCHAR(100) NOT NULL,
		
		PRIMARY KEY (nMov)
	)";
$bd->consulta($sql);

//La tabla prioridades
$sql = "CREATE TABLE prioridades (
		nPrioridad int NOT NULL,
		IdPrioridad VARCHAR(50) NOT NULL,
		PRIMARY KEY (nPrioridad)
	)";
$bd->consulta($sql);

//La tabla categoria almacena las categorias asociadas a cada incidencia
$sql = "CREATE TABLE categorias (
		nCategoria int NOT NULL,
		IdCategoria VARCHAR(50) NOT NULL,
		PRIMARY KEY (nCategoria)
	)";

$bd->consulta($sql);

//La tabla estados almacena los estados a los que puede estar asociada cada incidencia
$sql = "CREATE TABLE estados (
		nEstado int NOT NULL,
		IdEstado VARCHAR(50) NOT NULL,
		PRIMARY KEY (nEstado)
	)";

$bd->consulta($sql);

//Insertamos algunas claves predefinidas en el sistema
//Prioridades
$sql = "INSERT INTO prioridades (nPrioridad, IdPrioridad) VALUES ('1','Baja')";
$bd->consulta($sql);
$sql = "INSERT INTO prioridades (nPrioridad, IdPrioridad) VALUES ('2','Media')";
$bd->consulta($sql);
$sql = "INSERT INTO prioridades (nPrioridad, IdPrioridad) VALUES ('3','Alta')";
$bd->consulta($sql);
$sql = "INSERT INTO prioridades (nPrioridad, IdPrioridad) VALUES ('4','Urgente')";
$bd->consulta($sql);

//Estados
$sql = "INSERT INTO estados (nestado, Idestado) VALUES ('1','Nueva')";
$bd->consulta($sql);

$sql = "INSERT INTO estados (nestado, Idestado) VALUES ('2','Asignada')";
$bd->consulta($sql);

$sql = "INSERT INTO estados (nestado, Idestado) VALUES ('3','Cerrada (Resuelta)')";
$bd->consulta($sql);

$sql = "INSERT INTO estados (nestado, Idestado) VALUES ('4','Cerrada (No resuelta)')";
$bd->consulta($sql);

$sql = "INSERT INTO estados (nestado, Idestado) VALUES ('5','En espera')";
$bd->consulta($sql);

//Configuraciones
$sql = "INSERT INTO categorias (nCategoria, IdCategoria) VALUES ('1','Reparación Hardware')";
$bd->consulta($sql);
$sql = "INSERT INTO categorias (nCategoria, IdCategoria) VALUES (2, 'Asitencia Técnica Online')";
$bd->consulta($sql);
$sql = "INSERT INTO categorias (nCategoria, IdCategoria) VALUES (3, 'Solicitud de Presupuesto')";
$bd->consulta($sql);
$sql = "INSERT INTO categorias (nCategoria, IdCategoria) VALUES (4, 'Consultoría')";
$bd->consulta($sql);

//Las tablas ya han sido creadas ahora generamos las funciones oracle que necesitaremos
//-----------------------------------------------------------------------------
// Cuando ha terminado sin errores va a la pagina de crear un administrador
//-----------------------------------------------------------------------------


header("location: ./install.admin.form.php?empresa=".base64_encode($inst_user));

?>