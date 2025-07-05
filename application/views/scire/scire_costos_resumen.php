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
<div class="header"><? echo $txt_report; ?>REPORTE - RESUMEN DE COSTOS - METALES / GALVANIZADO</div>	
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
                    <td><? echo $checks; ?></td> 
                </tr>
                <tr style="">
                    <td>CC</td>
                    <td><? echo $cbo_group; ?></td>
                    <td>AREA</td>
                    <td><? echo $cbo_area; ?></td>
                    <td>COST. LAB</td>
                    <td style="font-weight:bold">
                        <?php echo $var_carga_social; ?>
                        <div onclick='btn_clear()' style='float:right;width:150px;font-weight: bold;border:1px solid rgb(190,190,190);cursor:pointer;background-color: rgb(211,211,211)'>Quitar Filtros</div>
                    </td>
                </tr>
            </tbody>
        </table>
        <input type="hidden" name="txt_report" id="txt_report" value='consolidado_cc'>
    </form>
     
</div>

<div style="text-align:left;float:left;margin-right:30px;">
PLANILLA : BASICO + ASIG. FAM. + DESCANSO SEM <br>
OTROS : VALOR OTROS + HORAS EXTRA + HORAS EXTRA DOBLE
</div>
<div class="case_botones" style="width:50%;border:0px solid #000;float: right;">
    
    <ul class="lista_botones"><li id="salir" class="xfacturar">Salir</li></ul>            
    <ul class="lista_botones excel"><li id="excel" class="scire_costos_resumen">Exportar</li></ul>
    <ul class="lista_botones"><li id="buscar" onclick="$('#frmBusqueda').submit()">Buscar</li></ul>  
</div>
 

<style>
    .val_shadow{
        font-weight:bold; background-color:rgb(242,242,242)
    }
    
    .val_subtotal{
        color:rgb(120,120,120);text-align:right; font-weight:bold;background-color:rgb(248,248,248)
    }
    
    .right{text-align:right;}
    .left{text-align:left;}
    .center{text-align:center;}
</style> 
 
