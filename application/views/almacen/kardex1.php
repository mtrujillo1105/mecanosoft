<div style="width:60%;float:left;text-align: left;height: 30px;border:0px solid #000;font-size: 15px;font-weight: bold;">
    &nbsp;KARDEX<br>
    &nbsp;<?php echo $codigo." ::: ".$descripcion;?>
</div>
 <!-- <ul class="lista_botones"><li  id="excel" class="ot_listar2" onclick="ver_stocktransitot(<?php echo $codigo?>)">Ver Excel</li></ul>-->

<div style="width:40%;float:left;text-align: right;height: 40px;border:0px solid #000;font-size: 15px;"><a href='#' onclick="$('#idcontenido2').show();$('#iddetalle').hide();$('#ul_excel').show();$('#ul_excel_det').hide();$('#tipoexport').val('');"><img src='<?php echo img;?>atras.png' width='40' height='40' border='0' title='Regresar' alt='Dont Picture' ></a></div>
<div style ="float: left;display: table; width: 100%;border:1px solid #000;">
    <table border='1' style='width:100%;'>
        <tr style='align:center;'>
            <td>FECHA</td>
            <td>TIPO MOV.</td>
            <td>DOCUMENTO</td>
            <td>NUMERO</td>
            <td>INGRESO</td>
            <td>SALIDA</td>
            <td>SALDO</td>
            <td>O.T.</td>
            <td>REQUERIMIENTO</td>
        </tr>
        <?php echo $fila;?>
    </table>
</div>