jQuery(document).ready(function(){
    $("#nuevo").click(function(){
        dataString = "";
        url = base_url+"index.php/almacen/almacen/editar/n";
        $.post(url,dataString,function(data){
            $('#basic-modal-content').modal();
            $('#mensaje').html(data);
        });              
    });
    
    $("#grabar").click(function(){
        url = base_url+"index.php/almacen/almacen/grabar";
        dataString  = $('#frmAlmacen').serialize();
        $.post(url,dataString,function(data){
            alert('Operacion realizada con exito');
            location.href = base_url+"index.php/almacen/almacen/listar";
        });  
    });
    
    $("#cancelar").click(function(){
        url = base_url+"index.php/almacen/almacen/listar";
        location.href = url;
    });
    
    $("#buscar").click(function(){
        $("#frmAlmacen").submit();
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
});

function editar(codigo){
    dataString = "codigo="+codigo;    
    url = base_url+"index.php/almacen/almacen/editar/e/"+codigo;
    $.post(url,dataString,function(data){
        $('#basic-modal-content').modal();
        $('#mensaje').html(data);
    });         
}

function eliminar(codigo){
    if(confirm('Esta seguro desea eliminar este almacen?')){
        dataString = "codigo="+codigo;
        url = base_url+"index.php/almacen/almacen/eliminar";
        $.post(url,dataString,function(data){
            url = base_url+"index.php/almacen/almacen/listar";
            location.href = url;
        });
    }
}

function ver(unidadmedida){
    location.href = base_url+"index.php/almacen/almacen/ver/"+unidadmedida;
}

function atras(){
    location.href = base_url+"index.php/almacen/almacen/listar";
}