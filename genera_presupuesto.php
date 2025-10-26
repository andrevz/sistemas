<?php

include("valida.php");
$actividad=17;
//$minimo=3;
$iversion=$_GET["idv"];
include("niveles_acceso.php");


$sql_blo="select estado from version where idversion=".$_GET["idv"];;
$res_blo=mysql_query($sql_blo);
$fila_blo=mysql_fetch_array($res_blo);
$bloqueado=($fila_blo[0]==0 ? false : true);

if (isset($_POST["Guardar"]) && $editar && !$bloqueado) {

    $sql_col="SELECT sum((1-descuento)*colegiatura*0.87*alumnos), sum(alumnos), sum(matricula*alumnos*.87), v.nromeses, matricula, colegiatura
                       FROM presupuestoingresos pi inner join version v on v.idversion=pi.idversion
                       where v.idversion=".$_POST["idv"];
    $res_col=mysql_query($sql_col);
    $fila_col=mysql_fetch_array($res_col);

    $sql_nm="SELECT count(*) FROM materiaversion mv where idversion=".$_POST["idv"];
    $res_nm=mysql_query($sql_nm);
    $fila_nm=mysql_fetch_array($res_nm);


    $sql_nd="SELECT sum(dias) FROM planmateriaversiondocente pmvd inner join materiaversion mv on mv.idmateriaversion=pmvd.idmateriaversion where idversion=".$_POST["idv"];
    $res_nd=mysql_query($sql_nd);
    $fila_nd=mysql_fetch_array($res_nd);
    if (is_null($fila_nd[0])) { $fila_nd[0]=0; }
    $sql_busca="select * from pres_version where idversion=".$_POST["idv"];
    $res_busca=mysql_query($sql_busca);
    if ($fila_Busca=mysql_fetch_array($res_busca)) {
       $sql_pr="update pres_version set nromeses='$fila_col[3]', nroalumnos='$fila_col[1]', nromaterias='$fila_nm[0]', dias='$fila_nd[0]', matricula='$fila_col[4]', colegiatura='$fila_col[5]' where idversion=".$_POST["idv"];
       $del_det="delete from pres_detalle where idversion=".$_POST["idv"];
       mysql_query($del_det);
    } else {
       $sql_pr="insert into pres_version values (".$_POST["idv"].", '$fila_col[1]', '$fila_nm[0]', '$fila_nd[0]', '$fila_col[4]', '$fila_col[5]', '$fila_col[3]')";
    }

    mysql_query($sql_pr);
    $sql_det="insert into pres_detalle values (null, 'Matricula', '$fila_col[2]', ".$_POST["idv"].", 1)";
    mysql_query($sql_det);
    $sql_det="insert into pres_detalle values (null, 'Colegiatura', '$fila_col[0]', ".$_POST["idv"].", 1)";
    mysql_query($sql_det);

    $sql_egre1="SELECT sum(pmvd.honorarioshora*pmvd.horas), sum(pmvd.pasajes),
                   sum(pmvd.viaticosdia*pmvd.dias), sum(pmvd.hospedajedia*pmvd.dias)
                   FROM materiaversion mv inner join materia m on m.idmateria=mv.idmateria left join planmateriaversiondocente pmvd on pmvd.idmateriaversion=mv.idmateriaversion left join recursohumano rh on rh.idrecursohumano=pmvd.idrecursohumano left join categoria c on c.idcategoria=pmvd.idcategoria
                   where mv.idversion=".$_POST["idv"];

    $res_egre1=mysql_query($sql_egre1);
    $fila_egre1=mysql_fetch_array($res_egre1);


    $sql_egre="SELECT tg.nombre, pg.valor, idaplicacion, tg.idtiposgastos
                       FROM presupuestogastos pg inner join tiposgastos tg on tg.idtiposgastos=pg.idtiposgastos
                       WHERE idaplicacion=1 and idversion=".$_POST["idv"]."
                union
                SELECT tg.nombre, pg.valor*$fila_col[1], idaplicacion, tg.idtiposgastos
                       FROM presupuestogastos pg inner join tiposgastos tg on tg.idtiposgastos=pg.idtiposgastos
                       WHERE idaplicacion=2 and idversion=".$_POST["idv"]."
                union
                SELECT tg.nombre, pg.valor*$fila_nm[0]*$fila_col[1], idaplicacion, tg.idtiposgastos
                       FROM presupuestogastos pg inner join tiposgastos tg on tg.idtiposgastos=pg.idtiposgastos
                       WHERE idaplicacion=3 and idversion=".$_POST["idv"]."
                union
                SELECT tg.nombre, pg.valor*$fila_nd[0]*$fila_col[1], idaplicacion, tg.idtiposgastos
                       FROM presupuestogastos pg inner join tiposgastos tg on tg.idtiposgastos=pg.idtiposgastos
                       WHERE idaplicacion=4 and idversion=".$_POST["idv"]."
                union
                SELECT tg.nombre, pg.valor*cf.monto, idaplicacion, tg.idtiposgastos
                       FROM presupuestogastos pg inner join tiposgastos tg on tg.idtiposgastos=pg.idtiposgastos,
                            (SELECT sum(pg.valor) as monto FROM presupuestogastos pg WHERE idaplicacion=1 and idversion=".$_POST["idv"].") cf
                       WHERE idaplicacion=5 and idversion=".$_POST["idv"]."
                union
                SELECT tg.nombre, pg.valor/100*($fila_col[0]+$fila_col[2]), idaplicacion, tg.idtiposgastos
                       FROM presupuestogastos pg inner join tiposgastos tg on tg.idtiposgastos=pg.idtiposgastos
                       WHERE idaplicacion=6 and idversion=".$_POST["idv"]."
                union
                SELECT tg.nombre, pg.valor*tt.total, idaplicacion, tg.idtiposgastos
                       FROM presupuestogastos pg inner join tiposgastos tg on tg.idtiposgastos=pg.idtiposgastos,
                            (select sum(valor) as total from
                                    (SELECT pg.valor*$fila_col[1] as valor FROM presupuestogastos pg inner join tiposgastos tg on tg.idtiposgastos=pg.idtiposgastos
                                            WHERE idaplicacion=2 and idversion=".$_POST["idv"]."
                                     union
                                     SELECT pg.valor*$fila_nm[0]*$fila_col[1] as valor FROM presupuestogastos pg inner join tiposgastos tg on tg.idtiposgastos=pg.idtiposgastos
                                            WHERE idaplicacion=3 and idversion=".$_POST["idv"]."
                                     union
                                     SELECT pg.valor*$fila_nd[0]*$fila_col[1] as valor FROM presupuestogastos pg inner join tiposgastos tg on tg.idtiposgastos=pg.idtiposgastos
                                            WHERE idaplicacion=4 and idversion=".$_POST["idv"].") as totalvar) tt WHERE idaplicacion=7 and idversion=".$_POST["idv"]."
                                    ";

        $res_egre=mysql_query($sql_egre);

        $sql_det="insert into pres_detalle values (null, 'Honorarios profesionales', '$fila_egre1[0]', ".$_POST["idv"].", 0)";
        mysql_query($sql_det);
        $sql_det="insert into pres_detalle values (null, 'Pasajes', '$fila_egre1[1]', ".$_POST["idv"].", 0)";
        mysql_query($sql_det);
        $sql_det="insert into pres_detalle values (null, 'Viaticos', '$fila_egre1[2]', ".$_POST["idv"].", 0)";
        mysql_query($sql_det);
        $sql_det="insert into pres_detalle values (null, 'Hospedaje', '$fila_egre1[3]', ".$_POST["idv"].", 0)";
        mysql_query($sql_det);


        while ($fila_egre=mysql_fetch_array($res_egre)) {
              $sql_det="insert into pres_detalle values (null, '$fila_egre[0]', '$fila_egre[1]', ".$_POST["idv"].", 0)";
              mysql_query($sql_det);
        }
        ?>
        <script language='javascript'>
        alert('Se actualizó la planificación');
        window.close();</script>
        <?php

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
    <td width="100%">
    <table align="center" width="100%" cellspacing='2'>

            <tr><td width='100%' valign='top'>
          <table class='contenido' width='100%' id='equipo'>

                <?php
    $sql_busca="SELECT v.gestion, v.inicioprogramado, v.finprogramado, p.nombre, p.sigla, c.nombre FROM version v inner join programa p on p.idprograma=v.idprograma inner join ciudad c on c.idciudad=v.ciudad WHERE v.idVersion =".$_GET["idv"];
    $res_busca=mysql_query($sql_busca);
    if ($fila_busca=mysql_fetch_array($res_busca)) {
       print "<tr><td colspan='2'><font color='red'>ATENCIÓN: La versión elegida ya tiene registrada una planificación presupuestaria, si elige guardar se actualizará con los datos que tiene en pantalla.</font></td></tr>";
       print "<tr><td colspan='2'><font size='2'><b>Programa:</b> ".$fila_busca[3]." (".$fila_busca[4].")</font></td></tr>";
       print "<tr><td colspan='2'><font size='2'><b>Versi&oacute;n:</b> ".$fila_busca[0]." &nbsp;&nbsp;&nbsp;<b>Ciudad:</b> ".$fila_busca[5]."</font></td></tr>";
    }

                ?>

                <tr>
                    <td colspan="2" class="titmenu">
                        GENERAR / ACTUALIZAR PLANIFICACI&Oacute;N<a name='nuevo'>
                    </td>
                </tr>
<?php

    $sql_col="SELECT sum((1-descuento)*colegiatura*0.87*alumnos), sum(alumnos), sum(matricula*alumnos*.87), v.nromeses, matricula, colegiatura
                       FROM presupuestoingresos pi inner join version v on v.idversion=pi.idversion
                       where v.idversion=".$_GET["idv"];
    $res_col=mysql_query($sql_col);
    $fila_col=mysql_fetch_array($res_col);

    $sql_nm="SELECT NroMaterias FROM version mv where idversion=".$_GET["idv"];
    $res_nm=mysql_query($sql_nm);
    $fila_nm=mysql_fetch_array($res_nm);


    $sql_nd="SELECT NroDias FROM version pmvd where idversion=".$_GET["idv"];
    $res_nd=mysql_query($sql_nd);
    $fila_nd=mysql_fetch_array($res_nd);
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


    $res_egre1=mysql_query($sql_egre1);
    $fila_egre1=mysql_fetch_array($res_egre1);


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

        $res_egre=mysql_query($sql_egre);
        $total_c=0;

        $total_c+=$fila_egre1[0]+$fila_egre1[1]+$fila_egre1[2]+$fila_egre1[3];
        print "<tr><td class='tabladettxt'>Honorarios profesionales</td><td class='tabladetnum'>".sprintf("%0.2f",$fila_egre1[0])."</td></tr>";
        print "<tr><td class='tabladettxt'>Pasajes</td><td class='tabladetnum'>".sprintf("%0.2f",$fila_egre1[1])."</td></tr>";
        print "<tr><td class='tabladettxt'>Viaticos</td><td class='tabladetnum'>".sprintf("%0.2f",$fila_egre1[2])."</td></tr>";
        print "<tr><td class='tabladettxt'>Hospedaje</td><td class='tabladetnum'>".sprintf("%0.2f",$fila_egre1[3])."</td></tr>";

        $overh=0;
        while ($fila_egre=mysql_fetch_array($res_egre)) {
              if ($fila_egre[3]==17) {
                 $overh=$fila_egre[1];
              }
              $total_c+=$fila_egre[1];
              print "<tr><td class='tabladettxt'>$fila_egre[0]</td><td class='tabladetnum'>".sprintf("%0.2f",$fila_egre[1])."</td></tr>";
        }
      print "<tr><td class='tabladettxt'><b>Total costos</b></td><td class='tabladetnum'><b>".sprintf("%0.2f",$total_c)."</b></td></tr>";
      print "<tr><td class='tabladettxt'><b>Superávit / Déficit (Con Overhead)</b></td><td class='tabladetnum'><b>".sprintf("%0.2f",($total_ineto-$total_c))."</b></td></tr>";
      print "<tr><td class='tabladettxt'><b>Superávit / Déficit (Sin Overhead)</b></td><td class='tabladetnum'><b>".sprintf("%0.2f",($total_ineto-$total_c+$overh))."</b></td></tr>";
?>

                                 <form method="POST" name="actualizar">
                                 <input type='hidden' name='idv' value="<?php print $_GET["idv"];?>">
                    <tr><td align='right'><a name='editor' > </a>
                     <?php
                     if ($editar && !$bloqueado) {
                     ?>
                     <input class="save" name="Guardar" type="submit" value="" >
                     <?php
                     }
                     ?>
                    <input class="cancel" name="Cancelar" type="button" onClick="window.close()" >
                    <input value="Imprimir" name="Imprimir" type="button" onClick="window.location='genera_presupuesto_pr.php?idv=<?php print $_GET["idv"];?>'" >

                    </td></tr></form>
             </table>

    </td>
    </tr>
    </table>
</td>
</tr>
</table>
</td></tr></table>

</body>
</html>