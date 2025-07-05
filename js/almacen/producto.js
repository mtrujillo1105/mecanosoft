jQuery(document).ready(function(){
    $("#nuevo").click(function(){
        dataString = "";
        url = base_url+"index.php/almacen/producto/editar/n";
        $.post(url,dataString,function(data){
            $('#basic-modal-content').modal();
            $('#mensaje').html(data);
        });             
    });
   
    $("#buscar").click(function(){
	$("#frmBusqueda").submit();
    });	

    $("#limpiar").click(function(){
        url = base_url+"index.php/almacen/producto/listar";
        location.href=url;
    });
    $("#grabar").click(function(){
        url = base_url+"index.php/almacen/producto/grabar";
        dataString  = $('#form1').serialize();
        $.post(url,dataString,function(data){
            alert('Operacion realizada con exito');
            location.href = base_url+"index.php/almacen/producto/listar";
        });        
        
    });
    
    $("#cancelar").click(function(){
        url = base_url+"index.php/almacen/producto/listar";
        location.href = url;
    });
    
    $("#salir").click(function(){
        window.close();
    });  
    
    $("#ttab1").click(function(){
        url = base_url +'index.php/almacen/producto/listar';
        location.href = url;               
    });
    
    $("#ttab2").click(function(){
        url = base_url +'index.php/almacen/productoatributo/listar/';
        location.href = url; 
    });   
    
    $("#ttab3").click(function(){
        url = base_url +'index.php/almacen/productoatributodetalle/listar/';
        location.href = url; 
    });      
});

function ver_familia(){
    url           = base_url+"index.php/almacen/familia/nuevo";
    window.open(url,"_blank","width=500,height=400,scrollbars=yes,status=yes,resizable=yes,screenx=0,screeny=0");
}

function editar(codigo){
    dataString = "codigo="+codigo;    
    url = base_url+"index.php/almacen/producto/editar/e/"+codigo;
    $.post(url,dataString,function(data){
        $('#basic-modal-content').modal();
        $('#mensaje').html(data);
    }); 
}

function eliminar(codigo){
    if(confirm('Esta seguro desea eliminar este producto?')){
        dataString = "codigo="+codigo;
        url = base_url+"index.php/almacen/producto/eliminar";
        $.post(url,dataString,function(data){
            url = base_url+"index.php/almacen/producto/listar";
            location.href = url;
        });
    }
}
