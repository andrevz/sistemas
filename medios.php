<?php

include("valida.php");
$iversion=$_GET["idv"];
$actividad=16;
include("niveles_acceso.php");

$sql_blo="select estado from version where idversion=".$_GET["idv"];;
$res_blo=mysqli_query($sql_blo);
$fila_blo=mysqli_fetch_array($res_blo);
$bloqueado=($fila_blo[0]==0 ? false : true);

function act_presupuesto_medios() {
         $sql_ap="select sum(precio) from planmedios where idversion=".$_GET["idv"];
         $res_ap=mysqli_query($sql_ap) or die(mysqli_error());
         $fila_ap=mysqli_fetch_array($res_ap);

         $sql_bp="delete from presupuestogastos where idtiposgastos=4 and idversion=".$_GET["idv"];
         $res_bp=mysqli_query($sql_bp) or die(mysqli_error());
         $sql_up="insert into  presupuestogastos values (null, '".$fila_ap[0]."', 4, ".$_GET["idv"].", 1)";
         mysqli_query($sql_up) or die(mysqli_error());
}

if (isset($_POST["MM_insert"]) && $insertar && !$bloqueado) {
    if ($_POST["fecha"]=="0" || $_POST["medio"]=="") {
          $error="<font color='red'><b>Debe registrar obligatoriamente la fecha y el medio</b></font>";
    } else if (strlen($error)<1){

         $sql="insert into planmedios values (null, '".fecha($_POST["fecha"],99)."', ".$_GET["idv"].", '".$_POST["medio"]."', '".$_POST["especificaciones"]."', '".$_POST["precio"]."', '".$_POST["observaciones"]."', 0, 0, 0)";
         mysqli_query($sql) or die(mysqli_error());

//         act_presupuesto_medios();

         header("Location: medios.php?idv=".$_POST["idv"]);
         exit;
    }
} else if (isset($_GET["mode"]) && $_GET["mode"]=="d" && $eliminar && !$bloqueado) {

      $sql="delete from planmedios where idplanmedios=".$_GET["ids"];
      mysqli_query($sql) or die(mysqli_error());
//      act_presupuesto_medios();

      header("Location: medios.php?idv=".$_GET["idv"]);
      exit;
} else if (isset($_POST["MM_edit"]) && $editar && !$bloqueado) {

    if ($_POST["fecha"]=="0" || $_POST["medio"]=="") {
          $error="<font color='red'><b>Deben registrar obligatoriamente la fecha y el medio</b></font>";
    } else if (strlen($error)<1){
      $sql="update planmedios set
                   fecha='".fecha($_POST["fecha"],99)."',
                   idmedio='".$_POST["medio"]."',
                   especificaciones='".$_POST["especificaciones"]."',
                   precio='".$_POST["precio"]."',
                   observaciones='".$_POST["observaciones"]."' where idplanmedios=".$_GET["ids"];
      mysqli_query($sql) or die(mysqli_error());

//      act_presupuesto_medios();
      header("Location: medios.php?idv=".$_GET["idv"]);
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
</html><table align="center" bgcolor="#ffffff" width="100%">
<tr>

    <td width="100%">
    <table align="center" width="100%" cellspacing='2'>

            <tr><td width='100%' valign='top'>
          <table class='contenido' width='100%' id='equipo'>
                <tr>
                    <td colspan="7" class="titmenu">
                        PLAN DE MEDIOS<a name='nuevo'>
                    </td>
                </tr>                <tr>
                    <td class='tititems' width='30' >Nro</td>
                    <td class='tititems' width='80' >Fecha</td>
                    <td class='tititems' width='250' >Medio</td>
                    <td class='tititems' width='200'>Especificaciones</td>
                    <td class='tititems' width='80'>Precio<br>neto ($us)</td>
                    <td class='tititems' width='150'>Observaciones</td>
                    <td class='tititems'></td>
                    <td></td>
                </tr>
                <?php
                    $col1="#eeeeee";
                    $col2="#ffffff";
                    if (isset($_GET["ids"])) {
                       $sql="SELECT * FROM planmedios pm inner join medio m on m.idmedio=pm.idmedio where idversion=".$_GET["idv"]." and idplanmedios<>".$_GET["ids"];
                    } else {
                       $sql="SELECT * FROM planmedios pm inner join medio m on m.idmedio=pm.idmedio where idversion=".$_GET["idv"]." ";
                    }
                    $res=mysqli_query($sql);
                    $colactual="";
                    $i=0;
                    $tot_pres=0;
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
                              <td class='tabladettxt'>".fecha($fila[1],8)."</td>
                              <td class='tabladettxt'>".$fila[11]."</td>
                              <td class='tabladettxt'>".$fila[4]."</td>
                              <td class='tabladetnum'>".sprintf("%0.2f",$fila[5])."</td>
                              <td class='tabladettxt'>".$fila[6]."</td>
                              <td class='tabladettxt'>";
                if ($eliminar && !$bloqueado) {
                   print "<a onclick='if(confirm(\"Esta seguro que desea eliminar este registro?\")) { return true;} else { return false; }' href='medios.php?mode=d&ids=".$fila[0]."&idv=".$_GET["idv"]."'><img src=\"images/delete.png\" alt=\"Eliminar\" title=\"Eliminar\" width=\"18\" height=\"18\" border=\"0\" /></a>";
                }
                if ($editar && !$bloqueado) {
                   print "<a href='medios.php?mode=e&ids=".$fila[0]."&idv=".$_GET["idv"]."#editor'><img src=\"images/editar.png\" alt=\"Editar\" title=\"Editar\" width=\"18\" height=\"18\" border=\"0\" /></a>";
                }
                print "</td>
                          </tr>";
                          $tot_pres+=$fila[5];
                    }

          if (isset($_GET["ids"])) {
                  $sql="select * from planmedios where idplanmedios=".$_GET["ids"];
                  $resres=mysqli_query($sql);
                  $filares=mysqli_fetch_array($resres);
               }
                ?>

             <tr><td></td><td></td><td class='tabladetnum' colspan='2'>Total presupuesto asignado:</td><td class='tabladetnum'>
                    <?php
                    $sql_pres="SELECT sum(valor) FROM presupuestogastos where idtiposgastos=4 and idversion=".$_GET["idv"];
                    $res_pres=mysqli_query($sql_pres);
                    $fila_pres=mysqli_fetch_array($res_pres);
                    print sprintf("%0.2f",$fila_pres[0]);
                    ?>
                    </td></tr>
             <tr><td></td><td></td><td class='tabladetnum' colspan='2'>Saldo disponible:</td><td class='tabladetnum'>
                    <?php
                    print sprintf("%0.2f",$fila_pres[0]-$tot_pres);
                    ?>
                    </td></tr>

             </table>
             <?php
               if (($insertar || ($editar && isset($_GET["ids"]))) && !$bloqueado) {
             ?>
             <form method="POST" name="actualizar">
             <table>
                <tr>
                    <td width='30'>&nbsp;</td>
                    <td width='80'>
                       <input type="text" name="fecha" size="8" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e" ) { print fecha($filares[1],8); } else { print date("d-m-Y"); } ?>" id='convo'>
                    </td>
                    <td width='250'>
                       <select name='medio' id='medio'>
                                    <option value='-1'>--- Elija ---</option>
                          <?php
                              $sql="select * from medio";
                              $res=mysqli_query($sql);
                              while ($fila=mysqli_fetch_array($res)) {
                                    print "                                     <option ";
                                    if (isset($_GET["mode"]) && $_GET["mode"]=="e" && $filares[3]==$fila[0]) { print "selected"; }
                                    print " value='$fila[0]'>$fila[1]</option>\n";
                              }
                          ?>
                          </select>
                          <?php
                          if (acceso($_SESSION['idRol'], 0,0,0,0,3,0)>=4) {
                          ?>
                          <a href='#' onclick="window.open('medios_nuevos.php?t=popup', '_blank', 'status=0,menubar=0,location=0,scrollbars=0,resizable=0,width=600,height=200', 0);"><img border='0' src='images/508.png'></a>
                          <?php
                          }
                          ?>
                    </td>
                    <td width='200'>
                       <input type="text" name="especificaciones" size="15" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e" ) { print $filares[4]; } ?>">
                    </td>
                    <td width='80'>
                       <input type="text" name="precio" size="5" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e" ) { print $filares[5]; } ?>">
                    </td>
                    <td width='150'>
                       <input type="text" name="observaciones" size="15" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e" ) { print $filares[6]; } ?>">
                    </td>
                    <td><input class="save" type="submit" name="guardar" value="">
                    <?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { ?>
                          <input class="cancel" type="button" name="cancelar" size="60" value="" onclick="window.location='medios.php?idv=<?php print $_GET["idv"]; ?>'">
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

                    <?php
                    if (isset($error)) {
                       print $error;
                    } ?>

                    </td></tr><tr><td align='right'><a name='editor' > </a>
                    <input class="exit" name="Cancelar" type="button" onClick="window.close()" value=''>
                    </td></tr>

    </td>
    </tr>
    </table>
</td>
</tr>
</table>
</td></tr></table>
</body>
</html>