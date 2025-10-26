<?php

require_once('../config.php');
mysqli_set_charset('latin1', $simulacion);
include("../valida.php");

$actividad=31;
include("../niveles_acceso.php");

require('../fpdf/fpdf.php');



class VariableStream
{
    var $varname;
    var $position;

    function stream_open($path, $mode, $options, &$opened_path)
    {
        $url = parse_url($path);
        $this->varname = $url['host'];
        if(!isset($GLOBALS[$this->varname]))
        {
            trigger_error('Global variable '.$this->varname.' does not exist', E_USER_WARNING);
            return false;
        }
        $this->position = 0;
        return true;
    }

    function stream_read($count)
    {
        $ret = substr($GLOBALS[$this->varname], $this->position, $count);
        $this->position += strlen($ret);
        return $ret;
    }

    function stream_eof()
    {
        return $this->position >= strlen($GLOBALS[$this->varname]);
    }

    function stream_tell()
    {
        return $this->position;
    }

    function stream_seek($offset, $whence)
    {
        if($whence==SEEK_SET)
        {
            $this->position = $offset;
            return true;
        }
        return false;
    }
    
    function stream_stat()
    {
        return array();
    }
}

class PDF_MemImage extends FPDF
{
    function PDF_MemImage($orientation='P', $unit='mm', $format='A4')
    {
        $this->FPDF($orientation, $unit, $format);
        //Register var stream protocol
        stream_wrapper_register('var', 'VariableStream');
    }

    function MemImage($data, $x=null, $y=null, $w=0, $h=0, $link='')
    {
        //Display the image contained in $data
        $v = 'img'.md5($data);
        $GLOBALS[$v] = $data;
        $a = getimagesize('var://'.$v);
        if(!$a)
            $this->Error('Invalid image data');
        $type = substr(strstr($a['mime'],'/'),1);
        $this->Image('var://'.$v, $x, $y, $w, $h, $type, $link);
        unset($GLOBALS[$v]);
    }

    function GDImage($im, $x=null, $y=null, $w=0, $h=0, $link='')
    {
        //Display the GD image associated to $im
        ob_start();
        imagepng($im);
        $data = ob_get_clean();
        $this->MemImage($data, $x, $y, $w, $h, $link);
    }

    function Header()
    {
        //Logo
        $this->Image('../images/logo.jpg',10,10,20);
        //Arial bold 15


    //    $this->SetFillColor(230,240,255);

        $this->SetFont('Arial','B',14);
        $this->Cell(50);
        $this->Cell(100,7,'SISTEMA DE GESTION DE PROYECTOS',0,1,'C');
        $this->SetFont('Arial','B',12);
        $this->Cell(50);
        $this->Cell(100,7,'CRONOGRAMA',0,1,'C');
        $this->SetFont('Arial','B',12);

        $this->Cell(70);
        $this->SetFont('Arial','B',11);
//        $this->Cell(50,5,'Gestion: '.($_SESSION['Anio']-$_SESSION['Periodo']+$_GET["p"]),0,0,'C');
//        $this->Rect(10,29,190,0);
//        $this->Rect(10,30,190,0);
          $this->Ln(5);
    }

    function Footer()
    {
        //PosiciÃ³nÂ a 1,5 cm del final
        $this->SetY(-15);
        $this->Rect(10,280,190,0);
        //Arial italic 8
        $this->SetFont('Arial','I',8);
        
        $this->Cell(60,5,"Sistema de Gestion de Proyectos v. 1.0",0,0,'L');
        $this->Cell(60,5,'(c) 2012 Mario A. Antezana Y�gar',0,0,'C');
        $this->Cell(0,5,'Pagina '.$this->PageNo().'/{nb}',0,0,'R');
    }
}



