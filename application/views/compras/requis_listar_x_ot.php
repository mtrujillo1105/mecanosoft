<div style = "width: 100%;height: 600px;border:0px solid #000;margin-top: 30px;">
    <div style='width:60%;float:left;text-align: left;height: 30px;border:0px solid #000;font-size: 15px;font-weight: bold;'>&nbsp;O.T.: <?php echo $nroOt."-".$dirOt;?></div>
    <div style="width:20%;float:left;text-align: left;height: 30px;border:0px solid #000;font-size: 15px;"><a href='#' onclick="$('#idcontenido').show();$('#excel.rpt_costoot').css('display','');$('#iddetalle').hide();"><img src='<?php echo img;?>atras.png' width='40' height='40' border='0' title='Regresar' alt='Dont Picture' ></a></div>
    <div style="width:20%;float:left;height: 30px;border:0px solid #000;height: 30px;" class="case_botones"  id="<?php echo $codot;?>">
        <ul class="lista_botones"><li id="excel" class="ot_listar2" onclick="rpt_requis(this);">Ver Excel</li></ul>
    </div>     
    <!--div style = "float: left; height:50px; width: 99%;border:1px solid #000;">
        <table border='1' style='width:100%;'>
            <tr align='center' style="height:50px;">
                <td style='width:7%;'><div>FECHA<br>REQ.</div></td>
                <td style='width:3%;'><div>SERIE<br>REQ.</div></td>                
                <td style='width:7%;'><div>NUMERO<br>REQ.</div></td>
                <td style='width:9%;'><div>TIPO</div></td>
                <td style='width:34%;'><div>DESCRIPCION</div></td>
                <td style='width:8%;'><div>CANTIDAD</div></td>
                <td style='width:5%;'><div>UNIDAD</div></td>
                <td style='width:9%;'><div>PESO</div></td>
                <td style='width:9%;'><div>O.C.</div></td>
                <td style='width:9%;'><div>ATENCION</div></td>
            </tr>
        </table>
    </div-->

    <div style = "float: left; height: 100%;width:100%;overflow: auto;border:1px solid #000;">
        <table heigth="50%" border="1" id="tabla_cabecera">
            <tr align="center" style="height:50px;font:12px; font:arial;color:#fff;background:#8AA8F3;">
                <td style='width:7%;'><div>OT.</div></td>
                <td style='width:7%;'><div>TIPO PRODUCTO</div></td>
                <td style='width:7%;'><div>NUMERO<br>REQ.</div></td>
<!--                <td style="width:7%;"><div>FECHA<br>EMISION REQ.</div></td>-->
                <td style="width:7%;"><div>FECHA<br>APROBACION REQ.</div></td>                
                <td style="width:3%;">DPTO</div></td>
                <td style='width:9%;'><div>CODIGO</div></td>
                <td style='width:9%;'><div>FAMILIA</div></td>
                <td style='width:34%;'><div>PRODUCTO</div></td>
                <td style='width:5%;'><div>UNIDAD</div></td>
                <td style='width:8%;'><div>CANTIDAD<br>SOLIC.</div></td>
                <?php if($ver_precios):;?>
                <td style='width:8%;'><div>PRECIO<br><?=($monedadoc=='S'?"S/.":"$");?></div></td>
                <td style='width:8%;'><div>TOTAL<br><?=($monedadoc=='S'?"S/.":"$");?></div></td>
                <?php endif;?>
<!--                <td style='width:8%;'><div>ATENDIDO</div></td>
                <td style='width:8%;'><div>SALDO</div></td>-->
                <td style='width:9%;'><div>PESO<br>SOLIC(KG)</div></td>
                <td style="width:25%;">RESPONSABLE</div></td>                
                <td style='width:9%;'><div>OBSERVACION</div></td>
            </tr>
            <?php echo $fila;?>
            <?php
            if($fila==''){
                ?>
                <tr><td colspan="29" align="center">::: NO EXISTEN REGISTROS :::</td></tr>    
                <?php
            }
            ?>
        </table>
    </div>
</div>	
