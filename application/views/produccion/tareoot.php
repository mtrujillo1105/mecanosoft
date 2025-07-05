<!DOCTYPE html>
<html>
<head>
    <title><?php echo titulo;?></title> 
    <link rel="stylesheet" href="<?php echo css;?>estilos.css" type="text/css">
    <link rel="stylesheet" href="<?php echo css;?>nav.css" type="text/css">
    <link rel="stylesheet" href="<?php echo css;?>theme.css" type="text/css">
    <link rel="stylesheet" href="<?php echo css;?>calendario/calendar-win2k-2.css" type="text/css" media="all" title="win2k-cold-1"/>	    
    <link rel="stylesheet" href="<?php echo css;?>estilos.css" type="text/css">
    <script type="text/javascript" src="<?php echo js;?>constants.js"></script>      
    <script type="text/javascript" src="<?php echo js;?>calendario/calendar.js"></script>
    <script type="text/javascript" src="<?php echo js;?>calendario/calendar-es.js"></script>
    <script type="text/javascript" src="<?php echo js;?>calendario/calendar-setup.js"></script>
    <script type="text/javascript" src="<?php echo js;?>jquery.js"></script>
    <script type="text/javascript" src="<?php echo js;?>produccion/tareo.js"></script>
</head>
<body>
    <div id="container">
        <div class="header">TAREO POR OTs</div>
	<div class="case_top3">
            <form method="post" id="frmBusqueda">
                <table width="98%" cellspacing="3" cellpadding="3" border="0" style="margin-top:5px;">
                    <tbody>
                        <tr>
                    
                            
                            <td align="right" width="10%">FECHA:</td>
                            <td align="left" width="23%">
                              <div style="text-align:left;">
                                    <span style="width:500px;border:0px solid #000;" id="Fecha1">
                                        <input  name="fechacomp" id="fechacomp" value="<?php echo $fechacomp;?>" type="hidden" class="cajaPequena" readonly="readonly">									
  
                                        <input  name="fecha" id="fecha" title="Fecha Inicio" value="<?php echo $fecha;?>" type="text" class="cajaPequena" readonly="readonly">									
                                        <img src="<?php echo img;?>calendario.png" name="Calendario1" id="Calendario1" width="16" height="16" border="0" id="Image1" onMouseOver="this.style.cursor='pointer'" title="Calendario">
                                        <script type="text/javascript">
                                            Calendar.setup({
                                                inputField     :    "fecha",      // id del campo de texto
                                                ifFormat       :    "%d/%m/%Y",       // formato de la fecha, cuando se escriba en el campo de texto
                                                button         :    "Calendario1",   // el id del bot�n que lanzar� el calendario
                                                onUpdate       :    function(){
                                                    $("#tipoexport").val('');
                                                   // $("#frmBusqueda").attr('action','tareoot_cabecera');
                                                    $("#frmBusqueda").attr("target","_top");  
                                                    $("#frmBusqueda").submit();
                                                }
                                            });
                                        </script>		
                                    </span>	
                                </div>  
                            </td>
                            <td align="right" width="10%">&nbsp;</td>
                            <td align="left" width="23%">&nbsp;</td>                            
                            <td align="right" width="10%">&nbsp;</td>   
                            <td align="left" width="23%">&nbsp;</td>
                        </tr>                                                           
                    </tbody>
                </table>
                
            
                <input type="hidden" name="tipoexport" id="tipoexport" value="<?php echo $tipoexport; ?>">
                
            </form>
            
	</div>    
	<div class="case_botones">
            <ul class="lista_botones"><li id="salir" class="ot_listar">Salir</li></ul>            
            <ul class="lista_botones"><li id="excel" class="ot_listar">Ver Excel</li></ul>
	</div> 	
        <div style = "display: table; width: 100%;border:0px solid #000;height:565px;">
            <div id='cabecera' style = "float: left; height:45%; width: 100%;border:0px solid #000;"><?php require_once "tareoot_cabecera.php";?></div>
            <div id='label' style='float:left;height:35px;font-size:13px;font-weight: bold;'>
                PERSONA:
                <input type='hidden' name='codres' id='codres' style='width:50px;'>
                <input type='hidden' name='dni' id='dni' style='width:50px;'> 
                ::SELECCIONE::
            </div>
            <div id='detalle' style = "float: left; height:45%; width: 100%;border:0px solid #000;"><?php require_once "tareoot_detalle.php";?></div>						
        </div>
    </div> 
</body>
</html>
