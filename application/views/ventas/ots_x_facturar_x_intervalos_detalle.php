<script type="text/javascript" src="<?php echo js;?>constants.js"></script> 
<script type="text/javascript" src="<?php echo js;?>jquery.js"></script>
<script type="text/javascript" src="<?php echo js;?>ventas/ot.js"></script>
<div class="case_botones">
    <ul class="lista_botones"><li id="salir" class="xfacturar_xintervalos_detalle">Salir</li></ul>
    <ul class="lista_botones"><li id="excel" class="xfacturar_xintervalos" name="<?php echo $del;?>" name2="<?php echo $al;?>">Ver Excel</li></ul>
    <!--ul class="lista_botones"><li id="html" class="xfacturar_xintervalos">Ver Html</li></ul-->  
</div> 	
<span style="float:left;width:80%;text-align:left;font-size:14px;margin-top:20px;"><?php echo $razcli;?> detallado del <?php echo $del;?> al <?php echo $al;?></span>
<div style="float:left;width:20%;text-align:rigth;font-size:14px;margin-top:20px;">T.C: <?php echo $tc2;?><br>
    <span id="html" class="xfacturar_xintervalos"><a href="javascript:;">&lt;&lt;&lt;Atras</a></span>
</div>
<div style="clear:both;border:1px solid #000;">
    <table border='1' width='100%'>
    <thead>
    <tr align="center"  class="cabeceraTabla">
            <td width='5px;'>No</td>
            <td>NUMERO</td>						
            <td width='80px;'>SALDO POR FACTURAR S/.<br>(<?php echo $del;?>)</td>
            <td width='80px;'>SALDO POR FACTURAR $.<br>(<?php echo $del;?>)</td>
            <td width='90px;'>MONTO <BR>FACTURADO. S/.</td>
            <td width='90px;'>MONTO <BR>FACTURADO. $</td>
            <td width='80px;'>SALDO POR<BR>FACTURAR S/.<BR>(<?php echo $al;?>)</td>
            <td width='80px;'>SALDO POR<BR>FACTURAR $<BR>(<?php echo $al;?>)</td>					
            <td width='98px;'>SALDO TOTAL<BR>POR FACTURAR $<BR>(<?php echo $al;?>)</td>							
            <td width='98px;'>TOTAL<BR>POR COBRAR $</td>	
            <td width='98px;' >RENDIMIENTO<br>FACTURACION (%)</td>	
        </tr>
    </thead>

    <tbody>
        <?php echo $fila2;?>
    </tbody>

    <tfoot>         
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td align="right"><?php echo number_format($inicialsoles_total,2,",",".");?></td>
            <td align="right"><?php echo number_format($inicialdolares_total,2,",",".");?></td>
            <td align='right'><?php echo number_format($factSoles_total,2,",",".");?></td>
            <td align='right'><?php echo number_format($factDolares_total,2,",",".");?></td>
            <td align='right'><?php echo number_format($saldoSoles_total,2,",",".");?></td>
            <td align='right'><?php echo number_format($saldoDolares_total,2,",",".");?></td>
            <td align='right'><?php echo number_format($x_facturar_total_dolares_total,2,",",".");?></td>
            <td align='right'><?php echo number_format($venta_total_dolares_total,2,",",".");?></td>
            <td align='right'><?php echo number_format($rendimiento_total,2,",",".");?></td>
        </tr>
    </tfoot>
    </table>
</div>	
<div style="width:80%;text-align:left;font-size:14px;margin-top:20px;">
    *  Saldo por facturar<HASTA> = Saldo por facturar<DESDE> - Monto facturado<br><br>
    ** Rendimiento facturacion   = 100 - (Saldo total por facturar $<HASTA> * 100 / Valor total venta OT $)<br><br>
    (+) Venta
    (-) Facturacion
</div>				