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
    <script type="text/javascript" src="<?php echo js;?>ventas/guiarem.js"></script>	
    <script type="text/javascript">
    function seleccionar_cliente(codigo,ruc,razon_social){
        $("#cliente").val(codigo);
        $("#ruc").val(ruc);
        $("#nombre_cliente").val(razon_social);
        var sel = document.getElementById('dir_entrega');
        var opt = sel.getElementsByTagName("option");
        for(j=0;j<opt.length;j++){
            sel.options[j]=null;
        }    
        //listar_establecimientos(codigo);
    }
    function guarda_direntrega(direccion){
        $("#dir_entrega").val(direccion);
    }
    function escribe_nombre_unidad_medida(){
        index     = document.getElementById("unidad_medida").selectedIndex;
        nombre = document.getElementById("unidad_medida").options[index].text;
        $("#nombre_unidad_medida").val(nombre);
    }
    function seleccionar_producto(codigo,interno,familia,stock,costo,flagGenInd){
        $("#producto").val(codigo);
        $("#codproducto").val(interno);
        $("#nombre_familia").val(familia);
        $("#stock").val(stock);
        $("#costo").val(costo);
        $("#cantidad").select();
        $("#flagGenInd").val(flagGenInd);
        var sel = document.getElementById('unidad_medida');
        var opt = sel.getElementsByTagName("option");
        for(i=1;i<opt.length;i++){
            sel.options[i]=null;
        }
        listar_unidad_medida_producto(codigo);
    }
    </script>    
