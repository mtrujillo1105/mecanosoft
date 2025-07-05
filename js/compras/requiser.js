jQuery(document).ready(function(){
    $("#nuevo.ot_listar").click(function(){
	location.href = base_url+"index.php/ventas/ot/nuevo";
    });	
    $("#editar").click(function(){
	location.href = base_url+"index.php/ventas/ot/editar";
    });	
    $("#buscar").click(function(){
	$("#frmBusqueda").submit();
    });	
    $("#limpiar").click(function(){
        url = base_url+"index.php/ventas/ot/listar";
        location.href=url;
    });
    $("#grabar").click(function(){
        url = base_url+"index.php/ventas/ot/grabar";
        location.href = url;
    });
    $("#cancelar").click(function(){
        url = base_url+"index.php/ventas/ot/listar";
        location.href = url;
    });    	    
    $("#salir.ot_listar").click(function(){
        window.close();
    }); 

    /*Reporte requisiciones de servicio por OT*/
    $("#html.requiser_x_ot").click(function(){
      $("#tipo").val('html');
      $("#opcion").val('C');     
      $('#frmBusqueda').attr('target','_self');
      $('#frmBusqueda').attr('action','');
      $('#tipoexport').val('');
      $("#frmBusqueda").submit();  
    });	 
    	     
    $("#pdf.requiser_x_ot").click(function(){
        $("#tipo").val('pdf');
        $("#opcion").val('C');
        $("#frmBusqueda").attr("target","_parent");        
        $("#frmBusqueda").submit();
    });	 
    $("#salir.requiser_x_ot").click(function(){
        window.close();
    });	 
    
    $("#excel.requiser_x_ot").click(function(){
        var_url = base_url +'index.php/compras/requiser/export_excel/listar_requiser_x_ot';
        window.open(var_url, this.target, 'width=600,height=250,top=150,left=200');  
    });      

});