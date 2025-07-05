<!DOCTYPE html>
<html>
<head>

    <title><?php echo titulo;?></title> 
    <link rel="stylesheet" href="<?php echo css;?>estilos.css" type="text/css">
    <link rel="stylesheet" href="<?php echo css;?>nav.css" type="text/css">
    <link rel="stylesheet" href="<?php echo css;?>theme.css" type="text/css">
       <link rel="stylesheet" href="<?php echo css;?>estilos.css" type="text/css">    
    <script type="text/javascript" src="<?php echo js;?>constants.js"></script> 
    
   
   <script type="text/javascript" src="<?php echo js;?>calendario/calendar.js"></script>
    <script type="text/javascript" src="<?php echo js;?>calendario/calendar-es.js"></script>
    <script type="text/javascript" src="<?php echo js;?>calendario/calendar-setup.js"></script>
     <link rel="stylesheet" href="<?php echo css;?>calendario/calendar-win2k-2.css" type="text/css" media="all" title="win2k-cold-1"/>	    

    <!--  
    <link href="<?php echo css;?>calendar/calendar-green.css" media="screen" rel="stylesheet" type="text/css">
        <link href="<?php echo css;?>calendar/calendar-green.css" rel="stylesheet" type="text/css"> 
        <script type="text/javascript" src="<?php echo js;?>calendar/calendar.js"></script>
   		<script type="text/javascript" src="<?php echo js;?>calendar/calendar-es.js"></script> 
   		<script type="text/javascript" src="<?php echo js;?>calendar/calendar-setup.js"></script>
    -->
    
    <script type="text/javascript" src="<?php echo js;?>jquery.js"></script> 
    <script type="text/javascript" src="<?php echo js;?>contabilidad/costos.js"></script>
    <style>
        .tabla_cabecera tr{cursor:pointer;}
    </style>    
    <link rel="stylesheet" href="calendar-win2k-2.css" type="text/css" media="all" title="win2k-cold-1"/>
</head>    
<body>
    <div id="container">
        <?php echo validation_errors("<div class='error'>",'</div>');?>  
        <div class="header">REPORTE DE COSTOS POR CENTRO DE COSTO</div>
        <div class="case_top2">
           <form name="frmBusqueda" id="frmBusqueda" method="post" onsubmit="return validarForm(this);">
                <table width="100%" cellspacing="0" cellpadding="3" border="0" >
                    <tbody>
                        <tr>
                            <td align="left" width="10%">CENTRO COSTO:</td>     
                            <td align="left"><?php echo $seltipot;?></td>   
                            <td align="left" width="10%">FECHA INI.:</td>     
                            <td align="left">
                                <span style="width:500px;border:0px solid #000;" id="Fecha1">
                                    <input  name="fecha_ini" id="fecha_ini" title="Fecha" value="<?php echo $fecha_ini;?>" type="text" readonly="readonly" style='width:80px;' >									               
                                     <!--  onClick="popUpCalendar(this, frmBusqueda.fecha_ini, 'mm/dd/yyyy');"-->
                                     <img src="<?php echo img;?>calendario.png" name="Calendario1" id="Calendario1" width="25"  border="0" id="Image1" onMouseOver="this.style.cursor='pointer'" title="Calendario" >
                                     <script type="text/javascript">
                                        Calendar.setup({
                                                inputField     :    "fecha_ini",      // id del campo de texto
                                                ifFormat       :    "%d/%m/%Y",       // formato de la fecha, cuando se escriba en el campo de texto
                                                button         :    "Calendario1",   // el id del bot?n que lanzar? el calendario
                                                onUpdate       :    function(){ 
                                                   $('#tipoexport').val('');
                                                   //$("#frmBusqueda").submit();
                                                }
                                        });
                                        </script>	                                    
                                </span>                                
                            </td>
                            <td align="left">
                                FECHA FIN:
                                <span style="width:500px;border:0px solid #000;" id="Fecha1" >
                                    <input  name="fecha_fin" id="fecha_fin" title="Fecha" value="<?php echo $fecha_fin;?>" type="text" readonly="readonly" style='width:80px;'>									
                                    <img src="<?php echo img;?>calendario.png" name="Calendario2" id="Calendario2" width="25"  border="0" id="Image1" onMouseOver="this.style.cursor='pointer'" title="Calendario" ALIGN=BASELINE>
                                    <script type="text/javascript">
                                            Calendar.setup({
                                                    inputField     :    "fecha_fin",      // id del campo de texto
                                                    ifFormat       :    "%d/%m/%Y",       // formato de la fecha, cuando se escriba en el campo de texto
                                                    button         :    "Calendario2",   // el id del bot?n que lanzar? el calendario
                                                    onUpdate       :    function(){ 
                                                       $('#tipoexport').val('');
                                                       $("#frmBusqueda").submit();
                                                    }                                            
                                            });
                                    </script>		
                                </span>   
                            </td>                   
                        </tr>   
                        <tr>
                            <td align="left" width="10%" >MONEDA:</td>     
                            <td align="left"><?php echo $selmoneda;?></td>   
                            <td align="left" width="10%"></td>     
                            <td align="left" ></td> 
                            <td align="left"></td>							
                        </tr> 
                    </tbody>
                </table>
               <?php echo $oculto;?>
            </form> 
        </div>
        <div class="case_botones" style="width:85%;border:0px solid #000;text-align: right;float: left;font-family: arial;font-size: 11px;margin-top:4px;">Hora del reporte <?php echo $hora_actual;?></div>
	<div class="case_botones" style="width:15%;border:0px solid #000;float: left;">
            <ul class="lista_botones"><li id="salir">Salir</li></ul>            
            <ul class="lista_botones"><li id="excel" class="rpt_costoot">Ver Excel</li></ul>
<!--            <ul class="lista_botones"><li id="pdf" class="rpt_costoot" >Ver Pdf</li></ul>-->
	</div> 
        <div id="idcontenido" style = "display: table;float:none; width: 100%;border:1px solid #000;height:565px;"> 
            <table border='1' style='width:100%;'>
			<thead>
                <tr align='center' class='cabeceraTabla'>
                    <td>NRO</td>
                    <td>NOMBRE</td>		
                    <td>RESPONSABLE</td>	
                    <td class="pa01"><span>MATERIALES</span></td>
                    <td class="pa02"><span>M.O.<br>DIRECTA</span></td>
                    <td class="pa11"><span>SERVICIOS<br>DIRECTOS</span></td>                            
                    <td class="pa12"><span>TRANSPORTE</span></td>   
                    <td class="pa13" bgcolor='#66FF66'><span><B><FONT COLOR="BLACK">GASTOS</FONT><BR><FONT COLOR="BLACK">TESORERIA</FONT></span></td>
                    <td class="pa14" ><span>CAJA<br>CHICA</span></td>
                    <td>COSTO<br>TOTAL</td>
                </tr>
				</thead>
				
				
                <?php 
                if($fila!=''){
                  
                    /*AQUI GENERA LA GRILLA*/                    
                    echo $fila;    
                    /****/
                  
                }
                else{
                    $colspan = $tipo_reporte=='G'?7:13;
                    ?>
                    <tr>
                        <td colspan="<?php echo $colspan;?>">NO EXISTEN REGISTROS</td>
                    </tr>
                    <?php
                }
                ?>
            </table>              
        </div>    
        <div id="iddetalle" style = "display: none; margin-top: 30px;width: 100%;border:1px solid #000;height:565px;">&nbsp;</div>    
    </div>
</body>
</html>