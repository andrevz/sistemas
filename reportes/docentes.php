<?php

require_once('../config.php');
mysqli_set_charset('latin1', $simulacion);
include("../valida.php");

$actividad=45;
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

function CheckPageBreak($h)
{
    //If the height h would cause an overflow, add a new page immediately
    if($this->GetY()+$h>$this->PageBreakTrigger)
        $this->AddPage($this->CurOrientation);
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
        $this->Cell(100,7,'DOCENTES',0,1,'C');
        $this->SetFont('Arial','B',12);

        $this->Cell(70);
        $this->SetFont('Arial','B',11);

          $this->Ln(5);

          $this->SetFont('Arial','B',8);
          $this->Cell(7,5,"Nro",1,0,'L');
          $this->Cell(15,5,"C�d. DTI",1,0,'L');
          $this->Cell(70,5,"Nombre",1,0,'L');
          $this->Cell(25,5,"Tel�fono",1,0,'L');
          $this->Cell(25,5,"Celular",1,0,'L');
          $this->Cell(20,5,"C.I.",1,0,'L');
          $this->Cell(25,5,"Ciudad",1,1,'L');
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
     $pdf->SetAutoPageBreak(1,13);
   $margenIzq=15;
   $pdf->SetMargins($margenIzq,10,10);
   $altoCeldas=6;

          $pdf->AddPage();

          $sql_m="SELECT upper(nombres), upper(apellidos), codigo_UPB, telefono, celular, ci, ciudad FROM recursohumano rh order by upper(apellidos), upper(nombres)";

   $pdf->SetWidths(array(7,15,70,25,25,20,25));


          $i=0;

          $res_m=mysqli_query($sql_m);
          $pdf->SetFont('Arial','',8);
          $pdf->SetAligns(array('L','C','L','C','C', 'C','L'));
          while ($fila_m=mysqli_fetch_array($res_m)) {
                    $i++;
                    $pdf->Row(array($i,$fila_m[2],$fila_m[1].", ".$fila_m[0],$fila_m[3],$fila_m[4],$fila_m[5],$fila_m[6]));

          }


$pdf->Output();
?>