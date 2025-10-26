<?php

include("valida.php");
$iversion=$_GET["idv"];
$actividad=25;
include("niveles_acceso.php");

$sql_blo="select estado from version where idversion=".$_GET["idv"];;
$res_blo=mysqli_query($sql_blo);
$fila_blo=mysqli_fetch_array($res_blo);
$bloqueado=($fila_blo[0]<2 ? false : true);

if (isset($_POST["MM_edit"]) && ($editar || $insertar) && !$bloqueado) {

     $sql="update planmedios set
                   ejecutado='".$_POST["ejecutado"]."',
                   fechaejecutado='".fecha($_POST["fechaejecutado"],99)."',
                   montoejecutado='".$_POST["montoejecutado"]."',
                   observaciones='".$_POST["observaciones"]."' where idplanmedios=".$_GET["ids"];
      mysqli_query($sql) ;


/*      $sql_ap="select uu.anio, uu.mes, monto from
                      (SELECT year(fechaejecutado) as anio, month(fechaejecutado) as mes, sum(montoejecutado) as monto
                              FROM planmedios where idversion=".$_GET["idv"]."
                              group by year(fechaejecutado), month(fechaejecutado)) as tt right join
                      (SELECT DISTINCT year( inicio ) as anio , month( inicio ) as mes
                              FROM ejecucionmateriaversiondocente emvd
                              INNER JOIN materiaversion mv ON mv.idmateriaversion = emvd.idmateriaversion
                              WHERE idversion =".$_GET["idv"].") as uu on tt.anio=uu.anio and tt.mes=uu.mes";
         $res_ap=mysqli_query($sql_ap) or die(mysqli_error());

         $sql_bp="delete from ejecuciongastos where idtiposgastos=4 and idversion=".$_GET["idv"];
         $res_bp=mysqli_query($sql_bp) or die(mysqli_error());
         $mes=0;
                  print $sql_ap;
         while ($fila_ap=mysqli_fetch_array($res_ap)) {
               $mes++;
               if ($fila_ap[2]>0) {
                  $sql_up="insert into ejecuciongastos values (null, $mes, '".$fila_ap[2]."', 4, ".$_GET["idv"].")";
                  mysqli_query($sql_up) or die(mysqli_error());
               }
         }
                exit; */

      header("Location: medios_ejec.php?idv=".$_GET["idv"]);
      exit;
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

<table align="center" bgcolor="#ffffff" width="100%">
<tr>
    <td width="800">
    <table align="center" width="100%" cellspacing='2'>

            <tr><td width='100%' valign='top'>
          <table class='contenido' width='100%' id='equipo'>
                <tr>
                    <td colspan="10" class="titmenu">
                        PLAN DE MEDIOS<a name='nuevo'>
                    </td>
                </tr>                <tr>
                    <td class='tititems' width='28' >Nro</td>
                    <td class='tititems' width='80' >Fecha</td>
                    <td class='tititems' width='130' >Medio</td>
                    <td class='tititems' width='115'>Especificaciones</td>
                    <td class='tititems' width='60'>Presup.</td>
                    <td class='tititems' width='70'>Ejecutado</td>
                    <td class='tititems' width='80'>Fecha</td>
                    <td class='tititems' width='60'>Gasto</td>
                    <td class='tititems' width='180'>Observaciones</td>
                    <td class='tititems'></td>
                    <td></td>
                </tr>
                <?php
                    $col1="#eeeeee";
                    $col2="#ffffff";
                    if (isset($_GET["ids"])) {
                       $sql="SELECT * FROM planmedios pm inner join medio m on m.idmedio=pm.idmedio where idversion=".$_GET["idv"]." and idplanmedios<>".$_GET["ids"]." order by fecha desc";
                    } else {
                       $sql="SELECT * FROM planmedios pm inner join medio m on m.idmedio=pm.idmedio where idversion=".$_GET["idv"]." order by fecha desc";
                    }
                    $res=mysqli_query($sql);
                    $colactual="";
                    $i=0;
                    $tot_pres=0;
                    $tot_gasto=0;
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
                              <td class='tabladettxt'>".($fila[7]==1 ? "S&iacute;" : "No")."</td>
                              <td class='tabladettxt'>".fecha($fila[8],8)."</td>
                              <td class='tabladetnum'>".sprintf("%0.2f",$fila[9])."</td>
                              <td class='tabladettxt'>".$fila[6]."</td>
                              <td class='tabladettxt'>";
                if (($editar || $insertar) && !$bloqueado) {
                   print "<a href='medios_ejec.php?mode=e&ids=".$fila[0]."&idv=".$_GET["idv"]."#editor'><img src=\"images/editar.png\" alt=\"Editar\" title=\"Editar\" width=\"18\" height=\"18\" border=\"0\" /></a>";
                }
                print "</td>
                          </tr>";
                          $tot_pres+=$fila[5];
                          $tot_gasto+=$fila[9];
                    }
                                             ?>
             <tr><td></td><td></td><td class='tabladetnum' colspan='2'>Totales:</td><td class='tabladetnum'>
                    <?php
                    print sprintf("%0.2f",$tot_pres);
                    ?>
                    </td><td></td><td></td><td class='tabladetnum'><?php print sprintf("%0.2f",$tot_gasto); ?></td></tr>
             <tr><td></td><td></td><td class='tabladetnum' colspan='2'>Total presupuesto asignado:</td><td class='tabladetnum'>
                    <?php
                    $sql_pres="SELECT sum(valor) FROM presupuestogastos where idtiposgastos=4 and idversion=".$_GET["idv"];
                    $res_pres=mysqli_query($sql_pres);
                    $fila_pres=mysqli_fetch_array($res_pres);
                    print sprintf("%0.2f",$fila_pres[0]);
                    ?>
                    </td><td></td><td></td><td class='tabladetnum'><?php print sprintf("%0.2f",$fila_pres[0]); ?></td></tr>
             <tr><td></td><td></td><td class='tabladetnum' colspan='2'>Saldo disponible:</td><td class='tabladetnum'>
                    <?php
                    $saldo_pres=$fila_pres[0]-$tot_pres;
                    $saldo_ejec=$fila_pres[0]-$tot_gasto;
                    if ($saldo_pres<0) {
                    print "<font color='red'>";
                    }
                    print sprintf("%0.2f",$saldo_pres);
                    if ($saldo_pres<0) {
                    print "</font>";
                    }
                    ?>
                    </td><td></td><td></td><td class='tabladetnum'><?php
                    if ($saldo_ejec<0) {
                    print "<font color='red'>";
                    }
                     print sprintf("%0.2f",$saldo_ejec); 
                    if ($saldo_ejec<0) {
                    print "</font>";
                    }
                    ?></td></tr>
                    <?php
          if (isset($_GET["ids"])) {
                  $sql="select * from planmedios pm inner join medio m on m.idmedio=pm.idmedio  where idplanmedios=".$_GET["ids"];
                  $resres=mysqli_query($sql);
                  $filares=mysqli_fetch_array($resres);
               }
                ?>
             </table>

             <?php
             if (isset($_GET["mode"]) && $_GET["mode"]=="e" ) {
             ?>
             <form method="POST" name="actualizar">
             <table class='contenido'>
                <tr>
                    <td width='28'>&nbsp;</td>
                    <td width='80' class='tabladettxt'>
                       <?php if (isset($_GET["mode"]) && $_GET["mode"]=="e" ) { print fecha($filares[1],8); }  ?>
                    </td>
                    <td width='130' class='tabladettxt'>
                       <?php if (isset($_GET["mode"]) && $_GET["mode"]=="e" ) { print $filares[11]; } ?>
                    </td>
                    <td width='115' class='tabladettxt'>
                       <?php if (isset($_GET["mode"]) && $_GET["mode"]=="e" ) { print $filares[4]; } ?>
                    </td>
                    <td width='60' class='tabladetnum'>
                       <?php if (isset($_GET["mode"]) && $_GET["mode"]=="e" ) { print sprintf("%0.2f",$filares[5]); } ?>
                    </td>
                    <td width='70'>
                       <select name="ejecutado" >
                               <option <?php if (isset($_GET["mode"]) && $_GET["mode"]=="e"  && $filares[7]==1) { print " selected "; } ?> value='1'>Si</option>
                               <option <?php if (isset($_GET["mode"]) && $_GET["mode"]=="e"  && $filares[7]==0) { print " selected "; } ?> value='0'>No</option>
                       </select>
                    </td>
                    <td width='80'>
                       <input type="text" name="fechaejecutado" size="8" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e" ) { print fecha($filares[8],8); } ?>" id='pres'>
                    </td>
                    <td width='60'>
                       <input type="text" name="montoejecutado" size="4" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e" ) { print $filares[9]; } ?>">
                    </td>
                    <td width='180'>
                       <input type="text" name="observaciones" size="22" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e" ) { print $filares[6]; } ?>">
                    </td>
                    <td><input class="save" type="submit" name="guardar" value="">
                    <?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { ?>
                          <input class="cancel" type="button" name="cancelar" size="60" value="" onclick="window.location='medios_ejec.php?idv=<?php print $_GET["idv"]; ?>'">
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

                    <?php }
                    ?>
                    </table>

                    <?php print $error; ?>

                    </td></tr>

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
</td></tr></table>

</body>
</html>