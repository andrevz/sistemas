<?php

include("valida.php");

$actividad=3;
include("niveles_acceso.php");

if (isset($_GET["mode"]) && $_GET["mode"]=='d' && $eliminar) {

      $sql="delete from medio where idmedio=".$_GET["id"];
      mysqli_query($sql) or die(mysqli_error());

      header("Location: edmedios.php");
      exit;

}
if (isset($_POST["MM_edit"]) && $_POST["MM_edit"]=="actualizar" && $editar) {

      $sql="update medio set nombre='".$_POST["medio"]."' where idmedio=".$_POST["ids"];
      mysqli_query($sql) or die(mysqli_error());

      header("Location: edmedios.php");
      exit;

}
if (isset($_POST["MM_insert"]) && $_POST["MM_insert"]=="actualizar" && $insertar) {

      $sql="insert into medio values (null, '".$_POST["medio"]."')";
      mysqli_query($sql) or die(mysqli_error());

      header("Location: edmedios.php");
      exit;

}
include("encabezado.php");

?>

<table align="center" bgcolor="#ffffff" width="100%">
<tr>
    <td width="200" valign="top">
        <table align="center" width="100%">
                        <tr>
                            <td class="textsubmenu"><img src="images/bc_parent.png" /></td><td class="textsubmenu"><a href="parametros.php" class="submenu">Retornar al men&uacute; anterior</a></td>
                        </tr>

        </table>

        <br><br>
    </td>
    <td width="800">
    <table align="center" width="100%" cellspacing='2'>
        <tr>
            <td class="bordes">
            <table class='contenido' width='100%'>
                <tr>
                    <td class='tititems' width='500'>Nombre</td>
                    <td class='tititems' width='70'>Acciones</td>
                </tr>
                <?php
                    $col1="#eeeeee";
                    $col2="#ffffff";
                    if (isset($_GET["ids"])) {
                       $sql="select * from medio where idmedio not like '".$_GET["ids"]."' order by nombre";
                    } else {
                       $sql="select * from medio order by nombre";
                    }

                    $res=mysqli_query($sql);
                    $colactual="";
                    while ($fila=mysqli_fetch_array($res)) {
                          $sql1="select count(*) from planmedios where idmedio=$fila[0]";
                          $res1=mysqli_query($sql1);
                          $fila1=mysqli_fetch_array($res1);
                          if ($colactual!=$col1) {
                             $colactual=$col1;
                          } else {
                             $colactual=$col2;
                          }
                          print "
                <tr bgcolor='$colactual'>
                    <td class='tabladettxt'>".$fila[1]."</td>
                    <td align='center'>";
                    if ($editar) {
                       print "<a href='edmedios.php?mode=e&ids=".$fila[0]."#editor'><img border='0' src='images/editar.png' width='20' alt='Editar' title='Editar'></a> ";
                    }
                    if ($fila1[0]<1 && $eliminar) {
                        print "<a onclick='if(confirm(\"Esta seguro que desea eliminar el medio ".$fila[1]."?\")) { return true;} else { return false; }' href='edmedios.php?mode=d&id=".$fila[0]."'><img border='0' src='images/delete.png' width='20' alt='Eliminar' title='Eliminar'></a>";
                    }
                    print "</td>
                </tr>";
                    }
                 if (isset($_GET["ids"])) {
                  $sql="select * from medio where idmedio=".$_GET["ids"];
                  $resres=mysqli_query($sql);
                  $filares=mysqli_fetch_array($resres);
               }
                if ($insertar || ($editar && isset($_GET["ids"]))) {
                ?>
            <form method="POST" name="actualizar">
             <table width='100%' class='contenido'>
                <tr>
                    <td width='550'>
                         <input name='medio' id='medio' type='text' size='80' value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e" ) {
                          print $filares[1]; } ?>">
                    </td>
                    <td width='70'><input class="save" type="submit" name="guardar" size="60" value="">
                    <?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { ?>
                          <input class="cancel" type="button" name="cancelar" size="60" value="" onclick="window.location='edmedios.php'">
                    <?php } ?>
                         </td>
                        </tr>
                      </table>
                      <input type="hidden" name="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print "MM_edit"; } else { print "MM_insert"; } ?>" value="actualizar">
                      <?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") {
                               print "<input type='hidden' name='ids' value='".$_GET["ids"]."'>";
                            }?>
                    </form><a name='editor'> </a>
                    <?php
                    }
                    ?>

            </table>

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