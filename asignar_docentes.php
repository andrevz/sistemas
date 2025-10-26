<?php

include("valida.php");
$iversion=$_GET["idv"];
$actividad=19;
include("niveles_acceso.php");


$sql_blo="select estado from version where idversion=".$_GET["idv"];;
$res_blo=mysql_query($sql_blo);
$fila_blo=mysql_fetch_array($res_blo);
$bloqueado=($fila_blo[0]==0 ? false : true);

if (isset($_POST["MM_insert"]) && $insertar && !$bloqueado) {
    if ($_POST["recursohumano"]=="-1" or $_POST["horas"]=="" or is_null($_POST["horas"]) or $_POST["horas"]=="0") {
       $error="<font color='red'><b>Deben registrarse obligatoriamente el docente y las horas asignadas</b></font>";
    } else {

      $sql="insert into planmateriaversiondocente values (null, '".$_POST["horas"]."', '".$_POST["hhora"]."', '".$_POST["pasajes"]."', '".$_POST["viaticos"]."', '".$_POST["dias"]."', '".$_POST["hdia"]."', '".$_POST["categoria"]."', '".$_POST["recursohumano"]."','".$_POST["ids"]."', '".fecha($_POST["inicio"],99)."','".fecha($_POST["fin"],99)."', '".$_POST["procedencia"]."')";
      mysql_query($sql) or die(mysql_error());
      $idins=mysql_insert_id();
      $sql="insert into ejecucionmateriaversiondocente values (null, '".$_POST["horas"]."', '".$_POST["hhora"]."', '".$_POST["pasajes"]."', '".$_POST["viaticos"]."',
                   '".$_POST["dias"]."', '".$_POST["hdia"]."', '', 0, 0, 0, 0, '', '".$_POST["ids"]."', '".$_POST["recursohumano"]."', '".$_POST["categoria"]."', $idins, '".fecha($_POST["inicio"],99)."','".fecha($_POST["fin"],99)."', '".$_POST["procedencia"]."', '')";
      mysql_query($sql) or die(mysql_error());

      $id_version=mysql_insert_id();

      header("Location: asignar_docentes.php?idv=".$_GET["idv"]."&ids=".$_POST["ids"]);
      exit;
   }
} else if (isset($_GET["mode"]) && $_GET["mode"]=="d" && $eliminar && !$bloqueado) {

      $sqla="select idejecucionmateriaversiondocente from ejecucionmateriaversiondocente where idplanmateriaversiondocente=".$_GET["idpr"];
      $resa=mysql_query($sqla) or die(mysql_error());
      $filaa=mysql_fetch_array($resa);

      if (!is_null($filaa[0])) {
         $sql="delete from checklist where idejecucionmateriaversiondocente=".$filaa[0];
         mysql_query($sql) or die(mysql_error());
      }

      $sql="delete from ejecucionmateriaversiondocente where idplanmateriaversiondocente=".$_GET["idpr"];
      mysql_query($sql) or die(mysql_error());


      $sql="delete from planmateriaversiondocente where idplanmateriaversiondocente=".$_GET["idpr"];
      mysql_query($sql) or die(mysql_error());


      header("Location: asignar_docentes.php?idv=".$_GET["idv"]."&ids=".$_GET["ids"]);
      exit;
} else if (isset($_POST["MM_edit"]) && $editar && !$bloqueado) {
    if ($_POST["recursohumano"]=="-1" or $_POST["horas"]=="" or is_null($_POST["horas"]) or $_POST["horas"]=="0") {
       $error="<font color='red'><b>Deben registrarse obligatoriamente el docente y las horas asignadas</b></font>";
    } else {

      $sql="update planmateriaversiondocente set horas='".$_POST["horas"]."', honorarioshora='".$_POST["hhora"]."', pasajes='".$_POST["pasajes"]."',
                   viaticosdia='".$_POST["viaticos"]."', dias='".$_POST["dias"]."', hospedajedia='".$_POST["hdia"]."', idcategoria='".$_POST["categoria"]."',
                   idrecursohumano='".$_POST["recursohumano"]."', inicio='".fecha($_POST["inicio"],99)."', fin='".fecha($_POST["fin"],99)."', procedencia='".$_POST["procedencia"]."' where idplanmateriaversiondocente=".$_GET["idpr"];
      mysql_query($sql) or die(mysql_error());
      $idins=mysql_insert_id();
      $sql="update ejecucionmateriaversiondocente set horas='".$_POST["horas"]."', honorarioshora='".$_POST["hhora"]."', pasajes='".$_POST["pasajes"]."',
                   viaticosdia='".$_POST["viaticos"]."', dias='".$_POST["dias"]."', hospedajedia='".$_POST["hdia"]."', idcategoria='".$_POST["categoria"]."',
                   idrecursohumano='".$_POST["recursohumano"]."', inicio='".fecha($_POST["inicio"],99)."', fin='".fecha($_POST["fin"],99)."', procedencia='".$_POST["procedencia"]."' where idplanmateriaversiondocente=".$_GET["idpr"];
      mysql_query($sql) or die(mysql_error());


      header("Location: asignar_docentes.php?idv=".$_GET["idv"]."&ids=".$_POST["ids"]);
      exit;
   }
}

   ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php print $nombresistema; ?></title>
