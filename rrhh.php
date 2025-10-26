<?php

include("valida.php");

$actividad=1;
include("niveles_acceso.php");

if (isset($_GET["mode"]) && $_GET["mode"]=='d' && $eliminar) {

      $sql="delete from recursohumano where idrecursohumano=".$_GET["id"];
      mysql_query($sql) or die(mysql_error());

      header("Location: rrhh.php");
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
                        <?php
                            if ($insertar) {
                        ?>
                        <tr>
                            <td class="textsubmenu"><img src="images/ok.gif" /></td><td class="textsubmenu"><a href="rrhh_nuevos.php" class="submenu">Nuevo Recurso</a></td>
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
                <?php
                    if (!isset($_GET["pag"])) {
                        $_GET["pag"]="A";
                    }
                    $sql_letras="SELECT distinct left(ucase(trim(apellidos)),1) FROM recursohumano order by left(ucase(trim(apellidos)),1)";
                    $res_letras=mysql_query($sql_letras);
                    $cadena_letras="";
                    while ($fila_letras=mysql_fetch_array($res_letras)) {
                          if ($_GET["pag"]==$fila_letras[0]) {
                              $cadena_letras.=$fila_letras[0]." ";
                          } else {
                              $cadena_letras.="<a href='rrhh.php?pag=".$fila_letras[0]."'>".$fila_letras[0]."</a> ";
                          }
                    }
                ?>
                    <td colspan='5'>P&aacute;ginas: <?php print $cadena_letras; ?></td>
                </tr>
                <tr>
                    <td class='tititems' width='10%'>C&oacute;digo</td>
                    <td class='tititems' width='48%'>Nombre</td>
                    <td class='tititems' width='20%'>Tel&eacute;fono /<br>Celular</td>
                    <td class='tititems' width='10%'>Doc. Identidad</td>
                    <td class='tititems'>Acciones</td>
                </tr>
                <?php
                    $col1="#eeeeee";
                    $col2="#ffffff";
                    $sql="select * from recursohumano where left(ucase(trim(apellidos)),1) like '".$_GET["pag"]."' order by ucase(trim(apellidos)),ucase(trim(nombres))";
                    $res=mysql_query($sql);
                    $colactual="";
                    while ($fila=mysql_fetch_array($res)) {
                          $sql1="select count(*) from ejecucionmateriaversiondocente where idrecursohumano=$fila[0]";
                          $res1=mysql_query($sql1);
                          $fila1=mysql_fetch_array($res1);
                          $sql1="select count(*) from planmateriaversiondocente where idrecursohumano=$fila[0]";
                          $res1=mysql_query($sql1);
                          $fila2=mysql_fetch_array($res1);
                          $sql1="select count(*) from version where idrecursohumano=$fila[0]";
                          $res1=mysql_query($sql1);
                          $fila3=mysql_fetch_array($res1);
                          if ($colactual!=$col1) {
                             $colactual=$col1;
                          } else {
                             $colactual=$col2;
                          }
                          print "
                <tr bgcolor='$colactual'>
                    <td class='tabladetnum'>".$fila[16]."</td>
                    <td class='tabladettxt'>".$fila[3].", ".$fila[2]."</td>
                    <td class='tabladetnum'>".str_replace("-"," ",$fila[4])." ".str_replace("-"," ",$fila[5])."</td>
                    <td  class='tabladetnum'>".$fila[1]."</td>
                    <td class='tabladettxt'>";
                    if ($editar) {
                       print "<a href='rrhh_nuevos.php?mode=e&id=".$fila[0]."'><img border='0' src='images/editar.png' width='20' alt='Editar' title='Editar'></a> ";
                    }
                    if ($fila1[0]<1 && $fila2[0]<1 && $fila3[0]<1 && ($eliminar)) {
                        print "<a onclick='if(confirm(\"Esta seguro que desea eliminar a ".$fila[3].", ".$fila[2]."?\")) { return true;} else { return false; }' href='rrhh.php?mode=d&id=".$fila[0]."'><img border='0' src='images/delete.png' width='20' alt='Eliminar' title='Eliminar'></a>";
                    }
                    print " <a onclick=\"window.open('documentos.php?idrh=".$fila[0]."&t=popup&act=1', '_blank', 'status=0,menubar=0,location=0,scrollbars=0,resizable=0,width=700,height=250', 0);\" href='#'><img src=\"images/documentos.png\" alt=\"Documentos\" title=\"Documentos\" width=\"20\" border=\"0\" /></a>";

                    print "</td>
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
</td>
</tr>

</table>
</td></tr></table>
<?php
include("pie.php");
?>
</body>
</html>