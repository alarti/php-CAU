<?php
require_once("oracle.class.php");
require_once("phpmailer.class.php");
require_once("smtp.class.php");
require_once("security.class.php");
require_once("notifications.class.php");

/**
 * Clase que genera los reportes que se irán enviando por correo electronico
 *
 * @author Alberto Arce, Esteban García
 */

class mail {
        private $bd ;
        private $mail;
        private $seguridad;
        private $empresa;
        private $nombre_logeado;
        private $cuerpo_msg;

    function mail() {
        $this->bd= & new oracle();
        $this->seguridad= & new security();
        //Cargamos la configuracion de correo de la empresa logeada
        $n = &new notifications();
        $n->load_dats();
        
        $this->empresa =$this->seguridad->get_empresa();
        $this->nombre_logeado=$this->seguridad->get_nombre();
        $this->mail = new PHPMailer();

        //añadimos a todos los gestores
        $sql = "SELECT correo FROM Usuarios WHERE privilegios like '__11%'";
        $this->bd->consulta($sql);
        while ($row = $this->bd->get_rows()) {
            $this->mail->AddAddress($row[0]);
        }
        
        $this->mail->IsSMTP();
        $this->mail->CharSet=$n->get_CharSet();
        $this->mail->SMTPAuth = $n->get_SMTPAuth();
        $this->mail->SMTPSecure = $n->get_SMTPSecure();
        $this->mail->Host = $n->get_Host();
        $this->mail->Port = $n->get_Port();
        $this->mail->Username = $n->get_Username();
        $this->mail->Password = $n->get_Password();
//$this->mail->From = "pwd@soporte".$empresa.".com";
        $this->mail->FromName = $n->get_FromName();
//$this->mail->AddAttachment("files/files.zip");
//$this->mail->AddAttachment("files/img03.jpg");
        $this->mail->IsHTML=$n->get_IsHTML();
    }
    public function genera_reporte($nIncidencia) {

        $this->mail->Subject = $this->empresa.":Incidencia nº ".$nIncidencia;

        $sql="select * from Incidencias  where nIncidencia=".$nIncidencia;

        $this->bd->consulta($sql);

        $row = $this->bd->get_rows();
        $this->cuerpo_msg=$this->cuerpo_msg."
------------------------------------------------
Nº de Incidencia ".$row[0]."
http://".$_SERVER["HTTP_HOST"]."/is/front/issue.dats.form.php?nIncidencia=".$nIncidencia."
------------------------------------------------
Prioridad = ".$row[1]."
Categoria = ".$row[2]."
Estado = ".$row[3]."
Fecha de Alta = ".$row[6]."
Fecha de Modificacion = ".$row[7]."
Fecha de Cierre = ".$row[8]."
Solicitante = ".$row[9]."
Técnico Asignado = ".$row[10]."
Email de Respuesta = ".$row[11]."
------------------------------------------------
Título: ".$row[4]."
------------------------------------------------
Descripción:
-----------
".$row[5]."
------------------------------------------------
";
        
        //capturamos el usuario de la incidencia para adjuntarle copia del email
        $this->mail->AddAddress($row[11]);

        $sql="select * from Seguimientos  where nIncidencia=".$nIncidencia;
        $this->bd->consulta($sql);
        while ($row = $this->bd->get_rows()) {
            $this->cuerpo_msg=$this->cuerpo_msg."Nº de Seguimiento = ".$row[0]."
-------------------------------------------------
Nº Incidencia Asociada = ".$row[1]."
Título del seguimiento: ".$row[2]."
Texto del seguimiento:
".$row[3]."
Autor del Seguimiento = ".$row[4]."
Fecha de Creación del Seguimiento = ".$row[5]."
Fecha de Ultima modificación del Seguimiento = ".$row[6]."
-------------------------------------------------
";
        }
        $this->mail->AltBody = $this->cuerpo_msg;
        $this->mail->MsgHTML($this->cuerpo_msg);

    }
    public function enviar() {
        if(!$this->mail->Send()) {
            echo "Error: " . $this->mail->ErrorInfo;
        }
    }

}
?>