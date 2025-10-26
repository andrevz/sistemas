<?php

include("valida.php");

if (isset($_POST["MM_insert"])) {
    if ($_POST["titulo"]=="" or is_null($_POST["titulo"])) {
       $error="<font color='red'><b>Debe registrarse obligatoriamente el titulo</b></font>";
    } else {

//       $tipos = array("image/gif","image/jpeg","image/bmp","image/pjpeg");
       $maximo = 10485760; //10Mb
       $tamano=0;
       if (is_uploaded_file($_FILES['archivo']['tmp_name'])) { // Se ha subido?
          if ($_FILES['archivo']['size'] <= $maximo) { // Es correcto?
              $fp = fopen($_FILES['archivo']['tmp_name'], 'r'); //Abrimos la imagen
              $tamano=filesize($_FILES['archivo']['tmp_name']);
              $imagen = fread($fp, $tamano); //Extraemos el contenido de la imagen
              $imagen = addslashes($imagen);
              fclose($fp); //Cerramos imagen
          }
          $sql="insert into documentos values (null, '";
          $nombre=$_FILES['archivo']['name'];
          $nombre=str_replace(" ","_",$nombre);
          if(!get_magic_quotes_gpc())
                $sql.=$nombre = addslashes($nombre); // Arreglamos el Nombre
          else $sql.= $_FILES['archivo']['name'];
                $sql.="', '".$_POST["descripcion"]."', '".$_FILES['archivo']['type']."', '".$imagen."', '".$_POST["idrh"]."', now(), '".$_POST["titulo"]."', '".$_FILES['archivo']['size']."', '".$_POST["idem"]."','".$_POST["idv"]."')";
          mysqli_query($sql) or die(mysqli_error());
          if (!(isset($_GET["t"]) && $_GET["t"]=="popup")) {
                header("Location: documentos.php");

                } else {
                   header("Location: documentos.php?t=popup&idp=".$_POST["idp"]."&idrh=".$_POST["idrh"]."&idv=".$_POST["idv"]."&idem=".$_POST["idem"]);
                }
                exit;
       } else { $error="<font color='red'><b>El documento no se ha subido al servidor</b></font>"; }

   }
} else if (isset($_POST["MM_edit"])) {
    if ($_POST["titulo"]=="" or is_null($_POST["titulo"])) {
       $error="<font color='red'><b>Debe registrarse obligatoriamente el titulo</b></font>";
    } else {


       $maximo = 10485760; //10Mb
       $tamano=0;
       if (is_uploaded_file($_FILES['archivo']['tmp_name'])) { // Se ha subido?
          if ($_FILES['archivo']['size'] <= $maximo) { // Es correcto?
              $fp = fopen($_FILES['archivo']['tmp_name'], 'r'); //Abrimos la imagen
              $tamano=filesize($_FILES['archivo']['tmp_name']);
              $imagen = fread($fp, $tamano); //Extraemos el contenido de la imagen
              $imagen = addslashes($imagen);
              fclose($fp); //Cerramos imagen
          }
          $sql="update documentos set nombrearchivo='";
          $nombre=$_FILES['archivo']['name'];
          $nombre=str_replace(" ","_",$nombre);
          if(!get_magic_quotes_gpc())
                $sql.=$nombre = addslashes($nombre); // Arreglamos el Nombre
          else $sql.= $_FILES['archivo']['name'];
                $sql.="', descripcion='".$_POST["descripcion"]."', tipo='".$_FILES['archivo']['type']."', contenido='".$imagen."', titulo='".$_POST["titulo"]."', tamano='".$_FILES['archivo']['size']."' where iddocumentos=".$_GET["idd"];
          mysqli_query($sql) or die(mysqli_error());
          if (!(isset($_GET["t"]) && $_GET["t"]=="popup")) {
                header("Location: documentos.php");

                } else {
                   header("Location: documentos.php?t=popup&idrh=".$_POST["idrh"]."&idv=".$_POST["idv"]."&idem=".$_POST["idem"]);
                }
                exit;
       } else {

          $sql="update documentos set descripcion='".$_POST["descripcion"]."', titulo='".$_POST["titulo"]."' where iddocumentos=".$_GET["idd"];
          mysqli_query($sql) or die(mysqli_error());
          if (!(isset($_GET["t"]) && $_GET["t"]=="popup")) {
                header("Location: documentos.php");

                } else {
                   header("Location: documentos.php?t=popup&idrh=".$_POST["idrh"]."&idv=".$_POST["idv"]."&idem=".$_POST["idem"]);
                }
                exit;
           }

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
                            <td class="textsubmenu"><img src="images/bc_parent.png" /></td><td class="textsubmenu"><a href="documentos.php" class="submenu">Retornar al men&uacute; anterior</a></td>
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
                                  $sqle="select * from documentos where iddocumentos=".$_GET["idd"];
                                  $rese=mysqli_query($sqle);
                                  $filae=mysqli_fetch_array($rese);
                              } else {
                                  print "NUEVO";
                              }
                              ?> DOCUMENTO<a name='nuevo'>
                    </td>
                </tr>
                <tr>
                    <td>
                    <form method="POST" name="actualizar" enctype="multipart/form-data">
                      <table align="center">
                        <tr valign="baseline">
                          <td  class="tititems" align="right">T&iacute;tulo:</td>
                          <td><input type="text" name="titulo" size="30" maxlength="150" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print $filae[7]; } ?>"></td>
                        </tr>
                        <tr valign="baseline">
                          <td  class="tititems" align="right">Descripci&oacute;n:</td>
                          <td><input type="text" name="descripcion" size="60"  maxlength="250" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print $filae[2]; } ?>" />
                          <input type="hidden" name="idp" value="<?php if (isset($_GET["idp"])) { print $_GET["idp"]; } ?>" />
                          <input type="hidden" name="idrh" value="<?php if (isset($_GET["idrh"])) { print $_GET["idr"]; } ?>" />
                          </td>
                        </tr>
                        <tr valign="baseline">
                          <td class="tititems" align="right" valign="top">Archivo:</td>
                          <td><input type="file" name="archivo" >
                          <?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print "<font color='red' size='1'>* Seleccione un archivo s&oacute;lo si desea reemplazar el anterior</font>"; } ?>
                          </td>
                        </tr>
                        <tr valign="baseline">
                          <td colspan="2" align="center"><input class="boton1" type="submit" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print "Editar"; } else { print "Subir"; }?> documento">
                          <input class="boton1" name="Cancelar" type="button" onClick="<?php if ((isset($_GET["t"]) && $_GET["t"]=="popup")) { print "window.location='documentos.php?t=popup&idrh=".$_GET["idrh"]."&idv=".$_GET["idv"]."&idem=".$_GET["idem"]."'"; } else { print "window.location='documentos.php'"; } ?>" value='Cancelar'/></td>
                        </tr>
                      </table>
                      <input type="hidden" name="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print "MM_edit"; } else { print "MM_insert"; } ?>" value="actualizar">
                      <?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print "<input type='hidden' name='idd' value='".$_GET["idd"]."'>"; } ?>
                      <?php print "<input type='hidden' name='idrh' value='".$_GET["idrh"]."'>"; ?>
                      <?php print "<input type='hidden' name='idv' value='".$_GET["idv"]."'>"; ?>
                      <?php print "<input type='hidden' name='idem' value='".$_GET["idem"]."'>"; ?>

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