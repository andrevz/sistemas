<?php

include("valida.php");
$nacceso=acceso($_SESSION['idRol'], 0,0,0,0,2,0);
if ($nacceso<3) {
   header("Location: menu.php");
}
if (isset($_POST["MM_insert"]) && $nacceso>4) {
   if ($_POST["nombre"]=="" or is_null($_POST["nombre"])) {
       $error="<font color='red'><b>Debe registrarse obligatoriamente el nombre </b></font>";
    } else {

      $sql="insert into materia values (null, '".$_POST["nombre"]."', '".$_POST["horas"]."', '".$_POST["codigo"]."', '".$_POST["nombreoficial"]."', '".$_POST["programa"]."', '".$_POST["codigoDTI"]."')";
      mysql_query($sql) or die(mysql_error());
      if (!(isset($_GET["t"]) && $_GET["t"]=="popup")) {

         header("Location: materias.php");
      } else {
         print "<script type='text/javascript' src='select_dependientes.js'></script>
         <script language='javascript'>cargaContenido(\"materia\"); </script>
        ";
      }
      exit;
   }
} else if (isset($_POST["MM_edit"]) && ($nacceso==3 || $nacceso==6)) {
    if ($_POST["nombre"]=="" or is_null($_POST["nombre"])) {
       $error="<font color='red'><b>Debe registrarse obligatoriamente el nombre</b></font>";
    } else {

      $sql="update materia set nombre='".$_POST["nombre"]."', horas='".$_POST["horas"]."', codigo='".$_POST["codigo"]."', nombre_oficial='".$_POST["nombreoficial"]."', idprograma='".$_POST["programa"]."', cod_dti='".$_POST["codigoDTI"]."'
                   where idmateria=".$_POST["idmateria"];
      mysql_query($sql) or die(mysql_error());


      header("Location: materias.php");
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
                            <td class="textsubmenu"><img src="images/bc_parent.png" /></td><td class="textsubmenu"><a href="materias.php" class="submenu">Retornar al men&uacute; anterior</a></td>
                        </tr>
                        <tr>
                            <td class="textsubmenu"><img src="images/ok.gif" /></td><td class="textsubmenu"><a href="materias.php" class="submenu">Editar materias</a></td>
                        </tr>

        </table>

        <br><br>
    </td>
    <?php }
    ?>
    <td width="800">

            <table class="contenido" width="100%">
                <tr>
                    <td colspan="2" class="titmenu">
                        <?php if ($_GET["mode"]=='e') {
                                  print "EDITAR";
                                  $sqle="select * from materia where idmateria=".$_GET["id"];
                                  $rese=mysql_query($sqle);
                                  $filae=mysql_fetch_array($rese);
                              } else {
                                  print "NUEVA";
                              }
                              ?> MATERIA <a name='nuevo'>
                    </td>
                </tr>
                <tr>
                    <td>
                    <form method="POST" name="actualizar">
                      <table align="center">
                        <tr valign="baseline">
                          <td  class="tititems" align="right">C&oacute;digo:</td>
                          <td><input type="text" name="codigo" size="10" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print $filae[3]; } ?>"></td>
                        </tr>
                        <tr valign="baseline">
                          <td  class="tititems" align="right">C&oacute;digo DTI:</td>
                          <td><input type="text" name="codigoDTI" size="10" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print $filae[6]; } ?>"></td>
                        </tr>
                        <tr valign="baseline">
                          <td  class="tititems" align="right">Nombre:</td>
                          <td><input type="text" name="nombre" size="60" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print $filae[1]; } ?>"></td>
                        </tr>
                        <tr valign="baseline">
                          <td  class="tititems" align="right">Nombre Gen&eacute;rico:</td>
                          <td><input type="text" name="nombreoficial" size="60" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print $filae[4]; } ?>"></td>
                        </tr>
                        <tr valign="baseline">
                          <td  class="tititems" align="right">Programa:</td>
                          <td><select name='programa' id='programa'>
                           <option value='-1'>--- Elija ---</option>
                          <?php
                              $sql="select idprograma, nombre, sigla from programa order by nombre";
                              $res=mysql_query($sql);
                              while ($fila=mysql_fetch_array($res)) {
                                    print "<option ";
                                    if (isset($_GET["mode"]) && $_GET["mode"]=="e" && ($filae[5]==$fila[0]) || ($fila[0]==$_GET["ipd"])) { print "selected"; }
                                    print " value='$fila[0]'>".substr($fila[1],0,80)." ($fila[2])</option>";
                              }
                          ?>
                          </select></td>
                        </tr>
                        <tr valign="baseline">
                          <td  class="tititems" align="right">Horas:</td>
                          <td><input type="text" name="horas" size="5" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print $filae[2]; } ?>"></td>
                        </tr>
                        <tr valign="baseline">
                          <td colspan="2" align="center"><input class="boton1" type="submit" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print "Editar"; } else { print "Crear"; }?> materia">
                          <input class="boton1" name="Cancelar" type="button" onClick="<?php if ((isset($_GET["t"]) && $_GET["t"]=="popup")) { print "window.close();"; } else { print "window.location='materias.php'"; } ?>" value='Cancelar'/></td>
                        </tr>
                      </table>
                      <input type="hidden" name="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print "MM_edit"; } else { print "MM_insert"; } ?>" value="actualizar">
                      <?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print "<input type='hidden' name='idmateria' value='".$_GET["id"]."'>"; } ?>
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
<p>&nbsp;</p>
<br>
</body>
</html>