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
    <script type="text/javascript" src="<?php echo js;?>jquery.simplemodal.js"></script>
    <script type="text/javascript" src="<?php echo js;?>almacen/producto.js"></script>
</head>
<body onload="$('#basic-modal-content').modal();">
<div id="container">
    <?php echo validation_errors("<div class='error'>",'</div>');?>     
    <div class="header">STOCK DE PRODUCTOS - (INCLUYE TRANSITO Y COMPROMETIDO)</div>	
    <div class="case_top2">
        <form method="post" id="frmBusqueda">           
            <table width="98%" cellspacing="3" cellpadding="3" border="0" style="margin-top:5px;">
                <tbody>
                    <tr>
                        <td align="right" width="18%">Almacen</td>
                        <td align="left" width="32%"><?php echo $cboTipoalmacen;?></td>
                        <td align="right" width="18%">Linea</td>
                        <td align="left"><?php echo $cboFamilia;?></td>                          
                    </tr>                                   
                    <tr>
                        <td align="right" width="18%">Tipo Material</td>
                        <td align="left" width="32%"><?php echo $cboTipoamaterial;?></td>
                        <td align="right" width="18%"></td>
                        <td align="left">Cantidad:<?php echo $zz;?></td>                          
                    </tr>                                    
                </tbody>
            </table>
            <?php echo $oculto;?>
        </form>
    </div>   
    <div id="idcontenido">    
	<div class="case_botones">
            <ul class="lista_botones"><li id="salir" class="xfacturar">Salir</li></ul>            
            <!--ul class="lista_botones"><li id="grafica" class="xfacturar">Ver Grafica</li></ul--> 
            <ul class="lista_botones"><li id="excel" class="xfacturar">Ver Excel</li></ul>
<!--            <ul class="lista_botones"><li id="pdf" class="xfacturar">Ver Pdf</li></ul>-->
            <ul class="lista_botones"><li id="html" class="xfacturar">Ver Html</li></ul>
            <!--ul class="lista_botones"><li id="atras" class="xfacturar">Inicio</li></ul-->  
	</div> 	
        <div id="idcontenido2">
            <div style="text-align:left;float:left;width:80%;font-size:13px;margin-top:10px;">
                <h3>
                    REPORTE STOCK DE PRODUCTOS<br>
                    Fecha Cierre: <?php echo $feccierre;?>
                </h3>
            </div>
            <div style="float:left;width:20%;font-size:13px;margin-top:10px;"></div>
            <div style="clear:both;padding-top:5px;">
                <table border='1' width='100%'>
                    <thead>
                        <tr align="center" class="cabeceraTabla">
                            <td>CODIGO</td>
                            <td>T.ALMACEN</td>		
                            <td>PRODUCTO</td>	
<!--                            <td>STOCK MINIMO</td>
                            <td>STOCK MAXIMO</td>-->
                            <td>STOCK ACTUAL</td>
                            <td>STOCK CIERRE</td>
                            <td>STOCK COMPROM</td>
                            <td>STOCK TRANS</td>		
                            <td>STOCK DISPONIBLE</td>
                            <td>PRECIO</td>
                            <td>PRECIO PROM.</td>
                            <td>PRECIO<BR> CIERRE</td>
                            <td>PRECIO PROM<BR>CIERRE</td>                            
                        </tr>
                    </thead>
                        <tbody><?php echo $fila;?></tbody>
                    <tfoot>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                    </tfoot>
                </table>
                <br/><br/>
            </div>
        </div>
    </div> 
    <!--script>alert("Proceso finalizado");</script-->          
</div>	    
<!-- modal content -->
<div id="basic-modal-content">
    <h3>Pendientes</h3>
    <p><strong>Ordenes de Compra pendientes</strong></p>
    <?php echo $mensaje;?>
</div>     
</body>
</html>