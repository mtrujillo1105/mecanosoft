<!DOCTYPE html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Content-Language" content="es"> 
    <script type="text/javascript" src="<?php echo js;?>constants.js"></script> 
    <script type="text/javascript" src="<?php echo js;?>jquery.js"></script>
    <script type="text/javascript" src="<?php echo js;?>ventas/ot.js"></script>
</head>


  <form method="post" id="frmFact3">   
  <input type="hidden" name="tipo" id="tipo">
  </form>


<div class="case_botones">
    <ul class="lista_botones"><li id="salir" class="xfacturar">Salir</li></ul>            
    <ul class="lista_botones"><li id="excel" class="xfacturar3">Ver Excel</li></ul>
    <ul class="lista_botones"><li id="pdf" class="xfacturar3">Ver Pdf</li></ul>
    <!--ul class="lista_botones"><li id="html" class="xfacturar3">Ver Html</li></ul-->
    <!--ul class="lista_botones"><li id="atras" class="xfacturar">Inicio</li></ul-->  
</div> 


<?php
if($fInicio!="" && $fFin!="" && $tipo!=""){
?>
<div style="text-align:left;float:left;width:80%;font-size:13px;margin-top:10px;">
    <h3>
        REPORTE POR FACTURAR POR CLIENTE DETALLE (INCLUYE NUEVAS VENTAS) - <?php echo $fFin;?><br>
        CLIENTE: <?php echo $razon_social;?><br>
        OT: <?php echo $numero;?>
    </h3>
</div>

<div style="float:left;width:20%;font-size:13px;margin-top:10px;"><h3>T.C: <?php echo $tc;?></h3>
<span id="atras" class="xfacturar"><a href="javascript:;">&lt;&lt;&lt;Atras</a></span>
</div>

	

<div style="clear:both;padding-top:5px;">
    <table border='1' width='100%'>
        <thead>
            <tr align="center"  class="cabeceraTabla">
                <td width="8px;">No</td>
                <td>OT</td>
                <td>FECHA</td>
                <td>TIPO</td>
                <td>NUMERO</td>
                <td width='10%'>VALOR DE<BR>VENTA S/.</td>
                <td width='10%'>VALOR DE<BR>VENTA $</td>
                <td width='10%'>MONTO <BR>FACTURADO S/.</td>
                <td width='10%'>MONTO <BR>FACTURADO $</td>
                <td width='10%'>SALDO POR<BR>FACTURAR S/.<BR>(<?php echo $fFin;?>)</td>
                <td width='10%'>SALDO POR<BR>FACTURAR $<BR>(<?php echo $fFin;?>)</td>
                <td width='10%;'>SALDO TOTAL<BR>POR FACTURAR $<BR>(<?php echo $fFin;?>)</td>

            </tr>
        </thead>
        <tbody><?php echo $fila;?></tbody>
<!--        <tfoot>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>	
                <td>&nbsp;</td>	
                <td>&nbsp;</td>	
                <td>&nbsp;</td>	
                <td align='right'>< ?php echo number_format(($acumulado_soles),2,",",".");?></td>
                <td align='right'>< ?php echo number_format(($acumulado_dolares),2,",",".");?></td>
                <td align='right'>< ?php echo number_format(($acumuado_factSoles),2,",",".");?></td>
                <td align='right'>< ?php echo number_format(($acumuado_factDolares),2,",",".");?></td>
                <td align='right'>< ?php echo number_format(($acumuado_saldoSoles),2,",",".");?></td>
                <td align='right'>< ?php echo number_format(($acumuado_saldoDolares),2,",",".");?></td>
                <td align='right' style='background-color: #CCFFCC; opacity:0.8'>< ?php echo number_format(($acumuado_saldoDolares_total),2,",",".");?></td>

            </tr>
        </tfoot>-->
    </table>
    <BR/><BR/>
</div>
<!--script>alert("Proceso finalizado");</script-->
<?php
}
?>