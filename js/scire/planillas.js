jQuery(document).ready(function() {
    $(".export_bank").click(function() {
        var_bank = $(this).attr('bank');
        var_url = base_url +'index.php/scire/scire/export_bank/'+var_bank;
        window.open(var_url, this.target, 'width=700,height=250,top=150,left=200');
    });
        
    $("#html").click(function() {
        $("#tipo").val('html');
        $("#frmPlanilla").attr("target", "_parent");
        $("#frmPlanilla").submit();
    });

    $("#salir").click(function(){
        window.close();
    });
        
        $("#excel.scire_costos_resumen").click(function() {
            var_url = base_url +'index.php/scire/scire/export_excel/'+$("#txt_report").val();
            window.open(var_url, this.target, 'width=600,height=250,top=150,left=200');
        });
        
        $("#excel.planillas_periodo").click(function() {
            var_url = base_url +'index.php/scire/scire/export_excel/'+$("#txt_report").val();
            window.open(var_url, this.target, 'width=600,height=250,top=150,left=200');

        });
        
        $("#excel.scire_costos_area").click(function() {
            //location.href = base_url +'index.php/scire/scire/export_excel/scire_costos_area';
            var_url = base_url +'index.php/scire/scire/export_excel/scire_costos_area';
            window.open(var_url, this.target, 'width=600,height=250,top=150,left=200');
           
            $("#tipoexcel").val('');
        });
        
        $("#excel.detalle_area").click(function() {
            $("#tipoexcel").val('det');
            //location.href = base_url + "index.php/scire/scire/excel_gastos_area_det/";
            var_url = base_url +'index.php/scire/scire/excel_gastos_area_det/';
            window.open(var_url, this.target, 'width=600,height=250,top=150,left=200');
            
            $("#tipoexcel").val('');
        });
        
        $("#excel.detalle").click(function() {
            $("#tipoexcel").val('det_concepto');
            location.href = base_url + "index.php/scire/scire/excel_gastos_concepto_det/";
            $("#tipoexcel").val('');
        });
        
        $("#excel.consolidado_area").click(function() {
            $("#tipoexcel").val('con');

            var_url = base_url +'index.php/scire/scire/excel_gastos_area_con/';
            window.open(var_url, this.target, 'width=300,height=400');
            
            $("#tipoexcel").val('');
        
        });
        
        $("#excel.consolidado_concepto").click(function() {
            var_url = base_url +'index.php/scire/scire/export_excel/pago_concepto_con';
            window.open(var_url, this.target, 'width=300,height=400');
        });
        
        $("#excel.detalle_concepto").click(function() {
            var_url = base_url +'index.php/scire/scire/export_excel/pago_concepto_det';
            window.open(var_url, this.target, 'width=300,height=400');
        });
        
        $("#excel.consolidado").click(function() {
            $("#tipoexcel").val('con_concepto');
            location.href = base_url + "index.php/scire/scire/excel_gastos_concepto_con/";
            $("#tipoexcel").val('');
        });
        
        $("#excel.obr_noplanilla").click(function() {
            
            
            $("#tipo").val('html');
            $("#tipoexcel").val('1');
            $("#frmPlanilla").attr("target", "_parent");
            $("#frmPlanilla").submit();
            $("#tipoexcel").val('');
            


        });
        
        $("#excel.obr_planilla").click(function() {
            $("#tipo").val('html');
            $("#tipoexcel").val('2');
            $("#frmPlanilla").attr("target", "_parent");
            $("#frmPlanilla").submit();
            $("#tipoexcel").val('');
        });
        
        $("#consolidado_planilla").click(function() {
            $("#tipo").val('html');
            $("#tipoexcel").val('2');
            $("#frmPlanilla").attr("target", "_parent");
            $("#frmPlanilla").submit();
            $("#tipoexcel").val('');
        });
        
        $("#excel.obr_detalle").click(function() {
            $("#tipo").val('html');
            $("#tipoexcel").val('3');
            $("#frmPlanilla").attr("target", "_parent");
            $("#frmPlanilla").submit();
            $("#tipoexcel").val('');
        });
        
        $("#excel.diferencia_planilla").click(function() {
            $("#tipo").val('html');
            $("#tipoexcel").val('9');
            $("#frmPlanilla").attr("target", "_parent");
            $("#frmPlanilla").submit();
            $("#tipoexcel").val('');
        });
        
        $("#btn_diferencia_planilla").click(function() {
            $("#tipo").val('html');
            $("#tipoexcel").val('4');
            $("#frmPlanilla").attr("target", "_parent");
            $("#frmPlanilla").submit();
            $("#tipoexcel").val('');
        });
        
        $("#excel.obr_sin_cuenta").click(function() {
            $("#tipo").val('html');
            $("#tipoexcel").val('5');
            $("#frmPlanilla").attr("target", "_parent");
            $("#frmPlanilla").submit();
            $("#tipoexcel").val('');
        });
        
        $("#subtotal_cheque").click(function() {
            $("#tipo").val('html');
            $("#tipoexcel").val('5');
            $("#frmPlanilla").attr("target", "_parent");
            $("#frmPlanilla").submit();
            $("#tipoexcel").val('');
        });
        
        $(".tab_content").hide(); //Hide all content
        $("ul.tabs li:first").addClass("active").show(); //Activate first tab
        $(".tab_content:first").show(); //Show first tab content
        //On Click Event
        $("ul.tabs li").click(function() {
            $("ul.tabs li").removeClass("active"); //Remove any "active" class
            $(this).addClass("active"); //Add "active" class to selected tab
            $(".tab_content").hide(); //Hide all tab content
            var activeTab = $(this).find("a").attr("href"); //Find the rel attribute value to identify the active tab + content
        //$(this).find("a").remove(); //Find the rel attribute value to identify the active tab + content
            $(activeTab).fadeIn(); //Fade in the active content
        });
});
