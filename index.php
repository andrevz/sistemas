<?php
include("config.php");
include("funciones.php");

session_start();

if (isset($_POST['grupo'])) {
  $loginUsername=$_POST['grupo'];
  $password=$_POST['password'];
  
  if ($depuracion==1) {
        $sql="SELECT login, contrasena, idRecursoHumano, nombres, apellidos, idrol FROM recursohumano WHERE login='".$loginUsername."' and usuarioactivo=1";
  } else {
        $sql="SELECT login, contrasena, idRecursoHumano, nombres, apellidos, idrol FROM recursohumano WHERE login='".$loginUsername."' AND contrasena='".md5($password)."' and usuarioactivo=1";
  }


  $LoginRS = mysql_query($sql, $simulacion);
  $loginFoundUser = mysql_num_rows($LoginRS);
  $loginData = mysql_fetch_array($LoginRS);


  if ($loginFoundUser) {


    $_SESSION['Username'] = $loginUsername;
    $_SESSION['NombreUsuario'] = $loginData['apellidos'].", ".$loginData['nombres'];
    $_SESSION['IdRH'] = $loginData['idRecursoHumano'];
    $_SESSION['idRol'] = $loginData['idrol'];

    logea($_SESSION['IdRH'],"login","IP: ".$_SERVER["REMOTE_ADDR"]);

    header("Location: menu.php");
  }
  else {
    header("Location: salir.php");
  }
    mysql_free_result($LoginRS);
    mysql_free_result($PeriodoRS);
    
    exit;
}

include("encabezado.php");
?>

<table width="100%" align="center" cellpadding="0"  cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td bgcolor='#eeeeee'>

    <table width='100%' ><tr><td width='10%'>
    <table><tr><td>

    </td></tr></table>
    </td><td bgcolor="#ffffff">
    <br /><br />    <br /><br /><br /><br /><br />
<form method="POST" name="login" action="index.php">
    <table align="center" >
        <tr>
            <td align='right'><font size="2" face="tahoma, sans-serif">
                <b>Id. usuario:</b></font>
            </td>
            <td>
                <input name="grupo" type="text" size="15" maxlength="15" class="contlogin" tabindex="1"/>
            </td>
            <td rowspan="2" align="center">
                <input class="boton2" name="Ingresar" type="submit" value="" tabindex="3"/>
            </td>
        </tr>
        <tr>
            <td ><font size="2" face="tahoma, sans-serif"">
                <b>
                Contrase&ntilde;a:</b></font>
            </td>
            <td>
                <input name="password" type="password" size="15" maxlength="15"  class="contlogin" tabindex="2"/>
            </td>
        </tr>
    </table>
</form>

<br /><br /><br /> <br /><br /><br /><br />

</td></tr></table>
</td>
</tr>

</table>
</td></tr></table>
<?php include("pie.php");?>
</body>
</html>