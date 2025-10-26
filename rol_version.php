<?php
include("valida.php");

$actividad=8;
include("niveles_acceso.php");

if (isset($_POST["id_escuela"]) && ($editar || $insertar)) {
      $sql_buscaactividad="select * from rolversion where idrol=".$_POST["id_rol"]." and idversion=".$_POST["id_escuela"];
      $res_ba=mysqli_query($sql_buscaactividad);
      if ($fila_ba=mysqli_fetch_array($res_ba)) {
         if ($_POST["nivelacceso"]=="-1") {
             $sql_up="delete from rolversion where idrolversion=".$fila_ba[0];
         } else {
             $sql_up="update rolversion set idnivel=".$_POST["nivelacceso"]." where idrolversion=".$fila_ba[0];
         }
      } else {
         $sql_up="insert into rolversion values (null, '".$_POST["id_rol"]."', '".$_POST["id_escuela"]."', '".$_POST["nivelacceso"]."')";
      }
      $result=mysqli_query($sql_up);
}

   if (isset($_POST["id_rol"])) {
      $rol=$_POST["id_rol"];
   } else if (isset($_GET["id_rol"])) {
      $rol=$_GET["id_rol"];
   } else {
      $rol=1;
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

                  <table width="100%" cellpadding="5" cellspacing="5" align='center' class='contenido'>
   <form method='post' name="busca_rol">
              <tr>
                <td width="10">&nbsp;</td>
                <td class="content" align='right'>Rol:</td>
                <td width="350" class="content2">
                <select name='id_rol' onfocus="nextfield ='guardar'; nomform='busca_rol'" onchange="submit();">
                <?php
                    $sql_roles = "select * from rol order by Nombre";
                    $result = mysqli_query($sql_roles);
                    while ($fila=mysqli_fetch_array($result)) {
                          print "<option ";
                          if ($fila["idRol"]==$rol) {
                             print " selected ";
                          }
                          print "value='".$fila["idRol"]."'>".$fila["Nombre"]."</option>";
                    }
                ?>
                </select>
                <!--<input type="submit" value="Buscar" name='guardar'>--></td>
              </tr>
   </form>
<?php
                    print "<tr><td></td><td colspan=3 class='content2'>";

                    $sql_modulos = "select v.idversion, concat(p.nombre, ' - ', v.gestion, ' - ', c.nombre) as nombre,
                                           ar.idrol, ar.idnivel, c.idciudad, p.idescuela, p.idtipoprograma
                                   from
                                       version v inner join
                                       ciudad c on v.ciudad=c.idciudad inner join
                                       programa p on p.idprograma=v.idprograma left join
                                       rolversion ar on ar.idversion=v.idversion
                                   and ar.idrol=".$rol." where activo=1 order by nombre";

                    $result = mysqli_query($sql_modulos);
                    print "<table width='100%' border=0 cellspacing=1 cellpadding=1>
                                  <tr>
                                      <td width='80%' class='content'><b>Versi&oacute;n</b></td>
                                      <td class='content'><b>Acceso</b></td></tr>";
                    $temp_modulo="";
                    while ($fila=mysqli_fetch_array($result)) {
                      if (acceso($rol, 0,$fila["idescuela"],$fila["idtipoprograma"],$fila["idciudad"])>=2 || $rol==1) {
                          print "<form name='asigna_actividad' method='post'><tr><td class='tablatxt'>&rarr; ".$fila["nombre"]."</td>
                          <td align='center'><input type='hidden' name='id_rol' value='".$rol."'><input type='hidden' name='id_escuela' value='".$fila["idversion"]."'>";
                          print "<select name='nivelacceso' onchange='submit();'><option value='-1'>No definido</option>";
                          $sql_accesos="select * from nivelesacceso";
                          $res_accesos=mysqli_query($sql_accesos);
                          while ($fila_accesos=mysqli_fetch_array($res_accesos)) {
                             print "<option value='$fila_accesos[0]'";
                             if ($fila[3]==$fila_accesos[0]) { print " selected ";}
                             print ">$fila_accesos[1]</option>";
                          }
                          print "</select>";
//                          if ($fila["idrol"]=="") {
//                              print "<input type='submit' name='Agregar' value='x' class='button'></td></td></td>";
/*                          } else {
                              print "</td><td align='center'><input type='submit' name='Eliminar' value='x' class='button'></td>";
                          }                     */
                          print "</tr></form>";
                       }
                    }
                    print "</table></td></tr></table>";
?>
</table>
    </td>
    </tr>

    </table>
    </td></tr></table>
    </td>
</tr>

</table>
</td></tr></table>
<?php
include("pie.php");
?>
</body>
</html>