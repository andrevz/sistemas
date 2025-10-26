<?php

include("valida.php");
$actividad=13;
include("niveles_acceso.php");

if (isset($_POST["MM_insert"]) && $insertar) {
    if ($_POST["materia"]=="") {
          $error="<font color='red'><b>Debe seleccionar obligatoriamente la materia</b></font>";
    } else if (strlen($error)<1){
       $sql_bp="select idprograma from version where idversion=".$_GET["idv"];
       $res_bp=mysqli_query($sql_bp);
       $fila_bp=mysqli_fetch_array($res_bp);

/*       $sql_buscaciudad="select idmateria, idprograma from materia where nombre like '".$_POST["materia"]."' and (idprograma=".$fila_bp[0]." or idprograma=0) order by idprograma desc limit 1";
       $res_buscaciudad=mysqli_query($sql_buscaciudad);
       if (!($fila_buscaciudad=mysqli_fetch_array($res_buscaciudad))) {
          $ins_ciudad="insert into materia (nombre, idprograma) values ('".$_POST["materia"]."', $fila_bp[0])";
          $res_insciudad=mysqli_query($ins_ciudad);
          $idmateria=mysqli_insert_id();
       } else {
          if ($fila_buscaciudad[1]==0) { */
             $up_mat="update materia set idprograma=$fila_bp[0] where idmateria=".$_POST["materia"];
             mysqli_query($up_mat);
/*          }
          $idmateria=$fila_buscaciudad[0];
       }        */
         $sql="insert into materiaversion values (null, '".$_POST["universidad"]."', ".$_GET["idv"].", '0', '".$_POST["materia"]."', '".$_POST["orden"]."')";
         mysqli_query($sql) or die(mysqli_error());

         header("Location: version.php?idv=".$_POST["idv"]);
         exit;
    }
} else if (isset($_GET["mode"]) && $_GET["mode"]=="d" && $eliminar) {

      $sql="delete from planmateriaversiondocente where idmateriaversion=".$_GET["ids"];
      mysqli_query($sql) or die(mysqli_error());
      $sql="delete from materiaversion where idmateriaversion=".$_GET["ids"];
      mysqli_query($sql) or die(mysqli_error());


      header("Location: version.php?idv=".$_GET["idv"]);
      exit;
} else if (isset($_POST["MM_edit"]) && $editar) {

    if ( $_POST["tipo"]=="-1" || $_POST["materia"]=="") {
          $error="<font color='red'><b>Deben seleccionarse obligatoriamente la materia y el tipo</b></font>";
    } else if (strlen($error)<1){
       $sql_bp="select idprograma from version where idversion=".$_GET["idv"];
       $res_bp=mysqli_query($sql_bp);
       $fila_bp=mysqli_fetch_array($res_bp);
/*       $sql_buscaciudad="select idmateria, idprograma from materia where nombre like '".$_POST["materia"]."' and (idprograma=".$fila_bp[0]." or idprograma=0) order by idprograma desc limit 1";
       $res_buscaciudad=mysqli_query($sql_buscaciudad);
       if (!($fila_buscaciudad=mysqli_fetch_array($res_buscaciudad))) {
          $ins_ciudad="insert into materia values (null, '".$_POST["materia"]."',0)";
          $res_insciudad=mysqli_query($ins_ciudad);
          $idmateria=mysqli_insert_id();
       } else {
          if ($fila_buscaciudad[1]==0) {     */
             $up_mat="update materia set idprograma=$fila_bp[0] where idmateria=".$_POST["materia"];
             mysqli_query($up_mat);
/*          }
          $idmateria=$fila_buscaciudad[0];
       } */
       $sql="update materiaversion set
                   universidad='".$_POST["universidad"]."', orden='".$_POST["orden"]."',
                   idmateria='".$_POST["materia"]."' where idmateriaversion=".$_GET["ids"];
      mysqli_query($sql) or die(mysqli_error());


      header("Location: version.php?idv=".$_GET["idv"]);
      exit;
    }
}
if (isset($_POST["bloq"])) {
   $sql="update version set estado=".$_POST["bloq"]." where idversion=".$_POST["idv"];
   mysqli_query($sql);
      header("Location: version.php?idv=".$_POST["idv"]);
      exit;
}

