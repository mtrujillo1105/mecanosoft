<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php echo js;?>
    <title><?php echo titulo;?></title>
    <!-- Calendario -->
    <script type="text/javascript" src="<?php echo js;?>calendario/calendar.js"></script>
    <script type="text/javascript" src="<?php echo js;?>calendario/calendar-es.js"></script>
    <script type="text/javascript" src="<?php echo js;?>calendario/calendar-setup.js"></script>
    <!-- Calendario -->	    
    <script type="text/javascript" src="<?php echo js;?>JSCookMenu.js"></script>	
    <script type="text/javascript" src="<?php echo js;?>theme.js"></script>	
    <script type="text/javascript" src="<?php echo js;?>jquery.js"></script>	
    <script type="text/javascript" src="<?php echo js;?>constants.js"></script>     
    <script type="text/javascript" src="<?php echo js;?>superlink.js"></script>	
    <script type="text/javascript" src="<?php echo js;?>inicio.js"></script>	
    <link rel="stylesheet" href="<?php echo css;?>estilos.css" type="text/css">
    <link rel="stylesheet" href="<?php echo css;?>nav.css" type="text/css">
    <link rel="stylesheet" href="<?php echo css;?>theme.css" type="text/css">
    <link rel="stylesheet" href="<?php echo css;?>calendario/calendar-win2k-2.css" type="text/css" media="all" title="win2k-cold-1"/>	
</head>
<body>
    <div align="center" class="container">
        <div align='center' class='error' id='divMayus' style='visibility:hidden'>Recuerde Colocar Usuario y Clave en MAYUSCULAS .</div>
        <div class="div" style="bgcolor:'<?php echo img;?>login ferresat.jpg'">
            <div>
                <?php echo $form_open;?>
                    <table width="50%" cellspacing="0" cellpadding="0">
                      <tr align="center">
                          <td colspan="3"><strong>INGRESO AL SISTEMA</strong></td>
                      </tr>
                      <tr>
                        <td>Empresa</td>
                        <td align="left"><label>
                          <?php echo $cboEntidad;?>
                        </label></td>
                        <th rowspan="3"><img src="<?php echo img;?>mimco.jpg" border="0" width="147" height="150"/></th>
                      </tr>                      
                      <tr>
                        <td>Usuario </td>
                        <td align="left"><label><?php echo $txtUsuario;?></label></td>
                      </tr>
                      <tr>
                        <td>Clave </td>
                        <td align="left"><label><?php echo $txtClave;?></label></td>
                      </tr>   
                    </table>
                <?php echo $form_close;?>
            </div>
            <h3><a href="#">¿Olvido su contraseña?</a></h3> 
            <div align="center" style="width:100%; margin-top: 4px; border:0px solid #000;">
                <a href="#" id="ingresar"><img src="<?php echo img;?>btn/botonaceptar.jpg" width="85" height="22" class="imgBoton" ></a>
                <a href="#" id="cancelar"><img src="<?php echo img;?>btn/botoncancelar.jpg" width="85" height="22" class="imgBoton" ></a>
            </div>   
        </div>
    </div>
</body>
</html>