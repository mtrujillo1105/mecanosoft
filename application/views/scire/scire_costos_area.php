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
    <link rel="stylesheet" href="<?php echo css;?>estilos.css" type="text/css">
    <script type="text/javascript" src="<?php echo js;?>constants.js"></script> 
    <script type="text/javascript" src="<?php echo js;?>calendario/calendar.js"></script>
    <script type="text/javascript" src="<?php echo js;?>calendario/calendar-es.js"></script>
    <script type="text/javascript" src="<?php echo js;?>calendario/calendar-setup.js"></script>
    <script type="text/javascript" src="<?php echo js;?>jquery.js"></script>
    <script type="text/javascript" src="<?php echo js;?>ventas/ot.js"></script>
    <script type="text/javascript" src="<?php echo js;?>scire/planillas.js"></script>
</head>


<body>
 <div class="header">REPORTE - COSTOS POR AREA - METALES / GALVANIZADO</div>	
    <div class="case_top2">
        <form method="post" id="frmBusqueda">
 
            <table width="100%" cellspacing="3" cellpadding="3"style="margin-top:5px;">
                <tbody style="">
                    <tr style="">
                        <td>A&Ntilde;O</td>
                        <td><? echo $cbo_anio; ?></td> 
                        <td>PERIODO</td>
                        <td><? echo $cbo_mes; ?></td>
                        <td>TIPO</td>
                        <td>
                            <? echo $checks; ?>
                        </td> 
                    </tr> 
                    <tr style="">
                        <td>CC</td>
                        <td><? echo $cbo_group; ?></td> 
                        <td>AREA</td>
                        <td><? echo $cbo_area; ?></td>
                        <td>COST. LAB.</td>
                        <td>
                           <?php echo @$var_carga_social; ?><div onclick='btn_clear()' style='float:right;width:150px;font-weight: bold;border:1px solid rgb(190,190,190);cursor:pointer;background-color: rgb(211,211,211)'>Quitar Filtros</div>
                        </td>
                    </tr> 
             
                </tbody>
            </table>
         
   
        </form>
    </div>
    <div class="case_botones" style="width:25%;border:0px solid #000;float: right;">
        <ul class="lista_botones"><li id="salir" class="xfacturar">Salir</li></ul>            
        <ul class="lista_botones excel"><li id="excel" class="scire_costos_area">Exportar</li></ul>
        <ul class="lista_botones"><li id="buscar" onclick="$('#frmBusqueda').submit()">Buscar</li></ul>  
    </div>
 
 <br/>
</body>
<?php
echo $body;
?>
<script>
    function btn_clear(){
        $("#cbo_area").val("");
        $("#cbo_group").val("");
        $("#chk_01").attr('checked', true);
        $("#chk_02").attr('checked', false); 
        $("#chk_04").attr('checked', true);  
        
        $("#buscar").click();
   
    }
 
        
    $(window).ready(function(){
       
          
            $(".excel").css("display","none");
            <?
            if($view_export>0){
               ?>$(".excel").css("display","block");<? 
            }else{
            ?>
                $("#chk_01").attr('checked', true);
                $("#chk_04").attr('checked', true);  
            <?    
            }
            ?>
	
	});
</script>
    
