<?php
if($verencabezado!='N'){
?>
<div style='width:100%;float: left;text-align: left;height: 22px;border: 0px solid #000;font-size: 15px;font-weight: bold;'>&nbsp;
<?php if(count($arrnumero)==1){?>    
    O.T.: <?php echo $arrnumero[0]."-".$arrsite[0];?>
<?php }?>  
</div>    
<div style="width:60%;float:left;text-align: left;height: 30px;border:0px solid #000;font-size: 15px;font-weight: bold;">
    &nbsp;PARTIDA ::: MATERIALES
</div>
<?php
}
?>
<div style="width:20%;float:RIGHT;border:0px solid #000;height: 30px;margin-top: -15px;" class="case_botones"  id="<?php echo $codot;?>">
    <a href='#' onclick="$('#idcontenido').show();$('#excel.rpt_costoot').css('display','');$('#iddetalle').hide();"><img src='<?php echo img;?>atras.png' width='40' height='40' border='0' title='Regresar' alt='Dont Picture' ></a>
    <ul class="lista_botones"><li id="excel" class="ot_listar2" onclick="rpt_materiales(this);">Ver Excel</li></ul>
</div> 	
<div style = "width: 100%;border:0px solid #000;">      
    <div style = "float: left; height:50px; width: 99%;border:0px solid #000;">
        <table border='1' style='width:100%;'>
            <tr align='center' style="height:50px;">
                <td style="width:8%;height:auto;"><div>NRO OT</div></td>	
                <td style="width:8%;height:auto;"><div>T.PRODUCTO</div></td>
                <td style="width:8%;height:auto;"><div>NRO REQ.</div></td>
                <td style="width:9%;height:auto;"><div>CODIGO</div></td>		
                <td style="width:10%;height:auto;"><div>LINEAS</div></td>
                <td style="width:16%;height:auto;"><div>DESCRIPCION</div></td>
                <td style="width:8%;height:auto;"><div>FECHA</div></td>		
                <td style="width:8%;height:auto;"><div>CANT.</div></td>
                <?php
                if($ver_precios){
                if($monedadoc=='S'){
                ?>
                    <td style="width:11%;height:auto;"><div>PRECIO S/.</div></td>
                    <td style="width:10%;height:auto;"><div>TOTAL S/.</div></td>
                <?php
                }
                elseif($monedadoc=='D'){
                ?>
                    <td style="width:11%;height:auto;"><div>PRECIO $.</div></td>
                    <td style="width:10%;height:auto;"><div>TOTAL $.</div></td>
                <?php
                }
                }
                ?>
                <td style="width:10%;height:auto;"><div>PESO ATEND.<BR>(KG)</div></td>
                <td style="width:10%;height:auto;"><div>No VALE SALIDA</div></td>
            </tr>
        </table>
    </div>
    <div style = "float: left; height: 500px;overflow: auto; width: 100%;border:0px solid #000;">
        <table border='1' style='width:100%;' id ="idofyourtable">
            <?php echo $fila;?>
        </table>        
    </div>
</div>



