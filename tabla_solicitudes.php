<?php

    include_once('config.php');
    include_once('funciones.php');
?>
<table class='contenido' width='100%' id='detallesolicitudes'>
                <tr>
                    <td class='tititems' width='32%'>Concepto</td><td class='tititems' width='10%'>Fecha</td><td class='tititems' width='10%'>Monto</td><td class='tititems' width='10%'>Descargado</td><td class='tititems' width='16%'>Acciones</td>
                </tr>
                <?php
                    $col1="#dedede";
                    $col2="#efefef";
                    $sql="select pr.identregafondos, pr.descripcion, pr.fechasolicitud, pr.nrosolicitud, pr.moneda, pr.montosolicitado, sum(montopagado)
                                 from entregafondos pr left join gastos g on g.identregafondos=pr.identregafondos 
                                 group by pr.identregafondos, pr.descripcion, pr.fechasolicitud, pr.nrosolicitud, pr.moneda, pr.montosolicitado, pr.saldopendiente
                                 order by fechasolicitud";
                    $res=mysql_query($sql);
                    $colactual="";
                    while ($fila=mysql_fetch_array($res)) {
                          if ($colactual!=$col1) {
                             $colactual=$col1;
                          } else {
                             $colactual=$col2;
                          }
                          print "
                <tr bgcolor='$colactual'>
                    <td class='tabladettxt'>".$fila[1]."</td><td class='tabladettxt'>".fecha($fila[2],8)."</td><td  class='tabladetnum'>$fila[4].$fila[5]</td><td  class='tabladettxt'>".($fila[6]<$fila[5] ? "No" : "S&iacute;")."</td><td class='tabladettxt'><a href='solicitud_fondos.php?mode=e&id=".$fila[0]."'><img src=\"images/editar.png\" alt=\"Editar\" title=\"Editar\" width=\"20\" border=\"0\" /></a>
                    <a href='#' onclick=\"window.open('descargo.php?id=".$fila[0]."', '_blank', 'status=0,menubar=0,location=0,scrollbars=0,resizable=0,width=700,height=350', 0);\"><img src=\"images/money.png\" alt=\"Descargo\" title=\"Descargo\" width=\"20\" border=\"0\" /></a> ";
                    print "<a onclick='if(confirm(\"Esta seguro que desea eliminar esta solicitud?\")) { return true;} else { return false; }' href='solicitudes.php?mode=d&id=".$fila[0]."'><img src=\"images/eliminar.png\" alt=\"Eliminar\" title=\"Eliminar\" width=\"20\" border=\"0\" /></a>";
                    print "

                    </td>
                </tr>";
                    }

                ?>
                <tr>
                    <td><br></td>
                </tr>
            </table>