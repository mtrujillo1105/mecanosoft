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
    <script type="text/javascript" src="<?php echo js;?>jquery.metadata.js"></script>      
    <script type="text/javascript" src="<?php echo js;?>jquery.validate.js"></script>      
    <script type="text/javascript" src="<?php echo js;?>almacen/almacen.js"></script>
</head>	
<body>	
<div class="container">
    <div class="header"><?php echo $titulo;?></div>    
    <?php echo $form_open;?>
    <div style="width:100%; text-align: left;">
        <table class="fuente8" width="100%" cellspacing="0" cellpadding="6" border="0" bgcolor="#fff">
            <tr>
              <td width="16%">Nombre de almacen</td>
              <td width="42%"><input type="text" class="cajaMedia" name="descripcion" id="descripcion" value="<?php echo $lista->descripcion;?>"></td>
            </tr>
            <tr>
              <td width="16%">Direccion</td>
              <td width="42%"><input type="text" class="cajaMedia" name="direccion" id="direccion" value="<?php echo $lista->direccion;?>"></td>
            </tr>                            
        </table>
    </div>
    <div style="margin-top:20px; text-align: center">
        <a href="#" id="grabar"><img src="<?php echo img;?>botonaceptar.jpg" width="85" height="22" class="imgBoton" ></a>
        <a href="#" id="cancelar"><img src="<?php echo img;?>botoncancelar.jpg" width="85" height="22" class="imgBoton" ></a>
        <?php echo $oculto?>
    </div>
    <?php echo $form_close;?>
</div>
</body>
</html>