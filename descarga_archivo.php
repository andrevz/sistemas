<?php
include("config.php");

$id = $_GET['id']; 
$qry = "SELECT tipo, contenido, nombrearchivo, tamano FROM documentos WHERE iddocumentos=$id";
$res = mysql_query($qry);
$tipo = mysql_result($res, 0, "tipo");
$contenido = mysql_result($res, 0, "contenido");
$nombre = mysql_result($res, 0, "nombrearchivo");
$size = mysql_result($res, 0, "tamano");

 header("Content-type: $tipo");
 header("Content-length: $size");
 header("Content-Disposition: attachment; filename=$nombre");
 print $contenido; 
?> 