<?php

include("valida.php");
$iversion=$_GET["idv"];
$actividad=15;
include("niveles_acceso.php");

$sql_blo="select estado from version where idversion=".$_GET["idv"];;
$res_blo=mysqli_query($sql_blo);
$fila_blo=mysqli_fetch_array($res_blo);
$bloqueado=($fila_blo[0]==0 ? false : true);

if (isset($_POST["MM_insert"]) && $insertar && !$bloqueado) {
    if ($_POST["tipo"]=="-1" || $_POST["materia"]=="-1") {
          $error="<font color='red'><b>Debe registrar obligatoriamente el costo y el valor</b></font>";
    } else if (strlen($error)<1){

         $sql="insert into presupuestogastos values (null, '".$_POST["valor"]."', '".$_POST["tiposgastos"]."', ".$_GET["idv"].", '".$_POST["aplicacion"]."', '".$_POST["clasificacion"]."')";
         mysqli_query($sql) or die(mysqli_error());

         header("Location: presupuesto_gastos.php?idv=".$_POST["idv"]);
         exit;
    }
} else if (isset($_GET["mode"]) && $_GET["mode"]=="d" && $eliminar && !$bloqueado) {

      $sql="delete from presupuestogastos where idpresupuestogastos=".$_GET["ids"];
      mysqli_query($sql) or die(mysqli_error());
      header("Location: presupuesto_gastos.php?idv=".$_GET["idv"]);
      exit;
} else if (isset($_POST["MM_edit"]) && $editar && !$bloqueado) {

    if ( $_POST["tipogastos"]=="-1" || $_POST["valor"]=="") {
          $error="<font color='red'><b>Deben registrar obligatoriamente el costo y el valor</b></font>";
    } else if (strlen($error)<1){
      $sql="update presupuestogastos set
                   idtiposgastos='".$_POST["tiposgastos"]."',
                   valor='".$_POST["valor"]."', idaplicacion='".$_POST["aplicacion"]."', idclasificacion='".$_POST["clasificacion"]."' where idpresupuestogastos=".$_GET["ids"];
      mysqli_query($sql) or die(mysqli_error());


      header("Location: presupuesto_gastos.php?idv=".$_GET["idv"]);
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
</html><table align="center" bgcolor="#ffffff" width="100%">
<tr>
    <td width="800">
    <table align="center" width="100%" cellspacing='2'>

            <tr><td width='100%' valign='top'>
          <table class='contenido' width='100%' id='equipo'>
                <tr>
                    <td colspan="7" class="titmenu">
                        PRESUPUESTO DE COSTOS<a name='nuevo'>
                    </td>
                </tr>                <tr>
                    <td class='tititems' width='4%' >Nro</td>
                    <td class='tititems' width='29%' >Costo</td>
                    <td class='tititems' width='26%' >Tipo</td>
                    <td class='tititems' width='22%' >Presupuesto</td>
                    <td class='tititems' width='10%'>Monto ($us)</td>
                    <td></td>
                </tr>
                <?php
                    $col1="#eeeeee";
                    $col2="#ffffff";
                    if (isset($_GET["ids"])) {
                       $sql="SELECT pg.idpresupuestogastos, pg.valor, tg.nombre, a.descripcion, tg.idtiposgastos, cg.nombre, cg.idclasificacion
                                    FROM presupuestogastos pg inner join tiposgastos tg on tg.idtiposgastos=pg.idtiposgastos
                                         inner join aplicacion a on a.idaplicacion=pg.idaplicacion
                                         inner join clasificaciongastos cg on cg.idclasificacion=pg.idclasificacion
                                    where idversion=".$_GET["idv"]." and idpresupuestogastos<>".$_GET["ids"];
                    } else {
                       $sql="SELECT pg.idpresupuestogastos, pg.valor, tg.nombre, a.descripcion, tg.idtiposgastos, cg.nombre
                                    FROM presupuestogastos pg inner join tiposgastos tg on tg.idtiposgastos=pg.idtiposgastos
                                    inner join aplicacion a on a.idaplicacion=pg.idaplicacion
                                    inner join clasificaciongastos cg on cg.idclasificacion=pg.idclasificacion
                                    where idversion=".$_GET["idv"]." ";
                    }
                    $res=mysqli_query($sql);
                    $colactual="";
                    $i=0;
                    $total=0;
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
                              <td class='tabladettxt'>".$fila[2]."</td>
                              <td class='tabladettxt'>".$fila[3]."</td>
                              <td class='tabladettxt'>".$fila[5]."</td>
                              <td class='tabladetnum'>".sprintf("%0.2f",$fila[1])."</td>
                              <td class='tabladetnum'>";
                          $total+=$fila[1];
//                if ($fila[4]!=4) {
                     if ($eliminar && !$bloqueado) {
                        print "<a onclick='if(confirm(\"Esta seguro que desea eliminar este costo?\")) { return true;} else { return false; }' href='presupuesto_gastos.php?mode=d&ids=".$fila[0]."&idv=".$_GET["idv"]."'><img src=\"images/delete.png\" alt=\"Eliminar\" title=\"Eliminar\" width=\"18\" height=\"18\" border=\"0\" /></a>";
                     }
                     if ($editar && !$bloqueado) {
                        print "<a href='presupuesto_gastos.php?mode=e&ids=".$fila[0]."&idv=".$_GET["idv"]."#editor'><img src=\"images/editar.png\" alt=\"Editar\" title=\"Editar\" width=\"18\" height=\"18\" border=\"0\" /></a>";
                     }
//                }
                print "</td>
                          </tr>";
                    }
                    print "<tr><td colspan='4' class='tititems' align='right'>TOTAL</td><td class='tabladetnum'><b>".sprintf("%0.2f",$total)."</b></td></tr>";

          if (isset($_GET["ids"])) {
                  $sql="select * from presupuestogastos where idpresupuestogastos=".$_GET["ids"];
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
                    <td width='280'>
                    <select name='tiposgastos' id='tiposgastos' >
                           <option value='-1'>--- Elija ---</option>
                          <?php
                              if (isset($_GET["ids"])) {
                                  $sql="select * from tiposgastos tg left join presupuestogastos pg on pg.idtiposgastos=tg.idtiposgastos and pg.idversion=".$_GET["idv"]." where (pg.idversion is null or pg.idpresupuestogastos=".$_GET["ids"].") order by nombre";
                              } else {
                                  $sql="select * from tiposgastos tg left join presupuestogastos pg on pg.idtiposgastos=tg.idtiposgastos and pg.idversion=".$_GET["idv"]." where pg.idversion is null  order by nombre";
                              }
                              $res=mysqli_query($sql);
                              while ($fila=mysqli_fetch_array($res)) {
                                    print "<option ";
                                    if (isset($_GET["mode"]) && $_GET["mode"]=="e" && $filares[2]==$fila[0]) { print "selected"; }
                                    print " value='$fila[0]'>$fila[1]</option>";
                              }
                          ?>
                          </select>
                          <?php
                          if (acceso($_SESSION['idRol'], 0,0,0,0,4,0)>=4) {
                          ?>
                          <a href='#' onclick="window.open('costos_nuevos.php?t=popup', '_blank', 'status=0,menubar=0,location=0,scrollbars=0,resizable=0,width=500,height=250', 0);"><img border='0' src='images/508.png'></a>
                          <?php
                          }
                          ?>
                    </td>
                    <td width='210'>
                    <select name='aplicacion' id='aplicacion' >
                           <option value='-1'>--- Elija ---</option>
                          <?php
                              $sql="select * from aplicacion";
                              $res=mysqli_query($sql);
                              while ($fila=mysqli_fetch_array($res)) {
                                    print "<option ";
                                    if (isset($_GET["mode"]) && $_GET["mode"]=="e" && $filares[4]==$fila[0]) { print "selected"; }
                                    print " value='$fila[0]'>$fila[1]</option>";
                              }
                          ?>
                          </select>
                    </td>
                    <td width='170'>
                    <select name='clasificacion' id='clasificacion' >
                           <option value='-1'>--- Elija ---</option>
                          <?php
                              $sql="select * from clasificaciongastos";
                              $res=mysqli_query($sql);
                              while ($fila=mysqli_fetch_array($res)) {
                                    print "<option ";
                                    if (isset($_GET["mode"]) && $_GET["mode"]=="e" && $filares[5]==$fila[0]) { print "selected"; }
                                    print " value='$fila[0]'>$fila[1]</option>";
                              }
                          ?>
                          </select>
                    </td>
                    <td width='90' align='right'>
                       <input type="text" name="valor" size="5" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e" ) { print $filares[1]; } ?>">
                    </td>
                    <td width='80'><input class="save" type="submit" name="guardar" size="60" value="">
                    <?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { ?>
                          <input class="cancel" type="button" name="cancelar" size="60" value="" onclick="window.location='presupuesto_gastos.php?idv=<?php print $_GET["idv"]; ?>'">
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
                    </form>

                    <?php
                    }
                        if (isset($error)) {
                           print $error;
                        } ?>

                    </td></tr>
                    <tr><td align='right'><a name='editor' > </a>
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