<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>	
    <title><?php echo titulo;?></title>
    <META HTTP-EQUIV="Refresh" content="300"> 
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="Content-Language" content="es"> 
    <link rel="stylesheet" href="<?php echo css;?>estilos.css" type="text/css">
    <script type="text/javascript" src="<?php echo js;?>constants.js"></script> 
    <script type="text/javascript" src="<?php echo js;?>jquery.js"></script>
    <script type="text/javascript" src="<?php echo js;?>jquery.simplemodal.js"></script>      
    <script type="text/javascript" src="<?php echo js;?>almacen/unidadmedida.js"></script>
</head>	
<body>
<div class="container">
    <div class="header"><?php echo $titulo;?></div>
    <?php echo $form_open;?>
    <div style="width:100%; text-align: center;">
        <table class="fuente8" width="100%" cellspacing="0" cellpadding="6" bgcolor="#fff">
            <tr>
              <td width="50%">Codigo</td>
              <td width="50%">
                  <input type="text" class="cajaMinima" name="unidad" id="unidad" value="<?php echo trim($lista->unidad);?>" readonly="readonly">
                  <a href="#"><img height='16' width='16' id="ver_unidad" src='<?php echo img;?>ver.png' title='Buscar Unidad' border='0'></a>
              </td>
            </tr>            
            <tr>
              <td width="50%">Descripcion</td>
              <td width="50%"><input type="text" class="cajaMedia" name="descripcion" id="descripcion" value="<?php echo $lista->descripcion;?>"></td>
            </tr>
            <tr>
              <td>Simbolo</td>
              <td><input type="text" class="cajaMedia" name="simbolo" id="simbolo" value="<?php echo $lista->simbolo;?>"></td>
            </tr>                            
        </table>
    </div>
    <div class="case_botones">
        <ul class="lista_botones"><li id="grabar">Grabar</li></ul>
        <ul class="lista_botones"><li id="imprimir">Imprimir</li></ul>
        <ul class="lista_botones"><li id="nuevo">Nuevo</li></ul>        
        <ul class="lista_botones"><li id="eliminar">Eliminar</li></ul>   
        <ul class="lista_botones"><li id="salir">Cancelar</li></ul>        
    </div>       
    <?php echo $oculto;?>
    <?php echo $form_close;?>
</div>
 <div id="basic-modal-content"><div id="mensaje">&nbsp;</div></div>   
</body>
</html>