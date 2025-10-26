<?php

include("valida.php");

if (isset($_POST["MM_insert"])) {
    if ($_POST["descripcion"]=="" or is_null($_POST["descripcion"]) or $_POST["montosolicitado"]=="" or is_null($_POST["montosolicitado"])) {
       $error="<font color='red'><b>Deben registrarse obligatoriamente el concepto y el monto</b></font>";
    } else {

      $sql="insert into entregafondos values (null, '".$_POST["descripcion"]."', '".fecha($_POST["fechasolicitud"],99)."', 0, '".$_POST["nrosolicitud"]."', '".$_POST["montosolicitado"]."', '".$_POST["montosolicitado"]."', 0, '".$_POST["comentario"]."', '".$_POST["recursohumano"]."', '".$_POST["moneda"]."')";
      mysql_query($sql) or die(mysql_error());
      if (!(isset($_GET["t"]) && $_GET["t"]=="popup")) {

         header("Location: solicitudes.php");
      }
      exit;
   }
} else if (isset($_POST["MM_edit"])) {
    if ($_POST["descripcion"]=="" or is_null($_POST["descripcion"]) or $_POST["montosolicitado"]=="" or is_null($_POST["montosolicitado"])) {
       $error="<font color='red'><b>Deben registrarse obligatoriamente el concepto y el monto</b></font>";
    } else {

      $sql="update entregafondos set descripcion='".$_POST["descripcion"]."', fechasolicitud='".fecha($_POST["fechasolicitud"],99)."', nrosolicitud='".$_POST["nrosolicitud"]."', montosolicitado='".$_POST["montosolicitado"]."', saldopendiente='".$_POST["montosolicitado"]."', comentario='".$_POST["comentario"]."', moneda='".$_POST["moneda"]."', idrecursohumano='".$_POST["recursohumano"]."'
                   where identregafondos=".$_POST["idg"];

      mysql_query($sql) or die(mysql_error());


      header("Location: solicitudes.php");
      exit;
   }
}
   if (!(isset($_GET["t"]) && $_GET["t"]=="popup")) {
      include("encabezado.php");
   } else {
   ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php print $nombresistema; ?></title>
<link href="<?php print $path; ?>estilos.css" rel="stylesheet" type="text/css" />
</html>
   <?php
   }

?>

<table align="center" bgcolor="#ffffff" width="100%">
<tr>
<?php

   if (!(isset($_GET["t"]) && $_GET["t"]=="popup")) {

   ?>
    <td width="200" valign="top">
        <table align="center" width="100%">
                        <tr>
                            <td class="textsubmenu"><img src="images/bc_parent.png" /></td><td class="textsubmenu"><a href="finanzas.php" class="submenu">Retornar al men&uacute; anterior</a></td>
                        </tr>
                        <tr>
                            <td class="textsubmenu"><img src="images/ok.gif" /></td><td class="textsubmenu"><a href="solicitudes.php" class="submenu">Editar solicitud</a></td>
                        </tr>

        </table>

        <br><br>
    </td>
    <?php }
    ?>
    <td width="600">

            <table class="contenido" width="100%">
                <tr>
                    <td colspan="2" class="titmenu">
                        <?php if ($_GET["mode"]=='e') {
                                  print "EDITAR SOLICITUD DE FONDOS";
                                  $sqle="select * from entregafondos where identregafondos=".$_GET["id"];
                                  $rese=mysql_query($sqle);
                                  $filae=mysql_fetch_array($rese);
                              } else {
                                  print "SOLICITAR FONDOS";
                              }
                              ?> <a name='nuevo'>
                    </td>
                </tr>
                <tr>
                    <td>
                    <form method="POST" name="actualizar">
                      <table align="center">
                        <tr valign="baseline">
                          <td  class="tititems" align="right">Concepto:</td>
                          <td><input type="text" name="descripcion" size="50" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print $filae[1]; } ?>"></td>
                        </tr>
                        <tr valign="baseline">
                          <td  class="tititems" align="right">Fecha de solicitud:</td>
                          <td><input type="text" name="fechasolicitud" id="convo" size="10" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print fecha($filae[2],8); } ?>"></td>
                        </tr>
                        <tr valign="baseline">
                          <td class="tititems" align="right" valign="top">Numero de solicitud:</td>
                          <td><input type="text" name="nrosolicitud" size="12" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print $filae[4]; } ?>">
                          </td>
                        </tr>
                        <tr valign="baseline">
                          <td class="tititems" align="right" valign="top">Moneda:</td>
                          <td><select name='moneda' >
                                      <option <?php if ($filae[10]=="Bs") { print "selected"; } ?>>Bs</option>
                                      <option <?php if ($filae[10]=="\$us") { print "selected"; } ?>>$us</option>

                          </select>
                          </td>
                        </tr>
                        <tr valign="baseline">
                          <td class="tititems" align="right" valign="top">Monto:</td>
                          <td><input type="text" name="montosolicitado" size="12" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print $filae[5]; } ?>">
                          </td>
                        </tr>
                        <tr valign="baseline">
                          <td class="tititems" align="right" valign="top">Comentario:</td>
                          <td><input type="text" name="comentario" size="60" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print $filae[8]; } ?>">
                          </td>
                        </tr>
                        <tr valign="baseline">
                          <td class="tititems" align="right" valign="top">Entregado a:</td>
                          <td>                        <select name='recursohumano' id='recursohumano'>
                           <option value='-1'>--- Elija ---</option>
                          <?php
                              $sql="select idRecursoHumano, Nombres, Apellidos from recursohumano where activo='1' order by Apellidos, Nombres";
                              $res=mysql_query($sql);
                              while ($fila=mysql_fetch_array($res)) {
                                    print "<option ";
                                    if (isset($_GET["mode"]) && $_GET["mode"]=="e" && $filae[9]==$fila[0]) { print "selected"; }
                                    print " value='$fila[0]'>$fila[2] $fila[1]</option>";
                              }
                          ?>
                          </select> <a href='#' onclick="window.open('rrhh_nuevos.php?t=popup', '_blank', 'status=0,menubar=0,location=0,scrollbars=0,resizable=0,width=600,height=350', 0);"><img src='images/508.png'></a>

                          </td>
                        </tr>
                       <tr valign="baseline">
                          <td colspan="2" align="center"><input class="boton1" type="submit" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print "Editar"; } else { print "Registrar"; }?> gasto">
                          <input class="boton1" name="Cancelar" type="button" onClick="<?php if ((isset($_GET["t"]) && $_GET["t"]=="popup")) { print "window.close();"; } else { print "window.location='solicitudes.php'"; } ?>" value='Cancelar'/></td>
                        </tr>
                      </table>
                      <input type="hidden" name="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print "MM_edit"; } else { print "MM_insert"; } ?>" value="actualizar">
                      <?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print "<input type='hidden' name='idg' value='".$_GET["id"]."'>"; } ?>
                    </form>
<?php
    print $error;
?>

        </td>
        </tr>

        </table>
    </td>
    </tr>
    </table>
</td>
</tr>
<?php
include("pie.php");
?>
</table>
<p>&nbsp;</p>
<br>
</body>
</html>