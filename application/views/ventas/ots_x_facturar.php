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
    <div class="header">REPORTE POR FACTURAR POR CLIENTE - (INCLUYE NUEVAS VENTAS)</div>	
    <div class="case_top2">
        <form method="post" id="frmFact">           
            <table width="98%" cellspacing="3" cellpadding="3" border="0" style="margin-top:5px;">
                <tbody>
                    <tr>
                        <td align="right" width="18%">Tipo O.T.:</td>
                        <td align="left" width="32%"><?php echo $periodoOt;?></td>
                        <td align="right" width="18%">Hasta:</td>
                        <td align="left">
                            <div style="text-align:left;">
                              
                               
     
                    
                                <span>
                                   <span id="Fecha1" style="width:500px;border:0px solid #000;display:none;">
                                    <input  name="fInicio" id="fInicio" title="Fecha Inicio" value="<?php echo $fInicio;?>" type="text" class="cajaPequena" >									
                                    </span>                                    
                                    <span id="Fecha2" style="width:500px;border:0px solid #000;display:anone;">
                                    <input  name="fFin" id="fFin" title="Fecha Inicio" value="<?php echo $fFin;?>" type="text" class="cajaPequena" >									
                                    <img src="<?php echo img;?>calendario.png" name="Calendario2" id="Calendario2" width="16" height="16" border="0" id="Image1" onMouseOver="this.style.cursor='pointer'" title="Calendario">
                                    <script type="text/javascript">
                                        Calendar.setup({
                                            inputField     :    "fFin",      // id del campo de texto
                                            ifFormat       :    "%d/%m/%Y",       // formato de la fecha, cuando se escriba en el campo de texto
                                            button         :    "Calendario2"   // el id del bot�n que lanzar� el calendario
                                        });
                                    </script>	                    
                                    </span>
                                    
	
                                </span>	
                            </div>
                        </td>                          
                    </tr>
                    <tr>
                        <td align="right">Cliente</td>
                        <td align="left" width="32%">
                            <select class="comboGrande" name="codcliente" id="codcliente">
                                <?php
                                foreach($cboCli as $ind=>$val){
                                    ?>
                                    <option value="<?php echo $ind;?>" <?php if($codcliente==$ind) echo "selected='selected'";?>><?php echo $val;?></option>
                                    <?php
                                }
                                ?>
                            </select>					
                        </td>
                        <td align="right" colspan="2">
                            <input type="hidden" name="tipo" id="tipo">
                            <input type="hidden" name="codot" id="codot">
                            <input type="hidden" name="codcliente2" id="codcliente2">
                            <input type="hidden" name="nivel" id="nivel">
                        </td>
                    </tr>                                      
                </tbody>
            </table>
        </form>
    </div>
    <div id="idcontenido">    
	<div class="case_botones">
            <ul class="lista_botones"><li id="salir" class="xfacturar">Salir</li></ul>            
            <!--ul class="lista_botones"><li id="grafica" class="xfacturar">Ver Grafica</li></ul--> 
            <ul class="lista_botones"><li id="excel" class="xfacturar">Ver Excel</li></ul>
            <ul class="lista_botones"><li id="pdf" class="xfacturar">Ver Pdf</li></ul>
            <ul class="lista_botones"><li id="html" class="xfacturar">Ver Html</li></ul>
            <!--ul class="lista_botones"><li id="atras" class="xfacturar">Inicio</li></ul-->  
	</div> 	
	<?php
	if($fInicio!="" && $fFin!="" && $tipo!=""){
	?>
        <div id="idcontenido2">
            <div style="text-align:left;float:left;width:80%;font-size:13px;margin-top:10px;"><h3>REPORTE POR FACTURAR POR CLIENTES (INCLUYE NUEVAS VENTAS) - <?php echo $fFin;?></h3></div>
            <div style="float:left;width:20%;font-size:13px;margin-top:10px;"><h3>T.C: <?php echo $tc;?></h3></div>
            <div style="clear:both;padding-top:5px;">
                <table border='1' width='100%'>
                    <thead>
                        <tr align="center" class="cabeceraTabla">
                            <td width="8px;">No</td>
                            <td>CLIENTE</td>
                            <td>VALOR DE<BR>VENTA S/.</td>
                            <td>VALOR DE<BR>VENTA $</td>
                            <td width='90px;'>MONTO <BR>FACTURADO S/.</td>
                            <td width='90px;'>MONTO <BR>FACTURADO $</td>
                            <td width='80px;'>SALDO POR<BR>FACTURAR S/.<BR>(<?php echo $_REQUEST['fFin'];?>)</td>
                            <td width='80px;'>SALDO POR<BR>FACTURAR $<BR>(<?php echo $_REQUEST['fFin'];?>)</td>
                            <td width='98px;'>SALDO TOTAL<BR>POR FACTURAR $<BR>(<?php echo $_REQUEST['fFin'];?>)</td>
                        </tr>
                    </thead>
                        <tbody><?php echo $fila;?></tbody>
                    <tfoot>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td align='right'><?php echo number_format(($acumulado_soles),2,",",".");?></td>
                            <td align='right'><?php echo number_format(($acumulado_dolares),2,",",".");?></td>
                            <td align='right'><?php echo number_format(($acumuado_factSoles),2,",",".");?></td>
                            <td align='right'><?php echo number_format(($acumuado_factDolares),2,",",".");?></td>
                            <td align='right'><?php echo number_format(($acumuado_saldoSoles),2,",",".");?></td>
                            <td align='right'><?php echo number_format(($acumuado_saldoDolares),2,",",".");?></td>
                            <td align='right'><?php echo number_format(($acumuado_saldoDolares_total),2,",",".");?></td>

                        </tr>
                    </tfoot>
                </table>
                <br/><br/>
            </div>
        </div>
    </div>
    <!--script>alert("Proceso finalizado");</script-->
    <?php
    }
    ?>
</div>	
</body>
</html>