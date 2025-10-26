<?php

include("valida.php");
if (acceso($_SESSION['idRol'], 0,0,0,0,0,2)<=1) {
   header("Location: menu.php");
   exit;
}
$actividad=11;
include("niveles_acceso.php");

if (isset($_GET["mode"]) && $_GET["mode"]=='d' && $eliminar) {

      $sql="delete from programa where idprograma=".$_GET["idp"];
      mysqli_query($sql) or die(mysqli_error());

      header("Location: programas.php");
      exit;

}

include("encabezado.php");

?>

<table align="center" bgcolor="#ffffff" width="100%">
<tr>
    <td width="200" valign="top">
        <table align="center" width="100%">
                        <tr>
                            <td class="textsubmenu"><img src="images/bc_parent.png" /></td><td class="textsubmenu"><a href="menu.php" class="submenu">Retornar al men&uacute; anterior</a></td>
                        </tr>
                        <?php
                        if ($insertar) {
                        ?>
                        <tr>
                            <td class="textsubmenu"><img src="images/ok.gif" /></td><td class="textsubmenu"><a href="nuevo_programa.php" class="submenu">Nuevo Programa</a></td>
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
                       <td class='titproy' colspan='6' align='center'><font size='4'>PLANIFICACI&Oacute;N</font></td>
                   </tr>
                <?php
                    $sql="select distinct p.idtipoprograma, p.nombre from tipoprograma p inner join programa pr on pr.idtipoprograma=p.idtipoprograma";
                    $res1=mysqli_query($sql);
                    $colactual="";
                    while ($fila1=mysqli_fetch_array($res1)) {

                      if (acceso($_SESSION['idRol'], 0,0,$fila1[0],0,11,0)>=2) {
                         print "<tr>
                          <td class='tititems' colspan='3'>Programa: ".mb_strtoupper($fila1[1])."</td>
                        </tr>";
                          ?>
                          <tr>
                              <td class='tititems' width='74%'>Nombre</td><td class='tititems' width='10%'>Sigla</td><td class='tititems' width='16%'>Acciones</td>
                          </tr>
                          <?php
                              $col1="#dedede";
                              $col2="#efefef";
                              $sql="select * from programa where idtipoprograma=$fila1[0] order by trim(nombre)";
                              $res=mysqli_query($sql);
                              $colactual="";
                              while ($fila=mysqli_fetch_array($res)) {
                                 if (acceso($_SESSION['idRol'], 0,$fila["idescuela"],0,0,12,0)>=2) {
                                    if ($colactual!=$col1) {
                                       $colactual=$col1;
                                    } else {
                                       $colactual=$col2;
                                    }
                                    print "
                                    <tr bgcolor='$colactual'>
                                    <td class='tabladettxt'>".$fila[1]."</td><td class='tabladettxt'>".$fila[2]."</td><td class='tabladettxt'>";
                                    if ($editar) {
                                       print "<a href='nuevo_programa.php?mode=e&idp=".$fila[0]."'><img src=\"images/editar.png\" alt=\"Editar\" title=\"Editar\" width=\"20\" border=\"0\" /></a>";
                                    }
                                    if (acceso($_SESSION['idRol'], 0,0,$fila1[0],0,12,0)>=2) {
                                       print "<a href='versiones.php?idp=".$fila[0]."'><img src=\"images/seguimiento.png\" alt=\"Versiones\" title=\"Versiones\" width=\"20\" border=\"0\" /></a>";
                                    }
                                    print "<!-- <a onclick=\"window.open('documentos.php?idr=".$fila[0]."&t=popup', '_blank', 'status=0,menubar=0,location=0,scrollbars=0,resizable=0,width=700,height=250', 0);\" href='#'><img src=\"images/documentos.png\" alt=\"Documentos\" title=\"Documentos\" width=\"20\" border=\"0\" /></a>-->";
                                    $sql_vers="select count(*) from version where idprograma=".$fila[0];
                                    $res_vers=mysqli_query($sql_vers);
                                    $fila_vers=mysqli_fetch_array($res_vers);
                                    if ($fila_vers[0]==0 && $eliminar) {
                                        print "<a onclick='if(confirm(\"Esta seguro que desea eliminar el programa ".$fila["Nombre"]."?\\n Se eliminaran incluso los datos relacionados\")) { return true;} else { return false; }' href='programas.php?mode=d&idp=".$fila[0]."'><img src=\"images/delete.png\" alt=\"Eliminar\" title=\"Eliminar\" width=\"20\" border=\"0\" /></a>";
                                    }
                                    print "

                                    </td>
                                    </tr>";
                                 }
                           }
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