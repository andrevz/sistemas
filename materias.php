<?php

include("valida.php");
$actividad=2;
include("niveles_acceso.php");

if (isset($_GET["mode"]) && $_GET["mode"]=='d' && $eliminar) {

      $sql="delete from materia where idmateria=".$_GET["id"];
      mysqli_query($sql) or die(mysqli_error());

      header("Location: materias.php");
      exit;

}
if (isset($_POST["MM_edit"]) && $_POST["MM_edit"]=="actualizar" &&  $editar) {

      $sql="update materia set nombre='".$_POST["materia"]."', horas='".$_POST["horas"]."', nombre_oficial='".$_POST["nombreof"]."' where idmateria=".$_POST["ids"];
      mysqli_query($sql) or die(mysqli_error());

      header("Location: materias.php");
      exit;

}
if (isset($_POST["MM_insert"]) && $_POST["MM_insert"]=="actualizar"  && $insertar) {

      $sql="insert into materia values (null, '".$_POST["materia"]."', '".$_POST["horas"]."', '', '".$_POST["nombreof"]."')";
      mysqli_query($sql) or die(mysqli_error());

      header("Location: materias.php");
      exit;

}
include("encabezado.php");

?>

<table align="center" bgcolor="#ffffff" width="100%">
<tr>
    <td width="200" valign="top">
        <table align="center" width="100%">
                        <tr>
                            <td class="textsubmenu"><img src="images/bc_parent.png" /></td><td class="textsubmenu"><a href="parametros.php" class="submenu">Retornar al men&uacute; anterior</a></td>
                        </tr>
                        <?php
                            if ($nacceso>=4) {
                        ?>
                        <tr>
                            <td class="textsubmenu"><img src="images/ok.gif" /></td><td class="textsubmenu"><a href="materias_nuevas.php" class="submenu">Crear materia nueva</a></td>
                        </tr>
                        <?php
                        }
                        ?>
        </table>

        <br><br>
    </td>
    <td width="800">
    <table align="center" width="100%" cellspacing='2'>
        <tr>
            <td class="bordes">
            <table class='contenido' width='100%'>
                <tr>
                <?php
                    if (!isset($_GET["pag"])) {
                        $_GET["pag"]="A";
                    }
                    $sql_letras="SELECT distinct left(ucase(trim(nombre)),1) FROM materia order by left(ucase(trim(nombre)),1)";
                    $res_letras=mysqli_query($sql_letras);
                    $cadena_letras="";
                    while ($fila_letras=mysqli_fetch_array($res_letras)) {
                          if ($_GET["pag"]==$fila_letras[0]) {
                              $cadena_letras.=$fila_letras[0]." ";
                          } else {
                              $cadena_letras.="<a href='materias.php?pag=".$fila_letras[0]."'>".$fila_letras[0]."</a> ";
                          }
                    }
                ?>
                    <td colspan='5'>P&aacute;ginas: <?php print $cadena_letras; ?></td>
                </tr>
                <tr>
                    <td class='tititems' width='70'>N&uacute;mero</td>
                    <td class='tititems' width='400'>Nombre</td>
                    <td class='tititems' width='90'>Programa</td>
                    <td class='tititems' width='40'>Horas</td>
                    <td class='tititems' width='70'>Acciones</td>
                </tr>
                <?php
                    $col1="#eeeeee";
                    $col2="#ffffff";
                    $sql="select m.*, p.sigla from materia m left join programa p on p.idprograma=m.idprograma where left(ucase(trim(m.nombre)),1) like '".$_GET["pag"]."' order by trim(ucase(m.nombre))";

                    $res=mysqli_query($sql);
                    $colactual="";
                    while ($fila=mysqli_fetch_array($res)) {
                          $sql1="select count(*) from materiaversion where idmateria=$fila[0]";
                          $res1=mysqli_query($sql1);
                          $fila1=mysqli_fetch_array($res1);
                          if ($colactual!=$col1) {
                             $colactual=$col1;
                          } else {
                             $colactual=$col2;
                          }
                          print "
                <tr bgcolor='$colactual'>
                    <td class='tabladettxt'>".$fila[6]."</td>
                    <td class='tabladettxt'>".$fila[1]."</td>
                    <td class='tabladettxt'>".$fila[7]."</td>
                    <td class='tabladetnum'>".$fila[2]."</td><td align='center'>";
                    if ($editar) {
                    print "<a href='materias_nuevas.php?mode=e&id=".$fila[0]."#editor'><img border='0' src='images/editar.png' width='20' alt='Editar' title='Editar'></a> ";
                    }
                    if ($fila1[0]<1 && $eliminar) {
                        print "<a onclick='if(confirm(\"Esta seguro que desea eliminar la materia ".$fila[1]."?\")) { return true;} else { return false; }' href='materias.php?mode=d&id=".$fila[0]."'><img border='0' src='images/delete.png' width='20' alt='Eliminar' title='Eliminar'></a>";
                    }
                    print "</td>
                </tr>";
                    }
                 if (isset($_GET["ids"])) {
                  $sql="select * from materia where idmateria=".$_GET["ids"];
                  $resres=mysqli_query($sql);
                  $filares=mysqli_fetch_array($resres);
               }
                ?>
           <!-- <form method="POST" name="actualizar">
             <table width='100%' class='contenido'>
                <tr>
                    <td width='550'>
                         <input name='materia' id='materia' type='text' size='80' value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e" ) {
                          print $filares[1]; } ?>"><br>
                          <input name='nombreof' id='nombreof' type='text' size='80' value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e" ) {
                          print $filares[4]; } ?>">
                    </td>
                    <td width='60'>
                       <input type="text" name="horas" size="5" value="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e" ) { print $filares[2]; } ?>">
                    </td>
                    <td width='70'><input class="save" type="submit" name="guardar" size="60" value="">
                    <?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { ?>
                          <input class="cancel" type="button" name="cancelar" size="60" value="" onclick="window.location='materias.php'">
                    <?php } ?>
                         </td>
                        </tr>
                      </table>
                      <input type="hidden" name="<?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") { print "MM_edit"; } else { print "MM_insert"; } ?>" value="actualizar">
                      <?php if (isset($_GET["mode"]) && $_GET["mode"]=="e") {
                               print "<input type='hidden' name='ids' value='".$_GET["ids"]."'>";
                            }?>
                    </form><a name='editor'> </a>-->


            </table>

        </td>
        </tr>

        </table>
    </td>
    </tr>

    </table>
</td>
</tr>

</table>
</td></tr></table>
<?php
include("pie.php");
?>
</body>
</html>