<?php
require_once("security.class.php");

/**
 * Clase que trata los datos de notificaciones almacenandolos en el mismo fichero
 * donde se almacena, el usuario de oracle y la contraseña (empresa/pwd)
 * @author Alberto Arce, Esteban García
 */
//Constructor de la clase
class notifications {

        //private $seguridad;
        private $empresa;
        private $faltan_datos=false;
        
        //si no se encuentra nada guardado proponemos las características de gmail
        private $CharSet="utf-8";
        private $SMTPAuth = true;
        private $SMTPSecure = "ssl";
        private $Host = "smtp.gmail.com";
        private $Port = 465;
        private $Username = "";
        private $Password = "";
        private $FromName = "Soporte";
        private $IsHTML= true;
        private $bd="";
        private $usuariobd="";
        private $clavebd="";

    function   notifications() {
        //obtenemos el id de empresa (nombre de user de oracle) para acceder al
        //fichero de disco indicado
        //$this->seguridad= & new security();
        //$this->empresa =$this->seguridad->get_empresa();
        $this->empresa= $_COOKIE['empresa'];
    }

    public function  load_dats() {
        $fdatos = "../dat/".$this->empresa.".cfg";
        if (file_exists($fdatos)) {
            $archivo = fopen($fdatos , "r");
            if ($archivo) {
                $cadena = fgets($archivo, 500);
                $array_datos = split(';',$cadena);
                //los datos de bd,usuario,y pws de bd las guardamos para más adelante
                $this->bd = $array_datos[0];
                $this->usuariobd = $array_datos[1];
                $this->clavebd = $array_datos[2];
             
                fclose ($archivo);

                //comprobamos los datos del fichero desde elemeto 3 a 11
                For ($size=1;$size<=11;$size++){
                    if($array_datos[$size]=="")
                        $this->faltan_datos=true;
                }
                //si no falta ninguno los asignamos
                if(!$this->faltan_datos){
                $this->CharSet=$array_datos[3];
                $this->SMTPAuth = $array_datos[4];
                $this->SMTPSecure = $array_datos[5];
                $this->Host = $array_datos[6];
                $this->Port = $array_datos[7];
                $this->Username = $array_datos[8];
                $this->Password = $array_datos[9];
                $this->FromName = $array_datos[10];
                $this->IsHTML= $array_datos[11];
                }
                //si faltan se restablecen los valores por defecto de gmail
            }
        }
    }
    public function  save_dats() {
        
        $fdatos = "../dat/".$this->empresa.".cfg";
        if (file_exists($fdatos)) {
            $archivo = fopen($fdatos , "w");
            if ($archivo) {
                //los datos de bd,usuario,y pws de bd se recuperan
                fputs ($archivo,$this->bd.";");
                fputs ($archivo,$this->usuariobd.";");
                fputs ($archivo,$this->clavebd.";");
                //y los datos recogidos por get
                fputs ($archivo,$this->CharSet.";");
                fputs ($archivo,$this->SMTPAuth.";");
                fputs ($archivo,$this->SMTPSecure.";");
                fputs ($archivo,$this->Host.";");
                fputs ($archivo,$this->Port.";");
                fputs ($archivo,$this->Username.";");
                fputs ($archivo,$this->Password.";");
                fputs ($archivo,$this->FromName.";");
                fputs ($archivo,$this->IsHTML.";");
                //cerramos fichero
                fclose ($archivo);
            }
        }
    }

        //Funciones de obtención
        public function get_CharSet(){
            return $this->CharSet;
        }
        public function get_SMTPAuth(){
            return $this->SMTPAuth;
       }
        public function get_SMTPSecure(){
            return $this->SMTPSecure;
        }
        public function get_Host(){
            return $this->Host;
        }
        public function get_Port(){
            return $this->Port;
        }
        public function get_Username(){
            return $this->Username;
        }
        public function get_Password(){
            return $this->Password;
        }
        public function get_FromName(){
            return $this->FromName;
        }
        public function get_IsHTML(){
            return $this->IsHTML;
        }
        //Funciones de establecimiento
        public function set_CharSet($CharSet){
            $this->CharSet=$CharSet;
        }
        public function set_SMTPAuth($SMTPAuth){
            $this->SMTPAuth=$SMTPAuth;
       }
        public function set_SMTPSecure($SMTPSecure){
            $this->SMTPSecure=$SMTPSecure;
        }
        public function set_Host($Host){
            $this->Host=$Host;
        }
        public function set_Port($Port){
            $this->Port=$Port;
        }
        public function set_Username($Username){
            $this->Username=$Username;
        }
        public function set_Password($Password){
            $this->Password=$Password;
        }
        public function set_FromName($FromName){
            //Añadimos el sufijo del nombre de la empresa en el campo FromName
            $this->FromName=$FromName;
        }
        public function set_IsHTML($IsHTML){
            $this->IsHTML;
        }
}
?>