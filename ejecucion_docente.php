<?php

include("valida.php");
$iversion=$_GET["idv"];
$actividad=30;
include("niveles_acceso.php");

if (isset($_POST["MM_insert"]) && $insertar) {
    if ($_POST["recursohumano"]=="-1" or $_POST["horas"]=="" or is_null($_POST["horas"]) or $_POST["horas"]=="0") {
       $error="<font color='red'><b>Deben registrarse obligatoriamente el docente y las horas asignadas</b></font>";
    } else {

      $sql="insert into ejecucionmateriaversiondocente values (null, '".$_POST["horas"]."', '".$_POST["hhora"]."', '".$_POST["pasajes"]."', '".$_POST["viaticos"]."',
                   '".$_POST["dias"]."', '".$_POST["hdia"]."', '".$_POST["eval"]."', 0, 0, 0, 0, '', '".$_POST["ids"]."', '".$_POST["recursohumano"]."', '".$_POST["categoria"]."', 0, '".fecha($_POST["inicio"],99)."','".fecha($_POST["fin"],99)."', '".$_POST["procedencia"]."', '')";
      mysqli_query($sql) or die(mysqli_error());

      $sql_fechas="SELECT min(inicio), max(fin), sum(dias) 
                          FROM ejecucionmateriaversiondocente emvd inner join materiaversion mv on mv.idmateriaversion=emvd.idmateriaversion
                          where idversion=".$_POST["idv"];
      $res_fechas=mysqli_query($sql_fechas);
      $fila_fechas=mysqli_fetch_array($res_fechas);

      $sql_meses="select count(*) from (SELECT distinct year(inicio),month(inicio)  FROM ejecucionmateriaversiondocente emvd inner join materiaversion mv on mv.idmateriaversion=emvd.idmateriaversion
                          where idversion=".$_POST["idv"].") tt";
      $res_meses=mysqli_query($sql_meses);
      $fila_meses=mysqli_fetch_array($res_meses);

      $act_ver="update version set inicioejecutado='".$fila_fechas[0]."', finejecutado='".$fila_fechas[1]."', nrodiasejecutado='".$fila_fechas[2]."', nromesesejecutado='".$fila_meses[0]."' where idversion=".$_POST["idv"];
      mysqli_query($act_ver);

      header("Location: ejecucion_docente.php?idv=".$_POST["idv"]."&ids=".$_POST["ids"]);
      exit;
   }
} else if (isset($_GET["mode"]) && $_GET["mode"]=="d" && $eliminar) {

      $sql="delete from documentos where idejecucionmateriaversiondocente=".$_GET["idpr"];
      mysqli_query($sql) or die(mysqli_error());

      $sql="delete from checklist where idejecucionmateriaversiondocente=".$_GET["idpr"];
      mysqli_query($sql) or die(mysqli_error());

      $sql="delete from ejecucionmateriaversiondocente where idejecucionmateriaversiondocente=".$_GET["idpr"];
      mysqli_query($sql) or die(mysqli_error());

      header("Location: ejecucion_docente.php?idv=".$_GET["idv"]."&ids=".$_GET["ids"]);
      exit;
} else if (isset($_POST["MM_update"]) && $editar) {
    if ($_POST["recursohumano"]=="-1" or $_POST["horas"]=="" or is_null($_POST["horas"]) or $_POST["horas"]=="0") {
       $error="<font color='red'><b>Deben registrarse obligatoriamente el docente y las horas asignadas</b></font>";
    } else {

      $sql="update ejecucionmateriaversiondocente set horas='".$_POST["horas"]."', honorarioshora='".$_POST["hhora"]."', pasajes='".$_POST["pasajes"]."',
                   viaticosdia='".$_POST["viaticos"]."',
                   dias='".$_POST["dias"]."', hospedajedia='".$_POST["hdia"]."', evaluaciondocente='".$_POST["eval"]."',
                   idrecursohumano='".$_POST["recursohumano"]."', idcategoria='".$_POST["categoria"]."', procedencia='".$_POST["procedencia"]."', inicio='".fecha($_POST["inicio"],99)."', fin='".fecha($_POST["fin"],99)."' where idejecucionmateriaversiondocente=".$_POST["idem"];

      mysqli_query($sql) or die(mysqli_error());

      $sql_fechas="SELECT min(inicio), max(fin), sum(dias) 
                          FROM ejecucionmateriaversiondocente emvd inner join materiaversion mv on mv.idmateriaversion=emvd.idmateriaversion
                          where idversion=".$_POST["idv"];
      $res_fechas=mysqli_query($sql_fechas);
      $fila_fechas=mysqli_fetch_array($res_fechas);

      $sql_meses="select count(*) from (SELECT distinct year(inicio),month(inicio)  FROM ejecucionmateriaversiondocente emvd inner join materiaversion mv on mv.idmateriaversion=emvd.idmateriaversion
                          where idversion=".$_POST["idv"].") tt";
      $res_meses=mysqli_query($sql_meses);
      $fila_meses=mysqli_fetch_array($res_meses);


      $act_ver="update version set inicioejecutado='".$fila_fechas[0]."', finejecutado='".$fila_fechas[1]."', nrodiasejecutado='".$fila_fechas[2]."', nromesesejecutado='".$fila_meses[0]."'  where idversion=".$_POST["idv"];
      mysqli_query($act_ver);


      header("Location: ejecucion_docente.php?idv=".$_POST["idv"]."&ids=".$_POST["ids"]);
      exit;
   }
} 

   ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php print $nombresistema; ?></title>
