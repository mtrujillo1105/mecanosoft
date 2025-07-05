<!DOCTYPE html>
<html>
    <head>
        <script>
        base_url = "<?php echo base_url(); ?>"
        </script>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="<?php echo css;?>estilos.css" type="text/css">
        <link rel="stylesheet" href="<?php echo css;?>nav.css" type="text/css">
        <link rel="stylesheet" href="<?php echo css;?>theme.css" type="text/css">
        <link rel="stylesheet" href="<?php echo css;?>calendario/calendar-win2k-2.css" type="text/css" media="all" title="win2k-cold-1"/>
        <link rel="stylesheet" href="<?php echo css;?>basic.css" type="text/css">
        <link rel="stylesheet" href="<?php echo css;?>estilos.css" type="text/css">
        <style>
        ul {
        padding-left: 0px;
        margin-left: 0px;
        list-style-type: none;
        }	
        </style>        
        <script type="text/javascript" src="<?php echo js;?>constants.js"></script> 
        <script type="text/javascript" src="<?php echo js;?>calendario/calendar.js"></script>
        <script type="text/javascript" src="<?php echo js;?>calendario/calendar-es.js"></script>
        <script type="text/javascript" src="<?php echo js;?>calendario/calendar-setup.js"></script>
        <script type="text/javascript" src="<?php echo js;?>jquery.js"></script>
        <script type="text/javascript" src="<?php echo js;?>scire/scire.js"></script>
        <title><?php echo titulo;?></title>
    </head>
    <body>
        <div class="container">
            <div class="header">HORAS TRABAJADAS SCIRE VS. MIMCO</div>
            <div class="case_top2">
                <form method="post" id="frmPlanilla">
                    <input type="hidden" id="tipoexcel" name="tipoexcel" value="" />
                    <table width="98%" cellspacing="0" cellpadding="3" border="0" class="fuente8" align="center">
                        <tbody>
                            <tr>
                                <td align="right" width="10%">Anio: </td>
                                <td align="left" width="32%">                                  
                                    <?php echo $selanio;?>&nbsp;                               
                                </td>
                                <td align="left" width="26%">
                                    Periodo: <?php echo $selperiodo;?>&nbsp;
                                </td>
                                <td align="left">
                                    <span style="float:left;">Desde:</span>
                                    <span id="Fecha1" style="width:150px;border:0px solid #000;float: left;">
                                        <input  name="fecha_ini" id="fecha_ini" title="Fecha Inicio" value="<?php echo $fInicio;?>" type="text" class="cajaPequena" readonly="readonly">											
                                    </span>
                                    <span style="float:left;">Hasta:</span>
                                    <span id="Fecha2" style="width:150px;border:0px solid #000;display:block;float: left;">
                                        <input  name="fecha_fin" id="fecha_fin" title="Fecha Inicio" value="<?php echo $fFin;?>" type="text" class="cajaPequena" readonly="readonly">										                              
                                    </span>	                                    
                                </td>
                            </tr>   
                            <tr>
                                <td align="right" width="10%">Centro costo</td>
                                <td align="left" width="32%"><?php echo $selccosto_conta;?></td>
                                <td align="left" width="26%">Area:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $selccosto;?></td>
                                <td align="left">&nbsp;</td>
                            </tr> 
                        </tbody>
                    </table>
                    <input type="hidden" name="txt_report" id="txt_report" value='per_consolidado'>
                </form>            
            </div>
            <div class="case_botones" style="width:80%;border:0px solid #000;text-align: right;float: left;font-family: arial;font-size: 11px;margin-top:4px;">Cantidad: <?php echo $registros;?></div>            
            <div class="case_botones" style="width:20%;border:0px solid #000;float: left;">
                <ul class="lista_botones"><li id="salir">Salir</li></ul>            
                <ul class="lista_botones"><li id="excel">Ver Excel</li></ul>
                <ul class="lista_botones"><li id="html">Ver Html</li></ul>  
            </div> 
            <div class="case_botones" style="float:left;width:100%;" >
                <table width="100%">
                    <tr>
                        <td colspan="4"></td>
                        <td colspan="2">SCIRE</td>
                        <td colspan="2">SIDDEX</td>
                    </tr>
                    <tr align="center">
                        <th>DNI</th>
                        <th>Persona</th>
                        <th>Centro de Costo</th>
                        <th>CONCEPTOS</th>
                        <th>HORAS</th>
                        <th>MONTO S/.</th>
                        <th>HORAS</th>
                        <th>MONTO S/.</th>                         
                    </tr>
                    <?php 
                    if($fila!=""){
                        echo $fila; 
                    }
                    else{
                        echo "<td colspan='8' align='center'>NO EXISTEN REGISTROS</td>";
                    }
                    ?>
                </table>
            </div>
        </div>
    </body>
</html>