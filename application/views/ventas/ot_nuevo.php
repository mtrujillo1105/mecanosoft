<!DOCTYPE html>
<html>
<head>
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
    <script type="text/javascript" src="<?php echo js;?>ventas/ot.js"></script>
</head>
<body>
    <div class="container">
        <div class="header">GENERACIÓN DE ORDENES DE TRABAJO</div>
        <div class="case_back">
            <div class="case_sup">
                <form class="form_ot">
                    <table width="100%" border="0">
                        <tr>
                            <td>
                                <div class="box_item_form">
                                    <span class="lbl1">A&ntilde;o:</span>
                                  <span class="tit_group_media">
                                    <select class="comboMedio">
                                        <option>::Seleccione::</option>
                                        <option>ORDEN DE TRABAJO - 2009</option>
                                        <option>ORDEN DE TRABAJO - 2010</option>
                                        <option>ORDEN DE TRABAJO - 2011</option>
                                    </select>
                                  </span>
                                  <span class="lbl1">Nro OT: </span>
                                   <span class="tit_group nwidth">
                                        <input name="txtNombre" id="txtNombre" title="Nombres" value="" type="text" class="cajaPequena">
                                   </span>
                                  <span class="lbl1">Nro Ppto: </span>
                                   <span class="tit_group nwidth">
                                        <input  name="txtNombre" id="txtNombre" title="Nombres" value="" type="text" class="cajaPequena">
                                   </span>                                          
                                  <span class="lbl1">Nro An&aacute;lisis: </span>
                                   <span class="tit_group nwidth">
                                        <input name="txtNombre" id="txtNombre" title="Nombres" value="" type="text" class="cajaPequena">
                                   </span>	
                                </div>
                            </td>
                        </tr>						
                        <tr>
                            <td>
                                <div class="box_item_form">
                                    <span class="lbl1">Cliente :</span>
                                    <span class="tit_group"><input  name="txtNombre" id="txtNombre" title="Nombres" value="" type="text" class="cajaPequena"></span>	
                                    <span class="tit_group_grande"><input  name="txtNombre" id="txtNombre" title="Nombres" value="" type="text" class="cajaGrande"></span>	
                                    <span class="lbl1">Persona de contacto :</span>
                                    <span class="tit_group"><input  name="txtNombre" id="txtNombre" title="Nombres" value="" type="text" class="cajaMedia"></span>	                                            
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="box_item_form">
                                    <span class="lbl1">Site :</span>
                                    <span class="tit_group_media">
                                        <input  name="txtNombre" id="txtNombre" title="Nombres" value="" type="text" class="cajaMedia">
                                    </span>	
                                    <span class="lbl1">Lugar Entrega :</span>
                                    <span class="tit_group_media">
                                        <input  name="txtNombre" id="txtNombre" title="Nombres" value="" type="text" class="cajaGrande">
                                    </span>	
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
                                        <span class="tit_group_media">
                                            <select class="comboMedio">
                                                <option>::Seleccione::</option>
                                            </select>
                                        </span>	
                                        <span class="lbl1">Prov :</span>
                                        <span class="tit_group_media">
                                            <select class="comboMedio">
                                                <option>::Seleccione::</option>
                                            </select>
                                        </span>
                                        <span class="lbl1">Dist :</span>
                                        <span class="tit_group_media">
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
                                    <span class="lbl1">F.Apertura :</span>
                                    <span class="tit_group nwidth">
                                        <input  name="fApertura" id="fApertura" title="Nombres" value="" type="text" class="cajaPequena" readonly="readonly">									
                                        <img src="../../img/calendario.png" name="Calendario1" id="Calendario1" width="16" height="16" border="0" id="Image1" onMouseOver="this.style.cursor='pointer'" title="Calendario">
                                        <script type="text/javascript">
                                                Calendar.setup({
                                                        inputField     :    "fApertura",      // id del campo de texto
                                                        ifFormat       :    "%Y-%m-%d",       // formato de la fecha, cuando se escriba en el campo de texto
                                                        button         :    "Calendario1"   // el id del botón que lanzará el calendario
                                                });
                                        </script>										
                                    </span>	
                                    <span class="lbl1">F.Inicio. :</span>
                                    <span class="tit_group nwidth">
                                        <input  name="fFabricacion" id="fFabricacion" title="Nombres" value="" type="text" class="cajaPequena" readonly="readonly">
                                        <img src="../../img/calendario.png" name="Calendario2" id="Calendario2" width="16" height="16" border="0" id="Image1" onMouseOver="this.style.cursor='pointer'" title="Calendario2">
                                        <script type="text/javascript">
                                                Calendar.setup({
                                                        inputField     :    "fFabricacion",      // id del campo de texto
                                                        ifFormat       :    "%Y-%m-%d",       // formato de la fecha, cuando se escriba en el campo de texto
                                                        button         :    "Calendario2"   // el id del botón que lanzará el calendario
                                                });
                                        </script>										
                                    </span>	
                                    <span class="lbl1">Duracion :</span>
                                    <span class="tit_group">
                                        <input  name="txtDuracion" id="txtDuracion" title="Nombres" value="" type="text" class="cajaPequena" readonly="readonly">										
                                    </span>					
                                    <span class="lbl1">F.Prog.Entrega :</span>
                                    <span class="tit_group nwidth">
                                        <input  name="fProgEntrega" id="fProgEntrega" title="Nombres" value="" type="text" class="cajaPequena" readonly="readonly">
                                        <img src="../../img/calendario.png" name="Calendario3" id="Calendario3" width="16" height="16" border="0" id="Image1" onMouseOver="this.style.cursor='pointer'" title="Calendario">
                                        <script type="text/javascript">
                                                Calendar.setup({
                                                        inputField     :    "fProgEntrega",      // id del campo de texto
                                                        ifFormat       :    "%Y-%m-%d",       // formato de la fecha, cuando se escriba en el campo de texto
                                                        button         :    "Calendario3"   // el id del botón que lanzará el calendario
                                                });
                                        </script>											
                                    </span>	
                                    <span class="lbl1">F.T&eacute;rmino :</span>
                                    <span class="tit_group nwidth">
                                        <input  name="fEntrega" id="fEntrega" title="Nombres" value="" type="text" class="cajaPequena" readonly="readonly">
                                        <img src="../../img/calendario.png" name="Calendario4" id="Calendario4" width="16" height="16" border="0" id="Image1" onMouseOver="this.style.cursor='pointer'" title="Calendario">
                                        <script type="text/javascript">
                                            Calendar.setup({
                                                    inputField     :    "fEntrega",      // id del campo de texto
                                                    ifFormat       :    "%Y-%m-%d",       // formato de la fecha, cuando se escriba en el campo de texto
                                                    button         :    "Calendario4"   // el id del botón que lanzará el calendario
                                            });
                                        </script>											
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
                                    <span class="lbl1" style="width:77px;text-align: left;">Descripcion de trabajos:&nbsp;</span>
                                    <span class="tit_textarea">
                                        <textarea id="descripcion" name="descripcion" class="textareaMedia"></textarea>
                                    </span>	
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="box_item_form">
                                    <span class="lbl1">Observacion :&nbsp;</span>
                                    <span class="tit_textarea">
                                        <textarea id="descripcion" name="descripcion" class="textareaMedia"></textarea>
                                    </span>	
                                </div>
                            </td>
                        </tr>                                            
                    </table>
                </form>
            </div>   
            <div class="case_middle">
                <ul class="tabs">
                    <li><a href="#tab1">Productos & Servicios</a></li>
                    <li><a href="#tab2">Condiciones Pago</a></li>
                </ul>
                <div class="tab_container">
                    <div id="tab1" class="tab_content"><?php require_once "ot_productos.php";?></div>
                    <div id="tab2" class="tab_content"><?php require_once "ot_condiciones_pago.php";?></div>
                </div>                
           </div>  
        </div>
        <div style="margin:5px;">
            <a href="#" id="grabar"><img src="<?php echo img;?>btn/botonaceptar.jpg" width="85" height="22" class="imgBoton" ></a>
            <a href="#" id="limpiar"><img src="<?php echo img;?>btn/botonlimpiar.jpg" width="69" height="22" class="imgBoton" ></a>
            <a href="#" id="cancelar"><img src="<?php echo img;?>btn/botoncancelar.jpg" width="85" height="22" class="imgBoton" ></a>
        </div>	
        <div class="case_botones">
            <ul class="lista_botones"><li id="excel">Excel</li></ul>
            <ul class="lista_botones"><li id="imprimir">Imprimir</li></ul>
        </div>                     
    </div>               
</body>
<script type="text/javascript">
    $('#tblPresupuesto tr')
            .mouseover(function(){
               $(this).css("background: #bbbbbb;");
            });  
</script> 
</html>
