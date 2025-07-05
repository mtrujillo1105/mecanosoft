jQuery(document).ready(function() {
    
    $("#excel_ctecorriente.control").click(function() {
        $("#tipo").val('html');
        $("#tipoexcel").val('1');
        $("#frmPlanilla").attr("target", "_parent");
        $("#frmPlanilla").submit();
        $("#tipoexcel").val('');
    });
     
    $("#html.control").click(function() {
        $("#tipo").val('html');
        $("#frmPlanilla").attr("target", "_parent");
        $("#frmPlanilla").submit();
        $("#tipoexcel").val('');
    });
    
});