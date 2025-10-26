<?php

include("valida.php");

if (isset($_GET["mode"]) && $_GET["mode"]=='d') {

      $sql="delete from clientes where idClientes=".$_GET["id"];
      mysql_query($sql) or die(mysql_error());

      header("Location: clientes.php");
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
                            <td class="textsubmenu"><img src="images/ok.gif" /></td><td class="textsubmenu"><a href="clientes_nuevos.php" class="submenu">Nuevo Cliente</a></td>
                        </tr>
                        <tr>
                            <td class="textsubmenu"><img src="images/ok.gif" /></td><td class="textsubmenu"><a href="financiadores.php" class="submenu">Financiadores</a></td>
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
                    <td class='tititems' width='50%'>Nombre</td><td class='tititems' width='12%'>Tel&eacute;fono</td><td class='tititems' width='18%'>Ciudad<br>Pa&iacute;s</td><td class='tititems'>Acciones</td>
                </tr>
                <?php
                    $col1="#eeeeee";
                    $col2="#ffffff";
                    $sql="select * from clientes order by nombre";
                    $res=mysql_query($sql);
                    $colactual="";
                    while ($fila=mysql_fetch_array($res)) {
                          $sql1="select count(*) from propuesta where idclientes=$fila[0]";
                          $res1=mysql_query($sql1);
                          $fila1=mysql_fetch_array($res1);

                          $sql2="select count(*) from contacto where idclientes=$fila[0]";
                          $res2=mysql_query($sql2);
                          $fila2=mysql_fetch_array($res2);

                          if ($colactual!=$col1) {
                             $colactual=$col1;
                          } else {
                             $colactual=$col2;
                          }
                          print "
                <tr bgcolor='$colactual'>
                    <td class='tabladettxt'>".$fila[6]."</td><td class='tabladetnum'>".$fila[2]."</td><td  class='tabladettxt'>".$fila[4]." ".$fila[5]."</td><td class='tabladettxt'><a href='clientes_nuevos.php?mode=e&id=".$fila[0]."'><img src=\"images/editar.png\" width=\"20\" alt=\"Editar\" title=\"Editar\" border=\"0\" /></a>
                    <a onclick=\"window.open('contactos.php?t=popup&id=".$fila[0]."', '_blank', 'status=0,menubar=0,location=0,scrollbars=0,resizable=0,width=700,height=250', 0);\" href='#'><img src=\"images/272.png\" height=\"22\" alt=\"Contactos\" title=\"Contactos\" border=\"0\" /></a> ";
                    if ($fila1[0]+$fila2[0]<1) {
                        print "<a onclick='if(confirm(\"Esta seguro que desea eliminar este cliente?\")) { return true;} else { return false; }' href='clientes.php?mode=d&id=".$fila[0]."'><img src=\"images/eliminar.png\" width=\"20\" alt=\"Eliminar\" title=\"Eliminar\" border=\"0\" /></a>";
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