<?php

include("valida.php");

if (isset($_GET["mode"]) && $_GET["mode"]=='d') {

      $sql="delete from version where idversion=".$_GET["idv"];
      mysql_query($sql) or die(mysql_error());

      header("Location: versiones.php?idp=".$_GET["idp"]);
      exit;

}

if (isset($_GET["mode"]) && $_GET["mode"]=='a') {

      $sql="update version set activo=if(activo=1,0,1) where idversion=".$_GET["idv"];
      mysql_query($sql) or die(mysql_error());

      header("Location: versiones.php?idp=".$_GET["idp"]);
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
                            <td class="textsubmenu"><img src="images/ok.gif" /></td><td class="textsubmenu"><a href="reportes.php" class="submenu">Volver a reportes</a></td>
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
                    <td class='tititems' width='35%'>Programa</td>
                    <td class='tititems' width='7%'>Gesti&oacute;n</td>
                    <td class='tititems' width='12%'>Ciudad</td>
                    <td class='tititems' width='20%'>Responsable</td>
                    <td class='tititems' width='12%'>Inicio Prog.</td>
                    <td class='tititems' width='15%'>Reportes</td>
                </tr>
                <?php
                    $col1="#dedede";
                    $col2="#efefef";
                    $sql="select idversion, gestion, v.ciudad, inicioprogramado, nombres, apellidos, nombre, p.idprograma
                                 from version v inner join recursohumano rh on rh.idrecursohumano=v.idrecursohumano
                                 inner join programa p on p.idprograma=v.idprograma where activo=1 order by gestion";
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
                    <td class='tabladettxt'>".$fila[6]."</td>
                    <td class='tabladettxt'>".$fila[1]."</td>
                    <td class='tabladettxt'>".$fila[2]."</td>
                    <td class='tabladettxt'>".$fila[5].", ".$fila[4]."</td>
                    <td class='tabladettxt'>".fecha($fila[3],8)."</td>
                    <td class='tabladettxt'>
                    <a href='reportes/planificacion.php?idv=".$fila[0]."' target='_blank'><img src=\"images/plan.png\" alt=\"Planificación general\" title=\"Planificación general\" width=\"20\" border=\"0\" /></a>
                    <a href='reportes/control.php?idv=".$fila[0]."' target='_blank'><img src=\"images/control.png\" alt=\"Ejecución general\" title=\"Ejecución general\" width=\"20\" border=\"0\" /></a>
                    ";
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

</table>
</td></tr></table>
<?php
include("pie.php");
?>
</body>
</html>