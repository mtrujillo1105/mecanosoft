<!DOCTYPE html>
<html>
<head>
    <title><?php echo titulo;?></title> 
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
    <script type="text/javascript" src="<?php echo js;?>contabilidad/reportes.js"></script>
</head>
<body>
    <div id="container">
        <div class="header">GESTION DE ORDENES DE TRABAJO</div>
	<div class="case_top2">
            <form method="post" id="frmBusqueda">
                <table width="98%" cellspacing="3" cellpadding="3" border="0" style="margin-top:5px;">
                    <tbody>
                        <tr>
                            <td align="right" width="10%">Tipo O.T.:</td>
                            <td align="left" width="23%"><?php echo $periodoOt;?></td>
                            <td align="right" width="10%">Proyecto:</td>
                            <td align="left" width="23%"><?php echo $cboProyecto;?></td>                            
                            <td align="right" width="10%">Desde</td>   
                            <td align="left" width="23%">
                                <div style="text-align:left;">
                                    <span style="width:500px;border:0px solid #000;" id="Fecha1">
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
                                    <span>Hasta</span>
                                    <span id="Fecha2">
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
                                </div>
                            </td>
                        </tr>                        
                        <tr>
                            <td align="right" width="10%">Cliente:</td>
                            <td align="left" width="23%"><?php echo $cboCliente;?></td>
                            <td align="right" width="10%">Estado</td>   
                            <td align="left" width="23%">
                                <input type="hidden" name="opcion" id="opcion">
                                <input type="hidden" name="tipo" id="tipo">                                
                                <?php echo $cboEstado;?>
                            </td>
                            <td align="right" width="10%"></td>   
                            <td align="left" width="23%"><?php echo $oculto;?></td>                            
                        </tr>                                     
                    </tbody>
                </table>
            </form>
	</div>    
	<div class="case_botones">
            <ul class="lista_botones"><li id="salir" class="control_pesos">Salir</li></ul>            
            <ul class="lista_botones"><li id="excel" class="control_pesos">Ver Excel</li></ul>
<!--            <ul class="lista_botones"><li id="pdf" class="control_pesos">Ver Pdf</li></ul>-->
            <ul class="lista_botones"><li id="html" class="control_pesos">Ver Html</li></ul>  
	</div> 	        
	<div style="widh:100%;border:1px solid #000;height:250px;overflow:auto;margin-top: 5px;">
            <table width="100%" border="0">
                <thead>
                    <tr align="center">
                        <td>No OT</td>  
                        <td>FECHA<br>O.T.</td>
                        <td>P.O.</td>  
                        <td>PROYECTO</td>  
                        <td>CLIENTE</td>
                        <td>SITE</td>
<!--                        <td>UBICACION</td>
                        <td>CANTIDAD</td>-->
                        <td>T.PRODUCTO</td>
                        <td>PESO TEOR.<BR>(KG)</td>
<!--                        <td>AVANCE PROD.(%)</td>-->
                        <td>FECHA PROG.<BR>INICIAL</td>
                        <td>FECHA PROG.<BR>FINAL</td>                        
                        <td>FECHA FIN<br>PRODUCCION</td>
                        <td>FECHA DESPACHO</td>
                        <td>COSTO<BR>TOTAL S/.</td>
                    </tr>
                </thead>
                <tbody>
                    <?php echo $fila;?>	
                </tbody>
            </table>
	</div>
	<div style="display:anone;border:1px solid #ccc;float:left;height:200px;width:100%;">
            <div style="border:1px solid #000;height:31px;background:#000;">
                <div id="ttab1" class="tabulaciones">MATERIALES</div>					
                <div id="ttab2" class="tabulaciones">MANO OBRA</div>
                <div id="ttab3" class="tabulaciones">SERVICIOS</div>
                <div id="ttab4" class="tabulaciones">TRANSPORTE</div>
                <div id="ttab5" class="tabulaciones">GALVANIZADO</div>
                <div id="ttab6" class="tabulaciones">TESORERIA</div>
                <div id="ttab7" class="tabulaciones">CAJA CHICA</div>
            </div>
            <div style="clear:both;height:170px;overflow:auto;" id="divMateriales">&nbsp;</div>
            <div style="clear:both;height:170px;overflow:auto;" id="divRrhh">&nbsp;</div>
            <div style="clear:both;height:170px;overflow:auto;" id="divServicios">&nbsp;</div>   
            <div style="clear:both;height:170px;overflow:auto;" id="divTransporte">&nbsp;</div>
            <div style="clear:both;height:170px;overflow:auto;" id="divGalvanizado">&nbsp;</div>
            <div style="clear:both;height:170px;overflow:auto;" id="divTesoreria">&nbsp;</div>
            <div style="clear:both;height:170px;overflow:auto;" id="divCaja">&nbsp;</div>
	</div>
