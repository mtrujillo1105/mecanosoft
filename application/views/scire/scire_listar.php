<!DOCTYPE html>
<html>
    <head>
        <script>
        base_url = "<?php echo base_url(); ?>"
        </script>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="<?php echo css;?>estilos.css" type="text/css">
        <link rel="stylesheet" href="<?php echo css;?>nav.css" type="text/css">
        <script type="text/javascript" src="<?php echo js;?>jquery-1.9.1.js"></script>
        <script type="text/javascript" src="<?php echo js;?>jquery-ui.js"></script>
        <link rel="stylesheet" href="<?php echo css;?>theme.css" type="text/css">
        <link rel="stylesheet" href="<?php echo css;?>calendario/calendar-win2k-2.css" type="text/css" media="all" title="win2k-cold-1"/>
        <script type="text/javascript" src="<?php echo js;?>jquery.js"></script>
        <script type="text/javascript" src="<?php echo js;?>scire/planillas.js"></script>
        <title><?php echo titulo;?></title>
    </head>
    <body>
        <div class="container">
            <div class="header">PLANILLA <?php echo($param == "e")?'MENSUAL EMPLEADO':'SEMANAL OBRERO'; ?></div>
            <div class="case_top2">
                <form method="post" id="frmPlanilla">
                    <input type="hidden" id="tipoexcel" name="tipoexcel" value="" />
                    <table width="98%" cellspacing="0" cellpadding="3" border="0" class="fuente8" align="center">
                        <tbody>
                            <tr>
                                <td align="right" width="10%">Tipo Planilla:</td>
                                <td align="left" width="32%">
                                    <?php echo $selplanilla;?>&nbsp;                                     
                                    Anio: <?php echo $selanio;?>&nbsp;                               
                                </td>
                                <td align="left" width="26%">
                                    Periodo: <?php echo $selperiodo;?>&nbsp;
                                </td>
                                <td align="left">
                                    Proceso:
                                    <?php echo $selproceso; ?>
                                </td>
                            </tr>   
                            <tr>
                                <td align="right" width="10%">Centro costo</td>
                                <td align="left" width="32%"><?php echo $selccosto_conta;?></td>
                                <td align="left" width="26%">Area:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $selccosto;?></td>
                                <td align="left">&nbsp;</td>
                            </tr> 
                        </tbody>
                    </table>
                    <input type="hidden" name="txt_report" id="txt_report" value='per_consolidado'>
                </form>            
            </div>
            <div class="case_botones">
                
                <ul class="lista_botones"><li id="salir" class="salir">Salir</li></ul>
                <ul class="lista_botones"><li id="html" class="html">Ver Html</li></ul>
                <ul class="lista_botones excel"><li id="excel" class="planillas_periodo">Exportar</li></ul>
            </div>            
            <div id="bg_pagos" class="div_fondo">
                <div class="case_middle">
                    <ul class="tabs">
                        <li id="per_consolidado"><a href="#tab1">Consolidado</a></li>
                        <li id="per_nopla"><a href="#tab2"><?=($param=="e"?"RHP":"No planilla");?></a></li>
                        <li id="per_planilla"><a href="#tab3">Planilla</a></li>
                        <li id="per_difpla"><a href="#tab4">Diferencia Planilla</a></li>
                        <li id="per_efectivo"><a href="#tab5">Pago en Efectivo</a></li>
                        <li id="per_detalle"><a href="#tab6">Planilla Detalle</a></li>
                    </ul>
                    <div class="tab_container">
                        <div class="container">
                            <div id="tab1" class="tab_content">
                                <div class="case_botones">      
                                    <legend><font color='#ff0000'><b><?php echo $warning;?></b></font></legend>
