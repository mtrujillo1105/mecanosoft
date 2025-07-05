jQuery(document).ready(function() {
    $("#excel.detalle").click(function(){
        $("#tipoexcel").val('det');
//        $("#frmPlanilla").attr("target", "_parent");
//        $("#frmPlanilla").submit();
        location.href = base_url + "index.php/scire/scire/excel_gastos_area/";
        $("#tipoexcel").val('');
    });
});