<?php

include("valida.php");

if (isset($_GET["mode"]) && $_GET["mode"]=='d') {

      $sql="delete from entregafondos where identregafondos=".$_GET["id"];
      mysqli_query($sql) or die(mysqli_error());

      header("Location: solicitudes.php");
      exit;

}

include("encabezado.php");

?>

<table align="center" bgcolor="#ffffff" width="100%">
<tr>
    <td width="200" valign="top">
        <table align="center" width="100%">
                        <tr>
                            <td class="textsubmenu"><img src="images/bc_parent.png" /></td><td class="textsubmenu"><a href="finanzas.php" class="submenu">Retornar al men&uacute; anterior</a></td>
                        </tr>
                        <tr>
                            <td class="textsubmenu"><img src="images/ok.gif" /></td><td class="textsubmenu"><a href="solicitud_fondos.php" class="submenu">Nueva Solicitud</a></td>
                        </tr>
        </table>

        <br><br>
    </td>
    <td width="600">
    <table align="center" width="100%" cellspacing='2'>
        <tr>
            <td class="bordes">
            <?php
            include("tabla_solicitudes.php");
            ?>



    </td>
    </tr>
    </table>
</td>
</tr>
<?php
include("pie.php");
?>
</table>
<p>&nbsp;</p>
<br>
</body>
</html>