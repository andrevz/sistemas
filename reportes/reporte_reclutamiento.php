<?php

require_once('../config.php');
mysql_set_charset('latin1', $simulacion);
include("../valida.php");

$actividad=36;
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
        $this->Cell(100,7,'REPORTE DE RECLUTAMIENTO',0,1,'C');
        $this->SetFont('Arial','B',12);

        $this->Cell(70);
        $this->SetFont('Arial','B',11);
        $this->Ln(5);
    }

    function Footer()
    {
        //Posición: a 1,5 cm del final
        $this->SetY(-15);
        $this->Rect(10,280,190,0);
        //Arial italic 8
        $this->SetFont('Arial','I',8);
        //Número de página
        $this->Cell(60,5,"Sistema de Gestion de Proyectos v. 1.0",0,0,'L');
        $this->Cell(60,5,'(c) 2012 Mario A. Antezana Yúgar',0,0,'C');
        $this->Cell(0,5,'Pagina '.$this->PageNo().'/{nb}',0,0,'R');
    }
}



//Creación del objeto de la clase heredada
$pdf=new PDF_MemImage();
$pdf->AliasNbPages();
$pdf->AddPage();


    $pdf->SetFont('Arial','',10);

     $sql_versiones="select v.idversion, gestion, c.nombre, inicioprogramado, p.nombre, p.idprograma, r.fecha, r.meta, interesados, solicitud, seguros, matriculados, c.idciudad, p.idescuela, p.idtipoprograma
                            from reclutamiento r inner join
                                 (SELECT idversion, max(fecha) as ufecha FROM reclutamiento group by idversion) tt on tt.idversion=r.idversion and r.fecha=tt.ufecha
                                 inner join version v on v.idversion=r.idversion inner join programa p on p.idprograma=v.idprograma inner join ciudad c on c.idciudad=v.ciudad
                                 where activo=1 and p.idtipoprograma like '".$_GET["idtp"]."' and v.idprograma like '".$_GET["idp"]."'
                                 and v.ciudad like '".$_GET["ciudad"]."' and gestion like '".$_GET["gestion"]."' order by p.nombre, c.nombre, gestion";
      $res_versiones=mysql_query($sql_versiones);

    $pdf->SetFont('Arial','B',7);
    $pdf->Cell(7,5,"Nro",1,0,'L');
    $pdf->Cell(110,5,"Programa",1,0,'L');
    $pdf->Cell(11,5,"Version",1,0,'L');
    $pdf->Cell(20,5,"Ciudad",1,0,'L');
    $pdf->Cell(15,5,"Últ. fecha",1,0,'L');
    $pdf->Cell(12,5,"Meta",1,0,'L');
    $pdf->Cell(17,5,"Matriculados",1,1,'L');
    $pdf->SetFont('Arial','',7);

      $i=0;
      while ($fila_versiones=mysql_fetch_array($res_versiones)) {
         if (acceso($_SESSION['idRol'], $fila_versiones[0],$fila_versiones["idescuela"],$fila_versiones["idtipoprograma"],$fila_versiones["idciudad"],0,0)>=2) {
             $i++;
                    $pdf->Cell(7,5,$i,1,0,'L');
                    $pdf->Cell(110,5,htmlspecialchars_decode ( substr($fila_versiones[4],0,70)),1,0,'L');
                    $pdf->Cell(11,5,$fila_versiones[1],1,0,'R');
                    $pdf->Cell(20,5,$fila_versiones[2],1,0,'L');
                    $pdf->Cell(15,5,fecha($fila_versiones[6],8),1,0,'R');
                    $pdf->Cell(12,5,$fila_versiones[7],1,0,'R');
                    $pdf->Cell(17,5,$fila_versiones[11],1,1,'R');
        }
      }






$pdf->Output();
?>