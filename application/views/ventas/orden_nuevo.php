<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>	
    <title><?php echo titulo;?></title>
    <META HTTP-EQUIV="Refresh" content="300"> 
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="Content-Language" content="es"> 
    <link rel="stylesheet" href="<?php echo css;?>estilos.css" type="text/css">   
    <script type="text/javascript" src="<?php echo js;?>constants.js"></script> 
    <script type="text/javascript" src="<?php echo js;?>jquery/jquery.js"></script>
    <script type="text/javascript" src="<?php echo js;?>jquery/jquery.simplemodal.js"></script>                 
    <script type="text/javascript" src="<?php echo js;?>ventas/orden.js"></script>	
</head>
<body>
<div class="container">
    <?php echo $form_open;?>    
    <div class="case_header"><?php echo $titulo;?></div>
    <div class="case_body">
        <table width="100%" cellspacing="0" cellpadding="6" border="0">
          <tr>
            <td width="16%">OT Nro</td>
            <td width="38%">
                <input type="text" readonly="readonly" class="cajaPequena" name="numero" id="numero" readonly="readonly" value="">
                <a href="#"><img height='16' width='16' id="ver_orden" src='<?php echo img;?>ver.png' title='Registrar Familia' border='0'></a>
            </td>
            <td width="16%">N°Presup</td>
            <td width="42%">
                <input type="text" readonly="readonly" class="cajaPequena" name="nombre_familia" id="nombre_familia" readonly="readonly" value="">
            </td>                
            <td width="16%">N°O.C.</td>
            <td width="38%"><input type="text" id="interno" class="cajaPequena" style="background-color: #E6E6E6" readonly="readonly" name="interno" value=""></td>
          </tr>
          <tr>
            <td>Cliente</td>
            <td colspan="3">
                <input type="text" class="cajaMedia" name="ruc" id="ruc">
                <input type="text" class="cajaGrande" name="razon_social" id="razon_social">                        
                <a href="#"><img height='16' width='16' id="ver_cliente" src='<?php echo img;?>ver.png' title='Registrar Familia' border='0'></a>
            </td>
            <td>L.Entrega</td>
            <td><input type="text" class="cajaMedia" name="nombre" id="nombre" onblur="valida_nombre_producto();" value=""></td>
          </tr>
          <tr>
            <td>Departamento</td>
            <td><?php echo $seldpto;?></td>
            <td>Provincia</td>
            <td><?php echo $selprov;?></td>
            <td>Distrito</td>
            <td><?php echo $seldist;?></td>                
          </tr>
          <tr>
            <td>F.Apertura</td>
            <td><input type="text" class="cajaPequena" name="modelo" id="modelo" value=""></td>
            <td>F.Inicio</td>
            <td><input type="text" class="cajaPequena" name="presentacion" id="presentacion" value=""></td>
            <td>F.Estimada</td>
            <td><input type="text" class="cajaPequena" name="presentacion" id="presentacion" value=""></td>                
          </tr>
          <tr>
            <td>Responsable</td>
            <td><?php echo $selusuario;?></td>
            <td>Moneda</td>
            <td><?php echo $selmoneda;?></td>
            <td>Importe</td>
            <td><input type="text" class="cajaPequena" name="modelo" id="modelo" value=""></td>                
          </tr>
          <tr>
            <td>Tipo de orden</td>
            <td><?php echo $seltipoorden;?></td>
            <td valign="top">Tipo de producto</td>
            <td valign="top" width="38%"><?php echo $selcurso;?></td>
            <td>Peso</td>
            <td><input type="text" class="cajaPequena" name="modelo" id="modelo" value=""></td>      
          </tr>
          <tr>
            <td valign="top">Descripción</td>
            <td colspan="5"><textarea rows="5"  style="width:98%"  name="descripcion" id="descripcion"></textarea></td>
          </tr>
          <tr>
            <td valign="top">Comentario</td>
            <td colspan="5"><textarea name="comentario" id="comentario" rows="5" style="width:98%"></textarea></td>                      
          </tr>
        </table>     
    </div>
    <div class="case_botones">
        <ul class="lista_botones"><li id="salir">Salir</li></ul>   
        <ul class="lista_botones"><li id="imprimir">Imprimir</li></ul>
        <ul class="lista_botones"><li id="nuevo">Nuevo</li></ul>        
        <ul class="lista_botones"><li id="eliminar">Eliminar</li></ul>   
        <ul class="lista_botones"><li id="grabar">Grabar</li></ul>
    </div>
    <?php echo $oculto;?>
    <?php echo $form_close;?>    
</div>  
</body>
</html>