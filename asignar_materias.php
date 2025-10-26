<?php

include("valida.php");

if (isset($_POST["MM_insert"])) {
    if ($_POST["tipo"]=="-1" || $_POST["materia"]=="-1") {
          $error="<font color='red'><b>Debe seleccionar obligatoriamente la materia y el tipo</b></font>";
    } else if (strlen($error)<1){

         $sql="insert into materiaversion values (null, '".$_POST["universidad"]."', '".fecha($_POST["inicio"],99)."', '".fecha($_POST["fin"],99)."', '".$_POST["dias"]."', '".$_POST["horas"]."', 0, 0, 0, 0, ".$_GET["idv"].", '".$_POST["tipomateria"]."', '".$_POST["materia"]."')";
         mysqli_query($sql) or die(mysqli_error());

         header("Location: asignar_materias.php?idv=".$_POST["idv"]);
         exit;
    }
} else if (isset($_GET["mode"]) && $_GET["mode"]=="d") {

      $sql="delete from planmateriaversiondocente where idmateriaversion=".$_GET["ids"];
      mysqli_query($sql) or die(mysqli_error());
      $sql="delete from materiaversion where idmateriaversion=".$_GET["ids"];
      mysqli_query($sql) or die(mysqli_error());


      header("Location: asignar_materias.php?idv=".$_GET["idv"]);
      exit;
} else if (isset($_POST["MM_edit"])) {

    if ( $_POST["tipo"]=="-1" || $_POST["materia"]=="-1") {
          $error="<font color='red'><b>Deben seleccionarse obligatoriamente la materia y el tipo</b></font>";
    } else if (strlen($error)<1){
      $sql="update materiaversion set
                   universidad='".$_POST["universidad"]."',
                   inicioprogramado='".fecha($_POST["inicio"],99)."',
                   finprogramado='".fecha($_POST["fin"],99)."',
                   nrodiasplanificado='".$_POST["dias"]."',
                   horasprogramadas='".$_POST["horas"]."',
                   idtipomateria='".$_POST["tipomateria"]."',
                   idmateria='".$_POST["materia"]."' where idmateriaversion=".$_GET["ids"];
      mysqli_query($sql) or die(mysqli_error());


      header("Location: asignar_materias.php?idv=".$_GET["idv"]);
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
<script type='text/javascript' src='tabla.js'></script>
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

<table align="center" bgcolor="#ffffff" width="100%">
<tr>
    <td width="600">
            <table class='contenido' width='100%'>
                <tr>
                    <td colspan="8" class="titmenu">
                        ASIGNAR MATERIAS<a name='nuevo'>
                    </td>
                </tr>
                <tr>
                    <td class='tititems' width='60%'>Materia</td>
                    <td class='tititems' width='10%'>Tipo</td>
                    <td class='tititems' width='10%'>Universidad</td>
                    <td class='tititems' width='5%'>Inicio</td>
                    <td class='tititems' width='5%'>Fin</td>
                    <td class='tititems' width='5%'>Dias</td>
                    <td class='tititems' width='5%'>Horas</td>
                    <td class='tititems'>Acciones</td>
                </tr>
                <?php
                    $col1="#eeeeee";
                    $col2="#ffffff";
                    $sql="SELECT * FROM materiaversion mv inner join materia m on m.idmateria=mv.idmateria inner join tipomateria tm on tm.idtipomateria=mv.idtipomateria where idversion=".$_GET["idv"];
                    if (isset($_GET["ids"]) && $_GET["mode"]=="e") {
                       $sql.=" and idmateriaversion<>".$_GET["ids"];
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
                    <td class='tabladettxt'>".$fila[14]."</td>
                    <td class='tabladettxt'>".$fila[17]."</td>
                    <td class='tabladettxt'>".$fila[1]."</td>
                    <td class='tabladettxt'>".fecha($fila[2],99)."</td>
                    <td class='tabladettxt'>".fecha($fila[3],99)."</td>
                    <td class='tabladetnum'>".$fila[4]."</td>
                    <td class='tabladetnum'>".$fila[5]."</td>
                    <td class='tabladettxt'>";
                print "<a onclick='if(confirm(\"Esta seguro que desea eliminar esta materia?\\nAl eliminar una materia tambi�n se eliminan los docentes asignados a la misma.\")) { return true;} else { return false; }' href='asignar_materias.php?mode=d&ids=".$fila[0]."&idv=".$_GET["idv"]."'><img src=\"images/eliminar.png\" alt=\"Eliminar\" title=\"Eliminar\" width=\"18\" height=\"18\" border=\"0\" /></a>
                      <a onclick='' href='asignar_materias.php?mode=e&ids=".$fila[0]."&idv=".$_GET["idv"]."#editor'><img src=\"images/editar.png\" alt=\"Editar\" title=\"Editar\" width=\"18\" height=\"18\" border=\"0\" /></a>
                      <a onclick='' href='asignar_docentes.php?mode=e&ids=".$fila[0]."&idv=".$_GET["idv"]."'><img src=\"images/191.png\" alt=\"Asignar Docentes\" title=\"Asignar Docentes\" width=\"18\" height=\"18\" border=\"0\" /></a>";
                print "</td>
                </tr>";
                    }

               if (isset($_GET["ids"])) {
                  $sql="select * from materiaversion where idmateriaversion=".$_GET["ids"];
                  $resres=mysqli_query($sql);
                  $filares=mysqli_fetch_array($resres);
               }
                ?>

             <form method="POST" name="actualizar">
                <tr>
                    <td>
                    <select name='materia' id='materia' >
                           <option value='-1'>--- Elija ---</option>
                          <?php
                              if (isset($_GET["ids"])) {
                                  $sql="select * from materia m left join materiaversion mv on mv.idmateria=m.idmateria order by Nombre";
                              } else {
                                  $sql="select * from materia m left join materiaversion mv on mv.idmateria=m.idmateria and mv.idversion=".$_GET["idv"]." where mv.idversion is null order by Nombre";
                              }
                              $res=mysqli_query($sql);
                              while ($fila=mysqli_fetch_array($res)) {
                                    print "<option ";
                                    if (isset($_GET["mode"]) && $_GET["mode"]=="e" && $filares[12]==$fila[0]) { print "selected"; }
                                    print " value='$fila[0]'>$fila[1]</option>";
                              }
                          ?>
                          </select> <a href='#' onclick="window.open('materias_nuevas.php?t=popup', '_blank', 'status=0,menubar=0,location=0,scrollbars=0,resizable=0,width=600,height=350', 0);"><img src='images/508.png'></a>
                    </td>
                    <td>
                    <select name='tipomateria' id='tipomateria' >
                           <option value='-1'>--- Elija ---</option>
                          <?php
                              $sql="select * from tipomateria order by idtipomateria";
                              $res=mysqli_query($sql);
                              while ($fila=mysqli_fetch_array($res)) {
                                    print "<option ";
                                    if (isset($_GET["mode"]) && $_GET["mode"]=="e" && $filares[11]==$fila[0]) { print "selected"; }
                                    print " value='$fila[0]'>$fila[1]</option>";
                              }
                          ?>
                          </select>
                    </td><td>
                       <input type="text" name="universidad" size="10" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e" ) { print $filares[1]; } ?>">
                    </td>
                    <td>
                       <input type="text" name="inicio" size="8" id="convo" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e" ) { print fecha($filares[2],8); } ?>">
                    </td>
                    <td>
                       <input type="text" name="fin" size="8" id="ela" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e" ) { print fecha($filares[3],8); } ?>">
                    </td>
                    <td>
                       <input type="text" name="dias" size="2" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e" ) { print $filares[4]; } ?>">
                    </td>
                    <td>
                       <input type="text" name="horas" size="3" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e" ) { print $filares[5]; } ?>">
                    </td>
                    <td><input class="boton1" type="submit" name="guardar" size="60" value="Guardar">
                    <?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { ?>
                          <input class="boton1" type="button" name="cancelar" size="60" value="Cancelar" onclick="window.location='asignar_materias.php?idv=<?php print $_GET["idv"]; ?>'">
                    <?php } ?>
                          <input class="boton1" name="Cancelar" type="button" onClick="cargaContenido('equipo','<?php print $_GET["idv"]; ?>','tabla_materias');" value='Salir'/></td>
                        </tr>
                      </table>
                      <input type="hidden" name="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print "MM_edit"; } else { print "MM_insert"; } ?>" value="actualizar">
                      <?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") {
                               print "<input type='hidden' name='ids' value='".$_GET["ids"]."'>";
                            } else {
                               print "<input type='hidden' name='idv' value='".$_GET["idv"]."'>";
                            }?>
                    </form><a name='editor'> </a>
<?php
    print $error;
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