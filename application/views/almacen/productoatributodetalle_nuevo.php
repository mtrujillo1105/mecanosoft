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
    <script type="text/javascript" src="<?php echo js;?>almacen/productoatributodetalle.js"></script>
</head>	
<body>
<div class="container">
    <div class="header"><?php echo $titulo;?></div>
    <?php echo $form_open;?>
    <div style="width:100%; text-align: left;">
        <table class="fuente8" width="100%" cellspacing="0" cellpadding="6" border="0" style="background: #fff;">
            <tr>
              <td width="16%">Producto</td>
              <td width="42%"><?php echo $selproducto;?> </td>
            </tr>     
            <tr>
              <td width="16%">Atributo</td>
              <td width="42%"><?php echo $selatributo;?> </td>
            </tr>           
            <tr>
              <td width="16%">Descripcion</td>
              <td width="42%">
                  <input type="text" class="cajaMedia" name="descripcion" id="descripcion" value="<?php echo $lista->descripcion;?>">
              </td>
            </tr>
            <tr>
              <td width="16%">Descripcion ampliada</td>
              <td width="42%">
                  <input type="text" class="cajaMedia" name="descriampliada" id="descriampliada" value="<?php echo $lista->descriampliada;?>">
              </td>
            </tr>  
            <tr>
              <td width="16%">Cantidad</td>
              <td width="42%">
                  <input type="text" class="cajaMedia" name="cantidad" id="cantidad" value="<?php echo $lista->cantidad;?>">
              </td>
            </tr> 
            <tr>
              <td width="16%">Caracteristica1</td>
              <td width="42%">
                  <input type="text" class="cajaMedia" name="caracteristica1" id="caracteristica1" value="<?php echo $lista->caracteristica1;?>">
              </td>
            </tr>      
            <tr>
              <td width="16%">Caracteristica2</td>
              <td width="42%">
                  <input type="text" class="cajaMedia" name="caracteristica2" id="caracteristica2" value="<?php echo $lista->caracteristica2;?>">
              </td>
            </tr>   
            <tr>
              <td width="16%">Caracteristica3</td>
              <td width="42%">
                  <input type="text" class="cajaMedia" name="caracteristica3" id="caracteristica3" value="<?php echo $lista->caracteristica3;?>">
              </td>
            </tr>   
            <tr>
              <td width="16%">Caracteristica4</td>
              <td width="42%">
                  <input type="text" class="cajaMedia" name="caracteristica4" id="caracteristica4" value="<?php echo $lista->caracteristica4;?>">
              </td>
            </tr>   
            <tr>
              <td width="16%">Caracteristica5</td>
              <td width="42%">
                  <input type="text" class="cajaMedia" name="caracteristica5" id="caracteristica5" value="<?php echo $lista->caracteristica5;?>">
              </td>
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