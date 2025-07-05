<div id="idtitulo" style='width:35%;float:left;text-align: left;'><h2>O.T.: <?php echo $nroOt."-".$dirOt;?> - PARTIDAS</h2></div>
<div id="idencabezado" style="width:65%;float:left;">
    <input type="hidden" name="codot" id="codot" value="<?php echo $codot;?>">
    <div style="width:30%;float:left;"><a href='#' onclick="$('#tipoexport').val('');$('#frmBusqueda').submit();"><<<<< Atras</a></div>
    <div style="width:50%;float:left;margin-right: 0px;">
        <table width="40%" border="1">
            <tr align="center">
                <td>&nbsp;</td>
                <td>Presupuestado</td>
                <td>Modificado</td>
                <td>Consumido</td>
                <td>Saldo</td>
            </tr>
            <?php
            if($monedadoc=='S'){
            ?>
                <tr align="center">
                    <td align="center">S/.</td>
                    <td align="right"><?php echo number_format($total_monto_soles,2);?></td>
                    <td align="right"><?php echo number_format($total_ampliado_soles,2);?></td>
                    <td align="right"><?php echo number_format($total_ejecutado_soles,2);?></td>
                    <td align="right"><?php echo number_format($total_monto_soles-$total_ejecutado_soles,2);?></td>
                </tr>         
            <?php
            }
            elseif($monedadoc=='D'){
            ?>
                <tr align="center">
                    <td align="center">$</td>
                    <td align='right'><?php echo number_format($total_monto_dolar,2);?></td>
                    <td align='right'><?php echo number_format($total_ampliado_dolar,2);?></td>
                    <td align='right'><?php echo number_format($total_ejecutado_dolar,2);?></td>
                    <td align='right'><?php echo number_format($total_monto_dolar-$total_ejecutado_dolar,2);?></td>
                </tr>            
            <?php
            }
            ?>
        </table>         
    </div>   
    <div style="width:20%;float:left;" class="case_botones"  id="<?php echo $codot;?>"><ul class="lista_botones"><li id="excel" class="ot_listar2" onclick="rpt_presupuesto(this);">Ver Excel</li></ul></div>     
</div>    
<div style = "display: table; width: 100%;border:1px solid #000;">
    <div id="idcabecera" style = "float: left; height: 300px;overflow: auto; width: 100%;border:1px solid #000;">
        <table border='1' style='width:100%;'>
            <tr align='center' style="height:50px;">
                <td><div style='width:50px;height:auto;'>ITEM</div></td>
                <td><div style='width:250px;height:auto;'>DESCRIPCION</div></td>
<!--                <td><div style='width:50px;height:auto;'>CONTROL</div></td>                -->
                <td><div style='width:80px;height:auto;'>PRESUPUESTO</div></td>
                <td><div style='width:80px;height:auto;'>AMPLIADO</div></td>
                <td><div style='width:80px;height:auto;'>EJECUTADO</div></td>
                <td><div style='width:80px;height:auto;'>MARGEN</div></td>
            </tr>
            <?php echo $fila;?>
        </table>
    </div>
    <div id="iddetalle" style = "margin-top:5px;float: left; height: 250px;overflow: auto; width: 100%;border:1px solid #000;">
        <table border='1' style='width:100%;'>
            <tr align='center' style="height:50px;">
                <td><div style='width:50px;height:auto;'>ITEM</div></td>
                <td><div style='width:250px;height:auto;'>NRO VOUCHER</div></td>
                <td><div style='width:50px;height:auto;'>FECHA</div></td>                
                <td><div style='width:80px;height:auto;'>TIP MOV</div></td>
                <td><div style='width:80px;height:auto;'>DESCRIPCION</div></td>
                <td><div style='width:80px;height:auto;'>NRO CHEQUE</div></td>
                <td><div style='width:80px;height:auto;'>A LA ORDEN</div></td>
                <td><div style='width:80px;height:auto;'>NRO DOC</div></td>
                <td><div style='width:80px;height:auto;'>IMPORTE</div></td>
            </tr>
            <tr>
                <td colspan='9' align="center">NO EXISTEN REGISTROS</td>
            </tr>
        </table> 
    </div>    
</div>