include("encabezado.php");

                $sqldatos="SELECT idVersion, Gestion, v.Ciudad, Colegiatura, Overhead, v.idPrograma, v.idRecursoHumano, NroDias, NroMeses,
                                  InicioProgramado, FinProgramado, InicioEjecutado, FinEjecutado, Matricula, activo, NroDiasEjecutado,
                                  NroMesesEjecutado, rh.nombres, rh.apellidos, p.nombre, c.nombre, v.NroMaterias, p.idescuela, p.idtipoprograma, estado
                                  FROM version v inner join recursohumano rh on rh.idrecursohumano=v.idrecursohumano
                                  inner join programa p on p.idprograma=v.idprograma
                                  inner join ciudad c on c.idciudad=v.ciudad
                                  where idversion=".$_GET["idv"];
                $resdatos=mysqli_query($sqldatos);
                $filadatos=mysqli_fetch_array($resdatos);
                if ($filadatos["estado"]>=1) {
                   $bloqueado=true;
                } else {
                   $bloqueado=false;
                }
            ?>
<script>
$(function() {
var availableTags = [
<?php
  $sql="select nombre from materia WHERE idprograma =1 OR idprograma =0 order by nombre";
  $res=mysqli_query($sql);
  $i=0;
  while ($fila=mysqli_fetch_array($res)) {
        if ($i>0) { print ",\n"; }
        $i++;
        print "'".addslashes($fila[0])."'";
  }
?>
];
$( "#materia" ).autocomplete({
source: availableTags
});
});
</script>
<table align="center" bgcolor="#ffffff" width="100%">
<tr>

       <td width="200" valign="top">
        <table align="center" width="100%">
                        <tr>
                            <td class="textsubmenu"><img src="images/bc_parent.png" /></td><td class="textsubmenu"><a href="menu.php" class="submenu">Retornar al men&uacute; principal</a></td>
                        </tr>
                        <tr>
                            <td class="textsubmenu"><img src="images/ok.gif" /></td><td class="textsubmenu"><a href="versiones.php?idp=<?php print $filadatos[5]; ?>" class="submenu">Retornar a versiones</a></td>
                        </tr>
        </table>

        <br><br>
    </td>
    <td width="800">
    <table align="center" width="100%" cellspacing='2'>
        <tr>
            <td class="bordes" colspan='2'>
            <table class='contenido' width='100%' >
                   <tr>
                       <td class='titproy' colspan='5' align='center'><font size='4'>PLANIFICACI&Oacute;N DE VERSIONES</font></td>
                   </tr>
                   <tr>
                       <td class='tablatxt' colspan='5'>Programa:</td></tr><tr>
                       <td class='titproy' colspan='5'><?php print $filadatos[19]; ?></td>
                   </tr>
                   <tr>
                       <td class='tablatxt' colspan='5'>Versi&oacute;n:</td></tr><tr>
                   </tr>
                   <tr>
                       <td class='tititems' width='18%'>Gestion:</td><td width='32%' class='detproy'><?php print $filadatos[1]; ?></td>
                       <td class='tititems' width='18%'>Ciudad:</td><td width='32%' class='detproy'><?php print $filadatos[20]; ?></td>
                   </tr>
                   <tr>
                       <td class='tititems'>Colegiatura ($us):</td><td class='detproy'><?php print $filadatos[3]; ?></td>
                       <td class='tititems'>Matr&iacute;cula ($us):</td><td class='detproy'><?php print $filadatos[13]; ?></td>
                   </tr>
                   <tr>
                       <td class='tititems'>Dias de clase:</td><td  class='detproy'><?php print $filadatos[7]; ?></td>
                       <td class='tititems'>Nro. Meses:</td><td class='detproy'><?php print $filadatos[8]; ?></td>
                   </tr>
                   <tr>
                       <td class='tititems' >Responsable:</td><td  class='detproy'><?php print $filadatos[18].", ".$filadatos[17]; ?></td>
                       <td class='tititems'>Nro. Materias:</td><td class='detproy'><?php print $filadatos["NroMaterias"]; ?></td>
                   </tr>
                   <tr>
                       <td class='tititems' >Programado:</td><td  class='detproy' align='center'><font size='1'><b>Inicio</b></font><br><?php print fecha($filadatos[9],8); ?></td>
                       <td  class='detproy' align='center'><font size='1'><b>Fin</b></font><br><?php print fecha($filadatos[10],8); ?></td>
                       <td align='right' rowspan='2'><table><tr>
                       <td align='center'>
                       <?php
                           $nacceso=acceso($_SESSION['idRol'], $filadatos["idVersion"],$filadatos["idescuela"],$filadatos["idtipoprograma"],$filadatos["Ciudad"],14,0);
                            $eliminar=($nacceso==3 || $nacceso==6 || $nacceso==99);
                            $editar=$eliminar;
                            $insertar=($nacceso>=4 || $nacceso==99);
                            $leer=($nacceso==2 || $nacceso==3 || $nacceso==5 || $nacceso==6 || $nacceso==99);

                             if (acceso($_SESSION['idRol'], 0,0,0,0,40,0)>=2) {
                                print "<form name='bloqueo' method='post'><input type='hidden' name='idv' value='".$_GET["idv"]."'><button name='bloq' type='submit' value='";
                                if ($filadatos["estado"]==0) {
                                   print "1'><img src=\"images/abierto.png\">";
                                } else if ($filadatos["estado"]==1) {
                                   print "0'><img src=\"images/cerrado.png\">";
                                } else if ($filadatos["estado"]==2) {
                                   print "2' onclick='alert(\"Debe desbloquear primero la ejecucion.\"); return false;'><img src=\"images/cerrado.png\">";
                                }
                                print "</button></form> ";
                             }
                           if ($editar && !$bloqueado) {
                             print "<a class='icono' href='nueva_version.php?mode=e&idv=".$filadatos[0]."&idp=".$filadatos[5]."'>
                                   <img src=\"images/editar.png\" alt=\"Editar Versi�n\" title=\"Editar versi�n\" width=\"31\" height=\"30\" border=\"0\" />
                                   </a> ";
                             }
                             if (acceso($_SESSION['idRol'], 0,0,0,0,14,0)>=2 ) {
                             print "<a class='icono' href='#' onclick=\"window.open('presupuesto_ingresos.php?idv=".$filadatos[0]."&idp=".$filadatos[5]."', '_blank', 'status=0,menubar=0,location=0,resizable=0,scrollbars=1,width=400,height=600', 0)\">
                                   <img src=\"images/ingresos.png\" alt=\"Presupuesto de ingresos\" title=\"Presupuestos de ingresos\" border=\"0\" />
                                   </a> ";
                             }
                             if (acceso($_SESSION['idRol'], 0,0,0,0,15,0)>=2 ) {
                             print "<a class='icono' href='#' onclick=\"window.open('presupuesto_gastos.php?idv=".$filadatos[0]."&idp=".$filadatos[5]."', '_blank', 'status=0,menubar=0,location=0,resizable=0,scrollbars=1,width=800,height=400', 0)\">
                                   <img src=\"images/gastos.png\" alt=\"Presupuesto de gastos\" title=\"Presupuesto de gastos\" border=\"0\" />
                                   </a> ";
                             }
                             if (acceso($_SESSION['idRol'], 0,0,0,0,16,0)>=2 ) {
                             print "<a class='icono' href='#' onclick=\"window.open('medios.php?idv=".$filadatos[0]."&idp=".$filadatos[5]."', '_blank', 'status=0,menubar=0,location=0,resizable=0,scrollbars=1,width=950,height=400', 0)\">
                                   <img src=\"images/medios.png\" alt=\"Plan de medios\" title=\"Plan de medios\" height=\"30\" border=\"0\" />
                                   </a> ";
                             }
                             if (acceso($_SESSION['idRol'], 0,0,0,0,17,0)>=3 ) {
                             print "<a class='icono' href='#' onclick=\"window.open('genera_presupuesto.php?idv=".$filadatos[0]."&idp=".$filadatos[5]."', '_blank', 'status=0,menubar=0,location=0,resizable=0,scrollbars=1,width=400,height=600', 0)\">
                                   <img src=\"images/calc.png\" alt=\"Generar presupuesto\" title=\"Generar presupuesto\" border=\"0\" />
                                   </a> ";
                             }
                 ?>
                       </td>
           </table></td>
                   </tr>
                   <tr>
                       <td class='tititems' >Ejecutado:</td><td  class='detproy' align='center'><font size='1'><b>Inicio</b></font><br><?php print fecha($filadatos[11],8); ?></td>
                       <td  class='detproy' align='center'><font size='1'><b>Fin</b></font><br><?php print fecha($filadatos[12],8); ?></td>
                   </tr>

            </table>
            </td></tr>
            <tr><td width='100%' valign='top'>
