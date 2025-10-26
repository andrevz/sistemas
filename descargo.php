<?php

include("valida.php");

if (isset($_POST["MM_insert"])) {

    if ($_POST["fecha"]=="" or is_null($_POST["fecha"]) or $_POST["descripcion"]=="" or is_null($_POST["descripcion"]) or $_POST["monto"]=="" or is_null($_POST["monto"])) {
       $error="<font color='red'><b>Deben registrarse obligatoriamente la descripci&oacute;n, la fecha y el monto</b></font>";
    } else {

      $sql="insert into gastos values (null, '".$_POST["descripcion"]."', 0, '".$_POST["id"]."', 0, 0, 0, 0, 0, '".$_POST["moneda"]."', '".$_POST["factura"]."', '".fecha($_POST["fecha"],99)."', 'EF', 'PAGADO', 0, '".$_POST["anombrede"]."', '".$_POST["monto"]."', 0, '".$_POST["idrh"]."')";
      mysqli_query($sql) or die(mysqli_error());

      $sql_ver="SELECT ef.montosolicitado, sum(montopagado) FROM `gastos` g right join entregafondos ef on ef.identregafondos=g.identregafondos where identregafondos=".$_POST["id"]." group by g.identregafondos, ef.montosolicitado";
      $res_ver=mysqli_query($sql_ver);
      $fila_ver=mysqli_fetch_array($res_ver);
      if ($fila_ver[0]==$fila_ver[1]) {
         $sql="update gastos set saldopendiente=0 where identregafondos=".$_POST["id"];
         mysqli_query($sql);
      }

      header("Location: descargo.php?id=".$_POST["id"]);
      exit;
   }
} else if (isset($_GET["mode"]) && $_GET["mode"]=="d") {

      $sql="delete from gastos where idgastos=".$_GET["idrp"];
      mysqli_query($sql) or die(mysqli_error());

      header("Location: descargo.php?id=".$_GET["id"]);
      exit;
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
//      defaultDate: "+1w",
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
            <?php
                $sqldatos="SELECT p.*, r.apellidos, r.nombres
                                  FROM entregafondos p inner join recursohumano r on r.idrecursohumano=p.idrecursohumano
                                  where p.identregafondos=".$_GET["id"];
                $resdatos=mysqli_query($sqldatos);
                $filadatos=mysqli_fetch_array($resdatos);
            ?>
            <table class='contenido' width='100%'>
                   <tr>
                       <td class='tititems' align='right' width='40%'>Descripci&oacute;n:</td><td class='tablatxt'><?php print $filadatos[1]; ?></td>
                   </tr>
                   <tr>
                       <td class='tititems' align='right'>Fecha de solicitud:</td><td class='tablatxt'><?php print fecha($filadatos[2],8); ?></td>
                   </tr>
                   <tr>
                       <td class='tititems' align='right'>Monto solicitado:</td><td class='tablatxt'><?php print $filadatos[10]." ".$filadatos[5]; ?></td>
                   </tr>
                   <tr>
                       <td class='tititems' align='right'>Entregado a:</td><td class='tablatxt'><?php print $filadatos[11].", ".$filadatos[12]; ?></td>
                   </tr>
            </table>
            <table class='contenido' width='100%'>
                <tr>
                    <td colspan="5" class="titmenu">
                              DESCARGOS<a name='nuevo'>
                    </td>
                </tr>                <tr>
                    <td class='tititems' width='50%'>Descripci&oacute;n - Beneficiario</td><td class='tititems' width='10%'>Fecha</td><td class='tititems' width='15%'>Monto</td><td class='tititems'>Factura</td><td class='tititems'>Acciones</td>
                </tr>
                <?php
                    $col1="#eeeeee";
                    $col2="#ffffff";
                    $sql="select * from GASTOS where identregafondos=".$_GET["id"]." order by fecharecepcion";
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
                    <td class='tabladettxt'>".$fila[1]." - ".$fila[15]."</td><td class='tabladettxt'>".fecha($fila[11],8)."</td><td  class='tabladetnum'>".$fila[9].".".$fila[16]."</td><td  class='tabladetnum'>".$fila[10]."</td><td class='tabladettxt'>";
                print "<a onclick='if(confirm(\"Esta seguro que desea eliminar este descargo?\")) { return true;} else { return false; }' href='descargo.php?mode=d&idrp=".$fila[0]."&id=".$_GET["id"]."'><img src=\"images/eliminar.png\" alt=\"Eliminar\" title=\"Eliminar\" width=\"15\" height=\"15\" border=\"0\" /></a>";
                print "</td>
                </tr>";
                    }

                ?>

             <form method="POST" name="actualizar">
                <tr>
                    <td><input type="text" class='boton3' name="descripcion" size="70" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print $filae[1]; } ?>"><br>
                    <input type="text" class='boton3' name="anombrede" size="70" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print $filae[15]; } ?>"></td>
                    <td><input type="text" class='boton3' name="fecha" id='pres' size="9"  maxlength="10" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print $filae[6]; } ?>" /></td>
                    <td>
                    <select name='moneda'>
                                      <option <?php if (isset($_GET["mode"]) && $_GET["mode"]=="e" && $fila_e[4]=="Bs") { print "selected"; } ?>>Bs</option>
                                      <option <?php if (isset($_GET["mode"]) && $_GET["mode"]=="e" && $fila_e[4]=="\$us") { print "selected"; } ?>>$us</option>

                          </select><input type="text" class='boton3' name="monto" size="6"  maxlength="6" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print $filae[6]; } ?>" /></td><td>
                    <input type="text" class='boton3' name="factura" size="10"  maxlength="15" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print $filae[6]; } ?>" /></td>
                    <td><input class="boton1" type="submit" name="guardar" size="60" value="Guardar">
                          <input class="boton1" name="Cancelar" type="button" onClick="cargaContenido('detallesolicitudes','<?php print $_GET["id"]; ?>','tabla_solicitudes');" value='Cancelar'/></td>
                        </tr>
                      </table>
                      <input type="hidden" name="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print "MM_edit"; } else { print "MM_insert"; } ?>" value="actualizar">
                      <?php
                               print "<input type='hidden' name='id' value='".$_GET["id"]."'><input type='hidden' name='idrh' value='".$filadatos[9]."'>";
                            ?>
                    </form>
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