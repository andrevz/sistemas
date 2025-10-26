<?php
     $path='http://www.fundacioncompartir.net/sistemas/';  //Nombre del servidor y directorio en el que se encuentran las paginas
     $ruta='/home1/fundacio/public_html/sistemas/';  //Directorio en el que se encuentran los archivos
     $server_name='http://www.fundacioncompartir.net';  //Nombre del servidor
     $motorBD="MySQLi";

     $servidor="localhost";  //Servidor de base de datos MySQL
     $logindb="fundacio_sistema";           // Usuario BD MySql
     $contdb="";             // Contrasena de acceso a la BD MySQL
     $basededatos="fundacio_sistema";  // Nombre de BD MySQL
     $depuracion=0;
     $restahoras=0;
     $rutamysqldump="";

     $nombresistema="Gestor de proyectos v. 1.0";
     
    $simulacion=mysqli_connect($servidor,$logindb,$contdb,$basededatos);
if (!mysqli_set_charset('utf8', $simulacion)) {
    echo "Error: Unable to set the character set.\n";
    exit;
}
    mysqli_select_db($basededatos,$simulacion);

    $ruta_bak='/home1/fundacio/bakups/';

    date_default_timezone_set("America/La_Paz");
    $dif_horaria=0;
    
    $umbral_amarillo=0.5;
    $umbral_verde=0.9;
    
?>