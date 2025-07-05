<script type="text/javascript" src="<?php echo base_url();?>js/maestros/cargo.js"></script>				
<div id="pagina">
    <div id="zonaContenido">
        <div align="center">
            <div id="tituloForm" class="header"><?php echo $titulo_busqueda;?></div>
            <div id="frmBusqueda">
                <form id="form_busquedaCargo" name="form_busquedaCargo" method="post" action="<?php echo $action;?>">
                    <table class="fuente8" width="98%" cellspacing="0" cellpadding="5" border="0">
                        <tr>
                            <td align='left' width="13%">Nombre del cargo</td>
                            <td align='left'><input id="txtCargo" name="txtCargo" type="text" class="cajaGrande" maxlength="45" value="<?php echo $txtCargo;?>"></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                    </table>
                </form>
            </div>
            <div id="botonBusqueda">
                <ul id="imprimirCargo" class="lista_botones"><li id="imprimir">Imprimir</li></ul>
                <ul id="nuevoCargo" class="lista_botones"><li id="nuevo">Nuevo Cargo</li></ul>
                <ul id="limpiarCargo" class="lista_botones"><li id="limpiar">Limpiar</li></ul>
                <ul id="buscarCargo" class="lista_botones"><li id="buscar">Buscar</li></ul> 
            </div>
            <div id="lineaResultado">
              <table class="fuente8" width="100%" cellspacing=0 cellpadding=3 border=0>
                    <tr>
                    <td width="50%" align="left">N de cargos encontrados:&nbsp;<?php echo $registros;?> </td>
                    <td width="50%" align="right">&nbsp;</td>
              </table>
            </div>
            <div id="cabeceraResultado" class="header"><?php echo $titulo_tabla;;?></div>
            <div id="frmResultado">
                <table class="fuente8" width="100%" cellspacing="0" cellpadding="3" border="0" ID="Table1">
                    <tr class="cabeceraTabla">
                        <td width="5%">ITEM</td>
                        <td width="60%">NOMBRES DE CARGOS</td>
                        <td width="5%">&nbsp;</td>
                        <td width="5%">&nbsp;</td>
                        <td width="6%">&nbsp;</td>
                    </tr>
                        <?php
                        if(count($lista)>0){
                        foreach($lista as $indice=>$valor){
                                $class = $indice%2==0?'itemParTabla':'itemImparTabla';
                                ?>
                                <tr class="<?php echo $class;?>">
                                        <td><div align="center"><?php echo $valor[0];?></div></td>
                                        <td><div align="left"><?php echo $valor[1];?></div></td>
                                        <td><div align="center"><?php echo $valor[2];?></div></td>
                                        <td><div align="center"><?php echo $valor[3];?></div></td>
                                        <td><div align="center"><?php echo $valor[4];?></div></td>
                                </tr>
                                <?php
                                }
                        }
                        else{
                        ?>
                        <table width="100%" cellspacing="0" cellpadding="3" border="0" class="fuente8">
                                <tbody>
                                        <tr>
                                                <td width="100%" class="mensaje">No hay ning&uacute;n registro que cumpla con los criterios de b&uacute;squeda</td>
                                        </tr>
                                </tbody>
                        </table>
                        <?php
                        }
                        ?>
                </table>
                <input type="hidden" id="iniciopagina" name="iniciopagina">
                <input type="hidden" id="cadena_busqueda" name="cadena_busqueda">
                <input type="hidden" name="base_url" id="base_url" value="<?php echo base_url();?>">
            </div>
            <div style="margin-top: 15px;"><?php echo $paginacion;?></div>
        </div>
    </div>
</div>