<?php

    include_once('config.php');
    include_once('funciones.php');
?>
<table class='contenido' width='100%' id='productos'>
                <tr>
                    <td colspan="4" class="titmenu">
                              PRODUCTOS<a name='nuevo'>
                    </td>
                </tr>                <tr>
                    <td class='tititems' width='56%' colspan='2'>Descripci&oacute;n</td><td class='tititems' width='22%'>Inicio</td><td class='tititems' width='22%'>Fin</td>
                </tr>
                <?php
                    $col1="#eeeeee";
                    $col2="#ffffff";
                    $txtcolor="black";
                    $sql="select * from producto where idproyecto=".$_GET["id"]." order by fechainicio, fechafinal";
                    $res=mysqli_query($sql);
                    $colactual="";
                    while ($fila=mysqli_fetch_array($res)) {
                          if ($colactual!=$col1) {
                             $colactual=$col1;
                          } else {
                             $colactual=$col2;
                          }
                          if ($fila[4]<date("Y-m-d")) {
                             $txtcolor="red";
                          } else {
                             $txtcolor='black';
                          }
                          print "
                <tr bgcolor='$colactual'>
                    <td class='tabladettxt' colspan='2'><font color='$txtcolor'>".$fila[1]."</font></td><td class='tabladettxt'><font color='$txtcolor'>".fecha($fila[3],2)."</font></td><td  class='tabladettxt'><font color='$txtcolor'>".fecha($fila[4],2)."</font></td>
                </tr>";
                    }

                ?>
                                    <tr>
                    <td class='tablatxt' align='center' width='10%'>
                    <?php print "<a class='icono' onclick=\"window.open('definir_productos.php?id=".$_GET["id"]."', '_blank', 'status=0,menubar=0,location=0,scrollbars=1,resizable=0,width=700,height=250', 0);\" href='#'><img src=\"images/addproducto.png\" alt=\"Definir productos\" title=\"Definir productos\" border=\"0\" width=\"31\" height=\"31\" /><br>Gestionar</a>";
                    ?>
                    </td>
                    <td class='tablatxt' align='center' colspan='3' width='90%'></td>
                </tr>
                    </table>