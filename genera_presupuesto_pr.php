<?php

include("valida.php");
$actividad=17;
//$minimo=3;
$iversion=$_GET["idv"];
include("niveles_acceso.php");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php print $nombresistema; ?></title>
<link href="<?php print $path; ?>estilos.css" rel="stylesheet" type="text/css" />
</html><table align="center" bgcolor="#ffffff" width="100%">
<tr>
    <td width="100%">
    <table align="center" width="100%" cellspacing='2'>

            <tr><td width='100%' valign='top'>
          <table class='contenido' width='100%' id='equipo'>

                <?php
    $sql_busca="SELECT v.gestion, v.inicioprogramado, v.finprogramado, p.nombre, p.sigla, c.nombre FROM version v inner join programa p on p.idprograma=v.idprograma inner join ciudad c on c.idciudad=v.ciudad WHERE v.idVersion =".$_GET["idv"];
    $res_busca=mysqli_query($sql_busca);
    if ($fila_busca=mysqli_fetch_array($res_busca)) {
       print "<tr><td colspan='2'><font size='2'><b>Programa:</b> ".$fila_busca[3]." (".$fila_busca[4].")</font></td></tr>";
       print "<tr><td colspan='2'><font size='2'><b>Versi&oacute;n:</b> ".$fila_busca[0]." &nbsp;&nbsp;&nbsp;<b>Ciudad:</b> ".$fila_busca[5]."</font></td></tr>";
    }

                ?>

                <tr>
                    <td colspan="2" class="titmenu">
                        PLANIFICACI&Oacute;N<a name='nuevo'>
                    </td>
                </tr>
