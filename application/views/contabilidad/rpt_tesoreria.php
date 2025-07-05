<?php
if($verencabezado!='N'){
    if($nroOt!=""){
    ?>
<!--    <div style="float:left;width:30%;text-align:left;">-->
    <div style='width:100%;float: left;text-align: left;height: 22px;border: 0px solid #000;font-size: 15px;font-weight: bold;'>&nbsp;        
        O.T.: <?php echo $nroOt."-".$dirOt;?>
    </div>
    <div style="width:60%;float:left;text-align: left;height: 30px;border:0px solid #000;font-size: 15px;font-weight: bold;">        
        &nbsp;PARTIDA ::: TESORERIA
    </div>
    <?php
    }
    ?>
    <!--input type="text" name="codpartida" id ="codpartida" value="< ? echo $var_codpartida;?>"-->    
<!--    <div style="float: right;width: 20%;">-->
    <div style="width:20%;float:RIGHT;border:0px solid #000;height: 30px;margin-top: -15px;" class="case_botones" id="<?php echo $codot;?>">        
        <a href='#' onclick="$('#idcontenido').show();$('#excel.rpt_costoot').css('display','');$('#iddetalle').hide();"><img src='<?php echo img;?>atras.png' width='40' height='40' border='0' title='Regresar' alt='Dont Picture'></a>
        <ul class="lista_botones" id="<?php echo $var_codpartida;?>"><li id="excel" class="ot_listar2" onclick="rpt_tesoreria(this);">Ver Excel</li></ul>        
    </div>
<!--   <div style="float: left;width: 30%;">
        < ?php
        if($nroOt!="" && trim($codpartida)!=""){
        ?>
            <table width="40%" border="1">
                <tr align="center">
                    <td>&nbsp;</td>
                    <td>Presupuestado</td>
                    <td>Modificado</td>
                    <td>Consumido</td>
                    <td>Saldo</td>
                </tr>
                < ?php
                if($monedadoc=='S'){
                ?>
                    <tr align="center">
                        <td align="center">S/.</td>
                        <td align="right">< ?php echo number_format($pres_soles,2);?></td>
                        <td align="right">< ?php echo number_format($mod_soles,2);?></td>
                        <td align="right">< ?php echo number_format($total_otros_costos,2);?></td>
                        <td align="right">< ?php echo number_format($pres_soles-$total_otros_costos,2);?></td>
                    </tr>         
                < ?php
                }
                elseif($monedadoc=='D'){
                ?>
                    <tr align="center">
                        <td align="center">$</td>
                        <td align='right'>< ?php echo number_format($pres_dolar,2);?></td>
                        <td align='right'>< ?php echo number_format($mod_dolar,2);?></td>
                        <td align='right'>< ?php echo number_format($total_otros_costos_dolar,2);?></td>
                        <td align='right'>< ?php echo number_format($pres_dolar-$total_otros_costos_dolar,2);?></td>
                    </tr>            
                < ?php
                }
                ?>
            </table>  
        < ?php
        }
        ?>
    </div>-->
    <?php
}
?>
<!--<style>.cls_boton{margin-top:-7px;}</style>-->
<div style = "width: 100%;border:0px solid #000;">
<!--<div style = "margin-top:-30px; float: left; display: table; width: 100%;border:0px solid #000;">-->
    <?php
    if(!(($codpartida=='08' || $codpartida=='10') && $fila=="")){
    ?>     
        <div style = "float: left; height:50px; width: 100%;border:0px solid #000;">
            <table border='1' style='width:100%;'>
                <tr align='center' style="height:50px;">
                    <td><div style='width:80px;height:auto;'>NRO OT.</div></td>
                    <td><div style='width:80px;height:auto;'>NRO VOUCHER</div></td>
                    <td><div style='width:80px;height:auto;'>FECHA</div></td>
                    <td><div style='width:80px;height:auto;'>TIP MOV.</div></td>                
                    <td><div style='width:80px;height:auto;'>COD.PARTIDA.</div></td>  
                    <td><div style='width:150px;height:auto;'>DESCRIPCION</div></td>                
                    <td><div style='width:80px;height:auto;'>NRO CHEQUE</div></td>
                    <td><div style='width:80px;height:auto;'>A LA ORDEN</div></td>
                    <td><div style='width:100px;height:auto;'>NRO DOC</div></td>
                    <td><div style='width:100px;height:auto;'>OC/OS</div></td>
                    <?php
                    if($monedadoc=='S'){
                       ?>
                       <td><div style='width:50px;height:auto;'>IMPORTE S/.</div></td>
                       <?php 
                    }
                    elseif($monedadoc=='D'){
                    ?>
                        <td><div style='width:50px;height:auto;'>IMPORTE $.</div></td>
                    <?php
                    }
                    ?>
                </tr>
            </table>
        </div>
        <div style = "float: left; overflow: auto; width: 100%;border:0px solid #000;">
            <table border='1' style='width:100%;'>
                <?php
                if($fila!=''){
                    echo $fila;    
                }
                else{
                    echo "<tr><td colspan='9' align='center'>NO EXISTEN REGISTROS.</td></tr>";
                }
                ?>
            </table>        
        </div>
    <?php
    }
    if($codpartida=='10' && $fila2!=''){
    ?>
        <div class="cls_boton" class="cls_boton" style="margin-right: 0px;"  id="<?php echo $codot;?>">
            <ul class="lista_botones" id="<?php echo $codpartida;?>">
                <li id="excel" class="ot_listar2" onclick="rpt_caja(this);">Ver Excel</li>
            </ul>
        </div>  
        <div style = "float: left;  width: 100%;border:1px solid #000;">
            <table border='1' style='width:100%;'>
                <tr style="height:50px;">
                    <td><div style='width:5%;height:auto;'>O.T.</div></td>
                    <td><div style='width:5%;height:auto;'>NºCAJA</div></td>
                    <td><div style='width:5%;height:auto;'>ITEM</div></td>                
                    <td><div style='width:10%;height:auto;'>REFERENCIA</div></td>
                    <td><div style='width:20%;height:auto;'>CLIENTE</div></td>
                    <td><div style='width:5%;height:auto;'>TIP.DOC.</div></td>
                    <td><div style='width:5%;height:auto;'>NRO DCTO</div></td>
                    <td><div style='width:5%;height:auto;'>FECHA DCTO</div></td>
                    <td><div style='width:15%;height:auto;'>DESCRIPCION</div></td>
                    <?php
                    if($monedadoc=='S'){
                    ?>
                        <td><div style='width:5%;height:auto;'>SUBTOTAL S/.</div></td>
                    <?php
                    }
                    elseif($monedadoc=='D'){
                    ?>
                        <td><div style='width:5%;height:auto;'>SUBTOTAL $.</div></td>
                    <?php
                    }
                    ?>
    <!--            <td><div style='width:5%;height:auto;'>COD. GASTO</div></td>
                    td><div style='width:5%;height:auto;'>MOTIVO</div></td>-->
                </tr>
                <?php echo $fila2;?>
            </table>
        </div>
    <?php
    }
    if($codpartida=='10' && $fila4!=''){
        ?>
        <div class="cls_boton" style="margin-right: 0px;"  id="<?php echo $codot;?>">
            <ul class="lista_botones" id="<?php echo $codpartida;?>">
                <li id="excel" class="ot_listar2" onclick="rpt_servicios(this);">Ver Excel</li>
            </ul>
        </div>  
        <div style = "float: left; overflow: auto; width: 100%;border:1px solid #000;">
            <table border='1' style='width:100%;'> 
                <tr align='center' style="height:50px;">
                    <td><div style='width:80px;height:auto;'>NRO OT.</div></td>		
                    <td><div style='width:80px;height:auto;'>REQ.SERV.</div></td>		
                    <td><div style='width:80px;height:auto;'>FECHA SERV.<BR>REALIZADO</div></td>
<!--                    <td><div style='width:80px;height:auto;'>FECHA EMISION</div></td>-->
                    <td><div style='width:120px;height:auto;'>TIPO SERVICIO</div></td>
                    <td><div style='width:80px;height:auto;'>PESOS(KG)</div></td>
                    <td><div style='width:100px;height:auto;'>PROVEEDOR</div></td>              
                    <td><div style='width:100px;height:auto;'>REALIZADO</div></td> 
                    <?php
                    if($monedadoc=='S'){
                    ?>
                        <td><div style='width:80px;height:auto;'>COSTO S/.</div></td>
                        <td><div style='width:80px;height:auto;'>FACT S/.</div></td>
                    <?php
                    }
                    elseif($monedadoc=='D'){
                    ?>
                        <td><div style='width:80px;height:auto;'>COSTO $</div></td>
                        <td><div style='width:80px;height:auto;'>FACT $</div></td>
                    <?php
                    }
                    ?>
                    <td><div style='width:100px;height:auto;'>COMPROBANTE<br>PAGO</div></td>                      
                    <td><div style='width:100px;height:auto;'>VOUCHER</div></td>   
                </tr>               
                <?php echo $fila4;?>
            </table>        
        </div>
        <?php
    }
    if($codpartida=='08' && $fila3!=''){
        ?>
        <div class="cls_boton" style="margin-right: 0px;"  id="<?php echo $codot;?>">
            <ul class="lista_botones" id="<?php echo $codpartida;?>">
                <li id="excel" class="ot_listar2" onclick="rpt_transportes(this);">Ver Excel</li>
            </ul>
        </div>  
        <div style = "float: left; overflow: auto; width: 100%;border:1px solid #000;">
            <table border='1' style='width:100%;'> 
                <tr align='center' style="height:50px;">
                    <td><div style='width:80px;height:auto;'>NRO OT.</div></td>		
                    <td><div style='width:80px;height:auto;'>REQ.SERV.</div></td>		
                    <td><div style='width:80px;height:auto;'>FECHA EMISION</div></td>                    
                    <td><div style='width:80px;height:auto;'>FECHA SERV.<BR>REALIZADO</div></td>
                    <td><div style='width:120px;height:auto;'>TIPO SERVICIO</div></td>
                    <td><div style='width:80px;height:auto;'>PESOS(KG)</div></td>
                    <td><div style='width:100px;height:auto;'>PROVEEDOR</div></td>              
                    <td><div style='width:100px;height:auto;'>REALIZADO</div></td> 
                    <?php
                    if($monedadoc=='S'){
                    ?>
                        <td><div style='width:80px;height:auto;'>COSTO S/.</div></td>
                        <td><div style='width:80px;height:auto;'>FACT S/.</div></td>
                    <?php
                    }
                    elseif($monedadoc=='D'){
                    ?>
                        <td><div style='width:80px;height:auto;'>COSTO $</div></td>
                        <td><div style='width:80px;height:auto;'>FACT $</div></td>
                    <?php
                    }
                    ?>
                    <td><div style='width:100px;height:auto;'>COMPROBANTE<br>PAGO</div></td>                      
                    <td><div style='width:100px;height:auto;'>VOUCHER</div></td>   
                </tr>               
                <?php echo $fila3;?>
            </table>        
        </div>
        <?php
    }
    ?>
</div>