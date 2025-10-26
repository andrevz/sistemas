<?php

function acceso ($rol, $version=0, $escuela=0, $tipoprograma=0, $ciudad=0, $actividad=0, $modulo=0) {
    global $simulacion;
    if ($rol==1) {
       return 99;
    } else {
      $acc_ver=99;
      $acc_esc=99;
      $acc_act=0;
      $acc_mod=0;
      $acc_tp=99;
      $acc_ciu=99;
      if ($actividad==0 && $modulo==0) {
         $acc_act=99;
         $acc_mod=99;
      }
      if ($version!=0) {
         $sql="select idnivel from rolversion where idrol=$rol and idversion=$version";
         $res=mysql_query($sql);
         if ($fila=mysql_fetch_array($res)) {
            $acc_ver=$fila[0];
         } else {
            $acc_ver=0;
         }
      }
      if ($escuela!=0) {
         $sql="select idnivel from rolescuela where idrol=$rol and idescuela=$escuela";
         $res=mysql_query($sql);
         if ($fila=mysql_fetch_array($res)) {
            $acc_esc=$fila[0];
         } else {
            $acc_esc=0;
         }
      }
      if ($actividad!=0) {
         $sql="select nivelacceso from rolesactividades where idrol=$rol and idactividad=$actividad";
         $res=mysql_query($sql);
         if ($fila=mysql_fetch_array($res)) {
            $acc_act=$fila[0];
            $acc_mod=99;
         }
      }
      if ($modulo!=0) {
         $sql="select * from rolesmodulos where idrol=$rol and idmodulo=$modulo";
         $res=mysql_query($sql);
         if ($fila=mysql_fetch_array($res)) {
            $acc_act=99;
            $acc_mod=99;
         }
      }
      if ($tipoprograma!=0) {
         $sql="select idnivel from roltipoprograma where idrol=$rol and idtipoprograma=$tipoprograma";
         $res=mysql_query($sql);
         if ($fila=mysql_fetch_array($res)) {
            $acc_tp=$fila[0];
         } else {
            $acc_tp=0;
         }
      }
      if ($ciudad!=0) {
         $sql="select idnivel from rolciudad where idrol=$rol and idciudad=$ciudad";
         $res=mysql_query($sql);
         if ($fila=mysql_fetch_array($res)) {
            $acc_ciu=$fila[0];
         } else {
            $acc_ciu=0;
         }
      }
      return min($acc_ver, $acc_esc, $acc_act, $acc_mod, $acc_tp, $acc_ciu);
    }
}

function fecha($date, $tipo = 0){
     if (!is_null($date)) {
         if ($tipo==99) {
               $arrfecha=explode("-",$date);
               $fecha=$arrfecha[2]."-".$arrfecha[1]."-".$arrfecha[0];
         } else {
                          $nombremes = array (1=>"Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
                          $nommes = array (1=>"Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic");
                          $nommes[0] = "00";
                          $arrfecha=explode("-",$date);
                          $arrdiahora=explode(" ",$arrfecha[2]);
                          $anno = $arrfecha[0];
                          $mes = $arrfecha[1];
                          $dia = $arrdiahora[0];
                          if (count($arrdiahora)>1)
                          {
                             $hora = $arrdiahora[1];
                          }
                          else
                          {
                              $hora="";
                          }

                          if ($tipo == 0 ) {
                               $nombmes = $nombremes[intval($mes)];
                               $fecha = $dia." de ".$nombmes." de ".$anno.", a las ".$hora;
                          } elseif ($tipo == 1) {
                               $fecha = $dia."-".$mes."-".substr($anno,2,2)."  ".$hora;
                          } elseif ($tipo == 2) {
                               $nombmes = $nommes[intval($mes)];
                               $fecha = $dia."-".$nombmes."-".substr($anno,2,2);
                          } elseif ($tipo == 3) {
                               $nombmes = $nombremes[intval($mes)];
                               $fecha = $dia." de ".$nombmes." de ".$anno;
                          } elseif ($tipo == 4) {
                               $nombmes = $nommes[intval($mes)];
                               $fecha = $dia."-".$nombmes."-".$anno;
                          } elseif ($tipo == 5) {
                               $fecha = $dia."/".$mes."/".substr($anno,2);
                          } elseif ($tipo == 6) {
                               $fecha = substr($hora,0,strpos($hora,":",4));
                          } elseif ($tipo == 7) {
                               $nombmes = $nommes[intval($mes)];
                               $fecha = $dia."/".$nombmes."/".$anno;
                          } elseif ($tipo == 8) {
                               $nombmes = $nommes[intval($mes)];
                               $fecha = $dia."-".$mes."-".$anno;
                          } else if ($tipo == 9 ) {
                               $nombmes = $nommes[intval($mes)];
                               $fecha = $dia."/".$nombmes."/".$anno.", ".$hora;
                          }
         }
     } else {
         $fecha="--";
     }
     return $fecha;
}

function logea ($empresa, $tipo, $detalle) {
    global $simulacion;
    $bitacora="insert into bitacora values (null, now(), ".$empresa.", '".$tipo."', '".AddSlashes($detalle)."')";
    mysql_query($bitacora);
}

function imagen($tipo) {
    print "<img src='".$path."images/";

       switch ($tipo) {
              case "application/vnd.ms-excel" :
                   print "Office_XLS.png";
                   break;
              case "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" :
                   print "Office_XLS.png";
                   break;
              case "application/pdf" :
                   print "Office_PDF.png";
                   break;
              case "application/vnd.openxmlformats-officedocument.wordprocessingml.document" :
                   print "Office_DOC.png";
                   break;
              case "application/msword" :
                   print "Office_DOC.png";
                   break;
              case "application/vnd.ms-powerpoint" :
                   print "Office_PPT.png";
                   break;
              case "application/vnd.openxmlformats-officedocument.presentationml.presentation" :
                   print "Office_PPT.png";
                   break;
              case "application/zip" :
                   print "zip.png";
                   break;
              case "text/html" :
                   print "Office_HTML.png";
                   break;
              case "application/vnd.ms-visio.viewer" :
                   print "Office_VSD.png";
                   break;
              case "text/plain" :
                   print "Office_TXT.png";
                   break;
              case "application/msaccess" :
                   print "Office_MDB.png";
                   break;
              case "image/jpeg" :
                   print "Image_JPG.png";
                   break;
              case "image/jpeg" :
                   print "Image_JPG.png";
                   break;
              case "image/png" :
                   print "Image_PNG.png";
                   break;
              case "image/gif" :
                   print "Image_GIF.png";
                   break;
              case "image/bmp" :
                   print "Image_BMP.png";
                   break;
              default:
                   print "file.png";
       }
    print "'>";
}

function sumArray($array, $params = array('direction' => 'x', 'key' => 'xxx'), $exclusions = array()) {

    if(!empty($array)) {
   
        $sum = 0;
   
        if($params['direction'] == 'x') {
       
            $keys = array_keys($array);
           
            for($x = 0; $x < count($keys); $x++) {
           
                if(!in_array($keys[$x], $exclusions))
                    $sum += $array[$keys[$x]];
           
            }
           
            return $sum;
       
        } elseif($params['direction'] == 'y') {
       
            $keys = array_keys($array);
       
            if(array_key_exists($params['key'], $array[$keys[0]])) {
           
                for($x = 0; $x < count($keys); $x++) {
               
                    if(!in_array($keys[$x], $exclusions))
                        $sum += $array[$keys[$x]][$params['key']];
                   
                }
                   
                return $sum;
           
            } else return false;
       
        } else return false;
   
    } else return false;

}
?>