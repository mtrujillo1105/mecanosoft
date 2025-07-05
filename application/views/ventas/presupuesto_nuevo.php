<!DOCTYPE html>
<html>
<head>
    <title><?php echo titulo;?></title>        
    <!-- Calendario -->
    <link rel="stylesheet" href="<?php echo css;?>estilos.css" type="text/css">
    <link rel="stylesheet" href="<?php echo css;?>nav.css" type="text/css">
    <link rel="stylesheet" href="<?php echo css;?>theme.css" type="text/css">
    <link rel="stylesheet" href="<?php echo css;?>calendario/calendar-win2k-2.css" type="text/css" media="all" title="win2k-cold-1"/>	
    <!-- Calendario -->	
    <script type="text/javascript" src="<?php echo js;?>constants.js"></script>    
    <script type="text/javascript" src="<?php echo js;?>calendario/calendar.js"></script>
    <script type="text/javascript" src="<?php echo js;?>calendario/calendar-es.js"></script>
    <script type="text/javascript" src="<?php echo js;?>calendario/calendar-setup.js"></script>
    <script type="text/javascript" src="<?php echo js;?>jquery.js"></script>
    <script type="text/javascript" src="<?php echo js;?>ventas/presupuesto.js"></script>
</head>
<body>
    <div class="container">
        <div class="header">GENERACI&Oacute;N DE PRESUPUESTOS</div>
       <div class="div_fondo">
            <ul class="tabs">
                <li id2="01"><a href="#tab1">Datos</a></li>
                <li id2="02"><a href="#tab2">Estructura</a></li>
            </ul>
            <div id="tab1" class="tab_content" style="width:100%;border: 1px solid #ccc;float:left;height:617px; overflow:auto;">
                <form id="frmPresupuesto">
                    <table width="95%" cellspacing="0" cellpadding="0" border="0" class="fuente8" align="center">
                        <tr>
                            <td>
                               <span class="lbl1">Nro Ppto :</span>
                               <span class="tit_group nwidth"><input name="presupuesto" id="txtNombre" title="presupuesto" value="<?php echo $presupuesto;?>" type="text" class="cajaPequena"></span>
                               <span class="lbl1">Nro An&aacute;lisis :</span>
                               <span class="tit_group"><input name="analisis" id="analisis" title="Nombres" value="<?php echo $analisis;?>" type="text" class="cajaPequena"></span>
                               <span class="lbl1">Proyecto :</span>   
                               <span class="lbl1"><?php echo $selproyecto;?></span>	
                               <span class="lbl1">Fecha Presup :</span> 
                               <span class="tit_group nwidth">
                                    <input  name="fecha" id="fecha" title="Nombres" value="<?php echo $fecha;?>" type="text" class="cajaPequena" readonly="readonly">
                                    <img src="<?php echo img;?>calendario.png" name="Calendario6" id="Calendario6" width="16" height="16" border="0" id="Image1" onMouseOver="this.style.cursor='pointer'" title="Calendario">
                                    <script type="text/javascript">
                                            Calendar.setup({
                                                    inputField  :    "fPresupuesto",      // id del campo de texto
                                                    ifFormat    :    "%Y-%m-%d",       // formato de la fecha, cuando se escriba en el campo de texto
                                                    button      :    "Calendario6"   // el id del botón que lanzará el calendario
                                            });
                                    </script>											
                               </span>	                                           
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="box_item_form">
                                    <span class="lbl1">Cliente :</span>
                                    <span class="lbl1"><?php echo $selcliente;?></span>	
                                    <span class="lbl1">Persona de contacto :</span>
                                    <span class="tit_group"><input  name="txtNombre" id="txtNombre" title="Nombres" value="" type="text" class="cajaMedia"></span>	                                            
                                </div>
                            </td>
                        </tr>    
                        <tr>
                            <td>
                                <div class="box_item_form">
                                    <span class="lbl1">Site :</span>
                                    <span class="tit_group_media"><input  name="txtNombre" id="txtNombre" title="Nombres" value="<?php echo $site;?>" type="text" class="cajaMedia"></span>	
                                    <span class="lbl1">Lugar Entrega :</span>
                                    <span class="tit_group_media"><input  name="txtNombre" id="txtNombre" title="Nombres" value="" type="text" class="cajaGrande"></span>	
                                </div>
                            </td>
                        </tr>	
                      <tr>
                            <td>
                                <div class="box_item_form">
                                    <span class="lbl1"><input type="radio" name="tipo_cliente" id="tipo_cliente" onclick="$('#internacional').show();$('#local').hide();">Extranjero</span>
                                    <span class="lbl1 nwidth2"><input type="radio" name="tipo_cliente" id="tipo_cliente" checked="checked" onclick="$('#local').show();$('#internacional').hide();">Local</span>
                                    <span id="local">
                                        <span class="lbl1">Dpto :</span>
                                        <span class="lbl1">
                                            <select class="comboMedio">
                                                <option>::Seleccione::</option>
                                            </select>
                                        </span>	
                                        <span class="lbl1">Prov :</span>
                                        <span class="lbl1">
                                            <select class="comboMedio">
                                                <option>::Seleccione::</option>
                                            </select>
                                        </span>
                                        <span class="lbl1">Dist :</span>
                                        <span class="lbl1">
                                            <select class="comboMedio">
                                                <option>::Seleccione::</option>
                                            </select>
                                        </span>
                                    </span>
                                    <span id="internacional" style="display: none;">
                                        <span class="lbl1">Ciudad :</span>
                                        <span class="tit_group_media">
                                            <select class="comboMedio">
                                                <option>::Seleccione::</option>
                                            </select>
                                        </span>	
                                        <span class="lbl1">Est/Prov/Dpto :</span>
                                        <span class="tit_group_media">
                                            <select class="comboMedio">
                                                <option>::Seleccione::</option>
                                            </select>
                                        </span>
                                        <span class="lbl1">Pais :</span>
                                        <span class="tit_group_media">
                                            <select class="comboMedio">
                                                <option>::Seleccione::</option>
                                            </select>
                                        </span>
                                    </span>                                                
                                </div>
                            </td>
                        </tr>							
                        <tr>
                            <td>
                                <div class="box_item_form">
                                    <span class="lbl1">Gestor Proyectos :</span>
                                    <span class="tit_group_media">
                                        <input  name="txtNombre" id="txtNombre" title="Nombres" value="" type="text" class="cajaMedia">
                                    </span>	
                                    <span class="lbl1">Moneda :</span>
                                    <span class="tit_group_media nwidth3">
                                        <select class="comboMedio">
                                            <option>::Seleccione::</option>
                                        </select>
                                    </span>
                                    <span class="lbl1">Monto :</span>
                                    <span class="tit_group_media">
                                        <input  name="txtNombre" id="txtNombre" title="Nombres" value="" type="text" class="cajaMedia">
                                    </span>                                            
                                </div>
                            </td>
                        </tr>	
                        <tr>
                            <td>
                                <div class="box_item_form">
                                    <span class="lbl1">Plazo de entrega&nbsp;(d&iacute;as) :</span>
                                    <span class="tit_group_media nwidth4">
                                        <input  name="txtEntrega" id="txtEntrega" title="Nombres" value="" type="text" class="cajaMinima">
                                        <input style="display:none;" name="txtEntregaDet" id="txtEntregaDet" title="Nombres" value="Segun sus requerimientos" type="text" class="cajaMedia">
                                        <input type="checkbox" name="chkEntrega" id="chkEntrega" onclick="if(this.checked){$('#txtEntregaDet').show();$('#txtEntrega').hide();}else{$('#txtEntregaDet').hide();$('#txtEntrega').show();};"/>Especificar
                                    </span>	
                                    <span class="lbl1">Validez de la oferta&nbsp;(d&iacute;as) :</span>
                                    <span class="tit_group_media nwidth4">
                                        <input  name="txtOferta" id="txtOferta" title="Nombres" value="" type="text" class="cajaMinima">
                                        <input style="display:none;" name="txtOfertaDet" id="txtOfertaDet" title="Nombres" value="Segun sus requerimientos" type="text" class="cajaMedia">
                                        <input type="checkbox" name="chkOferta" id="chkOferta" onclick="if(this.checked){$('#txtOfertaDet').show();$('#txtOferta').hide();}else{$('#txtOfertaDet').hide();$('#txtOferta').show();};"/>Especificar
                                    </span>                                      
                                </div>
                            </td>
                        </tr>                                
                        <tr>
                            <td>
                                <div class="box_item_form">
                                    <span class="lbl1">Descripcion de trabajos:&nbsp;</span>
                                    <span class="tit_textarea">
                                        <textarea id="descripcion" name="descripcion" class="textareaMedia"><?php echo $descripcion;?></textarea>
                                    </span>	
                                </div>
                            </td>
                        </tr>     

                    </table>
                </form>  
                <div><ul class="lista_botones" id="nuevoProducto"><li>Grabar</li></ul></div>
            </div>
            <div id="tab2" class="tab_content" style="width:100%;border: 1px solid #ccc;float:left;height:617px; overflow:auto;">
                <div id="divTipo" class="lbl2" style="text-align:left;">
                    <table width="95%" border="1" align="center" cellpadding="0" cellspacing="0" id="tblPresupuesto">
                        <thead>
                            <tr>
                                <th scope="col" width="55%" bgcolor="#CCCCCC"><div align="center">Descripcion</div></th>
                                <th scope="col" width="15%" bgcolor="#CCCCCC"><div align="center">Presupuestado S/.</div></th>
                            </tr>	
                        </thead>                                                 
                        <tbody>
                            <?php echo $fila_tipo;?>         
                        </tbody>
                    </table>     
                </div>
                <div id="divPartida" class="lbl2" style="text-align:left;">
                    <div class="lbl2" style="text-align:left;">
                        <table width="95%" border="1" align="center" cellpadding="0" cellspacing="0">
                            <thead>
                                <tr bgcolor="#CCCCCC">
                                    <th scope="col" width="52%"><div align="center">Descrpcion</div></th>
                                    <th scope="col" width="12%"><div align="center">Presupuestado S/.</div></th>
                                </tr>	
                            </thead>                                                 
                            <tbody>
                                <tr bgcolor="#FFFFFF" id="1">
                                    <?php echo $fila_partida;?>
                                </tr>	               
                            </tbody>
                        </table>        
                    </div>
                </div>
                <div id="divSubpartida" class="lbl2" style="text-align:left;">
                    <div class="lbl2" style="text-align:left;">
                        <table width="95%" border="1" align="center" cellpadding="0" cellspacing="0">
                            <thead>
                                <tr bgcolor="#CCCCCC">
                                    <th scope="col" width="52%"><div align="center">Descrpcion</div></th>
                                    <th scope="col" width="12%"><div align="center">Presupuestado S/.</div></th>
                                </tr>	
                            </thead>                                                 
                            <tbody>
                                <tr bgcolor="#FFFFFF" id="1">
                                    <td align="center" colspan="2">NO EXISTEN REGISTROS.</td>
                                </tr>	               
                            </tbody>
                        </table>        
                    </div>
                </div>                            
            </div>              
        </div>     
    </div>
</body>
</html>