<!--                                    <ul class="lista_botones"><li id="" class="control">Ver Excel</li></ul>-->
                                    <br/>
                                    <div style="text-align: left">
                                        <fieldset>
                                            <legend>TRANSFERENCIA PARA ONLINE</legend>
                                            <ol>
                                                <li>
                                                    TRANSFERENCIA EN PLANILLA&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    &nbsp;&nbsp;
                                                    <label><?php if(isset($online)) echo "S/. " . number_format($online,2) ?></label>
                                                </li>
                                                <br/>
                                                <ul>
                                                    <li>
                                                        SUBTOTAL DE TRANSFERENCIAS PAGO ONLINE&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        <input type="text" readonly="" value="<?php if(isset($online)) echo number_format($online,2) ?>" />
                                                        
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        <button class="btn_exportar" id="consolidado_planilla">Excel</button>
                                                        <?php if($param != "so"){ ?>
                                                        <img class="export_bank" bank="bbva" src="<?php echo base_url(); ?>/img/grabar.png" alt="Exportar a Banco" title="Exportar a Banco" style="cursor:pointer; margin-left: 20px;"></img>
                                                        <?php } ?>
                                                    </li>
                                                </ul>
                                            </ol>
                                        </fieldset>
                                        <fieldset>
                                            <legend>CHEQUE PARA ABONO</legend>
                                            <ol>
                                                <li>
                                                    ABONO DIFERENCIA PLANILLA&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    &nbsp;&nbsp;
                                                    <label><?php if(isset($consolidado_abono_dif_planilla)) echo "S/. " . number_format($consolidado_abono_dif_planilla,2) ?></label>
                                                </li>
                                                <li>
                                                    ABONO 
                                                    <?php if($param == 'o'): ?>
                                                        NO PLANILLA &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    <?php else: ?>
                                                        RECIBO POR HONORARIO &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    <?php endif; ?>
                                                        <label><?php if(isset($consolidado_abono_no_planilla)) echo "S/. " . number_format ($consolidado_abono_no_planilla,2) ?></label>
                                                </li>
                                                <br/>
                                                <ul>
                                                    <li>
                                                        SUBTOTAL DE CHEQUE PARA ABONO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        <input type="text" readonly="" value="<?php if(isset($abono)) echo number_format ($abono,2) ?>" />
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        <button class="btn_exportar" id="btn_diferencia_planilla">Excel</button>
                                                    </li>
                                                </ul>
                                            </ol>
                                        </fieldset>
                                        <fieldset>
                                            <legend>CHEQUE</legend>
                                            <ol>
                                                <li>
                                                    PAGO EN EFECTIVO SIN CTA&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                                    <label><?php if(isset($cheque)) echo "S/. " . number_format ($cheque) ?></label>
                                                </li>
                                                <br/>
                                                <ul>
                                                    <li>
                                                        SUBTOTAL DE CHEQUE&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        <input type="text" readonly="" value="<?php if(isset($cheque)) echo number_format($cheque,2) ?>" />
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        <button class="btn_exportar" id="subtotal_cheque">Excel</button>
                                                    </li>
                                                </ul>
                                            </ol>
                                        </fieldset>
                                        <fieldset>
                                            <legend>TOTAL PLANILLA</legend>
                                            <ol>
                                                <ul>
                                                    <li>
                                                        TOTAL PLANILLA&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        &nbsp;&nbsp;
                                                        <input type="text" readonly="" value="<?php if(isset($totalplanilla)) echo number_format($totalplanilla,2) ?>" />
                                                    </li>
                                                </ul>
                                            </ol>
                                        </fieldset>
                                    </div>
                                </div>
                            </div>
                            <div id="tab2" class="tab_content">
                                
                                    <table width="100%">
                                        <tr>
                                            <th>Nro</th>
                                            <th>DNI</th>
                                            <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                Personal
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            </th>
                                            <th>Importe S/.</th>
                                            <th>Nro de Cuenta</th>
                                            
                                        </tr>
                                        <?php if ($fila2 != ""): ?>
                                            <?php echo $fila2; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="13" align="center">NO EXISTEN REGISTROS</td>
                                            </tr>
                                        <?php endif; ?>
                                    </table>
                            
                            </div>
                            <div id="tab3" class="tab_content">
                             
                                    <table width="100%">
                                        <tr>
                                            <th>Nro</th>
                                            <th>DNI</th>
                                            <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                Apellidos y Nombres
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            </th>
                                            <th>Importe S/.</th>
                                            <th>Nro de Cuenta</th>
                                            
                                        </tr>
                                        <?php if ($fila3 != ""): ?>
                                            <?php echo $fila3; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="13" align="center">NO EXISTEN REGISTROS</td>
                                            </tr>
                                        <?php endif; ?>
                                    </table>
                            
                            </div>
                            <div id="tab4" class="tab_content">
                                
                                    <table width="100%">
                                        <tr>
                                            <th>Nro</th>
                                            <th>DNI</th>
                                            <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                Apellidos y Nombres
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            </th>
                                            <th>Importe S/.</th>
                                            <th>Nro de Cuenta</th>
                                            
                                        </tr>
                                        <?php if ($fila4 != ""): ?>
                                            <?php echo $fila4; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="13" align="center">NO EXISTEN REGISTROS</td>
                                            </tr>
                                        <?php endif; ?>
                                    </table>
                              
                            </div>
                            <div id="tab5" class="tab_content">
                                
                                    <table width="100%">
                                        <tr>
                                            <th>Nro</th>
                                            <th>DNI</th>
                                            <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                Apellidos y Nombres
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            </th>
                                            <th>Importe S/.</th>
                                            <th>Tipo</th>
                                            
                                        </tr>
                                        <?php if ($fila5 != ""): ?>
                                            <?php echo $fila5; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="13" align="center">NO EXISTEN REGISTROS</td>
                                            </tr>
                                        <?php endif; ?>
                                    </table>
                             
                            </div>
                            <div id="tab6" class="tab_content">
                                    <table>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td colspan="15" style='background-color: #FBFEB2'>REMUNERACIONES</td>
                                            <td></td>
                                            <td colspan="13" style='background-color: #FBFEB2'>DESCUENTOS</td>
                                            <td></td>
                                            <td colspan="4" style='background-color: #FBFEB2'>APORTES</td>
                                            <td></td>
                                            <td style='background-color: #FBFEB2' colspan="3">TOTALES</td>
                                        </tr>
                                        <tr>
                                            <th>NRO</th>
                                            <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                Personal
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            </th>
                                             <th>Area</th>
                                            <th>Basico</th>
                                            <th>Asignacion Fam.</th>
                                            <th>Reintegro</th>
                                            <th>Reintegro Inafecto</th>
                                            <th>Bonif. Extraordinaria</th>
                                            <th>Basico-No-Trib</th>
                                            <th>Ingreso 4TA</th>
                                            <th>Movilidad</th>
                                            <th>Viaticos</th>
                                            <th>Vale Alimento</th>
                                            <th>H. Extras S/. </th>
                                            <th>H. Doble S/. </th>                                                
                                            <th>Grati. Semestral S/. </th>   
                                            <th>Bonif. Extraordinaria L29351 S/. </th>   
                                            <th style='background-color: #B0FAB2'>Total S/.</th>
                                            <th>Tardanza</th>
                                            <th>ONP</th>
                                            <th>AFP Fondo</th>
                                            <th>AFP Comis Variable</th>
                                            <th>AFP Comis Mixta</th>
                                            <th>AFP Seguro</th>
                                            <th>Retencion 5ta/4ta</th>
                                            <th>Adelanto Quincena</th>
                                            <th>Prestamo Personal</th>
                                            <th>Dscto Comedor</th>
                                            <th>Dscto 4ta</th>
                                            <th>Dscto Adicional</th>
                                            <th>Dscto EPS</th>
                                            <th style='background-color: #B0FAB2'>Total S/.</th>
                                            <th>ESSALUD</th>
                                            <th>SENATI</th>
                                            <th>SCTR Salud</th>
                                            <th>SCTR Pension</th>
                                            <th style='background-color: #B0FAB2'>Total S/.</th>
                                            <th style='background-color: #CEF6F5'>Neto Remun S/.</th>
                                            <th style='background-color: #CEF6F5'>Neto Fuera S/.</th>
                                            <th style='background-color: #CEF6F5'>Fuera + Remun S/.</th>
                                        </tr>
                                        <?php if($fila!=""): ?>
                                            <?php echo $fila; ?>
                                        <?php else: ?>
                                        <tr>
                                            <td colspan="17" align="center">NO EXISTEN REGISTROS</td>
                                        </tr>
                                        <?php endif; ?>
                                    </table>
                                
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
        $("#bg_pagos").height(doc-160);
        $(".tab_content").height(doc-240);
        $(".tabs").css('margin-top','10px');
    }

    $(window).ready(function(){

        $('ul.tabs li').click(function(e){ 
            $('#txt_report').val($(this).attr('id'));
        });

        ajust();
        $(".excel").css("display","none");
        $(".export_bank").css("display","none");

        <?

        if($view_export>0){
           ?>
           $(".excel").css("display","block");
           $(".export_bank").css("display","block");
           <? 
        }
        ?> 
    });
    </script>
</html>

