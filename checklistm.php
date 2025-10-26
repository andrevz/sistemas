<?php

include("valida.php");
$actividad=38;
include("niveles_acceso.php");

if (isset($_POST["MM_Edit"]) && ($editar || $insertar)) {

   $sql="select * from checklist where idejecucionmateriaversiondocente=".$_POST["idem"];
   $res=mysqli_query($sql);
   while ($fila=mysqli_fetch_array($res)) {
      $sql="update checklist set ejecutado='".($_POST["checklist"][$fila[0]]==1 ? "1" : "0")."', fecha='".fecha($_POST["fecha"][$fila[0]],99)."', comentarios='".$_POST["comentarios"][$fila[0]]."', responsable='".$_POST["responsable"][$fila[0]]."' where idchecklist=".$fila[0];
      mysqli_query($sql) or die(mysqli_error());
   }
   $sql="update ejecucionmateriaversiondocente set observaciones='".$_POST["observaciones"]."' where idejecucionmateriaversiondocente=".$_POST["idem"];
   mysqli_query($sql);

      ?>
      <script language='javascript'>alert("Datos actualizados");
      window.location="checklistm.php?idem=<?php print $_POST["idem"]; ?>&ids=<?php print $_POST["ids"]; ?>&idv=<?php print $_POST["idv"]; ?>";</script>
      <?php
      exit;
}

$sql_ver="select * from checklist where idejecucionmateriaversiondocente=".$_GET["idem"];
$res_ver=mysqli_query($sql_ver);
if (mysqli_num_rows($res_ver)<1) {

      $sqla="select idactividades, responsable from actividades where materia=1 order by orden";
      $resa=mysqli_query($sqla) or die(mysqli_error());
      while ($filaa=mysqli_fetch_array($resa)) {
            $sql="insert into checklist values (null, 0, '".$_GET["idem"]."', $filaa[0], 0, 0,'','$filaa[1]')";
            mysqli_query($sql) or die(mysqli_error());
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
    var dates = $( "<?php $sql="select a.idactividades
                                 from checklist ch inner join actividades a on a.idactividades=ch.idactividades
                                 where ch.idejecucionmateriaversiondocente=".$_GET["idem"]." order by orden";
                    $res=mysqli_query($sql);
                    $i=0;
                    while ($fila=mysqli_fetch_array($res)) {
                          if ($i>0) {
                             print ", ";
                          }
                          $i++;
                          print "#fech".$fila[0];
                    } ?>" ).datepicker({
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
</html>

<table align="center" bgcolor="#ffffff" width="100%">
<tr>
    <td width="800">
    <table align="center" width="100%" cellspacing='2'>
        <tr>
            <td class="bordes">
            <form name='guardar' method='post'>
            <table class='contenido' width='100%'>
                <tr>
                    <td class='tititems' colspan='5' align='center'>CHECKLIST</td>
                </tr><tr>
                    <td class='tititems' width='35' align='center'>Nro.</td>
                    <td class='tititems' width='250'>Actividad</td>
                    <td class='tititems' width='70'>Ejecutado</td>
                    <td class='tititems' width='85'>Fecha</td>
                    <td class='tititems' width='150'>Comentarios</td>
                    <td class='tititems' width='150'>Responsable</td>
                </tr>
                <?php
                    $col1="#dedede";
                    $col2="#efefef";
                    $sql="select ch.*, a.nombre, a.tipo, a.idactividades
                                 from checklist ch inner join actividades a on a.idactividades=ch.idactividades
                                 where ch.idejecucionmateriaversiondocente=".$_GET["idem"]." order by orden";
                    $res=mysqli_query($sql);
                    $colactual="";
                    $i=0;
                    while ($fila=mysqli_fetch_array($res)) {
                          $i++;
                          if ($tipot!=$fila["tipo"]) {
                             $tipot=$fila["tipo"];
                             print "<tr><td colspan='7'>".$fila["tipo"]."</td></tr>";
                         }
                         if ($colactual!=$col1) {
                             $colactual=$col1;
                          } else {
                             $colactual=$col2;
                          }
                          print "
                <tr bgcolor='$colactual'>
                    <td class='tabladetnum'>".$i."</td>
                    <td class='tabladettxt'>".$fila[8]."</td>
                    <td  class='tabladettxt'><input type='checkbox' value='1' ".($fila[4]==1 ? "checked" : "")." name='checklist[$fila[0]]'></td>
                    <td  class='tabladettxt'><input size='8' type='text' value='".fecha($fila[5],8)."'  name='fecha[$fila[0]]' id='fech".$fila["idactividades"]."'></td>
                    <td  class='tabladettxt'><input size='18' type='text' value='".$fila[6]."'  name='comentarios[$fila[0]]'></td>
                    <td  class='tabladettxt'><input size='18' type='text' value='".$fila[7]."'  name='responsable[$fila[0]]'></td>

                </tr>";
                    }

                ?>
                <tr>
                    <td colspan='6' valign="top">Observaciones: <textarea name='observaciones' cols='100' rows='2'><?php $sql1="select observaciones from ejecucionmateriaversiondocente
                                 where idejecucionmateriaversiondocente=".$_GET["idem"];
                    $res1=mysqli_query($sql1);
                    $fila1=mysqli_fetch_array($res1);
                    print $fila1[0]; ?></textarea></td>
                </tr>
                <tr>
                    <td align='center' colspan='6'><input type='hidden' value='Editar' name='MM_Edit'>
                    <input type='hidden' value="<?php print $_GET["ids"]; ?>" name='ids'>
                    <input type='hidden' value="<?php print $_GET["idv"]; ?>" name='idv'>
                    <input type='hidden' value="<?php print $_GET["idem"]; ?>" name='idem'>
                    <?php
                    if ($editar || $insertar) {
                    ?>
                    <input type='submit' name='Guardar' value='Guardar'>
                    <?php
                    }
                    ?>
                    <input type='reset' name='Cancelar' value='Salir' onclick="window.location='ejecucion_docente.php?ids=<?php print $_GET["ids"]; ?>&idv=<?php print $_GET["idv"]; ?>'"></td>
                </tr>
            </table>
            </form>


    </td>
    </tr>
    </table>
</td>
</tr>
</table>
</td>
</tr>
</table>

</body>
</html>