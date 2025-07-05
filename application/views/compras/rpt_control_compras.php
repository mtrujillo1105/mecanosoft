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

    
    <!----- Grafica ---------
    <script type="text/javascript" src="<?php echo js;?>jquery.min.js"></script>
    <script type="text/javascript" src="<?php echo js;?>highcharts.js"></script>
    <script type="text/javascript" src="<?php echo js;?>exporting.js"></script>
    <script type="text/javascript" src="<?php echo js;?>highslide-full.min.js"></script>
    <script type="text/javascript" src="<?php echo js;?>highslide.config.js" charset="utf-8"></script>
    <script type="text/javascript"> 
    var example = 'column-parsed',
            theme = 'default';
    </script>
    <script type="text/javascript" src="<?php echo js;?>scripts.js"></script>
    <script type="text/javascript"> 
            Highcharts.theme = { colors: ['#4572A7'] };// prevent errors in default theme
            var highchartsOptions = Highcharts.getOptions(); 
    </script>
    ----- Grafica ------------>    


<!--    
 <script type="text/javascript">

                        Highcharts.visualize = function(table, options) {
			// the categories
			options.xAxis.categories = [];
			jQuery('tbody th', table).each( function(i) {
				options.xAxis.categories.push(this.innerHTML);
			});
			
			// the data series
			options.series = [];
			jQuery('tr', table).each( function(i) {
				var tr = this;
				jQuery('th, td', tr).each( function(j) {
					if (j > 0) { // skip first column
						if (i == 0) { // get the name and init the series
							options.series[j - 1] = { 
								name: this.innerHTML,
								data: []
							};
						} else { // add values
							options.series[j - 1].data.push(parseFloat(this.innerHTML));
						}
					}
				});
			});
			
			var chart = new Highcharts.Chart(options);
		}
	
		// On document ready, call visualize on the datatable.
		jQuery(document).ready(function() {			
			var table = document.getElementById('datatable'),
			options = {
				   chart: {
				      renderTo: 'container2',
				      defaultSeriesType: 'column'
				   },
				   title: {
				      text: 'Cod Solicitud por Fecha'
				   },
				   xAxis: {
				   },
				   yAxis: {
				      title: {
				         text: 'codigo solicitado'
				      }
				   },
				   tooltip: {
				      formatter: function() {
				         return '<b>'+ this.series.name +'</b><br/>'+
				            this.y +' '+ this.x.toLowerCase();
				      }
				   }
				};
	
      					
			Highcharts.visualize(table, options);
		});
	</script>   
    -->
    
    
    
    
    
</head>
<body>
<div id="container">
    <div class="header">REPORTE DE CONTROL DE COMPRAS    (Solo Galvanizado)</div>	
    <div class="case_top2">
        <form method="post" enctype="multipart/form-data" id="frmControl">
            <table width="98%" cellspacing="0" cellpadding="3" border="0" style="margin-top:5px;">
                <tbody>
                    <tr>
                        <td align="right" width="18%"><span>DEL</span></td>
                        <td align="left" width="32%">
                            <span style="width:500px;border:0px solid #000;">
                                <input  name="fInicio" id="fInicio" title="Fecha Inicio" value="<?php echo $fInicio_ini;?>" type="text" class="cajaPequena" readonly="readonly"/>									
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
                        <td align="right" width="18%"><span>AL</span></td>
                        <td align="left" width="32%">
                            <span>
                                <input  name="fFin" id="fFin" title="Fecha Inicio" value="<?php echo $fFin_ini;?>" type="text" class="cajaPequena" readonly="readonly">									
                                <img src="<?php echo img;?>calendario.png" name="Calendario2" id="Calendario2" width="16" height="16" border="0" id="Image1" onMouseOver="this.style.cursor='pointer'" title="Calendario">
                                <script type="text/javascript">
                                    Calendar.setup({
                                        inputField     :    "fFin",      // id del campo de texto
                                        ifFormat       :    "%d/%m/%Y",       // formato de la fecha, cuando se escriba en el campo de texto
                                        button         :    "Calendario2"   // el id del bot�n que lanzar� el calendario
                                    });
                                </script>	
                                
                                
                                
                                <input type="hidden" name="ver" id="ver">
                                 <input type="hidden" name="tipo" id="tipo">
                                <textarea style="display:none;" name="dataExcell" id="dataExcell"><?php echo serialize($arrayExcel);?></textarea>
                            
                            
                            </span>	
                        </td>                          
                    </tr>                                    
                </tbody>
            </table>
        </form>
    </div>
    <div class="case_botones">
        <ul class="lista_botones"><li id="salir" class="control">Salir</li></ul>          
