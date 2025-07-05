<div style="padding-bottom: 20px;">
    <div style="width:100%;height:35px;text-align: left;pading-bottom: 0px;">
        <span class="lbl2">Partidas</span>
    </div>
    <table width="98%" border="1" align="center" cellpadding="0" cellspacing="0" id="tblPresupuesto">
        <thead>
            <tr>
                <th scope="col" width="55%" bgcolor="#CCCCCC"><div align="center">Partidas</div></th>
                <th scope="col" width="15%" bgcolor="#CCCCCC"><div align="center">Presupuestado</div></th>
            </tr>	
        </thead>                                                 
        <tbody>
            <tr bgcolor="#FFFFFF" id="1" class="verDetPartida">
                <td align="left"><a href="#">Materiales</a></td>
                <td align="right">1200.00</td>
            </tr>	
            <tr bgcolor="#FFFFFF" id="2" class="verDetPartida">
                <td align="left"><a href="#">Servicios</a></td>
                <td align="right">00.00</td>
            </tr>
            <tr bgcolor="#FFFFFF" id="3" class="verDetPartida">
                <td align="left"><a href="#">Mano de obra</a></td>
                <td align="right">00.00</td>
            </tr>            
        </tbody>
    </table>    
</div>
<ul class="lista_botones" id="nuevoProducto"><li>Nuevo</li></ul> 
<div style="width:100%;height:35px;text-align: left;pading-bottom: 0px;">
    <span class="lbl2">Sub Partidas: Servicios</span>
</div>
<div id="divPartida">
    <table width="98%" border="1" align="center" cellpadding="0" cellspacing="0">
        <thead>
            <tr bgcolor="#CCCCCC">
                <th scope="col" width="52%"><div align="center">Partida</div></th>
                <th scope="col" width="12%"><div align="center">Presupuestado</div></th>
                <th scope="col" width="12%"><div align="center">Ejecutado</div></th>
            </tr>	
        </thead>                                                 
        <tbody>
            <tr bgcolor="#FFFFFF" id="1">
                <td align="left">Internos</td>
                <td align="right">
                    <span id="lblPresup1">1200.00</span>
                    <span id="txtPresup1" style="display: none;"><input type="text" class="cajaPequena" value="1200.00"></span>
                </td>
                <td align="right">
                    <span id="lblEjec1">0.00</span>
                    <span id="txtEjec1" style="display: none;"><input type="text" class="cajaPequena" value="00.00"></span>                    
                </td>
            </tr>	
            <tr class="odd" bgcolor="#FFFFFF" id="2">
                <td align="left">Externos</td>
                <td align="right">
                    <span id="lblPresup2">00.00</span>
                    <span id="txtPresup2" style="display: none;"><input type="text" class="cajaPequena" value="00.00"></span>
                </td>
                <td align="right">
                    <span id="lblEjec2">0.00</span>
                    <span id="txtEjec2" style="display: none;"><input type="text" class="cajaPequena" value="00.00"></span>                                        
                </td>              
            </tr>
            <tr class="odd" bgcolor="#FFFFFF" id="3">
                <td align="left">Otros</td>
                <td align="right">
                    <span id="lblPresup3">00.00</span>
                    <span id="txtPresup3" style="display: none;"><input type="text" class="cajaPequena" value="00.00"></span>                    
                </td>
                <td align="right">
                    <span id="lblEjec3">0.00</span>
                    <span id="txtEjec3" style="display: none;"><input type="text" class="cajaPequena" value="00.00"></span>                                        
                </td>              
            </tr>               
        </tbody>
    </table>
</div>