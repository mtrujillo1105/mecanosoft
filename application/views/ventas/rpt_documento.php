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
    <script type="text/javascript" src="<?php echo js;?>ventas/presupuesto.js"></script>
    <style>
        .tabla_cabecera tr{cursor:pointer;}
    </style>   
</head>
<body>
    <div id="container">
        <div class="header">REPORTE DE DOCUMENTOS GIRADOS POR FECHAS</div>
	<div class="case_top">
            
            
    <form method="post" name="frmDocumentos" id="frmDocumentos" target=_parent>
                <table width="100%" cellspacing="0" cellpadding="3" border="0" >
                    <tbody>
                        <tr>

                            
                            
                            <td align="left" width="14%">TIPO DE DOCUMENTO:</td>     
                            
                            
                            <td align="left" width="25%">

                    <select name='tipdcto' id='tipdcto' class='comboMedio' onchange="$('#tipoexport').val('');submit();">
                        <option value='FV' <?=($tipdcto=='FV'?'selected':'');?>>FACTURA</option>
                        <option value='BL' <?=($tipdcto=='BL'?'selected':'');?>>BOLETA</option>
                        <option value='LC' <?=($tipdcto=='LC'?'selected':'');?>>LETRA 1</option>
                        <option value='LT' <?=($tipdcto=='LT'?'selected':'');?>>LETRA2 </option>
                        <option value='NC' <?=($tipdcto=='NC'?'selected':'');?>>NOTA CREDITO</option>
                        <option value='ND' <?=($tipdcto=='ND'?'selected':'');?>>NOTA DEBITO</option>
                        <option value='NT' <?=($tipdcto=='NT'?'selected':'');?>>NOTA</option>
                    </select>
                                
                            </td>   
                            
                            
                            

                            
                            
                            <td align="left" width="3%">DESDE:</td>

                            <td align="left" width="10%">
		                    <span style="width:150px;border:0px solid #000;"> 
		                        <input  name="fecha1" id="fecha1" title="Fecha Inicio" value="<?php echo $fecha1;?>" type="text" class="cajaPequena" readonly="readonly"/>									
		                        <img src="<?php echo img;?>calendario.png" name="Calendario1" id="Calendario1" width="16" height="16" border="0" id="Image1" onMouseOver="this.style.cursor='pointer'" title="Calendario">      
		                        <script type="text/javascript">
		                            Calendar.setup({
		                                inputField     :    "fecha1",      // id del campo de texto
		                                ifFormat       :    "%d/%m/%Y",       // formato de la fecha, cuando se escriba en el campo de texto
		                                button         :    "Calendario1",   // el id del botï¿½n que lanzarï¿½ el calendario
		                                onUpdate       :    function(){
		                                   $('#tipoexport').val(''); 
		                                }
		                            });
		                        </script>		
		                    </span>	                            
                            
                            <td align="right" width="1%">HASTA:</td>
                            
                            
                            
                            <td align="left" width="22%">
                              <div style="text-align:left;">
			                    <span style="width:150px;border:0px solid #000;"> 
			                        <input  name="fecha2" id="fecha2" title="Fecha Inicio" value="<?php echo $fecha2;?>" type="text" class="cajaPequena" readonly="readonly" size="11"/>									
			                        <img src="<?php echo img;?>calendario.png" name="Calendario2" id="Calendario2" width="16" height="16" border="0" id="Image1" onMouseOver="this.style.cursor='pointer'" title="Calendario">      
			                        <script type="text/javascript">
			                            Calendar.setup({
			                                inputField     :    "fecha2",      // id del campo de texto
			                                ifFormat       :    "%d/%m/%Y",       // formato de la fecha, cuando se escriba en el campo de texto
			                                button         :    "Calendario2",   // el id del botï¿½n que lanzarï¿½ el calendario
			                                onUpdate       :    function(){
			                                   $('#tipoexport').val(''); 
			                                }                                
			                            });
			                        </script>		
			                    </span>						
                                </div>  
                            </td>
                             
                            
                            <td align="left" width="5%"></td>

                        </tr>   
                        


                        <tr>
                    
                            
                            <td align="left" width="14%">CLIENTES:</td>
                            <td align="left" width="22%">

                              <div style="text-align:left;">
                                  <?php echo $ComboCliente;?>
                              </div>  
                            </td>
                            <td align="left" width="15%">
                                <label>Cabecera<input type='radio' name="tipodetalle" id="tipodetalleId" value="C" <?=($tipocontenido=='C'?"checked='checked'":"");?> onclick="$(this).val('C');$('#tipoexport').val('');submit();"></label>  
                            </td>

                            <td align="left" width="15%">
                                <label>Detalle<input type='radio' name="tipodetalle" id="tipodetalleId" value="D" <?=($tipocontenido=='D'?"checked='checked'":"");?>  onclick="$(this).val('D');$('#tipoexport').val('');submit();"></label>
                            </td>
                            
                            
                            <td align="left" width="1%">MONEDA: </td> 
                                                       
                            <td align="left" width="10%">
                               <select name='moneda' id='moneda' class='comboMedio' onchange="$('#tipoexport').val('');submit();">
                                   <option value="2" <?=(trim($moneda_rpt)==2?"selected='selected'":'');?>>SOLES</option>
                                   <option value="3" <?=(trim($moneda_rpt)==3?"selected='selected'":'');?>>DOLARES</option>
                               </select>                             
                            </td> 
                              
                            <td align="left" width="4%"></td>

                        </tr> 

                        
                        
                        
                        <tr>
                            <td align="left" width="14%"><span align='left'><b> Cantidad: <?php  echo $j;?></span></b></td>
                            <td align="left" width="22%"></td>
                            <td align="left" width="15%"></td>
                            <td align="left" width="15%"></td>
                            <td align="left" width="1%"></td>                     
                            <td align="left" width="10%"></td> 
                            <td align="left" width="4%"></td>
                        </tr> 
                       
                                                        
                    </tbody>
                    

                </table>

                
            
                <input type="hidden" name="tipoexport" id="tipoexport">
                
    </form>

            
	</div>   
        
        
    <div class="case_botones">
        <ul class="lista_botones"><li id="salir" class="Documentos">Salir</li></ul>            
        <ul class="lista_botones"><li id="excele" class="Documentos">Ver Excel</li></ul> 
        <ul class="lista_botones"><li id="html" class="Documentos">Buscar</li></ul>
    </div> 	
        

        
    <style>
    ul {
    padding-left: 0px;
    margin-left: 0px;
    list-style-type: none;
    }	
    </style>



                 
    <div id="cabecera" style = "display: table; width: 100%;border:1px solid #000;">
        <table border='1' width=1263px >
            <THEAD>
            <tr class="cabeceraTabla" align='center'>
                <td width='2%'><div><font size="2">SERIE</font></div></td>		
                <td width='5.54%'><div><font size="2">NUMERO</font></div></td>
                <td width='15.00%'><div><font size="2">RAZON SOCIAL</font></div></td>		
                <td width='9.00%'><div><font size="2">OBSERVACIONES</font></div></td>	
                <td width='5.80%'><font size="2"><?=($codent=='01'?'O.T.':'O.S.');?></font></td>	
                <td width='6.54%'><font size="2">FEC.DCTO</font></td>
                <td width='6.54%'><font size="2">FEC.VCTO</font></td>	
                <td width='7.02%'><font size="2">FORMA PAGO</font></td>
                <?php
                if($tipocontenido=='D'){
                    ?>
                    <td width='6.51%'><font size="2">PESO</font></td>	
                    <td width='6.10%'><font size="2">P.UNITARIO</font></td>	                    
                    <?php
                }
                ?>
                <td width='6.00%'><font size="2">SUBTOTAL <?=($moneda_rpt==2?'S/.':'$');?></font></td>
                <td width='5.8%'><font size="2">IGV <?=($moneda_rpt==2?'S/.':'$');?></font></td>	
                <td width='6.12%'><font size="2">TOTAL <?=($moneda_rpt==2?'S/.':'$');?></font></td>
            </tr>
            </THEAD>

        </table>
    </div>
    
      
            
    
    <div id="detalle" style = "float: left; height: 320px;overflow:auto; width:1280px;border:0px solid #000;">
        <table border='1' style='width:100%;'>
            <?php echo $fila;?>
        </table>
    </div>
	
	
        
</body>
</html>
