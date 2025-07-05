<div class="lbl2" style="text-align:left;">Sub Partidas: <?php echo $nombrepartida;?></div>
<div class="lbl2" style="text-align:left;">
    <form id="frmSubpartida">
        <table width="99%" border="1" align="center" cellpadding="0" cellspacing="0">
            <thead>
                <tr bgcolor="#CCCCCC">
                    <th scope="col" width="52%"><div align="center">Descrpcion</div></th>
                    <th scope="col" width="12%"><div align="center">Presupuestado S/.</div></th>
                </tr>	
            </thead>                                                 
            <tbody>
                <?php echo $fila;?> 
                <input type="hidden" name="codpartida" id="codpartida" value="<?php echo $codpartida;?>">
                <input type="hidden" name="codtipoproducto" id="codtipoproducto" value="<?php echo $codtipoproducto;?>">
                <input type="hidden" name="codpresupuesto" id="codpresupuesto" value="<?php echo $codpresupuesto;?>">
            </tbody>
        </table>  
    </form>
</div>
<div><ul class="lista_botones" onclick="grabar_subpartida();"><li>Grabar</li></ul></div>