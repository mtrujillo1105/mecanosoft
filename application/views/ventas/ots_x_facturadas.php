<?php
session_start();
require_once "../../libreria/conexion.php";
require_once "../../libreria/ExcelWriter.class.php";
$hoy = date("d/m/Y",time());
$fInicio_ini = "01/08/2011";
$fFin_ini    = date("d/m/Y",time());
$tipOt       = "'07','08','10','12'";
$arrFila     = array();
$arrayExcel  = array();
?>
<!DOCTYPE html>
<html>
<head>
<link href="<?php echo css;?>calendario/calendar-win2k-2.css" type="text/css" rel="stylesheet">
<link href=".<?php echo css;?>estilos.css" type="text/css" rel="stylesheet">
<!-- Calendario -->
<script type="text/javascript" src="<?php echo js;?>constants.js"></script> 
<script type="text/javascript" src="<?php echo js;?>calendario/calendar.js"></script>
<script type="text/javascript" src="<?php echo js;?>calendario/calendar-es.js"></script>
<script type="text/javascript" src="<?php echo js;?>calendario/calendar-setup.js"></script>
<script type="text/javascript" src="<?php echo js;?>jquery.js"></script>
<!-- Calendario -->	
<link rel="stylesheet" href="<?php echo css;?>css/estilos.css" type="text/css">
<link rel="stylesheet" href="<?php echo css;?>css/nav.css" type="text/css">
<link rel="stylesheet" href="<?php echo css;?>theme.css" type="text/css">
<link rel="stylesheet" href="<?php echo css;?>calendario/calendar-win2k-2.css" type="text/css" media="all" title="win2k-cold-1"/>	
</head>
<body>
<div id="container">
    <?php require_once view."menu.php";?>
    <div class="name_user">Hola: Martin Trujillo</div>
    <div class="header">REPORTE OTS FACTURADAS POR RANGO DE FECHAS - (NO INCLUYE NUEVAS VENTAS)</div>	
    <div class="case_top2">
        <form method="post" enctype="multipart/form-data">
            <table width="98%" cellspacing="0" cellpadding="3" border="0" style="margin-top:5px;">
                <tbody>
                    <tr>
                        <td align="right" width="18%"><span>DEL</span></td>
                        <td align="left" width="32%">
                            <span style="width:500px;border:0px solid #000;">
                                <input  name="fInicio" id="fInicio" title="Fecha Inicio" value="<?php echo $fInicio_ini;?>" type="text" class="cajaPequena" readonly="readonly">									
                                <img src="../../img/calendario.png" name="Calendario1" id="Calendario1" width="16" height="16" border="0" id="Image1" onMouseOver="this.style.cursor='pointer'" title="Calendario">
                                <script type="text/javascript">
                                    Calendar.setup({
                                        inputField     :    "fInicio",      // id del campo de texto
                                        ifFormat       :    "%d/%m/%Y",       // formato de la fecha, cuando se escriba en el campo de texto
                                        button         :    "Calendario1"   // el id del bot�n que lanzar� el calendario
                                    });
                                </script>		
                            </span>						
                        </td>
                        <td align="right" width="18%"><span>AL</span></td>
                        <td align="left" width="32%">
                            <span>
                                <input  name="fFin" id="fFin" title="Fecha Inicio" value="<?php echo $fFin_ini;?>" type="text" class="cajaPequena" readonly="readonly">									
                                <img src="../../img/calendario.png" name="Calendario2" id="Calendario2" width="16" height="16" border="0" id="Image1" onMouseOver="this.style.cursor='pointer'" title="Calendario">
                                <script type="text/javascript">
                                    Calendar.setup({
                                        inputField     :    "fFin",      // id del campo de texto
                                        ifFormat       :    "%d/%m/%Y",       // formato de la fecha, cuando se escriba en el campo de texto
                                        button         :    "Calendario2"   // el id del bot�n que lanzar� el calendario
                                    });
                                </script>	
                                <input type="hidden" name="ver" id="ver">
                                <textarea style="display:none;" name="dataExcell" id="dataExcell"><?php echo serialize($arrayExcel);?></textarea>
                            </span>	
                        </td>                          
                    </tr>                                    
                </tbody>
            </table>
        </form>
    </div>
    <div class="case_botones">
            <ul class="lista_botones" onclick="$('#ver').val('excel');document.forms[0].submit();"><li id="excel">Ver Excel</li></ul>
            <ul class="lista_botones" onclick="$('#ver').val('pdf');document.forms[0].submit();"><li id="pdf">Ver Pdf</li></ul>
            <ul class="lista_botones" onclick="$('#ver').val('html');document.forms[0].submit();"><li id="html">Ver Html</li></ul>  
    </div> 	
</div>	
</body>
</html>