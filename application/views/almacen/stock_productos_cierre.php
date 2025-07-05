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
    <link rel="stylesheet" href="<?php echo css;?>basic.css" type="text/css">
    <script type="text/javascript" src="<?php echo js;?>constants.js"></script> 
    <script type="text/javascript" src="<?php echo js;?>calendario/calendar.js"></script>
    <script type="text/javascript" src="<?php echo js;?>calendario/calendar-es.js"></script>
    <script type="text/javascript" src="<?php echo js;?>calendario/calendar-setup.js"></script>
    <script type="text/javascript" src="<?php echo js;?>jquery.js"></script>
    <script type="text/javascript" src="<?php echo js;?>almacen/producto.js"></script>
    <script type="text/javascript" src="<?php echo js;?>contabilidad/costos.js"></script>
</head>
<body>
<div id="container">
    <?php echo validation_errors("<div class='error'>",'</div>');?>     
    <div class="header">STOCK DE PRODUCTOS - (CIERRE)</div>
    <div class="case_top">
        <form method="post" id="frmBusqueda">
            <table width="98%" cellspacing="3" cellpadding="3" border="0" style="margin-top:5px;">
                <tbody>
                    <tr>
                        <td align="right" width="18%">Tipo Material</td>
                        <td align="left" width="32%"><?php echo $cboTipoamaterial;?></td>                        
                        <td align="right" width="18%">Linea</td>
                        <td align="left"><?php echo $cboFamilia;?></td>                          
                    </tr>                                   
                    <tr>
                        <td align="right" width="18%">Tipo Almacen</td>
                        <td align="left" width="32%"><?php echo $cboTipoalmacen;?></td>
                        <td align="right" width="18%">Ver todos</td>
                        <td align="left">
                             <div style="float:left; width:40%;"><?php echo $chknegativo;?>&nbsp;&nbsp;&nbsp;&nbsp;Ver precios <?php echo $chkprecio;?></div>
                             <div style="float:left; width:60%;" id="divMoneda"><?php echo $cboMoneda;?></div>                             
                        </td>
                    </tr>
                        <tr >
                            <td align="right" width="10%" >Fecha inicio:</td>
                            <td align="left">
                                <span style="width:500px;border:0px solid #000;" id="Fecha1">
                                    <input  onchange=$('#tipoexport').val('');$("#frmBusqueda").submit();  name="fecha_ini" id="fecha_ini" title="Fecha" value="<?php echo $fecha_ini;?>" type="text" readonly="readonly" style='width:80px;'>
                                  <!--  <input type="text" id="datepicker1" onchange='ver()'>-->

 <!--  onClick="popUpCalendar(this, frmBusqueda.fecha_ini, 'mm/dd/yyyy');"-->
<!-- <img src="<?php echo img;?>calendario.png" name="Calendario1" id="Calendario1" width="25"  border="0" id="Image1" onMouseOver="this.style.cursor='pointer'" title="Calendario" >-->

                                       <!--onUpdate       :    function(){
                                                       $('#tipoexport').val('');
                                                       $("#frmBusqueda").submit();
                                                    }-->

                                </span>
                            </td>
                            <td align="right" width="10%">Fecha termino:</td>
                            <td align="left" >
                                <div style="float:left;width:40%;" id="Fecha1" >
                                    <input  onchange=$('#tipoexport').val('');$("#frmBusqueda").submit(); name="fecha_fin" id="fecha_fin" title="Fecha" value="<?php echo $fecha_fin;?>" type="text" readonly="readonly" style='width:80px;'>
                                   <!-- <img src="<?php echo img;?>calendario.png" name="Calendario2" id="Calendario2" width="25"  border="0" id="Image1" onMouseOver="this.style.cursor='pointer'" title="Calendario" ALIGN=BASELINE>-->

                                </div>
                                <div style="float:left;width:60%;text-align:right;">Cantidad: <?php echo $registros."<br>";?></div>
                            </td>
                        </tr>
                </tbody>
            </table>
            <?php echo $oculto;?>
        </form>
    </div>   
    <div id="idcontenido">    
        <div class="case_botones" style="width:85%;border:0px solid #000;text-align: right;float: left;font-family: arial;font-size: 11px;margin-top:4px;">Hora del reporte <?php echo $hora_actual;?></div>
	<div class="case_botones" style="width:15%;border:0px solid #000;float: left;">
            <ul class="lista_botones"><li id="salir" class="xfacturar">Salir</li></ul>            
            <!--ul class="lista_botones"><li id="grafica" class="xfacturar">Ver Grafica</li></ul--> 
            <ul class="lista_botones"><li id="excel" class="xfacturar">Ver General</li></ul>
<!--            <ul class="lista_botones"><li id="pdf" class="xfacturar">Ver Pdf</li></ul>-->
<!--            <ul class="lista_botones"><li id="html" class="xfacturar">Ver Html</li></ul>-->
            <!--ul class="lista_botones"><li id="atras" class="xfacturar">Inicio</li></ul-->  
	</div> 	
        <div id="idcontenido2">
            <div style="clear:both;text-align:left;">Negativos Transito/Comprometido: <?php echo $negativos;?></div>
            <div style="clear:both;text-align:left;">Negativos Stock: <?php echo $negativos_stock;?></div>
            <div style="clear:both;">
                <table border='1' width='100%'>
                    <thead>
                        <tr align="center" class="cabeceraTabla">
                            <td rowspan='2'>CODIGO</td>
                            <td rowspan='2'>T.ALMACEN</td>
                            <td rowspan='2'>MATERIAL</td>
                            <td rowspan='2'>PRODUCTO</td>	
                            <td rowspan='2'>UNIDAD</td>
                            <td colspan='4' align='center'>CANTIDADES</td>
                            <?php
                            if($checkedprecio){
                                ?>
                                <td colspan='2' align='center'>PRECIO <?=($moneda_doc=='S'?'S/.':'$');?></td>                             
                                <td colspan='2' align='center'>TOTALES <?=($moneda_doc=='S'?'S/.':'$');?></td>                                
                                <?php
                            }    
                            ?>
                        </tr>
                        <tr align="center" class="cabeceraTabla">
                            <td>SALDO INICIAL</td>
                            <td>INGRESOS</td>
                            <td>SALIDAS</td>
                            <td>SALDO FINAL</td>
                            <?php
                            if($checkedprecio){
                                ?>  
                                <td>ULTIMO PRECIO <?=($moneda_doc=='S'?'S/.':'$');?></td>      
                                <td>PRECIO PROM. <?=($moneda_doc=='S'?'S/.':'$');?></td>     
                                <td>TOTAL ULTIMO PRECIO<?=($moneda_doc=='S'?'S/.':'$');?></td>      
                                <td>TOTAL PRECIO PROM. <?=($moneda_doc=='S'?'S/.':'$');?></td>                                   
                                <?php
                            }
                            ?>  
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if($fila==""){
                            echo "<td align='center' colspan='11'>NO EXISTEN REGISTROS</td>";
                        }
                        else{
                            echo $fila;    
                        }
                        ?>
                    </tbody>
                    <?php
                    if($fila!='' && $checkedprecio){
                        ?>
                        <tfoot>
                            <tr>
                                <td align='right' colspan="11">&nbsp;</td>
                                <td align='right'><?php echo number_format($total_precioprod,6);?></td>
                                <td align='right'><?php echo number_format($total_preprom,6);?></td>
                            </tr>
                        </tfoot>                                            
                        <?php
                    }
                    ?>
                </table>
                <br/><br/>
            </div>
        </div>
        <div id="iddetalle"></div>
    </div>          
</div>	       
</body>
</html>
