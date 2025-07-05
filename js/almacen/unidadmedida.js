jQuery(document).ready(function(){
    $("#nuevo").click(function(){
        location.href = base_url+"index.php/almacen/unidadmedida/editar/n/";
    });      
    
    $("#grabar").click(function(){
        url = base_url+"index.php/almacen/unidadmedida/grabar";
        dataString  = $('#frmUnidadmedida').serialize();
        $.post(url,dataString,function(data){
            alert('Operacion realizada con exito');
            location.href = base_url+"index.php/almacen/unidadmedida/editar/e/"+data;
        });  
    });
    
    $("#cancelar").click(function(){
        url = base_url+"index.php/almacen/unidadmedida/listar";
        location.href = url;
    });
    
    $("#buscar").click(function(){
        dataString  = $('#frmUndSearch').serialize();
        url = base_url+"index.php/almacen/unidadmedida/listar/";
        $.post(url,dataString,function(data){
            $('#mensaje').html(data);
        });  
    });
    
    $("#imprimir").click(function(){
        codigo   = $("#codigo").val();
        url = base_url+"index.php/almacen/unidadmedida/ver/"+codigo;
        window.open(url, this.target, 'width=800,height=400,top=150,left=200');
    });    
    
    $("#cerrar").click(function(){
        url = base_url+"index.php/inicio/index";
        location.href = url;
    });       
    
    $("#salir").click(function(){
        window.close();
    });   
    
    $("#ver_unidad").click(function(){
        dataString = "";
        url = base_url+"index.php/almacen/unidadmedida/listar/";
        $.post(url,dataString,function(data){
            $('#basic-modal-content').modal();
            $('#mensaje').html(data);
        });           
    });    
    
    $(".itemTabla").click(function(){
        codigo = $(this).attr("id");      
        url = base_url+"index.php/almacen/unidadmedida/editar/e/"+codigo;
        location.href = url;
    });        
    
    $("#eliminar").click(function(){
        if(confirm('Esta seguro desea eliminar este unidad de medida?')){
            codigo     = $("#codigo").val();
            dataString = "codigo="+codigo;
            url = base_url+"index.php/almacen/unidadmedida/eliminar";
            $.post(url,dataString,function(data){
                url = base_url+"index.php/almacen/unidadmedida/editar/n";
                location.href = url;
            });
        }
    }); 
});
function editar(codigo){
    dataString = "codigo="+codigo;    
    url = base_url+"index.php/almacen/unidadmedida/editar/e/"+codigo;
    $.post(url,dataString,function(data){
        $('#basic-modal-content').modal();
        $('#mensaje').html(data);
    });         
}

function ver(unidadmedida){
    location.href = base_url+"index.php/almacen/unidadmedida/ver/"+unidadmedida;
}

function atras(){
    location.href = base_url+"index.php/almacen/unidadmedida/listar";
}