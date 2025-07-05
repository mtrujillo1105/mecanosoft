jQuery(document).ready(function(){
    $("#html").click(function() {
        $("#tipoexport").val('html');
        $("#frmPlanilla").attr("action",'');   
        $("#frmPlanilla").attr("target", "_parent");
        $("#frmPlanilla").submit();
    });

   $("#excel").click(function(){
        $("#tipoexport").val('excel');
        url = base_url+"index.php/scire/scire/export_excel/horastrabajadas";
        $("#frmPlanilla").attr("action",url);        
        $("#frmPlanilla").attr("target","_parent");
        $("#frmPlanilla").submit();
   });
   
   $("#salir").click(function(){
        window.close();
   });

   $("#periodo").change(function(){
        codperiodo = $(this).val();
        url = base_url+"index.php/scire/periodo/obtener/"+codperiodo;
        $.getJSON(url,function(data){
            fecha1  = data.fInicio;
            fecha2 = data.fFin;
            $("#fecha_ini").val(fecha1);
            $("#fecha_fin").val(fecha2);
        });
   });  
})
