<!DOCTYPE html>
<html>
<head>
<!-- Calendario -->
<link rel="stylesheet" href="<?php echo css;?>estilos.css" type="text/css">
<link rel="stylesheet" href="<?php echo css;?>nav.css" type="text/css">
<link rel="stylesheet" href="<?php echo css;?>theme.css" type="text/css">
<!--link rel="stylesheet" href="<?php echo css;?>calendario/calendar-win2k-2.css" type="text/css" media="all" title="win2k-cold-1"/-->	
<!-- Calendario -->	
<script type="text/javascript" src="<?php echo js;?>constants.js"></script> 

<!--script type="text/javascript" src="<?php echo js;?>calendario/calendar.js"></script>
<script type="text/javascript" src="<?php echo js;?>calendario/calendar-es.js"></script>
<script type="text/javascript" src="<?php echo js;?>calendario/calendar-setup.js"></script>
<script type="text/javascript" src="<?php echo js;?>jquery.js"></script-->
<script src="<?php echo js;?>jquery.js"></script>
	<link rel="stylesheet" href="<?php echo css;?>themes/base/jquery.ui.all.css">
	<script src="<?php echo js;?>jquery/jquery-1.8.2.js"></script>
	<script src="<?php echo js;?>jquery/jquery.ui.core.js"></script>
	<script src="<?php echo js;?>jquery/jquery.ui.widget.js"></script>
	<script src="<?php echo js;?>jquery/jquery.ui.datepicker.js"></script>
        
<script type="text/javascript" src="<?php echo js;?>almacen/ningreso.js"></script>

<script>
            
        function blockui(){
            $.blockUI({ 
                message: 'Espere un momento por favor.',
                css: { 
                    border: 'none',
                   font: '20px',
                    padding: '15px', 
                    backgroundColor: '#000', 
                    '-webkit-border-radius': '10px', 
                    '-moz-border-radius': '10px', 
                    opacity: .5, 
                    color: '#fff' 
                } 
            }); 
        }
        
        $(function() {
		$('#fecha_ini').datepicker({
                        maxDate: 'today',
			changeMonth: true,
			changeYear: true
                        
		});
	});
	
          $(function() {
		$('#fecha_fin').datepicker({
                    maxDate: 'today',
                    changeMonth: true,
                    changeYear: true
		});
	});
	</script>
        
        
</head>
<body>
<div class="container">
    <div class="header"><?php echo $titulo_busqueda;?></div>
    <div class="case_top" style="height:60px">
        <form method="post" id="frmBusqueda">
            <table width="98%" cellspacing="0" cellpadding="3" border="0" class="fuente8">
                <tbody>
                    <tr>
                        <td align="right" width="18%">Fechas :</td>
                        <td align="left" width="32%">
                            Desde: <input type="text" maxlength="15" readonly name="txtNumDoc" class="cajaPequena" id="fecha_ini">   
                            Hasta: <input type="text" maxlength="15" readonly name="txtNumDoc" class="cajaPequena" id="fecha_fin">  
                   
                            
                            <!--input type="text" maxlength="15" name="txtNumDoc" class="cajaPequena" id="txtNumDoc"-->
                        <td align="right" width="18%"> </td>
                        <td align="left">
                            
                     </tr>
                    <!--tr>
                        <td align="right">Site</td>
                        <td align="left"><input type="text" maxlength="15" name="txtTelefono" class="cajaPequena" id="txtTelefono"></td>
                        <td align="right"></td>
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
                    </tr-->                    
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
    <!--div class="case_registro">N de proveedores encontrados:&nbsp;105</div-->        
    <div class="header"><?php echo $titulo_tabla;?></div>  
    <div>
        <table width="100%" cellspacing="0" cellpadding="3" border="0">
            <THEAD>
                <tr class="cabeceraTabla">
                    <td width="10px">ITEM</td>
                    <td width="90px">FECHA</td>
                    <td width="150px">CONSTANCIA</td>
                    <td width="120px">O.S.</td>
                    <td width="150px">NOMBRE O RAZ&Oacute;N SOCIAL </td>
                    <td width="150px">GUIA CLIENTE</td>
                    <td width="150px">REFERENCIA</td>
                    <td width="50px">PIEZAS</td>
                    <td width="150px">MATERIAL</td>
                    <td width="400px">MOTIVO</td>
                    <td width="90px">PESO (KG)</td>
                    <!--td width="2%">&nbsp;</td>
                    <td width="2%">&nbsp;</td>
                    <td width="2%">&nbsp;</td-->
                </tr>
            </THEAD>    
            <tbody><?php echo $fila;?></tbody>
        </table>
    </div>                
</div>
</body>
</html>