</head>
<body>	
<div class="container">
    <?php echo validation_errors("<div class='error'>",'</div>');?>
    <div class="header"><?php echo $titulo;?></div>
    <div class="containertable">
        <?php echo $form_open;?>
        <div style="width:100%;background-color:#f5f5f5;">
            <table class="fuente8" width="98%" cellspacing="0" cellpadding="5" border="0">
                <tr>
                    <td width="8%" >N&uacute;mero</td>
                    <td width="29%"><input type="text" maxlength="10" readonly="readonly" class="cajaPequena2" id="numero" value="<?php echo $lista->numero;?>" name="numero"></td>
                    <td width="10%">Almacen</td>
                    <td width="23%"><?php echo $selalmacen;?></td>
                    <td width="10%">Fecha</td>
                    <td width="23%">
                        <input type="text" maxlength="10" readonly="readonly" class="cajaPequena" id="fecha" value="<?php echo $lista->fecha;?>" name="fecha">
                        <a href="#" style="display:none;"><img height="16" border="0" width="16" id="Image1" name="Image1" src="<?php echo base_url();?>images/calendario.png"></a>
                    </td>
                </tr>
                <tr>
                    <td>Personal</td>
                    <td><input type="text" maxlength="30" readonly="readonly" class="cajaMedia" id="nombre_usuario" value="<?php echo $lista->nomusuario;?>" name="nombre_usuario"></td>
                    <td>Cliente </td>
                    <td>
                        <input type="text" maxlength="50" readonly="readonly" class="cajaMedia" id="nombre_cliente" value="<?php echo $lista->razon_social;?>" name="nombre_cliente">
                        &nbsp;<img width="16" height="16" border="0" title="Buscar" src="<?php echo img;?>ver.png" id="verCliente">
                    </td>
                    <td>Dir. Entrega</td>
                    <td align='left'>
                        <input type="text" readonly="readonly" maxlength="20" class="cajaMedia" id="dir_entrega" value="<?php echo $lista->direntrega;?>" name="dir_entrega">
                        &nbsp;<img width="16" height="16" border="0" title="Buscar" src="<?php echo img;?>ver.png" id="verCliente">
                    </td>
                </tr>
            </table>
        </div>
        <div>
            <table width="98%" cellspacing=0 cellpadding=3 border=0>
                <tr>
                    <td width="9%">Art&iacute;culo</td>
                    <td width="45%">
                        <input name="producto" type="hidden" class="cajaPequena2" id="producto" size="10" maxlength="11">
                        <input name="codproducto" type="text" class="cajaPequena2" id="codproducto" size="10" maxlength="11" onBlur="obtener_producto();" onKeyPress="return numbersonly(this,event,'.');">&nbsp;
                        <input NAME="nombre_producto" type="text" class="cajaGrande" id="nombre_producto" size="15" maxlength="15" readonly="readonly">
                        <img width="16" height="16" border="0" title="Buscar" src="<?php echo img;?>ver.png" id="verCliente">
                        <input name="stock" type="hidden" id="stock">
                        <input name="costo" type="hidden" id="costo">
                        <input name="simbolo" type="hidden" id="simbolo">
                        <input name="nombre_familia" type="hidden" id="nombre_familia">
                        <input name="flagGenInd" type="hidden" id="flagGenInd">
                    </td>
                    <td width="6%">Cantidad</td>
                    <td width="28%">
                        <input NAME="cantidad" type="text" class="cajaPequena2" id="cantidad" value="0" size="5" maxlength="10" onKeyPress="return numbersonly(this,event,'.');">
                        <select name="unidad_medida" id="unidad_medida" class="comboMedio" onChange="escribe_nombre_unidad_medida();"><option value="0">::Seleccione::</option></select>
                    </td>
                    <td width="2%"><input type="hidden" name="nombre_unidad_medida" id="nombre_unidad_medida" class="cajaMedia"></td>
                    <td width="15%"><div align="right"><a href="#" onClick="agregar_producto_guiarem();"><img src="<?php echo img;?>botonagregar.jpg" class="imgBoton" align="absbottom"></a></div></td>
                </tr>
            </table>
        </div>
        <div>
            <table class="fuente8" width="100%" cellspacing="0" cellpadding="3" border="1" id="Table1">
                <tr class="cabeceraTabla">
                    <td width="3%"><div align="center">&nbsp;</div></td>
                    <td width="5%"><div align="center">ITEM</div></td>
                    <td width="10%"><div align="center">C&Oacute;DIGO</div></td>
                    <td width="66%"><div align="center">DESCRIPCI&Oacute;N</div></td>
                    <td width="8%"><div align="center">CANTIDAD</div></td>
                    <td width="8%"><div align="center">UNIDAD</div></td>
                </tr>
            </table>
        </div>
        <div>
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td valign="top">
                        <table id="tblDetalleOcompra" class="fuente8" width="100%" border="0">
                        <?php
                        if(count($lista->detalle)>0){
                            foreach($lista->detalle as $indice=>$valor){
                            $detguiarem      = $valor->GUIAREMDETP_Codigo;
                            $prodproducto    = $valor->PROD_Codigo;
                            $unidad_medida   = $valor->UNDMED_Codigo;
                            $codigo_interno  = $valor->PROD_CodigoInterno;
                            $prodcantidad    = $valor->GUIAREMDETC_Cantidad;
                            $nombre_producto = $valor->GUIAREMDETC_Descripcion;
                            $nombre_unidad   =  $valor->UNDMED_Simbolo;
                            $costo           = $valor->GUIAREMDETC_Costo;
                            $venta           =  $valor->GUIAREMDETC_Venta;
                            $GenInd          = $valor->GUIAREMDETC_GenInd;
                            if(($indice+1)%2==0){$clase="itemParTabla";}else{$clase="itemImparTabla";}
                        ?>
                          <tr class="<?php echo $clase;?>">
                            <td width="3%"><div align="center"><font color="red"><strong><a href="#" onClick="eliminar_producto_ocompra(<?php echo $indice;?>);"><span style="border:1px solid red;background: #ffffff;">&nbsp;X&nbsp;</span></a></strong></font></div></td>
                            <td width="5%"><div align="center"><?php echo $indice+1;?></div></td>
                            <td width="10%"><div align="left">
                                <input type="text" onkeypress="return numbersonly(this,event,'.');" value="<?php echo $codigo_interno;?>" id="prodcantidad[0]" name="prodcantidad[0]" class="cajaPequena2">
                                <input type="hidden" class="cajaMinima" name="prodcodigo[<?php echo $indice;?>]" id="prodcodigo[<?php echo $indice;?>]" value="<?php echo $prodproducto;?>">
                                <input type="hidden" class="cajaMinima" name="produnidad[<?php echo $indice;?>]" id="produnidad[<?php echo $indice;?>]" value="<?php echo $unidad_medida;?>">
                                <input type="hidden" class="cajaMinima" name="flagGenIndDet[<?php echo $indice;?>]" id="flagGenInd[<?php echo $indice;?>]" value="<?php echo $GenInd;?>">
                            </div></td>
                            <td width="66%"><div align="left">
                                <input type="text" class="cajaSuperGrande" name="proddescri[<?php echo $indice;?>]" id="proddescri[<?php echo $indice;?>]" value="<?php echo $nombre_producto;?>">   
                            </div></td>
                            <td width="8%">
                                <div align="center">
                                    <?php if($GenInd=="I"):?>
                                    <a href="#" onclick="ventana_producto_serie2(<?php echo $indice;?>)"><img src="<?php echo base_url();?>images/flag-green_icon.png" width="20" height="20" border="0"/></a>
                                    <?php endif;?>
                                    <input type="text" class="cajaPequena2" name="prodcantidad[<?php echo $indice;?>]" id="prodcantidad[<?php echo $indice;?>]" value="<?php echo $prodcantidad;?>" onKeyPress="return numbersonly(this,event,'.');">
                                </div>
                            </td>
                            <td width="8%">
                                <div align="center">
                                    <?php echo $nombre_unidad;?>
                                    <input type="hidden" class="cajaMinima" name="detaccion[<?php echo $indice;?>]" id="detaccion[<?php echo $indice;?>]" value="m">
                                    <input type="hidden" class="cajaMinima" name="detguiarem[<?php echo $indice;?>]" id="detocom[<?php echo $indice;?>]" value="<?php echo $detguiarem;?>">
                                    <input type="hidden" class="cajaPequena2" name="prodcosto[<?php echo $indice;?>]" id="prodcosto[<?php echo $indice;?>]" readonly="readonly" value="<?php echo $costo;?>">
                                    <input type="hidden" class="cajaPequena2" name="prodventa[<?php echo $indice;?>]" id="prodventa[<?php echo $indice;?>]" value="<?php echo $venta;?>" readonly="readonly">
                                </div>
                            </td>
                          </tr>
                            <?php
                        }
                        }
                        ?>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
        <div>
            <table class="fuente8" width="100%" border="0" cellpadding="3" cellspacing="5">
                <tr>
                    <td>Doc Ref:&nbsp;<?php echo $seldocumento;?></td>
                    <td><input type="text" maxlength="15" class="cajaPequena" id="numero_ref" value="<?php echo $lista->numeroref;?>" name="numero_ref"></td>
                    <td>Motivo movimiento</td>
                    <td><?php echo $seltipomov;?></td>
                    <td>Fecha Traslado</td>
                    <td>
                        <input type="text" readonly="readonly" maxlength="10" class="cajaPequena" id="fecha_traslado" value="<?php echo $lista->fechatraslado;?>" name="fecha_traslado">
                        <img src="<?php echo img;?>calendario.png" name="Calendario1" id="Calendario1" width="16" height="16" border="0" id="Image1" onMouseOver="this.style.cursor='pointer'" title="Calendario">
                        <script type="text/javascript">
                            Calendar.setup({
                                inputField     :    "fecha_traslado",      // id del campo de texto
                                ifFormat       :    "%d/%m/%Y",       // formato de la fecha, cuando se escriba en el campo de texto
                                button         :    "Calendario1"   // el id del botón que lanzará el calendario
                            });
                        </script>
                    </td>
                </tr>
                <tr>
                    <td>Nombre Transportista:</td>
                    <td><input type="text" maxlength="20" class="cajaPequena" id="nombre_transportista" value="<?php echo $lista->nombretransportista;?>" name="nombre_transportista"></td>
                    <td>RUC Transportista</td>
                    <td><input type="text" onkeypress="return numbersonly(this,event);" maxlength="11" class="cajaPequena" id="ruc_transportista" value="<?php echo $lista->ructransportista;?>" name="ruc_transportista"></td>
                    <td>Vehiculo marca y placa</td>
                    <td><input type="text" maxlength="20" class="cajaPequena" id="marca_placa" value="<?php echo $lista->marcaplaca;?>" name="marca_placa"></td>
                </tr>
                <tr>
                    <td>Cert.Inscripcion</td>
                    <td><input type="text" maxlength="10" class="cajaPequena" id="certificado" value="<?php echo $lista->certificado;?>" name="certificado"></td>
                    <td>Licencia de conducir</td>
                    <td><input type="text" maxlength="10" class="cajaPequena" id="licencia" value="<?php echo $lista->licencia;?>" name="licencia"></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td valign="top">Observaci&oacute;n</td>
                    <td colspan="5" align="left"><?php echo $lista->observacion;?></td>
                </tr>
            </table>
            <?php echo $oculto;?>
        </div>
        <?php echo $form_close;?>
    </div>
    <div>
        <a href="#" id="grabarGuiarem"><img src="<?php echo img;?>botonaceptar.jpg" width="85" height="22" class="imgBoton" ></a>
        <a href="#" id="limpiarGuiarem"><img src="<?php echo img;?>botonlimpiar.jpg" width="69" height="22" class="imgBoton" ></a>
        <a href="#" id="cancelarGuiarem"><img src="<?php echo img;?>botoncancelar.jpg" width="85" height="22" class="imgBoton" ></a>
    </div>
</div>
</body>