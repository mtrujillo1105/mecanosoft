<!DOCTYPE html>
<html>
    <head>
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
        <title><?php echo titulo;?></title>
        
    </head>
    <body>
        <div class="container">
            <div class="header">DISTRIBUCION DE GASTOS POR CONCEPTO</div>
            <div class="case_top2">
                <form method="post" id="frmPlanilla">
                    <input type="hidden" id="tipoexcel" name="tipoexcel" value="" />
                    <table width="98%" cellspacing="0" cellpadding="3" border="0" class="fuente8" align="center">
                        <tbody>
                            <tr>
                                <td align="right" width="10%">A&ntilde;o: </td>
                                <td align="left" width="32%"><?php echo $selanio;?></td>
                                <td align="left" width="26%">Mes:&nbsp;&nbsp;<?php echo $selmes;?></td>
                                <td align="left">Tipo trabajador:&nbsp;<?php echo $seltrabajador;?></td>                                
                            </tr>   
                            <tr>
                                <td align="right" width="10%">Centro costo:</td>
                                <td align="left" width="32%"><?php echo $selccosto_conta;?></td>
                                <td align="left" width="26%">Area:&nbsp;<?php echo $selccosto;?></td>
                                <td align="left">Concepto:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $selconcepto;?></td>
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
                        <li id="01"><a href="#tab1">Consolidado</a></li>
                        <li id="02"><a href="#tab2">Detalle</a></li>
                    </ul>
                    <div class="tab_container">
                        <div class="container">
                            <div id="tab1" class="tab_content">
                                <div class="case_botones">      
                                    <ul class="lista_botones excel"><li id="excel" class="consolidado_concepto">Ver Excel</li></ul>
                                    <br/><br/><br/>
                                    <div style="text-align: left">
                                       
                                                    <table width="100%" border="1">
                                                        <tr>
                                                            <th>CONCEPTOS</th>
                                                            <th>C.COSTOS</th>
                                                            <th>AREA</th>
                                                            <th>TIPO</th>
                                                            <th>MONTO S/.</th>
                                                        </tr>
                                                        <?php 
                                                        if($fila!=""){
                                                            echo $fila;    
                                                        }
                                                        else{
                                                            ?>
                                                            <tr>
                                                                <td colspan="5" align="center">NO EXISTEN REGISTROS</td>
                                                            </tr>                                                            
                                                            <?php
                                                        }
                                                        ?>
                                                    </table>
                                        
                                    </div>
                                </div>
                            </div>
                            <div id="tab2" class="tab_content">
                                <div class="case_botones">
                                    <ul class="lista_botones excel"><li id="excel" class="detalle_concepto">Ver Excel</li></ul>
                                    <br/><br/><br/>
                                    <table width="100%">
                                        <tr>
                                            <th>Conceptos</th>
                                            <th>C.Costo</th>
                                            <th>Area</th>
                                            <th>Personal</th>
                                            <th>Tipo</th>
                                            <th>Valor</th>
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
