<?php
 require_once('config.php');

if (!isset($_SESSION)) {
  session_start();
}

if (!isset($_SESSION['Username'])) {
  header("Location: index.php"); 
  exit;
}
 include("funciones.php");

?>