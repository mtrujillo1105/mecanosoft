<!--<div style="margin-top:0px; float:left;width:100%;text-align: left;">&nbsp;-->
<div style='width:100%;float: left;text-align: left;height: 22px;border: 0px solid #000;font-size: 15px;font-weight: bold;'>&nbsp;
<?php if($nroOt!=""){?>O.T.: <?php echo $nroOt."-".$dirOt;?><?php } ?>
</div>
<div style="width:60%;float:left;text-align: left;height: 30px;border:0px solid #000;font-size: 15px;font-weight: bold;">
    &nbsp;PARTIDA ::: CAJA CHICA
</div>
<!--<div style="margin-top:-40px; float:left;width:20%;margin-right: 0px;">-->
<div style="width:20%;float:RIGHT;border:0px solid #000;height: 30px;margin-top: -15px;" class="case_botones" id="<?php echo $codot;?>">    
    <a href='#' onclick="$('#idcontenido').show();$('#excel.rpt_costoot').css('display','');$('#iddetalle').hide()"><img src='<?php echo img;?>atras.png' width='40' height='40' border='0' title='Regresar' alt='Dont Picture' ></h3></a>
    <ul class="lista_botones"><li id="excel" class="ot_listar2" onclick="rpt_caja(this);">Ver Excel</li></ul>    
</div>
<div style = "width: 100%;border:0px solid #000;">    
    <div style = "float: left;  width: 100%;border:0px solid #000;">
        <table border='1' style='width:100%;'>
            <tr style="height:50px;">
                <td><div style='width:5%;height:auto;'>O.T.</div></td>
                <td><div style='width:5%;height:auto;'>NºCAJA</div></td>
                <td><div style='width:5%;height:auto;'>ITEM</div></td>                
                <td><div style='width:10%;height:auto;'>REFERENCIA</div></td>
                <td><div style='width:20%;height:auto;'>PROVEEDOR</div></td>
                <td><div style='width:5%;height:auto;'>TIP.DOC.</div></td>
                <td><div style='width:5%;height:auto;'>NRO DOCUMENTO</div></td>
                <td><div style='width:5%;height:auto;'>FECHA DOCUMENTO</div></td>
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
            </tr>
            <?php echo $fila;?>
        </table>
    </div>
</div>