<?php
                    $niva=acceso($_SESSION['idRol'], $filadatos["idVersion"],$filadatos["idescuela"],$filadatos["idtipoprograma"],$filadatos["Ciudad"],18,0);
             if ($niva>=2 ) {
                    ?>

          <table class='contenido' width='100%' id='equipo'>
                <tr>
                    <td colspan="7" class="titmenu">
                        MATERIAS<a name='nuevo'>
                    </td>
                </tr>                <tr>
                    <td class='tititems' width='4%' >Nro</td>
                    <td class='tititems' width='60%' >Nombre</td>
                    <td class='tititems' width='10%'>Sigla</td>
                    <td class='tititems' width='8%'>Codigo</td>
                    <td class='tititems' width='10%'>Area</td>
                    <td></td>
                </tr>
                <?php
                    $col1="#eeeeee";
                    $col2="#ffffff";
                    if (isset($_GET["ids"])) {
                       $sql="SELECT * FROM materiaversion mv inner join materia m on m.idmateria=mv.idmateria where idversion=".$_GET["idv"]." and idmateriaversion<>".$_GET["ids"]." order by idmateriaversion";
                    } else {
                       $sql="SELECT * FROM materiaversion mv inner join materia m on m.idmateria=mv.idmateria where idversion=".$_GET["idv"]." order by idmateriaversion";
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
                              <td class='tabladettxt'>".$fila[5]."</td>
                              <td class='tabladettxt'>".$fila[7]."</td>
                              <td class='tabladettxt'>".$fila[9]."</td>
                              <td class='tabladettxt'>".$fila[12]."</td>
                              <td class='tabladettxt'>".$fila[1]."</td>
                              <td class='tabladettxt'>";
                $sql_v="SELECT count(*) FROM ejecucionmateriaversiondocente where idmateriaversion=".$fila[0];
                $res_v=mysqli_query($sql_v);
                $fila_v=mysqli_fetch_array($res_v);
                $sql_v="SELECT count(*) FROM planmateriaversiondocente where idmateriaversion=".$fila[0];
                $res_v=mysqli_query($sql_v);
                $fila_v1=mysqli_fetch_array($res_v);
                if (($niva==3 || $niva==6 || $niva==99)  && !$bloqueado) {
                   print "<a href='version.php?mode=e&ids=".$fila[0]."&idv=".$_GET["idv"]."#editor'><img src=\"images/editar.png\" alt=\"Editar\" title=\"Editar\" width=\"18\" height=\"18\" border=\"0\" /></a>";
                }
                if (acceso($_SESSION['idRol'], 0,0,0,0,19,0)>=2 ) {
                   print "<a onclick=\"window.open('asignar_docentes.php?ids=".$fila[0]."&idv=".$_GET["idv"]."', '_blank', 'status=0,menubar=0,location=0,resizable=0,scrollbars=1,width=1120,height=350', 0)\" href='#'><img src=\"images/191.png\" alt=\"Asignar Docentes\" title=\"Asignar Docentes\" width=\"18\" height=\"18\" border=\"0\" /></a>";
                }
                if (($fila_v[0]==0 && $fila_v1[0]==0 && ($niva==3 || $niva==6 || $niva==99)) && !$bloqueado) {
                   print " <a onclick='if(confirm(\"Esta seguro que desea eliminar esta materia?\\nAl eliminar una materia tambien se eliminan los docentes asignados a la misma.\")) { return true;} else { return false; }' href='version.php?mode=d&ids=".$fila[0]."&idv=".$_GET["idv"]."'><img src=\"images/delete.png\" alt=\"Eliminar\" title=\"Eliminar\" width=\"18\" height=\"18\" border=\"0\" /></a>";
                }
                print "</td>
                          </tr>";
                       }

          if (isset($_GET["ids"])) {
                  $sql="select * from materiaversion where idmateriaversion=".$_GET["ids"];
                  $resres=mysqli_query($sql);
                  $filares=mysqli_fetch_array($resres);
               }
                ?>
             </table>
             <?php
             }
               $niva=acceso($_SESSION['idRol'], $filadatos["idVersion"],$filadatos["idescuela"],$filadatos["idtipoprograma"],$filadatos["Ciudad"],18,0);
               if (((($niva==3 || $niva==6 || $niva==99) && isset($_GET["ids"])) || $niva>=4) && !$bloqueado) {
             ?>
             <form method="POST" name="actualizar">
             <table>
                <tr>
                    <td width='30'><input type='text' name='orden' size='1' value="<?php print ($i+1); ?>"></td>
                    <td width='600'>
                    <select name='materia' id='materia' >
                           <option value='-1'>--- Elija ---</option>
                          <?php
                              if (isset($_GET["ids"])) {
                                  $sql="select * from materia m left join materiaversion mv on mv.idmateria=m.idmateria and mv.idversion=".$_GET["idv"]." where mv.idversion is null or mv.idmateriaversion=".$_GET["ids"]." order by trim(Nombre)";
                              } else {
                                  $sql="select * from materia m left join materiaversion mv on mv.idmateria=m.idmateria and mv.idversion=".$_GET["idv"]." where mv.idversion is null and (idprograma =$filadatos[5] OR idprograma =0) order by trim(Nombre)";
                              }
                              $res=mysqli_query($sql);
                              while ($fila=mysqli_fetch_array($res)) {
                                    print "<option ";
                                    if (isset($_GET["mode"]) && $_GET["mode"]=="e" && $filares[4]==$fila[0]) { print "selected"; }
                                    print " value='$fila[0]'>".substr($fila[1],0,62).(strlen($fila[1])>62 ? "..." : "")." (".$fila[3].")</option>";
                              }
                          ?>
                          </select>
                          <?php
                          if (acceso($_SESSION['idRol'], 0,0,0,0,2,0)>=4) {
                          ?>
                          <a href='#' onclick="window.open('materias_nuevas.php?t=popup&ipd=<?php print $filadatos[5]; ?>', '_blank', 'status=0,menubar=0,location=0,scrollbars=0,resizable=0,width=710,height=280', 0);"><img border='0' src='images/508.png'></a>
                          <?php
                          }
                          ?>
                         <!--<input name='materia' id='materia' type='text' size='60' value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e" ) {
                         $sql_buscaciudad="select nombre from materia where idmateria = ".$filares[4];
                          $res_buscaciudad=mysqli_query($sql_buscaciudad);
                          $fila_buscaciudad=mysqli_fetch_array($res_buscaciudad);
                          print $fila_buscaciudad[0]; } ?>">-->
                    </td>
                    <!--<td width='115'>
                    <select name='tipomateria' id='tipomateria' >
                           <option value='-1'>--- Elija ---</option>
                          <?php
                              $sql="select * from tipomateria order by idtipomateria";
                              $res=mysqli_query($sql);
                              while ($fila=mysqli_fetch_array($res)) {
                                    print "<option ";
                                    if (isset($_GET["mode"]) && $_GET["mode"]=="e" && $filares[3]==$fila[0]) { print "selected"; }
                                    print " value='$fila[0]'>$fila[1]</option>";
                              }
                          ?>
                          </select>
                    </td>
                    -->
                    <td width='80'>
                       <input type="text" name="universidad" size="6" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e" ) { print $filares[1]; } ?>">
                    </td>
                    <td><input class="save" type="submit" name="guardar" size="60" value="">
                    <?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { ?>
                          <input class="cancel" type="button" name="cancelar" size="60" value="" onclick="window.location='version.php?idv=<?php print $_GET["idv"]; ?>'">
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
                        }
                        ?>

                    </td></tr>

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