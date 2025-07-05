<div style="width:60%;float:left;text-align: left;height: 30px;border:0px solid #000;font-size: 15px;font-weight: bold;">
    &nbsp;STOCK COMPROMETIDO<br>
    &nbsp;<?php echo $codigo." ::: ".$producto;?>
</div>

   <!-- <ul class="lista_botones"><li  id="excel" class="ot_listar2" onclick="ver_stockcomprometidot(<?php echo $codigo?>)">Ver Excel</li></ul>-->
<div style="width:40%;float:left;text-align: right;height: 40px;border:0px solid #000;font-size: 15px;"><a href='#' onclick="$('#idcontenido2').show();$('#iddetalle').hide();$('#ul_excel').show();$('#ul_excel_det').hide();$('#tipoexport').val('');"><img src='<?php echo img;?>atras.png' width='40' height='40' border='0' title='Regresar' alt='Dont Picture' ></a></div>
<div style ="float: left;display: table; width: 100%;border:1px solid #000;">  
    <table border='1' style='width:100%;'>
        <tr>
            <td>NUM.REQ</td>
            <td>FECHA</td>		
            <td>NROOT</td>		
            <td>CODRES</td>
            <td>USER</td>		
            <td>USERAPROB</td>		
            <td>CANTIDAD</td>
            <!--td>CANTIDADS</td-->
            <td>CANTIDAD V.S.</td>
            <td>STOCK COMPROM.</td>
            <td>FECHA V.S.</td>
            <td>NUMERO V.S.</td>
            <td>VALUSER</td>
        </tr>
        <?php echo $fila;?>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>				
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>	
            <td>&nbsp;</td>		
            <td align='right'><?php echo $total_cantidad;?></td>
            <td align='right'><?php echo $total_valcant;?></td>
            <td align='right'><?php echo $total_comprom;?></td>
            <td>&nbsp;</td>			
            <td>&nbsp;</td>			
        </tr>
    </table>
</div>