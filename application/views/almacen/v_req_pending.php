<!DOCTYPE html>
<html>
<head>
    <title><?php echo titulo;?></title>   
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Content-Language" content="es"> 
    <link rel="stylesheet" href="<?php echo css;?>estilos.css" type="text/css">
    <link rel="stylesheet" href="<?php echo css;?>nav.css" type="text/css">
    <link rel="stylesheet" href="<?php echo css;?>theme.css" type="text/css">
    <link rel="stylesheet" href="<?php echo css;?>calendario/calendar-win2k-2.css" type="text/css" media="all" title="win2k-cold-1"/>	
    <link rel="stylesheet" href="<?php echo css;?>basic.css" type="text/css">
    
    
    
    <!--link rel="stylesheet" href="<?php echo css;?>jquery.pnotify.default.css" type="text/css"></link-->

    <script type="text/javascript" src="<?php echo js;?>constants.js"></script> 
    <script type="text/javascript" src="<?php echo js;?>calendario/calendar.js"></script>
    <script type="text/javascript" src="<?php echo js;?>calendario/calendar-es.js"></script>
    <script type="text/javascript" src="<?php echo js;?>calendario/calendar-setup.js"></script>
    <script type="text/javascript" src="<?php echo js;?>jquery.js"></script>
    <script type="text/javascript" src="<?php echo js;?>almacen/producto.js"></script>
    <script type="text/javascript" src="<?php echo js;?>contabilidad/costos.js"></script>
    <!--script type="text/javascript" src="<?php echo js;?>jquery.pnotify.min.js"></script-->
    <script type="text/javascript" src="<?php echo js;?>blockui.js"></script>
    <script>
        window.onunload=function(){window.opener.unloadPage();}
        function blockui(){
            $.blockUI({ 
                message: 'Espere un momento por favor.',
                css: { 
                    border: 'none',
                   font: '20px',
                    padding: '15px', 
                    backgroundColor: '#000', 
                    '-webkit-border-radius': '10px', 
                    '-moz-border-radius': '10px', 
                    opacity: .5, 
                    color: '#fff' 
                } 
            }); 
        }
        
        function doc_export(type,form){
            $('#export_type').val(type);
            $.blockUI({ 
                message: 'Espere un momento por favor mientras carga el documento.',
                css: { 
                   border: 'none',
                   font: '20px',
                    padding: '15px', 
                    backgroundColor: '#000', 
                    '-webkit-border-radius': '10px', 
                    '-moz-border-radius': '10px', 
                    opacity: .5, 
                    color: '#fff' 
                } 
            });
            $('form#'+form).submit();
            setTimeout($.unblockUI, 15000); 
        }
        
        function all_checked(obj,req_number){
            if ($(obj).is(':checked')) {
                $('#table_'+req_number+' input[type=checkbox]').each(function() {
                   $(this).attr("checked","checked");
                });
            }else{
                $('#table_'+req_number+' input[type=checkbox]').each(function() {
                   $(this).removeAttr("checked");
                });
            }
        }

        function select_checkbox(req_code){
            if ($("#checkbox_"+req_code).is(':checked')){
                $("#checkbox_"+req_code).removeAttr("checked");
            }else{
                $("#checkbox_"+req_code).attr("checked","checked");

            }
        }
    </script>
</head>

