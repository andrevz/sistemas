<?php
include("config.php");

$id = $_GET['id']; 
$qry = "SELECT tipo, contenido, nombrearchivo, tamano FROM documentos WHERE iddocumentos=$id";
$res = mysqli_query($qry);
$tipo = mysqli_result($res, 0, "tipo");
$contenido = mysqli_result($res, 0, "contenido");
$nombre = mysqli_result($res, 0, "nombrearchivo");
$size = mysqli_result($res, 0, "tamano");

 header("Content-type: $tipo");
 header("Content-length: $size");
 header("Content-Disposition: attachment; filename=$nombre");
 print $contenido; 
?> 