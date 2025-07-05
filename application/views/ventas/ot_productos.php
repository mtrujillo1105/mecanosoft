<div class="prod_sup">
    <div id="divProducto" class="Cproducto">
        <div class="lbl1_block"><strong>TIPO</strong></div>
        <div class="lbl1_block"><input type="radio" name="chkProducto" id="chkProducto" value="1" checked="checked">Fabricaci&oacute;n</div>
        <div class="lbl1_block"><input type="radio" name="chkProducto" id="chkProducto" value="2">Montaje</div>
        <div class="lbl1_block"><input type="radio" name="chkProducto" id="chkProducto" value="3">Obras Civiles</div>
        <div class="lbl1_block"><input type="radio" name="chkProducto" id="chkProducto" value="3">Transporte</div>
        <div class="lbl1_block"><input type="radio" name="chkProducto" id="chkProducto" value="3">Serv.Ingenieria</div>
        <div class="lbl1_block"><input type="radio" name="chkProducto" id="chkProducto" value="3">Proy.Especiales</div>
        <div class="lbl1_block"><input type="radio" name="chkProducto" id="chkProducto" value="3">Otros</div>
    </div>
    <div id="divTipProducto" class="CTipProducto">
        <div class="lbl1_block"><strong>TIPO PRODUCTO/SERVICIO</strong></div>
        <div class="lbl1_block"><input type="radio" name="chkTipPro" id="chkTipPro" value="b1" checked="checked">Fab.Est.Met.Telecomunicaci&oacute;n</div>
        <div class="lbl1_block"><input type="radio" name="chkTipPro" id="chkTipPro" value="b2">Fab.Est.Met.Comerciales</div>
        <div class="lbl1_block"><input type="radio" name="chkTipPro" id="chkTipPro" value="b3">Fab.Est.Met.Electrificaci&oacute;n</div>						
        <div class="lbl1_block"><input type="radio" name="chkTipPro" id="chkTipPro" value="b3">Fab.Est.Met.Transporte</div>						
        <div class="lbl1_block"><input type="radio" name="chkTipPro" id="chkTipPro" value="b3">Fab.Est.Metalicas Menores</div>						
    </div>
    <div id="divModelo" class="CModelo">
        <div class="lbl1_block"><strong>MODELO</strong></div>
        <div class="lbl1_block"><input type="radio" name="checkbox" value="checkbox">Torre Arriostada.</div>
        <div class="lbl1_block"><input type="radio" name="checkbox2" value="checkbox"> Torre Autosoportada</div>
        <div class="lbl1_block"><input type="radio" name="checkbox2" value="checkbox"> Torre Ventada</div>
        <div class="lbl1_block"><input type="radio" name="checkbox3" value="checkbox"> Mastil</div>						
        <div class="lbl1_block"><input type="radio" name="checkbox3" value="checkbox"> Monopolo</div>						
        <div class="lbl1_block"><input type="radio" name="checkbox3" value="checkbox"> Monoposte</div>						
        <div class="lbl1_block"><input type="radio" name="checkbox3" value="checkbox"> Mastil</div>						
        <div class="lbl1_block"><input type="radio" name="checkbox3" value="checkbox"> Fast Size</div>		
        <div class="lbl1_block"><input type="radio" name="checkbox3" value="checkbox"> Torretas</div>		
    </div>
</div>
<div class="prod_inf">
    <table width="100%" border="1">
        <tr>
            <td><span class="lbl1">Mto.ejecuitado :</span></td>
            <td><span class="tit_group"><input  name="txtNombre" id="txtNombre" title="Nombres" value="Martin" type="text" class="cajaPequena"></span></td>
            <td><span class="lbl1">Mto.ejecuitado :</span></td>
            <td><span class="tit_group"><input  name="txtNombre" id="txtNombre" title="Nombres" value="Martin" type="text" class="cajaPequena"></span></td>
            <td><span class="lbl1">Mto.ejecuitado :</span></td>
            <td><span class="tit_group"><input  name="txtNombre" id="txtNombre" title="Nombres" value="Martin" type="text" class="cajaPequena"></span></td>
        </tr>
        <tr>
            <td><span class="lbl1">Observacion :</span></td>
            <td colspan="5"><span class="tit_group"><input  name="txtNombre" id="txtNombre" title="Nombres" value="Martin" type="text" class="cajaGrande"></span></td>
        </tr>
    </table>
