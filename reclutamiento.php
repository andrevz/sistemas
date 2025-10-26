<?php

include("valida.php");
$iversion=$_GET["idv"];
$actividad=26;
include("niveles_acceso.php");

$sql_blo="select estado from version where idversion=".$_GET["idv"];;
$res_blo=mysqli_query($sql_blo);
$fila_blo=mysqli_fetch_array($res_blo);
$bloqueado=($fila_blo[0]<2 ? false : true);

$sql_nro="SELECT sum(alumnos) FROM presupuestoingresos where idversion=".$_GET["idv"];
$res_nro=mysqli_query($sql_nro);
$fila_nro=mysqli_fetch_array($res_nro);
$_POST["meta"]=$fila_nro[0];

if (isset($_POST["MM_insert"]) && $insertar && !$bloqueado) {
    if ($_POST["fecha"]=="") {
          $error="<font color='red'><b>Debe registrar obligatoriamente la fecha y el medio</b></font>";
    } else if (strlen($error)<1){

         $sql="insert into reclutamiento values (null, ".$_GET["idv"].", '".fecha($_POST["fecha"],99)."',  '".$_POST["meta"]."', '".$_POST["interesados"]."', '".$_POST["solicitud"]."', '".$_POST["seguros"]."', '".$_POST["matriculados"]."')";
         mysqli_query($sql) or die(mysqli_error());

         header("Location: reclutamiento.php?idv=".$_POST["idv"]);
         exit;
    }
} else if (isset($_GET["mode"]) && $_GET["mode"]=="d" && $eliminar && !$bloqueado) {

      $sql="delete from reclutamiento where idreclutamiento=".$_GET["ids"];
      mysqli_query($sql) or die(mysqli_error());
      header("Location: reclutamiento.php?idv=".$_GET["idv"]);
      exit;
} else if (isset($_POST["MM_edit"]) && $editar && !$bloqueado) {

    if ($_POST["fecha"]=="") {
          $error="<font color='red'><b>Deben registrar obligatoriamente la fecha y el medio</b></font>";
    } else if (strlen($error)<1){
      $sql="update reclutamiento set
                   fecha='".fecha($_POST["fecha"],99)."',
                   meta='".$_POST["meta"]."',
                   interesados='".$_POST["interesados"]."',
                   solicitud='".$_POST["solicitud"]."',
                   seguros='".$_POST["seguros"]."',
                   matriculados='".$_POST["matriculados"]."'
                   where idreclutamiento=".$_GET["ids"];
      mysqli_query($sql) or die(mysqli_error());


      header("Location: reclutamiento.php?idv=".$_GET["idv"]);
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
</head><table align="center" bgcolor="#ffffff" width="100%">
<tr>
    <td width="800">
    <table align="center" width="100%" cellspacing='2'>

            <tr><td width='100%' valign='top'>
          <table class='contenido' width='100%' id='equipo'>
                <tr>
                    <td colspan="8" class="titmenu">
                        RECLUTAMIENTO<a name='nuevo'>
                    </td>
                </tr>                <tr>
                    <td class='tititems' width='30' >Nro</td>
                    <td class='tititems' width='80' >Reporte a fecha</td>
                    <td class='tititems' width='65'>Meta</td>
                    <td class='tititems' width='80'>Interesados</td>
                    <td class='tititems' width='65'>Seguros</td>
                    <td class='tititems' width='80'>Matriculados</td>
                    <td class='tititems'></td>
                    <td></td>
                </tr>
                <?php
                    $col1="#eeeeee";
                    $col2="#ffffff";
                    if (isset($_GET["ids"])) {
                       $sql="SELECT * FROM reclutamiento where idversion=".$_GET["idv"]." and idreclutamiento<>".$_GET["ids"];
                    } else {
                       $sql="SELECT * FROM reclutamiento where idversion=".$_GET["idv"]." ";
                    }
                    $res=mysqli_query($sql);
                    $colactual="";
                    $i=0;
                    while ($fila=mysqli_fetch_array($res)) {
                          if ($colactual!=$col1) {
                             $colactual=$col1;
                          } else {
                             $colactual=$col2;
                          }
                          $i++;
                          print "
                          <tr bgcolor='$colactual'>
                              <td class='tabladettxt'>".$i."</td>
                              <td class='tabladettxt'>".fecha($fila[2],8)."</td>
                              <td class='tabladetnum'>".$fila[3]."</td>
                              <td class='tabladetnum'>".$fila[4]."</td>
<!--                              <td class='tabladetnum'>".$fila[5]."</td> -->
                              <td class='tabladetnum'>".$fila[6]."</td>
                              <td class='tabladetnum'>".$fila[7]."</td>
                              <td class='tabladettxt'>";
                if ($eliminar && !$bloqueado) {
                   print "<a onclick='if(confirm(\"Esta seguro que desea eliminar este registro?\")) { return true;} else { return false; }' href='reclutamiento.php?mode=d&ids=".$fila[0]."&idv=".$_GET["idv"]."'><img src=\"images/delete.png\" alt=\"Eliminar\" title=\"Eliminar\" width=\"18\" height=\"18\" border=\"0\" /></a>";
                }
                if ($editar && !$bloqueado) {
                   print "<a href='reclutamiento.php?mode=e&ids=".$fila[0]."&idv=".$_GET["idv"]."#editor'><img src=\"images/editar.png\" alt=\"Editar\" title=\"Editar\" width=\"18\" height=\"18\" border=\"0\" /></a>";
                }
                print "</td>
                          </tr>";
                    }

          if (isset($_GET["ids"])) {
                  $sql="select * from reclutamiento where idreclutamiento=".$_GET["ids"];
                  $resres=mysqli_query($sql);
                  $filares=mysqli_fetch_array($resres);
               }
                ?>
             </table>
             <?php
             if (($insertar || ($editar && isset($_GET["ids"]))) && !$bloqueado) {
             ?>
             <form method="POST" name="actualizar">
             <table>
                <tr>
                    <td width='30'>&nbsp;</td>
                    <td width='80'>
                       <input type="text" name="fecha" size="8" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e" ) { print fecha($filares[2],8); } else { print date("d-m-Y"); } ?>" id='convo'>
                    </td>
                    <td width='65'>
                       <input type="text" name="meta" size="5" value="<?php $sql_nro="SELECT sum(alumnos) FROM presupuestoingresos where idversion=".$_GET["idv"]; $res_nro=mysqli_query($sql_nro); $fila_nro=mysqli_fetch_array($res_nro); print $fila_nro[0]; ?>" disabled>
                    </td>
                    <td width='80'>
                       <input type="text" name="interesados" size="5" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e" ) { print $filares[4]; } ?>">
                    </td>
                    <!--<td width='65'>
                       <input type="text" name="solicitud" size="5" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e" ) { print $filares[5]; } ?>">
                    </td>-->
                    <td width='65'>
                       <input type="text" name="seguros" size="5" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e" ) { print $filares[6]; } ?>">
                    </td>
                    <td width='80'>
                       <input type="text" name="matriculados" size="5" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e" ) { print $filares[7]; } ?>">
                    </td>
                    <td><input class="save" type="submit" name="guardar" value="">
                    <?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { ?>
                          <input class="cancel" type="button" name="cancelar" size="60" value="" onclick="window.location='reclutamiento.php?idv=<?php print $_GET["idv"]; ?>'">
                    <?php } ?>
                         </td>
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
                    }
                    ?>

                    </table>

                    <?php print $error; ?>

                    </td></tr>

    </td>
    </tr><tr>
        <td align='right'>
        <input class="exit" name="Salir" type="button" onClick="window.close();" value=''></td>
        </td>
    </tr>
    </table>
</td>
</tr>
</table>
</td></tr></table>

</body>
</html>