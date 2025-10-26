<?php

require_once('../config.php');
mysql_set_charset('latin1', $simulacion);
include("../valida.php");

$actividad=49;
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
    function SetWidths($w)
{
    //Set the array of column widths
    $this->widths=$w;
}

function SetAligns($a)
{
    //Set the array of column alignments
    $this->aligns=$a;
}

function Row($data)
{
    //Calculate the height of the row
    $nb=0;
    for($i=0;$i<count($data);$i++)
        $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
    $h=5*$nb;
    //Issue a page break first if needed
    $this->CheckPageBreak($h);
    //Draw the cells of the row
    for($i=0;$i<count($data);$i++)
    {
        $w=$this->widths[$i];
        $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
        //Save the current position
        $x=$this->GetX();
        $y=$this->GetY();
        //Draw the border
        $this->Rect($x,$y,$w,$h);
        //Print the text
        $this->MultiCell($w,5,$data[$i],0,$a);
        //Put the position to the right of the cell
        $this->SetXY($x+$w,$y);
    }
    //Go to the next line
    $this->Ln($h);
}
function NbLines($w,$txt)
{
    //Computes the number of lines a MultiCell of width w will take
    $cw=&$this->CurrentFont['cw'];
    if($w==0)
        $w=$this->w-$this->rMargin-$this->x;
    $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
    $s=str_replace("\r",'',$txt);
    $nb=strlen($s);
    if($nb>0 and $s[$nb-1]=="\n")
        $nb--;
    $sep=-1;
    $i=0;
    $j=0;
    $l=0;
    $nl=1;
    while($i<$nb)
    {
        $c=$s[$i];
        if($c=="\n")
        {
            $i++;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
            continue;
        }
        if($c==' ')
            $sep=$i;
        $l+=$cw[$c];
        if($l>$wmax)
        {
            if($sep==-1)
            {
                if($i==$j)
                    $i++;
            }
            else
                $i=$sep+1;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
        }
        else
            $i++;
    }
    return $nl;
}
function CheckPageBreak($h)
{
    //If the height h would cause an overflow, add a new page immediately
    if($this->GetY()+$h>$this->PageBreakTrigger)
        $this->AddPage($this->CurOrientation);
}
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
        $this->Cell(100,7,'ACTIVIDADES',0,1,'C');

        $this->SetFont('Arial','B',12);

        $this->Cell(70);
        $this->SetFont('Arial','B',11);
//        $this->Cell(50,5,'Gestion: '.($_SESSION['Anio']-$_SESSION['Periodo']+$_GET["p"]),0,0,'C');
//        $this->Rect(10,29,190,0);
//        $this->Rect(10,30,190,0);
        //Salto de línea
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
   $altoCeldas=6;

    $sql_versiones="select idversion, p.idescuela, v.ciudad, p.idtipoprograma
                           from version v inner join programa p on p.idprograma=v.idprograma
                           where p.idtipoprograma like '".$_GET["idtp"]."' and v.idprograma like '".$_GET["idp"]."'
                                 and ciudad like '".$_GET["ciudad"]."' and gestion like '".$_GET["gestion"]."'";
    $res_versiones=mysql_query($sql_versiones);

    while ($fila_versiones=mysql_fetch_array($res_versiones)) {
       if (acceso($_SESSION['idRol'], $fila_versiones[0],$fila_versiones[1],$fila_versiones[3],$fila_versiones[2],0,0)>=2) {
          $pdf->AddPage();

          $pdf->SetFont('Arial','',10);

          $pdf->SetFillColor(230,230,230);

          $sql_i="SELECT v.gestion, c.nombre, v.colegiatura, v.nrodiasejecutado, v.nromesesejecutado, v.inicioejecutado, v.finejecutado, v.matricula, p.nombre,
                         p.sigla, rh.nombres, rh.apellidos, e.nombre
                         FROM version v inner join programa p on p.idprograma=v.idprograma
                         inner join recursohumano rh on rh.idrecursohumano=v.idrecursohumano
                         inner join escuela e on e.idescuela=p.idescuela
                         inner join ciudad c on c.idciudad=v.ciudad
                         where v.idversion=".$fila_versiones[0];
          $res=mysql_query($sql_i);
          $fila=mysql_fetch_array($res);

          $pdf->Cell(40,5,"Programa:",1,0,'L');
          $pdf->SetFont('Arial','B',10);
          $pdf->Cell(150,5,substr($fila[8],0,60)." (".$fila[9].")",1,1,'L');
          $pdf->SetFont('Arial','',10);
          $pdf->Cell(40,5,"Versión:",1,0,'L');
          $pdf->Cell(55,5,$fila[0],1,0,'L');
          $pdf->Cell(40,5,"Ciudad:",1,0,'L');
          $pdf->Cell(55,5,$fila[1],1,1,'L');
          $pdf->Cell(40,5,"Responsable:",1,0,'L');
          $pdf->Cell(150,5,$fila[11].", ".$fila[10],1,1,'L');
          $pdf->Cell(40,5,"Escuela:",1,0,'L');
          $pdf->Cell(150,5,$fila[12],1,1,'L');

          $pdf->Ln();

          $sql_m="SELECT a.tipo, a.nombre, c.responsable, c.ejecutado, c.fecha, c.comentarios FROM checklist c inner join actividades a on a.idactividades=c.idactividades 
                         where idversion=".$fila_versiones[0]." order by orden";

          $pdf->SetFont('Arial','B',8);
          $pdf->Cell(7,5,"Nro",1,0,'L');
          $pdf->Cell(51,5,"Actividad",1,0,'L');
          $pdf->Cell(51,5,"Responsable",1,0,'L');
          $pdf->Cell(15,5,"Ejecutado",1,0,'L');
          $pdf->Cell(15,5,"Fecha",1,0,'L');
          $pdf->Cell(51,5,"Comentarios",1,1,'L');

          $i=0;

          $res_m=mysql_query($sql_m);
          $pdf->SetFont('Arial','',7);

          $materia="";



          $pdf->SetWidths(array(7,51,51,15,15,51));
          $pdf->SetAligns(array('L','L','L','C','C','L'));

          while ($fila_m=mysql_fetch_array($res_m)) {
                $i++;
                if ($fila_m[0]!=$materia) {
                    $pdf->SetFont('Arial','B',7);
                    $materia=$fila_m[0];
                    $pdf->Cell(190,4,substr($fila_m[0],0,37),1,1,'L');
                    $pdf->SetFont('Arial','',7);
                }

                $pdf->Row(array($i,$fila_m[1],$fila_m[2],$fila_m[3],$fila_m[4],$fila_m[5]));

          }


       }
    }
$pdf->Output();
?>