<!DOCTYPE html>
<div class="case_botones">
    <ul class="lista_botones"><li id="salir" class="control_pesos">Salir</li></ul>            
    <ul class="lista_botones"><li id="excel" class="control_pesos">Ver Excel</li></ul>
    <ul class="lista_botones"><li id="pdf" class="control_pesos">Ver Pdf</li></ul>
    <ul class="lista_botones"><li id="html" class="control_pesos">Ver Html</li></ul>  
</div> 	
<div style="margin-top:5px;text-align:left;"><h2><?php echo $ot;?></h2></div>    
<div style="margin-top:5px;text-align:left;"><h2><?php echo $codpro." ".$producto;?></h2></div>
<div style="margin-top:5px;">
    <table border='1' width='95%'>
        <tbody>
            <tr align='center'>
                <td>FECHA V.S.</td>
                <td>SERIE V.S.</td>
                <td>NUMERO V.S.</td>
                <td>PESO</td>
                <td>CANTIDAD<br>ATENDIDA</td>
                <td>PESO<br>ATENDIDO TOTAL</td>
            </tr>
            <?php echo $fila;?>
        </tbody>
        <tfoot>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td align='right'><?php echo number_format($p_atendido_total_total,2,",",".");?></td>
            <tr>
        </tfoot>
    </table>
</div>
<div style="margin:0 auto 0 auto;height:25px;margin-top:15px;font-size:14px;"><a href="#" onclick="history.back(-1);">REGRESAR</a></div>
