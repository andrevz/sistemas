<?php

include("valida.php");

if (isset($_POST["MM_insert"])) {
    if ($_POST["nombre"]=="" or is_null($_POST["nombre"])) {
       $error="<font color='red'><b>Debe registrarse obligatoriamente el nombre</b></font>";
    } else {

      $sql="insert into clientes values (null, '".$_POST["nombre"]."', '".$_POST["telefono"]."', '".$_POST["direccion"]."', '".$_POST["ciudad"]."',  '".$_POST["pais"]."',  '".$_POST["nombrecompleto"]."')";
      mysqli_query($sql) or die(mysqli_error());

      if (!(isset($_GET["t"]) && $_GET["t"]=="popup")) {
         header("Location: clientes.php");

      } else {
         print "<script type='text/javascript' src='select_dependientes.js'></script>
         <script language='javascript'>cargaContenido(\"clientes\"); </script>
        ";
      }
      exit;
   }
} else if (isset($_POST["MM_edit"])) {
    if ($_POST["nombre"]=="" or is_null($_POST["nombre"])) {
       $error="<font color='red'><b>Debe registrarse obligatoriamente el nombre</b></font>";
    } else {

      $sql="update clientes set nombre='".$_POST["nombre"]."', telefono='".$_POST["telefono"]."', direccion='".$_POST["direccion"]."',
                   ciudad='".$_POST["ciudad"]."',  pais='".$_POST["pais"]."',  nombrecompleto='".$_POST["nombrecompleto"]."' where idclientes=".$_POST["idc"];
      mysqli_query($sql) or die(mysqli_error());


      header("Location: clientes.php");
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
                            <td class="textsubmenu"><img src="images/bc_parent.png" /></td><td class="textsubmenu"><a href="menu.php" class="submenu">Retornar al men&uacute; principal</a></td>
                        </tr>
                        <tr>
                            <td class="textsubmenu"><img src="images/ok.gif" /></td><td class="textsubmenu"><a href="clientes.php" class="submenu">Editar clientes</a></td>
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
                                  $sqle="select * from clientes where idClientes=".$_GET["id"];
                                  $rese=mysqli_query($sqle);
                                  $filae=mysqli_fetch_array($rese);
                              } else {
                                  print "NUEVO";
                              }
                              ?> CLIENTE<a name='nuevo'>
                    </td>
                </tr>
                <tr>
                    <td>
                    <form method="POST" name="actualizar">
                      <table align="center">
                        <tr valign="baseline">
                          <td  class="tititems" align="right">Nombre Corto:</td>
                          <td><input type="text" name="nombre" size="30" maxlength="50" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print $filae[1]; } ?>"></td>
                        </tr>
                        <tr valign="baseline">
                          <td  class="tititems" align="right">Nombre Completo:</td>
                          <td><input type="text" name="nombrecompleto" size="60"  maxlength="150" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print $filae[6]; } ?>" /></td>
                        </tr>
                        <tr valign="baseline">
                          <td class="tititems" align="right" valign="top">Tel&eacute;fono:</td>
                          <td><input type="text" name="telefono" size="12" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print $filae[2]; } ?>"/>
                          </td>
                        </tr>
                        <tr valign="baseline">
                          <td class="tititems" align="right" valign="top">Direcci&oacute;n:</td>
                          <td><input type="text" name="direccion" size="60" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print $filae[3]; } ?>">
                          </td>
                        </tr>
                        <tr valign="baseline">
                          <td class="tititems" align="right" valign="top">Ciudad:</td>
                          <td><input type="text" name="ciudad" size="20" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print $filae[4]; } ?>">
                          </td>
                        </tr>
                        <tr valign="baseline">
                          <td class="tititems" align="right" valign="top">Pa&iacute;s:</td>
                          <td><input type="text" name="pais" size="20" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print $filae[5]; } ?>">
                          </td>
                        </tr>
                        <tr valign="baseline">
                          <td colspan="2" align="center"><input class="boton1" type="submit" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print "Editar"; } else { print "Crear"; }?> cliente">
                          <input class="boton1" name="Cancelar" type="button" onClick="<?php if ((isset($_GET["t"]) && $_GET["t"]=="popup")) { print "window.close();"; } else { print "window.location='clientes.php'"; } ?>" value='Cancelar'/></td>
                        </tr>
                      </table>
                      <input type="hidden" name="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print "MM_edit"; } else { print "MM_insert"; } ?>" value="actualizar">
                      <?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print "<input type='hidden' name='idc' value='".$_GET["id"]."'>"; } ?>
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