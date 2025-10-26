<?php

require_once('../config.php');
mysql_set_charset('latin1', $simulacion);
include("../valida.php");

$actividad=33;
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
        $this->Cell(200,7,'REPORTE CONSOLIDADO DE PROGRAMAS VIGENTES',0,1,'C');
        $this->SetFont('Arial','B',12);

        $this->Cell(70);
        $this->SetFont('Arial','B',11);
        $this->Ln(5);
        $this->SetFont('Arial','B',8);
        $this->Cell(8,5,"Nro",1,0,'L');
        $this->Cell(18,5,"Sigla",1,0,'L');
        $this->Cell(110,5,"Programa",1,0,'L');
        $this->Cell(13,5,"Version",1,0,'L');
        $this->Cell(25,5,"Ciudad",1,0,'L');
        $this->Cell(12,5,"Matric.",1,0,'L');
        $this->Cell(12,5,"Coleg.",1,0,'L');
        $this->Cell(12,5,"Nro. Al.",1,0,'L');
        $this->Cell(17,5,"Ing. mes",1,0,'L');
        $this->Cell(17,5,"Egr. mes",1,0,'L');
        $this->Cell(17,5,"Neto mes",1,0,'L');
        $this->Cell(15,5,"# meses",1,1,'L');
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
        $this->Cell(150,5,'(c) 2012 Mario A. Antezana Yúgar',0,0,'C');
        $this->Cell(0,5,'Pagina '.$this->PageNo().'/{nb}',0,0,'R');
    }
}



//Creación del objeto de la clase heredada
$pdf=new PDF_MemImage();
$pdf->AliasNbPages();
$pdf->AddPage("L");


    $pdf->SetFont('Arial','',10);


    $sql_v="SELECT v.gestion, c.nombre, v.colegiatura, v.matricula, p.nombre, p.sigla, v.idversion, p.idescuela, v.ciudad, p.idtipoprograma
                   FROM version v inner join programa p on p.idprograma=v.idprograma
                   inner join ciudad c on c.idciudad=v.ciudad
                   WHERE v.activo=1";
    $res_v=mysql_query($sql_v);



    $i=0;
        $pdf->SetFont('Arial','',8);
    while ($fila_v=mysql_fetch_array($res_v)){
       if (acceso($_SESSION['idRol'], $fila_v["idversion"],$fila_v["idescuela"],$fila_v["idtipoprograma"],$fila_v["ciudad"],0,0)>=2) {
        $i++;
        $pdf->Cell(8,5,$i,1,0,'L');
        $pdf->Cell(18,5,$fila_v[5],1,0,'L');
        $pdf->Cell(110,5,substr($fila_v[4],0,63),1,0,'L');
        $pdf->Cell(13,5,$fila_v[0],1,0,'L');
        $pdf->Cell(25,5,$fila_v[1],1,0,'L');
        $pdf->Cell(12,5,$fila_v[3],1,0,'R');
        $pdf->Cell(12,5,$fila_v[2],1,0,'R');

        $sql_l="select vigentes from estudiantes, (select max(nromes) as ulm from estudiantes where idversion=".$fila_v[6].") mv where idversion=".$fila_v[6]." and mv.ulm=nromes";
        $res_l=mysql_query($sql_l);
        $fila_l=mysql_fetch_array($res_l);
        $pdf->Cell(12,5,$fila_l[0],1,0,'R');

        $sql_i="select avg(totmes) from (SELECT nromes, sum(monto) as totmes FROM ejecucioningresos ei  where idversion=".$fila_v[6]." group by nromes) tt";
        $res_i=mysql_query($sql_i);
        $fila_i=mysql_fetch_array($res_i);
        $pdf->Cell(17,5,sprintf("%0.2f",$fila_i[0]),1,0,'R');

        $sql_g="select avg(totmes) from (SELECT nromes, sum(monto) as totmes FROM ejecuciongastos ei  where idversion=".$fila_v[6]." group by nromes) tt";
        $res_g=mysql_query($sql_g);
        $fila_g=mysql_fetch_array($res_g);
        
        $sql_g2="select avg(totalg) from (SELECT year(inicio),month(inicio), sum(horas*honorarioshora+pasajes+viaticosdia*dias+hospedajedia*dias) as totalg FROM ejecucionmateriaversiondocente emvd inner join materiaversion mv on mv.idmateriaversion=emvd.idmateriaversion
                        where inicio<=now() and idversion=".$fila_v[6]."
                        group by year(inicio),month(inicio)) as tt";
        $res_g2=mysql_query($sql_g2);
        $fila_g2=mysql_fetch_array($res_g2);

        $pdf->Cell(17,5,sprintf("%0.2f",$fila_g[0]+$fila_g2[0]),1,0,'R');
        $pdf->Cell(17,5,sprintf("%0.2f",($fila_i[0]-$fila_g[0]-$fila_g2[0])),1,0,'R');

        $sql_mes="select count(*) from (SELECT year(inicio),month(inicio) FROM ejecucionmateriaversiondocente emvd inner join materiaversion mv on mv.idmateriaversion=emvd.idmateriaversion
                         where inicio<=now() and idversion=".$fila_v[6]."
                         group by year(inicio),month(inicio)) as tt";
        $res_mes=mysql_query($sql_mes);
        $fila_mes=mysql_fetch_array($res_mes);
        $pdf->Cell(15,5,$fila_mes[0],1,1,'R');
      }

    }






$pdf->Output();
?>