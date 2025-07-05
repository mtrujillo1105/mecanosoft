<!DOCTYPE html>
<META HTTP-EQUIV="Pragma" CONTENT="no-store"/>
<META HTTP-EQUIV="Expires" CONTENT="-1"/>
<html>
<head>    
    <title><?php echo titulo;?></title>        
    <!-- Calendario -->	
    <link rel="stylesheet" href="<?php echo css;?>estilos.css" type="text/css">
    <link rel="stylesheet" href="<?php echo css;?>nav.css" type="text/css">
    <link rel="stylesheet" href="<?php echo css;?>theme.css" type="text/css">
    <link rel="stylesheet" href="<?php echo css;?>calendario/calendar-win2k-2.css" type="text/css" media="all" title="win2k-cold-1"/>	
    <!-- Calendario -->
    <script type="text/javascript" src="<?php echo js;?>constants.js"></script> 
    <script type="text/javascript" src="<?php echo js;?>calendario/calendar.js"></script>
    <script type="text/javascript" src="<?php echo js;?>calendario/calendar-es.js"></script>
    <script type="text/javascript" src="<?php echo js;?>calendario/calendar-setup.js"></script>
    <script type="text/javascript" src="<?php echo js;?>jquery.js"></script>
    <script type="text/javascript" src="<?php echo js;?>ventas/ot.js"></script>
