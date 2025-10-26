<?php

include("valida.php");

if (isset($_POST["MM_insert"])) {
    if ((!isset($_POST["idp"]) || $_POST["idp"]==0) && ($_POST["nombre"]=="" or is_null($_POST["nombre"]))) {
       $error="<font color='red'><b>Debe registrarse obligatoriamente el nombre</b></font>";
    } else {

       if (!isset($_POST["idp"]) || $_POST["idp"]==0) {
          $sql="insert into propuesta (titulo, monto, moneda, idcreador, idclientes, idrecursohumano) values ('".$_POST["nombre"]."', '".$_POST["monto"]."', '".$_POST["moneda"]."', '".$_SESSION['IdRH']."', '".$_POST["clientes"]."', '".$_SESSION['IdRH']."')";
          mysqli_query($sql);
          $idpr=mysqli_insert_id();
       } else {
          $idpr=$_POST["idp"];
       }
      $sql="insert into proyecto values (null, '".fecha($_POST["fechaini"],99)."', '".fecha($_POST["fechafin"],99)."', '".$_POST["contrato"]."', '".$_POST["estado"]."', '".$_SESSION['IdRH']."')";
      mysqli_query($sql) or die(mysqli_error());
      $sql="update propuesta set idproyecto=".mysqli_insert_id()." where idpropuesta=".$idpr;
      mysqli_query($sql);

      if (!isset($_POST["idp"]) || $_POST["idp"]==0) {
          print "<script language='javascript'>window.location='proyectos.php';</script>";
      } else {
          print "<script language='javascript'>window.opener.location='proyectos.php'; window.close();</script>";
      }
      exit;
   }
} else if (isset($_POST["MM_edit"])) {
    if ($_POST["nombre"]=="" or is_null($_POST["nombre"])) {
       $error="<font color='red'><b>Debe registrarse obligatoriamente el nombre</b></font>";
    } else {

      $sql="update propuesta set titulo='".$_POST["nombre"]."', monto='".$_POST["monto"]."', moneda='".$_POST["moneda"]."',
                   idclientes='".$_POST["clientes"]."' where idproyecto=".$_POST["id"];
      mysqli_query($sql) or die(mysqli_error());

      $sql="update proyecto set fechainicio='".fecha($_POST["fechaini"],99)."', fechafinal='".fecha($_POST["fechafin"],99)."', nrocontrato='".$_POST["contrato"]."',
                   estado='".$_POST["estado"]."' where idproyecto=".$_POST["id"];
      mysqli_query($sql) or die(mysqli_error());

      if (isset($_POST["id"])) {
         header("Location: proyectos.php?id=".$_POST["id"]);
      } else {
         header("Location: proyectos.php");
      }
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
<link rel="stylesheet" href="<?php print $path; ?>theme/jquery.ui.all.css">
  <script src="<?php print $path; ?>js/jquery-1.7.1.min.js"></script>
  <script src="<?php print $path; ?>js/jquery.ui.core.js"></script>
  <script src="<?php print $path; ?>js/jquery.ui.widget.js"></script>
  <script src="<?php print $path; ?>js/jquery.ui.datepicker.js"></script>
  <script>
$(function() {
    var dates = $( "#convo, #ela, #pres" ).datepicker({
      defaultDate: "+1w",
      changeYear: true,
      numberOfMonths: 1,
      onSelect: function( selectedDate ) {
    //    var option = this.id == "convo" ? "minDate" : "maxDate",
          instance = $( this ).data( "datepicker" ),
          date = $.datepicker.parseDate(
            instance.settings.dateFormat ||
            $.datepicker._defaults.dateFormat,
            selectedDate, instance.settings );
    //    dates.not( this ).datepicker( "option", option, date );
      }
    });
  });
  </script>
</html>
   <?php
   }

?>
<table align="center" bgcolor="#ffffff" width="100%">
<tr>

    <td width="600">

            <table class="contenido" width="100%">
                <tr>
                    <td colspan="2" class="titmenu">
                        <?php
                            if (isset($_GET["idp"])) {
                                  $sqle="select titulo, monto, moneda, idclientes from propuesta where idpropuesta=".$_GET["idp"];
                                  $rese=mysqli_query($sqle);
                                  $filae=mysqli_fetch_array($rese);
                            } else if (isset($_GET["id"])) {
                                  $sqle="select titulo, monto, moneda, idclientes, idpropuesta, fechainicio, fechafinal, nrocontrato, estado from propuesta p inner join proyecto pr on pr.idproyecto=p.idproyecto where p.idproyecto=".$_GET["id"];
                                  $rese=mysqli_query($sqle);
                                  $filae=mysqli_fetch_array($rese);
                            }
                            if ($_GET["mode"]=='e') {
                                  print "EDITAR";
                              } else {
                                  print "NUEVO";
                              }
                              ?> PROYECTO<a name='nuevo'>
                    </td>
                </tr>
                <tr>
                    <td>
                    <form method="POST" name="actualizar">
                      <table align="center">
                        <tr valign="baseline">
                          <td  class="tititems" align="right">T&iacute;tulo:</td>
                          <td><textarea type="text" name="nombre" cols="55" rows='1' <?php if (isset($_GET["idp"])) { print "disabled"; }?>><?php print $filae[0]; ?></textarea></td>
                        </tr>
                        <tr valign="baseline">
                          <td  class="tititems" align="right">Cliente:</td>
                          <td>                        <select name='clientes' id='clientes' <?php if (isset($_GET["idp"])) { print "disabled"; }?>>
                               <option value='-1'>--- Elija ---</option>
                          <?php
                              $sql="select idClientes, nombre, ciudad, pais from clientes order by nombre";
                              $res=mysqli_query($sql);
                              while ($fila=mysqli_fetch_array($res)) {
                                    print "<option ";
                                    if ($filae[3]==$fila[0]) { print "selected"; }
                                    print " value='$fila[0]'>$fila[1]</option>";
                              }
                          ?>
                          </select>
                          </td>
                        </tr>
                        <tr valign="baseline">
                          <td  class="tititems" align="right">Monto:</td>
                          <td><input type="text" name="monto" size="15" <?php if (isset($_GET["idp"])) { print "disabled"; }?> value="<?php print $filae[1]; ?>" /></td>
                        </tr>
                        <tr valign="baseline">
                          <td class="tititems" align="right" valign="top">Moneda:</td>
                          <td><select name='moneda' <?php if (isset($_GET["idp"])) { print "disabled"; }?>>
                                      <option <?php if ($filae[2]=="Bs") { print "selected"; } ?>>Bs</option>
                                      <option <?php if ($filae[2]=="\$us") { print "selected"; } ?>>$us</option>

                          </select>
                          </td>
                        </tr>
                        <tr valign="baseline">
                          <td class="tititems" align="right" valign="top">Fecha inicio:</td>
                          <td><input type="text" name="fechaini" size="10" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print fecha($filae[5],8); } ?>" id="convo"/>
                          </td>
                        </tr>
                        <tr valign="baseline">
                          <td class="tititems" align="right" valign="top">Fecha Final:</td>
                          <td><input type="text" name="fechafin" size="10" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print fecha($filae[6],8); } ?>" id="ela">
                          </td>
                        </tr>
                        <tr valign="baseline">
                          <td class="tititems" align="right" valign="top">Nro.Contrato:</td>
                          <td><input type="text" name="contrato" size="15" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print $filae[7]; } ?>">
                          </td>
                        </tr>
                        <tr valign="baseline">
                          <td class="tititems" align="right" valign="top">Estado:</td>
                          <td><select name='estado'>
                                      <option value='A' <?php if (isset($_GET["mode"]) && $_GET["mode"]=="e" && $filae[8]=="A") { print "selected"; } ?>>Activo</option>
                                      <option value='C' <?php if (isset($_GET["mode"]) && $_GET["mode"]=="e" && $filae[8]=="C") { print "selected"; } ?>>Cerrado</option>

                          </select>
                          </td>
                        </tr>
                        <tr valign="baseline">
                          <td colspan="2" align="center"><input class="boton1" type="submit" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print "Editar"; } else { print "Crear"; }?> Proyecto">
                          <input class="boton1" name="Cancelar" type="button" onClick="<?php if ((isset($_GET["t"]) && $_GET["t"]=="popup")) { print "window.close();"; } else { print "window.location='proyectos.php".(isset($_GET["id"]) ? "?id=".$_GET["id"] : "")."'"; } ?>" value='Cancelar'/></td>
                        </tr>
                      </table>
                      <input type="hidden" name="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print "MM_edit"; } else { print "MM_insert"; } ?>" value="actualizar">
                      <?php  print "<input type='hidden' name='idp' value='".$_GET["idp"]."'>";
                             print "<input type='hidden' name='id' value='".$_GET["id"]."'>"; ?>
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