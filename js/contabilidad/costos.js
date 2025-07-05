jQuery(document).ready(function(){    
    $("#salir").click(function(){
        window.close();
    }); 

    /*Reportes en HTML*/
    $("#html.rpt_costoot").click(function(){
        $('#tipoexport').val('');
        ot = $('#ot').val();
        if(ot.trim()!=''){
            blockui();
            $("#frmBusqueda").submit();
        }
        else{
            if(confirm("Esto mostrara los resultados para todas las OT, esta seguro continuar?")){
                blockui();
                $("#frmBusqueda").submit();                
            }
        }
    });	  

    $("#excel.costos_x_ot").click(function(){
      var_url = base_url +'index.php/contabilidad/costos/export_excel/costos_x_ot';
      window.open(var_url, this.target, 'width=600,height=250,top=150,left=200');  
    });
    
    /*Reportes en excell*/
    $("#excel.rpt_costoot").click(function(){
        $("#tipoexport").val('excel0');
        $("#nivel").val('0');
        $("#frmBusqueda").attr("target","_parent");        
        $("#frmBusqueda").submit();
    });	     

    $("#excel.rpt_manoobra").click(function(){
        $("#tipoexport").val('excel2');
        $("#nivel").val('2');
        $("#frmBusqueda").attr("target","_parent");        
        $("#frmBusqueda").submit();
    });	  

    $("#excel.rpt_servicios").click(function(){
        $("#tipoexport").val('excel2');
        $("#nivel").val('2');
        $("#frmBusqueda").attr("target","_parent");        
        $("#frmBusqueda").submit();
    });	 
    
    $("#excel.rpt_tesoreria").click(function(){
        $("#tipoexport").val('excel2');
        $("#nivel").val('2');
        $("#frmBusqueda").attr("target","_parent");        
        $("#frmBusqueda").submit();
    });	     

    $("#excel.rpt_caja").click(function(){
        $("#tipoexport").val('excel2');
        $("#nivel").val('2');
        $("#frmBusqueda").attr("target","_parent");        
        $("#frmBusqueda").submit();
    });	 	
	
    /*Reportes en pdf*/  
    $("#pdf.rpt_costoot").click(function(){
        $("#tipo").val('pdf');
        $("#nivel").val('3');
        $("#frmFact").attr("target","_blank");
        $("#frmFact").submit();
    });	
    
    $("#pdf.rpt_materiales").click(function(){
        $("#tipo").val('pdf');
        $("#nivel").val('3');
        $("#frmFact").attr("target","_blank");
        $("#frmFact").submit();
    });	
    
    $("#pdf.rpt_manoobra").click(function(){
        $("#tipo").val('pdf');
        $("#nivel").val('3');
        $("#frmFact").attr("target","_blank");
        $("#frmFact").submit();
    });	

    $("#pdf.rpt_servicios").click(function(){
        $("#tipo").val('pdf');
        $("#nivel").val('3');
        $("#frmFact").attr("target","_blank");
        $("#frmFact").submit();
    });	

    $("#pdf.rpt_tesoreria").click(function(){
        $("#tipo").val('pdf');
        $("#nivel").val('3');
        $("#frmFact").attr("target","_blank");
        $("#frmFact").submit();
    });	
    
    $("#pdf.rpt_caja").click(function(){
        $("#tipo").val('pdf');
        $("#nivel").val('3');
        $("#frmFact").attr("target","_blank");
        $("#frmFact").submit();
    });	
});

function rpt_otros(obj){
    codot     = $(obj).parent().parent().attr('id');
    $("#excel.rpt_costoot").css("display","none");
    moneda    = $("#moneda").val();
    fInicio   = $("#fecha_ini").val();
    fFin      = $("#fecha_fin").val();
    url = base_url+"index.php/contabilidad/costos/rpt_otros/";
    $('#idcontenido').html('<div style="hegiht:50px;"><img src="'+base_url+'img/loading.gif"/></div>');
    dataString  = "codot="+codot+"&moneda="+moneda+"&fini="+fInicio+"&ffin="+fFin;
    $.post(url,dataString,function(data){
        $("#idcontenido").fadeIn(1000).html(data);
    }); 
    
  
    
}
/*Ver documentos*/

function ver_voucher(obj){
    numero = $(obj).parent().attr('id');
    $("#numero").val(numero);
    url = base_url+"index.php/finanzas/voucher/ver/";
    $("#frmBusqueda").attr("action",url);
    $("#frmBusqueda").attr("target","_blank");
    $("#frmBusqueda").submit();
}

function ver_vale_salida(obj){
    numero = $(obj).parent().attr('id');
    $("#numero").val(numero);
    url = base_url+"index.php/almacen/nsalida/ver/";
    $("#frmBusqueda").attr("action",url);
    $("#frmBusqueda").attr("target","_blank");
    $("#frmBusqueda").submit();
}

function ver_nota_ingreso(obj){
    numero = $(obj).parent().attr('id');
    $("#numero").val(numero);
    url = base_url+"index.php/almacen/ningreso/ver/";
    $("#frmBusqueda").attr("action",url);
    $("#frmBusqueda").attr("target","_blank");
    $("#frmBusqueda").submit();
}

function ver_devolucion(obj){
    numero = $(obj).parent().attr('id');
    $("#numero").val(numero);
    url = base_url+"index.php/almacen/devolucion/ver/";
   // alert(url);
    $("#frmBusqueda").attr("action",url);
    $("#frmBusqueda").attr("target","_blank");
    $("#frmBusqueda").submit();
}

