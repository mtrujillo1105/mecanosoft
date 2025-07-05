function rpt_requis(obj){
    codot  = $(obj).parent().parent().attr('id');
    tipoexport = $(obj).attr('id'); 
    ini   = $("#fecha_ini").val();
    fin   = $("#fecha_fin").val();          
    $("#excel.rpt_costoot").css("display","none");
    $("#codot").val(codot);
    url = base_url+"index.php/compras/requis/rpt_requis/";
    if(tipoexport=='excel'){
        url = base_url +'index.php/compras/requis/export_excel/listar_req_ot_corto';
        window.open(url, this.target, 'width=600,height=250,top=150,left=200');
    }
    else{
        $("#idcontenido").hide();
        $("#iddetalle").show();
        $('#iddetalle').html('<div style="hegiht:50px;"><img src="'+base_url+'img/loading.gif"/></div>');
        dataString = $("#frmBusqueda").serialize();
        $.post(url,dataString,function(data){
            $("#iddetalle").fadeIn(1000).html(data);
        });        
    }   
}

function rpt_galva(obj){
    codot  = $(obj).parent().parent().attr('id');
    tipoexport = $(obj).attr('id'); 
    ini   = $("#fecha_ini").val();
    fin   = $("#fecha_fin").val();          
    $("#excel.rpt_costoot").css("display","none");
    $("#codot").val(codot);
    url = base_url+"index.php/balanza/constancia/rpt_constancias/";
    if(tipoexport=='excel'){
        var_ot_code   = $("#txt_ot_code").val(); 
        $("#codot").val(var_ot_code);
        $("#tipoexport").val('excel');
        $('#cadenaot').val();
        $("#frmBusqueda").attr("action",url);        
        $("#frmBusqueda").attr("target","_parent");  
        $("#frmBusqueda").submit();
        $("#tipoexport").val('');   
    }
    else{
        $("#idcontenido").hide();
        $("#iddetalle").show();
        $('#iddetalle').html('<div style="hegiht:50px;"><img src="'+base_url+'img/loading.gif"/></div>');
        dataString = $("#frmBusqueda").serialize();
        $.post(url,dataString,function(data2){
            $("#iddetalle").fadeIn(1000).html(data2);
        });        
    }   
}

function rpt_galvat(obj){
    codot  = $(obj).parent().parent().attr('id');
    tipoexport = $(obj).attr('id'); 
    ini   = $("#fecha_ini").val();
    fin   = $("#fecha_fin").val();          
    $("#excel.rpt_costoot").css("display","none");
    $("#codot").val(codot);
    url = base_url+"index.php/balanza/constancia/rpt_constancias/";
    if(tipoexport=='excel'){
        var_ot_code   = $("#txt_ot_code").val(); 
        $("#codot").val(var_ot_code);
        $("#tipoexport").val('excel');
        $('#cadenaot').val();
        $("#frmBusqueda").attr("action",url);        
        $("#frmBusqueda").attr("target","_parent");  
        $("#frmBusqueda").submit();
        $("#tipoexport").val('');   
    }
    else{
//        $("#idcontenido").hide();
        $("#iddetalle").show();
        $('#iddetalle').html('<div style="hegiht:50px;"><img src="'+base_url+'img/loading.gif"/></div>');
        dataString = $("#frmBusqueda").serialize();
        $.post(url,dataString,function(data2){
            $("#iddetalle").fadeIn(1000).html(data2);
        });        
    }   
}


function rpt_materiales(obj){
    codot      = $(obj).parent().parent().attr('id');  
    tipoexport = $(obj).attr('id');   
    $("#excel.rpt_costoot").css("display","none");
    $("#codot").val(codot);
    url = base_url+"index.php/contabilidad/costos/rpt_costomateriales/";
    if(tipoexport=='excel'){
        url = base_url +'index.php/contabilidad/costos/export_excel/listar_costomateriales';
        window.open(url, this.target, 'width=600,height=250,top=150,left=200');
    }
    else{
        $("#idcontenido").hide();
        $("#iddetalle").show();
        $('#iddetalle').html('<div style="height:50px;"><img src="'+base_url+'img/loading.gif"/></div>');
        dataString = $("#frmBusqueda").serialize();
        $.post(url,dataString,function(data){
            $("#iddetalle").fadeIn(1000).html(data);
        });          
    }
}



