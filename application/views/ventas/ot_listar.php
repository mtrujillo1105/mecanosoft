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
<script type="text/javascript" src="<?php echo js;?>ventas/ot.js"></script>
</head>
<body>
<div class="container">
    <div class="header">ORDENES DE TRABAJO</div>
    <div class="case_top">
        <form method="post" id="frmBusqueda">
            <table width="98%" cellspacing="0" cellpadding="3" border="0" class="fuente8">
                <tbody>
                  <tr>
                        <td align="right" width="18%">O.T.:</td>
                        <td align="left" width="32%"><?php echo $selecttipoot;?>
                        <td align="right" width="18%">FECHA INI.:</td>
                        <td align="left">
                            <span id="Fecha1" style="width:150px;border:0px solid #000;float: left;">
                                <input  name="fecha_ini" id="fecha_ini" title="Fecha Inicio" value="<?php echo $fecha_ini;?>" type="text" class="cajaPequena" readonly="readonly">									
                                <img src="<?php echo img;?>calendario.png" name="Calendario1" id="Calendario1" width="16" height="16" border="0" id="Image1" onMouseOver="this.style.cursor='pointer'" title="Calendario">
                                <script type="text/javascript">
                                    Calendar.setup({
                                        inputField     :    "fecha_ini",      // id del campo de texto
                                        ifFormat       :    "%d/%m/%Y",       // formato de la fecha, cuando se escriba en el campo de texto
                                        button         :    "Calendario1"   // el id del bot�n que lanzar� el calendario
                                    });
                                </script>		
                            </span>
                            <span style="float:left;">Hasta:</span>
                            <span id="Fecha2" style="width:150px;border:0px solid #000;display:block;float: left;">
                                <input  name="fecha_fin" id="fecha_fin" title="Fecha Inicio" value="<?php echo $fecha_fin;?>" type="text" class="cajaPequena" readonly="readonly">									
                                <img src="<?php echo img;?>calendario.png" name="Calendario2" id="Calendario2" width="16" height="16" border="0" id="Image1" onMouseOver="this.style.cursor='pointer'" title="Calendario">
                                <script type="text/javascript">
                                    Calendar.setup({
                                        inputField     :  "fecha_fin",      // id del campo de texto
                                        ifFormat       :  "%d/%m/%Y",       // formato de la fecha, cuando se escriba en el campo de texto
                                        button         :  "Calendario2",   // el id del bot�n que lanzar� el calendario
                                        onChange        :  function(){
                                            alert('Bumerang');
                                        }
                                    });
                                </script>	                              
                            </span>
                        </td>                            
                    </tr>
                    <tr>
                        <td align="right">Proyecto</td>
                        <td align="left"><?php echo $selproyecto;?></td>
                        <td align="right">Moneda</td>
                        <td align="left"><?php echo $selmoneda;?></td>                        
                    </tr> 
                    <tr>
                        <td align="right">Nombre o Raz&oacute;n Social</td>
                        <td align="left"><?php echo $selcliente;?></td>
                        <td align="right">Estadp</td>
                        <td align="left"><?php echo $selestado;?></td>    
                    </tr>
                </tbody>
            </table>
        </table>            
    </div>
    <div class="case_botones">
        <ul class="lista_botones"><li id="salir" class="ot_listar">Salir</li></ul>
        <ul class="lista_botones"><li id="excel" class="ot_listar">Excel</li></ul>
<!--        <ul class="lista_botones"><li id="pdf" class="ot_listar">Pdf </li></ul>        -->
        <ul class="lista_botones"><li id="html" class="ot_listar">Buscar</li></ul>   
        <ul class="lista_botones"><li id="nuevo" class="ot_listar">Nueva O.T.</li></ul>
    </div> 
    <div class="case_registro">N de proveedores encontrados:&nbsp;105</div>        
    <div class="header">RELACIÓN DE ORDENES DE TRABAJO</div>  
    <div>
        <table width="100%" cellspacing="0" cellpadding="3" border="0">
            <THEAD>
                <tr class="cabeceraTabla">
                    <td width="10%">OT</td>
                    <td width="7%">FECHA</td>
                    <td width="17%">SITE</td>
                    <td width="9%">ESTADO</td>
                    <td width="25%">NOMBRE O RAZ&Oacute;N SOCIAL </td>
                    <td width="10%">PESO TEORICO(KG)</td>
                    <td width="16%">PACKING LIST</td>
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