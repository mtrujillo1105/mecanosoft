jQuery(document).ready(function(){
    $("#excel").click(function(){
      var_url = base_url +'index.php/ventas/ot/export_excel/productos_x_ot';
      window.open(var_url, this.target, 'width=600,height=250,top=150,left=200');  
    });
    
    $("#nuevo").click(function(){
	location.href = base_url+"index.php/ventas/orden/nuevo";
    });	
    
    $("#editar").click(function(){
	location.href = base_url+"index.php/ventas/orden/editar";
    });	
    
    $("#limpiar").click(function(){
        url = base_url+"index.php/ventas/orden/listar";
        location.href=url;
    });
    
    $("#grabar").click(function(){
        url = base_url+"index.php/ventas/orden/grabar";
        dataString  = $('#frmUnidadmedida').serialize();
        $.post(url,dataString,function(data){
            alert('Operacion realizada con exito');
            location.href = base_url+"index.php/ventas/orden/editar/e/"+data;
        });          
    });
    
    $("#cancelar").click(function(){
        url = base_url+"index.php/ventas/orden/listar";
        location.href = url;
    });   
    
    $("#salir").click(function(){
        window.close();
    }); 
     
    $("#ver_cliente").click(function(){
        dataString = "";    
        url = base_url+"index.php/ventas/cliente/listar/";
        $.post(url,dataString,function(data){
            $('#basic-modal-content').modal();
            $('#mensaje').html(data);
        }); 
    }); 
    
    $("#ver_orden").click(function(){
        dataString = "";    
        url = base_url+"index.php/ventas/orden/listar/";
        $.post(url,dataString,function(data){
            $.modal(data);
        }); 
    });  
});

$(document).on('click',"#buscar",function(){
    alert("Mala, mala");
});	

$(document).on('click',".itemTabla",function(){
    codot  = $(this).attr('id');
    $("#codigo").val(codot);
    $.modal.close();
});

//function seleccionar(codigo){
////    $('#simplemodal-container').hide();
////    $('#simplemodal-overlay').hide();
//    alert(codigo);
//    $.modal.close();
////    $("#numero").val(codigo);
////    url = base_url+"index.php/seguridad/usuario/editar/e/"+codigo;
////    $.post(url,dataString,function(data){
////        $('#basic-modal-content').modal();
////        $('#mensaje').html(data);
////    }); 
//}