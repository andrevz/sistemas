<?php

require_once('../config.php');
mysqli_set_charset('latin1', $simulacion);
include("../valida.php");

$actividad=32;
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
        $this->Cell(45);
        $this->Cell(100,7,'SISTEMA DE GESTION DE PROYECTOS',0,1,'C');
        $this->SetFont('Arial','B',12);
        $this->Cell(45);
        $this->Cell(100,7,'EJECUCI�N',0,1,'C');

        $this->SetFont('Arial','B',12);

        $this->Cell(70);
        $this->SetFont('Arial','B',11);
//        $this->Cell(50,5,'Gestion: '.($_SESSION['Anio']-$_SESSION['Periodo']+$_GET["p"]),0,0,'C');
//        $this->Rect(10,29,190,0);
//        $this->Rect(10,30,190,0);
        //Salto de l�nea
        $this->Ln(5);
    }

    function Footer()
    {
        //Posici�n: a 1,5 cm del final
        $this->SetY(-15);
        $this->Rect(10,280,190,0);
        //Arial italic 8
        $this->SetFont('Arial','I',8);
        //N�mero de p�gina
        $this->Cell(60,5,"Sistema de Gestion de Proyectos v. 1.0",0,0,'L');
        $this->Cell(60,5,'(c) 2012 Mario A. Antezana Y�gar',0,0,'C');
        $this->Cell(0,5,'Pagina '.$this->PageNo().'/{nb}',0,0,'R');
    }
}



//Creaci�n del objeto de la clase heredada
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

          $sql_i="SELECT v.gestion, c.nombre, v.colegiatura, v.nrodiasejecutado, v.nromesesejecutado, v.inicioejecutado, v.finejecutado, v.matricula, p.nombre,
                         p.sigla, rh.nombres, rh.apellidos
                         FROM version v inner join programa p on p.idprograma=v.idprograma inner join recursohumano rh on rh.idrecursohumano=v.idrecursohumano
                         inner join ciudad c on c.idciudad=v.ciudad
                         where v.idversion=".$fila_versiones[0];
          $res=mysqli_query($sql_i);
          $fila=mysqli_fetch_array($res);

          $pdf->Cell(40,5,"Programa:",1,0,'L');
          $pdf->SetFont('Arial','B',10);
          $pdf->Cell(150,5,substr($fila[8],0,60)." (".$fila[9].")",1,1,'L');
          $pdf->SetFont('Arial','',10);
          $pdf->Cell(40,5,"Versi�n:",1,0,'L');
          $pdf->Cell(55,5,$fila[0],1,0,'L');
          $pdf->Cell(40,5,"Ciudad:",1,0,'L');
          $pdf->Cell(55,5,$fila[1],1,1,'L');
          $pdf->Cell(40,5,"D�as de clase:",1,0,'L');
          $pdf->Cell(55,5,$fila[3],1,0,'L');
          $pdf->Cell(40,5,"Nro. de meses:",1,0,'L');
          $pdf->Cell(55,5,$fila[4],1,1,'L');
          $pdf->Cell(40,5,"Responsable:",1,0,'L');
          $pdf->Cell(150,5,$fila[11].", ".$fila[10],1,1,'L');
          $pdf->Cell(40,5,"Inicio Ejecutado:",1,0,'L');
          $pdf->Cell(55,5,fecha($fila[5],3),1,0,'L');
          $pdf->Cell(40,5,"Fin Ejecutado:",1,0,'L');
          $pdf->Cell(55,5,fecha($fila[6],3),1,1,'L');

          $pdf->Cell(70);
          $pdf->SetFont('Arial','B',13);
          $pdf->Cell(50,7,'MATERIAS',0,1,'C');

          $sql_m="SELECT mv.universidad, m.nombre, pmvd.horas, pmvd.honorarioshora, pmvd.pasajes,
                         pmvd.viaticosdia, pmvd.dias, pmvd.hospedajedia, pmvd.inicio, pmvd.fin, concat(substring(apellidos, 1, if(instr(apellidos,' ')>0,instr(apellidos,' ')-1,length(apellidos))), ', ', substring(nombres, 1, if(instr(nombres,' ')>0,instr(nombres,' ')-1,length(nombres)))),
                         c.nombre, mv.idmateria
                         FROM materiaversion mv inner join materia m on m.idmateria=mv.idmateria
                         left join ejecucionmateriaversiondocente pmvd on pmvd.idmateriaversion=mv.idmateriaversion left join recursohumano rh on rh.idrecursohumano=pmvd.idrecursohumano left join categoria c on c.idcategoria=pmvd.idcategoria
                         where mv.idversion=".$fila_versiones[0]." order by orden, mv.idmateriaversion";

