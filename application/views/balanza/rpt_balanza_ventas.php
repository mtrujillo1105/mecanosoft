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
    <script type="text/javascript" src="<?php echo js;?>balanza/balanza.js"></script>
    <style>
        .tabla_cabecera tr{cursor:pointer;}
    </style>  
    <style>
    ul {
    padding-left: 0px;
    margin-left: 0px;
    list-style-type: none;
    }	
    </style>
</head>
<body>
    <div class="header">REPORTE DE SEGUIMIENTO DE OS</div>
	<div class="case_top21">
            <form method="post" name="frmBusqueda" id="frmBusqueda" target=_parent>

                <input type="hidden" value="<?=$codres;?>"></input>
                
                <table width="100%" cellspacing="0" cellpadding="3" border="0" >

                    <tbody>
                        <tr>
                            <td align="left" width="15%">TIPO OT:</td>     
                            <td align="left" width="20%">
                                <?php echo $Combotipot;?>
                            </td>     
                            <td align="right" width="12%">DESDE:</td>
                            <td align="left" width="13%">
                                <span style="width:120px;border:0px solid #000;"> 
                                    <input  name="fecha1" id="fecha1" title="Fecha Inicio" value="<?php echo $fecha1;?>" type="text" class="cajaPequena" readonly="readonly"/>									
                                    <img src="<?php echo img;?>calendario.png" name="Calendario1" id="Calendario1" width="16" height="16" border="0" id="Image1" onMouseOver="this.style.cursor='pointer'" title="Calendario">      
                                    <script type="text/javascript">
                                        Calendar.setup({
                                            inputField     :    "fecha1",      // id del campo de texto
                                            ifFormat       :    "%d/%m/%Y",       // formato de la fecha, cuando se escriba en el campo de texto
                                            button         :    "Calendario1",   // el id del botï¿½n que lanzarï¿½ el calendario
                                            onUpdate       :    function(){
                                               $('#tipoexport').val(''); 
                                               //$("#frmBusqueda").submit();
                                            }
                                        });
                                    </script>		
                                </span>	                            
                            <td align="right" width="1%">HASTA:</td>
                            <td align="left" width="22%">
                              <div style="text-align:left;">
                                <span style="width:120px;border:0px solid #000;"> 
                                    <input  name="fecha2" id="fecha2" title="Fecha Inicio" value="<?php echo $fecha2;?>" type="text" class="cajaPequena" readonly="readonly" size="11"/>									
                                    <img src="<?php echo img;?>calendario.png" name="Calendario2" id="Calendario2" width="16" height="16" border="0" id="Image1" onMouseOver="this.style.cursor='pointer'" title="Calendario">      
                                    <script type="text/javascript">
                                        Calendar.setup({
                                            inputField     :    "fecha2",      // id del campo de texto
                                            ifFormat       :    "%d/%m/%Y",       // formato de la fecha, cuando se escriba en el campo de texto
                                            button         :    "Calendario2",   // el id del botï¿½n que lanzarï¿½ el calendario
                                            onUpdate       :    function(){
                                               $('#tipoexport').val(''); 
                                               //$("#frmBusqueda").submit();
                                            }                                
                                        });
                                    </script>		
                                </span>						
                                </div>  
                            </td>
                        </tr>   
                        <tr>
                            <td align="left" width="15%">CLIENTES: </td>
                            <td align="left" width="20%">	
                                <div style="text-align:left;">
                                <?php echo $ComboCliente;?>    
                                </div>  		
                            </td>
                            <td align="right" width="12%"></td>
                            <td align="left" width="13%"></td>
                            <td align="left" width="1%"></td>                         
                            <td align="left" width="10%"></td>   
                        </tr> 
                    </tbody>
                </table>
                <input type="hidden" name="tipoexport" id="tipoexport" value="">
            </form>
	</div>   
<!--    <div class="case_botones" style='position:fixed; float:right;' >-->
    <div class="case_botones">
        Cantidad: <?php echo $registros;?>
        <ul class="lista_botones"><li id="salir" class="balanza">Salir</li></ul>            
        <ul class="lista_botones"><li id="excel" class="balanza">Ver Excel</li></ul> 
        <ul class="lista_botones"><li id="html" class="balanza">Buscar</li></ul>
    </div>
    <div style = "display:table; width:250%; border:0px solid #000;">
        <div style = "float: left; height:50px; width: 99.97%;">
            <table border='3' style='width:100%;' class='tabla_cabecera'>
                <THEAD>
                    <tr class="cabeceraTabla" align='center' style="height:50px;">
                        <td style='width:3%;'><div>N&#176; OS</div></td>
                        <td style='width:4%;'><div>FECHA INGRESO</div></td>                
                        <td style='width:8%;'><div>NOMBRE CLIENTE</div></td>
                        <td style='width:5%;'><div>MATERIAL</div></td>
                        <td style='width:5%;'><div>N&#176; PIEZAS</div></td>
                        <td style='width:5%;'><div>N&#176; CONST.RECEP</div></td>
                        <td style='width:5%;'><div>N&#176; GUIA CLIENTE</div></td>
                        <td style='width:5%;'><div>CLASIFICACION</div></td>
                        <td style='width:5%;'><div>ESTADO</div></td>
                        <td style='width:5%;'><div>PESO</div></td>
                        <td style='width:5%;'><div>PRECIO <br> UNIT ($)</div></td>
                        <td style='width:5%;'><div>SUB<br> TOTAL ($)</div></td>
                        <td style='width:5%;'><div>TOTAL ($)</div></td>
                        <td style='width:5%;'><div>DETRACCION<br>12%</div></td>
                        <td style='width:5%;'><div>SALDO<br>88%</div></td>
                        <td style='width:5%;'><div>FORMA PAGO</div></td>
                        <td style='width:7%;'><div>OBSERVACIONES</div></td>
                        <td style='width:5%;'><div>N&#176; GUIA MIMCO</div></td>
                        <td style='width:4;'><div>F. ESTIMADA</div></td>
                        <td style='width:4%;'><div>F. DESPACHO</div></td>
                    </tr>
                </THEAD>
            </table>
        </div>
        <div  id='detalle'  style = " overflow-x: hidden; float: left; height: 370px;overflow: auto; width: 99.99%;">
           <table border='2' style='width:100%; height: 100%;' id='tabla_detalle' class='tabla_cabecera'>
              <?php echo $fila;?>
          </table>  
        </div>
    </div>
<style>
* { margin: 0px;padding: 0px;}
html, body { height: 99%; overflow-y: hidden; overflow-x: auto;}
div#contenido 
{
   width:100%;
   height:100%;
   background-color:#CC3300;
   margin: 0px;
   padding: 0px;
   position:absolute;
}
</style>    
</body>
</html>
