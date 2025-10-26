<?php

include("valida.php");
$iversion=$_GET["idv"];
$actividad=24;
include("niveles_acceso.php");

$sql_blo="select estado from version where idversion=".$_GET["idv"];;
$res_blo=mysqli_query($sql_blo);
$fila_blo=mysqli_fetch_array($res_blo);
$bloqueado=($fila_blo[0]<2 ? false : true);


if (isset($_POST["MM_insert"]) && $insertar && !$bloqueado) {
    if ($_POST["tipo"]=="-1" || $_POST["materia"]=="-1") {
          $error="<font color='red'><b>Debe registrar obligatoriamente el numero de mes y el valor</b></font>";
    } else if (strlen($error)<1){

         $sql="insert into ejecucioningresos values (null, '".$_POST["nromes"]."', '".$_POST["valor"]."', '".$_POST["tipoingresos"]."', ".$_GET["idv"].")";
         mysqli_query($sql) or die(mysqli_error());

         header("Location: ejecucion_ingresos.php?idv=".$_POST["idv"]);
         exit;
    }
} else if (isset($_GET["mode"]) && $_GET["mode"]=="d" && $eliminar && !$bloqueado) {

      $sql="delete from ejecucioningresos where idejecucioningresos=".$_GET["ids"];
      mysqli_query($sql) or die(mysqli_error());
      header("Location: ejecucion_ingresos.php?idv=".$_GET["idv"]);
      exit;
} else if (isset($_POST["MM_edit"]) && $editar && !$bloqueado) {

    if ( $_POST["tipoingresos"]=="-1" || $_POST["valor"]=="") {
          $error="<font color='red'><b>Deben registrar obligatoriamente el numero de mes y el valor</b></font>";
    } else if (strlen($error)<1){
      $sql="update ejecucioningresos set
                   nromes='".$_POST["nromes"]."',
                   idtiposingresos='".$_POST["tipoingresos"]."',
                   monto='".$_POST["valor"]."' where idejecucioningresos=".$_GET["ids"];
      mysqli_query($sql) or die(mysqli_error());


      header("Location: ejecucion_ingresos.php?idv=".$_GET["idv"]);
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

<table align="center" bgcolor="#ffffff" width="100%">
<tr>

    <td width="800">
    <table align="center" width="100%" cellspacing='2'>

            <tr><td width='100%' valign='top'>
          <table class='contenido' width='100%' id='equipo'>
                <tr>
                    <td colspan="7" class="titmenu">
                        EJEUCI&Oacute;N DE INGRESOS<a name='nuevo'>
                    </td>
                </tr>                <tr>
                    <td class='tititems' width='85' >Nro. Mes</td>
                    <td class='tititems' width='230' >Concepto</td>
                    <td class='tititems' width='90' align='center'>Monto</td>
                    <td class='tititems'></td>
                </tr>
                <?php
                    $col1="#eeeeee";
                    $col2="#ffffff";
                    if (isset($_GET["ids"])) {
                       $sql="SELECT * FROM ejecucioningresos pg inner join tiposingresos tg on tg.idtiposingresos=pg.idtiposingresos where idversion=".$_GET["idv"]." and idejecucioningresos<>".$_GET["ids"]." order by nromes";
                    } else {
                       $sql="SELECT * FROM ejecucioningresos pg inner join tiposingresos tg on tg.idtiposingresos=pg.idtiposingresos where idversion=".$_GET["idv"]." order by nromes";
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
                              <td class='tabladettxt'>".$fila[1]."</td>
                              <td class='tabladettxt'>".$fila[6]."</td>
                              <td class='tabladetnum'>".$fila[2]."</td>
                              <td class='tabladettxt'>";
                if ($eliminar && !$bloqueado) {
                    print "<a onclick='if(confirm(\"Esta seguro que desea eliminar este ingreso?\")) { return true;} else { return false; }' href='ejecucion_ingresos.php?mode=d&ids=".$fila[0]."&idv=".$_GET["idv"]."'><img src=\"images/delete.png\" alt=\"Eliminar\" title=\"Eliminar\" width=\"18\" height=\"18\" border=\"0\" /></a>";
                }
                if ($editar && !$bloqueado) {
                    print "<a href='ejecucion_ingresos.php?mode=e&ids=".$fila[0]."&idv=".$_GET["idv"]."#editor'><img src=\"images/editar.png\" alt=\"Editar\" title=\"Editar\" width=\"18\" height=\"18\" border=\"0\" /></a>";
                }
                print "</td>
                          </tr>";
                    }

          if (isset($_GET["ids"])) {
                  $sql="select * from ejecucioningresos where idejecucioningresos=".$_GET["ids"];
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
                    <td width='85'>
                        <select name='nromes' id='nromes' >
                           <option value='-1'>--- Elija ---</option>
                          <?php
                              $sql="select nromeses from version where idversion=".$_GET["idv"];
                              $res=mysqli_query($sql);
                              $fila=mysqli_fetch_array($res);
                              for ($i=1;$i<=$fila[0];$i++) {
                                    print "<option ";
                                    if (isset($_GET["mode"]) && $_GET["mode"]=="e" && $filares[1]==$i) { print "selected"; }
                                    print " value='$i'>$i</option>";
                              }
                          ?>
                          </select>
                    </td>
                    <td width='230'>
                    <select name='tipoingresos' id='tipoingresos' >
                           <option value='-1'>--- Elija ---</option>
                          <?php
                              $sql="select * from tiposingresos tg order by descripcion";
                              $res=mysqli_query($sql);
                              while ($fila=mysqli_fetch_array($res)) {
                                    print "<option ";
                                    if (isset($_GET["mode"]) && $_GET["mode"]=="e" && $filares[3]==$fila[0]) { print "selected"; }
                                    print " value='$fila[0]'>$fila[1]</option>";
                              }
                          ?>
                          </select> <!--<a href='#' onclick="window.open('materias_nuevas.php?t=popup', '_blank', 'status=0,menubar=0,location=0,scrollbars=0,resizable=0,width=500,height=250', 0);"><img border='0' src='images/508.png'></a>-->
                    </td>
                    <td width='90'>
                       <input type="text" name="valor" size="8" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e" ) { print $filares[2]; } ?>">
                    </td>
                    <td><input class="save" type="submit" name="guardar" size="60" value="">
                    <?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { ?>
                          <input class="cancel" type="button" name="cancelar" size="60" value="" onclick="window.location='ejecucion_ingresos.php?idv=<?php print $_GET["idv"]; ?>'">
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