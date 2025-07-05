<div style="float:left;width:60%;text-align: left;height: 30px;border: 0px solid #000;font-size: 15px;font-weight: bold;">
<?php
if($nroOt!=""){
?>
    O.T.: <?php echo $nroOt."-".$dirOt;?>
<?php
}
?>
</div>
<div style="width:60%;float:left;text-align: left;height: 30px;border:0px solid #000;font-size: 15px;font-weight: bold;">
    &nbsp;PARTIDA ::: <?=($tipser=='T'?"TRANSPORTE":"SERVICIOS DIRECTOS");?>
</div>
<div style="width:20%;float:RIGHT;border:0px solid #000;height: 30px;margin-top: -15px;" class="case_botones"  id="<?php echo $codot;?>">
<!--<div style="float:left;width:20%;margin-right: 0px;">-->
    <a href='#' onclick="$('#idcontenido').show();$('#excel.rpt_costoot').css('display','');$('#iddetalle').hide();"><img src='<?php echo img;?>atras.png' width='40' height='40' border='0' title='Regresar' alt='Dont Picture'></a>
    <ul class="lista_botones">
        <?php
        if($tipser){
        ?>
            <li id="excel" class="ot_listar2" onclick="rpt_transportes(this);">Ver Excel</li>
        <?php 
        }
        else{
        ?>
            <li id="excel" class="ot_listar2" onclick="rpt_servicios(this);">Ver Excel</li>
        <?php
        }
        ?>
    </ul>
</div>
<div style = "width: 100%;border:0px solid #000;">
<!--    <div style = "float: left; height:50px; width: 100%;border:1px solid #000;">
         <table border='1' style='width:100%;'>
            <tr align='center' style="height:50px;">
                <td><div style='width:80px;height:auto;'>NRO.DOC.</div></td>		
                <td><div style='width:80px;height:auto;'>FECHA ENTREGA</div></td>
                <td><div style='width:120px;height:auto;'>TIPO SERVICIO</div></td>
                <td><div style='width:80px;height:auto;'>PESOS(KG)</div></td>
                <td><div style='width:100px;height:auto;'>PROVEEDOR</div></td>              
                <td><div style='width:100px;height:auto;'>REALIZADO</div></td> 
                < ?php
                if($monedadoc=='S'){
                ?>
                    <td><div style='width:80px;height:auto;'>COSTO S/.</div></td>
                    <td><div style='width:80px;height:auto;'>FACT S/.</div></td>
                < ?php
                }
                elseif($monedadoc=='D'){
                ?>
                    <td><div style='width:80px;height:auto;'>COSTO $</div></td>
                    <td><div style='width:80px;height:auto;'>FACT $</div></td>
                < ?php
                }
                ?>
                <td><div style='width:100px;height:auto;'>COMPROBANTE<br>PAGO</div></td>                      
                <td><div style='width:100px;height:auto;'>VOUCHER</div></td>   
            </tr>            
        </table> 
    </div>-->
    <div style = "float: left; height: 500px;overflow: auto; width: 100%;border:0px solid #000;">
        <table border='1' style='width:100%;'> 
            <tr align='center' style="height:50px;">
                <td style ="width:40px"><div style='width:40px;height:auto;'>NRO.OT.</div></td>	
                <td><div style='width:60px;height:auto;'>REQ.SERV.</div></td>		
                <td><div style='width:80px;height:auto;'>FECHA EMISION</div></td>                
                <td><div style='width:80px;height:auto;'>FECHA SERV.<BR>REALIZADO</div></td>
                <td><div style='width:120px;height:auto;'>TIPO SERVICIO</div></td>
                <td><div style='width:80px;height:auto;'>PESOS(KG)</div></td>
                <td><div style='width:100px;height:auto;'>PROVEEDOR</div></td>              
                <td style='width:50px;'><div style='width:50px;height:auto;'>REALIZADO</div></td> 
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
            <?php echo $fila;?>
        </table>        
    </div>
</div>