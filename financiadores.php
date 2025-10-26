<?php

include("valida.php");

if (isset($_GET["mode"]) && $_GET["mode"]=='d') {

      $sql="delete from financiador where idfinanciador=".$_GET["id"];
      mysqli_query($sql) or die(mysqli_error());

      header("Location: financiadores.php");
      exit;

}

include("encabezado.php");

?>

<table align="center" bgcolor="#ffffff" width="100%">
<tr>
    <td width="200" valign="top">
        <table align="center" width="100%">
                        <tr>
                            <td class="textsubmenu"><img src="images/bc_parent.png" /></td><td class="textsubmenu"><a href="menu.php" class="submenu">Retornar al men&uacute; principal</a></td>
                        </tr>
                        <tr>
                            <td class="textsubmenu"><img src="images/ok.gif" /></td><td class="textsubmenu"><a href="clientes.php" class="submenu">Clientes</a></td>
                        </tr>
                        <tr>
                            <td class="textsubmenu"><img src="images/ok.gif" /></td><td class="textsubmenu"><a href="financiadores_nuevos.php" class="submenu">Nuevo Financiador</a></td>
                        </tr>
        </table>

        <br><br>
    </td>
    <td width="600">
    <table align="center" width="100%" cellspacing='2'>
        <tr>
            <td class="bordes">
            <table class='contenido' width='100%'>
                <tr>
                    <td class='tititems' width='70%'>Nombre</td><td class='tititems' width='12%'>Tel&eacute;fono</td><td class='tititems'>Acciones</td>
                </tr>
                <?php
                    $col1="#eeeeee";
                    $col2="#ffffff";
                    $sql="select * from financiador order by nombre";
                    $res=mysqli_query($sql);
                    $colactual="";
                    while ($fila=mysqli_fetch_array($res)) {
                          $sql1="select count(*) from propuesta where idfinanciador=$fila[0]";
                          $res1=mysqli_query($sql1);
                          $fila1=mysqli_fetch_array($res1);

                          if ($colactual!=$col1) {
                             $colactual=$col1;
                          } else {
                             $colactual=$col2;
                          }
                          print "
                <tr bgcolor='$colactual'>
                    <td class='tabladettxt'>".$fila[1]."</td><td class='tabladetnum'>".$fila[3]."</td><td class='tabladettxt'><a href='financiadores_nuevos.php?mode=e&id=".$fila[0]."'><img src='images/editar.png' width='20'></a> ";
                    if ($fila1[0]<1) {
                        print "<a onclick='if(confirm(\"Esta seguro que desea eliminar este financiador?\")) { return true;} else { return false; }' href='financiadores.php?mode=d&id=".$fila[0]."'><img src='images/eliminar.png' width='20'></a>";
                    }
                    print "</td>
                </tr>";
                    }

                ?>
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