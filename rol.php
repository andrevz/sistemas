<?php
include("valida.php");

$actividad=9;
include("niveles_acceso.php");

if (isset($_POST["agregar"]) && $insertar) {
// Añade módulo a rol
      $sql_insertamodulo="insert into rolesmodulos values (null, '".$_POST["id_rol"]."', '".$_POST["id_modulo"]."')";
      $result_inscliente=mysql_query($sql_insertamodulo);
      header("Location: rol.php?id_rol=".$_POST["id_rol"]);
      exit;
} else if (isset($_POST["Eliminara"]) && $eliminar) {
      $sql_insertamodulo="delete a.* from rolesactividades a inner join actividad c on a.idactividad=c.idactividad where idrol='".$_POST["id_rol"]."' and c.idmodulo='".$_POST["id_modulo"]."'";
      $result_inscliente=mysql_query($sql_insertamodulo);
      $sql_insertamodulo="delete from rolesmodulos where idrol='".$_POST["id_rol"]."' and idmodulo='".$_POST["id_modulo"]."'";
      $result_inscliente=mysql_query($sql_insertamodulo);
      header("Location: rol.php?id_rol=".$_POST["id_rol"]);
      exit;
} else  if (isset($_POST["descripcion"]) and $_POST["descripcion"]!="" && $insertar) {
      $sql_insert = "insert into rol (nombre) values ('".$_POST["descripcion"]."')";
      $result = mysql_query($sql_insert);
      header("Location: rol.php");
      exit;
} else if (isset($_POST["copiar"]) && $_POST["nuevo"]!="" && $insertar) {
      $sql_insert = "insert into rol (nombre) values ('".$_POST["nuevo"]."')";
      $result = mysql_query($sql_insert);
      $id_rol=mysql_insert_id();


      $sql_eliminarol="insert into rolesmodulos select null, $id_rol, idmodulo from rolesmodulos where idrol='".$_POST["id_rol"]."'";
      $result=mysql_query($sql_eliminarol);
      $sql_eliminarol="insert into rolesactividades select null, $id_rol, idactividad, nivelacceso from rolesactividades where idrol='".$_POST["id_rol"]."'";
      $result=mysql_query($sql_eliminarol);
      $sql_eliminarol="insert into rolciudad select null, $id_rol, idciudad, idnivel from rolciudad where idrol='".$_POST["id_rol"]."'";
      $result=mysql_query($sql_eliminarol);
      $sql_eliminarol="insert into rolescuela select null, $id_rol, idescuela, idnivel from rolescuela where idrol='".$_POST["id_rol"]."'";
      $result=mysql_query($sql_eliminarol);
      $sql_eliminarol="insert into roltipoprograma select null, $id_rol, idtipoprograma, idnivel from roltipoprograma where idrol='".$_POST["id_rol"]."'";
      $result=mysql_query($sql_eliminarol);
      $sql_eliminarol="insert into rolversion select null, $id_rol, idversion, idnivel from rolversion where idrol='".$_POST["id_rol"]."'";
      $result=mysql_query($sql_eliminarol);


      header("Location: rol.php");
      exit;
  
}

