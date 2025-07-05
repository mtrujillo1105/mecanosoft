<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="<?php echo css;?>estilos.css" type="text/css">
        <link rel="stylesheet" href="<?php echo css;?>nav.css" type="text/css">
        <script type="text/javascript" src="<?php echo js;?>jquery-1.9.1.js"></script>
        <script type="text/javascript" src="<?php echo js;?>jquery-ui.js"></script>
        <link rel="stylesheet" href="<?php echo css;?>theme.css" type="text/css">
        <link rel="stylesheet" href="<?php echo css;?>calendario/calendar-win2k-2.css" type="text/css" media="all" title="win2k-cold-1"/>
        <script type="text/javascript" src="<?php echo js;?>calendario/calendar.js"></script>
    <script type="text/javascript" src="<?php echo js;?>calendario/calendar-es.js"></script>
    <script type="text/javascript" src="<?php echo js;?>calendario/calendar-setup.js"></script>
        <script type="text/javascript" src="<?php echo js;?>jquery.js"></script>
        <script type="text/javascript" src="<?php echo js;?>scire/horaslaboradas.js"></script>
        <title></title>
    </head>
    <body>
        <div class="container">
            <div class="header">HORAS LABORADAS TRABAJADOR</div>
            <div class="case_top2">
                <form method="post" id="frmHorasLaboradas">
                    <input type="hidden" id="param" name="param" value="1" />
                    <table width="98%" cellspacing="0" cellpadding="3" border="0" class="fuente8" align="center">
                        <tbody>
                            <tr>
                                <td align="right" width="10%">Tipo Trabajador</td>
                                <td align="left" width="32%">
                                    <?php echo $seltrabajador;?>&nbsp;
                                </td>
                                <td align="left" width="26%">
                                    <div style="text-align:left;">
                                        <span style="width:500px;border:0px solid #000;" id="Fecha1">
                                            Fecha Ini : <input  name="fecha" id="fecha" title="Fecha Inicio" value="<?php echo (isset($fecha) && trim($fecha) != "") ? $fecha : date('d/m/Y');?>" type="text" class="cajaPequena">
                                            <img src="<?php echo img;?>calendario.png" name="Calendario1" id="Calendario1" width="16" height="16" border="0" id="Image1" onMouseOver="this.style.cursor='pointer'" title="Calendario">
                                            <script type="text/javascript">
                                                Calendar.setup({
                                                        inputField     :    "fecha",      // id del campo de texto
                                                        ifFormat       :    "%d/%m/%Y",       // formato de la fecha, cuando se escriba en el campo de texto
                                                        button         :    "Calendario1",   // el id del bot?n que lanzar? el calendario
                                                });
                                            </script>
                                        </span>
                                    </div>
                                </td>
                                <td align="left">
                                    <span style="width:500px;border:0px solid #000;" id="Fecha2">
                                        Fecha Fin : <input  name="fechafin" id="fechafin" title="Fecha Fin" value="<?php echo (isset($fechafin) && trim($fechafin) != "") ? $fechafin : date('d/m/Y');?>" type="text" class="cajaPequena">
                                        <img src="<?php echo img;?>calendario.png" name="Calendario2" id="Calendario2" width="16" height="16" border="0" id="Image1" onMouseOver="this.style.cursor='pointer'" title="Calendario">
                                        <script type="text/javascript">
                                            Calendar.setup({
                                                    inputField     :    "fechafin",      // id del campo de texto
                                                    ifFormat       :    "%d/%m/%Y",       // formato de la fecha, cuando se escriba en el campo de texto
                                                    button         :    "Calendario2",   // el id del bot?n que lanzar? el calendario
                                            });
                                        </script>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td align="right" width="10%">Centro costo</td>
                                <td align="left" width="32%"><?php echo utf8_encode($selccosto);?></td>
                                <td align="left" width="26%">&nbsp;</td>
                                <td align="left">&nbsp;</td>
                            </tr> 
                        </tbody>
                    </table>
                </form>            
            </div>
            <div class="case_botones" style="width:80%;border:0px solid #000;text-align: right;float: left;font-family: arial;font-size: 11px;margin-top:4px;">Hora del reporte <?php echo $hora_actual;?></div>            
            <div class="case_botones" style="width:20%;border:0px solid #000;float: left;">
                <ul class="lista_botones"><li id="salir">Salir</li></ul>            
                <ul class="lista_botones"><li id="html" class="control">Ver Html</li></ul>
                <ul class="lista_botones"><li id="excel_ctecorriente" class="control" onclick="location.href = 'http://nazca/mimco_planillas/index.php/scire/scire/exportarExcel'">Ver Excel</li></ul>                
            </div> 
            <div class="case_botones" style="float:left;width:100%;" >
                <table width="100%">
                    <tr>
                        <td colspan="4"></td>
                        <td colspan="<?php if(isset($cantreg)) echo $cantreg; ?>">HORAS TRABAJADAS</td>
                    </tr>
                    <tr align="center">
                        <th>Tipo</th>
                        <th>Persona</th>
                        <th>DNI</th>
                        <th>Centro de Costo</th>
                        <?php if(isset($cont)): ?>
                            <?php foreach($cont as $k => $v): ?>
                                <th><?php echo $v;?></th>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <th>Total Horas Trabajadas</th>
                        <?php if($ccosto=='000000000000028'):?>
                            <th>Total Horas Produccion</th>
                            <th>Diff Valorizada</th>
                        <?php endif;?>
                    </tr>
                    <?php 
                    if($fila!=""){
                        echo $fila; 
                    }
                    else{
                        echo "<td colspan='6' align='center'>NO EXISTEN REGISTROS</td>";
                    }
                    ?>
                </table>
            </div>
        </div>
    </body>
</html>
