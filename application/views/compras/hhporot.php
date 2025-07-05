<!DOCTYPE html>
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
    <script type="text/javascript" src="<?php echo js;?>compras/ocompra.js"></script>
</head>
<body>
<div id="container">
    <div class="header">INDICADORES HORAS HOMBRES POR OT   (Solo Metales)</div>	
    <div class="case_top">
        <form method="post" enctype="multipart/form-data" id="frmHorasH" name="frmHorasH">
 
            <table width="98%" cellspacing="0" cellpadding="3" border="0" style="margin-top:5px;">
                <tbody>
                    <tr>
                        <td align="right" width="9%">TIPO OT</td>
                        
                        <td align="left" width="14%">
                        <?php echo $selecttipoot;?>
                        </td>
                        
                        <td align="right" width="5%">ESTADO</td>
                        
                        <td align="left" width="12%">
                        <?php echo $selecestado;?>
                        </td>    
						                      
                        <td align="left" width="52%">
                        </td> 
                                                 
                </tr>          
                    
		<tr>
                        <td align="right" width="9%"><span>TIPO TORRE</span></td>
                        
                        <td align="left" width="14%">
                        <?php echo $selecttorre;?>
			</td>
                        
                        <td align="right" width="5%">CLIENTE</td>
                        
                        <td align="left" width="12%">
                        <?php echo $seleccliente;?>
                        </td>               
                                    
                       <td align="left" width="52%">
                       </td>                          
                    </tr>            
                          
                </tbody>
            </table>

            <input type="text" name="tipoexport" id="tipoexport"></input>
            
        </form>
    </div>
    <div class="case_botones">
        <ul class="lista_botones"><li id="salir" class="HorasH">Salir</li></ul>              
        <ul class="lista_botones"><li id="excel" class="HorasH">Ver Excel</li></ul>
        <ul class="lista_botones"><li id="html" class="HorasH">Ver Html</li></ul>  
    </div> 
    
    
  
  
    
  
        <div style = "float: left; height:50px; width: 2243px;border:1px solid #000;">
            <table border='1' style='width:2243px;'>
                <THEAD>
                <tr align='center' style="height:50px;" class="cabeceraTabla">
                    <td style='width:3.17%;'><div><font size="1"><b>NRO OT</b></font></div></td>
                    <td style='width:3.0%;'><div><font size="1"><b>FECHA</b></font></div></td> 
                    <td style='width:7%;'><div><font size="1"><b>SITE</b></font></div></td>                
                    <td style='width:7.13%;'><div><font size="1"><b>CLIENTE</b></font></div></td>
                    <td style='width:3.12%;'><div><font size="1"><b>PESO(TN)</b></font></div></td>
                    <td style='width:2.12%;'><div><font size="1"><b>ALT.</b></font></div></td>
                    <td style='width:5.89%;'><div><font size="1"><b>TIPO<br>TORRE</b></font></div></td>
                    <td style='width:4.37%;'>__________</td>
                    <td style='width:4.28%;'><div><font size="1"><b>ABRAZADERAS</b></font></div></td>
                    <td style='width:4.28%;'><div><font size="1"><b>ANGLEMASTER</b></font></div></td>
                    <td style='width:4.28%;'><div><font size="1"><b>ESCALERAS</b></font></div></td>
                    <td style='width:4.28%;'><div><font size="1"><b>ESTRUCTURAS<BR>ESPECIALES</b></font></div></td>
                    <td style='width:4.28%;'><div><font size="1"><b>FPB</b></font></div></td>
                    <td style='width:4.28%;'><div><font size="1"><b>LIMPIEZA<BR>MECANICA</font></b></div></td>
                    <td style='width:4.28%;'><div><font size="1"><b>MAESTRANZA</b></font></div></td>
                    <td style='width:4.28%;'><div><font size="1"><b>OTRAS<BR>ESTRUCTURAS</b></font></div></td>
                    <td style='width:4.28%;'><div><font size="1"><b>PARRILLAS</b></font></div></td>
                    <td style='width:4.28%;'><div><font size="1"><b>PINTURAS</b></font></div></td>
                    <td style='width:4.28%;'><div><font size="1"><b>PLACAS</b></font></div></td>
                    <td style='width:4.28%;'><div><font size="1"><b>SELEC <BR>DESP</b></font></div></td>
                    <td style='width:4.28%;'><div><font size="1"><b>SOLDADURA</b></font></div></td>
                    <td style='width:4.28%;'><div><font size="1"><b>SOPORTES</b></font></div></td>
                    <td style='width:4.28%;'><div><font size="1"><b>TOTAL</b></font></div></td>
                </tr>
                </THEAD> 
            </table>
        </div>
        
        <div style = "float: left; height: 380px;overflow:auto; width:2263px;border:1px solid #000;">
            
            
    <style>
    ul {
    padding-left: 0px;
    margin-left: 0px;
    list-style-type: none;
    }	
    </style>
    
            <table border='1' style='width:2243px;'>
			<?php echo $fila;?>
			</table>        
        </div>
    
    
      
  
</div>	
  
		
    </div>

    
</body>



</html>