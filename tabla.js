function nuevoAjax()
{ 
    /* Crea el objeto AJAX. Esta funcion es generica para cualquier utilidad de este tipo, por
    lo que se puede copiar tal como esta aqui */
    var xmlhttp=false;
    try
    {
        // Creacion del objeto AJAX para navegadores no IE
        xmlhttp=new ActiveXObject("Msxml2.XMLHTTP");
    }
    catch(e)
    {
        try
        {
            // Creacion del objet AJAX para IE
            xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
        catch(E)
        {
            if (!xmlhttp && typeof XMLHttpRequest!='undefined') xmlhttp=new XMLHttpRequest();
        }
    }
    return xmlhttp; 
}

function cargaContenido(idSelectDestino,ident, tabla)
{

        var selectDestino=window.opener.document.getElementById(idSelectDestino);
        var ajax=nuevoAjax();
        ajax.open("GET", tabla+".php?idv="+ident, true);
        ajax.onreadystatechange=function() 
        { 
            if (ajax.readyState==1)
            {
                // Mientras carga elimino la opcion "Selecciona Opcion..." y pongo una que dice "Cargando..."
//                selectDestino.length=0;
//                var nuevaOpcion=document.createElement("option"); nuevaOpcion.value=0; nuevaOpcion.innerHTML="Cargando...";
//                selectDestino.appendChild(nuevaOpcion); selectDestino.disabled=true;
           }
            if (ajax.readyState==4)
            {
                selectDestino.parentNode.innerHTML=ajax.responseText;
                window.close();
            }
        }
        ajax.send(null);

    }