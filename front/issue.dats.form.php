<?php
include("menu.form.php");

require_once("../inc/security.class.php");
require_once("../inc/oracle.class.php");

// oracle
$bd =& new oracle();

// seguridad
$s =& new security();
$nombre = $s->get_nombre();
$privilegios = $s->get_privilegios();


// Comprueba que tiene acceso

if(!substr($privilegios,4,1)=='1') {
    echo '<link href="../css/Estilo.css" rel="stylesheet" type="text/css" />';
    echo "<div id='salida' class='Error'>No tiene privilegios suficientes para acceder a esta Incidencia</div>";
    exit;
}
//recogemos el numero de incidencia
$nIncidencia=$_GET['nIncidencia'];
if(!isset($nIncidencia)) {
    //include("");
    echo '<link href="../css/Estilo.css" rel="stylesheet" type="text/css" />';
    echo "<div id='salida' class='Error'>No se ha obtenido código de incidencia para mostrar</div>";
    exit;
}
//recogemos el tipo de orden para los seguimientos sino establecemos uno
//recuperamos el filtro en caso de existir
$order=$_GET['order'];
if(!isset($order)) {
    $order='Fecha';
}
$sqlorder=" order by ".$order;



$sql = "SELECT * FROM Incidencias WHERE nIncidencia=".$nIncidencia;
//$sql = "SELECT nombre, correo, AES_DECRYPT(privilegios,'".$clave."'), contrasena  FROM Usuarios WHERE nombre = '".$nombreUsuario."';";
$bd->consulta($sql);
$row = $bd->get_rows();
// si no tiene permisos de lectura de incidencias o es el que abrió la incidencia sale fuera
if(substr($privilegios,4,1)==0) {
    echo '<link href="../css/Estilo.css" rel="stylesheet" type="text/css" />';
    echo("ERROR no tiene acceso a esta incidencia");
    exit;
}
$titulo=$row['TITULO'];
$descripcion=$row['DESCRIPCION'];
$correo = $row['EMAIL_RESP'];
$prioridad= $row['PRIORIDAD'];
$categoria= $row['CATEGORIA'];
$estado= $row['ESTADO'];
$solicitante= $row['SOLICITANTE'];
$tecnico_asig=$row['TECNICO_ASIG'];

//montamos los estados de los selectores dependiendo de lo privilegios
if(!substr($privilegios,3,1)=='1')
    $estado_state="disabled";
else
    $estado_state="";
?>
<link href="../css/Estilo.css" rel="stylesheet" type="text/css" />
<script src="../SpryAssets/SpryValidationTextarea.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationSelect.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryCollapsiblePanel.js" type="text/javascript"></script>
<link href="../SpryAssets/SpryValidationTextarea.css" rel="stylesheet" type="text/css" />
<link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<link href="../SpryAssets/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<link href="../SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />
<link href="../SpryAssets/SpryCollapsiblePanel.css" rel="stylesheet" type="text/css" />
<form name="form_incidencia" id="form_incidencia" method="post" action="issue.alter.php" dir="ltr" lang="es" xml:lang="es">
    <table width="100%" border="0" align="center" >
