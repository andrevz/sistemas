<?php

include("valida.php");
if (acceso($_SESSION['idRol'], 0,0,0,0,0,4)<=1) {
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
            var gestion = $("#gestion").find(':selected').val();
            $("#pdocente").html("<img src='<?php print $path; ?>images/loading.gif' />");
            $("#pdocente").load('select_formulario.php?select=docente&tipo='+idtipoprograma+'&programa=%&ciudad=%&gestion=%');
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
            var gestion = $("#gestion").find(':selected').val();
            $("#pdocente").html("<img src='<?php print $path; ?>images/loading.gif' />");
            $("#pdocente").load('select_formulario.php?select=docente&tipo='+idtipoprograma+'&programa='+idprograma+'&ciudad=%&gestion=%');
        });
        $("#ciudad").live("change",function(event)
        {
            var idtipoprograma = $("#idtipoprograma").find(':selected').val();
            var idprograma = $("#idprograma").find(':selected').val();
            var ciudad = $(this).find(':selected').val();
            $("#pgestion").html("<img src='<?php print $path; ?>images/loading.gif' />");
            $("#pgestion").load('select_formulario.php?select=gestion&tipo='+idtipoprograma+'&programa='+idprograma+'&ciudad='+ciudad);
            var gestion = $("#gestion").find(':selected').val();
            $("#pdocente").html("<img src='<?php print $path; ?>images/loading.gif' />");
            $("#pdocente").load('select_formulario.php?select=docente&tipo='+idtipoprograma+'&programa='+idprograma+'&ciudad='+ciudad+'&gestion=%');
        });
        $("#gestion").live("change",function(event)
        {
            var idtipoprograma = $("#idtipoprograma").find(':selected').val();
            var idprograma = $("#idprograma").find(':selected').val();
            var ciudad = $("#ciudad").find(':selected').val();
            var gestion = $(this).find(':selected').val();
            $("#pdocente").html("<img src='<?php print $path; ?>images/loading.gif' />");
            $("#pdocente").load('select_formulario.php?select=docente&tipo='+idtipoprograma+'&programa='+idprograma+'&ciudad='+ciudad+'&gestion='+gestion);
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
                    if (acceso($_SESSION['idRol'], 0,0,0,0,31,0)>=2) {
                        ?>
                        <tr>
                            <td class="textsubmenu"><img src="images/ok.gif" /></td><td class="textsubmenu"><a href="#" onclick="window.open('reportes/planificacion.php?idtp='+document.datos.idtipoprograma.value+'&idp='+document.datos.idprograma.value+'&ciudad='+document.datos.ciudad.value+'&gestion='+document.datos.gestion.value)" class="submenu">Planificaci&oacute;n por versi&oacute;n</a></td>
                        </tr>
                        <?php
                    }
                    if (acceso($_SESSION['idRol'], 0,0,0,0,32,0)>=2) {
                        ?>
                        <tr>
                            <td class="textsubmenu"><img src="images/ok.gif" /></td><td class="textsubmenu"><a href="#" onclick="window.open('reportes/control.php?idtp='+document.datos.idtipoprograma.value+'&idp='+document.datos.idprograma.value+'&ciudad='+document.datos.ciudad.value+'&gestion='+document.datos.gestion.value)" class="submenu">Ejecuci&oacute;n por versi&oacute;n</a></td>
                        </tr>
                        <?php
                    }
                    if (acceso($_SESSION['idRol'], 0,0,0,0,33,0)>=2) {
                        ?>
                        <tr>
                            <td class="textsubmenu"><img src="images/ok.gif" /></td><td class="textsubmenu"><a href="reportes/tablero.php" class="submenu" target='_blank'>Reporte consolidado</a></td>
                        </tr>
                        <?php
                    }
                    if (acceso($_SESSION['idRol'], 0,0,0,0,34,0)>=2) {
                        ?>
                        <tr>
                            <td class="textsubmenu"><img src="images/ok.gif" /></td><td class="textsubmenu"><a href="#" onclick="window.open('reportes/plan_medios_programa.php?idtp='+document.datos.idtipoprograma.value+'&idp='+document.datos.idprograma.value+'&ciudad='+document.datos.ciudad.value+'&gestion='+document.datos.gestion.value)" class="submenu">Plan de medios por programa</a></td>
                        </tr>
                        <?php
                    }
                    if (acceso($_SESSION['idRol'], 0,0,0,0,35,0)>=2) {
                        ?>
                        <tr>
                            <td class="textsubmenu"><img src="images/ok.gif" /></td><td class="textsubmenu"><a href="#" onclick="window.open('reportes/plan_medios_ciudad.php?idtp='+document.datos.idtipoprograma.value+'&idp='+document.datos.idprograma.value+'&ciudad='+document.datos.ciudad.value+'&gestion='+document.datos.gestion.value)" class="submenu">Plan de medios por ciudad</a></td>
                        </tr>
                        <?php
                    }
                    if (acceso($_SESSION['idRol'], 0,0,0,0,36,0)>=2) {
                        ?>
                        <tr>
                            <td class="textsubmenu"><img src="images/ok.gif" /></td><td class="textsubmenu"><a href="#" onclick="window.open('reportes/reporte_reclutamiento.php?idtp='+document.datos.idtipoprograma.value+'&idp='+document.datos.idprograma.value+'&ciudad='+document.datos.ciudad.value+'&gestion='+document.datos.gestion.value)" class="submenu">Reporte de reclutamiento</a></td>
                        </tr>
                        <?php
                    }
                    if (acceso($_SESSION['idRol'], 0,0,0,0,37,0)>=2) {
                        ?>
                        <tr>
                            <td class="textsubmenu"><img src="images/ok.gif" /></td><td class="textsubmenu"><a href="#" onclick="window.open('reportes/detalle_reclutamiento.php?idtp='+document.datos.idtipoprograma.value+'&idp='+document.datos.idprograma.value+'&ciudad='+document.datos.ciudad.value+'&gestion='+document.datos.gestion.value)" class="submenu">Detalle reclutamiento</a></td>
                        </tr>
                        <?php
                    }
                    if (acceso($_SESSION['idRol'], 0,0,0,0,43,0)>=2) {
                        ?>
                        <tr>
                            <td class="textsubmenu"><img src="images/ok.gif" /></td><td class="textsubmenu"><a href="#" onclick="window.open('reportes/cronograma.php?idtp='+document.datos.idtipoprograma.value+'&idp='+document.datos.idprograma.value+'&ciudad='+document.datos.ciudad.value+'&gestion='+document.datos.gestion.value)" class="submenu">Reporte de cronograma</a></td>
                        </tr>
                        <?php
                    }
                    if (acceso($_SESSION['idRol'], 0,0,0,0,44,0)>=2) {
                        ?>
                        <tr>
                            <td class="textsubmenu"><img src="images/ok.gif" /></td><td class="textsubmenu"><a href="#" onclick="window.open('reportes/roles.php')" class="submenu">Reporte de roles</a></td>
                        </tr>
                        <?php
                    }
                    if (acceso($_SESSION['idRol'], 0,0,0,0,45,0)>=2) {
                        ?>
                        <tr>
                            <td class="textsubmenu"><img src="images/ok.gif" /></td><td class="textsubmenu"><a href="#" onclick="window.open('reportes/docentes.php')" class="submenu">Reporte de docentes</a></td>
                        </tr>
                        <?php
                    }
                    if (acceso($_SESSION['idRol'], 0,0,0,0,46,0)>=2) {
                        ?>
                        <tr>
                            <td class="textsubmenu"><img src="images/ok.gif" /></td><td class="textsubmenu"><a href="#" onclick="window.open('reportes/docentes_programa.php?idtp='+document.datos.idtipoprograma.value+'&idp='+document.datos.idprograma.value+'&ciudad='+document.datos.ciudad.value+'&gestion='+document.datos.gestion.value)" class="submenu">Docentes por programa</a></td>
                        </tr>
                        <?php
                    }
                    if (acceso($_SESSION['idRol'], 0,0,0,0,47,0)>=2) {
                        ?>
                        <tr>
                            <td class="textsubmenu"><img src="images/ok.gif" /></td><td class="textsubmenu"><a href="#" onclick="window.open('reportes/asignaturas_docente.php?idtp='+document.datos.idtipoprograma.value+'&idp='+document.datos.idprograma.value+'&ciudad='+document.datos.ciudad.value+'&gestion='+document.datos.gestion.value+'&docente='+document.datos.docente.value)" class="submenu">Asignaturas por docente</a></td>
                        </tr>
                        <?php
                    }
                    if (acceso($_SESSION['idRol'], 0,0,0,0,48,0)>=2) {
                        ?>
                        <tr>
                            <td class="textsubmenu"><img src="images/ok.gif" /></td><td class="textsubmenu"><a href="#" onclick="window.open('reportes/historial_asignaturas_docente.php?idtp='+document.datos.idtipoprograma.value+'&idp='+document.datos.idprograma.value+'&ciudad='+document.datos.ciudad.value+'&gestion='+document.datos.gestion.value+'&docente='+document.datos.docente.value)" class="submenu">Historial de asignaturas por docente</a></td>
                        </tr>
                        <?php
                    }
                    if (acceso($_SESSION['idRol'], 0,0,0,0,49,0)>=2) {
                        ?>
                        <tr>
                            <td class="textsubmenu"><img src="images/ok.gif" /></td><td class="textsubmenu"><a href="#" onclick="window.open('reportes/actividades_programa.php?idtp='+document.datos.idtipoprograma.value+'&idp='+document.datos.idprograma.value+'&ciudad='+document.datos.ciudad.value+'&gestion='+document.datos.gestion.value+'&docente='+document.datos.docente.value)" class="submenu">Actividades por programa</a></td>
                        </tr>
                        <?php
                    }
                    if (acceso($_SESSION['idRol'], 0,0,0,0,50,0)>=2) {
                        ?>
                        <tr>
                            <td class="textsubmenu"><img src="images/ok.gif" /></td><td class="textsubmenu"><a href="#" onclick="window.open('reportes/presupuesto_version.php?idtp='+document.datos.idtipoprograma.value+'&idp='+document.datos.idprograma.value+'&ciudad='+document.datos.ciudad.value+'&gestion='+document.datos.gestion.value+'&docente='+document.datos.docente.value)" class="submenu">Presupuesto por versi&oacute;n</a></td>
                        </tr>
                        <?php
                    }
                    if (acceso($_SESSION['idRol'], 0,0,0,0,51,0)>=2) {
                        ?>
                        <tr>
                            <td class="textsubmenu"><img src="images/ok.gif" /></td><td class="textsubmenu"><a href="reporte_actividades_programa.php" onclick="" class="submenu">Actividades por versi&oacute;n</a></td>
                        </tr>
                        <?php
                    }
                        ?>

        </table>

        <br><br>
    </td>
    <td width="800">
    <form name='datos'>
    <table>
        <tr>
            <td class='tititems'>Programa:</td>
            <td>
                <select name='idtipoprograma' id='idtipoprograma' >
                        <option value='%'>[ Todos los tipos de programa ]</option>
    <?php
      $sql_versiones="select distinct tp.idtipoprograma, tp.nombre
                                 from version v inner join recursohumano rh on rh.idrecursohumano=v.idrecursohumano
                                 inner join programa p on p.idprograma=v.idprograma inner join tipoprograma tp on tp.idtipoprograma=p.idtipoprograma
                                 where activo=1 order by nombre";
      $res_versiones=mysqli_query($sql_versiones);
      while ($fila_versiones=mysqli_fetch_array($res_versiones)) {
         if (acceso($_SESSION['idRol'], 0,0,$fila_versiones[0],0,0,0)>=2) {
            print "<option value='$fila_versiones[0]'>$fila_versiones[1]</option>";
         }
      }
    ?></select>
            </td>
        </tr>
        <tr>
            <td class='tititems'>Nombre:</td>
            <td>
                <p id="pidprograma"><select name='idprograma' id='idprograma' >
                          <option value='%'>[ Todos los programas ]</option>
    <?php
      $sql_versiones="select distinct p.idprograma, p.nombre
                                 from version v inner join recursohumano rh on rh.idrecursohumano=v.idrecursohumano
                                 inner join programa p on p.idprograma=v.idprograma inner join tipoprograma tp on tp.idtipoprograma=p.idtipoprograma
                                 where activo=1 order by nombre";
      $res_versiones=mysqli_query($sql_versiones);
      while ($fila_versiones=mysqli_fetch_array($res_versiones)) {
            print "<option value='$fila_versiones[0]'>$fila_versiones[1]</option>";
      }
    ?></select></p>
            </td>
        </tr>
        <tr>
            <td class='tititems'>Ciudad:</td>
            <td>
                <p id="pciudad"><select name='ciudad' id='ciudad' >
                        <option value='%'>[ Todas las ciudades ]</option>
    <?php
      $sql_versiones="select distinct c.idciudad, c.nombre
                                 from version v inner join recursohumano rh on rh.idrecursohumano=v.idrecursohumano
                                 inner join programa p on p.idprograma=v.idprograma inner join tipoprograma tp on tp.idtipoprograma=p.idtipoprograma
                                 inner join ciudad c on c.idciudad=v.ciudad
                                 where activo=1 order by c.nombre";
      $res_versiones=mysqli_query($sql_versiones);
      while ($fila_versiones=mysqli_fetch_array($res_versiones)) {
         if (acceso($_SESSION['idRol'], 0,0,0,$fila_versiones[0],0,0)>=2) {
            print "<option value='$fila_versiones[0]'>$fila_versiones[1]</option>";
         }
      }
    ?></select></p>

            </td>
        </tr>
        <tr>
            <td class='tititems'>Gesti&oacute;n:</td>
            <td>
                <p id="pgestion"><select name='gestion' id='gestion'>
                        <option value='%'>[ Todas las gestiones ]</option>
    <?php
      $sql_versiones="select distinct gestion
                                 from version v inner join recursohumano rh on rh.idrecursohumano=v.idrecursohumano
                                 inner join programa p on p.idprograma=v.idprograma inner join tipoprograma tp on tp.idtipoprograma=p.idtipoprograma
                                 where activo=1 order by gestion";
      $res_versiones=mysqli_query($sql_versiones);
      while ($fila_versiones=mysqli_fetch_array($res_versiones)) {
            print "<option value='$fila_versiones[0]'>$fila_versiones[0]</option>";
      }
    ?></select></p>

            </td>
        </tr>
        <tr>
            <td class='tititems'>Docente:</td>
            <td>
                <p id="pdocente"><select name='docente' id='docente'>
                        <option value='%'>[ Todos los docentes ]</option>
    <?php
      $sql_versiones="select distinct rh.idrecursohumano, concat(upper(apellidos), ', ', upper(nombres))
                                 from version v inner join recursohumano rh on rh.idrecursohumano=v.idrecursohumano
                                 inner join programa p on p.idprograma=v.idprograma inner join tipoprograma tp on tp.idtipoprograma=p.idtipoprograma
                                 where activo=1 order by upper(apellidos), upper(nombres)";
      $res_versiones=mysqli_query($sql_versiones);
      while ($fila_versiones=mysqli_fetch_array($res_versiones)) {
            print "<option value='$fila_versiones[0]'>$fila_versiones[1]</option>";
      }
    ?></select></p>

            </td>
        </tr>
</form>
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