function rpt_materialest(obj){
    codot  = $(obj).parent().parent().attr('id');
    tipoexport = $(obj).attr('id'); 
    ini   = $("#fecha_ini").val();
    fin   = $("#fecha_fin").val();
    a=new Date(ini);
    b=new Date(fin);
    //if(a.getDate()==b.getDate())
    //{            
        $("#excel.rpt_costoot").css("display","none");
        $("#codot").val(codot);
        url = base_url+"index.php/contabilidad/costos/rpt_costomateriales/";
        if(tipoexport=='excel'){
            
            $("#tipoexport").val('excel');
            $('#cadenaot').val();
            $("#frmBusqueda").attr("action",url);        
            $("#frmBusqueda").attr("target","_parent");  
            $("#frmBusqueda").submit();
            $("#tipoexport").val('');   
        }
        else{
            $("#idcontenido").hide();
            $("#iddetalle").show();
            $('#iddetalle').html('<div style="hegiht:50px;"><img src="'+base_url+'img/loading.gif"/></div>');
            dataString = $("#frmBusqueda").serialize();
            $.post(url,dataString,function(data){
                $("#iddetalle").fadeIn(1000).html(data);
            });        
        }   
    //}
    //else{
    //    alert ('Partida Materiales ::: Debe seleccionar el mismo mes para imprimir el detalle !!!');
    //}
}

function rpt_nomenclatura(obj){
    codot  = $(obj).parent().parent().attr('id');
    tipoexport = $(obj).attr('id'); 
    ini   = $("#fecha_ini").val();
    fin   = $("#fecha_fin").val();          
    $("#excel.rpt_costoot").css("display","none");
    $("#codot").val(codot);
    url = base_url+"index.php/siddex/nomenclatura/lista_material/";
    if(tipoexport=='excel'){
        url = base_url +'index.php/siddex/nomenclatura/export_excel/listar_nomenclatura';
        window.open(url, this.target, 'width=600,height=250,top=150,left=200');
    }
    else{
        $("#idcontenido").hide();
        $("#iddetalle").show();
        $('#iddetalle').html('<div style="hegiht:50px;"><img src="'+base_url+'img/loading.gif"/></div>');
        dataString = $("#frmBusqueda").serialize();
        $.post(url,dataString,function(data){
            $("#iddetalle").fadeIn(1000).html(data);
        });        
    }   
}

function rpt_servicios(obj){
    codot  = $(obj).parent().parent().attr('id');      
    tipoexport = $(obj).attr('id');     
    $("#excel.rpt_costoot").css("display","none");  
    $("#codot").val(codot);
    url = base_url+"index.php/contabilidad/costos/rpt_servicios/";
    if(tipoexport=='excel'){
        $("#tipoexport").val('excel');
        $('#cadenaot').val();
        $("#frmBusqueda").attr("action",url);        
        $("#frmBusqueda").attr("target","_parent");        
        $("#frmBusqueda").submit();    
        $("#tipoexport").val('');
        url = base_url +'index.php/siddex/nomenclatura/export_excel/listar_nomenclatura';
        window.open(url, this.target, 'width=600,height=250,top=150,left=200');        
    }
    else{   
        $("#idcontenido").hide();
        $("#iddetalle").show();          
        $('#iddetalle').html('<div style="hegiht:50px;"><img src="'+base_url+'img/loading.gif"/></div>');
        dataString = $("#frmBusqueda").serialize();
        $.post(url,dataString,function(data){
            $("#iddetalle").fadeIn(1000).html(data);
        });     
    }
}

