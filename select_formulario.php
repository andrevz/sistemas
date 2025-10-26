<?php

$selectDestino=$_GET["select"];

//    $tabla=$listadoSelects[$selectDestino];
    include 'config.php';
//    print $selectDestino;
    if ($selectDestino=="idprograma") {
           $consulta="select distinct p.idprograma, p.nombre
                                 from version v inner join recursohumano rh on rh.idrecursohumano=v.idrecursohumano
                                 inner join programa p on p.idprograma=v.idprograma inner join tipoprograma tp on tp.idtipoprograma=p.idtipoprograma
                                 where activo=1 and tp.idtipoprograma like '".$_GET["tipo"]."'
                                 order by nombre";
           $opcion1="<option value='%'>[ Todos los programas ]</option>";
    }
    if ($selectDestino=="ciudad") {
           $consulta="select distinct c.idciudad, c.nombre
                                 from version v inner join recursohumano rh on rh.idrecursohumano=v.idrecursohumano
                                 inner join programa p on p.idprograma=v.idprograma inner join tipoprograma tp on tp.idtipoprograma=p.idtipoprograma
                                 inner join ciudad c on c.idciudad=v.ciudad
                                 where activo=1 and tp.idtipoprograma like '".$_GET["tipo"]."' and p.idprograma like '".$_GET["programa"]."'";
           $opcion1="<option value='%'>[ Todas las ciudades ]</option>";
    }
    if ($selectDestino=="gestion") {
           $consulta="select distinct upper(v.gestion), upper(v.gestion)
                                 from version v inner join recursohumano rh on rh.idrecursohumano=v.idrecursohumano
                                 inner join programa p on p.idprograma=v.idprograma inner join tipoprograma tp on tp.idtipoprograma=p.idtipoprograma
                                 where activo=1 and tp.idtipoprograma like '".$_GET["tipo"]."' and p.idprograma like '".$_GET["programa"]."'
                                 and upper(v.ciudad) like '".$_GET["ciudad"]."'";
           $opcion1="<option value='%'>[ Todas las gestiones ]</option>";
    }
    if ($selectDestino=="docente") {
           $consulta="select distinct rh.idrecursohumano, concat(upper(apellidos), ', ', upper(nombres))
                                 from version v inner join programa p on p.idprograma=v.idprograma
                                 inner join tipoprograma tp on tp.idtipoprograma=p.idtipoprograma
                                 inner join materiaversion mv on mv.idversion=v.idversion
                                 inner join planmateriaversiondocente pmvd on pmvd.idmateriaversion=mv.idmateriaversion inner join
                                 recursohumano rh on rh.idrecursohumano=pmvd.idrecursohumano
                                 where activo=1 and tp.idtipoprograma like '".$_GET["tipo"]."' and p.idprograma like '".$_GET["programa"]."'
                                 and upper(v.ciudad) like '".$_GET["ciudad"]."' order by upper(apellidos), upper(nombres)";
           $opcion1="<option value='%'>[ Todos los docentes ]</option>";
    }

    $resultado=mysqli_query($consulta) or die(mysqli_error());

    echo "<select name='".$selectDestino."' id='".$selectDestino."'>";
    echo $opcion1;
    while ($registro=mysqli_fetch_array($resultado)) {
            print "<option value='$registro[0]'>$registro[1]</option>";
      }
    echo "</select>";

?>