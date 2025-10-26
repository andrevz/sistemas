<?php

include("valida.php");
             $bloqueado=false;
             if (isset($_GET["idrh"]) && $_GET["idrh"]>0) {
                    $actividad=1;
                    $sql_rh="select * from recursohumano where idrecursohumano=".$_GET["idrh"];
                    $res_rh=mysql_query($sql_rh);
                    $fila_rh=mysql_fetch_array($res_rh);
                    $encabezado="<tr><td class='tititems' colspan='5' bgcolor='eeeeff'>Docente: $fila_rh[3], $fila_rh[2]</td></tr>";
                } else if (isset($_GET["idv"]) && $_GET["idv"]>0) {
                    $actividad=28;
                    $iversion=$_GET["idv"];
                    $sql_rh="select gestion, p.nombre, sigla, c.nombre, estado from version v inner join programa p on p.idprograma=v.idprograma inner join ciudad c on c.idciudad=v.ciudad where idversion=".$_GET["idv"];
                    $res_rh=mysql_query($sql_rh);
                    $fila_rh=mysql_fetch_array($res_rh);
                    $encabezado="<tr><td class='tititems' colspan='5' bgcolor='eeeeff'>Programa / versi&oacute;n: $fila_rh[1] ($fila_rh[2]) $fila_rh[0] - $fila_rh[3]</td></tr>";

                    $bloqueado=($fila_rh[4]<2 ? false : true);

                } else if (isset($_GET["idem"]) && $_GET["idem"]>0) {
                    $actividad=39;
                    $sql_rh="SELECT m.nombre, rh.nombres, rh.apellidos, v.gestion, c.nombre, p.nombre, p.sigla, v.idversion
                                    FROM ejecucionmateriaversiondocente em inner join materiaversion mv on mv.idmateriaversion=em.idmateriaversion
                                    inner join materia m on m.idmateria=mv.idmateria inner join recursohumano rh on em.idrecursohumano=rh.idrecursohumano
                                    inner join version v on v.idversion=mv.idversion inner join programa p on p.idprograma=v.idprograma
                                    inner join ciudad c on c.idciudad=v.ciudad
                                    where em.idejecucionmateriaversiondocente=".$_GET["idem"];
                    $res_rh=mysql_query($sql_rh);
                    $fila_rh=mysql_fetch_array($res_rh);

                    $sql_blo="select estado from version where idversion=".$fila_rh["idversion"];
                    $res_blo=mysql_query($sql_blo);
                    $fila_blo=mysql_fetch_array($res_blo);
                    $bloqueado=($fila_blo[0]<2 ? false : true);

                    $encabezado="<tr><td class='tititems' colspan='5' bgcolor='eeeeff'>Programa / versi&oacute;n: $fila_rh[5] ($fila_rh[6]) $fila_rh[3] - $fila_rh[4]<br>Materia / docente: $fila_rh[0] - $fila_rh[2], $fila_rh[1]</td></tr>";
                }

include("niveles_acceso.php");

if (isset($_GET["mode"]) && $_GET["mode"]=='d' && $eliminar && !$bloqueado) {

      $sql="delete from documentos where iddocumentos=".$_GET["idd"];
      mysql_query($sql) or die(mysql_error());

      header("Location: documentos.php?t=popup&idp=".$_GET["idp"]."&idr=".$_GET["idr"]."&idv=".$_GET["idv"]."&idem=".$_GET["idem"]);
      exit;

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
                            <td class="textsubmenu"><img src="images/bc_parent.png" /></td><td class="textsubmenu"><a href="menu.php" class="submenu">Retornar al men&uacute; principal</a></td>
                        </tr>
                        <tr>
                            <td class="textsubmenu"><img src="images/ok.gif" /></td><td class="textsubmenu"><a href="documentos_nuevos.php" class="submenu">Nuevo Documento</a></td>
                        </tr>
        </table>

        <br><br>
    </td>
    <?php } else { ?>
<table align="center" width="100%">
                        <tr>
                            <td class="textsubmenu" align='right' width='20'><img src="images/bc_parent.png" /></td><td class="textsubmenu" align='right'><a href="#" onclick="window.close();" class="submenu">Regresar</a></td>
                            <?php
                            if ($insertar && !$bloqueado){
                            ?>
                            <td class="textsubmenu" width='20'><img src="images/ok.gif" /></td><td class="textsubmenu">
                                <a href="documentos_nuevos.php?t=popup&idrh=<?php print $_GET["idrh"]; ?>&idv=<?php print $_GET["idv"]; ?>&idem=<?php print $_GET["idem"]; ?>" class="submenu">Nuevo Documento</a>
                            </td>
                            <?php
                            }
                            ?>
                        </tr>
        </table>
<?php
    }
    ?>
    <td width="600">
    <table align="center" width="100%" cellspacing='2'>
        <tr>
            <td class="bordes">
            <table class='contenido' width='100%'>
            <?php
                print $encabezado;
            ?>
                <tr>
                    <td class='tititems' width='62%'>T&iacute;tulo</td>
                    <td class='tititems' width='5%'>Tipo</td>
                    <td class='tititems' width='9%'>Tama&ntilde;o</td>
                    <td class='tititems' width='12%'>Fecha</td>
                    <td class='tititems'>Acciones</td>
                </tr>
                <?php
                    $col1="#eeeeee";
                    $col2="#ffffff";
                    $sql="select iddocumentos, titulo, tipo, tamano, fecha, nombrearchivo, descripcion from documentos ";
                    if (isset($_GET["idrh"]) && $_GET["idrh"]>0) {
                       $sql.=" where idrecursohumano=".$_GET["idrh"];
                    } else if (isset($_GET["idv"]) && $_GET["idv"]>0) {
                       $sql.=" where idversion=".$_GET["idv"];
                    }  else if (isset($_GET["idem"]) && $_GET["idem"]>0) {
                       $sql.=" where idejecucionmateriaversiondocente=".$_GET["idem"];
                    }
                    $sql.=" order by fecha, titulo";
                    $res=mysql_query($sql);
                    $colactual="";
                    while ($fila=mysql_fetch_array($res)) {
                          if ($colactual!=$col1) {
                             $colactual=$col1;
                          } else {
                             $colactual=$col2;
                          }
                          print "
                <tr bgcolor='$colactual'>
                    <td class='tabladettxt'><a href='descarga_archivo.php?id=$fila[0]' target='_blank'>".$fila[1]."</a>
                    <br>".$fila["descripcion"]."</td>
                    <td class='tabladetnum'>";
                    imagen($fila[2]);
                    print "</td>
                    <td  class='tabladetnum'>".round(($fila[3]/1024),0)." KB</td>
                    <td  class='tabladettxt'>".fecha($fila[4],9)."</td><td class='tabladettxt'>";
                    if ($editar && !$bloqueado) {
                       print "<a href='documentos_nuevos.php?t=popup&mode=e&idp=".$_GET["idp"]."&idrh=".$_GET["idrh"]."&idd=".$fila[0]."&idem=".$_GET["idem"]."&idv=".$_GET["idv"]."'><img src='images/editar.png' border='0'></a> ";
                    }
                    if ($eliminar && !$bloqueado) {
                       print "<a onclick='if(confirm(\"Esta seguro que desea eliminar este documento?\")) { return true;} else { return false; }' href='documentos.php?mode=d&idp=".$_GET["idp"]."&idr=".$_GET["idr"]."&idd=".$fila[0]."&idem=".$_GET["idem"]."'><img src='images/delete.png' border='0' width='25'></a>";
                    }
                    print "</td>
                </tr>";
                    }

                ?>
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