<link href="<?php print $path; ?>estilos.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="<?php print $path; ?>theme/jquery.ui.all.css">
  <script src="<?php print $path; ?>js/jquery-1.7.1.min.js"></script>
  <script src="<?php print $path; ?>js/jquery.ui.core.js"></script>
  <script src="<?php print $path; ?>js/jquery.ui.widget.js"></script>
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
<?php print $error; ?>
<table align="center" bgcolor="#ffffff" width="100%">
<tr>
    <td width="600">
            <?php
                $sqldatos="SELECT idmateriaversion, universidad, m.nombre, gestion, c.nombre, p.nombre
                                  FROM materiaversion mv inner join materia m on m.idmateria=mv.idmateria
                                   inner join version v on v.idversion=mv.idversion
                                  inner join programa p on p.idprograma=v.idprograma
                                  inner join ciudad c on c.idciudad=v.ciudad
                                  where idmateriaversion=".$_GET["ids"];
                $resdatos=mysqli_query($sqldatos);
                $filadatos=mysqli_fetch_array($resdatos);
            ?>
            <table class='contenido' width='100%'>

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
                    <td colspan="13" class="titmenu">
                        SEGUIMIENTO A DOCENTES Y MATERIAS<a name='nuevo'>
                    </td>
                </tr>
                <tr>
                    <td class='tititems' width='250'>Nombre</td>
                    <td class='tititems' width='130'>Categor&iacute;a</td>
                    <td class='tititems' width='110'>Procedencia</td>
                    <td class='tititems' width='70'>Fecha Ini</td>
                    <td class='tititems' width='70'>Fecha Fin</td>
                    <td class='tititems' width='40'>Horas</td>
                    <td class='tititems' width='35'>Dias</td>
                    <td class='tititems' width='45'>Hon. hora</td>
                    <td class='tititems' width='55'>Pasajes</td>
                    <td class='tititems' width='60'>Viaticos dia</td>
                    <td class='tititems' width='45'>Hosp. dia</td>
                    <td class='tititems' width='47'>Eval.</td>
                    <td class='tititems'>Acciones</td>
                </tr>
                <?php
                    $col1="#eeeeee";
                    $col2="#ffffff";
                    $sql="select pmvd.*, rh.nombres, rh.apellidos, c.nombre from ejecucionmateriaversiondocente pmvd inner join recursohumano rh on rh.idrecursohumano=pmvd.idrecursohumano left join categoria c on c.idcategoria=pmvd.idcategoria where idmateriaversion=".$_GET["ids"];
                    if (isset($_GET["mode"]) && $_GET["mode"]=="e") {
                       $sql.=" and idejecucionmateriaversiondocente<>".$_GET["idem"];
                    }
                    $res=mysqli_query($sql);
                    $colactual="";
                    while ($fila=mysqli_fetch_array($res)) {
                          if ($colactual!=$col1) {
                             $colactual=$col1;
                          } else {
                             $colactual=$col2;
                          }
                          print "
                <tr bgcolor='$colactual'>
                    <td class='tabladettxt'>".$fila[22].", ".$fila[21]."</td>
                    <td class='tabladettxt'>".$fila[23]."</td>
                    <td class='tabladettxt'>".$fila[19]."</td>
                    <td  class='tabladettxt'>".fecha($fila[17],8)."</td>
                    <td  class='tabladettxt'>".fecha($fila[18],8)."</td>
                    <td  class='tabladettxt' align='center'>".$fila[1]."</td>
                    <td  class='tabladettxt' align='center'>".$fila[5]."</td>
                    <td  class='tabladettxt' align='center'>".$fila[2]."</td>
                    <td  class='tabladettxt' align='center'>".$fila[3]."</td>
                    <td  class='tabladettxt' align='center'>".$fila[4]."</td>
                    <td  class='tabladettxt' align='center'>".$fila[6]."</td>
                    <td  class='tabladettxt' align='center'>".$fila[7]."</td>
                    <td class='tabladettxt'>";
                if ($editar) {
                   print "<a onclick='' href='ejecucion_docente.php?mode=e&ids=".$fila[13]."&idv=".$_GET["idv"]."&idem=".$fila[0]."#editor'><img src=\"images/editar.png\" alt=\"Editar\" title=\"Editar\" width=\"20\" height=\"20\" border=\"0\" /></a>";
                }
                if ($eliminar) {
                   print "<a onclick='if(confirm(\"Esta seguro que desea eliminar este docente?\")) { return true;} else { return false; }' href='ejecucion_docente.php?mode=d&idpr=".$fila[0]."&ids=".$_GET["ids"]."&idv=".$_GET["idv"]."'><img src=\"images/delete.png\" alt=\"Eliminar\" title=\"Eliminar\" width=\"20\" height=\"20\" border=\"0\" /></a>";
                }
                if (acceso($_SESSION['idRol'], 0,0,0,0,38,0)>=2) {
                   print " <a href='checklistm.php?ids=".$fila[13]."&idv=".$_GET["idv"]."&idem=".$fila[0]."'><img src=\"images/check.png\" alt=\"Checklist\" title=\"Checklist\" height=\"20\" border=\"0\" /></a>";
                }
                if (acceso($_SESSION['idRol'], 0,0,0,0,39,0)>=2) {
                   print " <a onclick=\"window.open('documentos.php?idem=".$fila[0]."&t=popup', '_blank', 'status=0,menubar=0,location=0,scrollbars=0,resizable=0,width=700,height=250', 0);\" href='#'><img src=\"images/documentos.png\" alt=\"Documentos\" title=\"Documentos\" width=\"20\" border=\"0\" /></a>";
                }

                print "</td>
                </tr>";
                    }

                ?>
                </table>