</div>
<ul  class="lista_botones" id="alcanceSuministro" style="display:none;"><li>Alc. Suministro</li></ul>
<ul  class="lista_botones" id="nuevoProducto"><li>Nuevo</li></ul>
<ul  class="lista_botones" style="display: none;" id="cancelarProducto"><li>Cancelar</li></ul>	
<ul  class="lista_botones" style="display: none;" id="grabarProducto"><li>Grabar</li></ul>																					
<div style="padding-top: 40px;">
    <table width="98%" border="1" align="center" cellpadding="0" cellspacing="0">
        <thead>
            <tr bgcolor="#CCCCCC" height="27">
                <th scope="col" width="6%"><div align="center">Acciones</div></th>
                <th scope="col" width="9%"><div align="center">Tipo</div></th>
                <th scope="col" width="18%"><div align="center">Producto/Servicio</div></th>
                <th scope="col" width="14%"><div align="center">Modelo</div></th>
                <th scope="col" width="8%"><div align="center">Peso(kg)</div></th>
                <th scope="col" width="10%"><div align="center">Altura(m)</div></th>
                <th scope="col" width="10%"><div align="center">Cantidad</div></th>
                <th scope="col" width="10%"><div align="center">P.U.</div></th>
                <th scope="col" width="15%"><div align="center">Sub.Total</div></th>
            </tr>	
        </thead>
        <tfoot>
            <tr>
                <th scope="row">Total</th>										
                <td colspan="10" align="right">$/.<input type="text" class="cajaPequena" value="2000.00" title="Nombres" id="txtNombre" name="txtNombre"></td>
            </tr>
        </tfoot>                                                   
        <tbody>
            <tr>
                <th scope="row" bgcolor="#FFFFFF" align="center">
                    <span><img width="16" height="16" border="0" title="Modificar" src="../../img/modificar.png"></img></span>
                    <span><img width="16" height="16" border="0" title="Modificar" src="../../img/eliminar2.png"></img></span>    
                </th>
                <td align="center">Fabricaci&oacute;n</td>
                <td align="left">Fab.Est.Met.Telecomunicaci&oacute;n</td>
                <td align="left">Torre Arriostada</td>
                <td align="center">50</td>
                <td align="right">100.00</td>
                <td align="right"><input type="text" class="cajaMinima" value="6.00" title="Nombres" id="txtNombre" name="txtNombre"></td>
                <td align="right"><input type="text" class="cajaPequena" value="200.00" title="Nombres" id="txtNombre" name="txtNombre"></td>
                <td align="right"><input type="text" class="cajaPequena" value="1200.00" title="Nombres" id="txtNombre" name="txtNombre"></td>
            </tr>	
            <tr class="odd">
                <th scope="row" bgcolor="#FFFFFF" align="center">
                    <span><img width="16" height="16" border="0" title="Modificar" src="<?php echo img;?>modificar.png"></img></span>
                    <span><img width="16" height="16" border="0" title="Modificar" src="<?php echo img;?>eliminar2.png"></img></span>    
                </th>
                <td align="center">Fabricaci&oacute;n</td>
                <td align="left">Fab.Est.Met.Telecomunicaci&oacute;n</td>
                <td align="left">Mastil</td>
                <td align="center">80</td>
                <td align="right">120.00</td>
                <td align="right"><input type="text" class="cajaMinima" value="4.00" title="Nombres" id="txtNombre" name="txtNombre"></td>
                <td align="right"><input type="text" class="cajaPequena" value="200.00" title="Nombres" id="txtNombre" name="txtNombre"></td>
                <td align="right"><input type="text" class="cajaPequena" value="800.00" title="Nombres" id="txtNombre" name="txtNombre"></td>
            </tr>	
            <tr>
                <th scope="row">&nbsp;</th>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>   
            <tr bgcolor="#FFFFFF">
                <th scope="row">&nbsp;</th>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>   
            <tr bgcolor="#FFFFFF">
                <th scope="row">&nbsp;</th>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>   
            <tr bgcolor="#FFFFFF">
                <th scope="row">&nbsp;</th>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>               
        </tbody>
    </table>
</div>
