<!DOCTYPE html>
<html>
<head>
    <title><?php echo titulo;?></title> 
    <link rel="stylesheet" href="<?php echo css;?>estilos.css" type="text/css">
    <link rel="stylesheet" href="<?php echo css;?>nav.css" type="text/css">
    <link rel="stylesheet" href="<?php echo css;?>theme.css" type="text/css">
    <link rel="stylesheet" href="<?php echo css;?>calendario/calendar-win2k-2.css" type="text/css" media="all" title="win2k-cold-1"/>	    
    <link rel="stylesheet" href="<?php echo css;?>estilos.css" type="text/css">
    <link rel="stylesheet" type="text/css" href="<?php echo js;?>tablefixed/css/base.css" />
    <script type="text/javascript" src="<?php echo js;?>constants.js"></script>      
    <script type="text/javascript" src="<?php echo js;?>calendario/calendar.js"></script>
    <script type="text/javascript" src="<?php echo js;?>calendario/calendar-es.js"></script>
    <script type="text/javascript" src="<?php echo js;?>calendario/calendar-setup.js"></script>
    <script type="text/javascript" src="<?php echo js;?>jquery.js"></script>
    <script type="text/javascript" src="<?php echo js;?>tablefixed/javascript/jquery.fixheadertable.js"></script>
    <script type="text/javascript" src="<?php echo js;?>produccion/tareo_rpt.js"></script>
 
<script type="text/javascript">
    $(document).ready(function() {
        
        $('#TablaPrincipal').fixheadertable({
            
            colratio    : [80, 350, 150, 150, 240, 90, 100, 70, 70],
            height      : 430,
           
            zebra       : true,
            sortable    : true,
            sortedColId : 3, 
            sortType    : ['date', 'string', 'string', 'string', 'string', 'integer','integer','decimal','decimal'],
            dateFormat  : 'm/d/Y',
            
            
        });
    });
</script>   
    
<script language=JavaScript>
function limpiarText()
{
document.frmBusqueda.tipoexport.value = "";
}
function LimpiarOT(){
        $("#ot").val('');
        $("#codot").val('');
    }
    
function compararFecha(fecha1,fecha2)
{
    var fechaSep = fecha1.split('/');
    var fechaSep2 = fecha2.split('/');
    var indicada = new Date(fechaSep[2],fechaSep[1]-1,fechaSep[0]);
    var indicada2 = new Date(fechaSep2[2],fechaSep2[1]-1,fechaSep2[0]);
    if(indicada > indicada2){
        alert('[Error] La inicial no puede ser mayor a la final!');
        $("#fecha").val(fech2);
    }     
 
}


</script>

   
    
    <style>
        .tabla_cabecera tr{cursor:pointer;}
    </style>   
    
