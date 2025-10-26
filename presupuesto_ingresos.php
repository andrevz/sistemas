<?php

include("valida.php");
$iversion=$_GET["idv"];
$actividad=14;
include("niveles_acceso.php");

$sql_blo="select estado from version where idversion=".$_GET["idv"];;
$res_blo=mysqli_query($sql_blo);
$fila_blo=mysqli_fetch_array($res_blo);
$bloqueado=($fila_blo[0]==0 ? false : true);

if (isset($_POST["MM_Edit"]) && ($editar || $insertar) && !$bloqueado) {

   $sql="select * from presupuestoingresos where idversion=".$_POST["idv"];
   $res=mysqli_query($sql);
   while ($fila=mysqli_fetch_array($res)) {
      $sql="update presupuestoingresos set alumnos='".$_POST["cantidad"][$fila[0]]."' where idpresupuestoingresos=".$fila[0];
      mysqli_query($sql) or die(mysqli_error());
   }
      header("Location: presupuesto_ingresos.php?idv=".$_POST["idv"]);
      exit;
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php print $nombresistema; ?></title>
<link href="<?php print $path; ?>estilos.css" rel="stylesheet" type="text/css" />
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
                    <td class='tititems' colspan='5' align='center'>PRESUPUESTO DE INGRESOS</td>
                </tr><tr>
                    <td class='tititems' width='45%' align='right'>Descuento</td>
                    <td class='tititems' width='25%' align='center'>Monto ($us)</td>
                    <td class='tititems' >Nro. Alumnos</td>
                </tr>
                <?php
                    $col1="#dedede";
                    $col2="#efefef";
                    $sql="select pi.*, v.colegiatura from presupuestoingresos pi inner join version v on v.idversion=pi.idversion where v.idversion=".$_GET["idv"];
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
                    <td class='tabladetnum'>".($fila[1]*100)."%</td>
                    <td class='tabladetnum'>".($fila[4]*$fila[1])."</td>
                    <td  class='tabladettxt'><input type='text' value='".$fila[2]."' name='cantidad[$fila[0]]' size='5'></td>
                </tr>";
                    }

                ?>
                <tr>
                    <td align='center' colspan='3'>
                    <?php
                    if (($editar || $insertar) && !$bloqueado) {
                    ?>
                    <input type='hidden' value='Editar' name='MM_Edit'><input type='hidden' value="<?php print $_GET["idv"]; ?>" name='idv'><input type='submit' name='Guardar' value='Guardar'>
                    <?php
                    }
                    ?>
                     <input type='button' name='Cancelar' value='Cancelar' onclick="window.close();"></td>
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