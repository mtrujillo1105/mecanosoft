jQuery(document).ready(function(){
    
    /*Reporte de Personal Asistencia*/
    $("#html.personal").click(function(){
        $("#tipoexport").val('');
        $("#frmBusqueda").attr("target","_parent");        
        $("#frmBusqueda").submit();
    });	 

    $("#excel.personal").click(function(){
        var tipo = $('input[name=tipodetalle]:checked', '#frmBusqueda').val();
        location.href = base_url+"index.php/scire/scire/asistencia_excel/"+tipo;
    });	   
    	    
    $("#pdf.personal").click(function(){
        $("#tipoexport").val('pdf');
        $("#frmBusqueda").attr("target","_blank");        
        $("#frmBusqueda").submit();
    });	 
    
    $("#otro.personal").click(function(){
        fInicio = $("#fInicio").val();
        str 	= new String(fInicio);
        dataString = $("#frmBusqueda").serialize();
        url  = base_url+"index.php/scire/scire/regulariza_asistencia?"+dataString;
        window.open(url,'','width=800px,height=600px,scrollbars=no');     
    });	 

    $("#salir.personal").click(function(){
        window.close();
    }); 
    
    /*Regulariza*/
    $("#html.regulariza").click(function(){
        $("#tipoexport").val('');
        $("#frmBusqueda").attr("target","_parent");        
        $("#frmBusqueda").submit();
    });	 
    
    $("#excel.regulariza").click(function(){
        var tipo = $('input[name=tipodetalle]:checked', '#frmBusqueda').val();
//        location.href = base_url+"index.php/scire/scire/asistencia_excel/"+tipo;
        location.href = base_url+"index.php/scire/scire/excel_regulariza_asistencia/";
    });	
    
    /*Asistencia*/
    $("#pdf.asistencia").click(function(){
        fFin = $("#fFin").val();
        url = base_url+"index.php?accion=rpt_asistencia&tipo=pdf&fFin="+fFin;
        window.open(url,'','width=650px,height=600px,scrollbars=no');
    });	 
    
    $("#excel.asistencia").click(function(){
        fFin = $("#fFin").val();
        url = base_url+"index.php?accion=rpt_asistencia&tipo=excel&fFin="+fFin;
        window.open(url,'','width=650px,height=600px,scrollbars=no');
    });	 
    
    $("#html.asistencia").click(function(){
        fFin = $("#fFin").val();
        url = base_url+"index.php?accion=rpt_asistencia&tipo=html&fFin="+fFin;
        location.href = url;
    });	
    
    /*Reporte de asistencia*/
    $("#pdf.asistencia").click(function(){
        fFin = $("#fFin").val();
        url = base_url+"index.php?accion=rpt_asistencia&tipo=pdf&fFin="+fFin;
        window.open(url,'','width=650px,height=600px,scrollbars=no');
    });	 
    
    $("#excel.asistencia").click(function(){
        fFin = $("#fFin").val();
        url = base_url+"index.php?accion=rpt_asistencia&tipo=excel&fFin="+fFin;
        window.open(url,'','width=650px,height=600px,scrollbars=no');
    });	 
    
    $("#html.asistencia").click(function(){
        fFin = $("#fFin").val();
        url = base_url+"index.php?accion=rpt_asistencia&tipo=html&fFin="+fFin;
        location.href = url;
    });	     
    
    $("#grafica.asistencia").click(function(){
        fFin = $("#fFin").val();
        url = base_url+"index.php?accion=rpt_asistencia_grafica&tipo=grafica&fFin="+fFin;
        window.open(url,'','width=650px,height=600px,scrollbars=no');
    });	 
    /*Reporte por facturar*/
    $("#html.xfacturar").click(function(){
        dataString = $("#frmFact").serialize();
        url = base_url+"index.php?accion=rpt_por_facturar_cliente&tipo=html&"+dataString;
        location.href = url;
    });	
    $("#pdf.xfacturar").click(function(){
        dataString = $("#frmFact").serialize();
        url = base_url+"index.php?accion=rpt_por_facturar_cliente&tipo=pdf&"+dataString;
        location.href = url;
    });
    $("#excel.xfacturar").click(function(){
        dataString = $("#frmFact").serialize();
        url = base_url+"index.php?accion=rpt_por_facturar_cliente&tipo=excel&"+dataString;
        location.href = url;
    });	     
    /*Reporte por facturar por intervalos*/
    $("#html.xfacturar_xintervalos").click(function(){
        fInicio = $("#fInicio").val();
        fFin    = $("#fFin").val();
        opcion  = $("#opcion").val();
        url = base_url+"index.php?accion=rpt_por_facturar_x_intervalos&tipo=html&fInicio="+fInicio+"&fFin="+fFin+"&opcion="+opcion;
        location.href = url;
    });	

   $("#pdf.xfacturar_xintervalos").click(function(){
        fFin = $("#fFin").val();
        url = base_url+"index.php?accion=rpt_por_facturar_x_intervalos&tipo=pdf&fFin="+fFin;
        window.open(url,'','width=650px,height=600px,scrollbars=no');
    });	 
    
    $("#excel.xfacturar_xintervalos").click(function(){
        fInicio = $("#fInicio").val();
        fFin = $("#fFin").val();
        url = base_url+"index.php?accion=rpt_por_facturar_x_intervalos&tipo=excel&fInicio="+fInicio+"&fFin="+fFin;
        window.open(url,'','width=650px,height=600px,scrollbars=no');
    });	   
    
    $("#grafica.xfacturar_xintervalos").click(function(){
        dataString = $("#frmFactxInterv").serialize();
        url = base_url+"index.php?accion=rpt_por_facturar_x_intervalos_grafica&"+dataString;
        window.open(url,'','width=750px,height=600px,scrollbars=yes');
    });	
    
   $("#dialog-btn").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio1").val(),
                    "ffin": $("#ffin1").val(),
                    "valorReal": $("#vreal1").val(),
                    "kpiCode": $("#kpicode1").val(),
                };
 
                if ($("#finicio1").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }
 
                if ($("#ffin1").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }
 
                $.ajax({
                    data: parametros,
                    url: base_url + '/index.php/indicadores/indicadores/recuperarIndicador',
                    type: 'post',
                    success: function(response) {
                        window.alert(response);
                        location.href = base_url + '/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });    
});

