<!DOCTYPE html>
<html>
<head>
<!-- Calendario -->
<link rel="stylesheet" href="<?php echo css;?>estilos.css" type="text/css">
<link rel="stylesheet" href="<?php echo css;?>nav.css" type="text/css">
<link rel="stylesheet" href="<?php echo css;?>theme.css" type="text/css">
<link rel="stylesheet" href="<?php echo css;?>calendario/calendar-win2k-2.css" type="text/css" media="all" title="win2k-cold-1"/>	
<!-- Calendario -->	
<script type="text/javascript" src="<?php echo js;?>constants.js"></script> 
<script type="text/javascript" src="<?php echo js;?>calendario/calendar.js"></script>
<script type="text/javascript" src="<?php echo js;?>calendario/calendar-es.js"></script>
<script type="text/javascript" src="<?php echo js;?>calendario/calendar-setup.js"></script>
<script type="text/javascript" src="<?php echo js;?>jquery.js"></script>
<script type="text/javascript" src="<?php echo js;?>almacen/ningreso.js"></script>
</head>
<body>
<div class="container">
    <div class="header"><?php echo $titulo_busqueda;?></div>
    <div class="case_top">
        <form method="post" id="frmBusqueda">
            <table width="98%" cellspacing="0" cellpadding="3" border="0" class="fuente8">
                <tbody>
                    <tr>
                        <td align="right" width="18%">Cliente</td>
                        <td align="left" width="32%"><input type="text" maxlength="15" name="txtNumDoc" class="cajaPequena" id="txtNumDoc">
                        <td align="right" width="18%">Hasta</td>
                        <td align="left"><input type="text" maxlength="15" name="txtNumDoc" class="cajaPequena" id="txtNumDoc">                            
                    </tr>
                    <tr>
                        <td align="right">Site</td>
                        <td align="left"><input type="text" maxlength="15" name="txtTelefono" class="cajaPequena" id="txtTelefono"></td>
                        <td align="right">Estado</td>
                        <td align="left">
                            <select class="comboMedio" name="cboTipoProveedor" id="cboTipoProveedor">
                                <option selected="selected" value="">::Seleccionar::</option>
                                <option value="N">En Proceso</option>
                                <option value="J">Anulada</option>
                                <option value="J">Cerrada</option>
                                <option value="J">Stand Bye</option>
                            </select>
                        </td>                        
                    </tr>                   
                    <tr>
                        <td align="right">Nombre o Raz&oacute;n Social</td>
                        <td align="left" colspan="3"><input type="text" maxlength="45" class="cajaGrande" name="txtNombre" id="txtNombre"></td>
                    </tr>                    
                </tbody>
            </table>
        </table>            
    </div>
    <div class="case_botones">
        <ul class="lista_botones"><li id="salir" class="ot_listar">Salir</li></ul>
        <ul class="lista_botones"><li id="excel" class="ot_listar">Excel</li></ul>
        <ul class="lista_botones"><li id="pdf" class="ot_listar">Pdf </li></ul>
        <ul class="lista_botones"><li id="nuevo" class="ot_listar">Nueva O.T.</li></ul>
        <ul class="lista_botones"><li id="buscar" class="ot_listar">Buscar</li></ul>   
    </div> 
    <div class="case_registro">N de proveedores encontrados:&nbsp;105</div>        
    <div class="header"><?php echo $titulo_tabla;?></div>  
    <div>
        <table width="100%" cellspacing="0" cellpadding="3" border="0">
            <THEAD>
                <tr class="cabeceraTabla">
                    <td width="5%">ITEM</td>
                    <td width="13%">OT</td>
                    <td width="7%">FECHA</td>
                    <td width="9%">SITE</td>
                    <td width="9%">ESTADO</td>
                    <td width="20%">NOMBRE O RAZ&Oacute;N SOCIAL </td>
                    <td width="26%">OBSERVACIONES</td>
                    <td width="5%">TIEMPO(DIAS)</td>
                    <td width="2%">&nbsp;</td>
                    <td width="2%">&nbsp;</td>
                    <td width="2%">&nbsp;</td>
                </tr>
            </THEAD>    
            <tbody><?php echo $fila;?></tbody>
        </table>
    </div>                
</div>
</body>
</html>