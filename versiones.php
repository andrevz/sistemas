<?php

include("valida.php");

$actividad=12;
include("niveles_acceso.php");

if (isset($_GET["mode"]) && $_GET["mode"]=='d' && $eliminar) {

      $sql="delete from version where idversion=".$_GET["idv"];
      mysqli_query($sql) or die(mysqli_error());

      $sql="delete from materiaversion where idversion=".$_GET["idv"];
      mysqli_query($sql) or die(mysqli_error());

      $sql="delete from presupuestoingresos where idversion=".$_GET["idv"];
      mysqli_query($sql) or die(mysqli_error());

      $sql="delete from presupuestogastos where idversion=".$_GET["idv"];
      mysqli_query($sql) or die(mysqli_error());

      $sql="delete from ejecucioningresos where idversion=".$_GET["idv"];
      mysqli_query($sql) or die(mysqli_error());

      $sql="delete from ejecuciongastos where idversion=".$_GET["idv"];
      mysqli_query($sql) or die(mysqli_error());

      $sql="delete from checklist where idversion=".$_GET["idv"];
      mysqli_query($sql) or die(mysqli_error());

      header("Location: versiones.php?idp=".$_GET["idp"]);
      exit;

}

if (isset($_GET["mode"]) && $_GET["mode"]=='a' && $editar) {

      $sql="update version set activo=if(activo=1,0,1) where idversion=".$_GET["idv"];
      mysqli_query($sql) or die(mysqli_error());

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
                            <td class="textsubmenu"><img src="images/ok.gif" /></td><td class="textsubmenu"><a href="programas.php" class="submenu">Retornar a programas</a></td>
                        </tr>
                        <?php
                            if ($insertar) {
                        ?>
                        <tr>
                            <td class="textsubmenu"><img src="images/ok.gif" /></td><td class="textsubmenu"><a href="nueva_version.php?idp=<?php print $_GET["idp"];?>" class="submenu">Nueva versi&oacute;n</a></td>
                        </tr>
                        <?php
                        }
                        ?>
        </table>

        <br><br>
    </td>
    <td width="800">
    <table align="center" width="100%" cellspacing='2'>
        <tr>
            <td class="bordes">
            <table class='contenido' width='100%'>
                <tr>
                       <td class='titproy' colspan='6' align='center'><font size='4'>PLANIFICACI&Oacute;N DE VERSIONES</font></td>
                   </tr><tr>
                    <td colspan='6' class='tititems' >
<?php
                    $sql="select * from programa where idprograma=".$_GET["idp"];
                    $res=mysqli_query($sql);
                    $fila=mysqli_fetch_array($res);
                    print "Porgrama: ".$fila[1]." - ".$fila[2];
?>

                    </td>
                </tr>

                <tr>
                    <td class='tititems' width='8%'>Gesti&oacute;n</td>
                    <td class='tititems' width='20%'>Ciudad</td>
                    <td class='tititems' width='35%'>Responsable</td>
                    <td class='tititems' width='15%'>Inicio Prog.</td>
                    <td class='tititems' width='8%'>Activo</td>
                    <td class='tititems' width='15%'>Acciones</td>
                </tr>
                <?php
                    $col1="#dedede";
                    $col2="#efefef";
                    $sql="select v.*, rh.Apellidos, rh.Nombres, c.nombre, p.idescuela, p.idtipoprograma
                                 from version v inner join recursohumano rh on rh.idrecursohumano=v.idrecursohumano
                                 inner join ciudad c on c.idciudad=v.ciudad
                                 inner join programa p on p.idprograma=v.idprograma
                                 where v.idprograma=".$_GET["idp"]." order by gestion";

                    $res=mysqli_query($sql);
                    $colactual="";
                    while ($fila=mysqli_fetch_array($res)) {
                       $nacceso=acceso($_SESSION['idRol'], $fila["idVersion"],$fila["idescuela"],$fila["idtipoprograma"],$fila["Ciudad"],12,0);
                        $eliminar=($nacceso==3 || $nacceso==6 || $nacceso==99);
                        $editar=$eliminar;
                        $insertar=($nacceso>=4 || $nacceso==99);
                        $leer=($nacceso==2 || $nacceso==3 || $nacceso==5 || $nacceso==6 || $nacceso==99);
                       if ($nacceso>=2) {
                          if ($colactual!=$col1) {
                             $colactual=$col1;
                          } else {
                             $colactual=$col2;
                          }
                          print "
                <tr bgcolor='$colactual'>
                    <td class='tabladettxt'>".$fila["Gestion"]."</td>
                    <td class='tabladettxt'>".$fila["nombre"]."</td>
                    <td class='tabladettxt'>".$fila["Apellidos"].", ".$fila["Nombres"]."</td>
                    <td class='tabladettxt'>".fecha($fila["InicioProgramado"],8)."</td>
                    <td class='tabladettxt' align='center'>";
                    if ($editar) {
                       print "<a href='versiones.php?mode=a&idv=".$fila[0]."&idp=".$_GET["idp"]."'><img border='0' src='images/".($fila[14]==1 ? "si": "no").".png'></a>";
                    }
                    print "</td>
                    <td class='tabladettxt'>";
                    if ($editar) {
                       print "<a href='nueva_version.php?mode=e&idv=".$fila[0]."&idp=".$_GET["idp"]."'><img src=\"images/editar.png\" alt=\"Editar\" title=\"Editar\" width=\"20\" border=\"0\" /></a>";
                       print " <a href='duplicar_version.php?mode=e&idv=".$fila[0]."&idp=".$_GET["idp"]."'><img src=\"images/duplicar.png\" alt=\"Duplicar\" title=\"Duplicar\" width=\"20\" border=\"0\" /></a> ";
                    }
                    if (acceso($_SESSION['idRol'], $fila["idVersion"],0,0,0,13,0)>=2) {
                       print "<a href='version.php?idv=".$fila[0]."'><img src=\"images/seguimiento.png\" alt=\"Detalles\" title=\"Detalles\" width=\"20\" border=\"0\" /></a>";
                    }
                    if ($eliminar) {
                       print " <a onclick='if(confirm(\"Esta seguro que desea eliminar esta versión?\\n Se eliminaran incluso los datos relacionados\")) { return true;} else { return false; }' href='versiones.php?mode=d&idv=".$fila[0]."&idp=".$_GET["idp"]."'><img src=\"images/delete.png\" alt=\"Eliminar\" title=\"Eliminar\" width=\"20\" border=\"0\" /></a>";
                    }
                    print "

                    </td>
                </tr>";
                    }
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