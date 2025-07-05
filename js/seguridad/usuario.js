jQuery(document).ready(function(){
    $("#nuevo").click(function(){
        dataString = "";
        url = base_url+"index.php/seguridad/usuario/editar/n";
        $.post(url,dataString,function(data){
            $('#basic-modal-content').modal();
            $('#mensaje').html(data);
        });             
    });	
    
    $("#grabar").click(function(){
        url = base_url+"index.php/seguridad/usuario/grabar";
        dataString  = $('#frm1').serialize();
        $.post(url,dataString,function(data){
            alert('Operacion realizada con exito');
            location.href = base_url+"index.php/seguridad/usuario/listar";
        });          
    });    
    
    $("#limpiar").click(function(){
        url = base_url+"index.php/seguridad/usuario/listar";
        $("#nombre_unidadmedida").val('');
        $("#simbolo").val('');
        location.href=url;
    });
    
    $("#cancelar").click(function(){
        url = base_url+"index.php/seguridad/usuario/listar";
        location.href = url;
    });  
    
    $("#buscar").click(function(){
	$("#frmBusqueda").submit();
    });	    
    
    $("#salir").click(function(){
        window.close();
    });   
    
    $("#ver_usuario").click(function(){
        dataString = "";    
        url = base_url+"index.php/seguridad/usuario/listar/";
        $.post(url,dataString,function(data){
            $('#basic-modal-content').modal();
            $('#mensaje').html(data);
        }); 
    });        
});

function editar(codigo){
    dataString = "codigo="+codigo;    
    url = base_url+"index.php/seguridad/usuario/editar/e/"+codigo;
    $.post(url,dataString,function(data){
        $('#basic-modal-content').modal();
        $('#mensaje').html(data);
    }); 
}
function eliminar(codigo){
    if(confirm('Esta seguro desea eliminar este unidad de medida?')){
        dataString = "codigo="+codigo;
        url = base_url+"index.php/seguridad/usuario/eliminar";
        $.post(url,dataString,function(data){
            url = base_url+"index.php/seguridad/usuario/listar";
            location.href = url;
        });
    }
}