<?php
include("valida.php");
include("encabezado.php");

?>
<table width="100%" align="center" cellpadding="0"  cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td valign="top" align="center">
        <table width="200" border="0" cellspacing="2">
            <tr>
                <td class="bordes">
                    <table width="100%" cellspacing="0" cellpadding="0">
                        <?php
                            $alto='55';
                        ?>
<?php
    if (acceso($_SESSION['idRol'], 0,0,0,0,0,1)>1) {
?>
                        <tr>
                            <td class="titmenu" height="<?php print $alto; ?>" width='55'><img border="0" src="images/parametros.png" /></td><td class='textmenu'><a href="parametros.php" class="menu">Par&aacute;metros</a></td>
                        </tr>
<?php
    }
    if (acceso($_SESSION['idRol'], 0,0,0,0,0,2)>1) {
?>

                        <tr>
                            <td class="titmenu" height="<?php print $alto; ?>"><img border="0" src="images/222.png" /></td><td class='textmenu'><a href="programas.php" class="menu">Planificaci&oacute;n</a></td>
                        </tr>
<?php
    }
    if (acceso($_SESSION['idRol'], 0,0,0,0,0,3)>1) {
?>
                        <tr>
                            <td class="titmenu" height="<?php print $alto; ?>"><img border="0" src="images/proyectos.png" /></td><td class='textmenu'><a href="versiones_t.php" class="menu">Ejecuci&oacute;n</a></td>
                        </tr>
<!--                        <tr>
                            <td class="titmenu" height="<?php print $alto; ?>"><img border="0" src="images/money.png" /></td><td class='textmenu'><a href="finanzas.php" class="menu">Finanzas</a></td>
                        </tr> -->
<?php
    }
    if (acceso($_SESSION['idRol'], 0,0,0,0,0,4)>1) {
?>
                        <tr>
                            <td class="titmenu" height="<?php print $alto; ?>"><img width='48' border="0" src="images/reportes.png" /></td><td class='textmenu'><a href="reportes.php" class="menu">Reportes</a></td>
                        </tr>
<?php
    }
?>
                    </table>
                </td>
            </tr>
        </table>
        <table width="100%" border="0" cellspacing="2">
            <tr>
                <td class="bordes">
                    <table width="100%" cellspacing="0" cellpadding="0">
                        <tr>
                            <td class="titmenu" width="55" height="<?php print $alto; ?>"><img border="0" src="images/Shutdown.png"/></td>
                            <td class='textmenu'><a href="salir.php" class="menu">Salir</a></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <br><br><br><br>
    </td>
    <td width="600" valign="top">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr height="40" >
                <td valign="top" bgcolor="#FFFFFF"><img src="images/bordes/supizq.gif" width="30" height="30"></td>
                <td width="90%" background="images/bordes/centro.gif" class='titnoticias'>
                    <table>
                        <tr>
                            <td></td>
                            <td></td>
                        </tr>
                    </table>
                </td>
                <td valign="top" bgcolor="#FFFFFF"><img src="images/bordes/supder.gif" width="30" height="30" /></td>
            </tr>
            <tr>
                <td class="cuerponoticia">&nbsp;</td>
                <td class="cuerponoticia"><span class="detaviso"><b></b>
                <?php
                ?>
                </span>
                </td>
                <td class="cuerponoticia">&nbsp;</td>
            </tr>
            <tr>
                <td class="cuerponoticia">&nbsp;</td>
                <td class="cuerponoticia"><span class="detaviso"><BR><b></b>
                </span>
                </td>
                <td class="cuerponoticia">&nbsp;</td>
            </tr>
            <tr>
                <td></td><td class="detaviso" align='center' width='100%'>
                </td>
            </tr>
            <tr>
                <td class="cuerponoticia">&nbsp;</td>
                <td class="cuerponoticia"><span class="detaviso"><BR><table><tr><td><b>Usuario:</b></td><td>
                <?php
                print $_SESSION['NombreUsuario'];
                $sql="select * from rol where idrol=".$_SESSION['idRol'];
                $res=mysqli_query($sql);
                $fila=mysqli_fetch_array($res);
                print "</td></tr><tr><td><b>Rol:</b></td><td> ".$fila[1]."</td></tr></table>";
                ?>
                </span>
                </td>
                <td class="cuerponoticia">&nbsp;</td>
            </tr>
        </table>
    </td>
    <td width="200" valign='top'>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr height="40" >
                <td width='30' valign="top" bgcolor="#FFFFFF"><img src="images/bordes/supizq.gif" width="30" height="30"></td>
                <td width="140" background="images/bordes/centro.gif" class='titnoticias'><table><tr><td><img src="images/noticias.gif" /></td><td>&nbsp;&nbsp;&nbsp;AVISOS</td></tr></table></td>
                <td width='30' valign="top" bgcolor="#FFFFFF"><img src="images/bordes/supder.gif" width="30" height="30" /></td>
            </tr>
            <tr height="250">
                <td class="cuerponoticia">&nbsp;</td>
                <td class="cuerponoticia" valign="top">
                <?php
/*                    $sql="select * from noticias where ".$_SESSION['Periodo']." between inicio and final";
                    $NoticiasRS = mysqli_query($sql, $simulacion); if (!$NoticiasRS) { $msj="Error: ".mysqli_error()."\n SQL: ".$sql."\n Script: ".$_SERVER["PHP_SELF"]; logea($_SESSION["IdEmpresa"], $msj, $_SESSION["SubPeriodo"]); if ($depuracion==1) { print "<br>".$msj."<br>"; } }
                    $noticiasFound = mysqli_num_rows($NoticiasRS);
                    
                    while ($noticiasData = mysqli_fetch_array($NoticiasRS)) {
                        print "<p><span class='titulonot'>".$noticiasData['titulo']."</span><br><span class='detnot'>";
                        print $noticiasData['detalle']."</span></p>";
                    } */
                ?>
                </td>
                <td class="cuerponoticia">&nbsp;</td>
            </tr>
        </table>
    </td>
</tr>

</table>
</td>
</tr>

</table>
<?php
include("pie.php");
?></body>