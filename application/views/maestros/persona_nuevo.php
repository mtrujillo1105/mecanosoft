<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>	
    <title><?php echo titulo;?></title>
    <META HTTP-EQUIV="Refresh" content="300"> 
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="Content-Language" content="es"> 
    <link rel="stylesheet" href="<?php echo css;?>estilos.css" type="text/css">
    <link rel="stylesheet" href="<?php echo css;?>basic.css" type="text/css">   
    <link rel="stylesheet" href="<?php echo css;?>calendario/calendar-win2k-2.css" type="text/css" media="all" title="win2k-cold-1"/>	            
    <script type="text/javascript" src="<?php echo js;?>constants.js"></script> 
    <script type="text/javascript" src="<?php echo js;?>jquery.js"></script>
    <script type="text/javascript" src="<?php echo js;?>jquery.metadata.js"></script>
    <script type="text/javascript" src="<?php echo js;?>jquery.validate.js"></script>
    <script type="text/javascript" src="<?php echo js;?>maestros/persona.js"></script>	
</head>
<body>
<div class="container">
    <div class="header"><?php echo $titulo;?></div>
    <?php echo $form_open;?>
    <div style="float:left;width:100%; text-align: left;">
        <div style="width:100%">
            <table class="fuente8" width="100%" cellspacing="0" cellpadding="6" border="0" bgcolor="#fff">
                <tr>
                    <td>Tipo de Documento&nbsp;(*)</td>
                    <td><?php echo $seltipodoc;?></td>
                  <td>Número de Documento</td>
                  <td><input name="numero" type="text" class="cajaMedia" id="numero" size="15" maxlength="8" value="<?php echo $lista->numerodoc;?>" onkeypress="return numbersonly('numero_documento',event);">
                  </td>
                </tr>
                <tr>
                  <td>Nombres&nbsp;(*)</td>
                  <td>
                      <input id="nombres" type="text" class="cajaMedia" name="nombres" maxlength="45" value="<?php echo $lista->nombres;?>">
                  </td>
                  <td>Lugar de Nacimiento</td>
                  <td>
                      <input type="hidden" name="cboNacimiento" id="cboNacimiento" class="cajaMedia" value=""/>
                      <input type="text" name="cboNacimientovalue" id="cboNacimientovalue" class="cajaMedia" readonly="readonly" value="" ondblclick="abrir_formulario_ubigeo();"/>
                      <a href="#" onclick="abrir_formulario_ubigeo();"><image src="<?php echo img;?>ver.png" border='0'></a>
                  </td>
                </tr>
                <tr>
                    <td>Apellidos Paterno&nbsp;(*)</td>
                    <td><input NAME="paterno" type="text" class="cajaMedia" id="paterno" size="45" maxlength="45" value="<?php echo $lista->paterno;?>"></td>
                  <td>Sexo&nbsp;(*)</td>
                  <td><?php echo $selsexo;?></td>
                </tr>
                <tr>
                    <td>Apellidos Materno</td>
                    <td><input NAME="materno" type="text" class="cajaMedia" id="materno" size="45" maxlength="45" value="<?php echo $lista->materno;?>"></td>
                  <td>Estado Civil</td>
                  <td><?php echo $selestadoc;?></td>

                </tr>
                <tr>
                  <td>Nacionalidad&nbsp;(*)</td>
                  <td><?php echo $selnacion;?></td>
                   <td>RUC</td>   
                   <td><input id="ruc_persona" type="text" class="cajaMedia" name="ruc_persona" size="45" maxlength="11" value="<?php echo $lista->ruc;?>"></td>
                </tr>
                <tr height="10px">
                 <td colspan="4"><hr></td>
                </tr>
                <tr>							  
                  <td>Departamento&nbsp;</td>
                  <td colspan="3">							  	
                      <div id="divUbigeo">
                        <?php echo $seldpto;?>&nbsp;	&nbsp;
                        Provincia&nbsp;&nbsp;	&nbsp;
                        <?php echo $selprov;?>&nbsp;	&nbsp;
                        Distrito&nbsp;&nbsp;	&nbsp;<?php echo $seldist;?>
                        </div>
                  </td>
                </tr>
                <tr>
                  <td width="16%">Direcci&oacute;n fiscal</td>
                  <td colspan="3"><input NAME="direccion" type="text" class="cajaGrande" id="direccion" size="45" maxlength="100" value="<?php echo $lista->direccion;?>"></td>
               </tr>
                <tr height="10px">
                  <td colspan="4"><hr></td>
                </tr>
                <tr>
                    <td width="16%">Tel&eacute;fono </td>
                    <td><input id="telefono" name="telefono" type="text" class="cajaPequena" maxlength="15" value="<?php echo $lista->telefono;?>"></td>
                    <td>M&oacute;vil</td>
                    <td>
                        <input id="movil" name="movil" type="text" class="cajaPequena" maxlength="15" value="<?php echo $lista->movil;?>">
                        Fax<input id="fax" name="fax" type="text" class="cajaPequena" maxlength="15" value="<?php echo $lista->fax;?>">    
                    </td>
                </tr>
                <tr>
                    <td>Correo electr&oacute;nico  </td>
                    <td><input NAME="email" type="text" class="cajaGrande" id="email" size="35" maxlength="50" value="<?php echo $lista->email;?>"></td>
                    <td>Direcci&oacute;n web </td>
                    <td><input NAME="web" type="text" class="cajaGrande" id="web" size="45" maxlength="50" value="<?php echo $lista->web;?>"></td>
                </tr>
         </table>
         </div> 
        <div style="margin-top:20px; text-align: center">
             <a href="#" id="grabar"><img src="<?php echo img;?>botonaceptar.jpg" width="85" height="22" class="imgBoton" onMouseOver="style.cursor=cursor"></a>
             <a href="#" id="imprimir"><img src="<?php echo img;?>botonimprimir.jpg" width="69" height="22" class="imgBoton" onMouseOver="style.cursor=cursor"></a>
             <a href="#" id="cancelar"><img src="<?php echo img;?>botoncancelar.jpg" width="85" height="22" class="imgBoton" onMouseOver="style.cursor=cursor"></a>
             <?php echo $oculto;?>
        </div>
        <?php echo $form_close;?>
    </div>
</div>
</body>
</html>