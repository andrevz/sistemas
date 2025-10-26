<?php

include("valida.php");
$actividad=42;
$minimo=3;
include("niveles_acceso.php");

if (isset($_POST["MM_edit"]) && $editar) {
    if ($_POST["gestion"]=="" or is_null($_POST["gestion"]) or $_POST["recursohumano"]=="-1") {
       $error="<font color='red'><b>Debe registrarse obligatoriamente la gesti&oacute;n y el responsable</b></font>";
    } else {
       $sql_buscaciudad="select idciudad from ciudad where nombre like '".mb_strtoupper($_POST["ciudad"])."'";;
       $res_buscaciudad=mysql_query($sql_buscaciudad);
       if (!($fila_buscaciudad=mysql_fetch_array($res_buscaciudad))) {
          $ins_ciudad="insert into ciudad values (null, '".mb_strtoupper($_POST["ciudad"])."')";
          $res_insciudad=mysql_query($ins_ciudad);
          $idciudad=mysql_insert_id();
       } else {
          $idciudad=$fila_buscaciudad[0];
       }
       $sql="insert into version values (null, '".$_POST["gestion"]."', '$idciudad', '".$_POST["colegiatura"]."', '0',
                   '".$_POST["idprograma"]."', '".$_POST["recursohumano"]."', '".$_POST["dias"]."', '".$_POST["meses"]."', '".fecha($_POST["inicioprogramado"],99)."',
                   '".fecha($_POST["finprogramado"],99)."', '".fecha($_POST["inicioprogramado"],99)."',
                   '".fecha($_POST["finprogramado"],99)."', '".$_POST["matricula"]."', 1, 0, 0, '".$_POST["materias"]."',0
                   )";
      mysql_query($sql) or die(mysql_error());
      $id_version=mysql_insert_id();

      for ($i=0;$i<=1.01;$i+=0.05) {
          $sql="insert into presupuestoingresos values (null, '$i', 0, $id_version)";
          mysql_query($sql);
      }

//      $sql_ch="insert into checklist select null, $id_version, 0, idactividades, 0, '0000-00-00', '', responsable from checklist where idversion=".$_POST["idv"];
//      mysql_query($sql_ch);

      $sql_ch="insert into presupuestogastos select null, valor, idtiposgastos, $id_version, idaplicacion, idclasificacion from presupuestogastos where idversion=".$_POST["idv"];
      mysql_query($sql_ch);


      $sql="select idmateriaversion, universidad, $id_version, idtipomateria, idmateria, orden from materiaversion where idversion=".$_POST["idv"];
      $res=mysql_query($sql);
      while ($fila=mysql_fetch_array($res)) {
            $sql1="insert into materiaversion values (null, '$fila[1]', '$fila[2]', '$fila[3]', '$fila[4]', '$fila[5]')";
            mysql_query($sql1);
            $idmv=mysql_insert_id();
            $sql2="select idplanmateriaversiondocente, horas, honorarioshora, pasajes, viaticosdia, dias, hospedajedia, idcategoria, idrecursohumano, $idmv, inicio, fin, procedencia from planmateriaversiondocente where idmateriaversion=".$fila[0];
            $res2=mysql_query($sql2);
            while ($fila2=mysql_fetch_array($res2)) {
                  $sql2="insert into planmateriaversiondocente
                                values (null, '$fila2[1]', '$fila2[2]', '$fila2[3]', '$fila2[4]', '$fila2[5]', '$fila2[6]', '$fila2[7]', '$fila2[8]', $idmv, '$fila2[10]', '$fila2[11]', '$fila2[12]')";
                  mysql_query($sql2);
                  $idpmvd=mysql_insert_id();
                  $sql3="select idejecucionmateriaversiondocente, horas, honorarioshora, pasajes, viaticosdia, dias, hospedajedia, evaluaciondocente, fechacontrato, monto, fechasolicitudpago, nrosolicitud, estado, $idmv, idrecursohumano, idcategoria, $idpmvd, inicio, fin, procedencia, observaciones from ejecucionmateriaversiondocente where idmateriaversion=".$fila[0]." and idplanmateriaversiondocente=".$fila2[0];
                  $res3=mysql_query($sql3);
                  while ($fila3=mysql_fetch_array($res3)) {
                        $sql2="insert into ejecucionmateriaversiondocente
                                      values (null, '$fila3[1]', '$fila3[2]', '$fila3[3]', '$fila3[4]', '$fila3[5]', '$fila3[6]', '$fila3[7]', '$fila3[8]', '$fila3[9]', '$fila3[10]', '$fila3[11]', '$fila3[12]', $idmv, '$fila3[14]', '$fila3[15]', $idpmvd, '$fila3[17]', '$fila3[18]', '$fila3[19]', '$fila3[20]')";
                        mysql_query($sql2);
/*                        $idemvd=mysql_insert_id();
                        $sql_ch="insert into checklist select null, 0, $idemvd, idactividades, 0, '0000-00-00', '', responsable from checklist where idejecucionmateriaversiondocente=".$fila3[0];
                        mysql_query($sql_ch); */
                  }
            }
      }


      header("Location: versiones.php?idp=".$_POST["idprograma"]);
      exit;
   }
}
if (isset($_GET["mode"]) && $_GET["mode"]=="e") {
  $sql="select * from version where idversion=".$_GET["idv"];
  $res=mysql_query($sql);
  $fila_e=mysql_fetch_array($res);
}

