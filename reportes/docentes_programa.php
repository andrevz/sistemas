<?php

require_once('../config.php');
mysqli_set_charset('latin1', $simulacion);
include("../valida.php");

$actividad=46;
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
        $this->Cell(100,7,'DOCENTES POR PROGRAMA',0,1,'C');
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
          $pdf->Cell(50,7,'DOCENTES',0,1,'C');

          $sql_m="SELECT distinct upper(apellidos), upper(nombres), codigo_upb

                         FROM materiaversion mv inner join materia m on m.idmateria=mv.idmateria inner join planmateriaversiondocente pmvd on pmvd.idmateriaversion=mv.idmateriaversion inner join recursohumano rh on rh.idrecursohumano=pmvd.idrecursohumano
                         where mv.idversion=".$fila_versiones[0]." order by apellidos, nombres";


          $pdf->SetFont('Arial','B',8);
          $pdf->Cell(20,5);
          $pdf->Cell(10,5,"Nro",1,0,'L');
          $pdf->Cell(60,5,"Apellidos",1,0,'L');
          $pdf->Cell(60,5,"Nombres",1,0,'L');
          $pdf->Cell(20,5,"C�digo",1,1,'L');

          $i=0;

          $res_m=mysqli_query($sql_m);
          $pdf->SetFont('Arial','',8);

          while ($fila_m=mysqli_fetch_array($res_m)) {
                    $i++;
                    $materia=$fila_m[13];
                    $pdf->Cell(20,5);
                    $pdf->Cell(10,4,$i,1,0,'R');
                    $pdf->Cell(60,4,$fila_m[0],1,0,'L');
                    $pdf->Cell(60,4,$fila_m[1],1,0,'L');
                $pdf->Cell(20,4,$fila_m[2],1,1,'L');
          }


       }
     }

$pdf->Output();
?>