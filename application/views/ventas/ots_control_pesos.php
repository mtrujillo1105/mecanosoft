<!DOCTYPE html>
<html>
<head>
    <title><?php echo titulo;?></title> 
    <link rel="stylesheet" href="<?php echo css;?>estilos.css" type="text/css">
    <link rel="stylesheet" href="<?php echo css;?>nav.css" type="text/css">
    <link rel="stylesheet" href="<?php echo css;?>theme.css" type="text/css">
    <link rel="stylesheet" href="<?php echo css;?>calendario/calendar-win2k-2.css" type="text/css" media="all" title="win2k-cold-1"/>
    <link rel="stylesheet" href="<?php echo css;?>basic.css" type="text/css">
    <link rel="stylesheet" href="<?php echo css;?>estilos.css" type="text/css">
    <script type="text/javascript" src="<?php echo js;?>constants.js"></script> 
    <script type="text/javascript" src="<?php echo js;?>calendario/calendar.js"></script>
    <script type="text/javascript" src="<?php echo js;?>calendario/calendar-es.js"></script>
    <script type="text/javascript" src="<?php echo js;?>calendario/calendar-setup.js"></script>
    <script type="text/javascript" src="<?php echo js;?>jquery.js"></script>
    <script type="text/javascript" src="<?php echo js;?>jquery.simplemodal.js"></script>    
    <script type="text/javascript" src="<?php echo js;?>ventas/ot.js"></script>
    <script type="text/javascript" src="<?php echo js;?>contabilidad/reportes.js"></script>
    <script>
    function ajust(){
        doc = $(window).height();

        $("#table").height(doc-210);
        $("#iddetalle").height(doc-160);
        $("#idcontenido").height(doc-210);
    }
    $(window).ready(function(){
        ajust();
    });
    </script>    
</head>
<body onload="$('#basic-modal-content').modal();">
    <div id="container">
    <?php echo validation_errors("<div class='error'>",'</div>');?>          
        <div class="header">CONTROL DE PESOS</div>	
	<div class="case_top2">
            <form method="post" id="frmBusqueda">
                <table width="98%" cellspacing="3" cellpadding="3" border="0" style="margin-top:5px;">
                    <tbody>
                        <tr>
                            <td align="right" width="15%">Tipo O.T.:</td>
                            <td align="left" width="32%"><?php echo $periodoOt;?></td>                        
                            <td align="right" width="15%"><span>Desde:</span></td>
                            <td align="left" width="38%">
                                <span id="Fecha1" style="width:150px;border:0px solid #000;float: left;">
                                    <input  name="fecha_ini" id="fecha_ini" title="Fecha Inicio" value="<?php echo $fInicio;?>" type="text" class="cajaPequena" readonly="readonly">									
                                    <img src="<?php echo img;?>calendario.png" name="Calendario1" id="Calendario1" width="16" height="16" border="0" id="Image1" onMouseOver="this.style.cursor='pointer'" title="Calendario">
                                    <script type="text/javascript">
                                        Calendar.setup({
                                            inputField     :    "fecha_ini",      // id del campo de texto
                                            ifFormat       :    "%d/%m/%Y",       // formato de la fecha, cuando se escriba en el campo de texto
                                            button         :    "Calendario1"   // el id del bot�n que lanzar� el calendario
                                        });
                                    </script>		
                                </span>
                                <span style="float:left;">Hasta:</span>
                                <span id="Fecha2" style="width:150px;border:0px solid #000;display:block;float: left;">
                                    <input  name="fecha_fin" id="fecha_fin" title="Fecha Inicio" value="<?php echo $fFin;?>" type="text" class="cajaPequena" readonly="readonly">									
                                    <img src="<?php echo img;?>calendario.png" name="Calendario2" id="Calendario2" width="16" height="16" border="0" id="Image1" onMouseOver="this.style.cursor='pointer'" title="Calendario">
                                    <script type="text/javascript">
                                        Calendar.setup({
                                            inputField     :  "fecha_fin",      // id del campo de texto
                                            ifFormat       :  "%d/%m/%Y",       // formato de la fecha, cuando se escriba en el campo de texto
                                            button         :  "Calendario2",   // el id del bot�n que lanzar� el calendario
                                            onChange        :  function(){
                                                alert('Bumerang');
                                            }
                                        });
                                    </script>	                              
                                </span>	                            
                            </td>                          
                        </tr>                        
                        <tr>
                            <td align="right" width="15%">Proyecto</td>   
                            <td align="left" width="32%"><?php echo $cboProyecto;?></td>
                            <td align="right" width="15%">Tipo producto</td>
                            <td align="left" width="38%">
                                <?php echo $cboTipoprod;?>
                                Estado: <?php echo $cboEstado;?>
                                <span style='display:none'><?php echo $selmoneda;?></span>                              
                            </td>            
                        </tr>                                      
                    </tbody>
                </table>
                <?php echo $oculto;?>
            </form>
	</div>
        <div class="case_botones" style="width:80%;border:0px solid #000;text-align: right;float: left;font-family: arial;font-size: 11px;margin-top:4px;">Registros: <?php echo $registros;?></div>
        <div class="case_botones" style="width:20%;border:0px solid #000;float: left;">
            <ul class="lista_botones"><li id="salir" class="control_pesos">Salir</li></ul>            
            <ul class="lista_botones"><li id="excel" class="control_pesos">Ver Excel</li></ul>