include("encabezado.php");
?>
<script>
$(function() {
var availableTags = [
<?php
  $sql="select idciudad, nombre from ciudad order by nombre";
  $res=mysql_query($sql);
  $i=0;
  while ($fila=mysql_fetch_array($res)) {
        if ($i>0) { print ",\n"; }
        $i++;
        print "\"$fila[1]\"";
  }
?>
];
$( "#tags" ).autocomplete({
source: availableTags
});
});
</script>
<?php

  $sql="select * from programa where idprograma=".$_GET["idp"];
  $res=mysql_query($sql);
  $fila=mysql_fetch_array($res);
?>

<table align="center" bgcolor="#ffffff" width="100%">
<tr>
    <td width="200" valign="top">
        <table align="center" width="100%">
                        <tr>
                            <td class="textsubmenu"><img src="images/bc_parent.png" /></td><td class="textsubmenu"><a href="versiones.php?idp=<?php print $_GET["idp"]; ?>" class="submenu">Retornar a versiones</a></td>
                        </tr>
        </table>

        <br><br>
    </td>
    <td width="800">
    <table align="center" width="100%" cellspacing='2'>
        <tr>
            <td class="bordes">
            <table class="contenido" width="100%">
                <tr>
                    <td colspan="2" class="titmenu">
                        DUPLICAR VERSION
                    </td>
                </tr>
                <tr>
                    <td>
                    <form method="POST" name="actualizar">
                      <table align="center">
                        <tr valign="baseline">
                          <td  class="tititems" align="right">Programa:</td>
                          <td><?php print $fila[1]; ?></td>
                        </tr>
                        <tr valign="baseline">
                          <td  class="tititems" align="right">Gesti&oacute;n:</td>
                          <td><input type="text" name="gestion" size="8" value="" class='boton1'></td>
                        </tr>
                        <tr valign="baseline">
                          <td  class="tititems" align="right">Ciudad:</td>
                          <td><input  class='boton1' id="tags" type="text" name="ciudad" size="30" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") {
                          $sql_buscaciudad="select nombre from ciudad where idciudad = ".$fila_e[2];
                          $res_buscaciudad=mysql_query($sql_buscaciudad);
                          $fila_buscaciudad=mysql_fetch_array($res_buscaciudad);
                          print $fila_buscaciudad[0];
                          } ?>" ></td>
                        </tr>
                        <tr valign="baseline">
                          <td  class="tititems" align="right">Colegiatura ($us):</td>
                          <td><input class='boton1' type="text" name="colegiatura" size="8" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print $fila_e[3]; } ?>"></td>
                        </tr>
                        <tr valign="baseline">
                          <td  class="tititems" align="right">Matr&iacute;cula ($us):</td>
                          <td><input class='boton1' type="text" name="matricula" size="5" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print $fila_e[13]; } ?>"></td>
                        </tr>
                        <tr valign="baseline">
                          <td class="tititems" align="right" valign="top">Responsable:</td>
                          <td><select name='recursohumano' id='recursohumano'>
                           <option value='-1'>--- Elija ---</option>
                          <?php
                              $sql="select idRecursoHumano, concat(apellidos, ', ',nombres, ' - ',codigo_UPB) from recursohumano where recursoactivo='1' order by Apellidos, Nombres";
                              $res=mysql_query($sql);
                              while ($fila=mysql_fetch_array($res)) {
                                    print "<option ";
                                    if (isset($_GET["mode"]) && $_GET["mode"]=="e" && $fila_e[6]==$fila[0]) { print "selected"; }
                                    print " value='$fila[0]'>$fila[1]</option>";
                              }
                          ?>
                          </select>
                          <?php
                          if (acceso($_SESSION['idRol'], 0,0,0,0,1,0)>2) {
                          ?>
                          <a href='#' onclick="window.open('rrhh_nuevos.php?t=popup', '_blank', 'status=0,menubar=0,location=0,scrollbars=0,resizable=0,width=600,height=500', 0);"><img src='images/508.png' border='0'></a>
                          <?php
                          }
                          ?>
                          </td>
                        </tr>
                        <tr valign="baseline">
                          <td class="tititems" align="right" valign="top">Dias de clase:</td>
                          <td><input class='boton1' type="text" name="dias" size="5" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print $fila_e[7]; } ?>">
                          </td>
                        </tr>
                        <tr valign="baseline">
                          <td class="tititems" align="right" valign="top">Nro. Meses:</td>
                          <td><input class='boton1' type="text" name="meses" size="5" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print $fila_e[8]; } ?>">
                          </td>
                        </tr>
                        <tr valign="baseline">
                          <td class="tititems" align="right" valign="top">Nro. Materias:</td>
                          <td><input class='boton1' type="text" name="materias" size="5" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print $fila_e["NroMaterias"]; } ?>">
                          </td>
                        </tr>
                        <tr valign="baseline">
                          <td  class="tititems" align="right">Inicio:</td>
                          <td><input class='boton1' type="text" name="inicioprogramado" size="10" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print fecha($fila_e[9],8); } else { print date("d-m-Y"); } ?>" id="convo"></td>
                        </tr>
                        <tr valign="baseline">
                          <td  class="tititems" align="right">Fin:</td>
                          <td><input class='boton1' type="text" name="finprogramado" size="10" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print fecha($fila_e[10],8); } else { print date("d-m-Y"); } ?>"  id="ela"></td>
                        </tr>

                        <tr valign="baseline">
                          <td colspan="2" align="center"><input class="boton1" type="submit" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print "Duplicar"; } else { print "Crear"; } ?> version"> <input class="boton1" name="Cancelar" type="button" onClick="window.location='versiones.php?idp=<?php print $_GET["idp"]; ?>'" value='Cancelar'/></td>
                        </tr>
                      </table>
                      <input type="hidden" name="idprograma" value="<?php print $_GET["idp"] ?>">
                      <input type="hidden" name="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print "MM_edit"; } else { print "MM_insert"; } ?>" value="actualizar">
                      <?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print "<input type='hidden' name='idv' value='".$fila_e[0]."'>"; }
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
    </table>
</td>
</tr>

</table>
</td></tr></table>
<?php
include("pie.php");
?>
</body>
</html>