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
    <script type="text/javascript" src="<?php echo js;?>produccion/tareo.js"></script>

</head>
<body>
<div id="container">
    <div class="header">INDICADORES HORAS HOMBRES POR OT   (Solo Metales)</div>	
    <div class="case_top2">
        <form method="post" enctype="multipart/form-data" id="frmHorasH" name="frmHorasH">
            <table width="98%" cellspacing="0" cellpadding="3" border="0" style="margin-top:5px;">
                <tbody>
                    <tr>
                        <td align="right" width="9%">TIPO OT</td>
                        <td align="left" width="14%"><?php echo $selecttipoot;?></td>
                        <td align="right" width="5%">ESTADO</td>
                        <td align="left" width="12%"><?php echo $selecestado;?></td>    
                        <td align="left" width="38%">
                            <span id="Fecha1" style="width:150px;border:0px solid #000;float: left;">
                                <input  name="fecha_ini" id="fecha_ini" title="Fecha Inicio" value="<?php echo $fInicio;?>" type="text" class="cajaPequena" readonly="readonly">									
                                <img src="<?php echo img;?>calendario.png" name="Calendario1" id="Calendario1" width="16" height="16" border="0" id="Image1" onMouseOver="this.style.cursor='pointer'" title="Calendario">
                                <script type="text/javascript">
                                    Calendar.setup({
                                        inputField     :    "fecha_ini",      // id del campo de texto
                                        ifFormat       :    "%d/%m/%Y",       // formato de la fecha, cuando se escriba en el campo de texto
                                        button         :    "Calendario1"   // el id del bot�n que lanzar� el calendario
                                    });
                                </script>		
                            </span>
                            <span style="float:left;">Hasta:</span>
                            <span id="Fecha2" style="width:150px;border:0px solid #000;display:block;float: left;">
                                <input  name="fecha_fin" id="fecha_fin" title="Fecha Inicio" value="<?php echo $fFin;?>" type="text" class="cajaPequena" readonly="readonly">									
                                <img src="<?php echo img;?>calendario.png" name="Calendario2" id="Calendario2" width="16" height="16" border="0" id="Image1" onMouseOver="this.style.cursor='pointer'" title="Calendario">
                                <script type="text/javascript">
                                    Calendar.setup({
                                        inputField     :  "fecha_fin",      // id del campo de texto
                                        ifFormat       :  "%d/%m/%Y",       // formato de la fecha, cuando se escriba en el campo de texto
                                        button         :  "Calendario2",   // el id del bot�n que lanzar� el calendario
                                        onChange        :  function(){
                                            alert('Bumerang');
                                        }
                                    });
                                </script>	                              
                            </span>	                            
                        </td>            
                    </tr>          
                    <tr>
                        <td align="right" width="9%"><span>TIPO TORRE</span></td>
                        <td align="left" width="14%"><?php echo $selecttorre;?></td>
                        <td align="right" width="5%">CLIENTE</td>
                        <td align="left" width="12%"><?php echo $seleccliente;?></td>
                        <td align="left">
                            <span style="float:left;">
                                General<input type="radio" name="tipo_reporte" id="tipo_reporte" value="G" <?=($tipo_reporte=='G'?"checked='checked'":"");?> onclick="submit();">
                                Detalle<input type="radio" name="tipo_reporte" id="tipo_reporte" value="D" <?=($tipo_reporte=='D'?"checked='checked'":"");?> onclick="submit();">                                
                            </span>
                            <span style='text-align:rigth;'>Registros:<?php echo $registros;?></span>
                        </td>                          
                    </tr>                  
                </tbody>
            </table>
            <input type="hidden" name="tipoexport" id="tipoexport"></input>
        </form>
    </div>
    <div class="case_botones">
        <ul class="lista_botones"><li id="salir" class="HorasH">Salir</li></ul>              
        <ul class="lista_botones"><li id="excel" class="HorasH">Ver Excel</li></ul>
        <ul class="lista_botones"><li id="html" class="HorasH">Ver Html</li></ul>  
    </div> 
        <div style = "float: left; height:40px; width: <?php echo $tipo_reporte=='D'?'1800px;':'100%;';?>;border:1px solid #000;">
            <table border='1' style="width:<?php echo $tipo_reporte=='D'?'1800px;':'100%;';?>">
                <THEAD>
                <tr align='center' style="height:40px;" class="cabeceraTabla">
                    <td style='width:5%;'><div><font size="1"><b>NRO OT</b></font></div></td>
                    <td style='width:5%;'><div><font size="1"><b>FECHA<br>INICIO</b></font></div></td> 
                    <td style='width:5%;'><div><font size="1"><b>FECHA<br>TERMINO</b></font></div></td> 
                    <td style='width:15%;'><div><font size="1"><b>SITE</b></font></div></td>                
                    <td style='width:15%;'><div><font size="1"><b>CLIENTE</b></font></div></td>
                    <td style='width:5%;'><div><font size="1"><b>PESO(TN)</b></font></div></td>
<!--                    <td style='width:2.12%;'><div><font size="1"><b>ALT.</b></font></div></td>-->
                    <td style='width:10%;'><div><font size="1"><b>TIPO<br>TORRE</b></font></div></td>
                    <td style='width:5%;'>__________</td>
                    <?php
                    if($tipo_reporte=='D'){
                    ?>
                        <td style='width:5%;'><div><font size="1"><b>HABILIADO MANUAL</b></font></div></td>
                        <td style='width:5%;'><div><font size="1"><b>HABILIADO AUTOMATICO</b></font></div></td>
                        <td style='width:5%;'><div><font size="1"><b>ESTRUCTURADO</b></font></div></td>
                        <td style='width:5%;'><div><font size="1"><b>GALVANIZADO</b></font></div></td>
                        <td style='width:5%;'><div><font size="1"><b>PINTURA</b></font></div></td>
                        <td style='width:5%;'><div><font size="1"><b>DESPACHO</font></b></div></td>
                        <td style='width:5%;'><div><font size="1"><b>CONTROL</b></font></div></td>
                    <?php
                    }
                    ?>
                    <td style='width:5%;'><div><font size="1"><b>TOTAL</b></font></div></td>
                </tr>
                </THEAD> 
            </table>
        </div>
        
        <div style = "float: left; height: 435px;overflow:auto; width:<?php echo $tipo_reporte=='D'?'1800px;':'100%;';?>;border:1px solid #000;">
            
            
    <style>
    ul {
    padding-left: 0px;
    margin-left: 0px;
    list-style-type: none;
    }	
    </style>
    
            <table border='1' style='width:<?php echo $tipo_reporte=='D'?'1800px;':'100%;';?>;'>
                <?php 
                if($fila!=''){
                    echo $fila;
                }
                else{
                    echo "<td colspan='15'>NO EXISTEN REGISTROS</td>";
                }
                ?>
            </table>        
        </div>
</div>		
    </div>
</body>
</html>