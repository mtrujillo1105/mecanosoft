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
    <script type="text/javascript" src="<?php echo js;?>almacen/producto.js"></script>				
</head>
<body>
    <div class="container">
        <div class="header"><?php echo $titulo_busqueda;?></div>
        <div class="case_top">
            <?php echo $form_open;?>
                <table class="fuente8" width="98%" cellspacing=0 cellpadding=3 border=0>					
                    <tr>
                    <td width="16%">Código </td>
                    <td width="68%" align="left"><input id="txtCodigo" type="text" class="cajaPequena" NAME="txtCodigo" maxlength="30" value="<?php echo $codigo; ?>">
                    <td width="5%">&nbsp;</td>
                    <td width="5%">&nbsp;</td>
                    <td width="6%" align="right"></td>
                    </tr>
                    <tr>
                        <td>Nombre</td>
                        <td align="left"><input id="txtNombre" name="txtNombre" type="text" class="cajaGrande" maxlength="100" value="<?php echo $nombre; ?>"></td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td>Familia</td>
                      <td align="left"><input id="txtFamilia" type="text" class="cajaGrande" NAME="txtFamilia" maxlength="100" value="<?php echo $familia; ?>"></td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      </tr>
                </table>
            <?php echo $form_close;?>
        </div>
        <div class="case_botones">
            <ul class="lista_botones"><li id="salir" class="ot_listar">Salir</li></ul>
            <ul class="lista_botones"><li id="nuevo" class="ot_listar">Nuevo Produco</li></ul>
            <ul class="lista_botones"><li id="imprimir" class="ot_listar">Imprimir</li></ul>
            <ul class="lista_botones"><li id="buscar" class="ot_listar">Buscar</li></ul>   
        </div>         
        <div class="case_registro">N de productos encontrados:&nbsp;<?php echo $registros;?></div>  
        <div style="border:0px solid #000;height:31px;background:#fff;">
            <div id="ttab1" class="tabulaciones">General</div>					
            <div id="ttab2" class="tabulaciones">Atributos</div>
            <div id="ttab3" class="tabulaciones">Componentes</div>
        </div>        
        <div class="header"><?php echo $titulo_tabla;?></div>  
        <div>
            <table width="100%" cellspacing="0" cellpadding="3" border="0">
                <THEAD>
                <tr class="cabeceraTabla">
                    <td width="3%">ITEM</td>
                    <td width="5%" align='center'>CODIGO</td>
                    <td width="30%">DESCRIPCION</td>	
                    <td width="20%">FAMILIA</td>	
                    <td width="15%">FABRICANTE</td>							
                    <td width="5%">ESTADO</td>
                    <td width="4%">&nbsp;</td>
                    <td width="4%">&nbsp;</td>
                </tr>					
                </THEAD>
                <tbody>
                <?php
                if(count($lista)>0){
                foreach($lista as $indice=>$valor){
                    $class = $indice%2==0?'itemParTabla':'itemImparTabla';
                    ?>
                    <tr class="<?php echo $class;?>">
                        <td><div align="center"><?php echo $indice+1;?></div></td>
                        <td><div align="center"><?php echo str_pad($valor->interno,"3","0",STR_PAD_LEFT);?></div></td>
                        <td><div align="left"><?php echo $valor->nombre;?></div></td>
                        <td><div align="left"><?php echo $valor->familia;?></div></td>
                        <td><div align="center"><?php echo $valor->fabricante;?></div></td>
                        <td>
                            <div align="center">
                                <image src='<?php echo img.($valor->estado==1?'green_light.gif':'green.gif');?>' border='0' title='Activo'>
                                <image src='<?php echo img.($valor->estado==0?'red_light.gif':'red.gif');?>' border='0' title='Activo'>
                            </div>
                        </td>
                        <td><div align="center"><a href='javascript:;' onclick='editar("<?php echo $valor->codigo;?>")'><img src='<?php echo img;?>modificar.png' width='16' height='16' border='0' title='Modificar'></a></div></td>
                        <td><div align="center"><a href='javascript:;' onclick='eliminar("<?php echo $valor->codigo;?>")'><img src='<?php echo img;?>eliminar.png' width='16' height='16' border='0' title='Modificar'></a></div></td>                            
                    </tr>
                    <?php
                    }
                }
                else{
                ?>
                    <tr><td colspan="8" class="mensaje">No hay ning&uacute;n registro que cumpla con los criterios de b&uacute;squeda</td></tr>
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
