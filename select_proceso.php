<?php

$selectDestino=$_GET["select"];

//    $tabla=$listadoSelects[$selectDestino];
    include('config.php');

    if ($selectDestino=="recursohumano") {
           $consulta=mysqli_query("SELECT id$selectDestino, concat(apellidos, ', ',nombres) FROM $selectDestino where recursoactivo=1 order by apellidos, nombres") or die(mysqli_error());
    } else {
           $consulta=mysqli_query("SELECT id$selectDestino, nombre FROM $selectDestino order by nombre") or die(mysqli_error());
    }

    // Comienzo a imprimir el select
    echo "<select name='".$selectDestino."' id='".$selectDestino."'>";
    echo "<option value='-1'>--- Elija ---</option>";
    while($registro=mysqli_fetch_row($consulta))
    {
        // Imprimo las opciones del select
        echo "<option value='".$registro[0]."'>".$registro[1]."</option>";
    }           
    echo "</select>";

?>