<link rel="stylesheet" href="<?php print $path; ?>theme/jquery.ui.all.css">
  <script src="<?php print $path; ?>js/jquery-1.7.1.min.js"></script>
  <script src="<?php print $path; ?>js/jquery.ui.core.js"></script>
  <script src="<?php print $path; ?>js/jquery.ui.widget.js"></script>
<link href="<?php print $path; ?>estilos.css" rel="stylesheet" type="text/css" />
  <script src="<?php print $path; ?>js/jquery.ui.datepicker.js"></script>
  <script>
$(function() {
    var dates = $( "#convo, #ela, #pres" ).datepicker({
      defaultDate: "+1w",
      changeYear: true,
      numberOfMonths: 1,
      onSelect: function( selectedDate ) {
    //    var option = this.id == "convo" ? "minDate" : "maxDate",
          instance = $( this ).data( "datepicker" ),
          date = $.datepicker.parseDate(
            instance.settings.dateFormat ||
            $.datepicker._defaults.dateFormat,
            selectedDate, instance.settings );
    //    dates.not( this ).datepicker( "option", option, date );
      }
    });
  });
  </script>

</head>
<?php
if (isset($error)) {
print $error;
}?>
<table align="center" bgcolor="#ffffff" width="1100">
<tr>
    <td width="100%">
            <?php
                $sqldatos="SELECT idmateriaversion, universidad, m.nombre, gestion, c.nombre, p.nombre
                                  FROM materiaversion mv inner join materia m on m.idmateria=mv.idmateria

                                  inner join version v on v.idversion=mv.idversion inner join programa p on p.idprograma=v.idprograma
                                  inner join ciudad c on c.idciudad=v.ciudad
                                  where idmateriaversion=".$_GET["ids"];
                $resdatos=mysql_query($sqldatos);
                $filadatos=mysql_fetch_array($resdatos);
            ?>
            <table class='contenido' width='100%'>

                   <tr>
                       <td class='titproy' colspan='4' align='center'><font size='5'>PLANIFICACI&Oacute;N</font></td>
                   </tr>

                   <tr>
                       <td class='tititems' align='right' width='15%'>Programa:</td><td class='tablatxt' colspan='3'><?php print $filadatos[5]; ?></td>
                   </tr>
                   <tr>
                       <td class='tititems' align='right' width='15%'>Versi&oacute;n:</td><td class='tablatxt'  width='35%'><?php print $filadatos[3]; ?></td>
                       <td class='tititems' align='right' width='15%'>Ciudad:</td><td class='tablatxt'><?php print $filadatos[4]; ?></td>
                   </tr>
                   <tr>
                       <td class='tititems' align='right'>Materia:</td><td class='tablatxt' colspan='3'><?php print $filadatos[2]; ?></td>
                   </tr>
                   <tr>
                       <td class='tititems' align='right'>Universidad:</td><td class='tablatxt'><?php print $filadatos[1]; ?></td>
                   </tr>
            </table>
            <table class='contenido' width='100%'>
                <tr>
                    <td colspan="12" class="titmenu">
                        DOCENTES<a name='nuevo'>
                    </td>
                </tr>
                <tr>
                    <td class='tititems' width='275'>Nombre</td>
                    <td class='tititems' width='135'>Categor&iacute;a</td>
                    <td class='tititems' width='120'>Procedencia</td>
                    <td class='tititems' width='70'>Fecha Ini</td>
                    <td class='tititems' width='70'>Fecha Fin</td>
                    <td class='tititems' width='40'>Horas</td>
                    <td class='tititems' width='40'>Dias</td>
                    <td class='tititems' width='65' align='center'>Hon. hora <font size='1'>($)</font></td>
                    <td class='tititems' width='55' align='center'>Pasajes <font size='1'>($)</font></td>
                    <td class='tititems' width='65' align='center'>Viaticos dia <font size='1'>($)</font></td>
                    <td class='tititems' width='65' align='center'>Hosp. dia <font size='1'>($)</font></td>
                    <td class='tititems' width='55'>Acci&oacute;n</td>
                </tr>
                <?php
                    $col1="#eeeeee";
                    $col2="#ffffff";
                    $sql="select pmvd.*, rh.nombres, rh.apellidos, c.nombre from planmateriaversiondocente pmvd inner join recursohumano rh on rh.idrecursohumano=pmvd.idrecursohumano inner join categoria c on c.idcategoria=pmvd.idcategoria where idmateriaversion=".$_GET["ids"];
                    if (isset($_GET["idpr"]) && $_GET["mode"]=="e") {
                       $sql.=" and idplanmateriaversiondocente<>".$_GET["idpr"];
                    }

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
                    <td class='tabladettxt'>".$fila[14].", ".$fila[13]."</td>
                    <td class='tabladettxt'>".$fila[15]."</td>
                    <td class='tabladettxt'>".$fila[12]."</td>
                    <td  class='tabladettxt'>".fecha($fila[10],8)."</td>
                    <td  class='tabladettxt'>".fecha($fila[11],8)."</td>
                    <td  class='tabladettxt' align='center'>".$fila[1]."</td>
                    <td  class='tabladettxt' align='center'>".$fila[5]."</td>
                    <td  class='tabladettxt' align='center'>".$fila[2]."</td>
                    <td  class='tabladettxt' align='center'>".$fila[3]."</td>
                    <td  class='tabladettxt' align='center'>".$fila[4]."</td>
                    <td  class='tabladettxt' align='center'>".$fila[6]."</td>
                    <td class='tabladettxt'>";
                if ($editar && !$bloqueado) {
                    print "<a onclick='' href='asignar_docentes.php?mode=e&idpr=".$fila[0]."&ids=".$_GET["ids"]."&idv=".$_GET["idv"]."#editor'><img src=\"images/editar.png\" alt=\"Editar\" title=\"Editar\" width=\"18\" height=\"18\" border=\"0\" /></a>";
                }
                if ($eliminar && !$bloqueado) {
                print " <a onclick='if(confirm(\"Esta seguro que desea eliminar este docente?\")) { return true;} else { return false; }' href='asignar_docentes.php?mode=d&idpr=".$fila[0]."&ids=".$_GET["ids"]."&idv=".$_GET["idv"]."'><img src=\"images/delete.png\" alt=\"Eliminar\" title=\"Eliminar\" width=\"15\" height=\"15\" border=\"0\" /></a>";
                }
                print "</td>
                </tr>";
                    }

                ?>
                </table>
                <?php
               if (isset($_GET["idpr"])) {
                  $sql="select * from planmateriaversiondocente where idplanmateriaversiondocente=".$_GET["idpr"];
                  $resres=mysql_query($sql);
                  $filae=mysql_fetch_array($resres);
               }
             if (($insertar || ($editar && isset($_GET["idpr"]))) && !$bloqueado) {
              ?>
             <form method="POST" name="actualizar">
             <table>
                <tr>
                    <td width='275'>
                        <?php
                                   $consulta=mysql_query("SELECT idrecursohumano, concat(apellidos, ', ',nombres), codigo_UPB FROM recursohumano where recursoactivo=1 order by apellidos, nombres") or die(mysql_error());
                        echo "<select name='recursohumano' id='recursohumano'>\n";
                        echo "                             <option value='-1'>--- Elija ---</option>\n";
                        while($registro=mysql_fetch_row($consulta))
                        {
                            echo "                            <option value='".$registro[0]."'";
                            if (isset($_GET["mode"]) && $_GET["mode"]=="e" && $filae[8]==$registro[0]) { print "selected"; }
                          if (strlen($registro[1])>21) {
                              $registro[1]=substr($registro[1],0,21)."...";
                          }
                            print ">".$registro[1]." - ".$registro[2]."</option>\n";
                        }           
                        echo "                    </select>";
                        if (acceso($_SESSION['idRol'], 0,0,0,0,1,0)>=4) {
                        ?> <a href='#' onclick="window.open('rrhh_nuevos.php?t=popup', '_blank', 'status=0,menubar=0,location=0,scrollbars=0,resizable=0,width=600,height=420', 0);"><img border='0' src='images/508.png'></a>
                        <?php
                        }
                        ?>
                    </td>
                    <td width='140'>
                            <select name='categoria' id='categoria'>
                                    <option value='-1'>--- Elija ---</option>
                          <?php
                              $sql="select * from categoria order by idcategoria";
                              $res=mysql_query($sql);
                              while ($fila=mysql_fetch_array($res)) {
                                    print "                                     <option ";
                                    if (isset($_GET["mode"]) && $_GET["mode"]=="e" && $filae[7]==$fila[0]) { print "selected"; }
                                    print " value='$fila[0]'>$fila[1]</option>\n";
                              }
                          ?>
                          </select> <a href='#' onclick="window.open('categorias_nuevas.php?t=popup', '_blank', 'status=0,menubar=0,location=0,scrollbars=0,resizable=0,width=600,height=200', 0);"><img border='0' src='images/508.png'></a></td>
                    <td width='115'><input type="text" class='boton3' name="procedencia" size="18"  value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print $filae[12]; } ?>"  ></td>
                    <td width='70'><input type="text" class='boton3' name="inicio" size="9"  value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print fecha($filae[10],8); } ?>"  id="convo"/></td>
                    <td width='70'><input type="text" class='boton3' name="fin" size="9"  value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print fecha($filae[11],8); } ?>"  id="ela"/></td>
                    <td width='40'><input type="text" class='boton3' name="horas" size="2"  value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print $filae[1]; } ?>" /></td>
                    <td width='35'><input type="text" class='boton3' name="dias" size="2"  value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print $filae[5]; } ?>" /></td>
                    <td width='65' align='center'><input type="text" class='boton3' name="hhora" size="4"  value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print $filae[2]; } ?>" /></td>
                    <td width='55' align='center'><input type="text" class='boton3' name="pasajes" size="5"  value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print $filae[3]; } ?>" /></td>
                    <td width='65' align='center'><input type="text" class='boton3' name="viaticos" size="5"  value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print $filae[4]; } ?>" /></td>
                    <td width='65' align='center'><input type="text" class='boton3' name="hdia" size="5"  value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print $filae[6]; } ?>" /></td>
                    <td width='60'><input class="save" type="submit" name="guardar" value="">
                    <?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { ?>
                          <input class="cancel" type="button" name="cancelar" value="" onclick="window.location='asignar_docentes.php?idv=<?php print $_GET["idv"]; ?>&ids=<?php print $_GET["ids"]; ?>'">
                    <?php } ?>
                    <input type="hidden" name="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print "MM_edit"; } else { print "MM_insert"; }?>" value="actualizar">
                      <?php
                               print "<input type='hidden' name='ids' value='".$_GET["ids"]."'>";
                               print "<input type='hidden' name='idv' value='".$_GET["idv"]."'>";
                            ?>
                                </td>
                            </tr>
                      </table>
                    </form>
                    <?php
                    }
                    ?>
    </td>
    </tr>
    <tr>
        <td align='right'>
        <input class="exit" name="Salir" type="button" onClick="window.close();" value=''></td>
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