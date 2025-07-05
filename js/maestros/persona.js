jQuery(document).ready(function(){
    $("#nuevo").click(function(){
        dataString = "";
        url = base_url+"index.php/maestros/persona/editar/n";
        $.post(url,dataString,function(data){
            $('#basic-modal-content').modal();
            $('#mensaje').html(data);
        });              
    });
    
    $("#grabar").click(function(){
        url = base_url+"index.php/maestros/persona/grabar";
        dataString  = $('#frmPersona').serialize();
        $.post(url,dataString,function(data){
            alert('Operacion realizada con exito');
            location.href = base_url+"index.php/maestros/persona/listar";
        });
    });     
    
    $("#cancelar").click(function(){
        url = base_url+"index.php/maestros/persona/listar";
        location.href = url;
    });
    
    $("#buscar").click(function(){
        $("#form_busqueda").submit();
    });
    
    $("#imprimir").click(function(){
        codigo   = $("#codigo").val();
        url = base_url+"index.php/maestros/persona/ver/"+codigo;
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
    url = base_url+"index.php/maestros/persona/editar/e/"+codigo;
    $.post(url,dataString,function(data){
        $('#basic-modal-content').modal();
        $('#mensaje').html(data);
    });     
}

function eliminar(codigo){
    if(confirm('Esta seguro desea eliminar este persona?')){
        dataString = "codigo="+codigo;
        url = base_url+"index.php/maestros/persona/eliminar";
        $.post(url,dataString,function(data){
            url = base_url+"index.php/maestros/persona/listar";
            location.href = url;
        });
    }
}

//function ver(codigo){
//    url = base_url+"index.php/maestros/persona/ver/"+codigo;
//    $("#zonaContenido").load(url);
//}

function abrir_formulario_ubigeo(){
	ubigeo = $("#cboNacimiento").val();
	url = base_url+"index.php/maestros/ubigeo/formulario_ubigeo/"+ubigeo;
	window.open(url,'Formulario Ubigeo','menubar=no,resizable=no,width=200,height=180');
}