function ver_facturac(obj){
    numero = $(obj).parent().attr('id');
    serie  = $(obj).parent().attr('id2');
    orden  = $(obj).parent().attr('id4');
    codot  = $(obj).parent().attr('id3');
    tipo   = 'FV';
   
    $("#serie").val(serie);
    $("#numero").val(numero);
    $("#codpartida").val(orden);
    $("#codot").val(codot);
    
    
    if(tipo=='FV'){
        url = base_url+"index.php/compras/facturac/ver/";    
    
    

}
    else if(tipo=='RS'){
        url = base_url+"index.php/compras/requis_ser/ver/";  
    }
    $("#frmBusqueda").attr("action",url);
    $("#frmBusqueda").attr("target","_blank");
    $("#frmBusqueda").submit();
}

function ver_cajas(obj){
    numero = $(obj).parent().attr('id');
    $("#numero").val(numero);
    url = base_url+"index.php/finanzas/caja/ver/";
    $("#frmBusqueda").attr("action",url);
    $("#frmBusqueda").attr("target","_blank");
    $("#frmBusqueda").submit();
}


function ver_requis(obj){
    numero = $(obj).parent().attr('id');
    $("#numero").val(numero);
    url = base_url+"index.php/compras/requis/ver/";
    $("#frmBusqueda").attr("action",url);
    $("#frmBusqueda").attr("target","_blank");
    $("#frmBusqueda").submit();
}

function ver_requis_ser(obj){
    numero = $(obj).parent().attr('id');
    codot = $(obj).parent().attr('id2');
    $("#numero").val(numero);
    $("#codot").val(codot);
    url = base_url+"index.php/compras/requis_ser/ver/";
    $("#frmBusqueda").attr("action",url);
    $("#frmBusqueda").attr("target","_blank");
    $("#frmBusqueda").submit();
}

function ver_ocos(obj){
    numero = $(obj).parent().attr('id');
    serie  = $(obj).parent().attr('id2');
    tipo   = $(obj).parent().attr('id3');
    $("#serie").val(serie);
    $("#numero").val(numero);
    if(tipo=='OC'){
        url = base_url+"index.php/compras/ocompra/ver/";    
    }
    else if(tipo=='RS'){
        url = base_url+"index.php/compras/requis_ser/ver/";  
    }
    $("#frmBusqueda").attr("action",url);
    $("#frmBusqueda").attr("target","_blank");
    $("#frmBusqueda").submit();
}

function ver_ot(obj){
    codigo = $(obj).parent().attr('id');
    $("#codot").val(codigo);
    url = base_url+"index.php/ventas/ot/ver/";
    $("#frmBusqueda").attr("action",url);
    $("#frmBusqueda").attr("target","_blank");
    $("#frmBusqueda").submit();
}

function ver_detalle(){
    tipo = $('#tiproducto').val();
    anio = $('#tipot').val();
    if(anio==''){
        alert('Favor ingrese el año');
    }    
//    else if(tipo==''){
//        alert('Favor ingresar el tipo de producto.');
//    }
    if(tipo==''){
       $('.pa01').show(); 
       $('.pa02').show(); 
       $('.pa03').show(); 
       $('.pa06').show(); 
       $('.pa07').show(); 
       $('.pa04').show(); 
       $('.pa09').show(); 
       $('.pa08').show(); 
       $('.pa10').show(); 
       $('.pa11').show(); 
       $('.pa12').show(); 
       $('.pa13').show(); 
       $('.pa14').show(); 
       $('.pa15').show(); 
    }
    else if(tipo=='02'){
       $('.pa01').show(); 
       $('.pa02').show(); 
       $('.pa11').show(); 
       $('.pa12').show(); 
       $('.pa13').show(); 
       $('.pa14').show(); 
       $('.pa15').show();
    }
    else{
       $('.pa01').show(); 
       $('.pa02').show(); 
       $('.pa03').show(); 
       $('.pa06').show(); 
       $('.pa07').show(); 
       $('.pa04').show(); 
       $('.pa09').show(); 
       $('.pa08').show(); 
       $('.pa10').show(); 
       $('.pa13').show(); 
       //$('.pa14').show();        
       //$('.pa15').show();  
    }
}
function ver_general(){
    tipo = $('#tiproducto').val();
    anio = $('#tipot').val();
    if(anio==''){
        alert('Favor ingrese el año');
    }
//    else if(tipo==''){
//        alert('Favor ingrese un tipo de OT.');
//    }
    else{
        $('.pa01').hide(); 
        $('.pa02').hide(); 
        $('.pa03').hide(); 
        $('.pa06').hide(); 
        $('.pa07').hide(); 
        $('.pa04').hide(); 
        $('.pa09').hide(); 
        $('.pa08').hide(); 
        $('.pa10').hide(); 
        $('.pa11').hide(); 
        $('.pa12').hide(); 
        $('.pa13').hide(); 
        $('.pa14').hide();         
        $('.pa15').hide();  
    }
}

$("#excel.rpt_materiales").click(function(){
    $("#tipoexport").val('excel');

    $('#tipoex').val();
    $("#nivel").val('0');
    url = base_url+"index.php/contabilidad/costos/rpt_costomateriales/";
    $("#frmBusqueda").attr("action",url);        
    $("#frmBusqueda").attr("target","_parent");        
    $("#frmBusqueda").submit();
});	  

function agrega_ot(n){
    window.open(base_url+"index.php/ventas/ot/buscar/"+n,"","width=750px,height=430px,noresize=no");
}

function cargar_ot2(codot){
    url    = base_url+"index.php/ventas/ot/obtener/"+codot;
    if(codot!=''){
        $.getJSON(url,function(data){ 
            $("#ot").val(data.NroOt);
            $("#tipo").val(data.TipOt);
        }); 
    }
    else{
        $("#codot").val('');        
        $("#ot").val('');  
        $("#opcion").val('C'); 
        $("#tipoexport").val('');
        $("#frmBusqueda").attr("target","_top");     
    }    
}