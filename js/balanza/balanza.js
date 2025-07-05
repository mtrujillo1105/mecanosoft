var base_url;
jQuery(document).ready(function(){
    base_url  = "http://nazca/erik/";
    
    
    /*Reporte de balanza constancias*/
    $("#html.balanza").click(function(){
      //$("#tipoexport").val('html');
        $("#tipoexport").val('');
        $("#frmBusqueda").attr("target","_parent");        
        $("#frmBusqueda").submit();
    });	 

    $("#excel.balanza").click(function(){
        $("#tipoexport").val('excel');
        $("#frmBusqueda").attr("target","_parent");        
        $("#frmBusqueda").submit();
    });	   
    	    
    $("#pdf.balanza").click(function(){
        $("#tipoexport").val('pdf');
        $("#frmBusqueda").attr("target","_blank");        
        $("#frmBusqueda").submit();
    });	 

    $("#salir.balanza").click(function(){
        window.close();
    }); 
    
    
    
    
    
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
});

