<html>
<head>	
    <script type="text/javascript" src="<?php echo js;?>constants.js"></script> 
 <script type="text/javascript" src="<?php echo base_url();?>js/jquery.js"></script>
		<script type="text/javascript" src="<?php echo base_url();?>js/jquery.metadata.js"></script>
		<script type="text/javascript" src="<?php echo base_url();?>js/jquery.validate.js"></script>
		<script type="text/javascript" src="<?php echo base_url();?>js/maestros/proyecto.js"></script>
		<script type="text/javascript" src="<?php echo base_url();?>js/funciones.js"></script>		

	</head>
	<body>
<!-- Inicio -->
<div id="VentanaTransparente" style="display:none;">
  <div class="overlay_absolute"></div>
  <div id="cargador" style="z-index:2000">
    <table width="100%" height="100%" border="0" class="fuente8">
		<tr valign="middle">
		     <td> Por Favor Espere    </td>
		     <td><img src="<?php echo base_url();?>images/cargando.gif"  border="0" title="CARGANDO" /><a href="#" id="hider2"></a>	</td>
		</tr>
    </table>
  </div>
</div>
	<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="tituloForm" class="header"><?php echo $titulo;?></div>
				<div id="frmBusqueda">
				<form id="frmProyecto" name="frmProyecto" method="post" action="">
					<div id="container" class="container">
						<ol>
						<h4>Primero debe completar los siguientes campos antes de enviar.</h4>						
							<div id="containerProyecto">								
							</div>
						</ol>
					</div>					
                    
                    <div id="nuevoRegistro" style="display:none;float:right;width:150px;height:20px;border:0px solid #000;margin-top:7px;"><a href="#">Nuevo <image src="<?php echo base_url();?>images/add.png" name="agregarFila" id="agregarFila" border="0" alt="Agregar"></a></div><br><br>				
					<div id="datosGenerales">                        
                      
                       <div id="datosProyecto" >
                           <table class="fuente8" width="98%" cellspacing="0" cellpadding="6" border="0">
                             <tr>
                                 <td>Nombre de Proyecto</td>
                                 <td><input name="nombres" type="text" class="cajaGrande" id="nombres" maxlength="150" value="<?php echo $nombres;?>">
                                 </td>
                             </tr>
                             <tr>                                   
                             </tr>
                             <tr>
                                <td>Descripcion</td>
                                <td>                                 
                                <textarea name="descripcion" id="descripcion" cols="45" rows="5"><?php echo $descripcion;?></textarea>

                                </td>
                             </tr>
                                <tr>

                                </tr>
                                 <tr>
                                   <td>Encargado</td>
                                   <td>
                                     <?php echo $encargado?>
                                   </td>
                                </tr>
                                   <tr>
                            <td align='left' width="15%">Fecha inicial</td>
                            <td align='left' width="15%">
                                <?php echo $fechai?>
                                <img src="<?php echo base_url();?>images/calendario.png" name="Calendario1" id="Calendario1" width="16" height="16" border="0" onMouseOver="this.style.cursor='pointer'" title="Calendario"/>
                                <script type="text/javascript">
                                    Calendar.setup({
                                        inputField     :    "fechai",      // id del campo de texto
                                        ifFormat       :    "%Y-%m-%d",       // formato de la fecha, cuando se escriba en el campo de texto
                                        button         :    "Calendario1"   // el id del botón que lanzará el calendario
                                    });
                                </script>
                            </td>
                            <td align='left' width="10%">Fecha final</td>
                            <td align='left' width="60%">
                                <?php echo $fechaf?>
                                <img src="<?php echo base_url();?>images/calendario.png" name="Calendario2" id="Calendario2" width="16" height="16" border="0" onMouseOver="this.style.cursor='pointer'" title="Calendario2"/>
                                <script type="text/javascript">
                                    Calendar.setup({
                                        inputField     :    "fechaf",      // id del campo de texto
                                        ifFormat       :    "%Y-%m-%d",       // formato de la fecha, cuando se escriba en el campo de texto
                                        button         :    "Calendario2"   // el id del botón que lanzará el calendario
                                    });
                                </script>
                            </td>
                        </tr>
                       <tr>
                           
                      </tr>
                         </table >
                        </div>
			  <div id="divDireccion">
                       
                          </div>
			  </div>
            
                  <div id="datosProyecto" >
		 		  </div>
				<div style="margin-top:20px; text-align: center">
                                        <a href="#" id="imgGuardarProyecto"><img src="<?php echo base_url();?>images/botonaceptar.jpg" width="85" height="22" class="imgBoton" onMouseOver="style.cursor=cursor"></a>
					<a href="#" id="imgLimpiarProyecto"><img src="<?php echo base_url();?>images/botonlimpiar.jpg" width="69" height="22" class="imgBoton" onMouseOver="style.cursor=cursor"></a>
					<a href="#" id="imgCancelarProyecto"><img src="<?php echo base_url();?>images/botoncancelar.jpg" width="85" height="22" class="imgBoton" onMouseOver="style.cursor=cursor"></a>
					<input type="hidden" name="base_url" id="base_url" value="<?php echo base_url();?>">
                                        <input id="modo" name="modo" type="hidden" value="<?php echo $modo;?>">                                       
                                       <input type="hidden" name="proyecto" id="proyecto" value="<?php echo $datos->id; ?>" />
                                </div>
			  </form>
		  </div>
		  </div>
		</div></div>
	</body>
</html>