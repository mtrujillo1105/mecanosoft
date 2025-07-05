<div style = "width: 100%;height: 600px;border:0px solid #000;margin-top: 30px;">
    <div style='width:60%;float:left;text-align: left;height: 30px;border:0px solid #000;font-size: 15px;font-weight: bold;'>
        <?php if(count($arrnumero)==1){?>
        &nbsp;O.T.: <?php echo $arrnumero[0]."-".$arrsite[0];?>
        <?php }?>
    </div>
    <!--div style="width:20%;float:left;text-align: left;height: 30px;border:0px solid #000;font-size: 15px;">
        <a href='#' onclick="$('#idcontenido').show();$('#excel.rpt_costoot').css('display','');$('#iddetalle').hide();">
            <img src='<?php echo img;?>atras.png' width='40' height='40' border='0' title='Regresar' alt='Dont Picture' ></img>
        </a>
    </div-->
    <div style="width:20%;float:right;height: 30px;border:0px solid #000;height: 30px;margin-top: -15px;" class="case_botones"  id="<?php echo $codot;?>">
        <a href='#' onclick="$('#idcontenido').show();$('#excel.rpt_costoot').css('display','');$('#iddetalle').hide();">
            <img src='<?php echo img;?>atras.png' width='40' height='40' border='0' title='Regresar' alt='Dont Picture' ></img>
        </a>
        <input type ="hidden" name = "txt_ot_code" id="txt_ot_code" value="<? echo $var_ot_code; ?>">
        <ul class="lista_botones"><li id="excel" class="ot_listar2" onclick="rpt_galva(this);">Ver Excel</li></ul>
       <!-- <ul class="lista_botones"><li id="pdf" class="ot_listar2" onclick="rpt_tesoreria(this);">Ver Pdf</li></ul>-->
    </div>     
    <div style = "float: left; height: 80%;width:100%;overflow: auto;border:1px solid #000;">
        <table heigth="50%" border="1" id="tabla_cabecera">
            <THEAD>
                <tr class="cabeceraTabla">
                    <td width="5%">ITEM</td>
                    <td width="7%">FECHA</td>
                    <td width="7%">O.T.</td>
                    <td width="7%">T.PRODUCTO</td>
                    <td width="13%">CONSTANCIA</td>
                    <td width="13%">O.S.</td>
                    <td width="20%">NOMBRE O RAZ&Oacute;N SOCIAL </td>
                    <td width="9%">GUIA CLIENTE</td>
                    <td width="26%">REFERENCIA</td>
                    <td width="26%">PIEZAS</td>
                    <td width="26%">MOTIVO</td>
                    <td width="5%">PESO<br>(KG)</td>
                    <?php if($ver_precios):;?>
                    <td width="5%">TOTAL<br><?php echo ($monedadoc=='S'?"S/.":"$");?></td>
                    <?php endif;?>
                </tr>
            </THEAD>    
            <tbody><?php echo $fila;?></tbody>
            <?php
            if($fila==''){
                ?>
                <tbody><tr><td colspan="30" align="center">::: NO EXISTEN REGISTROS :::</td></tr></tbody>    
                <?php
            }
            ?>
        </table>
    </div>
</div>	
