<?php

include("valida.php");

if (isset($_POST["MM_insert"])) {
    if ($_POST["nombre"]=="" or is_null($_POST["nombre"])) {
       $error="<font color='red'><b>Debe registrarse obligatoriamente el nombre del contacto</b></font>";
    } else {

      $sql="insert into contacto values (null, '".$_POST["nombre"]."', '".$_POST["telefono"]."', '".$_POST["id"]."', '".$_POST["celular"]."', '".$_POST["email"]."', '".$_POST["cargo"]."')";
      mysqli_query($sql) or die(mysqli_error());

      header("Location: contactos.php?id=".$_POST["id"]);
      exit;
   }
} else if (isset($_GET["mode"]) && $_GET["mode"]=="d") {

      $sql="delete from contacto where idcontacto=".$_GET["idc"];
      mysqli_query($sql) or die(mysqli_error());

      header("Location: contactos.php?id=".$_GET["id"]);
      exit;
} else if (isset($_POST["MM_edit"])) {
    if ($_POST["nombre"]=="" or is_null($_POST["nombre"])) {
       $error="<font color='red'><b>Debe registrarse obligatoriamente el nombre del contacto</b></font>";
    } else {

      $sql="update contacto set nombre='".$_POST["nombre"]."', telefono='".$_POST["telefono"]."', celular='".$_POST["celular"]."', email='".$_POST["email"]."', cargo='".$_POST["cargo"]."' where idcontacto=".$_POST["idc"];
      mysqli_query($sql) or die(mysqli_error());

      header("Location: contactos.php?id=".$_POST["id"]);
      exit;
   }
}

   ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php print $nombresistema; ?></title>
<link href="<?php print $path; ?>estilos.css" rel="stylesheet" type="text/css" />
<script type='text/javascript' src='tabla.js'></script>

</html>

<table align="center" bgcolor="#ffffff" width="100%">
<tr>
    <td width="600">
            <?php
                $sqldatos="SELECT cl.nombrecompleto
                                  FROM clientes cl
                                  where cl.idclientes=".$_GET["id"];
                $resdatos=mysqli_query($sqldatos);
                $filadatos=mysqli_fetch_array($resdatos);
            ?>
            <table class='contenido' width='100%'>
                   <tr>
                       <td class='tititems' align='right' width='20%'>Cliente:</td><td class='tablatxt'><?php print $filadatos[0]; ?></td>
                   </tr>
            </table>
            <table class='contenido' width='100%'>
                <tr>
                    <td colspan="6" class="titmenu">
                              CONTACTOS<a name='nuevo'>
                    </td>
                </tr>                <tr>
                    <td class='tititems' width='40%'>Nombre</td>
                    <td class='tititems' width='15%'>Tel&eacute;fono</td>
                    <td class='tititems' width='15%'>Celular</td>
                    <td class='tititems' width='15%'>E-mail</td>
                    <td class='tititems' width='15%'>Cargo</td>
                    <td class='tititems'>Acciones</td>
                </tr>
                <?php
                    $col1="#eeeeee";
                    $col2="#ffffff";
                    if (isset($_GET["mode"]) && $_GET["mode"]=="e") {
                           $sql="select * from contacto where idclientes=".$_GET["id"]." and idcontacto not like '".$_GET["idc"]."' order by nombre";
                    } else {
                           $sql="select * from contacto where idclientes=".$_GET["id"]." order by nombre";
                    }
                    $res=mysqli_query($sql);
                    $colactual="";
                    while ($fila=mysqli_fetch_array($res)) {
                          if ($colactual!=$col1) {
                             $colactual=$col1;
                          } else {
                             $colactual=$col2;
                          }
                          print "
                <tr bgcolor='$colactual'>
                    <td class='tabladettxt'>".$fila[1]."</td><td class='tabladettxt'>".$fila[2]."</td><td  class='tabladettxt'>".$fila[4]."</td><td  class='tabladettxt'>".$fila[5]."</td><td  class='tabladettxt'>".$fila[6]."</td><td class='tabladettxt'>";
                print "<a onclick='if(confirm(\"Esta seguro que desea eliminar este contacto?\")) { return true;} else { return false; }' href='contactos.php?mode=d&idc=".$fila[0]."&id=".$_GET["id"]."'><img src=\"images/eliminar.png\" alt=\"Eliminar\" title=\"Eliminar\" width=\"15\" height=\"15\" border=\"0\" /></a>
                      <a href='contactos.php?mode=e&idc=".$fila[0]."&id=".$_GET["id"]."'><img src=\"images/editar.png\" width=\"20\" alt=\"Editar\" title=\"Editar\" border=\"0\" /></a>";
                print "</td>
                </tr>";
                    }
                    if (isset($_GET["mode"]) && $_GET["mode"]=="e") {
                           $sql="select * from contacto where idcontacto like '".$_GET["idc"]."'";
                    }
                    $res1=mysqli_query($sql);
                    $filae=mysqli_fetch_array($res1);


                ?>

             <form method="POST" name="actualizar">
                <tr>
                    <td><input type="text" class='boton3' name="nombre" size="40" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print $filae[1]; } ?>"></td>
                    <td><input type="text" class='boton3' name="telefono" size="12"  maxlength="15" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print $filae[2]; } ?>" /></td>
                    <td><input type="text" class='boton3' name="celular" size="12"  maxlength="15" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print $filae[4]; } ?>" /></td>
                    <td><input type="text" class='boton3' name="email" size="15"  maxlength="15" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print $filae[5]; } ?>" /></td>
                    <td><input type="text" class='boton3' name="cargo" size="15"  maxlength="15" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print $filae[6]; } ?>" /></td>
                    <td><input class="boton1" type="submit" name="guardar" size="60" value="Guardar">
                          <input class="boton1" name="Cancelar" type="button" onClick=" window.close(); window.opener.location.reload();" value='Cancelar'/></td>
                        </tr>
                      </table>
                      <input type="hidden" name="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print "MM_edit"; } else { print "MM_insert"; } ?>" value="actualizar">
                      <?php
                               print "<input type='hidden' name='id' value='".$_GET["id"]."'>";
                               print "<input type='hidden' name='idc' value='".$filae[0]."'>";
                            ?>
                    </form>
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