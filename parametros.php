<?php

include("valida.php");

if (acceso($_SESSION['idRol'], 0,0,0,0,0,1)<=1) {
   header("Location: menu.php");
   exit;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php print $nombresistema; ?></title>
<link href="<?php print $path; ?>estilos.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php print $path; ?>js/jquery.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        /* COMBOBOX */
        $("#idtipoprograma").change(function(event)
        {
            var idtipoprograma = $(this).find(':selected').val();
            $("#pidprograma").html("<img src='<?php print $path; ?>images/loading.gif' />");
            $("#pidprograma").load('select_formulario.php?select=idprograma&tipo='+idtipoprograma);
            var idprograma = $("#idprograma").find(':selected').val();
            $("#pciudad").html("<img src='<?php print $path; ?>images/loading.gif' />");
            $("#pciudad").load('select_formulario.php?select=ciudad&tipo='+idtipoprograma+'&programa=%');
            var ciudad = $("#ciudad").find(':selected').val();
            $("#pgestion").html("<img src='<?php print $path; ?>images/loading.gif' />");
            $("#pgestion").load('select_formulario.php?select=gestion&tipo='+idtipoprograma+'&programa=%&ciudad=%');
        });
        $("#idprograma").live("change", function(event)
        {
            var idtipoprograma = $("#idtipoprograma").find(':selected').val();
            var idprograma = $(this).find(':selected').val();
            $("#pciudad").html("<img src='<?php print $path; ?>images/loading.gif' />");
            $("#pciudad").load('select_formulario.php?select=ciudad&tipo='+idtipoprograma+'&programa='+idprograma);
            var ciudad = $("#ciudad").find(':selected').val();
            $("#pgestion").html("<img src='<?php print $path; ?>images/loading.gif' />");
            $("#pgestion").load('select_formulario.php?select=gestion&tipo='+idtipoprograma+'&programa='+idprograma+'&ciudad=%');
        });
        $("#ciudad").live("change",function(event)
        {
            var idtipoprograma = $("#idtipoprograma").find(':selected').val();
            var idprograma = $("#idprograma").find(':selected').val();
            var ciudad = $(this).find(':selected').val();
            $("#pgestion").html("<img src='<?php print $path; ?>images/loading.gif' />");
            $("#pgestion").load('select_formulario.php?select=gestion&tipo='+idtipoprograma+'&programa='+idprograma+'&ciudad='+ciudad);
        });
    });
    </script>

    </head>
<body>
<table width="1000" cellspacing="0" cellpadding="1" align="center" class="cuerpo" border="0">
    <tr>
        <td background="<?php print $path; ?>images/banner2.jpg" ALIGN='RIGHT' valign="bottom" height="110" style="background-repeat:no-repeat; background-position:center">

        </td>
    </tr>
    <tr>
    <td>
<table align="center" bgcolor="#ffffff" width="100%">
<tr>
    <td width="200" valign="top">
        <table align="center" width="100%">
                        <tr>
                            <td class="textsubmenu"><img src="images/bc_parent.png" /></td><td class="textsubmenu"><a href="menu.php" class="submenu">Retornar al men&uacute; anterior</a></td>
                        </tr>
<?php
    if (acceso($_SESSION['idRol'], 0,0,0,0,1,0)>1) {
?>
                        <tr>
                            <td class="textsubmenu"><img src="images/ok.gif" /></td><td class="textsubmenu"><a href="rrhh.php" class="submenu">Recursos Humanos</a></td>
                        </tr>
<?php
    }
    if (acceso($_SESSION['idRol'], 0,0,0,0,2,0)>1) {
?>
                        <tr>
                            <td class="textsubmenu"><img src="images/ok.gif" /></td><td class="textsubmenu"><a href="materias.php" class="submenu">Materias</a></td>
                        </tr>
<?php
    }
    if (acceso($_SESSION['idRol'], 0,0,0,0,3,0)>1) {
?>
                        <tr>
                            <td class="textsubmenu"><img src="images/ok.gif" /></td><td class="textsubmenu"><a href="edmedios.php" class="submenu">Medios</a></td>
                        </tr>
<?php
    }
    if (acceso($_SESSION['idRol'], 0,0,0,0,4,0)>1) {
?>
                        <tr>
                            <td class="textsubmenu"><img src="images/ok.gif" /></td><td class="textsubmenu"><a href="edcostos.php" class="submenu">Costos</a></td>
                        </tr>
<?php
    }
    if (acceso($_SESSION['idRol'], 0,0,0,0,9,0)>1) {
?>
                        <tr>
                            <td class="textsubmenu"><img src="images/ok.gif" /></td><td class="textsubmenu"><a href="rol.php" class="submenu">Crear / editar roles</a></td>
                        </tr>
<?php
    }
    if (acceso($_SESSION['idRol'], 0,0,0,0,5,0)>1) {
?>
                        <tr>
                            <td class="textsubmenu"><img src="images/ok.gif" /></td><td class="textsubmenu"><a href="actividades_roles.php" class="submenu">Asignar actividades a roles</a></td>
                        </tr>
<?php
    }
    if (acceso($_SESSION['idRol'], 0,0,0,0,6,0)>1) {
?>
                        <tr>
                            <td class="textsubmenu"><img src="images/ok.gif" /></td><td class="textsubmenu"><a href="rol_escuela.php" class="submenu">Asignar escuelas a roles</a></td>
                        </tr>
<?php
    }
    if (acceso($_SESSION['idRol'], 0,0,0,0,7,0)>1) {
?>
                        <tr>
                            <td class="textsubmenu"><img src="images/ok.gif" /></td><td class="textsubmenu"><a href="rol_tipoprograma.php" class="submenu">Asignar tipo de programas a roles</a></td>
                        </tr>
<?php
    }
    if (acceso($_SESSION['idRol'], 0,0,0,0,8,0)>1) {
?>
                        <tr>
                            <td class="textsubmenu"><img src="images/ok.gif" /></td><td class="textsubmenu"><a href="rol_version.php" class="submenu">Asignar versiones a roles</a></td>
                        </tr>
<?php
    }
    if (acceso($_SESSION['idRol'], 0,0,0,0,10,0)>1) {
?>
                        <tr>
                            <td class="textsubmenu"><img src="images/ok.gif" /></td><td class="textsubmenu"><a href="rol_ciudad.php" class="submenu">Asignar ciudades a roles</a></td>
                        </tr>
<?php
    }
?>

        </table>

        <br><br>
    </td>
    <td width="800">

</td>
</tr>

</table>
</td></tr></table>
<?php
include("pie.php");
?>
</body>
</html>