<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<!--<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />-->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php print $nombresistema; ?></title>

<link href="<?php print $path; ?>estilos.css" rel="stylesheet" type="text/css" />

<script language="Javascript" type="text/JavaScript">
function generarPassword(form) {
    var strCaracteresPermitidos = 'a,b,c,d,e,f,g,h,i,j,k,m,n,p,q,r,s,t,u,v,w,x,y,z,0,1,2,3,4,5,6,7,8,9';
    var strArrayCaracteres = new Array(34);
    strArrayCaracteres = strCaracteresPermitidos.split(',');
    var length = form.txtCampoLongitud.value, i = 0, j, tmpstr = "";
    do {
        var randscript = -1
        while (randscript < 1 || randscript > strArrayCaracteres.length || isNaN(randscript)) {
            randscript = parseInt(Math.random() * strArrayCaracteres.length)
        }
        j = randscript;
        tmpstr = tmpstr + strArrayCaracteres[j];
        i = i + 1;
    } while (i < length)
    form.contrasena.value = tmpstr;
}
</script>



<!--<script type="text/javascript" src="modalbox/lib/prototype.js"></script>
<script type="text/javascript" src="modalbox/lib/scriptaculous.js?load=builder,effects"></script>
<script type="text/javascript" src="modalbox/modalbox.js"></script>
<link rel="stylesheet" href="modalbox/modalbox.css" type="text/css" media="screen" /> -->

<link rel="stylesheet" href="<?php print $path; ?>js/1.9/css/smoothness/jquery-ui-1.10.0.custom.css">
  <script src="<?php print $path; ?>js/1.9/js/jquery-1.9.0.js"></script>
  <script src="<?php print $path; ?>js/1.9/js/jquery-ui-1.10.0.custom.js"></script>
  <script>
$(function() {
    var dates = $( "#convo, #ela, #pres" ).datepicker({
      defaultDate: "+1w",
      changeYear: true,
      numberOfMonths: 1,
      dayNamesMin: ["Do","Lu","Ma","Mi","Ju","Vi","Sa"],
      monthNames: ["Enero","Febrero","Marzo","Abril","Mayo","Junio",
            "Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"],
            dateFormat: "dd-mm-yy",
      onSelect: function( selectedDate ) {
    //    var option = this.id == "convo" ? "minDate" : "maxDate",
          instance = $( this ).data( "datepicker" ),
          date = $.datepicker.parseDate(
            instance.settings.dateFormat ||
            $.datepicker._defaults.dateFormat,
            selectedDate, instance.settings );
    //    dates.not( this ).datepicker( "option", option, date );
      }
    });
  });
  </script>

<script type="text/javascript" src="<?php print $path; ?>disableFormButtons.js"></script>

</head>

<body>
<table width="1000" cellspacing="0" cellpadding="1" align="center" class="cuerpo" border="0">
    <tr>
        <td background="<?php print $path; ?>images/banner2.jpg" ALIGN='RIGHT' valign="bottom" height="110" style="background-repeat:no-repeat; background-position:center">

        </td>
    </tr>
    <tr>
    <td>