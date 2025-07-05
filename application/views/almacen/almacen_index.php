<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title><?php echo titulo;?></title>
    <META HTTP-EQUIV="Refresh" content="300"> 
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />   
    <meta http-equiv="Content-Language" content="es"> 
    <link rel="stylesheet" href="<?php echo css;?>estilos.css" type="text/css">
    <link rel="stylesheet" href="<?php echo css;?>basic.css" type="text/css">          
    <script type="text/javascript" src="<?php echo js;?>constants.js"></script> 
    <script type="text/javascript" src="<?php echo js;?>jquery.js"></script>   
    <script type="text/javascript" src="<?php echo js;?>jquery.simplemodal.js"></script>      
    <script type="text/javascript" src="<?php echo js;?>almacen/almacen.js"></script>			
</head>
<body>	
<div class="container">
    <div class="header"><?php echo $titulo_busqueda;?></div>
    <div class="case_top2">
        <?php echo $form_open;?>
            <table class="fuente8" width="98%" cellspacing=0 cellpadding="5" border=0>
                <tr>
                    <td align='left' width="13%">Nombre almacen</td>
                    <td align='left'><input id="unidadmedida" class="cajaMedia" type="text" maxlength="100" value="" name="unidadmedida"></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td align='left' width="13%">Tipo almacen</td>
                    <td align='left'><input id="unidadmedida" class="cajaMedia" type="text" maxlength="100" value="" name="unidadmedida"></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
            </table>
        <?php echo $form_close;?>
    </div>        
    <div class="case_botones">
        <ul class="lista_botones"><li id="salir" class="ot_listar">Salir</li></ul>
        <ul class="lista_botones"><li id="nuevo" class="ot_listar">Nuevo Almacen</li></ul>
        <ul class="lista_botones"><li id="imprimir" class="ot_listar">Imprimir</li></ul>
        <ul class="lista_botones"><li id="buscar" class="ot_listar">Buscar</li></ul>   
    </div>   
    <div class="case_registro">N de productos encontrados:&nbsp;<?php echo $registros;?></div>  
    <div class="header"><?php echo $titulo_tabla;?></div>  
        <div id="frmResultado">
        <table class="fuente8" width="100%" cellspacing="0" cellpadding="3" border="0" ID="Table1">
            <THEAD>
                <tr class="cabeceraTabla">
                    <td width="5%">ITEM</td>
                    <td width="25%">NOMBRE</td>
                    <td width="20%">T.ALMACEN</td>
                    <td width="40%">DIRECCION</td>
                    <td width="5%">&nbsp;</td>
                    <td width="5%">&nbsp;</td>
                </tr> 
            </THEAD>
            <?php
            if(count($lista)>0){
                foreach($lista as $indice=>$valor)
                {
                    $class = $indice%2==0?'itemParTabla':'itemImparTabla';
                    ?>
                    <tr class="<?php echo $class;?>">
                    <td><div align="center"><?php echo $indice+1;?></div></td>
                    <td><div align="left"><?php echo $valor->descripcion;?></div></td>
                    <td><div align="left"><?php echo $valor->tipoalmacen;?></div></td>
                    <td><div align="center"><?php echo $valor->direccion;?></div></td>
                    <td><div align="center"><a href='#' onclick='editar("<?php echo $valor->codigo;?>")'><img src='<?php echo img;?>modificar.png' width='16' height='16' border='0' title='Modificar'></a></div></td>
                    <td><div align="center"><a href='#' onclick='eliminar("<?php echo $valor->codigo;?>")'><img src='<?php echo img;?>eliminar.png' width='16' height='16' border='0' title='Modificar'></a></div></td>
                    </tr>
                    <?php
                }
            }
            else{
            ?>
                <tr><td width="100%" class="mensaje">No hay ning&uacute;n registro que cumpla con los criterios de b&uacute;squeda</td></tr>
            <?php
            }
            ?>
        </table>
        </div>
    <div style="margin-top: 15px;"><?php echo $paginacion;?></div>
</div>
 <div id="basic-modal-content"><div id="mensaje">&nbsp;</div></div>   
</body>
</html>