<?php
    if ($insertar || ($editar && isset($_GET["idem"]))) {
?>
             <form method="POST" name="actualizar">
             <table width='100%'>
                <tr>
                    <td width='250'>
                        <?php
                    if (isset($_GET["mode"]) && $_GET["mode"]=="e") {
                       $sql="select pmvd.*, rh.nombres, rh.apellidos, c.nombre from ejecucionmateriaversiondocente pmvd inner join recursohumano rh on rh.idrecursohumano=pmvd.idrecursohumano left join categoria c on c.idcategoria=pmvd.idcategoria where idmateriaversion=".$_GET["ids"];
                       $sql.=" and idejecucionmateriaversiondocente=".$_GET["idem"];
                       $res=mysqli_query($sql);
                       $filae=mysqli_fetch_array($res);
                    }




                   $consulta=mysqli_query("SELECT idrecursohumano, concat(apellidos, ', ',nombres), codigo_UPB FROM recursohumano where recursoactivo=1 order by apellidos, nombres") or die(mysqli_error());

                   echo "<select name='recursohumano' id='recursohumano'>";
                   echo "<option value='-1'>--- Elija ---</option>";
                   while($registro=mysqli_fetch_row($consulta))
                   {
                       echo "<option ";
                       if (isset($_GET["mode"]) && $_GET["mode"]=="e" && $filae[14]==$registro[0]) { print "selected"; }
                          if (strlen($registro[1])>21) {
                              $registro[1]=substr($registro[1],0,21)."...";
                          }
                          print " value='".$registro[0]."'>".$registro[1]." - ".$registro[2]."</option>";
                   }           
                   echo "</select>";
                        if (acceso($_SESSION['idRol'], 0,0,0,0,1,0)>=4) {
                        ?>
                          <a href='#' onclick="window.open('rrhh_nuevos.php?t=popup', '_blank', 'status=0,menubar=0,location=0,scrollbars=0,resizable=0,width=600,height=420', 0);"><img border='0' src='images/508.png'></a>
                          <?php
                          }
                          ?>
                          </td>
                    <td width='130'><select name='categoria' id='categoria'>
                           <option value='-1'>--- Elija ---</option>
                          <?php
                              $sql="select * from categoria order by idcategoria";
                              $res=mysqli_query($sql);
                              while ($fila=mysqli_fetch_array($res)) {
                                    print "<option ";
                                    if (isset($_GET["mode"]) && $_GET["mode"]=="e" && $filae[15]==$fila[0]) { print "selected"; }
                                    print " value='$fila[0]'>$fila[1]</option>";
                              }
                          ?>
                          </select> <a href='#' onclick="window.open('categorias_nuevas.php?t=popup', '_blank', 'status=0,menubar=0,location=0,scrollbars=0,resizable=0,width=600,height=200', 0);"><img border='0' src='images/508.png'></a></td>
                    <td width='110'><input type="text" class='boton3' name="procedencia" size="18"  value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print $filae[19]; } ?>" ></td>
                    <td width='70'><input type="text" class='boton3' name="inicio" size="9"  value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print fecha($filae[17],8); } ?>"  id="convo"/></td>
                    <td width='70'><input type="text" class='boton3' name="fin" size="9"  value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print fecha($filae[18],8); } ?>"  id="ela"/></td>
                    <td width='40'><input type="text" class='boton3' name="horas" size="2"  value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print $filae[1]; } ?>" /></td>
                    <td width='35'><input type="text" class='boton3' name="dias" size="2"  value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print $filae[5]; } ?>" /></td>
                    <td width='45' align='center'><input type="text" class='boton3' name="hhora" size="5"  value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print $filae[2]; } ?>" /></td>
                    <td width='55' align='center'><input type="text" class='boton3' name="pasajes" size="5"  value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print $filae[3]; } ?>" /></td>
                    <td width='60' align='center'><input type="text" class='boton3' name="viaticos" size="5"  value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print $filae[4]; } ?>" /></td>
                    <td width='45' align='center'><input type="text" class='boton3' name="hdia" size="5"  value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print $filae[6]; } ?>" /></td>
                    <td width='47' align='center'><input type="text" class='boton3' name="eval" size="3"  value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print $filae[7]; } ?>" /></td>
                    <td ><input class="save" type="submit" name="guardar" size="60" value="">
                    <input type="hidden" name="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print "MM_update"; } else { print "MM_insert"; } ?>" value="actualizar">
                    <?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print "<input class='cancel' name='Cancelar' value='' type='button' onclick='window.location=\"ejecucion_docente.php?ids=".$_GET["ids"]."&idv=".$_GET["idv"]."\"'>"; } ?>
                        </tr>
                      <?php
                               print "<input type='hidden' name='ids' value='".$_GET["ids"]."'>";
                               print "<input type='hidden' name='idv' value='".$_GET["idv"]."'>";
                               if (isset($_GET["mode"]) && $_GET["mode"]=="e") {
                                  print "<input type='hidden' name='idem' value='".$filae[0]."'>";
                               }
                            ?>
                    </form>
                    <?php
                    }
                    ?>
                      </table>
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