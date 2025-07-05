<!--<script type="text/javascript" src="<?php echo base_url();?>js/jquery.validate.min.js"></script>-->
<script type="text/javascript" src="<?php echo base_url();?>js/maestros/formapago.js"></script>
<div id="pagina">
    <div id="zonaContenido">
    <div align="center">
        <div id="tituloForm" class="header"><?php echo $titulo_busqueda;?></div>
        <div id="frmBusqueda" >
            <?php echo $form_open;?>
                <table class="fuente8" width="98%" cellspacing=0 cellpadding="5" border=0>
                    <tr>
                        <td align='left' width="13%">Nombre Forma pago</td>
                        <td align='left'><?php echo $nombre_formapago;?></td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                </table>
            <?php echo $form_close;?>
        </div>
        <div id="botonBusqueda">
            <ul id="imprimirFormapago" class="lista_botones"><li id="imprimir">Imprimir</li></ul>
            <ul id="nuevaFormapago" class="lista_botones"><li id="nuevo">Nueva Forma de Pago</li></ul>
            <ul id="limpiarFormapago" class="lista_botones"><li id="limpiar">Limpiar</li></ul>
            <ul id="buscarFormapago" class="lista_botones"><li id="buscar">Buscar</li></ul> 
        </div>
        <div id="lineaResultado">
            <table class="fuente8" width="100%" cellspacing=0 cellpadding=3 border=0>
                <tr>
                    <td width="50%" align="left">N de formas de pago encontradas:&nbsp;<?php echo $registros;?> </td>
                    <td width="50%" align="right">&nbsp;</td>
                </tr>
            </table>
        </div>
            <div id="cabeceraResultado" class="header"><?php echo $titulo_tabla;;?></div>
            <div id="frmResultado">
            <table class="fuente8" width="100%" cellspacing="0" cellpadding="3" border="0" ID="Table1">
                    <tr class="cabeceraTabla">
                        <td width="5%">ITEM</td>
                        <td width="80%">NOMBRE FORMAS DE PAGO</td>
                        <td width="5%">&nbsp;</td>
                        <td width="5%">&nbsp;</td>
                        <td width="5%">&nbsp;</td>
                    </tr>
                    <?php
                    if(count($lista)>0){
                        foreach($lista as $indice=>$valor)
                        {
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