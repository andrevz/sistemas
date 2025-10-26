<?php

include("valida.php");

$actividad=21;
include("niveles_acceso.php");

if (isset($_POST["bloq"])) {
   $sql="update version set estado=".$_POST["bloq"]." where idversion=".$_POST["idv"];
   mysql_query($sql);
      header("Location: version_s.php?idv=".$_POST["idv"]);
      exit;
}
include("encabezado.php");

                $sqldatos="SELECT idVersion, Gestion, v.Ciudad, Colegiatura, Overhead, v.idPrograma, v.idRecursoHumano, NroDias, NroMeses, InicioProgramado, FinProgramado, InicioEjecutado, FinEjecutado, Matricula, activo, NroDiasEjecutado, NroMesesEjecutado, rh.nombres, rh.apellidos, p.nombre, c.nombre, v.NroMaterias, v.estado
                                  FROM version v inner join recursohumano rh on rh.idrecursohumano=v.idrecursohumano
                                  inner join programa p on p.idprograma=v.idprograma
                                  inner join ciudad c on c.idciudad=v.ciudad
                                  where idversion=".$_GET["idv"];
                $resdatos=mysql_query($sqldatos);
                $filadatos=mysql_fetch_array($resdatos);
                if ($filadatos["estado"]==2) {
                   $bloqueado=true;
                } else {
                   $bloqueado=false;
                }
            ?>
<table align="center" bgcolor="#ffffff" width="100%">
<tr>

       <td width="200" valign="top">
        <table align="center" width="100%">
                        <tr>
                            <td class="textsubmenu"><img src="images/bc_parent.png" /></td><td class="textsubmenu"><a href="menu.php" class="submenu">Retornar al men&uacute; principal</a></td>
                        </tr>
                        <tr>
                            <td class="textsubmenu"><img src="images/ok.gif" /></td><td class="textsubmenu"><a href="versiones_t.php" class="submenu">Retornar a versiones</a></td>
                        </tr>
        </table>

        <br><br>
    </td>
    <td width="600">
    <table align="center" width="100%" cellspacing='2'>
        <tr>
            <td class="bordes" colspan='2'>
            <table class='contenido' width='100%' >
                   <tr>
                       <td class='titproy' colspan='4' align='center'><font size='4'>EJECUCI&Oacute;N DE VERSIONES</font></td>
                   </tr><tr>
                       <td class='tablatxt' colspan='5'>Programa:</td></tr><tr>
                       <td class='titproy' colspan='5'><?php print $filadatos[19]; ?></td>
                   </tr>
                   <tr>
                       <td class='tablatxt' colspan='5'>Versi&oacute;n:</td></tr><tr>
                   </tr>
                   <tr>
                       <td class='tititems' width='18%'>Gestion:</td><td width='32%' class='detproy'><?php print $filadatos[1]; ?></td>
                       <td class='tititems' width='18%'>Ciudad:</td><td  width='32%' class='detproy'><?php print $filadatos[20]; ?></td>
                   </tr>
                   <tr>
                       <td class='tititems' >Dias de clase:</td><td  class='detproy'><?php print $filadatos[15]; ?></td>
                       <td class='tititems'>Nro. Meses:</td><td class='detproy'><?php print $filadatos[16]; ?></td>
                   </tr>
                   <tr>
                       <td class='tititems' >Responsable:</td><td  class='detproy'><?php print $filadatos[18].", ".$filadatos[17]; ?></td>
                       <td class='tititems'>Nro. Materias:</td><td class='detproy'><?php print $filadatos["NroMaterias"]; ?></td>
                   </tr>
                   <tr>
                       <td class='tititems' >Programado:</td><td  class='detproy' align='center'><font size='1'><b>Inicio</b></font><br><?php print fecha($filadatos[9],8); ?></td>
                       <td  class='detproy' align='center'><font size='1'><b>Fin</b></font><br><?php print fecha($filadatos[10],8); ?></td>
                       <td align='right' rowspan='2'>
                       <table><tr><td><table><tr><td>
                       <?php
                             if (acceso($_SESSION['idRol'], 0,0,0,0,41,0)>=2) {
                                print "<form name='bloqueo' method='post'><input type='hidden' name='idv' value='".$_GET["idv"]."'><button name='bloq' type='submit' value='";
                                if ($filadatos["estado"]==0) {
                                   print "0' onclick='alert(\"Debe bloquear primero la planificacion.\"); return false;'><img src=\"images/abierto.png\">";
                                } else if ($filadatos["estado"]==1) {
                                   print "2'><img src=\"images/abierto.png\">";
                                } else if ($filadatos["estado"]==2) {
                                   print "1'><img src=\"images/cerrado.png\">";
                                }
                                print "</button></form> ";
                             }
                             print "</td><td>";
                        if (acceso($_SESSION['idRol'], 0,0,0,0,22,0)>=2) {
                             print "&nbsp;<a class='icono' href='#' onclick=\"window.open('checklist.php?idv=".$filadatos[0]."&t=popup&idp=".$filadatos[5]."', '_blank', 'status=0,menubar=0,location=0,resizable=0,scrollbars=1,width=900,height=500', 0)\">
                                   <img src=\"images/check.png\" alt=\"Checklist\" title=\"Checklist\" height=\"30\" border=\"0\" />
                                   </a> &nbsp;";
                        }
                        if (acceso($_SESSION['idRol'], 0,0,0,0,23,0)>=2) {
                             print "<a class='icono' href='#' onclick=\"window.open('seguimiento.php?idv=".$filadatos[0]."&t=popup&idp=".$filadatos[5]."', '_blank', 'status=0,menubar=0,location=0,resizable=0,scrollbars=1,width=350,height=400', 0)\">
                                   <img src=\"images/075.png\" alt=\"Seguimiento alumnos\" title=\"Seguimiento alumnos\" height=\"30\" border=\"0\" />
                                   </a> ";
                        }
                        if (acceso($_SESSION['idRol'], 0,0,0,0,24,0)>=2) {
                             print "<a class='icono' href='#' onclick=\"window.open('ejecucion_ingresos.php?idv=".$filadatos[0]."&t=popup&idp=".$filadatos[5]."', '_blank', 'status=0,menubar=0,location=0,resizable=0,scrollbars=1,width=500,height=400', 0)\">
                                   <img src=\"images/ingresos.png\" alt=\"Ejecución de ingresos\" title=\"Ejecución de ingresos\" border=\"0\" />
                                   </a> ";
                        }
                        if (acceso($_SESSION['idRol'], 0,0,0,0,28,0)>=2) {
                             print " <a onclick=\"window.open('documentos.php?idv=".$filadatos[0]."&t=popup&idp=".$filadatos[5]."', '_blank', 'status=0,menubar=0,location=0,scrollbars=0,resizable=0,width=700,height=250', 0);\" href='#'>
                                    <img src=\"images/documentos.png\" alt=\"Documentos\" title=\"Documentos\" width=\"30\" border=\"0\" />
                                    </a>";
                        }
                        print "<br>";
                        if (acceso($_SESSION['idRol'], 0,0,0,0,25,0)>=2) {

                             print "<a class='icono' href='#' onclick=\"window.open('medios_ejec.php?idv=".$filadatos[0]."&t=popup&idp=".$filadatos[5]."', '_blank', 'status=0,menubar=0,location=0,resizable=0,scrollbars=1,width=900,height=400', 0)\">
                                   <img src=\"images/medios.png\" alt=\"Ejecución de plan de medios\" title=\"Ejecución de plan de medios\" height=\"30\" border=\"0\" />
                                   </a> ";
                        }
                        if (acceso($_SESSION['idRol'], 0,0,0,0,26,0)>=2) {

                             print "<a class='icono' href='#' onclick=\"window.open('reclutamiento.php?idv=".$filadatos[0]."&t=popup&idp=".$filadatos[5]."', '_blank', 'status=0,menubar=0,location=0,resizable=0,scrollbars=1,width=550,height=400', 0)\">
                                   <img src=\"images/rh.png\" alt=\"Reclutamiento\" title=\"Reclutamiento\" height=\"30\" border=\"0\" />
                                   </a> ";
                        }
                        if (acceso($_SESSION['idRol'], 0,0,0,0,27,0)>=2) {

                             print "<a class='icono' href='#' onclick=\"window.open('ejecucion_gastos.php?idv=".$filadatos[0]."&t=popup&idp=".$filadatos[5]."', '_blank', 'status=0,menubar=0,location=0,resizable=0,scrollbars=1,width=650,height=400', 0)\">
                                   <img src=\"images/gastos.png\" alt=\"Ejecución de gastos\" title=\"Ejecución de gastos\" border=\"0\" />
                                   </a> ";
                        }

                                ?></td></tr></table>
                       </td></tr></table>
                       </td>
                   </tr>
                   <tr>
                       <td class='tititems' >Ejecutado:</td><td  class='detproy' align='center'><font size='1'><b>Inicio</b></font><br><?php print fecha($filadatos[11],8); ?></td>
                       <td  class='detproy' align='center'><font size='1'><b>Fin</b></font><br><?php print fecha($filadatos[12],8); ?></td>
                   </tr>

            </table>
            </td></tr>
            <tr><td width='100%' valign='top'>
