jQuery(document).ready(function(){
    $("#ingresar").click(function(){
        $("#frmInicio").submit();
    });
    
    $("#cerrar").click(function(){
        url = base_url+"index.php/inicio/index";
        location.href = url;
    });    
    
    $('#menu li a').click(function(event){
            var elem = $(this).next();
            if(elem.is('ul')){
                    event.preventDefault();
                    $('#menu ul:visible').not(elem).slideUp();
                    elem.slideToggle();
            }
    });
});
function activar(obj){
    cadena  = obj.value;
    myArray = cadena.split('-');
    tipo    = myArray[0];
    numero  = myArray[1];
    if(tipo=='OC'){
       serie   = "001";
       $("#serie").val(serie);
       $("#numero").val(numero);
       url = base_url+"index.php/compras/ocompra/ver/";
       $("#frmPrincipal").attr("action",url);
       $("#frmPrincipal").attr("target","_blank");
       $("#frmPrincipal").submit();
    }
    else if(tipo=='OT'){
       $("#codot").val(numero);
       url = base_url+"index.php/ventas/ot/ver/";
       $("#frmPrincipal").attr("action",url);
       $("#frmPrincipal").attr("target","_blank");
       $("#frmPrincipal").submit();
    }
}

function submitenter(myfield,e)
{
var keycode;
if (window.event) keycode = window.event.keyCode;
else if (e) keycode = e.which;
else return true;

if (keycode == 13)
   {
   myfield.form.submit();
   return false;
   }
else
   return true;
}

function capLock(e){
 kc = e.keyCode?e.keyCode:e.which;
 sk = e.shiftKey?e.shiftKey:((kc == 16)?true:false);
 if(((kc >= 65 && kc <= 90) && !sk)||((kc >= 97 && kc <= 122) && sk))
  document.getElementById('divMayus').style.visibility = 'hidden';
 else
  document.getElementById('divMayus').style.visibility = 'visible';
}