<!--    <ul class="lista_botones">
        <a href="javascript:void(0)" onclick="document.getElementById('light').style.display='block';document.getElementById('fade').style.display='block'">
        <li class="control" id="grafica">Ver Grafica</li>
        </a>
        </ul>     -->
        <ul class="lista_botones"><li id="excel" class="control">Ver Excel</li></ul>
  <!--  <ul class="lista_botones"><li id="pdf" class="control">Ver Pdf (HojaA3)</li></ul> -->
        <ul class="lista_botones"><li id="html" class="control">Ver Html</li></ul>  
    </div> 
    
    
 
    <?php
    if(isset($_REQUEST['fInicio']) && $_REQUEST['fFin']!=""){
        ?>
        <div style="float:left;width:80%;text-align:left;font-size:14px;margin-top:0px;">Reporte del <?php $fInicio=$_POST["fInicio"]; echo $fInicio; ?> Al <?php $fFin=$_POST["fFin"]; echo $fFin;?></div>	
        <div>Registros:<?php echo $registros;?></div>
        <?php
        }?>
        <div style="clear:both;">
            <table  border='1' width='100%'>
                <tr style='font:12px; font:arial;color:#fff;background:#8AA8F3;'>
                    <td width='80px;'>SERIE <BR>REQUERIMIENTO</td>
                    <td width='80px;'>NUMERO <BR>REQUERIMIENTO</td>
                    <td width='80px;'>FECHA <BR>REQUERIMIENTO</td>
                    <td width='90px;'>SERIE <BR>SOLICITUD COMPRA</td>
                    <td width='80px;'>NUMERO <BR>SOLICITUD COMPRA</td>
                    <td width='80px;'>FECHA <BR>SOLICITUD COMPRA</td>
                    <td width='90px;'>SERIE <BR>ORDEN COMPRA</td>
                    <td width='80px;'>NUMERO <BR>ORDEN COMPRA</td>
                    <td width='80px;'>FECHA <BR>ORDEN COMPRA</td>
                    <td width='98px;'>FECHA <BR>APROBACION</td>
                    <td width='80px;'>FECHA REGISTRO<BR>ORDEN COMPRA</td>
                    <td width='98px;'>SERIE <BR>NOTA ENTRADA</td>
                    <td width='98px;'>NUMERO <BR>NOTA ENTRADA</td>
                    <td width='98px;'>FECHA <BR>NOTA ENTRADA</td>
                    <td width='98px;'>FECHA <BR>GUIA CLIENTE</td>
                    <td width='98px;'>FECHA REGISTRO <BR>NOTA ENTRADA</td>
                    <td width='98px;'>CODIGO <BR>PRODUCTO</td>
                    <td width='120px;'>PRODUCTO</td>
                    <td width='80px;'>RUC CLIENTE</td>
                    <td width='80px;'>RAZON SOCIAL<BR>CLIENTE</td>
                </tr>
                <?php
                if($fila!=""){
                    echo $fila;    
                }
                else{
                    echo "<tr><td colspan='20' align='center'>::NO EXISTEN REGISTROS::</td></tr>";
                }
                ?>
            </table>
        </div>	
<!--        <script>alert("Proceso finalizado");</script>		-->
</div>	

    <div style="border:3px solid #808080; padding:0px; display: none" id="light" class="modal">
    
    <a href="javascript:void(0)" onclick="document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'">
	<img border="0" src="<?php echo img;?>b_salir_on.gif" width="79" height="27" align="right" hspace="0"></a> 
       
	<div id="container2" class="highcharts-container" style="height:410px; margin: 0 2em; clear:both; min-width: 600px">
	</div>
 
 
  
        <table id="datatable" style="display:none;">

	<tr height="20" style="height:15.0pt">
		<td></td>
		<td>Producto Por Fecha</td>
	</tr>
	
        <?php echo $fila2;?>
    
</table>
		
    </div>

    
</body>
</html>