//function rpt_serviciost(obj){
//    codot  = $(obj).parent().parent().attr('id');
//    tipoexport = $(obj).attr('id'); 
////    ini   = $("#fecha_ini").val();
////    fin   = $("#fecha_fin").val();
////    a     = new Date(ini);
////    b     = new Date(fin);
////    if(a.getDate()==b.getDate()) 
////    {        
//        $("#excel.rpt_costoot").css("display","none");
//        $("#codot").val(codot);
//        url = base_url+"index.php/contabilidad/costos/rpt_servicios/";
//        if(tipoexport=='excel'){
//            $("#tipoexport").val('excel');
//            $('#cadenaot').val();
//            $("#frmBusqueda").attr("action",url);        
//            $("#frmBusqueda").attr("target","_parent");        
//            $("#frmBusqueda").submit();    
//            $("#tipoexport").val('');
//        }
//        else{   
//            $("#idcontenido").hide();
//            $("#iddetalle").show();          
//            $('#iddetalle').html('<div style="hegiht:50px;"><img src="'+base_url+'img/loading.gif"/></div>');
//            dataString = $("#frmBusqueda").serialize();
//            $.post(url,dataString,function(data){
//                $("#iddetalle").fadeIn(1000).html(data);
//            });     
//        }   
////    }
////    else{
////        alert ('Partida Servicios ::: Debe seleccionar el mismo mes para imprimir el detalle !!!');
////    }
//}

function rpt_tesoreria(obj){
    codot      = $(obj).parent().parent().attr('id');
    tipoexport = $(obj).attr('id');
    codpartida = $(obj).parent().attr('id');
    $("#excel.rpt_costoot").css("display","none");
    $("#codot").val(codot);
    $("#codpartida").val(codpartida);
    url = base_url+"index.php/contabilidad/costos/rpt_tesoreria/";
    //alert(url);
    if(tipoexport=='excel'){
        $("#tipoexport").val('excel');
        $('#cadenaot').val();
        $("#frmBusqueda").attr("action",url);        
        $("#frmBusqueda").attr("target","_parent");        
        $("#frmBusqueda").submit(); 
          $("#tipoexport").val('');
    }
    else{
        $("#idcontenido").hide();
        $("#iddetalle").show();          
        $('#iddetalle').html('<div style="hegiht:50px;"><img src="'+base_url+'img/loading.gif"/></div>');
        dataString = $("#frmBusqueda").serialize();
        $.post(url,dataString,function(data){
            $("#iddetalle").fadeIn(1000).html(data);
        });         
    }
}

function rpt_tesoreriat(obj){
    codot  = $(obj).parent().parent().attr('id');
    tipoexport = $(obj).attr('id'); 
    ini   = $("#fecha_ini").val();
    fin   = $("#fecha_fin").val();
    a=new Date(ini);
    b=new Date(fin);
    if(a.getDate()==b.getDate())
        {
    $("#excel.rpt_costoot").css("display","none");
    $("#codot").val(codot);
    $("#codpartida").val(codpartida);
   url = base_url+"index.php/contabilidad/costos/rpt_tesoreria/";
    //alert(url);
    if(tipoexport=='excel'){
        $("#tipoexport").val('excel');
        $('#cadenaot').val();
        $("#frmBusqueda").attr("action",url);        
        $("#frmBusqueda").attr("target","_parent");        
        $("#frmBusqueda").submit(); 
          $("#tipoexport").val('');
    }
    else{
        $("#idcontenido").hide();
        $("#iddetalle").show();          
        $('#iddetalle').html('<div style="hegiht:50px;"><img src="'+base_url+'img/loading.gif"/></div>');
        dataString = $("#frmBusqueda").serialize();
        $.post(url,dataString,function(data){
            $("#iddetalle").fadeIn(1000).html(data);
        });         
    }
            
        }
    else
    {
        alert ('Partida Tesoreria ::: Debe seleccionar el mismo mes para imprimir el detalle !!!');
    }
}