//          print $sql_m;
//          exit;
          $pdf->SetFont('Arial','B',8);
          $pdf->Cell(7,5,"Nro",1,0,'L');
          $pdf->Cell(68,5,"Materia",1,0,'L');
//          $pdf->Cell(17,5,"Tipo",1,0,'L');
          $pdf->Cell(10,5,"Univ.",1,0,'L');
          $pdf->Cell(32,5,"Docente",1,0,'L');
          $pdf->Cell(8,5,"Hrs.",1,0,'L');
          $pdf->Cell(8,5,"Dias",1,0,'L');
          $pdf->Cell(9,5,"Hon.",1,0,'L');
          $pdf->Cell(9,5,"Pasa.",1,0,'L');
          $pdf->Cell(8,5,"Viat.",1,0,'L');
          $pdf->Cell(10,5,"Hosp.",1,0,'L');
          $pdf->Cell(13,5,"Inicio",1,0,'L');
          $pdf->Cell(13,5,"Fin",1,1,'L');

          $i=0;

          $res_m=mysqli_query($sql_m);
          $pdf->SetFont('Arial','',7);

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
                    $materia=$fila_m[12];
                    $pdf->Cell(7,4,$i,1,0,'R');
                    $pdf->Cell(68,4,substr($fila_m[1],0,37),1,0,'L');
//                    $pdf->Cell(17,4,$fila_m[2],1,0,'L');
                    $pdf->Cell(10,4,substr($fila_m[0],0,5),1,0,'L');
/*                } else {
                    $pdf->Cell(7,4,"",0,0,'R');
                    $pdf->Cell(55,4,"",0,0,'L');
                    $pdf->Cell(17,4,"",0,0,'L');
                    $pdf->Cell(10,4,"",0,0,'L');
                } */
                $pdf->Cell(32,4,$fila_m[10],1,0,'L');
                $pdf->Cell(8,4,$fila_m[2],1,0,'R');
                $pdf->Cell(8,4,$fila_m[6],1,0,'R');
                $pdf->Cell(9,4,round(($fila_m[3]*$fila_m[2]),0),1,0,'R');
                $pdf->Cell(9,4,($fila_m[4]),1,0,'R');
                $pdf->Cell(8,4,round(($fila_m[5]*$fila_m[6]),0),1,0,'R');
                $pdf->Cell(10,4,round(($fila_m[6]*$fila_m[7]),0),1,0,'R');
                $pdf->Cell(13,4,fecha($fila_m[8],1),1,0,'L');
                $pdf->Cell(13,4,fecha($fila_m[9],1),1,1,'L');

                $total_hrs+=$fila_m[2];
                $total_dias+=$fila_m[6];
                $total_pasa+=$fila_m[4];
                $total_hon+=($fila_m[3]*$fila_m[2]);
                $total_viat+=($fila_m[5]*$fila_m[6]);
                $total_hosp+=($fila_m[6]*$fila_m[7]);

          }

          $pdf->Cell(7,4,"",1,0,'R');
          $pdf->Cell(68,4,"TOTALES",1,0,'L');