<?php
         $niva=acceso($_SESSION['idRol'], 0,0,0,0,29,0);
         if ($niva>=2) {

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
                    <td class='tititems' width='8%'>C&oacute;digo</td>
                    <td class='tititems' width='10%'>Area</td>
                    <td class='tititems'></td>
                </tr>
                <?php
                    $col1="#eeeeee";
                    $col2="#ffffff";
                    $sql="SELECT * FROM materiaversion mv inner join materia m on m.idmateria=mv.idmateria where idversion=".$_GET["idv"]."";
                    $res=mysql_query($sql);
                    $colactual="";
                    $i=0;
                    while ($fila=mysql_fetch_array($res)) {
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
                              <td class='tabladettxt' align='center'>";
                        if (acceso($_SESSION['idRol'], 0,0,0,0,30,0)>=2) {
                              print "<a onclick=\"window.open('ejecucion_docente.php?ids=".$fila[0]."&idv=".$_GET["idv"]."', '_blank', 'status=0,menubar=0,location=0,resizable=0,scrollbars=1,width=1185,height=350', 0)\" href='#'><img src=\"images/191.png\" alt=\"Seguimiento al docente\" title=\"Seguimiento al docente\" width=\"18\" height=\"18\" border=\"0\" /></a>";
                        }
                              print "</td>
                          </tr>";
                    }
                    ?>

                    </table>

               <?php
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