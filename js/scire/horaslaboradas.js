jQuery(document).ready(function(){
    $("#html.control").click(function() {
        $("#tipo").val('html');
//        $("#tipoexcel").val('1');
        $("#frmHorasLaboradas").attr("target", "_parent");
        $("#frmHorasLaboradas").submit();
    });
    
    $("#excel.obr_noplanilla").click(function() {
        $("#tipo").val('html');
        $("#tipoexcel").val('1');
        $("#frmPlanilla").attr("target", "_parent");
        $("#frmPlanilla").submit();
        $("#tipoexcel").val('');
    });    
    
    $("#salir").click(function(){
        window.close();
    });    
})

