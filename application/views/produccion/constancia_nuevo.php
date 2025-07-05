<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>	
    <title><?php echo titulo;?></title>
    <META HTTP-EQUIV="Refresh" content="300"> 
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="Content-Language" content="es"> 
    <link rel="stylesheet" href="<?php echo css;?>estilos.css" type="text/css">    
    <link rel="stylesheet" href="<?php echo css;?>calendario/calendar-win2k-2.css" type="text/css" media="all" title="win2k-cold-1"/>	            
    <script type="text/javascript" src="<?php echo js;?>constants.js"></script> 
    <script type="text/javascript" src="<?php echo js;?>calendario/calendar.js"></script>
    <script type="text/javascript" src="<?php echo js;?>calendario/calendar-es.js"></script>
    <script type="text/javascript" src="<?php echo js;?>calendario/calendar-setup.js"></script>    
    <script type="text/javascript" src="<?php echo js;?>jquery.js"></script>
    <script type="text/javascript" src="<?php echo js;?>jquery.simplemodal.js"></script>           
    <script type="text/javascript" src="<?php echo js;?>almacen/producto.js"></script>
</head>
<body>
<div class="container">
    <div class="header"><?php echo $titulo;?></div>
    <?php echo $form_open;?>
    <div class="containertable">
        <div style="width:100%;background-color:#f5f5f5;">
            <table class="fuente8" width="100%" cellspacing="0" cellpadding="6" border="0">
              <tr>
                <td width="10%">N°Constancia</td>
                <td width="40%"><input type="text" readonly="readonly" class="cajaPequena" name="nombre_familia" id="nombre_familia" readonly="readonly" value="415"></td>
                <td width="10%">F.Emision</td>
                <td width="40%">
                    <input type="text" readonly="readonly" class="cajaPequena" name="fecha" id="fecha" readonly="readonly" value="<?php echo $lista->fecha;?>" style="background-color: #E6E6E6">
                    <img src="<?php echo img;?>calendario.png" name="Calendario1" id="Calendario1" width="16" height="16" border="0" id="Image1" onMouseOver="this.style.cursor='pointer'" title="Calendario">
                     <script type="text/javascript">
                            Calendar.setup({
                                    inputField     :    "fecha",      // id del campo de texto
                                    ifFormat       :    "%d/%m/%Y",       // formato de la fecha, cuando se escriba en el campo de texto
                                    button         :    "Calendario1",   // el id del bot?n que lanzar? el calendario
                                    onUpdate       :    function(){
                                        $("#tipoexport").val('');
                                    }
                            });
                    </script>                
                </td>                
              </tr>
              <tr>
                <td>Orden de Trabajo</td>
                <td>
                    <input type="text" class="cajaPequena" name="nombre" id="nombre" onblur="valida_nombre_producto();" value="140135">
                    <a href="#" onclick='ver_familia();'><img height='16' width='16' src='<?php echo img;?>ver.png' title='Registrar Familia' border='0'></a>
                </td>
                <td>Cliente</td>
                <td>
                    <input type="text" class="cajaPequena" name="nombre" id="nombre" onblur="valida_nombre_producto();" value="20300166611" style="background-color: #E6E6E6" readonly="readonly">
                    <input type="text" class="cajaGrande" name="nombre" id="nombre" onblur="valida_nombre_producto();" value="METALES INGENIERIA Y CONSTRUCCION S.A.C." style="background-color: #E6E6E6" readonly="readonly">
                </td>
              </tr>
              <tr>
                <td>Guia Cliente</td>
                <td>
                    <input type="text" class="cajaMedia" name="nombre" id="nombre" onblur="valida_nombre_producto();" value="">
                </td>
                <td>&nbsp;</td>
                <td><img align="absbottom" class="imgBoton" src="http://localhost/ferresat/images/botonagregar.jpg"></td>
              </tr>
            </table>
        </div>  
        <div style="width:100%;background-color:#f5f5f5;height: 250px;">
            <table cellspacing="0" cellpadding="3" width="100%" border="1">
                <tr class="cabeceraTabla">
                    <td width="3%"><div align="center">&nbsp;</div></td>
                    <td width="5%"><div align="center">ITEM</div></td>
                    <td width="50%"><div align="center">DESCRIPCIÓN</div></td>
                    <td width="8%"><div align="center">PIEZAS</div></td>
                    <td width="10%"><div align="center">PESO(KG)</div></td>
                    <td width="10%"><div align="center">TIPO MATERIAL</div></td>
                </tr>
            </table>
           <table cellspacing="0" cellpadding="3" width="100%">
                <tr class="itemImparTabla">
                    <td width="3%" align="center"><font color="red"><strong><a onclick="eliminar_producto_guiasa(this);" href="#"><span style="border:1px solid red;background: #ffffff;">&nbsp;X&nbsp;</span></a></strong></font></td>
                    <td width="5%" align="center">1</td>
                    <td width="50%" align="left"><input type="text" value="BANDEJA, TEES, UNIONES" id="proddescri[0]" name="proddescri[0]" class="cajaGrande"></td>
                    <td width="8%" align="center"><input type="text" value="15" id="prodcodigo[0]" name="prodcodigo[0]" class="cajaMinima"></td>                    
                    <td width="10%" align="center"><input type="text" onkeypress="return numbersonly(this,event,'.');" value="245" id="prodcantidad[0]" name="prodcantidad[0]" class="cajaMinima"></td>
                    <td width="10%" align="center"><?php echo $seltipomat;?></td>
                </tr>   
                <tr class="itemImparTabla">
                    <td width="3%" align="center"><font color="red"><strong><a onclick="eliminar_producto_guiasa(this);" href="#"><span style="border:1px solid red;background: #ffffff;">&nbsp;X&nbsp;</span></a></strong></font></td>
                    <td width="5%" align="center">2</td>
                    <td width="50%" align="left"><input type="text" value="PARRILLAS" id="proddescri[0]" name="proddescri[0]" class="cajaGrande"></td>
                    <td width="8%" align="center"><input type="text" value="8" id="prodcodigo[0]" name="prodcodigo[0]" class="cajaMinima"></td>                    
                    <td width="10%" align="center"><input type="text" onkeypress="return numbersonly(this,event,'.');" value="120" id="prodcantidad[0]" name="prodcantidad[0]" class="cajaMinima"></td>
                    <td width="10%" align="center"><?php echo $seltipomat;?></td>
                </tr>                 
            </table>
        </div> 
        <div style="border-top:solid 1px #dcdcdc;">
            <table cellspacing="0" cellpadding="5" width="100%" border="0">
                <tr>
                    <td width="10%" valign="top">Observacion</td>
                    <td width="90%" align="left"><textarea class="fuente8" id="observacion" rows="3" style="width: 95%;" name="observacion"></textarea></td>
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