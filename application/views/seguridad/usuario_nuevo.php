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
    <script type="text/javascript" src="<?php echo js;?>seguridad/usuario.js"></script>			
</head>
<body>
<div class="container">
<?php echo $form_open;?>        
  <div class="case_header"><?php echo $titulo;?></div>
  <div class="case_body">
    <table width="100%" cellspacing="0" cellpadding="6" border="0">
        <tr>
          <td width="50%" class="formss">Codigo</td>
          <td width="50%">
              <input type="text" class="cajaMedia" name="codigo" id="codigo" value="<?php echo $lista->nombres;?>">
              <a href="#"><img height='16' width='16' id="ver_usuario" src='<?php echo img;?>ver.png' title='Buscar usuario' border='0'></a>
          </td>
        </tr>            
        <tr>
          <td class="formss">Nombres</td>
          <td><input type="text" class="cajaMedia" name="nombres" id="nombres" value="<?php echo $lista->nombres;?>"></td>
        </tr>
        <tr>
          <td class="formss">Apellido Paterno</td>
          <td><input type="text" class="cajaMedia" name="paterno" id="paterno" value="<?php echo $lista->paterno;?>"></td>
        </tr>
        <tr>
          <td class="formss">Apellido Materno</td>
          <td><input type="text" class="cajaMedia" name="materno" id="materno" value="<?php echo $lista->materno;?>"></td>
        </tr>
        <tr>
          <td class="formss">Usuario</td>
          <td><input type="text" class="cajaMedia" name="login" id="login" value="<?php echo $lista->login;?>"></td>
          </tr>   
          <tr>
            <td class="formss">Clave</td>
            <td><input type="password" class="cajaMedia" name="clave" id="clave" value="<?php echo $lista->clave;?>"></td>
          </tr>  
          <tr>
            <td class="formss">Rol</td>
            <td><?php echo $selrol;?></td>
          </tr>                         
      </table>
    </div>
    <div class="case_botones">
      <ul class="lista_botones"><li id="salir">Salir</li></ul>
      <ul class="lista_botones"><li id="nuevo">Nuevo</li></ul>        
      <ul class="lista_botones"><li id="eliminar">Eliminar</li></ul>           
      <ul class="lista_botones"><li id="grabar">Grabar</li></ul>        
    </div>
    <?php echo $oculto;?>
    <?php echo $form_close;?>       
</div>
<div id="basic-modal-content"><div id="mensaje">&nbsp;</div></div>      
</body>
</html>