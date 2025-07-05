<div id="divPartida">
    <div class="lbl2" style="text-align:left;">PARTIDAS</div>
    <div class="lbl2" style="text-align:left;">
        <table width="99%" border="1" align="center" cellpadding="0" cellspacing="0" id="tblPresupuesto">
            <thead>
                <tr>
                    <th scope="col" width="55%" bgcolor="#CCCCCC"><div align="center">Descripcion</div></th>
                    <th scope="col" width="15%" bgcolor="#CCCCCC"><div align="center">Presupuestado S/.</div></th>
                </tr>	
            </thead>                                                 
            <tbody>
                <?php
                switch($tipo){
                    case '02': echo $fila_fab;break;
                    case '03': echo $fila_montaje;break;
                    case '04': echo $fila_ociviles;break;
                    case '05': echo $fila_transporte;break;
                    case '06': echo $fila_singenieria;break;
                    case '07': echo $fila_pespeciales;break;                    
                    case '08': echo $fila_otros;break;                    
                    case '09': echo $fila_pingenieria;break;                    
                }
                ?>         
            </tbody>
        </table>     
    </div>
</div>
<div id="divSubpartida<?php echo $tipo;?>">
    <div class="lbl2" style="text-align:left;">Sub Partidas: Servicios</div>
    <div class="lbl2" style="text-align:left;">
        <input type="text" value="<?php echo $tipo;?>" name="tipo_producto" id="tipo_producto">
        <table width="99%" border="1" align="center" cellpadding="0" cellspacing="0">
            <thead>
                <tr bgcolor="#CCCCCC">
                    <th scope="col" width="52%"><div align="center">Descrpcion</div></th>
                    <th scope="col" width="12%"><div align="center">Presupuestado S/.</div></th>
                </tr>	
            </thead>                                                 
            <tbody>
                <tr bgcolor="#FFFFFF" id="1">
                    <td align="center" colspan="2">NO EXISTEN REGISTROS.</td>
                </tr>	               
            </tbody>
        </table>        
    </div>
    <div><ul class="lista_botones" id="nuevoProducto"><li>Grabar</li></ul></div>
</div>