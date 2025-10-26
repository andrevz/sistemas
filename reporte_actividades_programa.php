<?php

include("valida.php");

$actividad=51;
include("niveles_acceso.php");


include("encabezado.php");

?>

<table align="center" bgcolor="#ffffff" width="100%">
<tr>
    <td width="200" valign="top">
        <table align="center" width="100%">
                        <tr>
                            <td class="textsubmenu"><img src="images/bc_parent.png" /></td><td class="textsubmenu"><a href="reportes.php" class="submenu">Retornar al men&uacute; anterior</a></td>
                        </tr>
        </table>

        <br><br>
    </td>
    <td width="800">
    <table align="center" width="100%" cellspacing='2'>
        <tr>
            <td class="bordes">
            <table class="contenido" width="100%">
                <tr>
                    <td colspan="2" class="titmenu">
                        ELIJA PAR&Aacute;METROS
                    </td>
                </tr>
                <tr>
                    <td>
                    <form method="POST" name="actualizar">
                      <table align="center">
                        <tr valign="baseline">
                          <td  class="tititems" align="right">Actividad 1:</td>
                          <td>
                          <select name='actividad1'>
                          <option value="-1"> [ Elija una actividad ] </option>
                          <?php
                              $sql="select * from actividades where version=1 order by orden";
                              $res=mysqli_query($sql);
                              while ($fila=mysqli_fetch_array($res)) {
                                 if (acceso($_SESSION['idRol'], 0,$fila[0],0,0,11,0)>=2) {
                                    print "<option ";
                                    if (isset($_GET["mode"]) && $_GET["mode"]=="e" && $fila_e[4]==$fila[0]) { print "selected"; }
                                    print " value='$fila[0]'>$fila[5] - $fila[1]</option>";
                                 }
                              }
                          ?>
                          </select>
                          </td>
                        </tr>
                        <tr valign="baseline">
                          <td  class="tititems" align="right">Actividad 2:</td>
                          <td>
                          <select name='actividad2'>
                          <option value="-1"> [ Actividad opcional ] </option>
                          <?php
                              $sql="select * from actividades where version=1 order by orden";
                              $res=mysqli_query($sql);
                              while ($fila=mysqli_fetch_array($res)) {
                                 if (acceso($_SESSION['idRol'], 0,$fila[0],0,0,11,0)>=2) {
                                    print "<option ";
                                    if (isset($_GET["mode"]) && $_GET["mode"]=="e" && $fila_e[4]==$fila[0]) { print "selected"; }
                                    print " value='$fila[0]'>$fila[5] - $fila[1]</option>";
                                 }
                              }
                          ?>
                          </select>
                          </td>
                        </tr>
                        <tr valign="baseline">
                          <td  class="tititems" align="right">Actividad 3:</td>
                          <td>
                          <select name='actividad3'>
                          <option value="-1"> [ Actividad opcional ] </option>
                          <?php
                              $sql="select * from actividades where version=1 order by orden";
                              $res=mysqli_query($sql);
                              while ($fila=mysqli_fetch_array($res)) {
                                 if (acceso($_SESSION['idRol'], 0,$fila[0],0,0,11,0)>=2) {
                                    print "<option ";
                                    if (isset($_GET["mode"]) && $_GET["mode"]=="e" && $fila_e[4]==$fila[0]) { print "selected"; }
                                    print " value='$fila[0]'>$fila[5] - $fila[1]</option>";
                                 }
                              }
                          ?>
                          </select>
                          </td>
                        </tr>
                        <tr valign="baseline">
                          <td colspan="2" align="center">
                              <input name='buscar' class="boton1" type="submit" value="Ver reporte"> </td>
                        </tr>
                      </table>
                    </form>
        </td>
        </tr>

        </table>
    </td>
    </tr>
    </table>
<?php
if (isset($_POST["buscar"])) {
$color1="#ffffff";
$color2="#dddddd";
?>
    <table align="center" width="100%" cellspacing='2'>
        <tr>
            <td class="bordes">
            <table class="contenido" width="100%">
                <tr>
                    <td>
                      <table align="center">
                        <tr valign="baseline">
                          <td class="tititems" align="right">Escuela</td>
                          <td class="tititems" align="right">Programa</td>
                          <td class="tititems" align="right">Gesti&oacute;n</td>
                          <td class="tititems" align="right">Ciudad</td>

<?php
$cadena="";

$sql="SELECT distinct e.sigla, p.nombre, v.gestion, c.nombre ";

if ($_POST["actividad1"]!="-1") {
   $sql_a="select idactividades, nombre from actividades where idactividades=".$_POST["actividad1"];
   $res_a=mysqli_query($sql_a);
   $fila_a=mysqli_fetch_array($res_a);

   $sql.=", if(a.idactividades=".$_POST["actividad1"]." and ejecutado=1, 'X','') as '1000".$fila_a[0]."'";
   $cadena.="<td class='tititems' align='center'>$fila_a[1]</td>";
}
if ($_POST["actividad2"]!="-1") {
   $sql_a="select idactividades, nombre from actividades where idactividades=".$_POST["actividad2"];
   $res_a=mysqli_query($sql_a);
   $fila_a=mysqli_fetch_array($res_a);

   $sql.=", if(a.idactividades=".$_POST["actividad2"]." and ejecutado=1, 'X','') as '1000".$fila_a[0]."'";
   $cadena.="<td class='tititems' align='center'>$fila_a[1]</td>";
}
if ($_POST["actividad3"]!="-1") {
   $sql_a="select idactividades, nombre from actividades where idactividades=".$_POST["actividad3"];
   $res_a=mysqli_query($sql_a);
   $fila_a=mysqli_fetch_array($res_a);

   $sql.=", if(a.idactividades=".$_POST["actividad3"]." and ejecutado=1, 'X','') as '1000".$fila_a[0]."'";
   $cadena.="<td class='tititems' align='center'>$fila_a[1]</td>";
}
$sql.=" FROM actividades a inner join checklist ch on ch.idactividades=a.idactividades
inner join version v on v.idversion=ch.idversion
inner join ciudad c on c.idciudad=v.ciudad
inner join programa p on p.idprograma=v.idprograma
inner join escuela e on e.idescuela=p.idescuela
where version=1 and v.activo=1
order by e.idescuela, p.nombre, v.ciudad, v.gestion";
print $cadena."</tr>";

$res=mysqli_query($sql);
$i=1;
while ($fila=mysqli_fetch_array($res)) {
      if ($i==1) { $i=2; $color=$color1; }
      else if ($i==2) { $i=1; $color=$color2; }
      print "<tr><td class='tabladettxt' bgcolor='$color'>$fila[0]</td><td class='tabladettxt' bgcolor='$color'>$fila[1]</td><td class='tabladettxt' bgcolor='$color'>$fila[2]</td><td class='tabladettxt' bgcolor='$color'>$fila[3]</td>";
if ($_POST["actividad1"]!="-1") {
    print "<td class='tabladettxt' align='center' bgcolor='$color'>".$fila["1000".$_POST["actividad1"]]."</td>";
}
if ($_POST["actividad2"]!="-1") {
    print "<td class='tabladettxt' align='center' bgcolor='$color'>".$fila["1000".$_POST["actividad2"]]."</td>";
}
if ($_POST["actividad3"]!="-1") {
    print "<td class='tabladettxt' align='center' bgcolor='$color'>".$fila["1000".$_POST["actividad3"]]."</td>";
}
}
?>

                      </table>
        </td>
        </tr>

        </table>
        <?php
        }
        ?>
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