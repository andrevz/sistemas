<?php

include("valida.php");

if (isset($_POST["MM_edit"])) {
    if ($_POST["gestion"]=="" or is_null($_POST["gestion"]) or $_POST["responsable"]=="-1") {
       $error="<font color='red'><b>Debe registrarse obligatoriamente la gesti&oacute;n y el responsable</b></font>";
    } else {

      $sql="update version set gestion='".$_POST["gestion"]."', ciudad='".$_POST["ciudad"]."',
                   colegiatura='".$_POST["colegiatura"]."', matricula='".$_POST["matricula"]."',
                   idrecursohumano='".$_POST["recursohumano"]."', nrodias='".$_POST["dias"]."',
                   nromeses='".$_POST["meses"]."', inicioprogramado='".fecha($_POST["inicioprogramado"],99)."',
                   finprogramado='".fecha($_POST["finprogramado"],99)."' where idversion=".$_POST["idv"];
      mysql_query($sql) or die(mysql_error());


      header("Location: versiones.php?idp=".$_POST["idprograma"]);
      exit;
   }
}


  $sql="select v.idversion, v.gestion, v.ciudad, v.nrodiasejecutado, v.nromesesejecutado, v.inicioejecutado, v.finejecutado, p.nombre, rh.nombres, rh.apellidos from version v inner join programa p on p.idprograma=v.idprograma inner join recursohumano rh on rh.idrecursohumano=v.idrecursohumano where idversion=".$_GET["idv"];
  $res=mysql_query($sql);
  $fila_e=mysql_fetch_array($res);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php print $nombresistema; ?></title>
<link href="<?php print $path; ?>estilos.css" rel="stylesheet" type="text/css" />
</html>

<table align="center" bgcolor="#ffffff" width="100%">
<tr>
    <td width="100%">
    <table align="center" width="100%" cellspacing='2'>
        <tr>
            <td class="bordes">
            <table class="contenido" width="100%">
                <tr>
                    <td colspan="2" class="titmenu">
                        SEGUIMIENTO A LA MATERIA
                    </td>
                </tr>
                <tr>
                    <td>
                    <form method="POST" name="actualizar">
                      <table align="center">
                        <tr valign="baseline">
                          <td  class="tititems" align="right" width='15%'>Programa:</td>
                          <td><?php print $fila_e[7]; ?></td>
                        </tr>
                        <tr valign="baseline">
                          <td  class="tititems" align="right">Gesti&oacute;n:</td>
                          <td><?php print $fila_e[1];  ?></td>
                        </tr>
                        <tr valign="baseline">
                          <td  class="tititems" align="right">Ciudad:</td>
                          <td><?php print $fila_e[2];  ?></td>
                        </tr>
                        <tr valign="baseline">
                          <td class="tititems" align="right" valign="top">Responsable:</td>
                          <td><?php print $fila_e[9].", ".$fila_e[8];   ?>

                          </td>
                        </tr>
                        <tr valign="baseline">
                          <td class="tititems" align="right" valign="top">Dias de clase:</td>
                          <td><?php print $fila_e[3]; ?>
                          </td>
                        </tr>
                        <tr valign="baseline">
                          <td class="tititems" align="right" valign="top">Nro. Meses:</td>
                          <td><?php print $fila_e[4]; ?>
                          </td>
                        </tr>
                        <tr valign="baseline">
                          <td  class="tititems" align="right">Inicio:</td>
                          <td><input class='boton1' type="text" name="inicioprogramado" size="10" value="<?php  print fecha($fila_e[5],8); ?>" id="convo"></td>
                        </tr>
                        <tr valign="baseline">
                          <td  class="tititems" align="right">Fin:</td>
                          <td><input class='boton1' type="text" name="finprogramado" size="10" value="<?php  print fecha($fila_e[6],8);  ?>"  id="ela"></td>
                        </tr>

                        <tr valign="baseline">
                          <td colspan="2" align="center"><input class="save" type="submit" value="">
                          <input class="exit" name="Cancelar" type="button" onClick="window.close()" value=''></td>
                        </tr>
                      </table>
                      <input type="hidden" name="idprograma" value="<?php print $_GET["idp"] ?>">
                      <input type="hidden" name="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print "MM_edit"; } else { print "MM_insert"; } ?>" value="actualizar">
                      <?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print "<input type='hidden' name='idv' value='".$fila_e[0]."'>"; }
                      ?>
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

</table>
</td></tr></table>
<?php
include("pie.php");
?>
</body>
</html>