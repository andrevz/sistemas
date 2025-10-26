<?php

include("valida.php");

if (isset($_POST["MM_insert"])) {
    if ($_POST["concepto"]=="" or is_null($_POST["concepto"]) or $_POST["montopagado"]=="" or is_null($_POST["montopagado"])) {
       $error="<font color='red'><b>Deben registrarse obligatoriamente el concepto y el monto</b></font>";
    } else {

      $sql="insert into gastos values (null, '".$_POST["concepto"]."', 0, 0, 0, 0, 0, 0, 0, '".$_POST["moneda"]."', '".$_POST["facturapresentada"]."',  '".fecha($_POST["fecharecepcion"],99)."',  '".$_POST["formapago"]."',  '".$_POST["estadocancelacion"]."', '".$_POST["necesitarendicion"]."',  '".$_POST["anombrede"]."',  '".$_POST["montopagado"]."', 0, '".$_SESSION['IdRH']."')";
      mysql_query($sql) or die(mysql_error());
      if (!(isset($_GET["t"]) && $_GET["t"]=="popup")) {

         header("Location: gastos.php");
      }
      exit;
   }
} else if (isset($_POST["MM_edit"])) {
    if ($_POST["concepto"]=="" or is_null($_POST["concepto"]) or $_POST["montopagado"]=="" or is_null($_POST["montopagado"])) {
       $error="<font color='red'><b>Deben registrarse obligatoriamente el concepto y el monto</b></font>";
    } else {

      $sql="update gastos set concepto='".$_POST["concepto"]."', moneda='".$_POST["moneda"]."', facturapresentada='".$_POST["facturapresentada"]."', fecharecepcion='".fecha($_POST["fecharecepcion"],99)."', formapago='".$_POST["formapago"]."', estadocancelacion='".$_POST["estadocancelacion"]."',  necesitarendicion='".$_POST["necesitarendicion"]."', anombrede='".$_POST["anombrede"]."', montopagado='".$_POST["montopagado"]."'
                   where idgastos=".$_POST["idg"];

      mysql_query($sql) or die(mysql_error());


      header("Location: gastos.php");
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
                            <td class="textsubmenu"><img src="images/ok.gif" /></td><td class="textsubmenu"><a href="gastos.php" class="submenu">Editar gasto</a></td>
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
                                  print "EDITAR";
                                  $sqle="select * from gastos where idgastos=".$_GET["id"];
                                  $rese=mysql_query($sqle);
                                  $filae=mysql_fetch_array($rese);
                              } else {
                                  print "REGISTRAR";
                              }
                              ?> GASTO <a name='nuevo'>
                    </td>
                </tr>
                <tr>
                    <td>
                    <form method="POST" name="actualizar">
                      <table align="center">
                        <tr valign="baseline">
                          <td  class="tititems" align="right">Concepto:</td>
                          <td><input type="text" name="concepto" size="50" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print $filae[1]; } ?>"></td>
                        </tr>
                        <tr valign="baseline">
                          <td  class="tititems" align="right">Fecha de pago:</td>
                          <td><input type="text" name="fecharecepcion" id="convo" size="10" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print fecha($filae[11],8); } ?>"></td>
                        </tr>
                        <tr valign="baseline">
                          <td class="tititems" align="right" valign="top">Numero de solicitud:</td>
                          <td><input type="text" name="nrosolicitud" size="12" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print $filae[6]; } ?>">
                          </td>
                        </tr>
                        <tr valign="baseline">
                          <td class="tititems" align="right" valign="top">Moneda:</td>
                          <td><select name='moneda' >
                                      <option <?php if ($filae[9]=="Bs") { print "selected"; } ?>>Bs</option>
                                      <option <?php if ($filae[9]=="\$us") { print "selected"; } ?>>$us</option>

                          </select>
                          </td>
                        </tr>
                        <tr valign="baseline">
                          <td class="tititems" align="right" valign="top">Monto:</td>
                          <td><input type="text" name="montopagado" size="12" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print $filae[16]; } ?>">
                          </td>
                        </tr>
                        <tr valign="baseline">
                          <td class="tititems" align="right" valign="top">Factura presentada:</td>
                          <td><input type="text" name="facturapresentada" size="12" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print $filae[10]; } ?>">
                          </td>
                        </tr>
                        <tr valign="baseline">
                          <td class="tititems" align="right" valign="top">Beneficiario:</td>
                          <td><input type="text" name="anombrede" size="50" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print $filae[15]; } ?>">
                          </td>
                        </tr>
                        <tr valign="baseline">
                          <td class="tititems" align="right" valign="top">Forma de pago:</td>
                          <td><select name='formapago'>
                                      <option value='-1'>--- Elija ---</option>
                          <?php
                              $sql="select id_tipopago, descripcion from tipos_pago";
                              $res=mysql_query($sql);
                              while ($fila=mysql_fetch_array($res)) {
                                    print "<option ";
                                    if (isset($_GET["mode"]) && $_GET["mode"]=="e" && $filae[12]==$fila[0]) { print "selected"; }
                                    print " value='$fila[0]'>$fila[1]</option>";
                              }
                          ?>
                          </select>
                          </td>
                        </tr>
                        <tr valign="baseline">
                          <td class="tititems" align="right" valign="top">Estado de cancelaci&oacute;n:</td>
                          <td><select name='estadocancelacion'>
                                      <option <?php if ($filae[13]=="PENDIENTE") { print "selected"; } ?>>PENDIENTE</option>
                                      <option <?php if ($filae[13]=="PAGADO") { print "selected"; } ?>>PAGADO</option>

                          </select>
                          </td>
                        </tr>
                        <tr valign="baseline">
                          <td class="tititems" align="right" valign="top">Necesita rendici&oacute;n?:</td>
                          <td><select name='necesitarendicion' >
                                      <option value='0' <?php if ($filae[14]=="0") { print "selected"; } ?>>No</option>
                                      <option value='1' <?php if ($filae[14]=="1") { print "selected"; } ?>>Si</option>

                          </select>
                          </td>
                        </tr>
                        <tr valign="baseline">
                          <td colspan="2" align="center"><input class="boton1" type="submit" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print "Editar"; } else { print "Registrar"; }?> gasto">
                          <input class="boton1" name="Cancelar" type="button" onClick="<?php if ((isset($_GET["t"]) && $_GET["t"]=="popup")) { print "window.close();"; } else { print "window.location='gastos.php'"; } ?>" value='Cancelar'/></td>
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