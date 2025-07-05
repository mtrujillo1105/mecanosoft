<!DOCTYPE html>
<html>
<head>
<link href="<?php echo css;?>calendario/calendar-win2k-2.css" type="text/css" rel="stylesheet">
<link href="<?php echo css;?>estilos.css" type="text/css" rel="stylesheet">
<!-- Calendario -->
<script type="text/javascript" src="<?php echo js;?>constants.js"></script> 
<script type="text/javascript" src="<?php echo js;?>calendario/calendar.js"></script>
<script type="text/javascript" src="<?php echo js;?>calendario/calendar-es.js"></script>
<script type="text/javascript" src="<?php echo js;?>calendario/calendar-setup.js"></script>
<script type="text/javascript" src="<?php echo js;?>jquery.js"></script>
<script type="text/javascript" src="<?php echo js;?>personal/asistencia.js"></script>
<!-- Calendario -->	
<link rel="stylesheet" href="<?php echo css;?>estilos.css" type="text/css">
<link rel="stylesheet" href="<?php echo css;?>nav.css" type="text/css">
<link rel="stylesheet" href="<?php echo css;?>theme.css" type="text/css">
<link rel="stylesheet" href="<?php echo css;?>calendario/calendar-win2k-2.css" type="text/css" media="all" title="win2k-cold-1"/>	
</head>
<body>
<div id="container">
	<?php require_once vista."menu.php";?>
	<div class="name_user">Hola: Martin Trujillo</div>
	<div class="header">ASISTENCIA DE EMPLEADOS</div>	
	<div class="case_top2">
		<form method="post" action="index.php?accion=rpt_asistencia">
		<table width="98%" cellspacing="3" cellpadding="3" border="0" style="margin-top:5px;">
			<tbody>
				<tr>
					<td align="right" width="18%">Tipo de Trabajador:</td>
					<td align="left" width="32%">
						<select class="comboGrande" name="codcliente" id="codcliente">
							<option value="000000" <?php if($codcliente==$ind) echo "selected='selected'";?>>::Seleccione Todos::</option>
						</select>					
					</td>
					<td align="right" width="18%">Hasta:</td>
					<td align="left">
						<div style="text-align:left;">
							<span style="display:none;">F.Inicio</span>
							<span style="display:none;width:500px;border:0px solid #000;">
								<input  name="fInicio" id="fInicio" title="Fecha Inicio" value="31/07/2011" type="text" class="cajaPequena" readonly="readonly">									
								<img src="../../img/calendario.png" name="Calendario1" id="Calendario1" width="16" height="16" border="0" id="Image1" onMouseOver="this.style.cursor='pointer'" title="Calendario">
								<script type="text/javascript">
										Calendar.setup({
												inputField     :    "fInicio",      // id del campo de texto
												ifFormat       :    "%d/%m/%Y",       // formato de la fecha, cuando se escriba en el campo de texto
												button         :    "Calendario1"   // el id del bot�n que lanzar� el calendario
										});
								</script>		
							</span>
							<span></span>
							<span>
								<input  name="fFin" id="fFin" title="Fecha Inicio" value="<?php echo $fFin;?>" type="text" class="cajaPequena" readonly="readonly">									
								<img src="../../img/calendario.png" name="Calendario2" id="Calendario2" width="16" height="16" border="0" id="Image1" onMouseOver="this.style.cursor='pointer'" title="Calendario">
								<script type="text/javascript">
										Calendar.setup({
												inputField     :    "fFin",      // id del campo de texto
												ifFormat       :    "%d/%m/%Y",       // formato de la fecha, cuando se escriba en el campo de texto
												button         :    "Calendario2"   // el id del bot�n que lanzar� el calendario
										});
								</script>		
							</span>	
						</div>
					</td>                          
				</tr>
				<tr>
					<td align="right">Nombres</td>
					<td align="left">
						<select class="comboGrande" name="codcliente" id="codcliente">
							<option value="000000" <?php if($codcliente==$ind) echo "selected='selected'";?>>::Seleccione Todos::</option>
						</select>	
					</td>
					<td align="right" colspan="2">
                                            <input type="text" name="tipo" id="tipo">
                                        </td>
				</tr>                                      
			</tbody>
		</table>
		</form>
	</div>
	<div class="case_botones">
            <ul class="lista_botones"><li id="grafica" class="asistencia">Ver Grafica</li></ul>
            <ul class="lista_botones"><li id="excel" class="asistencia">Ver Excel</li></ul>
            <ul class="lista_botones"><li id="pdf" class="asistencia">Ver Pdf</li></ul>
            <ul class="lista_botones"><li id="html" class="asistencia">Ver Html</li></ul>  
	</div> 	
	<div style="text-align:left;float:left;width:80%;font-size:13px;margin-top:10px;"><h3>REPORTE ASISTENCIA</h3></div>
	<div style="float:left;width:20%;font-size:13px;margin-top:10px;"><h3>FECHA: <?php echo $tc;?></h3></div>
	<div style="clear:both;padding-top:5px;">
		<table border='1' width='100%'>
			<thead>
				<tr align="center">
					<td width="8px;">No</td>
					<td>DNI</td>
					<td>NOMBRES</td>
					<td>FECHA</td>
					<td width='90px;'>H.INGRESO</td>
					<td width='90px;'>H.SALIDA</td>
				</tr>
			</thead>
			<tbody><?php echo $fila;?></tbody>
		</table>
	</div>
</div>	
</body>
</html>