if (isset($_POST["eliminar"]) && $eliminar) {
   if ($_POST["id_rol"]>2) {
      $sql_eliminarol="update recursohumano set idrol=2 where idrol='".$_POST["id_rol"]."'";
      $result=mysql_query($sql_eliminarol);
      $sql_eliminarol="delete from rolesmodulos where idrol='".$_POST["id_rol"]."'";
      $result=mysql_query($sql_eliminarol);
      $sql_eliminarol="delete from rolesactividades where idrol='".$_POST["id_rol"]."'";
      $result=mysql_query($sql_eliminarol);
      $sql_eliminarol="delete from rolciudad where idrol='".$_POST["id_rol"]."'";
      $result=mysql_query($sql_eliminarol);
      $sql_eliminarol="delete from rolescuela where idrol='".$_POST["id_rol"]."'";
      $result=mysql_query($sql_eliminarol);
      $sql_eliminarol="delete from roltipoprograma where idrol='".$_POST["id_rol"]."'";
      $result=mysql_query($sql_eliminarol);
      $sql_eliminarol="delete from rolversion where idrol='".$_POST["id_rol"]."'";
      $result=mysql_query($sql_eliminarol);
      $sql_eliminarol="delete from rol where idrol='".$_POST["id_rol"]."'";
      $result=mysql_query($sql_eliminarol);
   }
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
<table class='contenido' width='100%'>
                <tr><td>
       <?php
     if ($insertar) {
     ?>
       <form method='post' name="rol">
      <table width="70%" cellpadding="5" cellspacing="5" align='center'>
              <tr>
                <td width="50">&nbsp;</td>
                <td class="content">Nuevo Rol:</td>
                <td width="350" class="content2"><input type='text' name='descripcion' size="30" onFocus="nextfield ='guardar';"> <input type="submit" value="Guardar" name='guardar'></td>
              </tr>
            </table>
   </form>
  <?php
  }
  ?>
      <table width="70%" cellpadding="5" cellspacing="5" align='center'>
   <form method='post' name="busca_rol">
              <tr>
                <td width="50">&nbsp;</td>
                <td class="content">Rol:</td>
                <td width="350" class="content2">
                <select name='id_rol' onfocus="nextfield ='guardar'; nomform='busca_rol'" onchange="submit();">
                <?php
                    $sql_roles = "select * from rol order by nombre";
                    $result = mysql_query($sql_roles);
                    while ($fila=mysql_fetch_array($result)) {
                          print "<option ";
                          if ($fila["idRol"]==$rol) {
                             print " selected ";
                          }
                          print "value='".$fila["idRol"]."'>".$fila["Nombre"]."</option>";
                    }
                ?>
                </select>
                <!--<input type="submit" value="Buscar" name='guardar'>-->
                <?php
                if ($eliminar) {
                ?>
                <input type='submit' value='Eliminar' name='eliminar' onclick="if (confirm('Esta seguro que desea eliminar este rol?')) { return true; } else { return false;}">
                <?php
                }
                ?>
                </td>
              </tr>
              <tr>
                <td width="50">&nbsp;</td>
                <td class="content">Copiar rol a:</td>
                <td width="350" class="content2">
                <input type='text' name='nuevo'>

                <?php
                if ($insertar) {
                ?>
                <input type='submit' value='Copiar' name='copiar'>
                <?php
                }
                ?>
                </td>
              </tr>
   </form>
<?php
                    print "<tr><td></td><td colspan=3 class='content2'>";
                    $sql_modulos = "select m.idmodulo, nombre from modulo m inner join rolesmodulos r on r.idmodulo=m.idmodulo where r.idrol=".$rol;
                    $result = mysql_query($sql_modulos);
                    print "<table width='100%' border=0 cellspacing=1 cellpadding=1><tr><td class='content'><b>M&oacute;dulo</b></td><td class='content'><b>Acci&oacute;n</b></td></tr>";
                    while ($fila=mysql_fetch_array($result)) {
                          print "<form name='elimina_modulo' method='post'><tr><td>".$fila["nombre"]."</td><td align='center'><input type='hidden' name='id_rol' value='".$rol."'><input type='hidden' name='id_modulo' value='".$fila["idmodulo"]."'>";
                          if ($eliminar) {
                             print "<input type='submit' name='Eliminara' value='X' class='button'>";
                          }
                          print "</td></tr></form>";
                    }
                    print "</table></td></tr>";
                    if ($insertar) {
                    print "<form name='anadir_modulo' method='post'><tr><td></td><td colspan=2>
                    <input type='hidden' name='id_rol' value='".$rol."'><select name='id_modulo'>";
                    $sql_modulos = "select m.idmodulo, m.nombre, r.idrol from modulo m left join rolesmodulos r on r.idmodulo=m.idmodulo and r.idrol=".$rol;
                    $result = mysql_query($sql_modulos);
                    while ($fila1=mysql_fetch_array($result)) {
                          if (is_null($fila1["idrol"]) or $fila1["idrol"]=="") {
                            print "<option value='".$fila1["idmodulo"]."'>".$fila1["nombre"]."</option>";
                          }
                    }
                ?>
                </select> <input type="submit" value="A&ntilde;adir" name='agregar'>
                </td>
              </tr></form>
              <?php
              }
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