<tr>
            <td width="100%"><div id="TabbedPanels1" class="TabbedPanels" >
                    <ul class="TabbedPanelsTabGroup">
                        <li class="TabbedPanelsTab">Datos de la Incidencia Nº <?php echo $nIncidencia ?></li>
                    </ul>
                    <div class="TabbedPanelsContentGroup">
                        <div class="TabbedPanelsContent">
                            <div>
                                <table width="100%" border="0">
                      <tr>
                                        <td>Solicitante:</td>
                                        <td><?php echo $solicitante ?></td>
                                    </tr>
                                    <tr>
                                        <td width="20%">Prioridad:</td>
                            <td width="80%"><span id="js_prioridad">
      <select name="prioridad" id="prioridad">
                                                    <?php
                                                    $sql="select * from Prioridades";
                                                    $bd->consulta($sql);
                                                    while ($row = $bd->get_rows()) {
                                                        if ($row[1]==$prioridad)
                                                            echo "<option $row[0] selected>$row[1]</option>";
                                                        else
                                                            echo "<option $row[0]>$row[1]</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </span>
                                            <input type="hidden" name="nIncidencia" id="nIncidencia" value="<?php echo$nIncidencia ?>" /></td>
                                  </tr>
                                    <tr>
                                        <td>Categoría:</td>
                                        <td><span id="js_categoría">
                                                <select name="categoria" id="categoria" >
                                                    <option value="-1" selected="selected"></option>
                                                    <?php
                                                    $sql="select * from Categorias";
                                                    $bd->consulta($sql);
                                                    while ($row = $bd->get_rows()) {
                                                        if ($row[1]==$categoria)
                                                            echo "<option $row[0] selected>$row[1]</option>";
                                                        else
                                                            echo "<option $row[0]>$row[1]</option>";
                                                    }
                                                    ?>
                                                </select>
                                                <span class="selectInvalidMsg">Seleccione un elemento válido.</span> </span></td>
                                    </tr>
                                    <tr>
                                        <td>Estado:</td>
                                        <td><span id="js_estado">
                                                <select name="estado" id="estado" <?php echo $estado_state ?> >
                                                    <?php
                                                    $sql="select * from Estados";
                                                    $bd->consulta($sql);
                                                    while ($row = $bd->get_rows()) {
                                                        if ($row[1]==$estado)
                                                            echo "<option $row[0] selected>$row[1]</option>";
                                                        else
                                                            echo "<option $row[0]>$row[1]</option>";
                                                    }
                                                    ?>
                                                </select>
                                                <span class="selectInvalidMsg">Seleccione un elemento válido.</span> </span></td>
                                    </tr>
                                    <tr>
                                        <td>Técnico asignado</td>
                                        <td><span id="js_tecnico_asig">
                                                <select name="tecnico_asig" id="tecnico_asig" <?php echo$estado_state ?>>
                                                    <option value="-1" selected="selected"></option>
                                                    <?php
                                                    $sql="select nombre from Usuarios WHERE (privilegios like '__11111111')";
                                                    $bd->consulta($sql);
                                                    while ($row = $bd->get_rows()) {
                                                        if ($row[0]==$tecnico_asig)
                                                            echo "<option selected>$row[0]</option>";
                                                        else
                                                            echo "<option>$row[0]</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </span></td>
                                    </tr>
                                    <tr>
                                        <td>Título:</td>
                                        <td><span id="js_titulo">
                                                <input name="titulo" type="text" id="titulo" size="60" maxlength="60" value="<?php echo $titulo ?>"/>
                                                <span class="textfieldRequiredMsg">El título es obligatorio.</span><span class="textfieldMaxCharsMsg">Se ha superado el número máximo de caracteres.</span></span></td>
                                    </tr>
                                    <tr>
                                        <td height="88">Descripción:</td>
                                        <td><span id="js_descrip">
                                                <textarea name="descripcion" id="descripcion" cols="80" rows="4"><?php echo $descripcion ?></textarea>
                                                <span id="countjs_descrip">&nbsp;</span> <span class="textareaRequiredMsg">Se necesita un valor.</span><span class="textareaMinCharsMsg">No se cumple el mínimo de caracteres requerido.</span><span class="textareaMaxCharsMsg">Se ha superado el número máximo de caracteres.</span></span></td>
                                    </tr>
                                    <tr>
                                        <td>Email:</td>
                                        <td><span id="js_email">
                                                <input type="text" name="email_resp" id="email_resp" value="<?php echo $correo ?>" />
                                                <span class="textfieldRequiredMsg">Se necesita un valor.</span><span class="textfieldInvalidFormatMsg">Formato no válido.</span></span></td>
                                    </tr>
                                    <tr>
                                        <td rowspan="2"><input type="submit" name="agregar_trace" value="Añadir Seguimiento" /></td>
                                        <td><input type="submit" name="borrar" value="Borrar Incidencia" /></td>
                                    </tr>
                                    <tr>
                                        <td><input type="submit" name="modificar" value="Modificar Incidencia" /></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="TabbedPanelsContent">
                        <div align="left">
                            <div id="CollapsiblePanel1" class="CollapsiblePanel">
                                <div id="js_visor_segui" class="CollapsiblePanel">
                                    <div class="CollapsiblePanelTab" >Visor de Seguimientos Asociados:</div>
                                    <div class="CollapsiblePanelContent">
                                        <div align="left">
                                            <div id="CollapsiblePanel3" class="CollapsiblePanel">
                                                <div class="CollapsiblePanelContent">
                                                    <div>
                                                        <div>
                                                            <table width='100%' border='0' align="center">
                                                                <tr>
                                                                    <td colspan="6" bgcolor="#CCCCCC" >&nbsp;</td>
                                                                </tr>
                                                                <tr>
                                                                    <td width="3%" bgcolor="#CCCCCC"><div align="center"><strong><a href="issue.dats.form.php?order=nSeguimiento&nIncidencia=<?php echo $nIncidencia ?>" class="Utiles">Nº</a></strong></div></td>
                                                                  <td width="12%" bgcolor="#CCCCCC"><div align="center"><strong><a href="issue.dats.form.php?order=fecha&nIncidencia=<?php echo $nIncidencia ?>" class="Utiles">Fecha Alta</a></strong></div></td>
                                                                  <td width="12%" bgcolor="#CCCCCC"><div align="center"><strong><a href="issue.dats.form.php?order=fecha_modif&nIncidencia=<?php echo $nIncidencia ?>" class="Utiles">Ultima Modif.</a></strong></div></td>
                                                                  <td width="12%" bgcolor="#CCCCCC"><div align="center"><strong><a href="issue.dats.form.php?order=autor&nIncidencia=<?php echo $nIncidencia ?>" class="Utiles">Autor</a></strong></div></td>
                                                                  <td width="20%" bgcolor="#CCCCCC"><div align="center"><strong><a href="issue.dats.form.php?order=titulo&nIncidencia=<?php echo $nIncidencia ?>" class="Utiles">Título</a></strong></div></td>
                                                                  <td width="41%" bgcolor="#CCCCCC"><div align="center"><strong><a href="issue.dats.form.php?order=descripcion&nIncidencia=<?php echo $nIncidencia ?>" class="Utiles">Descripcion</a></strong></div></td>
                                                              </tr>
                                                                <?php
                                                                //Mostramos los seguimientos de la incidencia
                                                                $sql="
                                                                    Select
                                                                        nSeguimiento,
                                                                        TO_CHAR(fecha, 'DD/MM/YYYY HH24:MI:SS' ),
                                                                        TO_CHAR(fecha_modif, 'DD/MM/YYYY HH24:MI:SS' ),
                                                                        autor,
                                                                        titulo,
                                                                        descripcion
                                                                    from Seguimientos
                                                                    where (nIncidencia=".$nIncidencia.")
                                                                    order by ".$order;

                                                                $bd->consulta($sql);

                                                                //creo e inicializo la variable para contar el número de filas
                                                                $num_fila = 0;

                                                                while ($row = $bd->get_rows()) {
                                                                    //pares e impares de diferente color
                                                                    if ($num_fila%2==0)
                                                                        $estilo_fila="align='center'";
                                                                    else
                                                                        $estilo_fila="bgcolor=#A2CADD align='center'";
                                                                    //añadimos el enlace para modificar las incidencias
                                                                    //$estilo_fila=$estilo_fila."><a href='issue.find.php?order=email_resp'";
                                                                    //los detalles con link a cada incidencia
                                                                    echo "<tr>
                                                      <td ".$estilo_fila.">".$row[0]."</td>
                                                      <td ".$estilo_fila.">".$row[1]."</td>
                                                      <td ".$estilo_fila.">".$row[2]."</td>
                                                      <td ".$estilo_fila.">".$row[3]."</td>
                                                      <td ".$estilo_fila."<div><a href='trace.dats.form.php?nSeguimiento=".$row[0]."&nIncidencia=".$nIncidencia."'>".$row[4]."</div></td>
                                                      <td ".$estilo_fila.">".$row[5]."</td>
                                                      </tr>";

                                                                    //aumentamos en uno el número de filas
                                                                    $num_fila++;
                                                                }
                                                                ?>
                                                                <tr>
                                                                    <td colspan="9" bgcolor="#CCCCCC" >&nbsp;</td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>            </td>
      </tr>
    </table>
</form>

<script type="text/javascript">
    <!--
    var sprytextarea1 = new Spry.Widget.ValidationTextarea("js_descrip", {counterId:"countjs_descrip", counterType:"chars_remaining", validateOn:["blur", "change"], minChars:1, maxChars:800});
    var sprytextfield2 = new Spry.Widget.ValidationTextField("js_email", "email", {validateOn:["blur", "change"]});
    var sprytextfield1 = new Spry.Widget.ValidationTextField("js_titulo", "none", {validateOn:["blur", "change"], maxChars:60});
    var spryselect3 = new Spry.Widget.ValidationSelect("js_estado", {validateOn:["blur", "change"], isRequired:false, invalidValue:"-1"});
    var spryselect2 = new Spry.Widget.ValidationSelect("js_categoría", {validateOn:["blur", "change"], isRequired:false, invalidValue:"-1"});
    var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1");

    var spryselect1 = new Spry.Widget.ValidationSelect("js_prioridad", {validateOn:["blur", "change"], isRequired:false});
    var spryselect4 = new Spry.Widget.ValidationSelect("js_tecnico_asig", {validateOn:["blur", "change"], isRequired:false});
    var CollapsiblePanel2 = new Spry.Widget.CollapsiblePanel("js_visor_segui");
    //-->
</script>