function rpt_manoobra(obj){
    codot  = $(obj).parent().parent().attr('id');
    tipoexport = $(obj).attr('id');    
    $("#excel.rpt_costoot").css("display","none");
    $("#codot").val(codot);
    url = base_url+"index.php/contabilidad/costos/rpt_costomanoobra/";
    if(tipoexport=='excel'){
//        $("#tipoexport").val('excel');
//        $('#cadenaot').val();
//        $("#frmBusqueda").attr("action",url);        
//        $("#frmBusqueda").attr("target","_parent");        
//        $("#frmBusqueda").submit();  
//          $("#tipoexport").val('');
        url = base_url +'index.php/contabilidad/costos/export_excel/listar_costomanoobra';
        window.open(url, this.target, 'width=600,height=250,top=150,left=200');          
    }
    else{
        $("#idcontenido").hide();
        $("#iddetalle").show();        
        $('#iddetalle').html('<div style="hegiht:50px;"><img src="'+base_url+'img/loading.gif"/></div>');
        dataString = $("#frmBusqueda").serialize();
        $.post(url,dataString,function(data){
            $("#iddetalle").fadeIn(1000).html(data);
           
        });         
    }
}

function rpt_manoobrasiddex(obj){
    codot  = $(obj).parent().parent().attr('id');
    tipoexport = $(obj).attr('id');    
    $("#excel.rpt_costoot").css("display","none");
    $("#codot").val(codot);
    url = base_url+"index.php/contabilidad/costos/rpt_costomanoobrasiddex/";
    if(tipoexport=='excel'){
        $("#tipoexport").val('excel');
        $('#cadenaot').val();
        $("#frmBusqueda").attr("action",url);        
        $("#frmBusqueda").attr("target","_parent");        
        $("#frmBusqueda").submit();  
          $("#tipoexport").val('');
    }
    else{
        $("#idcontenido").hide();
        $("#iddetalle").show();        
        $('#iddetalle').html('<div style="hegiht:50px;"><img src="'+base_url+'img/loading.gif"/></div>');
        dataString = $("#frmBusqueda").serialize();
        $.post(url,dataString,function(data){
            $("#iddetalle").fadeIn(1000).html(data);
           
        });         
    } 
}

function rpt_manoobrat(obj){
    codot  = $(obj).parent().parent().attr('id');
    tipoexport = $(obj).attr('id'); 
  
    ini   = $("#fecha_ini").val();
    fin   = $("#fecha_fin").val();
    
    a=new Date(ini);
    b=new Date(fin);
    
    //if(a.getDate()==b.getDate())
	if(true)
        
        {
            
    $("#excel.rpt_costoot").css("display","none");
    $("#codot").val(codot);
   url = base_url+"index.php/contabilidad/costos/rpt_costomanoobra/";
    if(tipoexport=='excel'){
        $("#tipoexport").val('excel');
        $('#cadenaot').val();
        $("#frmBusqueda").attr("action",url);        
        $("#frmBusqueda").attr("target","_parent");        
        $("#frmBusqueda").submit();  
          $("#tipoexport").val('');
    }
    else{
        $("#idcontenido").hide();
        $("#iddetalle").show();        
        $('#iddetalle').html('<div style="hegiht:50px;"><img src="'+base_url+'img/loading.gif"/></div>');
        dataString = $("#frmBusqueda").serialize();
        $.post(url,dataString,function(data){
            $("#iddetalle").fadeIn(1000).html(data);
           
        });         
    }  
        }

    else
        {
            alert ('Partida Mano de Obra ::: Debe seleccionar el mismo mes para imprimir el detalle !!!');
        }
}



function rpt_transportes(obj){
    codot  = $(obj).parent().parent().attr('id');      
    tipoexport = $(obj).attr('id'); 
    $("#excel.rpt_costoot").css("display","none");
    $("#codot").val(codot);
    url = base_url+"index.php/contabilidad/costos/rpt_servicios/T";
   if(tipoexport=='excel'){

        $("#tipoexport").val('excel');
        $('#cadenaot').val();
        $("#frmBusqueda").attr("action",url);        
        $("#frmBusqueda").attr("target","_parent");  
        //throw new Error("Error intentionally created to halt process. Not an actual error.");
        $("#frmBusqueda").submit();   
        $("#tipoexport").val('');
    }
    else{
        $("#idcontenido").hide();
        $("#iddetalle").show();           
        $('#iddetalle').html('<div style="hegiht:50px;"><img src="'+base_url+'img/loading.gif"/></div>');
        dataString = $("#frmBusqueda").serialize();
        $.post(url,dataString,function(data){
            $("#iddetalle").fadeIn(1000).html(data);
        });        
    } 
}

