<div style = "width: 100%;height: 600px;border:0px solid #000;margin-top: 30px;">
    <div style='width:60%;float:left;text-align: left;height: 30px;border:0px solid #000;font-size: 15px;font-weight: bold;'>
        <?php if(count($arrnumero)==1){;?>
        &nbsp;O.T.: <?php echo $arrnumero[0]."-".$arrsite[0];?>
        <?php };?>
    </div>
    <div style="width:20%;float:left;text-align: left;height: 30px;border:0px solid #000;font-size: 15px;"><a href='#' onclick="$('#idcontenido').show();$('#excel.rpt_costoot').css('display','');$('#iddetalle').hide();"><img src='<?php echo img;?>atras.png' width='40' height='40' border='0' title='Regresar' alt='Dont Picture' ></a></div>
    <div style="width:20%;float:left;height: 30px;border:0px solid #000;height: 30px;" class="case_botones"  id="">
        <ul class="lista_botones"><li id="excel" class="ot_listar2" onclick="rpt_nomenclatura(this);">Ver Excel</li></ul>
    </div>     
    <div style = "float: left; height: 100%;width:100%;overflow: auto;border:1px solid #000;">
        <table heigth="50%" border="1" id="tabla_cabecera">
            <tr align="center" style="height:50px;font:12px; font:arial;color:#fff;background:#8AA8F3;">
                <td style='width:7%;'><div>OT</div></td>
                <td style='width:7%;'><div>TIPO<BR>TORRE</div></td>
                <td style='width:7%;'><div>MARCA</div></td>
                <td style='width:7%;'><div>CONJUNTO<br>GENERAL</div></td>
                <td style="width:7%;"><div>CONJUNTO</div></td>                
                <td style="width:3%;"><div>CODIGO</div></td>
                <td style="width:7%;"><div>FAMILIA</div></td>
                <td style='width:30%;'><div>DESCRIPCION</div></td>
                <td style='width:9%;'><div>CANTIDAD<br>CJTO.GRAL.</div></td>
                <td style='width:9%;'><div>CANTIDAD<br>CJTO.</div></td>
                <td style='width:9%;'><div>PIEZAS<br>LISTA</div></td>
                <td style='width:5%;'><div>LARGO<br>(MM)</div></td>
                <td style='width:8%;'><div>ANCHO<BR>(MM)</div></td>
                <td style='width:8%;'><div>ESPESOR<BR>(MM)</div></td>
                <td style='width:9%;'><div>PESO TOTAL<br>(KG)</div></td>
            </tr>
            <?php echo $fila;?>
        </table>
    </div>
</div>	