//Creacion del objeto de la clase heredada
$pdf=new PDF_MemImage();
$pdf->AliasNbPages();


    $sql_versiones="select idversion, p.idescuela, v.ciudad, p.idtipoprograma
                           from version v inner join programa p on p.idprograma=v.idprograma
                           where p.idtipoprograma like '".$_GET["idtp"]."' and v.idprograma like '".$_GET["idp"]."'
                                 and ciudad like '".$_GET["ciudad"]."' and gestion like '".$_GET["gestion"]."'";
    $res_versiones=mysqli_query($sql_versiones);
    while ($fila_versiones=mysqli_fetch_array($res_versiones)) {
       if (acceso($_SESSION['idRol'], $fila_versiones[0],$fila_versiones[1],$fila_versiones[3],$fila_versiones[2],0,0)>=2) {
          $pdf->AddPage();
          $pdf->SetFont('Arial','',10);

          $pdf->SetFillColor(230,230,230);

          $sql_i="SELECT v.gestion, c.nombre, v.colegiatura, v.nrodias, v.nromeses, v.inicioprogramado, v.finprogramado, v.matricula, p.nombre, p.sigla, rh.nombres, rh.apellidos, v.nromaterias
                         FROM version v inner join programa p on p.idprograma=v.idprograma inner join recursohumano rh on rh.idrecursohumano=v.idrecursohumano
                         inner join ciudad c on c.idciudad=v.ciudad
                         where v.idversion=".$fila_versiones[0];
          $res=mysqli_query($sql_i);
          $fila=mysqli_fetch_array($res);

          $pdf->Cell(35,5,"Programa:",1,0,'L');
          $pdf->SetFont('Arial','B',10);
          $pdf->Cell(155,5,substr($fila[8],0,60)." (".$fila[9].")",1,1,'L');
          $pdf->SetFont('Arial','',10);
          $pdf->Cell(35,5,"Versi�n",1,0,'L');
          $pdf->Cell(60,5,$fila[0],1,0,'L');
          $pdf->Cell(35,5,"Ciudad:",1,0,'L');
          $pdf->Cell(60,5,$fila[1],1,1,'L');
          $pdf->Cell(35,5,"D�as de clase:",1,0,'L');
          $pdf->Cell(60,5,$fila[3],1,0,'L');
          $pdf->Cell(35,5,"Nro. de meses:",1,0,'L');
          $pdf->Cell(60,5,$fila[4],1,1,'L');
          $pdf->Cell(35,5,"Responsable:",1,0,'L');
          $pdf->Cell(60,5,substr($fila[11].", ".$fila[10],0,25),1,0,'L');
          $pdf->Cell(35,5,"Nro. Materias:",1,0,'L');
          $pdf->Cell(60,5,$fila["nromaterias"],1,1,'L');
          $pdf->Cell(35,5,"Inicio Programado:",1,0,'L');
          $pdf->Cell(60,5,fecha($fila[5],3),1,0,'L');
          $pdf->Cell(35,5,"Fin programado:",1,0,'L');
          $pdf->Cell(60,5,fecha($fila[6],3),1,1,'L');

          $pdf->Cell(70);
          $pdf->SetFont('Arial','B',13);
          $pdf->Cell(50,7,'MATERIAS',0,1,'C');

          $sql_m="SELECT mv.universidad, m.nombre, '', pmvd.horas, pmvd.honorarioshora, pmvd.pasajes,
                         pmvd.viaticosdia, pmvd.dias, pmvd.hospedajedia, pmvd.inicio, pmvd.fin, concat(substring(apellidos, 1, if(instr(apellidos,' ')>0,instr(apellidos,' ')-1,length(apellidos))), ', ', substring(nombres, 1, if(instr(nombres,' ')>0,instr(nombres,' ')-1,length(nombres)))),
                         c.nombre, mv.idmateria
                         FROM materiaversion mv inner join materia m on m.idmateria=mv.idmateria left join planmateriaversiondocente pmvd on pmvd.idmateriaversion=mv.idmateriaversion left join recursohumano rh on rh.idrecursohumano=pmvd.idrecursohumano left join categoria c on c.idcategoria=pmvd.idcategoria
                         where mv.idversion=".$fila_versiones[0]." order by orden, mv.idmateriaversion";


          $pdf->SetFont('Arial','B',8);
          $pdf->Cell(7,5,"Nro",1,0,'L');
          $pdf->Cell(70,5,"Materia",1,0,'L');
//          $pdf->Cell(17,5,"Tipo",1,0,'L');
          $pdf->Cell(10,5,"Univ.",1,0,'L');
          $pdf->Cell(60,5,"Docente",1,0,'L');
          $pdf->Cell(8,5,"Hrs.",1,0,'L');
          $pdf->Cell(8,5,"Dias",1,0,'L');
/*          $pdf->Cell(9,5,"Hon.",1,0,'L');
          $pdf->Cell(9,5,"Pasa.",1,0,'L');
          $pdf->Cell(8,5,"Viat.",1,0,'L');
          $pdf->Cell(10,5,"Hosp.",1,0,'L'); */
          $pdf->Cell(13,5,"Inicio",1,0,'L');
          $pdf->Cell(13,5,"Fin",1,1,'L');

          $i=0;

          $res_m=mysqli_query($sql_m);
          $pdf->SetFont('Arial','',8);

          $total_hrs=0;
          $total_dias=0;
          $total_hon=0;
          $total_viat=0;
          $total_hosp=0;
          $total_pasa=0;

          $materia="";

          while ($fila_m=mysqli_fetch_array($res_m)) {
//                if ($fila_m[13]!=$materia) {
                    $i++;
                    $materia=$fila_m[13];
                    $pdf->Cell(7,4,$i,1,0,'R');
                    $pdf->Cell(70,4,substr($fila_m[1],0,31).(strlen($fila_m[1])>50 ? "..." : ""),1,0,'L');
//                    $pdf->Cell(17,4,$fila_m[2],1,0,'L');
                    $pdf->Cell(10,4,substr($fila_m[0],0,5),1,0,'L');
/*                } else {
                    $pdf->Cell(7,4,"",0,0,'R');
                    $pdf->Cell(53,4,"",0,0,'L');
                    $pdf->Cell(17,4,"",0,0,'L');
                    $pdf->Cell(10,4,"",0,0,'L');
                } */
                $pdf->Cell(60,4,substr($fila_m[11],0,20),1,0,'L');
                $pdf->Cell(8,4,$fila_m[3],1,0,'R');
                $pdf->Cell(8,4,$fila_m[7],1,0,'R');
/*                $pdf->Cell(9,4,round(($fila_m[4]*$fila_m[3]),0),1,0,'R');
                $pdf->Cell(9,4,($fila_m[5]),1,0,'R');
                $pdf->Cell(8,4,($fila_m[6]*$fila_m[7]),1,0,'R');
                $pdf->Cell(10,4,($fila_m[7]*$fila_m[8]),1,0,'R'); */
                $pdf->Cell(13,4,fecha($fila_m[9],1),1,0,'L');
                $pdf->Cell(13,4,fecha($fila_m[10],1),1,1,'L');

                $total_hrs+=$fila_m[3];
                $total_dias+=$fila_m[7];
                $total_pasa+=$fila_m[5];
                $total_hon+=($fila_m[4]*$fila_m[3]);
                $total_viat+=($fila_m[6]*$fila_m[7]);
                $total_hosp+=($fila_m[7]*$fila_m[8]);

          }

/*          $i=$fila["nromaterias"];
          $pdf->Cell(7,4,"",1,0,'R');
          $pdf->Cell(60,4,"TOTALES",1,0,'L');
//          $pdf->Cell(17,4,"",1,0,'L');
          $pdf->Cell(10,4,"",1,0,'L');
          $pdf->Cell(32,4,"",1,0,'L');
          $pdf->Cell(8,4,$total_hrs,1,0,'R');
          $pdf->Cell(8,4,$total_dias,1,0,'R');
          $pdf->Cell(9,4,round($total_hon,0),1,0,'R');
          $pdf->Cell(9,4,round($total_pasa,0),1,0,'R');
          $pdf->Cell(8,4,round($total_viat,0),1,0,'R');
          $pdf->Cell(10,4,round($total_hosp,0),1,0,'R');
          $pdf->Cell(13,4,"",1,0,'L');
          $pdf->Cell(13,4,"",1,1,'L');
          $pdf->Cell(13,3,"",0,1,'L');

          $pdf->SetFont('Arial','B',13);
          $pdf->Cell(70,7,'INGRESOS PRESUPUESTADOS',0,0,'C');
          $pdf->Cell(5,7,'',0,0,'C');
          $pdf->Cell(100,7,'COSTOS PRESUPUESTADOS',0,1,'C');

              $sql_m="SELECT (1-descuento)*colegiatura*0.87, alumnos, descuento*100
                             FROM presupuestoingresos pi inner join version v on v.idversion=pi.idversion
                             where alumnos>0 and v.idversion=".$fila_versiones[0];
              $res_m=mysqli_query($sql_m);

              $sql_o="SELECT sum(alumnos), sum(((1-descuento)*colegiatura*0.87)*alumnos)+sum(alumnos*matricula*.87)
                             FROM presupuestoingresos pi inner join version v on v.idversion=pi.idversion
                             where alumnos>0 and v.idversion=".$fila_versiones[0];
              $res_o=mysqli_query($sql_o);
              $fila_o=mysqli_fetch_array($res_o);
              if (!($fila_o[0]>0)) { $fila_o[0]=1; }
              if (!($fila_o[1]>0)) { $fila_o[1]=1; }
              $total_dias=$fila["nrodias"];
              $sql_g="SELECT tg.nombre, pg.valor, idaplicacion, tg.idtiposgastos
                             FROM presupuestogastos pg inner join tiposgastos tg on tg.idtiposgastos=pg.idtiposgastos
                             WHERE idaplicacion=1 and idversion=".$fila_versiones[0]."
                      union
                      SELECT tg.nombre, pg.valor*$fila_o[0], idaplicacion, tg.idtiposgastos
                             FROM presupuestogastos pg inner join tiposgastos tg on tg.idtiposgastos=pg.idtiposgastos
                             WHERE idaplicacion=2 and idversion=".$fila_versiones[0]."
                      union
                      SELECT tg.nombre, pg.valor*$i*$fila_o[0], idaplicacion, tg.idtiposgastos
                             FROM presupuestogastos pg inner join tiposgastos tg on tg.idtiposgastos=pg.idtiposgastos
                             WHERE idaplicacion=3 and idversion=".$fila_versiones[0]."
                      union
                      SELECT tg.nombre, pg.valor*$total_dias*$fila_o[0], idaplicacion, tg.idtiposgastos
                             FROM presupuestogastos pg inner join tiposgastos tg on tg.idtiposgastos=pg.idtiposgastos
                             WHERE idaplicacion=4 and idversion=".$fila_versiones[0]."
                      union
                      SELECT tg.nombre, pg.valor*cf.monto, idaplicacion, tg.idtiposgastos
                             FROM presupuestogastos pg inner join tiposgastos tg on tg.idtiposgastos=pg.idtiposgastos,
                                  (SELECT sum(pg.valor) as monto FROM presupuestogastos pg WHERE idaplicacion=1 and idversion=".$fila_versiones[0].") cf
                             WHERE idaplicacion=5 and idversion=".$fila_versiones[0]."
                      union
                      SELECT tg.nombre, pg.valor/100*$fila_o[1], idaplicacion, tg.idtiposgastos
                             FROM presupuestogastos pg inner join tiposgastos tg on tg.idtiposgastos=pg.idtiposgastos
                             WHERE idaplicacion=6 and idversion=".$fila_versiones[0]."
                      union
                      SELECT tg.nombre, pg.valor*tt.total, idaplicacion, tg.idtiposgastos
                             FROM presupuestogastos pg inner join tiposgastos tg on tg.idtiposgastos=pg.idtiposgastos,
                                  (select sum(valor) as total from
                                          (SELECT pg.valor*$fila_o[0] as valor FROM presupuestogastos pg inner join tiposgastos tg on tg.idtiposgastos=pg.idtiposgastos
                                                  WHERE idaplicacion=2 and idversion=".$fila_versiones[0]."
                                           union
                                           SELECT pg.valor*$i*$fila_o[0] as valor FROM presupuestogastos pg inner join tiposgastos tg on tg.idtiposgastos=pg.idtiposgastos
                                                  WHERE idaplicacion=3 and idversion=".$fila_versiones[0]."
                                           union
                                           SELECT pg.valor*$total_dias*$fila_o[0] as valor FROM presupuestogastos pg inner join tiposgastos tg on tg.idtiposgastos=pg.idtiposgastos
                                                  WHERE idaplicacion=4 and idversion=".$fila_versiones[0].") as totalvar) tt WHERE idaplicacion=7 and idversion=".$fila_versiones[0]."
                                           ";

//          print $sql_g;
//          exit;
          $res_g=mysqli_query($sql_g);
          $pdf->SetFont('Arial','B',9);
          $pdf->Cell(20,5,"Descuento",1,0,'L');
          $pdf->Cell(20,5,"Monto",1,0,'L');
          $pdf->Cell(20,5,"# alumnos",1,0,'L');
          $pdf->Cell(20,5,"Ingreso",1,0,'L');

          $pdf->Cell(10,5,"",0,0,'L');
          $pdf->Cell(55,5,"Concepto",1,0,'L');
          $pdf->Cell(20,5,"Monto Mes",1,0,'L');
          $pdf->Cell(20,5,"Monto Total",1,1,'L');
          $posicionx=$pdf->GetY();
          $i=0;

          $pdf->SetFont('Arial','',8);

          $total_ingreso=0;
          $total_alumnos=0;

          while ($fila_m=mysqli_fetch_array($res_m)) {
                $i++;
                $pdf->Cell(20,4,$fila_m[2]."%",1,0,'R');
                $pdf->Cell(20,4,round($fila_m[0],0),1,0,'R');
                $pdf->Cell(20,4,$fila_m[1],1,0,'R');
                $pdf->Cell(20,4,round($fila_m[0]*$fila_m[1],0),1,1,'R');

                $total_ingreso+=($fila_m[0]*$fila_m[1]);
                $total_alumnos+=$fila_m[1];
          }

          if ($total_alumnos==0) { $total_alumnos=1; }
          if ($total_ingreso==0) { $total_ingreso=1; }

          $pdf->SetFont('Arial','I',8);
          $pdf->Cell(40,4,"Colegiatura",1,0,'L');
          $pdf->SetFont('Arial','',8);
          $pdf->Cell(20,4,$total_alumnos,1,0,'R');
          $pdf->Cell(20,4,round($total_ingreso,0),1,1,'R');
          $pdf->SetFont('Arial','I',8);
          $pdf->Cell(40,4,"Matricula",1,0,'L');
          $pdf->SetFont('Arial','',8);
          $pdf->Cell(20,4,$total_alumnos,1,0,'R');
          $pdf->Cell(20,4,round($total_alumnos*$fila[7]*.87,0),1,1,'R');
          $pdf->Cell(60,4,"TOTAL INGRESOS NETOS",1,0,'L');
          $total_matricula=$total_alumnos*$fila[7]*.87;
          $pdf->Cell(20,4,round($total_ingreso+$total_matricula,0),1,1,'R');
          $pdf->Cell(60,4,"INGRESO PROMEDIO MENSUAL",1,0,'L');
          $pdf->Cell(20,4,round(($total_ingreso+$total_matricula)/($fila[4]>0 ? $fila[4] : 1),0),1,1,'R');

          $posicionxi=$pdf->GetY();
          $numpag=$pdf->PageNo();

          $pdf->SetY($posicionx);
      //    $pdf->SetX(100);

          $total_gasto=0;
          $costo_fijo=0;
          $costo_variable=0;
          $pdf->SetFont('Arial','I',8);
          $pdf->Cell(90,4,"",0,0,'R');
          $pdf->Cell(55,4,"Costo docentes",1,0,'L');
          $pdf->Cell(20,4,"",1,0,'R');
          $pdf->Cell(20,4,"",1,1,'R');
          $pdf->SetFont('Arial','',8);

          $pdf->SetFont('Arial','',8);
          $pdf->Cell(90,4,"",0,0,'R');
          $pdf->Cell(55,4,"   Honorarios profesionales",1,0,'L');
          $pdf->Cell(20,4,round($total_hon/($fila[4]>0 ? $fila[4] : 1),0),1,0,'R');
          $pdf->Cell(20,4,round($total_hon,0),1,1,'R');
          $costo_fijo+=$total_hon;
          $total_gasto+=$total_hon;

          $pdf->Cell(90,4,"",0,0,'R');
          $pdf->Cell(55,4,"   Pasajes",1,0,'L');
          $pdf->Cell(20,4,round($total_pasa/($fila[4]>0 ? $fila[4] : 1),0),1,0,'R');
          $pdf->Cell(20,4,$total_pasa,1,1,'R');
          $costo_fijo+=$total_pasa;
          $total_gasto+=$total_pasa;

          $pdf->Cell(90,4,"",0,0,'R');
          $pdf->Cell(55,4,"   Viaticos",1,0,'L');
          $pdf->Cell(20,4,round($total_viat/($fila[4]>0 ? $fila[4] : 1),0),1,0,'R');
          $pdf->Cell(20,4,$total_viat,1,1,'R');
          $costo_fijo+=$total_viat;
          $total_gasto+=$total_viat;

          $pdf->Cell(90,4,"",0,0,'R');
          $pdf->Cell(55,4,"   Hospedaje",1,0,'L');
          $pdf->Cell(20,4,round($total_hosp/($fila[4]>0 ? $fila[4] : 1),0),1,0,'R');
          $pdf->Cell(20,4,$total_hosp,1,1,'R');
          $costo_fijo+=$total_hosp;
          $total_gasto+=$total_hosp;
          $clasif="";
          $over=0;
          while ($fila_g=mysqli_fetch_array($res_g)) {

                if ($clasif!=$fila_g[2]) {
                   $sql_c="select descripcion from aplicacion where idaplicacion=".$fila_g[2];
                   $res_c=mysqli_query($sql_c);
                   $fila_c=mysqli_fetch_array($res_c);
                   $pdf->SetFont('Arial','I',8);
                   $pdf->Cell(90,4,"",0,0,'R');
                   $pdf->Cell(55,4,$fila_c[0],1,0,'L');
                   $pdf->Cell(20,4,"",1,0,'R');
                   $pdf->Cell(20,4,"",1,1,'R');
                   $pdf->SetFont('Arial','',8);
                   $clasif=$fila_g[2];
                }
                $pdf->Cell(90,4,"",0,0,'R');
                $pdf->Cell(55,4,"   ".$fila_g[0],1,0,'L');
                $pdf->Cell(20,4,round($fila_g[1]/($fila[4]>0 ? $fila[4] : 1),0),1,0,'R');
                $pdf->Cell(20,4,round($fila_g[1],0),1,1,'R');
                if ($fila_g[3]==17) {
                   $over=$fila_g[1];
                } else {
                  if ($fila_g[2]==1 || $fila_g[2]==5) {
                     $costo_fijo+=$fila_g[1];
                  } else {
                     $costo_variable+=$fila_g[1];
                  }
                }

                $total_gasto+=$fila_g[1];
          }

          $pdf->Cell(90,4,"",0,0,'R');
          $pdf->Cell(55,4,"TOTAL COSTOS",1,0,'L');
          $pdf->Cell(20,4,round($total_gasto/($fila[4]>0 ? $fila[4] : 1),0),1,0,'R');
          $pdf->Cell(20,4,round($total_gasto,0),1,1,'R');

          if ($numpag!=$pdf->PageNo()) {
              $pdf->SetY(30);
          } else {
              $pdf->SetY($posicionxi+5);
          }

          $pdf->Cell(60,4,"Punto de operaci�n",1,0,'L');
          $pdf->Cell(20,4,round(($costo_fijo+$over)/($total_ingreso/$total_alumnos-$costo_variable/$total_alumnos),1),1,1,'R');
          $pdf->Cell(60,4,"Punto de equilibrio",1,0,'L');
          $pdf->Cell(20,4,round(($costo_fijo)/($total_ingreso/$total_alumnos-$costo_variable/$total_alumnos),1),1,1,'R');
          $pdf->Cell(60,4,"Ingreso promedio mensual",1,0,'L');
          $pdf->Cell(20,4,round(($total_ingreso+$total_matricula)/($fila[4]>0 ? $fila[4] : 1),0),1,1,'R');
          $pdf->Cell(60,4,"(-) Egreso promedio mensual",1,0,'L');
          $pdf->Cell(20,4,round(($total_gasto-$over)/($fila[4]>0 ? $fila[4] : 1),0),1,1,'R');
          $pdf->Cell(60,4,"(=) Super�vit / d�ficit",1,0,'L');
          $pdf->Cell(20,4,round(($total_ingreso+$total_matricula-$total_gasto+$over)/($fila[4]>0 ? $fila[4] : 1),0),1,1,'R');
          $pdf->Cell(60,4,"(=) Total general",1,0,'L');
          $pdf->Cell(20,4,round(($total_ingreso+$total_matricula-$total_gasto+$over),0),1,1,'R'); */
       }
     }

$pdf->Output();
?>