<body>
    <?
        $var_entidad = $this->session->userdata('entidad');
    ?>
    
    
    <? echo form_open('almacen/producto/req_pending',array('id' => 'frm_req')); ?>
    <div style="left:0;background-color: rgb(240,240,240); text-align:right; border:1px solid rgb(190,190,190); padding: 10px; width:100%;position: fixed; margin: -1px 0 0 -10px;">
        <div style="float:left; margin-left: 10px; color:rgb(150,150,150)">
            <h3>Requisiciones Pendientes</h3>
        </div>
        <input type="hidden" name="export_type" id="export_type" ></input>
        <img onclick ="javascript:doc_export('excel','frm_req')" style="margin-right: 5px; cursor:pointer" src="<? echo base_url(); ?>img/excel.gif"></img>
        
        <input  onclick="javascript:blockui()" style="border:1px solid; width: 80px; height: 30px; cursor: pointer; margin-right: 19px;" type="submit" value="Anular"></input>
    </div>
     <br/><br/><br/><br/><br/>    
    <? foreach($arr_req as $req_code => $req_data){ ?>
    <style>
        .form_table td{
            border : none;
            padding:0px;
        }
    </style>
    <?
        foreach ($req_data as $key => $value) {
            $req_date = date("d-m-Y",strtotime($value->req_date));
            $req_department = $value->req_department;
            $req_number = $value->req_number;
            $req_serie = $value->req_serie;
            break 1;
        }
    ?>   
    <table id="table_<? echo $req_number; ?>" style="border:1px solid" border="1" cellspacing="0" width="100%">
        <tr>
            <td style="border:none" colspan="4">
                <table class="form_table" border="0" width="100%" style="margin:0; text-align: left;  border:0px">
                    <tr>
                        <td width="5%" rowspan="3">
                            <input onclick="all_checked(this,'<? echo $req_number; ?>')" style="cursor:pointer" type="checkbox"></input></td>
                        <td width ="12%" style="font-weight: bold;">Requisici&oacute;n</td>
                        <td>: <? echo $req_serie." - ".$req_number; ?></td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold;">Fecha</td>
                        <td>: <? echo $req_date; ?></td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold;">Departamento</td>
                        <td>: <? echo $req_department; ?></td>
                    </tr>
                </table>
            </td>
            <td style ="background-color: rgb(83,142,213); padding:3px; font-weight: bold; color:white;">Solicitado</td>
            <td style ="background-color: rgb(83,142,213); padding:3px; font-weight: bold; color:white;">Atendido</td>
            <td style ="background-color: rgb(83,142,213); padding:3px; font-weight: bold; color:white;">Pendiente</td>
            <td style ="background-color: rgb(83,142,213); padding:3px; font-weight: bold; color:white;">OC</td>
        </tr>  
        
        <? 
        
            foreach($req_data as $req_code => $req_products){ ?>
            <tr style="cursor:pointer">
                <td><input id="checkbox_<? echo $req_code; ?>" style="cursor:pointer" type="checkbox" name="chk_products[]" value="<? echo $req_code; ?>"></input></td>
                <td onclick="select_checkbox(<? echo ($var_entidad=='01')?$req_code:"'".$req_code."'"; ?>)" style="text-align: center;"><? echo $req_products->req_prd_code; ?></td>
                <td onclick="select_checkbox(<? echo ($var_entidad=='01')?$req_code:"'".$req_code."'"; ?>)" style="text-align: center;"><? echo $req_products->req_ot_number; ?></td>
                <td onclick="select_checkbox(<? echo ($var_entidad=='01')?$req_code:"'".$req_code."'"; ?>)" width="75%" style="text-align: left"><? echo utf8_encode($req_products->req_prd_description); ?></td>
                <td onclick="select_checkbox(<? echo ($var_entidad=='01')?$req_code:"'".$req_code."'"; ?>)" width="5%" style="text-align: center;"><? echo $req_products->req_qty; ?></td>
                <td onclick="select_checkbox(<? echo ($var_entidad=='01')?$req_code:"'".$req_code."'"; ?>)" width="5%" style="text-align: center;"><? echo $req_products->req_qty_s; ?></td>
                <td onclick="select_checkbox(<? echo ($var_entidad=='01')?$req_code:"'".$req_code."'"; ?>)" width="5%" style="text-align: center;"><? echo $req_products->req_saldo; ?></td>
                <td onclick="select_checkbox(<? echo ($var_entidad=='01')?$req_code:"'".$req_code."'"; ?>)" width="5%" style="text-align: center;"><? echo $req_products->qty_oc; ?></td>
            </tr>  
           <? }?>
        </table><br/><hr><br/>
        <? } ?>
        
        
<? echo form_close(); ?>
    
          
</body>
</html>