<!--	<div style="float:left;width:1%;height:200px;">&nbsp;</div>
	<div style="border:1px solid #ccc;float:left;height:200px;width:50.5%;">
            <div style="border:1px solid #000;height:31px;background:#000;">
                <div id="ttab1" onclick="$('#divAvance').show();$('#divDetalle').hide();" class="tabulaciones">AVANCES</div>					
                <div id="ttab2" onclick="$('#divDetalle').show();$('#divAvance').hide();" class="tabulaciones">DETALLE</div>
            </div>
            <div style="clear:both;overflow:auto;" id="divAvance">
                <table width="100%">
                    <thead>
                        <tr align="center">
                            <td>NOMBRE</td>			
                            <td>%</td>							
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td align="left">DOBLADO</td>			
                            <td align="center">20</td>							
                        </tr>		
                        <tr>
                            <td align="left">TORNADO</td>			
                            <td align="center">30</td>							
                        </tr>
                        <tr>
                            <td align="left">PINTADO</td>			
                            <td align="center">10</td>							
                        </tr>					
                    </tbody>
                </table>
            </div>
            <div style="clear:both;display:none;overflow:auto;" id="divDetalle">
                <table width="100%">
                    <thead>
                        <tr align="center">
                            <td>FECHA</td>			
                            <td>GRAFICA</td>							
                        </tr>
                    </thead>
                        <tbody>
                            <tr>
                                <td align="left">11/05/2011</td>			
                                <td align="center">
                                    <table style="width:100%;">
                                        <tr>
                                            <td style="height:10px;">
                                                <div style="width:100%;height:70%;border:1px solid #000;"></div>
                                                <div style="width:100%;height:30%;background:#00ff00;border:1px solid #000 #fff #000 #000;"></div>
                                            </td>
                                            <td>
                                                <div style="width:100%;height:50%;border:1px solid #000;"></div>
                                                <div style="width:100%;height:50%;background:#00ff00;border:1px solid #000 #fff #000 #000;"></div>									
                                            </td>
                                            <td>
                                                <div style="width:100%;height:10%;border:1px solid #000;"></div>
                                                <div style="width:100%;height:90%;background:#00ff00;border:1px solid #000 #fff #000 #000;"></div>									
                                            </td>
                                            <td>
                                                <div style="width:100%;height:20%;border:1px solid #000;"></div>
                                                <div style="width:100%;height:80%;background:#00ff00;border:1px solid #000 #fff #000 #000;"></div>																		
                                            </td>
                                        </tr>
                                    </table>
                                </td>							
                            </tr>		
                            <tr>
                                <td align="left">18/05/2011</td>			
                                <td align="center">
                                    <table style="width:100%;">
                                        <tr>
                                            <td style="height:10px;">
                                                <div style="width:100%;height:70%;border:1px solid #000;"></div>
                                                <div style="width:100%;height:30%;background:#00ff00;border:1px solid #000 #fff #000 #000;"></div>
                                            </td>
                                            <td>
                                                <div style="width:100%;height:50%;border:1px solid #000;"></div>
                                                <div style="width:100%;height:50%;background:#00ff00;border:1px solid #000 #fff #000 #000;"></div>									
                                            </td>
                                            <td>
                                                <div style="width:100%;height:10%;border:1px solid #000;"></div>
                                                <div style="width:100%;height:90%;background:#00ff00;border:1px solid #000 #fff #000 #000;"></div>									
                                            </td>
                                            <td>
                                                <div style="width:100%;height:20%;border:1px solid #000;"></div>
                                                <div style="width:100%;height:80%;background:#00ff00;border:1px solid #000 #fff #000 #000;"></div>																		
                                            </td>
                                        </tr>
                                    </table>						
                                </td>							
                            </tr>
                            <tr>
                                <td align="left">22/05/2011</td>			
                                <td align="center">
                                    <table style="width:100%;">
                                        <tr>
                                            <td style="height:10px;">
                                                <div style="width:100%;height:70%;border:1px solid #000;"></div>
                                                <div style="width:100%;height:30%;background:#00ff00;border:1px solid #000 #fff #000 #000;"></div>
                                            </td>
                                            <td>
                                                <div style="width:100%;height:50%;border:1px solid #000;"></div>
                                                <div style="width:100%;height:50%;background:#00ff00;border:1px solid #000 #fff #000 #000;"></div>									
                                            </td>
                                            <td>
                                                <div style="width:100%;height:10%;border:1px solid #000;"></div>
                                                <div style="width:100%;height:90%;background:#00ff00;border:1px solid #000 #fff #000 #000;"></div>									
                                            </td>
                                            <td>
                                                <div style="width:100%;height:20%;border:1px solid #000;"></div>
                                                <div style="width:100%;height:80%;background:#00ff00;border:1px solid #000 #fff #000 #000;"></div>																		
                                            </td>
                                        </tr>
                                    </table>						
                                </td>							
                            </tr>					
                        </tbody>
                </table>
            </div>		
	</div>-->
    </div>
</body>
</html>