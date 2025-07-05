<!DOCTYPE html>
<html>
<head>
<link href="<?php echo css;?>calendario/calendar-win2k-2.css" type="text/css" rel="stylesheet">
<link href="<?php echo css;?>estilos.css" type="text/css" rel="stylesheet">
<!-- Calendario -->
<script type="text/javascript" src="<?php echo js;?>calendario/calendar.js"></script>
<script type="text/javascript" src="<?php echo js;?>calendario/calendar-es.js"></script>
<script type="text/javascript" src="<?php echo js;?>calendario/calendar-setup.js"></script>
<script type="text/javascript" src="<?php echo js;?>jquery.js"></script>
<script type="text/javascript" src="<?php echo js;?>comercial/asistencia.js"></script>
<!-- Calendario -->	
<link rel="stylesheet" href="<?php echo css;?>estilos.css" type="text/css">
<link rel="stylesheet" href="<?php echo css;?>nav.css" type="text/css">
<link rel="stylesheet" href="<?php echo css;?>theme.css" type="text/css">
<link rel="stylesheet" href="<?php echo css;?>calendario/calendar-win2k-2.css" type="text/css" media="all" title="win2k-cold-1"/>	
</head>
<body>
<div id="container">
    <div style="text-align: center;margin-top: 5px;">
        <table border="1" width="60%">
            <thead>
                <tr>
                    <th colspan="4">RENDIMIENTO FACTURACION</th>
                </tr>
            </thead>
            <tbody>
                <?php echo $fila;?>
            </tbody>
        </table>
    </div>
    <div style="margin-top: 10px;"><img src="example12.png" border="0"></div>
<!--    <div style="text-align: center;margin-top: 5px;">
        <table border="1" width="60%">
            <thead>
                <tr>
                    <th colspan="<?php echo count($arrClientes);?>">RENDIMIENTO FACTURACION POR CLIENTE</th>
                </tr>
            </thead>
            <tbody>
                < ? php echo //$fila2;?>
            </tbody>
        </table>
    </div>    -->
    <div style="margin-top: 10px;"><img src="example11.png" border="0"></div>
</div>	
</body>
</html>