<!--                <ul class="lista_botones"><li id="pdf" class="control_pesos">Ver Pdf</li></ul>-->
            <ul class="lista_botones"><li id="html" class="control_pesos">Ver Html</li></ul>  
            
        </div>         
        <div id="idcontenido" style ="display: table;float:none; width: 100%;">
            <?php
            if($fInicio!="" && $fFin!="" && $codperiodo!=""){
            ?>
            <div style = "height:40px; width:100%;border:0px solid #000;">
                <table border='1' style='width:100%;'>
                    <thead>
                        <tr style="background:#8AA8F3;">
                            <td style='width:8%;'><div>NRO OT</div></td>
                            <td style='width:10%;'><div>NOMBRE</div></td>
                            <td style='width:10%;'><div>PROYECTO</div></td>
                            <td style='width:8%;'><div>TIPO<BR>PRODUCTO</div></td>
                            <td style='width:8%;'><div>FECHA<br>INICIO</div></td>
                            <td style='width:8%;'><div>FECHA<br>TERMINO</div></td>
                            <td style='width:8%;'><div>W.REQUERIDO<BR>(KG)</div></td>						
                            <td style='width:8%;'><div>W.PPTO.<BR>(KG)</div></td>
<!--                            <td style='width:7%;'><div>W.METRADO.<BR>(KG)</div></td>-->
                            <td style='width:8%;'><div>W.O.TECNICA<BR>(KG)</div></td>						                       
                            <td style='width:8%;'><div>W.GALVANIZADO<BR>(KG)</div></td>
                            <td style='width:8%;'><div>W.PRODUCCION<BR>(KG)</div></td>
                            <td style='width:8%;'><div>W.ALMACEN<BR>(KG)</div></td>
                        </tr>
                    </thead>
                </table>
            </div>     
            <style>
            .ajustar{
            width: 100px;
            float: left;
            white-space: pre; /* CSS 2.0 */
            white-space: pre-wrap; /* CSS 2.1 */
            white-space: pre-line; /* CSS 3.0 */
            white-space: -pre-wrap; /* Opera 4-6 */
            white-space: -o-pre-wrap; /* Opera 7 */
            white-space: -moz-pre-wrap; /* Mozilla */
            white-space: -hp-pre-wrap; /* HP */
            word-wrap: break-word; /* IE 5+ */
            }
            </style>            
            <div id="table" style="margin-top:5px;border:0px solid #000;overflow: scroll;">                       
                <div style="border:0px solid #000;">
                    <table border='1' width='100%'>
                        <tbody>
                            <?php 
                            if($fila!=''){              
                                echo $fila;    
                            }
                            else{
                            ?>
                            <tr>
                            <td colspan="7">NO EXISTEN REGISTROS</td>
                            </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                    <div style='clear:both;'><!-- fix --></div>
                </div>
            </div>
            <?php }?>
        </div>
        <div id="iddetalle" style = "display:none; margin-top: 0px;width: 100%;z-index:1;">&nbsp;</div>
    </div>
    <!--Empiezan los modales-->
    <div id="basic-modal-content"><?php echo $mensaje;?></div>      
</body>
</html>