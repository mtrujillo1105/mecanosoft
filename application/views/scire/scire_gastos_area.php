<!DOCTYPE html>
<html>
    <head>
        <title><?php echo titulo;?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="<?php echo css;?>estilos.css" type="text/css">
        <link rel="stylesheet" href="<?php echo css;?>nav.css" type="text/css">
        <script type="text/javascript" src="<?php echo js;?>jquery-1.9.1.js"></script>
        <script type="text/javascript" src="<?php echo js;?>jquery-ui.js"></script>
        <link rel="stylesheet" href="<?php echo css;?>theme.css" type="text/css">
        <link rel="stylesheet" href="<?php echo css;?>calendario/calendar-win2k-2.css" type="text/css" media="all" title="win2k-cold-1"/>
        <script type="text/javascript" src="<?php echo js;?>jquery.js"></script>
        <script type="text/javascript" src="<?php echo js;?>constants.js"></script>
        <script type="text/javascript" src="<?php echo js;?>scire/planillas.js"></script>
        
    </head>
    <body>
        <div class="container">
            <div class="header">DISTRIBUCION DE GASTOS POR AREA</div>
            <div class="case_top2">
                <form method="post" id="frmPlanilla">
                    <input type="hidden" id="tipoexcel" name="tipoexcel" value="" />
                    <table width="98%" cellspacing="0" cellpadding="3" border="0" class="fuente8" align="center">
                        <tbody>
                            <tr>
                                <td align="right" width="10%">A&ntilde;o:</td>
                                <td align="left" width="32%"><?php echo $selanio;?></td>
                                <td align="left" width="26%">Mes:&nbsp;&nbsp;<?php echo $selmes;?></td>
                                <td align="left">Tipo trabajador: <?php echo $seltrabajador;?></td>                                
                            </tr>   
                            <tr>
                                <td align="right" width="10%">Centro costo:</td>
                                <td align="left" width="32%"><?php echo $selccosto_conta;?></td>
                                <td align="left" width="26%">Area: <?php echo $selccosto;?></td>
                                <td align="left">
                                </td>
                            </tr> 
                        </tbody>
                    </table>
                </form>            
            </div>
            <div class="case_botones">
                <ul class="lista_botones"><li id="salir" class="salir">Salir</li></ul>
                <ul class="lista_botones"><li id="html" class="html">Ver Html</li></ul>                                        
            </div>            
            <div class="div_fondo">
                <div class="case_middle">
                    <ul class="tabs">
                        <li id="01"><a href="#tab1">Consolidado por Ccosto</a></li>
                        <li id="02"><a href="#tab2">Consolidado por Area</a></li>
                        <li id="03"><a href="#tab3">Detalle</a></li>
                    </ul>
                    <div class="tab_container">
                        <div class="container">
                            <div id="tab1" class="tab_content">
                                <div>    
                                    <ul class="lista_botones excel"><li id="excel" class="consolidado_area">Ver Excel</li></ul>
                                    <br/><br/><br/>
                                    <div style="text-align: left">
                                        <table width="100%" border="1">
                                            <tr>
                                                <th width="18%">C.COSTOS</th>
                                                <th width="18%">CONCEPTOS</th>
                                                <th width="20%">MONTO S/.</th>
                                            </tr>
                                            <?php 
                                            if($fila3!=""){
                                                echo $fila3;    
                                            }
                                            else{
                                                ?>
                                                <tr>
                                                    <td colspan="3" align="center">NO EXISTEN REGISTROS</td>
                                                </tr>                                                            
                                                <?php
                                            }
                                            ?>
                                        </table>
                                    </div>
                                </div>                                
                            </div>
                            <div id="tab2" class="tab_content">                            
                                <div>    
                                    <ul class="lista_botones excel"><li id="excel" class="consolidado_area">Ver Excel</li></ul>
                                    <br/><br/><br/>
                                    <div style="text-align: left">
                                        <table width="100%" border="1">
                                            <tr>
                                                <th width="18%">C.COSTOS</th>
                                                <th width="44%">AREA</th>
                                                <th width="18%">CONCEPTOS</th>
                                                <th width="20%">MONTO S/.</th>
                                            </tr>
                                            <?php 
                                            if($fila!=""){
                                                echo $fila;    
                                            }
                                            else{
                                                ?>
                                                <tr>
                                                    <td colspan="4" align="center">NO EXISTEN REGISTROS</td>
                                                </tr>                                                            
                                                <?php
                                            }
                                            ?>
                                        </table>
                                    </div>
                                </div>
                            </div>                           
                            <div id="tab3" class="tab_content">
                                <div class="case_botones">
                                    <ul class="lista_botones excel"><li id="excel" class="detalle_area">Ver Excel</li></ul>
                                    <br/><br/><br/>
                                    <table width="100%">
                                        <tr>
                                            <th colspan="4">&nbsp;</th>
                                            <th colspan="10" style='background-color: #FBFEB2'>REMUNERACIONES</th>
                                            <th>&nbsp;</th>
                                            <th colspan="11" style='background-color: #FBFEB2'>DESCUENTOS</th>
                                            <th>&nbsp;</th>
                                            <th colspan="4" style='background-color: #FBFEB2'>APORTES</th>
                                            <th>&nbsp;</th>
                                            <th colspan="4" style='background-color: #FBFEB2'>TOTALES</th>
                                        </tr>
                                        <tr>
                                            <th>Nro</th>
                                            <th>Tipo</th>
                                            <th>Personal</th>
                                            <th>Ccosto</th>
                                            <th>Basico</th>
                                            <th>Desc. semanal</th>
                                            <th>Reintegro</th>
                                            <th>Reintegro<br>Inafecto</th>
                                            <th>Basico notrib</th>
                                            <th>Asig.Familiar</th>
                                            <th>Bonif.<BR>extraordinaria</th>
                                            <th>H.Extra</th>
                                            <th>H.Doble</th>
                                            <th>Ing 4ta</th>
                                            <th>TOTALs S/.</th>
                                            <th>Tardanza</th>
                                            <th>ONP</th>
                                            <th>AFP FONDO</th>
                                            <th>AFP COMISION</th>
                                            <th>AFP SEGURO</th>
                                            <th>Retencion<br>5ta/4ta</th>
                                            <th>Adelanto quincena</th>                                            
                                            <th>PRESTAMO<br>PERSONAL</th>
                                            <th>DSCTO COMEDOR</th>
                                            <th>DSCTO 4TA</th>
                                            <th>DSCTO ADICIONAL</th>
                                            <th>TOTAL S/.</th>
                                            <th>ESSALUD</th>
                                            <th>SENATI</th>
                                            <th>SCTR Salud</th>
                                            <th>SCTR Pension</th>
                                            <th>TOTAL S/.</th>
                                            <th style='background-color: #CEF6F5'>Neto Remun S/.</th>
                                            <th style='background-color: #CEF6F5'>Neto Fuera S/.</th>
                                            <th style='background-color: #CEF6F5'>Neto Trabajdor S/.</th>
                                            <th style='background-color: #CEF6F5'>Trabajdor + Fuera S/.</th>                                            
                                        </tr>
                                        <?php if ($fila2 != ""): ?>
                                            <?php echo $fila2; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="24" align="center">NO EXISTEN REGISTROS</td>
                                            </tr>
                                        <?php endif; ?>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
	<script>
	function ajust(){
            doc = $(window).height();
            $(".div_fondo").height(doc-160);
            $(".tab_content").height(doc-240);
            $(".tabs").css('margin-top','10px');
	}
	
	$(window).ready(function(){
            $(".excel").css("display","none");
            <?
            if($export>0){
               ?>$(".excel").css("display","block");<? 
            }
            ?>
		ajust();
	});
	</script>
</html>