</head>
<body>
<div id="container">
    <?php echo validation_errors("<div class='error'>",'</div>');?>  
    <div class="header">REPORTE POR FACTURAR POR RANGO DE FECHAS - (NO INCLUYE VENTAS PERIODO)</div>	
    <div class="case_top2">
        <form method="post" enctype="multipart/form-data" id="frmBusqueda">
            <table width="98%" cellspacing="0" cellpadding="3" border="0" style="margin-top:5px;">
                <tbody>
                    <tr>
                        <td align="right" width="18%">Tipo O.T.:</td>
                        <td align="left" width="32%"><?php echo $periodoOt;?></td> 
                        
                        
                        
                        <td align="right" width="8%"><span style="display: none;">Desde:</span></td>
                        <td align="left" width="12%">
                            <span id="Fecha1" style="width:150px;border:0px solid #000;float: left;display: none;">
                                <input  name="fInicio" id="fInicio" title="Fecha Inicio" value="<?php echo $fInicio;?>" type="text" class="cajaPequena" readonly="readonly">									
                                <img src="<?php echo img;?>calendario.png" name="Calendario1" id="Calendario1" width="16" height="16" border="0" id="Image1" onMouseOver="this.style.cursor='pointer'" title="Calendario">
                                <script type="text/javascript">
                                    Calendar.setup({
                                        inputField     :    "fInicio",      // id del campo de texto
                                        ifFormat       :    "%d/%m/%Y",       // formato de la fecha, cuando se escriba en el campo de texto
                                        button         :    "Calendario1"   // el id del bot�n que lanzar� el calendario
                                    });
                                </script>		
                            </span>
                        </td> 
                        
                        <td align="right" width="8%"><span>Hasta:</span></td>
                        <td align="left" width="12%">
                            <span id="Fecha2" style="width:150px;border:0px solid #000;display:block;float: left;">
                                <input  name="fFin" id="fFin" title="Fecha Inicio" value="<?php echo $fFin;?>" type="text" class="cajaPequena" readonly="readonly">									
                                <img src="<?php echo img;?>calendario.png" name="Calendario2" id="Calendario2" width="16" height="16" border="0" id="Image1" onMouseOver="this.style.cursor='pointer'" title="Calendario">
                                <script type="text/javascript">
                                    Calendar.setup({
                                        inputField     :    "fFin",      // id del campo de texto
                                        ifFormat       :    "%d/%m/%Y",       // formato de la fecha, cuando se escriba en el campo de texto
                                        button         :    "Calendario2"   // el id del bot�n que lanzar� el calendario
                                    });
                                </script>	                              
                            </span>	
                        </td> 
                        
                    </tr>
                    
                    <tr>
                        <td align="right" width="18%">Frecuencia:</td>
                        <td align="left" width="32%"><?php echo $frecuenciaOt;?></td> 
                        <td colspan="2">                            
                            <textarea style="display:none;" name="dataExcell" id="dataExcell"><?php //echo serialize($arrayExcel);?></textarea>
                            <input type="hidden" name="ver" id="ver">
                            <input type="hidden" name="tipo" id="tipo"> <input type="hidden" name="nivel" id="nivel"> 
                            <input type="hidden" name="fInicio_ini" id="fInicio_ini" value="<?php echo $fInicio_ini;?>"> 
                            <input type="hidden" name="fFin_ini" id="fFin_ini" value="<?php echo $fFin_ini;?>">   
                            <input type="hidden" name="del" id="del" > <input type="hidden" name="al" id="al" >    
                        </td>
                    </tr>                    
                    
                </tbody>
            </table>
        </form>
    </div>
    <div id="idcontenido">
        <div class="case_botones">
            <ul class="lista_botones"><li id="salir" class="xfacturar_xintervalos">Salir</li></ul>          
            <!--ul class="lista_botones"><li id="grafica" class="xfacturar_xintervalos">Ver Grafica</li></ul--->        
            <!--ul class="lista_botones"><li id="excel" class="xfacturar_xintervalos">Ver Excel</li></ul-->
            <!--ul class="lista_botones"><li id="pdf" class="xfacturar_xintervalos">Ver Pdf</li></ul-->
            <ul class="lista_botones"><li id="html" class="xfacturar_xintervalos">Ver Html</li></ul>  
        </div> 
        <div id="idcontenido2">
            <?php
            if(count($arrFila)>0 && $_REQUEST['fFin']!=""){
                foreach($arrFila as $ind=>$val){
                    $temp     = explode("/",$arrFecha[$ind][0]);
                    $fec_temp = mktime( 0, 0, 0, $temp[1], $temp[0],$temp[2]); 
                    $dia_temp = date("d/m/Y",$fec_temp-86400);
                    ?>
                    <span style="float:left;width:80%;text-align:left;font-size:14px;margin-top:40px;">Reporte del <?php echo $arrFecha[$ind][0];?> al <?php echo $arrFecha[$ind][1];?></span>
                    <span style="float:left;width:20%;text-align:right;font-size:14px;margin-top:40px;">T.C: <?php echo $arrTipoC[$ind];?></span>	                    
                        <span><ul class="lista_botones"><li id="excel" class="xfacturar_xintervalos" name="<?php echo $arrFecha[$ind][0];?>" name2="<?php echo $arrFecha[$ind][1];?>">Ver Excel</li></ul></span>
                    <div style="clear:both;">
                        <table border='1' width='100%'>
                            <thead>
                                 <tr align="center"  class="cabeceraTabla">
                                    <td width='5px;'>No</td>
                                    <td>CLIENTE</td>
                                    <td width='80px;'>SALDO POR FACTURAR S/.<br>(<?php echo $dia_temp;?>)</td>
                                    <td width='80px;'>SALDO POR FACTURAR $.<br>(<?php echo $dia_temp;?>)</td>
                                    <td width='90px;'>MONTO <BR>FACTURADO. S/.</td>
                                    <td width='90px;'>MONTO <BR>FACTURADO. $</td>
                                    <td width='80px;'>SALDO POR<BR>FACTURAR S/.<BR>(<?php echo $arrFecha[$ind][1];?>)</td>
                                    <td width='80px;'>SALDO POR<BR>FACTURAR $<BR>(<?php echo $arrFecha[$ind][1];?>)</td>
                                    <td width='98px;'>SALDO TOTAL<BR>POR FACTURAR EN $<BR>(<?php echo $arrFecha[$ind][1];?>)</td>						
                                    <td width='98px;'>VALOR TOTAL<BR>VENTA OT $</td>	
                                    <td width='98px;'>RENDIMIENTO<BR>FACTURACION (%)</td>	
                                </tr>
                            </thead>
                            <tbody>
                                <?php echo $arrFila[$ind];?>
                                <?php $acum_vta = $arr_acumulado_valor_venta[$ind]!=0?$arr_acumulado_valor_venta[$ind]:1;?>
                            </tbody>
                            <tfoot> 
                             <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td align="right"><?php echo number_format(($arr_acumulado_saldosoles_ant[$ind]),2,".",",");?></td>
                                <td align="right"><?php echo number_format(($arr_acumulado_saldodolares_ant[$ind]),2,".",",");?></td>
                                <td align='right'><?php echo number_format(($arr_acumuado_factSoles[$ind]),2,".",",");?></td>
                                <td align='right'><?php echo number_format(($arr_acumuado_factDolares[$ind]),2,".",",");?></td>
                                <td align='right'><?php echo number_format(($arr_acumuado_saldoSoles[$ind]),2,".",",");?></td>
                                <td align='right'><?php echo number_format(($arr_acumuado_saldoDolares[$ind]),2,".",",");?></td>
                                <td align='right'><?php echo number_format(($arr_acumuado_saldoDolares_total[$ind]),2,".",",");?></td>
                                <td align='right'><?php echo number_format(($arr_acumulado_valor_venta[$ind]),2,".",",");?></td>
                                <td align='right'><?php echo number_format(100-($arr_acumuado_saldoDolares_total[$ind]*100/$acum_vta),2,".",",");?></td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>	
                    <?php
                    //$_SESSION['monto_dolares'] = serialize($arr_acumuado_saldoDolares);
                    //$_SESSION['monto_soles']   = serialize($arr_acumuado_saldoSoles);
                    }
                    ?>
                    <script>alert("Proceso finalizado");</script>
                    <span style="float:left;width:80%;text-align:left;font-size:14px;margin-top:20px;">
                        *  Saldo por facturar<HASTA> = Saldo por facturar<DESDE> - Monto facturado<br><br>
                        ** Rendimiento facturacion   = 100 - (Saldo total por facturar $<HASTA> * 100 / Valor total venta OT $)<br>
                    </span>			
                    <?php
                    }
                ?>
        </div>
   </div>
</div>	
</body>
</html>