var base_url;
jQuery(document).ready(function(){
    base_url   = $("#base_url").val();
    $("#nuevoTipoCambio").click(function(){
        url = base_url+"index.php/maestros/tipocambio/nuevo";
        location.href = url;
    });
    $("#grabarTipoCambio").click(function(){
            //$("#frmTipoCambio").submit();
            var url = base_url+"index.php/maestros/tipocambio/grabar";
            var dataString  = $('#frmTipoCambio').serialize();
            $.post(url,dataString,function(data){
                    //url = base_url+"index.php/index/inicio";
                    $('#close').click(); 
            });
	}); 
    $("#limpiarTipoCambio").click(function(){
        url = base_url+"index.php/maestros/tipocambio/listar";
        location.href=url;
    });
    $("#cancelarTipoCambio").click(function(){
        $('#close').click(); 
    });
    $("#buscarTipoCambio").click(function(){
        $("#form_busquedaTipoCambio").submit();
    });
});
function ver_tipocambio(tipocambio){
    location.href = base_url+"index.php/maestros/tipocambio/ver/"+tipocambio;
}
function atras_tipocambio(){
    location.href = base_url+"index.php/maestros/tipocambio/listar";
}