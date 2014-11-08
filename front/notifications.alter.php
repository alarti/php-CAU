<?php
        require_once ("../inc/notifications.class.php");
        require_once ("../inc/security.class.php");
        require_once ("../inc/tracking.class.php");
        require_once ("../inc/text.class.php");

        $n= &new notifications();
        $s= &new security();
        $txt =&new text();

        $empresa=$s->get_empresa();

        $CharSet=$_POST['CharSet'];
        $FromName=$_POST['FromName'];
        $Host=$_POST['Host'];
        $IsHtml=$_POST['IsHTML'];
        $Password=$_POST['Password'];
        $Port=$_POST['Port'];
        $SMTPAut=$_POST['SMTPAuth'];
        $SMTPSecure=$_POST['SMTPSecure'];
        $Username=$_POST['Username'];

        if(
                !isset($CharSet)||!$txt->alfabeticonet($CharSet)||
                !isset($FromName)||!$txt->alfabeticonet($FromName)||
                !isset($Host)||!$txt->alfabeticonet($Hostname)||
                !isset($IsHtml)||
                !isset($Password)||!$txt->alfabeticonet($Password)||
                !isset($Port)||!$txt->numerico($Port)||
                !isset($SMTPAut)||
                !isset($SMTPSecure)||!$txt->alfabeticonet($SMTPSecure)||
                !isset($Username)||!$txt->alfabeticonet($Username)
           ){
                //
                echo '<link href="../css/Estilo.css" rel="stylesheet" type="text/css" />';
                echo "<div id='salida' class='Error'>Ha introducido carácteres no válidos en alguno de los campos</div>";
                exit;
            }
        
        $n->load_dats();
        $n->set_CharSet($CharSet);
        $n->set_FromName($FromName);
        $n->set_Host($Host);
        $n->set_IsHTML($IsHtml);
        $n->set_Password($Password);
        $n->set_Port($Port);
        $n->set_SMTPAuth($SMTPAut);
        $n->set_SMTPSecure($SMTPSecure);
        $n->set_Username($Username);
        $n->save_dats();

        $m = new tracking('SMTP de empresa '.$empresa.' cambiado: ','Empresa');
	header("location: notifications.alter.ok.php?ref=".$empresa);
?>