<?php

    $sql_col="SELECT sum((1-descuento)*colegiatura*0.87*alumnos), sum(alumnos), sum(matricula*alumnos*.87), v.nromeses, matricula, colegiatura
                       FROM presupuestoingresos pi inner join version v on v.idversion=pi.idversion
                       where v.idversion=".$_GET["idv"];
    $res_col=mysqli_query($sql_col);
    $fila_col=mysqli_fetch_array($res_col);

    $sql_nm="SELECT NroMaterias FROM version mv where idversion=".$_GET["idv"];
    $res_nm=mysqli_query($sql_nm);
    $fila_nm=mysqli_fetch_array($res_nm);


    $sql_nd="SELECT NroDias FROM version pmvd where idversion=".$_GET["idv"];
    $res_nd=mysqli_query($sql_nd);
    $fila_nd=mysqli_fetch_array($res_nd);
    if (is_null($fila_nd[0])) { $fila_nd[0]=0; }

               print "<tr><td class='tabladettxt'>Nro alumnos proyectado:</td><td class='tabladetnum'>$fila_col[1]</td></tr>";
               print "<tr><td class='tabladettxt'>Nro materias:</td><td class='tabladetnum'>$fila_nm[0]</td></tr>";
               print "<tr><td class='tabladettxt'>D&iacute;as de duraci&oacute;n:</td><td class='tabladetnum'>$fila_nd[0]</td></tr>";
               print "<tr><td class='tabladettxt'>Matr&iacute;cula:</td><td class='tabladetnum'>".sprintf("%0.2f",$fila_col[4])."</td></tr>";
               print "<tr><td class='tabladettxt'>Colegiatura:</td><td class='tabladetnum'>".sprintf("%0.2f",$fila_col[5])."</td></tr>";
    ?>
                <tr>
                    <td class='tititems' COLSPAN='2' align='center'>PRESUPUESTO</td>
                </tr>
                <tr>
                    <td class='tititems' width='75%' >Item</td>
                    <td class='tititems' width='25%' align='right'>Monto ($us)</td>
                </tr>
    <?php


    print "<tr><td class='tabladettxt'>Colegiatura</td><td class='tabladetnum'>".sprintf("%0.2f",$fila_col[0])."</td></tr>";
    print "<tr><td class='tabladettxt'>Matricula</td><td class='tabladetnum'>".sprintf("%0.2f",$fila_col[2])."</td></tr>";
    $total_ineto=$fila_col[0]+$fila_col[2];
    print "<tr><td class='tabladettxt'><b>Total ingresos netos</b></td><td class='tabladetnum'><b>".sprintf("%0.2f",$total_ineto)."</b></td></tr>";



    $sql_egre1="SELECT sum(pmvd.honorarioshora*pmvd.horas), sum(pmvd.pasajes),
                   sum(pmvd.viaticosdia*pmvd.dias), sum(pmvd.hospedajedia*pmvd.dias)
                   FROM materiaversion mv inner join materia m on m.idmateria=mv.idmateria left join planmateriaversiondocente pmvd on pmvd.idmateriaversion=mv.idmateriaversion left join recursohumano rh on rh.idrecursohumano=pmvd.idrecursohumano left join categoria c on c.idcategoria=pmvd.idcategoria
                   where mv.idversion=".$_GET["idv"];


    $res_egre1=mysqli_query($sql_egre1);
    $fila_egre1=mysqli_fetch_array($res_egre1);


    $sql_egre="SELECT tg.nombre, pg.valor, idaplicacion, tg.idtiposgastos
                       FROM presupuestogastos pg inner join tiposgastos tg on tg.idtiposgastos=pg.idtiposgastos
                       WHERE idaplicacion=1 and idversion=".$_GET["idv"]."
                union
                SELECT tg.nombre, pg.valor*$fila_col[1], idaplicacion, tg.idtiposgastos
                       FROM presupuestogastos pg inner join tiposgastos tg on tg.idtiposgastos=pg.idtiposgastos
                       WHERE idaplicacion=2 and idversion=".$_GET["idv"]."
                union
                SELECT tg.nombre, pg.valor*$fila_nm[0]*$fila_col[1], idaplicacion, tg.idtiposgastos
                       FROM presupuestogastos pg inner join tiposgastos tg on tg.idtiposgastos=pg.idtiposgastos
                       WHERE idaplicacion=3 and idversion=".$_GET["idv"]."
                union
                SELECT tg.nombre, pg.valor*$fila_nd[0]*$fila_col[1], idaplicacion, tg.idtiposgastos
                       FROM presupuestogastos pg inner join tiposgastos tg on tg.idtiposgastos=pg.idtiposgastos
                       WHERE idaplicacion=4 and idversion=".$_GET["idv"]."
                union
                SELECT tg.nombre, pg.valor*cf.monto, idaplicacion, tg.idtiposgastos
                       FROM presupuestogastos pg inner join tiposgastos tg on tg.idtiposgastos=pg.idtiposgastos,
                            (SELECT sum(pg.valor) as monto FROM presupuestogastos pg WHERE idaplicacion=1 and idversion=".$_GET["idv"].") cf
                       WHERE idaplicacion=5 and idversion=".$_GET["idv"]."
                union
                SELECT tg.nombre, pg.valor/100*($fila_col[0]+$fila_col[2]), idaplicacion, tg.idtiposgastos
                       FROM presupuestogastos pg inner join tiposgastos tg on tg.idtiposgastos=pg.idtiposgastos
                       WHERE idaplicacion=6 and idversion=".$_GET["idv"]."
                union
                SELECT tg.nombre, pg.valor*tt.total, idaplicacion, tg.idtiposgastos
                       FROM presupuestogastos pg inner join tiposgastos tg on tg.idtiposgastos=pg.idtiposgastos,
                            (select sum(valor) as total from
                                    (SELECT pg.valor*$fila_col[1] as valor FROM presupuestogastos pg inner join tiposgastos tg on tg.idtiposgastos=pg.idtiposgastos
                                            WHERE idaplicacion=2 and idversion=".$_GET["idv"]."
                                     union
                                     SELECT pg.valor*$fila_nm[0]*$fila_col[1] as valor FROM presupuestogastos pg inner join tiposgastos tg on tg.idtiposgastos=pg.idtiposgastos
                                            WHERE idaplicacion=3 and idversion=".$_GET["idv"]."
                                     union
                                     SELECT pg.valor*$fila_nd[0]*$fila_col[1] as valor FROM presupuestogastos pg inner join tiposgastos tg on tg.idtiposgastos=pg.idtiposgastos
                                            WHERE idaplicacion=4 and idversion=".$_GET["idv"].") as totalvar) tt WHERE idaplicacion=7 and idversion=".$_GET["idv"]."
                                    ";

        $res_egre=mysqli_query($sql_egre);
        $total_c=0;

        $total_c+=$fila_egre1[0]+$fila_egre1[1]+$fila_egre1[2]+$fila_egre1[3];
        print "<tr><td class='tabladettxt'>Honorarios profesionales</td><td class='tabladetnum'>".sprintf("%0.2f",$fila_egre1[0])."</td></tr>";
        print "<tr><td class='tabladettxt'>Pasajes</td><td class='tabladetnum'>".sprintf("%0.2f",$fila_egre1[1])."</td></tr>";
        print "<tr><td class='tabladettxt'>Viaticos</td><td class='tabladetnum'>".sprintf("%0.2f",$fila_egre1[2])."</td></tr>";
        print "<tr><td class='tabladettxt'>Hospedaje</td><td class='tabladetnum'>".sprintf("%0.2f",$fila_egre1[3])."</td></tr>";

        $overh=0;
        while ($fila_egre=mysqli_fetch_array($res_egre)) {
              if ($fila_egre[3]==17) {
                 $overh=$fila_egre[1];
              }
              $total_c+=$fila_egre[1];
              print "<tr><td class='tabladettxt'>$fila_egre[0]</td><td class='tabladetnum'>".sprintf("%0.2f",$fila_egre[1])."</td></tr>";
        }
      print "<tr><td class='tabladettxt'><b>Total costos</b></td><td class='tabladetnum'><b>".sprintf("%0.2f",$total_c)."</b></td></tr>";
      print "<tr><td class='tabladettxt'><b>Super&aacute;vit / D&eacute;ficit (Con Overhead)</b></td><td class='tabladetnum'><b>".sprintf("%0.2f",($total_ineto-$total_c))."</b></td></tr>";
      print "<tr><td class='tabladettxt'><b>Super&aacute;vit / D&eacute;ficit (Sin Overhead)</b></td><td class='tabladetnum'><b>".sprintf("%0.2f",($total_ineto-$total_c+$overh))."</b></td></tr>";
?>
             </table>
<script language="javascript">window.print();</script>
    </td>
    </tr>
    </table>
</td>
</tr>
</table>
</td></tr></table>

</body>
</html>