</head>
<body>
    <div id="container">
        <div class="header">REPORTE DEL TAREO POR OTs</div>
	<div class="case_top">
            <form method="post" name="frmBusqueda" id="frmBusqueda" target=_parent>
                <table width="98%" cellspacing="0" cellpadding="3" border="0" >
                    <tbody>
                        <tr>

                            
                            
                            <td align="left" width="2%">OT:</td>     
                            
                            
                            <td align="left" width="25%">
                                <input type="hidden" name="tipot" id="tipot" style="width: 100px;" class="cajaPequena" value="<?php echo $tipot;?>">
                                <input type="hidden" name="codot" id="codot" style="width: 100px;" class="cajaPequena" value="<?php echo $codot;?>">
                                <input type="text" name="ot" id="ot" style="width: 60px;"  readonly="readonly" class="cajaPequena" value="<?php echo $ot;?>">
                                <img src="<?php echo img;?>ver.png" name="ver" id="ver" width="16" height="16" border="0" id="ver" onMouseOver="this.style.cursor='pointer'" onclick="agrega_ot('');" title="Buscar OT">
                                <input type="button" name="btn_limpiar_o" id="btn_limpiar_o" value="Limpiar" onclick="LimpiarOT()" >
                            </td>   
                            
                            
                            

                            
                            
                            <td align="left" width="3%">AREA:</td>
                            <td align="right" width="10%">
                            <p align="left">
                            <?php echo $filtroarea;?>
                            </td>
                            <td align="right" width="5%"></td>
                            <td align="right" width="22%">FECHA INI:</td>
                            <td align="left" width="5%">
                              <div style="text-align:left;">
                                    <span style="width:500px;border:0px solid #000;" id="Fecha1">
                                        <!-- onblur="$('#frmBusqueda').submit();"  -->
                                        <input  name="fecha" id="fecha" title="Fecha Inicio" value="<?php echo $fecha;?>" type="text" class="cajaPequena" onblur="$('#tipoexport').val('');submit();">									
                                        <img src="<?php echo img;?>calendario.png" name="Calendario1" id="Calendario1" width="16" height="16" border="0" id="Image1" onMouseOver="this.style.cursor='pointer'" title="Calendario">
                                         <script type="text/javascript">
                                                Calendar.setup({
                                                        inputField     :    "fecha",      // id del campo de texto
                                                        ifFormat       :    "%d/%m/%Y",       // formato de la fecha, cuando se escriba en el campo de texto
                                                        button         :    "Calendario1",   // el id del bot?n que lanzar? el calendario
                                                        
                                                        onUpdate       :    function(){
                                                            
                                                            $("#tipoexport").val('');
                                                            
                                                            compararFecha($("#fecha").val(),$("#fechafin").val());
                                                                                                                      //$("#ot").val('');
                                                            //$("#codot").val('');
                                                            //$("#area").val('000');
                                                            //$("#codres").val('000000');
//                                                           $("#frmBusqueda").attr('action','');
//                                                           $("#frmBusqueda").attr("target","_top");  
//                                                           $("#frmBusqueda").submit();
                                                        }
                                                });
                                        </script>		
                                    </span>
                                </div>  
                            </td>

                        </tr>   
                        


                        <tr>
                    
                            
                            <td align="right" width="5%">NOMBRES:</td>
                            <td align="left" width="22%">
                              <div style="text-align:left;">
               				 <?php echo $filtronombre;?>
                              </div>  
                            </td>
                            <td align="left" width="3%">CARGO:</td>
                            <td align="right" width="10%">
                            <p align="left">
                            <?php echo $filtrocargo;?>
                            </td>
                            <td align="left" width="15%">
                                Moneda: 
                                <select id='moneda' name='moneda' >
                                    <option value='S'<?php if($moneda=='S') echo 'selected'; ?>>Soles</option>
                                    <option value='D'<?php if($moneda=='D') echo 'selected'; ?> >Dolares</option>
                                </select>
                            
                            </td>                            
                            <td align="right" width="12%">FECHA FIN:</td>   
                            <td align="left" width="24%">
                             <div style="text-align:left;">
                                    <span style="width:500px;border:0px solid #000;" id="Fechafinal">
                                        <input  name="fechafin" id="fechafin" title="Fecha Fin" value="<?php echo $fechafin; ?>" type="text" class="cajaPequena" onblur="$('#tipoexport').val('');submit();">									
                                        <img src="<?php echo img; ?>calendario.png" name="Calendario2" id="Calendario2" width="16" height="16" border="0" id="Image1" onMouseOver="this.style.cursor='pointer'" title="Calendario">
                                        <script type="text/javascript">
                                               Calendar.setup({
                                                       inputField     :    "fechafin",      // id del campo de texto
                                                       ifFormat       :    "%d/%m/%Y",       // formato de la fecha, cuando se escriba en el campo de texto
                                                       button         :    "Calendario2",   // el id del bot?n que lanzar? el calendario
                                                        
                                                       onUpdate       :    function(){
                                                            
                                                           $("#tipoexport").val('');
                                                           compararFecha($("#fecha").val(),$("#fechafin").val());
                                                           //$("#ot").val('');
                                                           //$("#codot").val('');
                                                           //$("#area").val('000');
                                                           //$("#codres").val('000000');
//                                                          $("#frmBusqueda").attr('action','');
//                                                          $("#frmBusqueda").attr("target","_top");  
//                                                          $("#frmBusqueda").submit();
                                                       }
                                               });
                                        </script>		
                                    </span>
                                </div>                                
                            </td>
                        </tr> 




                                                        
                    </tbody>
                </table>

                
            
                <input type="hidden" name="tipoexport" id="tipoexport" value="<?php echo $tipoexport; ?>">
                
            </form>
            
	</div>   
        
        
    <div class="case_botones">
        <ul class="lista_botones"><li id="salir" class="ot_listar">Salir</li></ul>            
        <ul class="lista_botones"><li id="excel" class="ot_listar">Ver Excel</li></ul> 
        <ul class="lista_botones"><li id="html" class="ot_listar">Buscar</li></ul>
    </div> 	
    
    <div style = "">
        <div style='text-align: left;font-size: 18px;'>
        Total Horas:<?php   echo $TotHoras ;?>
        <br>  Total Monto:<?php echo $TotMontos;?> 
          </div>
        <table id="TablaPrincipal">
            <thead>
                <tr >
                    <th ><div>FECHA</div></th>
                    <th ><div>NOMBRES</div></th>                
                    <th ><div>AREA</div></th>
                    <th ><div>CARGO</div></th>
                    <th ><div>DESCRIPCION</div></th>
                    <th ><div>CANT.</div></th>
                    <th ><div>OT</div></th>
                    <th ><div>Hrs.</div></th>
                    <th ><div>MONTO</div></th>

                </tr>
            </thead>
           <tbody>
                <?php echo $fila_detalle;?>
            </tbody>
            
        </table>
    </div>
</body>
</html>