<div class="div_fondo" style="margin-top:30px; ">
    
    <div class="case_middle">
        <ul class="tabs">
            <li id="consolidado_cc"><a href="#tab1">C. COSTO</a></li>
            <li id="consolidado_area"><a href="#tab2">AREA</a></li>
            <li id="consolidado_concepto"><a href="#tab3">CONCEPTO</a></li>
            <li id="consolidado_detalle"><a href="#tab4">DETALLE</a></li>
        </ul>
        
        <div class="tab_container">
            <div class="container">
                
                
               
                <div id="tab1" class="tab_content">
                    <div>
                        <div style="text-align: left">
                            <table width="100%" border="1">
                                <tr>
                                    <td class='val_shadow' colspan="12">CONSOLIDADO POR CENTRO DE COSTO</td>
                                </tr>
                                <tr>
                                    <td class='val_shadow center' rowspan='2'>CENTRO DE COSTOS</td>
                                    <td class='val_shadow center' colspan="4">EMPLEADOS</td>
                                    <td class='val_shadow center' colspan="4">OBREROS</td>
                                    <td class='val_shadow center' colspan="2">4TA</td>
                                    
                                    <td class='val_shadow center' rowspan="2">TOTAL</td>
                                    
                                </tr>
                                
                                <tr>
                                    
                                    
                                    <th width="8%" class='center'>PLANILLA</th>
                                    <th width="8%" class='center'>OTROS</th>
                                    <th width="8%" class='center'>CARGA SOCIAL</th>
                                    <th width="8%" class='val_shadow center'>TOTAL</th>
                                    
                                    <th width="8%" class='center'>PLANILLA</th>
                                    <th width="8%" class='center'>OTROS</th>
                                    <th width="8%" class='center'>CARGA SOCIAL</th>
                                    <th width="8%" class='val_shadow center'>TOTAL</th>
                                    
                                    <th width="8%" class='center'>PLANILLA</th>
                                    <th width="8%" class='val_shadow center'>TOTAL</th>
                                    
                                    
                                    
                                </tr>
                                            
                                <?php echo $body_cc; ?>
                                <!--img src='<? echo base_url(); ?>/img/load.gif'></img-->
                            </table>
                        </div>
                    </div>
                </div>
                
                
                
                
                
                <div id="tab2" class="tab_content">
                    <div>
                        <div style="text-align: left">
                            <table width="100%" border="1">
                                <tr>
                                    <td class='val_shadow' colspan="12">CONSOLIDADO POR AREA</td>
                                </tr>
                                <tr>
                                    <td class='val_shadow center' rowspan='2'>AREA</td>
                                    <td class='val_shadow center' colspan="4">EMPLEADOS</td>
                                    <td class='val_shadow center' colspan="4">OBREROS</td>
                                    <td class='val_shadow center' colspan="2">4TA</td>
                                    
                                    <td class='val_shadow center' rowspan="2">TOTAL</td>
                                    
                                </tr>
                                
                                <tr>
                                    
                                    
                                    <th width="8%" class='center'>PLANILLA</th>
                                    <th width="8%" class='center'>OTROS</th>
                                    <th width="8%" class='center'>CARGA SOCIAL</th>
                                    <th width="8%" class='val_shadow center'>TOTAL</th>
                                    
                                    <th width="8%" class='center'>PLANILLA</th>
                                    <th width="8%" class='center'>OTROS</th>
                                    <th width="8%" class='center'>CARGA SOCIAL</th>
                                    <th width="8%" class='val_shadow center'>TOTAL</th>
                                    
                                    <th width="8%" class='center'>PLANILLA</th>
                                    <th width="8%" class='val_shadow center'>TOTAL</th>
                                    
                                    
                                    
                                </tr>
                                            
                                <?php echo $body_area; ?>
                                <!--img src='<? echo base_url(); ?>/img/load.gif'></img-->
                            </table>
                           
                        </div>
                    </div>
                </div>
                
                <div id="tab3" class="tab_content">
                    <div>
                        <div style="text-align: left">
                            <table width="100%" border="1">
                                <tr>
                                    <td class='val_shadow' colspan="5">CONSOLIDADO POR CONCEPTO</td>
                                </tr>
                                <tr>
                                    <td class='val_shadow left' style="width:50%"></td>
                                    <td class='val_shadow left' style="width:30%"></td>
                                    <td class='val_shadow center' style="width:10%">DEBE</td>
                                    <td class='val_shadow center' style="width:10%">HABER</td>
                           
                                </tr>
                                


                                
                                <?php echo $body_concepto; ?>
                                <!--img src='<? echo base_url(); ?>/img/load.gif'></img-->
                            </table>
                           
                        </div>
                    </div>
                    
                </div>
                
                <div id="tab4" class="tab_content">
                    <div class="case_botones">
                        <table width="100%">
                            <tr>
                                <th colspan="5">&nbsp;</th>
                                <th colspan="10" class='val_shadow left'>REMUNERACIONES</th>
                              
                                
                            </tr>
                            <tr>
                                <th class="val_shadow center" style="width:5px;">Nro</th>
                                <th class="val_shadow center">Tipo</th>
                                <th class="val_shadow center">Personal</th>
                                <th class="val_shadow center">CC</th>
                                <th class="val_shadow center">AREA</th>
                                <th class="val_subtotal center">BASICO</th>
                                <th class="val_subtotal center">ASIG. FAM.</th>
                                <th class="val_subtotal center">OTROS</th>
                                <th class="val_subtotal center">4TA</th>
                                <th class="val_subtotal center">HE</th>
                                <th class="val_subtotal center">HE DOB</th>
                                <th class="val_subtotal center">DESCANSO SEM</th>
                                <th class="val_subtotal center">PATERNIDAD</th>
                                <th class="val_subtotal center">COMEDOR</th>
                                
                                <th class="val_subtotal center">ONP</th>
                                <th class="val_subtotal center">AFP FONDO</th>
                                <th class="val_subtotal center">AFP SEGURO</th>
                                <th class="val_subtotal center">AFP COMISION</th>
                                
                                <th class="val_subtotal center">RETENCION 4TA</th>
                                <th class="val_subtotal center">RETENCION 5TA</th>
                                
                                <th class="val_subtotal center">BONIF EXTRAOD</th>
                                <th class="val_subtotal center">ESSALUD</th>
                                <th class="val_subtotal center">SENATI</th>
                                <th class="val_subtotal center">SCTR SALUD</th>
                                <th class="val_subtotal center">SCTR PENSION</th>
                                <th class="val_subtotal center">TARDANZA</th>
                                
                                <th class="val_subtotal center">MOVILIDAD</th>
                                <th class="val_subtotal center">VIATICOS</th>
                                
                                <th class="val_subtotal center">REINTEGRO INAFECTO</th>
                                <th class="val_subtotal center">GRATI SEMESTRAL</th>
                                
                                <th class="val_subtotal center">DESC PRESTAMO</th>
                                <th class="val_subtotal center">DESC ADICIONAL</th>
                                <th class="val_subtotal center">DESC 4TA</th>
                                
                                <th class="val_subtotal center">VACACIONES</th>
                                <th class="val_subtotal center">AFP COMISION MIXTA</th>
                                
                                
                                
                            </tr>
                            <?php echo $body_detalle; ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>

<script>
    function btn_clear(){
        $("#cbo_area").val("");
        $("#cbo_group").val("");
        $("#chk_01").attr('checked', true);
        $("#chk_02").attr('checked', false); 
        $("#chk_04").attr('checked', true);  
        
        $("#buscar").click();

    }
    
    function ajust(){
            doc = $(window).height();
            $(".div_fondo").height(doc-160);
            $(".tab_content").height(doc-240);
            $(".tabs").css('margin-top','10px');
	}
    
 
        
    
        
    $(window).ready(function(){
        
        $('ul.tabs li').click(function(e){ 
         $('#txt_report').val($(this).attr('id'));
        });
        
        
            ajust();
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
            
            if($txt_report!=''){
                
                ?>
                 $("#<? echo $txt_report ?>").click();   
                <?
            }
            
            ?>
            
            
            
	});
</script>
    
