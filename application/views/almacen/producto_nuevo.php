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
    <div class="header"><?php echo $titulo;?></div>
    <?php echo $form_open;?>
    <div style="float:left;width:100%; text-align: left;">
        <div style="width:100%">
            <table class="fuente8" width="100%" cellspacing="0" cellpadding="6" border="0" bgcolor="#fff">
              <tr>
                <td width="16%">Familia</td>
                <td width="42%">
                    <input type="text" readonly="readonly" class="cajaMedia" name="nombre_familia" id="nombre_familia" readonly="readonly" value="<?php echo $lista->familia_nombre;?>">
                    <a href="#" onclick='ver_familia();'><img height='16' width='16' src='<?php echo img;?>ver.png' title='Registrar Familia' border='0'></a>
                </td>
                <td width="16%">C&oacute;digo Producto</td>
                <td width="38%"><input type="text" id="interno" class="cajaMedia" style="width:60px; background-color: #E6E6E6" readonly="readonly" name="interno" value="<?php echo $lista->interno;?>"></td>
              </tr>
              <tr>
                <td>Nombre Producto</td>
                <td width="34%"><input type="text" class="cajaGrande" name="nombre" id="nombre" onblur="valida_nombre_producto();" value="<?php echo trim($lista->nombre);?>"></td>
                <td width="14%">Marca</td>
                <td width="38%"><?php echo $selmarca;?></td>
              </tr>
              <tr>
                <td>Fabricante</td>
                <td width="34%"><?php echo $selfabricante;?></td>
                <td width="14%">Moneda (*)</td>
                <td width="38%"><?php echo $selmoneda;?></td>
              </tr>
              <tr>
                <td>Modelo</td>
                <td width="34%"><input type="text" class="cajaMedia" name="modelo" id="modelo" value="<?php echo $lista->modelo;?>"></td>
                <td width="14%">Presentación</td>
                <td width="38%"><input type="text" class="cajaMedia" name="presentacion" id="presentacion" value="<?php echo $lista->presentacion;?>"></td>
              </tr>
              <tr>
                <td>Gen./ Ind. (*)</td>
                <td width="34%">
                    <select name="generico" id="generico" class="comboMedio">
                        <option value="0">::Seleccionar::</option>    
                        <option value="G" selected='selected'>Genérico (Sin N/S)</option>
                        <option value="I">Individual (Con N/S)</option>
                    </select>
                </td>
                <td width="14%">Estado</td>
                <td width="38%"><?php echo $selestado;?></td>
              </tr>
              <tr>
                <td>L&iacute;nea</td>
                <td width="34%"><?php echo $sellinea;?></td>
                <td width="14%" valign="top">Imagen</td>
                <td valign="top" width="38%">
                    <input name="imagen" id="imagen" style="font-size:0.9em" type="file" />
                    <?php if($lista->imagen!='') echo '<img style="margin-top:10px;" src="'.img.'img_db/'.$lista->imagen.'" alt="'.$lista->imagen.'" width="120" height="120" border="1" />' ?>
                </td>
              </tr>
              <tr>
                <td valign="top">Descripción</td>
                <td width="34%"><textarea rows="1"  cols="35" style="width:100%"  name="descripcion" id="descripcion"><?php echo $lista->descripcion;?></textarea></td>
                <td valign="top" width="14%">Tipo producto (*)</td>
                <td valign="top" width="38%"><?php echo $seltipo;?>&nbsp;</td>
              </tr>
              <tr>
                <td align="left" valign="top">Unidad medida</td>
                <td width="19%"><?php echo $selunidad;?>&nbsp;</td>
                <td width="16%">Comentario</td>
                <td width="42%"><textarea name="comentario" id="comentario" rows="1" cols="35"><?php echo $lista->comentario;?></textarea></td>                      
              </tr>
            </table>
        </div>
    </div>
    <div style="float:left;margin-left: auto;margin-right: auto;width: 98%;margin-top:15px;">
        <a href="javascript:;" id="grabar"><img src="<?php echo img;?>botonaceptar.jpg" width="85" height="22" class="imgBoton"></a>
        <a href="javascript:;" id="cancelar"><img src="<?php echo img;?>botoncancelar.jpg" width="85" height="22" class="imgBoton"></a>
        <?php echo $oculto;?>
    </div>
    <?php echo $form_close;?>
</div>
</body>
</html>