<!DOCTYPE html>
<html>
<head>
    <title><?php echo titulo;?></title>        

    <link rel="stylesheet" href="<?php echo css;?>estilos.css" type="text/css">
    <link rel="stylesheet" href="<?php echo css;?>nav.css" type="text/css">
    <link rel="stylesheet" href="<?php echo css;?>theme.css" type="text/css">   
    <meta name="tipo_contenido"  content="text/html;" http-equiv="content-type" charset="utf-8">
    <script type="text/javascript" src="<?php echo js;?>constants.js"></script>      
    <script type="text/javascript" src="<?php echo js;?>jquery.js"></script>    
    <script type="text/javascript" src="<?php echo js;?>compras/ocompra.js"></script>
</head>
<body>
<div id="container">
    <div class="header">REPORTE DE INDICADORES DE REQUISICIONES ATENDIDAS</div>	
    <div class="case_top2">
        
        <form  class="frmAtendidas"  method="post" enctype="multipart/form-data" id="frmAtendidas">
            <table width="98%" cellspacing="0" cellpadding="3" border="0" style="margin-top:5px;">
                <tbody>
                    <tr>
                        <td align="right" width="18%"><span>PERIODO POR AÑO</span></td>
                        <td align="left">
                            <select class="comboMedio" name="anio" id="anio">
                                <option selected="selected" value="<?php echo $anio_ini;?>"><?php echo $anio_ini;?></option>
                                <option value="2010">2010</option>
                                <option value="2011">2011</option>
                                <option value="2012">2012 - Incompleto</option>
                            </select>
                        </td>  
                        
                        <td align="right" width="19%"><span>REQUISICIONES SOLICITADAS PARA</span></td>
                        <td align="left" width="156">
						<select class="comboMedio" onchange="if(options[selectedIndex].value){location = options[selectedIndex].value}"  name="menu" size="1">
						<option value="http://localhost/mimco/index.php/compras/ocompra/indicador_requis_atendidas" selected>Materiales</option>
						<option value="http://localhost/mimco/index.php/compras/ocompra/indicador_requis_transporte">Transporte</option>
						<option value="http://localhost/mimco/index.php/compras/ocompra/indicador_requis_otros">Otros</option>
						</select>                            
                        </td>  
                        
                        <!--td align="right" width="18%"><span></span></td-->
                        
                        
                        <td align="left" width="32%">
                            <span>
                                <input type="hidden" name="ver" id="ver">
                                 <input type="hidden" name="tipo" id="tipo">
                                 
                                <textarea style="display:none;" name="dataExcell" id="dataExcell"><?php echo serialize($arrayExcel);?></textarea>
                            </span>	
                        </td>   
                        
                    </tr>                                    
                </tbody>
            </table>
        </form>
    </div>
    
    <div class="case_botones">
        <ul class="lista_botones"><li id="salir" class="atendidas">Salir</li></ul>          
        <ul class="lista_botones"><li id="grafica" class="atendidas">Ver Grafica</li></ul>        
        <ul class="lista_botones"><li id="excel" class="atendidas">Ver Excel</li></ul>
        <ul class="lista_botones"><li id="pdf" class="atendidas">Ver Pdf</li></ul>
        <ul class="lista_botones"><li id="html" class="atendidas">Ver Html</li></ul>  
    </div> 
    
    
    <span style="float:left;width:80%;text-align:left;font-size:14px;margin-top:40px;">
    Indicadores de Materiales  para el año <?php echo $anio_ini;?>
    </span>	
        <div style="clear:both;">
            <table border='1' width='100%'>
                <THEAD>
                <tr class="cabeceraTabla">
                    <td width='200px;'>CONCEPTO MES</td>
                    <td width='120px;'>ENERO</td>
                    <td width='120px;'>FEBRERO</td>
                    <td width='120px;'>MARZO</td>
                    <td width='120px;'>ABRIL</td>
                    <td width='120px;'>MAYO</td>
                    <td width='120px;'>JUNIO</td>
                    <td width='120px;'>JULIO</td>
                    <td width='120px;'>AGOSTO</td>
                    <td width='120px;'>SETIEMBRE</td>
                    <td width='120px;'>OCTUBRE</td>
                    <td width='120px;'>NOVIEMBRE</td>
                    <td width='120px;'>DICIEMBRE</td>
                    <td width='100px;'>TOTAL</td>
                </tr>
                </THEAD>
               <tbody>
               <?php echo $fila;?>
               </tbody>
            </table>
        </div>		
</div>	
</body>
</html>