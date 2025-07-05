<div style="float:left;width:60%;text-align: left;font-size: 15px;font-weight: bold;">&nbsp;
<?php if(count($arrnumero)==1){?>    
    O.T.: <?php echo $arrnumero[0]."-".$arrsite[0];?>
<?php }?>  
</div>
<div style="width:60%;float:left;text-align: left;height: 30px;border:0px solid #000;font-size: 15px;font-weight: bold;">&nbsp;PARTIDA ::: MANO DE OBRA DIRECTA</div>
<div style="float:left;width:20%;margin-right: 0px;"><a href='#' onclick="$('#idcontenido').show();$('#excel.rpt_costoot').css('display','');$('#iddetalle').hide();"><h3><img src='<?php echo img;?>atras.png' width='40' height='40' border='0' title='Regresar' alt='Dont Picture' ></h3></a></div>
<div style="width:20%;float:left;" class="case_botones"  id="<?php echo $codot;?>"><ul class="lista_botones"><li id="excel" class="ot_listar2" onclick="rpt_manoobra(this);">Ver Excel</li></ul></div> 
<div style = "float:left;display: table; width: 100%;border:0px solid #000;">
        <div style = "float: left;  width: 100%;border:0px solid #000;">
            <table style="width: 100%" border="0">
                <tr>
                    <td align="left"><strong>PESO(TON): </strong><?php echo $peso;?></td>
                    <td align="left"><strong>HORAS: </strong><?php echo $total_horas;?></td>
                    <td align="left">
                        <strong><?=($monedadoc=='S'?'MONTO S/.':'MONTO $');?>: </strong>
                        <?=($monedadoc=='S'?number_format($total_real,2):number_format($total_realD,2));?>
                    </td>
                    <td align="left">
                        <strong><?=($monedadoc=='S'?'MONTO S/.':'MONTO $.');?> /HORAS: </strong>
                        <?=($monedadoc=='S'?number_format($total_real/$total_horas,2):number_format($total_realD/$total_horas,2));?>
                    </td>
                    <td align="left">
                        <strong>HORAS /TON: </strong>
                        <?php echo number_format($total_horas/$peso,2)?>
                    </td>
                    <td align="left">
                        <strong><?=($monedadoc=='S'?'MONTO S/.':'MONTO $.');?> /TON: </strong>
                        <?=($monedadoc=='S'?number_format($total_real/$peso,2):number_format($total_realD/$peso,2));?>
                    </td>            
                </tr>
            </table>
        </div>
    <div style = "float: left;  width: 100%;border:1px solid #000;">
        <table border='1' style='width:100%;' cellpadding="0" cellspacing="0">
            <tr style="height:50px;" >
                <td><div style='width:5%;height:auto;'>NRO OT.</div></td>
                <td><div style='width:5%;height:auto;'>DNI</div></td>
                <td><div style='width:35%;'>NOMBRES Y APELLIDOS</div></td>                
                <td><div style='width:15%;height:auto;'>AREA PRODUCCION</div></td>
                <td><div style='width:20%;height:auto;'>DESCRIPCION</div></td>
                <td><div style='width:5%;height:auto;'>FECHA</div></td>
                <td><div style='width:5%;height:auto;'>HORAS</div></td>
                <?php
                if($monedadoc=='S'){
                ?>
<!--                    <td><div style='width:5%;height:auto;'>MONTO S/.</div></td>-->
                    <td><div style='width:5%;height:auto;'>MONTO<br> S/.</div></td>
                <?php
                }
                elseif($monedadoc=='D'){
                ?>
<!--                    <td><div style='width:5%;height:auto;'>MONTO $</div></td>-->
                    <td><div style='width:5%;height:auto;'>MONTO<br>$</div></td>                
                <?php
                }
                ?>
            </tr>
             <?php echo $fila;?>
        </table>
    </div>
<!--    <div style = "float: left; height: 500px;overflow: auto; width: 100%;border:1px solid #000;">
        <table border='1' style='width:100%;'>
            <  ?php echo $fila;?>
        </table>        
    </div>-->
</div>