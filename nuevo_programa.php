<?php

include("valida.php");

$actividad=11;
include("niveles_acceso.php");

if (isset($_POST["MM_insert"]) && $insertar) {
    if ($_POST["nombre"]=="" or is_null($_POST["nombre"])) {
       $error="<font color='red'><b>Debe registrarse obligatoriamente el nombre</b></font>";
    } else {

      $sql="insert into programa values (null, '".htmlspecialchars($_POST["nombre"])."', '".$_POST["sigla"]."', '".$_POST["tipop"]."', '".$_POST["escuela"]."'
                   )";
      mysql_query($sql) or die(mysql_error());


      header("Location: programas.php");
      exit;
   }
} else if (isset($_POST["MM_edit"]) && $editar) {
    if ($_POST["nombre"]=="" or is_null($_POST["nombre"])) {
       $error="<font color='red'><b>Debe registrarse obligatoriamente el nombre</b></font>";
    } else {

      $sql="update programa set nombre='".htmlspecialchars($_POST["nombre"])."', Sigla='".$_POST["sigla"]."', idtipoprograma='".$_POST["tipop"]."', idescuela='".$_POST["escuela"]."' where idprograma=".$_GET["idp"];
      mysql_query($sql) or die(mysql_error());


      header("Location: programas.php");
      exit;
   }
} else if (isset($_GET["mode"]) && $_GET["mode"]=="e") {
  $sql="select * from programa where idprograma=".$_GET["idp"];
  $res=mysql_query($sql);
  $fila_e=mysql_fetch_array($res);
}

include("encabezado.php");

?>

<table align="center" bgcolor="#ffffff" width="100%">
<tr>
    <td width="200" valign="top">
        <table align="center" width="100%">
                        <tr>
                            <td class="textsubmenu"><img src="images/bc_parent.png" /></td><td class="textsubmenu"><a href="programas.php" class="submenu">Retornar al men&uacute; anterior</a></td>
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
                        NUEVO PROGRAMA
                    </td>
                </tr>
                <tr>
                    <td>
                    <form method="POST" name="actualizar">
                      <table align="center">
                        <tr valign="baseline">
                          <td  class="tititems" align="right">Nombre del programa:</td>
                          <td><input type="text" name="nombre" size="60" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print $fila_e[1]; } ?>" class='boton1'></td>
                        </tr>
                        <tr valign="baseline">
                          <td  class="tititems" align="right">Sigla:</td>
                          <td><input  class='boton1' type="text" name="sigla" size="10" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print $fila_e[2]; } ?>" id="sigla"></td>
                        </tr>
                        <tr valign="baseline">
                          <td  class="tititems" align="right">Tipo de programa:</td>
                          <td>
                          <select name='tipop' id='tipop'>
                          <?php
                              $sql="select * from tipoprograma";
                              $res=mysql_query($sql);
                              while ($fila=mysql_fetch_array($res)) {
                                 if (acceso($_SESSION['idRol'], 0,0,$fila[0],0,11,0)>=2) {
                                    print "<option ";
                                    if (isset($_GET["mode"]) && $_GET["mode"]=="e" && $fila_e[3]==$fila[0]) { print "selected"; }
                                    print " value='$fila[0]'>$fila[1]</option>";
                                 }
                              }
                          ?>
                          </select>
                          </td>
                        </tr>
                        <tr valign="baseline">
                          <td  class="tititems" align="right">Area:</td>
                          <td>
                          <select name='escuela' id='escuela'>
                          <?php
                              $sql="select * from escuela";
                              $res=mysql_query($sql);
                              while ($fila=mysql_fetch_array($res)) {
                                 if (acceso($_SESSION['idRol'], 0,$fila[0],0,0,11,0)>=2) {
                                    print "<option ";
                                    if (isset($_GET["mode"]) && $_GET["mode"]=="e" && $fila_e[4]==$fila[0]) { print "selected"; }
                                    print " value='$fila[0]'>$fila[1]</option>";
                                 }
                              }
                          ?>
                          </select>
                          </td>
                        </tr>
                        <tr valign="baseline">
                          <td colspan="2" align="center"><input class="boton1" type="submit" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print "Actualizar"; } else { print "Crear"; } ?> programa"> <input class="boton1" name="Cancelar" type="button" onClick="window.location='programas.php'" value='Cancelar'/></td>
                        </tr>
                      </table>
                      <input type="hidden" name="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print "MM_edit"; } else { print "MM_insert"; } ?>" value="actualizar">
                      <?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print "<input type='hidden' name='idp' value='".$_GET["idp"]."'>"; } ?>
                    </form>
<?php
    if (isset($error)) {
       print $error;
    }
?>

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