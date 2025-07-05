
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
	<script src="<?php echo js;?>jquery.js"></script>
	<link rel="stylesheet" href="<?php echo css;?>themes/base/jquery.ui.all.css">
	<script src="<?php echo js;?>jquery/jquery-1.8.2.js"></script>
	<script src="<?php echo js;?>jquery/jquery.ui.core.js"></script>
	<script src="<?php echo js;?>jquery/jquery.ui.widget.js"></script>
	<script src="<?php echo js;?>jquery/jquery.ui.datepicker.js"></script>
	<link rel="stylesheet" href="<?php echo css;?>demos.css">
        <script type="text/javascript" src="<?php echo js;?>blockui.js"></script>
	<script>
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
        
        $(function() {
		$('#fecha_ini').datepicker({
			changeMonth: true,
			changeYear: true
                        
		});
	});
	
          $(function() {
		$('#fecha_fin').datepicker({
			changeMonth: true,
			changeYear: true
		});
	});
	</script>

    <title><?php echo titulo;?></title> 
    <link rel="stylesheet" href="<?php echo css;?>estilos.css" type="text/css">
    <link rel="stylesheet" href="<?php echo css;?>nav.css" type="text/css">
    <link rel="stylesheet" href="<?php echo css;?>theme.css" type="text/css">
       <link rel="stylesheet" href="<?php echo css;?>estilos.css" type="text/css">    
    <script type="text/javascript" src="<?php echo js;?>constants.js"></script> 
    <script type="text/javascript" src="<?php echo js;?>contabilidad/costos.js"></script>
    
  
    <style>
        .tabla_cabecera tr{cursor:pointer;}
    </style>    
    <link rel="stylesheet" href="calendar-win2k-2.css" type="text/css" media="all" title="win2k-cold-1"/>
</head>    
<body>
    <div id="container">
<!--<div title="Click para Cerrar" id="carga" style="cursor:pointer;border-radius:10px;-moz-border-radius:10px;-webkit-border-radius:10px;box-shadow:inset yellow 0px 0px 14px;background-image:url(http://www.puntroma.com/img/ico_cargando.gif);background-position:center;background-size:100%;background-color:#111111;width:300px;color:#fff;text-align:center;height:100px;padding:52px 12px 12px 12px;position:fixed;top:30%;left:40%;z-index:6;">TU TEXTO AQUI</div>
        <?php echo validation_errors("<div class='error'>",'</div>');?>  -->
        <div class="header">REPORTE DE SERVICIOS</div>
        <div class="case_top">
           <form name="frmBusqueda" id="frmBusqueda" method="post">
                <table width="100%" cellspacing="0" cellpadding="3" border="0" >
                    <tbody>
                        <tr>
                            <td align="left" width="10%">OT:</td>     
                            <td align="left">
                                <?php echo $seltipot;?>
                                <input type="text" name="ot" id="ot" style="width: 60px;"  readonly="readonly" class="cajaPequena" value="" onclick="agrega_ot('');">                                
                            </td>   
                            <td align="left" width="10%">TIPO DE OT:</td>     
                            <td align="left"><?php echo $selproducto;?>&nbsp;</td>    
                            <td align="left">
                                General<input type="radio" name="tipo_reporte" id="tipo_reporte" value="G" checked="checked" onclick="ver_general();">
                                Detalle<input type="radio" name="tipo_reporte" id="tipo_reporte" value="D" onclick="ver_detalle();">
                                <input style="cursor:pointer" onclick ="blockui();$('#tipoexport').val('');" type="submit" value="Consultar"></input>                                
                            </td>
                        </tr>  
                        <tr>
                            <td align="left" width="10%">PROYECTO:</td>     
                            <td align="left"><?php echo $selproyecto;?></td>   
                            <td align="left" width="10%">ESTADO:</td>     
                            <td align="left"><?php echo $selestado;?></td>  
                            <td align="left">MONEDA:<?php echo $selmoneda;?></td>
                        </tr> 
                        <tr >
                            <td align="left" width="10%" >FECHA INI.:</td>     
                            <td align="left">
                                <span style="width:500px;border:0px solid #000;" id="Fecha1">
                                    
                                    
                                   <?PHP 
                                     
                                   $xini=substr($fecha_ini,6,4).''.substr($fecha_ini,3,2).''.substr($fecha_ini,0,2);
                                   $xfin=substr($fecha_fin,6,4).''.substr($fecha_fin,3,2).''.substr($fecha_fin,0,2);
                                               
                                   if($xini<=$xfin){
                                      
                                   ?>
                                   <input  onchange=$('#tipoexport').val('');$('#tiporeporte').val('D');$('#frmBusqueda').attr('action',''); name="fecha_ini" id="fecha_ini" title="Fecha" value="<?php echo $fecha_ini;?>" type="text" readonly="readonly" style='width:80px;'>									
                                   <?php }
                                   else{
                                   echo "<script>alert('La Fecha Fin debe ser mayor o igual que la Fecha Inicial *')</script>";   
                                   ?>
                                  <input  onchange=$('#tipoexport').val('');$('#tiporeporte').val('D');$('#frmBusqueda').attr('action','');$('#fecha_ini').val(''); name="fecha_ini" id="fecha_ini" title="Fecha" value="<?php echo $fecha_ini;?>" type="text" readonly="readonly" style='width:80px;'>									
                                   <?php } 
                                                                  
                                   
                                   ?> 
   
                                </span>
                            </td>   
                            <td align="left" width="10%">FECHA FIN:</td>     
                            <td align="left" >
                                <span style="width:500px;border:0px solid #000;" id="Fecha1" >
                                    <input  onchange=$('#tipoexport').val('');$('#tiporeporte').val('D');$('#frmBusqueda').attr('action',''); name="fecha_fin" id="fecha_fin" title="Fecha" value="<?php echo $fecha_fin;?>" type="text" readonly="readonly" style='width:80px;'>									
                                   <!-- <img src="<?php echo img;?>calendario.png" name="Calendario2" id="Calendario2" width="25"  border="0" id="Image1" onMouseOver="this.style.cursor='pointer'" title="Calendario" ALIGN=BASELINE>-->
                                </span>                       
                            </td> 
                            <td align="right">
                             Cantidad: <?php echo $j."<br>";?> 
                            </td>							
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
                    <td>FECHA</td>
                    <td>NRO_DOC</td>		
                    <td>COD_SERVICIO</td>	
                    <td>DESCRIPCION</td>
                    <td>MONTO</td>    
                    <td>NRO_OT</td>
                    <td>ESTADO</td>
                    
                </tr>
		</thead>
                <?php 
                if($fila!=''){              
                    echo $fila;    
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
        <div id="iddetalle" style = "display: none; margin-top: 30px;width: 100%;height:565px;">&nbsp;</div>    
    </div>
</body>
</html>