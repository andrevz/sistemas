<?php

require_once('../config.php');
mysql_set_charset('latin1', $simulacion);
include("../valida.php");

$actividad=48;
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
        $this->Cell(200,7,'SISTEMA DE GESTION DE PROYECTOS',0,1,'C');
        $this->SetFont('Arial','B',12);
        $this->Cell(50);
        $this->Cell(200,7,'ASIGNATURAS PROGRAMADAS POR DOCENTE',0,1,'C');
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
$pdf=new PDF_MemImage('L');
$pdf->AliasNbPages();


    $sql_versiones="select distinct rh.idrecursohumano
                                 from version v inner join programa p on p.idprograma=v.idprograma
                                 inner join tipoprograma tp on tp.idtipoprograma=p.idtipoprograma
                                 inner join materiaversion mv on mv.idversion=v.idversion
                                 inner join ejecucionmateriaversiondocente pmvd on pmvd.idmateriaversion=mv.idmateriaversion inner join
                                 recursohumano rh on rh.idrecursohumano=pmvd.idrecursohumano
                                 where activo=1 and tp.idtipoprograma like '".$_GET["idtp"]."' and p.idprograma like '".$_GET["idp"]."'
                                 and upper(v.ciudad) like '".$_GET["ciudad"]."' and gestion like '".$_GET["gestion"]."' and rh.idrecursohumano like '".$_GET["docente"]."' order by upper(apellidos), upper(nombres)";

    $res_versiones=mysql_query($sql_versiones);
    while ($fila_versiones=mysql_fetch_array($res_versiones)) {
//       if (acceso($_SESSION['idRol'], $fila_versiones[0],$fila_versiones[1],$fila_versiones[3],$fila_versiones[2],0,0)>=2) {
          $pdf->AddPage();
          $pdf->SetFont('Arial','',10);

          $pdf->SetFillColor(230,230,230);

          $sql_i="SELECT idrecursohumano, nombres, apellidos, codigo_upb
                         FROM recursohumano
                         where idrecursohumano=".$fila_versiones[0];
          $res=mysql_query($sql_i);
          $fila=mysql_fetch_array($res);

          $pdf->Cell(25,5,"Docente:",1,0,'L');
          $pdf->SetFont('Arial','B',10);
          $pdf->Cell(200,5,$fila[2].", ".$fila[1],1,0,'L');
          $pdf->SetFont('Arial','',10);
          $pdf->Cell(25,5,"C�digo DTI:",1,0,'L');
          $pdf->SetFont('Arial','B',10);
          $pdf->Cell(25,5,$fila[3],1,1,'L');

          $pdf->Cell(70);
          $pdf->SetFont('Arial','B',13);
          $pdf->Cell(150,7,'ASIGNATURAS',0,1,'C');

          $sql_m="SELECT p.nombre, v.gestion, c.nombre, m.nombre, pmvd.evaluaciondocente
                         FROM ejecucionmateriaversiondocente pmvd inner join materiaversion mv on mv.idmateriaversion=pmvd.idmateriaversion
                              inner join version v on v.idversion=mv.idversion inner join programa p on p.idprograma=v.idprograma
                              inner join materia m on m.idmateria=mv.idmateria inner join ciudad c on c.idciudad=v.ciudad
                         where pmvd.idrecursohumano=".$fila_versiones[0];


          $pdf->SetFont('Arial','B',8);
          $pdf->Cell(10,5,"Nro",1,0,'L');
          $pdf->Cell(115,5,"Programa",1,0,'L');
          $pdf->Cell(15,5,"Gesti�n",1,0,'L');
          $pdf->Cell(15,5,"Ciudad",1,0,'L');
          $pdf->Cell(110,5,"Asignatura",1,0,'L');
          $pdf->Cell(10,5,"Calif.",1,1,'L');

          $i=0;

          $res_m=mysql_query($sql_m);
          $pdf->SetFont('Arial','',8);

          while ($fila_m=mysql_fetch_array($res_m)) {
                    $i++;
                    $pdf->Cell(10,4,$i,1,0,'R');
                    $pdf->Cell(115,4,ucwords(strtolower($fila_m[0])),1,0,'L');
                    $pdf->Cell(15,4,$fila_m[1],1,0,'L');
                    $pdf->Cell(15,4,$fila_m[2],1,0,'L');
                    $pdf->Cell(110,4,ucwords(strtolower($fila_m[3])),1,0,'L');
                $pdf->Cell(10,4,$fila_m[4],1,1,'L');
          }


//       }
     }

$pdf->Output();
?>