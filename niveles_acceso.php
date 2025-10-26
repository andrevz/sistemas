<?php
if (!isset($iversion)) {
   $iversion=0;
}
if (!isset($iescuela)) {
   $iescuela=0;
}
if (!isset($itipoprograma)) {
   $itipoprograma=0;
}
if (!isset($iciudad)) {
   $iciudad=0;
}
$nacceso=acceso($_SESSION['idRol'], $iversion,$iescuela,$itipoprograma,$iciudad,$actividad,0);
if (!isset($minimo)) {
   $minimo=2;
}
if ($nacceso<$minimo) {
   header("Location: menu.php");
   exit;
}
 $eliminar=($nacceso==3 || $nacceso==6 || $nacceso==99);
 $editar=$eliminar;
 $insertar=($nacceso>=4 || $nacceso==99);
 $leer=($nacceso==2 || $nacceso==3 || $nacceso==5 || $nacceso==6 || $nacceso==99);
?>