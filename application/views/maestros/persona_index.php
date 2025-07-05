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
    <script type="text/javascript" src="<?php echo js;?>jquery.metadata.js"></script>   	
    <script type="text/javascript" src="<?php echo js;?>jquery.validate.js"></script>   	    
    <script type="text/javascript" src="<?php echo js;?>maestros/persona.js"></script>
</head>
<body>
<div class="container">
    <div class="header"><?php echo $titulo_busqueda;?></div>
    <div class="case_top">
        <?php echo $form_open;?>
            <table class="fuente8" width="98%" cellspacing=0 cellpadding=3 border=0>
                <tr>
                    <td width="16%" align="right">N. de Documento </td>
                    <td width="68%" align="left"><?php echo $txtnumero;?></td>
                    <td width="5%">&nbsp;</td>
                    <td width="5%">&nbsp;</td>
                    <td width="6%" align="right"></td>
                </tr>
                <tr>
                    <td align="right">Nombre </td>
                    <td align="left"><?php echo $txtnombre;?></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td align="right">Tel&eacute;fono/Celular</td>
                    <td align="left"><?php echo $txttelefono;?></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
            </table>
        <?php echo $form_open;?>
    </div>
    <div class="case_botones">
        <ul class="lista_botones"><li id="salir" class="ot_listar">Salir</li></ul>
        <ul class="lista_botones"><li id="nuevo" class="ot_listar">Nueva Persona</li></ul>
        <ul class="lista_botones"><li id="imprimir" class="ot_listar">Imprimir</li></ul>
        <ul class="lista_botones"><li id="buscar" class="ot_listar">Buscar</li></ul>   
    </div> 
    <div class="case_registro">N de productos encontrados:&nbsp;<?php echo $registros;?></div>  
    <div class="header"><?php echo $titulo_tabla;?></div>  
    <div>
        <table width="100%" cellspacing="0" cellpadding="3" border="0">
            <THEAD>
            <tr class="cabeceraTabla">
                <td width="8%">ITEM</td>
                <td width="8%">DNI</td>
                <td width="38%">NOMBRE  </td>
                <td width="13%">TEL&Eacute;FONO</td>
                <td width="19%">M&Oacute;VIL</td>
                <td width="5%">&nbsp;</td>
                <td width="5%">&nbsp;</td>
            </tr>
            </THEAD>
            <tbody>
                <?php
                if(count($lista)>0){
                    foreach($lista as $item => $value){
                        ?>
                        <tr class="<?php echo ($item%2==0?'itemParTabla':'itemImparTabla');?>">
                            <td align='center'><?php echo $item+1;?></td>
                            <td align='center'><?php echo $value->numero;?></td>
                            <td align='left'><?php echo $value->nombres." ".$value->paterno." ".$value->materno;?></td>
                            <td align='center'><?php echo $value->telefono;?></td>
                            <td align='center'><?php echo $value->movil;?></td>
                            <td align='center'><a href='#' onclick='editar("<?php echo $value->codigo;?>")'><img src="<?php echo img;?>modificar.png" width='16' height='16' border='0' title='Modificar'></a></td>
                            <td align='center'><a href='#' onclick='eliminar("<?php echo $value->codigo;?>")'><img src="<?php echo img;?>eliminar.png" width='16' height='16' border='0' title='Modificar'></a></td>                
                        </tr>
                        <?php
                    }
                }
                else{
                    ?>
                    <tr class="itemParTabla"><td colspan="6">:::NO EXISTEN REGISTROS:::</td></tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>
    <div style="margin-top: 15px;"><?php echo $paginacion;?></div>
</div>
 <div id="basic-modal-content"><div id="mensaje">&nbsp;</div></div>    
</body>
</html>