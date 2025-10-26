<?php

    include_once('config.php');
    include_once("funciones.php");

?>
<table class='contenido' width='100%' id='equipo'>
                <tr>
                    <td colspan="7" class="titmenu">
                        MATERIAS<a name='nuevo'>
                    </td>
                </tr>                <tr>
                    <td class='tititems' width='4%' >Nro</td>
                    <td class='tititems' width='46%' >Nombre</td>
                    <td class='tititems' width='15%'>Tipo</td>
                    <td class='tititems' width='12%'>Univ.</td>
                    <td class='tititems' width='11%'>Inicio</td>
                    <td class='tititems' width='6%'>Dias</td>
                    <td class='tititems' width='6%'>Horas</td>
                </tr>
                <?php
                    $col1="#eeeeee";
                    $col2="#ffffff";
                    $sql="SELECT * FROM materiaversion mv inner join materia m on m.idmateria=mv.idmateria inner join tipomateria tm on tm.idtipomateria=mv.idtipomateria where idversion=".$_GET["idv"]." ";
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
                              <td class='tabladettxt'>".$i."</td>
                              <td class='tabladettxt'>".$fila[14]."</td>
                              <td class='tabladettxt'>".$fila[17]."</td>
                              <td class='tabladettxt'>".$fila[1]."</td>
                              <td class='tabladettxt'>".fecha($fila[2],8)."</td>
                              <td class='tabladetnum'>".$fila[4]."</td>
                              <td class='tabladetnum'>".$fila[5]."</td>
                          </tr>";
                    }
                    ?>

                    </table>