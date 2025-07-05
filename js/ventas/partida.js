jQuery(document).ready(function(){
    $("#nuevo").click(function(){
        location.href = base_url+"index.php/ventas/presupuesto/nuevo";
    });
    $("#editar").click(function(){
        location.href = base_url+"index.php/ventas/presupuesto/editar";
    });     
    $("#buscar").click(function(){
	$("#frmBusqueda").submit();
    });	
    $("#limpiar").click(function(){
        url = base_url+"index.php/ventas/presupuesto/listar";
        location.href=url;
    });
    $("#grabar").click(function(){
        url = base_url+"index.php/ventas/presupuesto/grabar";
        location.href = url;
    });
    $("#cancelar").click(function(){
        url = base_url+"index.php/ventas/presupuesto/listar";
        location.href = url;
    });    
    $("#excel.partida_listar").click(function(){
        dataString = $("#frmBusqueda").serialize();
        url = base_url+"index.php?accion=presupuesto_listar&tipo=excel&"+dataString;
        location.href = url;
    });	    
    $("#pdf").click(function(){
        dataString = $("#frmBusqueda").serialize();
        url = base_url+"index.php?accion=presupuesto_listar&tipo=pdf&"+dataString;
        location.href = url;
    });	
    $("#salir").click(function(){
        window.close();
    });	 
    
    $(".classpartida").click(function(){
        $(".prod_sup").show();
        $(".prod_inf").show();
        $("#cancelarProducto").show();
        $("#grabarProducto").show();
        $("#nuevoProducto").hide();
    }); 
   
});
function ver_subpartida(obj){
    $(obj).hide();
    nro    = $(obj).parent().parent().parent().attr('id');
    $("#"+nro+" .ocultar_subpartida").show();
    nombre = "tbSubpartida"+nro;
    $("."+nombre+"").show();
}
function ocultar_subpartida(obj){
    $(obj).hide();
    nro    = $(obj).parent().parent().parent().attr('id');
    $("#"+nro+" .ver_subpartida").show();
    nombre = "tbSubpartida"+nro;
    $("."+nombre+"").hide();
}
function rpt_ejecutado(obj){
    codigo   = $(obj).parent().parent().parent().attr('id2');
    codot    = $("#codot").val();
    if(codigo.length==2){
        //Detalle de una partida
        codpartida = codigo;
        moneda     = $("#moneda").val();
        if(codigo=='05'){//Materiales
            url = base_url+"index.php/contabilidad/costos/rpt_costomateriales/";
            dataString  = "codot="+codot+"&moneda="+moneda+"&fini=&ffin=&verencabezado=N";
        }
        else{
            url = base_url+"index.php/contabilidad/costos/rpt_tesoreria/";
            dataString  = "codot="+codot+"&moneda="+moneda+"&fini=&ffin=&codpartida="+codpartida+"&verencabezado=N";  
        }
        $.post(url,dataString,function(data){
            $("#iddetalle").html(data);
        }); 
    }
    else if(codigo.length==4){
       //Detalle de una subpartida 
       
       alert(codsubpartida);
    }
}