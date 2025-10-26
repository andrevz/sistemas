<?php

include("valida.php");
if (acceso($_SESSION['idRol'], 0,0,0,0,0,3)<=1) {
   header("Location: menu.php");
   exit;
}

$actividad=20;
include("niveles_acceso.php");

/*if (isset($_GET["mode"]) && $_GET["mode"]=='d' && $eliminar) {

      $sql="delete from version where idversion=".$_GET["idv"];
      mysqli_query($sql) or die(mysqli_error());

      header("Location: versiones.php?idp=".$_GET["idp"]);
      exit;

}

if (isset($_GET["mode"]) && $_GET["mode"]=='a' ) {

      $sql="update version set activo=if(activo=1,0,1) where idversion=".$_GET["idv"];
      mysqli_query($sql) or die(mysqli_error());

      header("Location: versiones.php?idp=".$_GET["idp"]);
      exit;

} */


include("encabezado.php");

?>

<table align="center" bgcolor="#ffffff" width="100%">
<tr>
    <td width="200" valign="top">
        <table align="center" width="100%">
                        <tr>
                            <td class="textsubmenu"><img src="images/bc_parent.png" /></td><td class="textsubmenu"><a href="menu.php" class="submenu">Retornar al men&uacute; principal</a></td>
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
                       <td class='titproy' colspan='6' align='center'><font size='4'>EJECUCI&Oacute;N DE VERSIONES</font></td>
                   </tr>
                <?php
                    $col1="#dedede";
                    $col2="#efefef";

                    $sql="select distinct p.idtipoprograma, p.nombre
                                 from tipoprograma p inner join programa pr on pr.idtipoprograma=p.idtipoprograma
                                 inner join version v on v.idprograma=pr.idprograma where v.activo=1";
                    $res1=mysqli_query($sql);
                    $colactual="";
                    while ($fila1=mysqli_fetch_array($res1)) {
                      if (acceso($_SESSION['idRol'], 0,0,$fila1[0],0,20,0)>=2) {
                              print "<tr>
                              <td class='tititems' width='45%'>Programa: ".mb_strtoupper($fila1[1])."</td>
                              <td class='tititems' width='7%'>Gesti&oacute;n</td>
                              <td class='tititems' width='10%'>Ciudad</td>
                              <td class='tititems' width='20%'>Responsable</td>
                              <td class='tititems' width='12%'>Inicio Prog.</td>
                              <td class='tititems' width='7%'>Acciones</td>

                               </tr>";

                              $sql="select idversion, gestion, c.nombre, inicioprogramado, nombres, apellidos, p.nombre, p.idprograma, v.Ciudad , p.idescuela, p.idtipoprograma
                                       from version v inner join recursohumano rh on rh.idrecursohumano=v.idrecursohumano
                                       inner join programa p on p.idprograma=v.idprograma inner join ciudad c on c.idciudad=v.ciudad where activo=1 and p.idtipoprograma=$fila1[0] order by p.nombre, gestion";
                              $res=mysqli_query($sql);
                              $colactual="";
                              while ($fila=mysqli_fetch_array($res)) {
                                  if (acceso($_SESSION['idRol'], $fila["idversion"],$fila["idescuela"],$fila["idtipoprograma"],$fila["Ciudad"],20,0)>=2) {
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
                                         <td class='tabladettxt'>";
                                         if (acceso($_SESSION['idRol'], $fila[0], 0,0,0,21,0)>=2) {
                                            print "<a href='version_s.php?idv=".$fila[0]."'><img src=\"images/seguimiento.png\" alt=\"Seguimiento\" title=\"Seguimiento\" width=\"20\" border=\"0\" /></a>";
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