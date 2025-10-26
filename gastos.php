<?php

include("valida.php");

if (isset($_GET["mode"]) && $_GET["mode"]=='d') {

      $sql="delete from gastos where idgastos=".$_GET["id"];
      mysql_query($sql) or die(mysql_error());

      header("Location: gastos.php");
      exit;

}

include("encabezado.php");

?>

<table align="center" bgcolor="#ffffff" width="100%">
<tr>
    <td width="200" valign="top">
        <table align="center" width="100%">
                        <tr>
                            <td class="textsubmenu"><img src="images/bc_parent.png" /></td><td class="textsubmenu"><a href="finanzas.php" class="submenu">Retornar al men&uacute; anterior</a></td>
                        </tr>
                        <tr>
                            <td class="textsubmenu"><img src="images/ok.gif" /></td><td class="textsubmenu"><a href="nuevo_gasto.php" class="submenu">Nuevo Gasto</a></td>
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
                    <td class='tititems' width='32%'>Concepto</td><td class='tititems' width='10%'>Fecha</td><td class='tititems' width='10%'>Monto</td><td class='tititems' width='15%'>Forma Pago</td><td class='tititems' width='10%'>Estado</td><td class='tititems' width='12%'>Rendici&oacute;n</td><td class='tititems' width='16%'>Acciones</td>
                </tr>
                <?php
                    $col1="#dedede";
                    $col2="#efefef";
                    $sql="select pr.idgastos, pr.concepto, pr.fecharecepcion, pr.nrosolicitud, pr.moneda, pr.montopagado, pr.facturapresentada, pr.anombrede, fp.descripcion, pr.estadocancelacion,pr.necesitarendicion from gastos pr inner join tipos_pago fp on fp.id_tipopago=pr.formapago order by fecharecepcion";
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
                    <td class='tabladettxt'>".$fila[1]."</td><td class='tabladettxt'>".fecha($fila[2],8)."</td><td  class='tabladetnum'>$fila[4].$fila[5]</td><td  class='tabladettxt'>".$fila[8]."</td><td  class='tabladettxt'>".$fila[9]."</td><td  class='tabladettxt'>".($fila[10]==0 ? "No" : "S&iacute;" )."</td><td class='tabladettxt'><a href='nuevo_gasto.php?mode=e&id=".$fila[0]."'><img src=\"images/editar.png\" alt=\"Editar\" title=\"Editar\" width=\"20\" border=\"0\" /></a>";
                    print "<a onclick='if(confirm(\"Esta seguro que desea eliminar este gasto?\")) { return true;} else { return false; }' href='gastos.php?mode=d&id=".$fila[0]."'><img src=\"images/eliminar.png\" alt=\"Eliminar\" title=\"Eliminar\" width=\"20\" border=\"0\" /></a>";
                    print "

                    </td>
                </tr>";
                    }

                ?>
                <tr>
                    <td><br></td>
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