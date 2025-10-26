<?php

include("valida.php");
$actividad=1;
include("niveles_acceso.php");

if (isset($_POST["MM_insert"]) && $insertar) {
    $sql_verifica="select * from recursohumano where login='".$_POST["ilogin"]."'";
    $res_verifica=mysqli_query($sql_verifica);
    if ($fila_verifica=mysqli_fetch_array($res_verifica) && $_POST["usuarioactivo"]==1) {

       $error="<font color='red'><b>El identificador de usuario ya existe</b></font>";

    } else if ($_POST["nombre"]=="" or is_null($_POST["nombre"]) or $_POST["apellido"]=="" or is_null($_POST["apellido"])) {
       $error="<font color='red'><b>Deben registrarse obligatoriamente el nombre y apellido</b></font>";
    } else {

      $sql="insert into recursohumano values (null, '".$_POST["ci"]."', '".$_POST["nombre"]."', '".$_POST["apellido"]."', '".$_POST["telefono"]."',
                   '".$_POST["celular"]."',  '".$_POST["direccion"]."', '".$_POST["ciudad"]."',  '".$_POST["email"]."', '".$_POST["ilogin"]."',
                   '".md5($_POST["ipassword"])."',  '".$_POST["activo"]."', '".$_POST["rol"]."',  '".$_POST["usuario"]."',  '".$_POST["factura"]."',
                   '".$_POST["nit"]."', '".$_POST["codigo_UPB"]."')";
      mysqli_query($sql) or die(mysqli_error());
      if (!(isset($_GET["t"]) && $_GET["t"]=="popup")) {

         header("Location: rrhh.php");
      } else {
         print "<script type='text/javascript' src='select_dependientes.js'></script>
         <script language='javascript'>cargaContenido(\"recursohumano\"); </script>
        ";
      }
      exit;
   }
} else if (isset($_POST["MM_edit"]) && $editar) {
    if ($_POST["nombre"]=="" or is_null($_POST["nombre"]) or $_POST["apellido"]=="" or is_null($_POST["apellido"])) {
       $error="<font color='red'><b>Deben registrarse obligatoriamente el nombre y apellido</b></font>";
    } else {

      $sql="update recursohumano set ci='".$_POST["ci"]."', nombres='".$_POST["nombre"]."', apellidos='".$_POST["apellido"]."', telefono='".$_POST["telefono"]."',
                   celular='".$_POST["celular"]."', direccion='".$_POST["direccion"]."',
                   login='".$_POST["ilogin"]."',  ".((strlen($_POST["ipassword"])>0) ? "contrasena='".md5($_POST["ipassword"])."'," : "")."
                   recursoactivo='".$_POST["activo"]."', email='".$_POST["email"]."', ciudad='".$_POST["ciudad"]."', idrol='".$_POST["rol"]."',
                   usuarioactivo='".$_POST["usuario"]."', factura='".$_POST["factura"]."', nit='".$_POST["nit"]."', codigo_UPB='".$_POST["codigo_UPB"]."'
                   where idrecursohumano=".$_POST["idrrhh"];
      mysqli_query($sql) or die(mysqli_error());


      header("Location: rrhh.php");
      exit;
   }
}
   if (!(isset($_GET["t"]) && $_GET["t"]=="popup")) {
      include("encabezado.php");
   } else {
   ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php print $nombresistema; ?></title>
<link href="<?php print $path; ?>estilos.css" rel="stylesheet" type="text/css" />
</html>
   <?php
   }

?>

