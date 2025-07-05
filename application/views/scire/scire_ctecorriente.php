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
        <script type="text/javascript" src="<?php echo js;?>jquery.js"></script>
        <script type="text/javascript" src="<?php echo js;?>scire/ctecorriente.js"></script>
        <title></title>
    </head>
    <body>
        <div class="container">
            <div class="header">CUENTA CORRIENTE TRABAJADOR</div>
            <div class="case_top">
                <form method="post" id="frmPlanilla">
                    <input type="hidden" id="tipoexcel" name="tipoexcel" value="" />
                    <table width="98%" cellspacing="0" cellpadding="3" border="0" class="fuente8" align="center">
                        <tbody>
                            <tr>
                                <td align="right" width="10%">Anio</td>
                                <td align="left" width="32%">
                                    <?php echo $selanio;?>&nbsp;
                                    Mes: <?php echo $selmes;?>
                                </td>
                                <td align="left" width="26%">
                                    Periodo:
                                </td>
                                <td align="left"></td>
                            </tr>
                            <tr>
                                <td align="right" width="10%">Centro costo</td>
                                <td align="left" width="32%"><?php echo $selccosto;?></td>
                                <td align="left" width="26%"><?php echo $selperiodo;?>&nbsp;</td>
                                <td align="left">
                                    <ul class="lista_botones"><li id="salir" class="ot_listar">Salir</li></ul>
                                    <ul class="lista_botones"><li id="html" class="control">Ver Html</li></ul>
                                </td>
                            </tr> 
                        </tbody>
                    </table>
                </form>            
            </div>
            <div class="div_fondo">
                <div class="case_middle">
                    <div class="case_botones">
                        <div class="tab_content">
                            <ul class="lista_botones"><li id="excel_ctecorriente" class="control">Ver Excel</li></ul>
                            <br/><br/><br/>
                            <table>
                                <tr>
                                    <td colspan="11"><?php if(isset($numreg)) echo "Numero de Registros : " . $numreg ?></td>
                                </tr>
                                <tr>
                                    <th>Nombres</th>
                                    <th>FecMov</th>
                                    <th>Monto</th>
                                    <th>NroCuotas</th>
                                    <th>DesCuota</th>
                                    <th>Interes</th>
                                    <th>Cuota</th>
                                    <th>Periodo</th>
                                    <th>Estado</th>
                                    <th>Motivo</th>
                                    <th>Operacion</th>
                                </tr>
                                <?php if ($fila != ""): ?>
                                    <?php echo $fila; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="10" align="center">NO EXISTEN REGISTROS</td>
                                    </tr>
                                <?php endif; ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
