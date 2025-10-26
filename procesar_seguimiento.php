<?php

include("valida.php");

print "archivo";

if (isset($_POST)) {
print_r($_POST["fecha"]);
}

if (isset($_POST["MM_insert"])) {

    if ($_POST["fecha"]=="" or is_null($_POST["fecha"]) or $_POST["observaciones"]=="" or is_null($_POST["observaciones"])) {
       $error="<font color='red'><b>Deben registrarse obligatoriamente la fecha y las observaciones </b></font>";
    } else {

      $sql="insert into seguimiento values (null, '".$_POST["fecha"]."', '1', '".$_POST["idpr"]."', '".$_POST["observaciones"]."', '".$_SESSION['IdRH']."')";
      mysql_query($sql) or die(mysql_error());

      ?>
      <script language='javascript'>Modalbox.show('seguimiento.php?id=<?php print $_GET["id"]; ?>', {title: 'seguimiento', width: 800 }); return false;</script>
      <?php
//      header("Location: seguimiento.php?id=".$_POST["idpr"]);
      exit;
   }
} else if (isset($_GET["mode"]) && $_GET["mode"]=="d") {

      $sql="delete from seguimiento where idseguimiento=".$_GET["ids"];
      mysql_query($sql) or die(mysql_error());


      header("Location: seguimiento.php?id=".$_GET["id"]);
      exit;
}
?>