<table align="center" bgcolor="#ffffff" width="100%">
<tr>
<?php

   if (!(isset($_GET["t"]) && $_GET["t"]=="popup")) {

   ?>
    <td width="200" valign="top">
        <table align="center" width="100%">
                        <tr>
                            <td class="textsubmenu"><img src="images/bc_parent.png" /></td><td class="textsubmenu"><a href="parametros.php" class="submenu">Retornar al men&uacute; principal</a></td>
                        </tr>
                        <tr>
                            <td class="textsubmenu"><img src="images/ok.gif" /></td><td class="textsubmenu"><a href="rrhh.php" class="submenu">Editar recursos humanos</a></td>
                        </tr>

        </table>

        <br><br>
    </td>
    <?php }
    ?>
    <td width="800">

            <table class="contenido" width="100%">
                <tr>
                    <td colspan="2" class="titmenu">
                        <?php if ($_GET["mode"]=='e') {
                                  print "EDITAR";
                                  $sqle="select * from recursohumano where idrecursohumano=".$_GET["id"];
                                  $rese=mysqli_query($sqle);
                                  $filae=mysqli_fetch_array($rese);
                              } else {
                                  print "NUEVO";
                              }
                              ?> RECURSO HUMANO <a name='nuevo'>
                    </td>
                </tr>
                <tr>
                    <td>
                    <form method="POST" name="actualizar">
                      <table align="center">
                        <tr valign="baseline">
                          <td  class="tititems" align="right">C&oacute;digo:</td>
                          <td><input type="text" name="codigo_UPB" size="5" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print $filae[16]; } ?>"></td>
                        </tr>
                        <tr valign="baseline">
                          <td  class="tititems" align="right">Nombres:</td>
                          <td><input type="text" name="nombre" size="60" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print $filae[2]; } ?>"></td>
                        </tr>
                        <tr valign="baseline">
                          <td  class="tititems" align="right">Apellidos:</td>
                          <td><input type="text" name="apellido" size="60" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print $filae[3]; } ?>"></td>
                        </tr>
                        <tr valign="baseline">
                          <td  class="tititems" align="right">C.I.:</td>
                          <td><input type="text" name="ci" size="10" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print $filae[1]; } ?>"></td>
                        </tr>
                        <tr valign="baseline">
                          <td class="tititems" align="right" valign="top">Tel&eacute;fono:</td>
                          <td><input type="text" name="telefono" size="12" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print $filae[4]; } ?>">
                          </td>
                        </tr>
                        <tr valign="baseline">
                          <td class="tititems" align="right" valign="top">Celular:</td>
                          <td><input type="text" name="celular" size="12" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print $filae[5]; } ?>">
                          </td>
                        </tr>
                        <tr valign="baseline">
                          <td class="tititems" align="right" valign="top">Direcci&oacute;n:</td>
                          <td><input type="text" name="direccion" size="60" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print $filae[6]; } ?>">
                          </td>
                        </tr>
                        <tr valign="baseline">
                          <td class="tititems" align="right" valign="top">Ciudad:</td>
                          <td><input type="text" name="ciudad" size="30" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print $filae[7]; } ?>">
                          </td>
                        </tr>
                        <tr valign="baseline">
                          <td class="tititems" align="right" valign="top">E-Mail:</td>
                          <td><input type="text" name="email" size="60" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print $filae[8]; } ?>">
                          </td>
                        </tr>
                        <tr valign="baseline">
                          <td class="tititems" align="right" valign="top">Factura?:</td>
                          <td><select name='factura'>
                                      <option value='1' <?php if (isset($_GET["mode"]) && $_GET["mode"]=="e" && $filae[14]==1) { print "selected"; } ?>>S&iacute;</option>
                                      <option value='0' <?php if (isset($_GET["mode"]) && $_GET["mode"]=="e" && $filae[14]==0) { print "selected"; } ?>>No</option>
                          </select>
                          </td>
                        </tr>
                        <tr valign="baseline">
                          <td class="tititems" align="right" valign="top">N.I.T.:</td>
                          <td><input type="text" name="nit" size="10" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print $filae[15]; } ?>">
                          </td>
                        </tr>
                        <tr valign="baseline">
                          <td class="tititems" align="right" valign="top">Estado:</td>
                          <td><select name='activo'>
                                      <option value='1' <?php if (isset($_GET["mode"]) && $_GET["mode"]=="e" && $filae[11]==1) { print "selected"; } ?>>Activo</option>
                                      <option value='0' <?php if (isset($_GET["mode"]) && $_GET["mode"]=="e" && $filae[11]==0) { print "selected"; } ?>>Inactivo</option>
                          </select>
                          </td>
                        </tr>
                        <tr valign="baseline">
                          <td class="tititems" align="right" valign="top">Usuario del sistema?:</td>
                          <td><select name='usuario' onchange="if (this.value=='1') { document.actualizar.ilogin.disabled=false; document.actualizar.ipassword.disabled=false; } else { document.actualizar.ilogin.value=''; document.actualizar.ilogin.disabled=true; document.actualizar.ipassword.value=''; document.actualizar.ipassword.disabled=true; }">
                                      <option value='1' <?php if (isset($_GET["mode"]) && $_GET["mode"]=="e" && $filae[13]==1) { print "selected"; } ?>>S&iacute;</option>
                                      <option value='0' <?php if ((isset($_GET["mode"]) && $_GET["mode"]=="e" && $filae[13]==0) || !isset($_GET["mode"])) { print "selected"; } ?>>No</option>
                          </select>
                          </td>
                        </tr>
                        <tr valign="baseline">
                          <td class="tititems" align="right" valign="top">Login:</td>
                          <td><input type="text" name="ilogin" <?php if ((isset($_GET["mode"]) && $_GET["mode"]=="e" && $filae[13]==0) || !isset($_GET["mode"])) { print "disabled='disabled'"; } ?> size="20" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print $filae[9]; } ?>">
                          </td>
                        </tr>
                        <tr valign="baseline">
                          <td class="tititems" align="right" valign="top">Contrase&ntilde;a:</td>
                          <td><input type="password" name="ipassword" <?php if ((isset($_GET["mode"]) && $_GET["mode"]=="e" && $filae[13]==0) || !isset($_GET["mode"])) { print "disabled='disabled'"; } ?> size="20" value="">
                          </td>
                        </tr>
                        <tr valign="baseline">
                          <td class="tititems" align="right" valign="top">Rol:</td>
                          <td><select name='rol' id='rol'>
                          <?php
                              $sql="select * from rol order by idrol desc";
                              $res=mysqli_query($sql);
                              while ($fila=mysqli_fetch_array($res)) {
                                    print "<option ";
                                    if (isset($_GET["mode"]) && $_GET["mode"]=="e" && $filae[12]==$fila[0]) { print "selected"; }
                                    print " value='$fila[0]'>$fila[1]</option>";
                              }
                          ?>
                          </select> <!-- <a href='#' onclick="window.open('rol_nuevo.php?t=popup', '_blank', 'status=0,menubar=0,location=0,scrollbars=0,resizable=0,width=600,height=400', 0);"><img src='images/508.png'></a> -->
                          </td>
                        </tr>
                        <tr valign="baseline">
                          <td colspan="2" align="center"><input class="boton1" type="submit" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print "Editar"; } else { print "Crear"; }?> recurso">
                          <input class="boton1" name="Cancelar" type="button" onClick="<?php if ((isset($_GET["t"]) && $_GET["t"]=="popup")) { print "window.close();"; } else { print "window.location='rrhh.php'"; } ?>" value='Cancelar'/></td>
                        </tr>
                      </table>
                      <input type="hidden" name="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print "MM_edit"; } else { print "MM_insert"; } ?>" value="actualizar">
                      <?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print "<input type='hidden' name='idrrhh' value='".$_GET["id"]."'>"; } ?>
                    </form>
<?php
    print $error;
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
<p>&nbsp;</p>
<br>
</body>
</html>