//          $pdf->Cell(17,4,"",1,0,'L');
          $pdf->Cell(10,4,"",1,0,'L');
          $pdf->Cell(32,4,"",1,0,'L');
          $pdf->Cell(8,4,$total_hrs,1,0,'R');
          $pdf->Cell(8,4,$total_dias,1,0,'R');
          $pdf->Cell(9,4,round($total_hon,0),1,0,'R');
          $pdf->Cell(9,4,$total_pasa,1,0,'R');
          $pdf->Cell(8,4,round($total_viat,0),1,0,'R');
          $pdf->Cell(10,4,round($total_hosp,0),1,0,'R');
          $pdf->Cell(13,4,"",1,0,'L');
          $pdf->Cell(13,4,"",1,1,'L');
          $pdf->Cell(13,3,"",0,1,'L');


          $pdf->SetFont('Arial','B',7);

          $pdf->Cell(26,4,"MESES",1,0,'L');

          $sql="select nromeses from version where idversion=".$fila_versiones[0];
          $res_ms=mysqli_query($sql);
          $fila_ms=mysqli_fetch_array($res_ms);
          for ($i=1;$i<=$fila_ms[0];$i++) {
              $pdf->Cell(9,4,$i,1,0,'C');
          }
          $pdf->Cell(1,4,"",0,1);


          $sql_i="SELECT nromes, vigentes FROM estudiantes
                         where idversion=".$fila_versiones[0]."
                         order by nromes";
          $res_i=mysqli_query($sql_i);

          $array_datos=array();
          while ($fila_i=mysqli_fetch_array($res_i)) {
              $array_datos[$fila_i[0]]=$fila_i[1];
          }

                $pdf->SetFont('Arial','B',7);
                $pdf->Cell(26,4,"NRO. ALUMNOS",1,0,'L');
                for ($i=1;$i<=$fila_ms[0];$i++) {
                    $pdf->Cell(9,4,$array_datos[$i],1,0,'R');
                }
                $pdf->Cell(1,4,"",0,1);


          $sql_i="SELECT ti.descripcion, nromes, sum(monto), ti.idtiposingresos FROM ejecucioningresos ei inner join tiposingresos ti on ti.idtiposingresos=ei.idtiposingresos
                         where idversion=".$fila_versiones[0]."
                         group by ti.descripcion, nromes
                         order by descripcion, nromes";
          $res_i=mysqli_query($sql_i);

          $array_datos=array();
          while ($fila_i=mysqli_fetch_array($res_i)) {
              $array_datos[$fila_i[1]][$fila_i[3]]=$fila_i[2];
          }

          $sql_ti="select distinct ti.idtiposingresos, descripcion from tiposingresos ti inner join ejecucioningresos ei on ei.idtiposingresos=ti.idtiposingresos  where idversion=".$fila_versiones[0];
          $res_ti=mysqli_query($sql_ti);
          while ($fila_ti=mysqli_fetch_array($res_ti)) {
                $pdf->SetFont('Arial','',6);
                $pdf->Cell(26,4,substr($fila_ti[1],0,18),1,0,'L');
                for ($i=1;$i<=$fila_ms[0];$i++) {
                    $pdf->Cell(9,4,$array_datos[$i][$fila_ti[0]],1,0,'R');
                }
                $pdf->Cell(1,4,"",0,1);
          }
          $pdf->SetFont('Arial','B',7);
          $pdf->Cell(26,4,"TOTAL INGRESOS",1,0,'L');
          for ($i=1;$i<=$fila_ms[0];$i++) {
              $pdf->Cell(9,4,sumArray($array_datos[$i]),1,0,'R');
          }
          $pdf->Cell(1,4,"",0,1);




          $array_datos=array();

          $sql_doc="SELECT sum(pmvd.horas*pmvd.honorarioshora), month(pmvd.inicio), year(pmvd.inicio)
                         FROM materiaversion mv 
                         left join ejecucionmateriaversiondocente pmvd on pmvd.idmateriaversion=mv.idmateriaversion 
                         where mv.idversion=".$fila_versiones[0]." group by month(pmvd.inicio), year(pmvd.inicio)
                         order by year(pmvd.inicio), month(pmvd.inicio)";

          $res_doc=mysqli_query($sql_doc);
          $i=0;
          while($fila_doc=mysqli_fetch_array($res_doc)) {
                $i++;
                $array_datos[$i][-1]=($fila_doc[0]==0 ? "" :$fila_doc[0]);
          }


          $pdf->SetFont('Arial','',6);
          $pdf->Cell(26,4,"Honorarios profesionales",1,0,'L');
          $pdf->SetFont('Arial','',6);
          for ($i=1;$i<=$fila_ms[0];$i++) {
              $pdf->Cell(9,4,round($array_datos[$i][-1],0),1,0,'R');
          }
          $pdf->Cell(1,4,"",0,1);

         $sql_doc="SELECT sum(pmvd.pasajes),
                         month(pmvd.inicio), year(pmvd.inicio)
                         FROM materiaversion mv 
                         left join ejecucionmateriaversiondocente pmvd on pmvd.idmateriaversion=mv.idmateriaversion 
                         where mv.idversion=".$fila_versiones[0]." group by month(pmvd.inicio), year(pmvd.inicio)
                         order by year(pmvd.inicio), month(pmvd.inicio)";

          $res_doc=mysqli_query($sql_doc);
          $i=0;
          while($fila_doc=mysqli_fetch_array($res_doc)) {
                $i++;
                $array_datos[$i][-2]=($fila_doc[0]==0 ? "" :$fila_doc[0]);
          }


          $pdf->SetFont('Arial','',6);
          $pdf->Cell(26,4,"Pasajes",1,0,'L');
          $pdf->SetFont('Arial','',6);
          for ($i=1;$i<=$fila_ms[0];$i++) {
              $pdf->Cell(9,4,$array_datos[$i][-2],1,0,'R');
          }
          $pdf->Cell(1,4,"",0,1);

        $sql_doc="SELECT sum(pmvd.viaticosdia*dias),
                         month(pmvd.inicio), year(pmvd.inicio)
                         FROM materiaversion mv 
                         left join ejecucionmateriaversiondocente pmvd on pmvd.idmateriaversion=mv.idmateriaversion 
                         where mv.idversion=".$fila_versiones[0]." group by month(pmvd.inicio), year(pmvd.inicio)
                         order by year(pmvd.inicio), month(pmvd.inicio)";

          $res_doc=mysqli_query($sql_doc);
          $i=0;
          while($fila_doc=mysqli_fetch_array($res_doc)) {
                $i++;
                $array_datos[$i][-3]=($fila_doc[0]==0 ? "" :$fila_doc[0]);
          }


          $pdf->SetFont('Arial','',6);
          $pdf->Cell(26,4,"Viaticos",1,0,'L');
          $pdf->SetFont('Arial','',6);
          for ($i=1;$i<=$fila_ms[0];$i++) {
              $pdf->Cell(9,4,$array_datos[$i][-3],1,0,'R');
          }
          $pdf->Cell(1,4,"",0,1);




        $sql_doc="SELECT sum(pmvd.hospedajedia*dias),
                         month(pmvd.inicio), year(pmvd.inicio)
                         FROM materiaversion mv 
                         left join ejecucionmateriaversiondocente pmvd on pmvd.idmateriaversion=mv.idmateriaversion 
                         where mv.idversion=".$fila_versiones[0]." group by month(pmvd.inicio), year(pmvd.inicio)
                         order by year(pmvd.inicio), month(pmvd.inicio)";

          $res_doc=mysqli_query($sql_doc);
          $i=0;
          while($fila_doc=mysqli_fetch_array($res_doc)) {
                $i++;
                $array_datos[$i][-4]=($fila_doc[0]==0 ? "" :$fila_doc[0]);
          }


          $pdf->SetFont('Arial','',6);
          $pdf->Cell(26,4,"Hospedaje",1,0,'L');
          $pdf->SetFont('Arial','',6);
          for ($i=1;$i<=$fila_ms[0];$i++) {
              $pdf->Cell(9,4,$array_datos[$i][-4],1,0,'R');
          }
          $pdf->Cell(1,4,"",0,1);



          $sql_i="SELECT ti.nombre, nromes, sum(monto), ti.idtiposgastos
                         FROM ejecuciongastos ei inner join tiposgastos ti on ti.idtiposgastos=ei.idtiposgastos
                         where idversion=".$fila_versiones[0]."
                         group by ti.nombre, nromes
                         order by nombre, nromes";
          $res_i=mysqli_query($sql_i);


          while ($fila_i=mysqli_fetch_array($res_i)) {
              $array_datos[$fila_i[1]][$fila_i[3]]=$fila_i[2];
          }

          $pdf->SetFont('Arial','',7);

          $sql_ti="select distinct ti.idtiposgastos, nombre from tiposgastos ti inner join ejecuciongastos ei on ei.idtiposgastos=ti.idtiposgastos
                          where idversion=".$fila_versiones[0];
          $res_ti=mysqli_query($sql_ti);


          while ($fila_ti=mysqli_fetch_array($res_ti)) {
                $pdf->SetFont('Arial','',6);
                $pdf->Cell(26,4,substr($fila_ti[1],0,22),1,0,'L');
                $pdf->SetFont('Arial','',6);
                for ($i=1;$i<=$fila_ms[0];$i++) {
                    $pdf->Cell(9,4,$array_datos[$i][$fila_ti[0]],1,0,'R');
                }
                $pdf->Cell(1,4,"",0,1);
          }
          $pdf->SetFont('Arial','B',7);
          $pdf->Cell(26,4,"TOTAL COSTOS",1,0,'L');
          for ($i=1;$i<=$fila_ms[0];$i++) {
              $pdf->Cell(9,4,round(sumArray($array_datos[$i]),0),1,0,'R');
          }
          $pdf->Cell(1,4,"",0,1);
       }
    }
$pdf->Output();
?>