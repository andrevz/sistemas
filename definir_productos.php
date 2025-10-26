<?php

include("valida.php");

if (isset($_POST["MM_insert"])) {
    if ($_POST["fechaini"]=="" or is_null($_POST["fechaini"]) or $_POST["descripcion"]=="" or is_null($_POST["descripcion"])) {
       $error="<font color='red'><b>Deben registrarse obligatoriamente la fecha de inicio y la descripcion</b></font>";
    } else {

      $sql="insert into producto values (null, '".$_POST["descripcion"]."', '".$_POST["id"]."', '".$_POST["fechaini"]."', '".$_POST["fechafin"]."', null, null, false, null, null, null)";
      mysql_query($sql) or die(mysql_error());

      header("Location: definir_productos.php?id=".$_POST["id"]);
      exit;
   }
} else if (isset($_GET["mode"]) && $_GET["mode"]=="d") {

      $sql="delete from producto where idproducto=".$_GET["idrp"];
      mysql_query($sql) or die(mysql_error());

      header("Location: definir_productos.php?id=".$_GET["id"]);
      exit;
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
                $sqldatos="SELECT p.fechainicio, p.fechafinal, pr.titulo, cl.nombrecompleto
                                  FROM proyecto p
                                       inner join propuesta pr on pr.idproyecto=p.idproyecto inner join clientes cl on cl.idclientes=pr.idclientes
                                  where p.idproyecto=".$_GET["id"];
                $resdatos=mysql_query($sqldatos);
                $filadatos=mysql_fetch_array($resdatos);
            ?>
            <table class='contenido' width='100%'>
                   <tr>
                       <td class='tititems' align='right' width='40%'>Proyecto:</td><td class='tablatxt'><?php print $filadatos[2]; ?></td>
                   </tr>
                   <tr>
                       <td class='tititems' align='right'>Cliente:</td><td class='tablatxt'><?php print $filadatos[3]; ?></td>
                   </tr>
                   <tr>
                       <td class='tititems' align='right'>Inicio proyecto:</td><td class='tablatxt'><?php print $filadatos[0]; ?></td>
                   </tr>
                   <tr>
                       <td class='tititems' align='right'>Fin proyecto:</td><td class='tablatxt'><?php print $filadatos[1]; ?></td>
                   </tr>
            </table>
            <table class='contenido' width='100%'>
                <tr>
                    <td colspan="4" class="titmenu">
                              PRODUCTOS<a name='nuevo'>
                    </td>
                </tr>                <tr>
                    <td class='tititems' width='55%'>Descripci&oacute;n</td><td class='tititems' width='15%'>Fecha Inicio</td><td class='tititems' width='15%'>Fecha Fin</td><td class='tititems'>Acciones</td>
                </tr>
                <?php
                    $col1="#eeeeee";
                    $col2="#ffffff";
                    $sql="select * from producto where idproyecto=".$_GET["id"]." order by fechainicio, fechafinal";
                    $res=mysql_query($sql);
                    $colactual="";
                    while ($fila=mysql_fetch_array($res)) {
                          if ($colactual!=$col1) {
                             $colactual=$col1;
                          } else {
                             $colactual=$col2;
                          }
                          print "
                <tr bgcolor='$colactual'>
                    <td class='tabladettxt'>".$fila[1]."</td><td class='tabladettxt'>".$fila[3]."</td><td  class='tabladettxt'>".$fila[4]."</td><td class='tabladettxt'>";
                print "<a onclick='if(confirm(\"Esta seguro que desea eliminar este producto?\")) { return true;} else { return false; }' href='definir_productos.php?mode=d&idrp=".$fila[0]."&id=".$_GET["id"]."'><img src=\"images/eliminar.png\" alt=\"Eliminar\" title=\"Eliminar\" width=\"15\" height=\"15\" border=\"0\" /></a>";
                print "</td>
                </tr>";
                    }

                ?>

             <form method="POST" name="actualizar">
                <tr>
                    <td><input type="text" class='boton3' name="descripcion" size="60" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print $filae[1]; } ?>"></td>
                    <td><input type="text" class='boton3' name="fechaini" size="15"  maxlength="15" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print $filae[6]; } ?>" /></td>
                    <td><input type="text" class='boton3' name="fechafin" size="15"  maxlength="15" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print $filae[6]; } ?>" /></td>
                    <td><input class="boton1" type="submit" name="guardar" size="60" value="Guardar">
                          <input class="boton1" name="Cancelar" type="button" onClick="cargaContenido('productos','<?php print $_GET["id"]; ?>','tabla_productos');" value='Cancelar'/></td>
                        </tr>
                      </table>
                      <input type="hidden" name="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print "MM_edit"; } else { print "MM_insert"; } ?>" value="actualizar">
                      <?php
                               print "<input type='hidden' name='id' value='".$_GET["id"]."'>";
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