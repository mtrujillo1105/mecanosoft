jQuery(document).ready(function(){
    $("#html.requisiciones_x_ot").click(function(){
      $("#tipo").val('html');
      $("#opcion").val('C');     
      $('#frmBusqueda').attr('target','_self');
      $('#frmBusqueda').attr('action','');
      $('#tipoexport').val('');
      $("#frmBusqueda").submit();  
    });	 
    
    $("#pdf.requisiciones_x_ot").click(function(){
        $("#tipo").val('pdf');
        $("#opcion").val('C');
        $("#frmBusqueda").attr("target","_parent");        
        $("#frmBusqueda").submit();
    });	 
    $("#salir.requisiciones_x_ot").click(function(){
        window.close();
    });	
    
    $("#excel.requisiciones_x_ot").click(function(){
        var_url = base_url +'index.php/compras/requis/export_excel/listar_requisiciones_ot';
        window.open(var_url, this.target, 'width=600,height=250,top=150,left=200');
    });   
    
  
    
});

function agrega_ot(n){
    window.open(base_url+"index.php/ventas/ot/buscar/"+n,"","width=750px,height=430px,noresize=no");    
}

function cargar_ot(n,codot){
    a = "codot["+n+"]";
    b = "ot["+n+"]";
    c = "site["+n+"]";
    document.getElementById(a).value = codot;
    url    = base_url+"index.php/ventas/ot/obtener/"+codot;
    $.getJSON(url,function(data){
        nroot = data.NroOt;
        dirot = data.DirOt;
        document.getElementById(b).value = nroot;
        document.getElementById(c).value = dirot;
    });
}

function cargar_ot2(codot){
     url    = base_url+"index.php/ventas/ot/obtener/"+codot;
   
    if(codot!=''){
        $.getJSON(url,function(data){
            nroot = data.NroOt;      
            $("#codot").val(codot);        
            $("#ot").val(nroot);
            $("#opcion").val('C');
            
            if(data.TipOt!='04'){  $("#fecha_ini").val(data.FecOt); }
           
            $("#codres").val('000000');
            $("#tipoexport").val('');
            $("#frmBusqueda").attr("target","_top");  
        }); 
    }
    else{
        
        $("#codot").val('');        
        $("#ot").val('');  
        $("#opcion").val('C');
        //$("#tipot").val(tipo);
        $("#area").val('');    
        $("#codres").val('');    
        $("#tipoexport").val('');
        $("#frmBusqueda").attr("target","_top");  
        //$("#frmBusqueda").submit();
   
    }
}

function BuscarProducto(n){
    window.open(base_url+"index.php/almacen/producto/buscar/"+n,"","width=750px,height=430px,noresize=no");    

}

function cargar_producto(descripcion,codigo){
  
    //url    = base_url+"index.php/ventas/ot/obtener/"+codot;
    
       if(descripcion!=''){  $("#pro_descripcion").val(descripcion); } 
                else{  $("#pro_descripcion").val(""); }
       
       if(codigo!=''){  $("#pro_codigo").val(codigo); }
                else{  $("#pro_codigo").val("");      }
           
    
    

}




//
//function rpt_requis(obj){
//    codot  = $(obj).parent().parent().attr('id');
//    tipoexport = $(obj).attr('id'); 
//    ini   = $("#fecha_ini").val();
//    fin   = $("#fecha_fin").val();          
//    $("#excel.rpt_costoot").css("display","none");
//    $("#codot").val(codot);
//    url = base_url+"index.php/compras/requis/rpt_requis/";
//    if(tipoexport=='excel'){
//        url = base_url +'index.php/compras/requis/export_excel/listar_req_ot_corto';
//        window.open(url, this.target, 'width=600,height=250,top=150,left=200');
////        $("#tipoexport").val('excel');
////        $('#cadenaot').val();
////        $("#frmBusqueda").attr("action",url);        
////        $("#frmBusqueda").attr("target","_parent");  
////        $("#frmBusqueda").submit();
////        $("#tipoexport").val('');   
//    }
//    else{
//        $("#idcontenido").hide();
//        $("#iddetalle").show();
//        $('#iddetalle').html('<div style="hegiht:50px;"><img src="'+base_url+'img/loading.gif"/></div>');
//        dataString = $("#frmBusqueda").serialize();
//        $.post(url,dataString,function(data){
//            $("#iddetalle").fadeIn(1000).html(data);
//        });        
//    }   
//}
//
//
//    $("#excel.requisiciones_x_ot").click(function(){
//        var_url = base_url +'index.php/compras/requis/export_excel/listar_requisiciones_ot';
//        window.open(var_url, this.target, 'width=600,height=250,top=150,left=200');
//    });   
//
//
//function rpt_materiales(obj){
//    codot      = $(obj).parent().parent().attr('id');  
//    tipoexport = $(obj).attr('id');   
//    $("#excel.rpt_costoot").css("display","none");
//    $("#codot").val(codot);
//    url = base_url+"index.php/contabilidad/costos/rpt_costomateriales/";
//    if(tipoexport=='excel'){
//        $("#tipoexport").val('excel');
//        $('#cadenaot').val();
//        $("#frmBusqueda").attr("action",url);        
//        $("#frmBusqueda").attr("target","_parent");  
//        $("#frmBusqueda").submit();
//        $("#tipoexport").val('');
//    }
//    else{
//        $("#idcontenido").hide();
//        $("#iddetalle").show();
//        $('#iddetalle').html('<div style="height:50px;"><img src="'+base_url+'img/loading.gif"/></div>');
//        dataString = $("#frmBusqueda").serialize();
//        $.post(url,dataString,function(data){
//            $("#iddetalle").fadeIn(1000).html(data);
//        });          
//    }
//}
//
//function rpt_galva(obj){
//    codot  = $(obj).parent().parent().attr('id');
//    tipoexport = $(obj).attr('id'); 
//    ini   = $("#fecha_ini").val();
//    fin   = $("#fecha_fin").val();          
//    $("#excel.rpt_costoot").css("display","none");
//    $("#codot").val(codot);
//    url = base_url+"index.php/balanza/constancia/rpt_constancias/";
//    if(tipoexport=='excel'){
//        var_ot_code   = $("#txt_ot_code").val(); 
//        $("#codot").val(var_ot_code);
//        $("#tipoexport").val('excel');
//        $('#cadenaot').val();
//        $("#frmBusqueda").attr("action",url);        
//        $("#frmBusqueda").attr("target","_parent");  
//        $("#frmBusqueda").submit();
//        $("#tipoexport").val('');   
//    }
//    else{
//        $("#idcontenido").hide();
//        $("#iddetalle").show();
//        $('#iddetalle').html('<div style="hegiht:50px;"><img src="'+base_url+'img/loading.gif"/></div>');
//        dataString = $("#frmBusqueda").serialize();
//        $.post(url,dataString,function(data2){
//            $("#iddetalle").fadeIn(1000).html(data2);
//        });        
//    }   
//}