function rpt_transportest(obj){
    codot  = $(obj).parent().parent().attr('id');      
    tipoexport = $(obj).attr('id'); 
    ini   = $("#fecha_ini").val();
    fin   = $("#fecha_fin").val();
    a=new Date(ini);
    b=new Date(fin);
    if(a.getDate()==b.getDate())
    {
        $("#excel.rpt_costoot").css("display","none");
        $("#codot").val(codot);
        url = base_url+"index.php/contabilidad/costos/rpt_servicios/T";
        if(tipoexport=='excel'){
            $("#tipoexport").val('excel');
            $('#cadenaot').val();
            $("#frmBusqueda").attr("action",url);        
            $("#frmBusqueda").attr("target","_parent");        
            $("#frmBusqueda").submit();   
              $("#tipoexport").val('');
        }
        else{
            $("#idcontenido").hide();
            $("#iddetalle").show();           
            $('#iddetalle').html('<div style="hegiht:50px;"><img src="'+base_url+'img/loading.gif"/></div>');
            dataString = $("#frmBusqueda").serialize();
            $.post(url,dataString,function(data){
                $("#iddetalle").fadeIn(1000).html(data);
            });        
        }        
    }
    else
    {
        alert ('Partida Transporte ::: Debe seleccionar el mismo mes para imprimir el detalle !!!');
    }
}



function rpt_caja(obj){
    codot  = $(obj).parent().parent().attr('id');
    tipoexport = $(obj).attr('id'); 
    $("#excel.rpt_costoot").css("display","none");
    $("#codot").val(codot);
    url = base_url+"index.php/contabilidad/costos/rpt_caja/";
    if(tipoexport=='excel'){
        $("#tipoexport").val('excel');
        $('#cadenaot').val();
        $("#frmBusqueda").attr("action",url);        
        $("#frmBusqueda").attr("target","_parent");        
        $("#frmBusqueda").submit(); 
          $("#tipoexport").val('');
    }
    
            
    else{
        $("#idcontenido").hide();
        $("#iddetalle").show();          
        $('#iddetalle').html('<div style="hegiht:50px;"><img src="'+base_url+'img/loading.gif"/></div>');
        dataString = $("#frmBusqueda").serialize();
        $.post(url,dataString,function(data){
            $("#iddetalle").fadeIn(1000).html(data);
        });         
    }
}


function rpt_cajat(obj){
    codot  = $(obj).parent().parent().attr('id');
    tipoexport = $(obj).attr('id'); 
    ini   = $("#fecha_ini").val();
    fin   = $("#fecha_fin").val();
    a=new Date(ini);
    b=new Date(fin);
//    if(a.getDate()==b.getDate())
//        {   
    $("#excel.rpt_costoot").css("display","none");
    $("#codot").val(codot);
    url = base_url+"index.php/contabilidad/costos/rpt_caja/";
    
    if(tipoexport=='excel'){
        $("#tipoexport").val('excel');
        $('#cadenaot').val();
        $("#frmBusqueda").attr("action",url);        
        $("#frmBusqueda").attr("target","_parent");        
        $("#frmBusqueda").submit(); 
          $("#tipoexport").val('');
    }
        
    else{
        $("#idcontenido").hide();
        $("#iddetalle").show();          
        $('#iddetalle').html('<div style="hegiht:50px;"><img src="'+base_url+'img/loading.gif"/></div>');
        dataString = $("#frmBusqueda").serialize();
        $.post(url,dataString,function(data){
            $("#iddetalle").fadeIn(1000).html(data);
        });         
    }
            
        //}

//    else
//        {
//            alert ('Partida Caja ::: Debe seleccionar el mismo mes para imprimir el detalle !!!');
//        }
 
  

}