<!DOCTYPE html>
<html>
<head>
    <title><?php echo titulo;?></title> 
    <link rel="stylesheet" href="<?php echo css;?>estilos.css" type="text/css">
    <link rel="stylesheet" href="<?php echo css;?>nav.css" type="text/css">
    <link rel="stylesheet" href="<?php echo css;?>theme.css" type="text/css">
    <link rel="stylesheet" href="<?php echo css;?>calendario/calendar-win2k-2.css" type="text/css" media="all" title="win2k-cold-1"/>	    
     <script type="text/javascript">
     base_url='<?php echo base_url(); ?>'
     </script> 
    <script type="text/javascript" src="<?php echo js;?>constants.js"></script> 
    <script type="text/javascript" src="<?php echo js;?>calendario/calendar.js"></script>
    <script type="text/javascript" src="<?php echo js;?>calendario/calendar-es.js"></script>
    <script type="text/javascript" src="<?php echo js;?>calendario/calendar-setup.js"></script>
    <script type="text/javascript" src="<?php echo js;?>jquery.js"></script>
<!--    <script type="text/javascript" src="< ?php echo js;?>produccion/tareo_rpt.js"></script>-->
    <script type="text/javascript" src="<?php echo js;?>compras/requis.js"></script>
    <script>
    function habilitar()
    {
      if (frmBusqueda.estado.checked)
          {
              a=frmBusqueda.fecha_ini.value
              b=frmBusqueda.fecha_fin.value
           
           frmBusqueda.fecha_ini.value='__/__/____';
           frmBusqueda.fecha_fin.value='__/__/____';
       }
       else
           {
              frmBusqueda.fecha_ini.value=a
              frmBusqueda.fecha_fin.value=b
           }
    }
    
    function LimpiarOT(){
        $("#ot").val('');
        $("#codot").val('');
    }
    function LimpiarProducto(){
       
        $("#pro_descripcion").val('');
        $("#pro_codigo").val('');
        
    }

    </script>
</head>
<body>
    <div id="container">
        <div class="header">REQUISICIONES POR OTs</div>
        <div class="case_top2">
           <form method="post" id="frmBusqueda">
                <table width="98%" cellspacing="3" cellpadding="3" border="0" style="margin-top:5px;">
                    <tbody>
                        <tr>
                            <td align="left" width="5%">OT/ C.C.:</td>
                            <td align="left" width="35%">
                                <input type="hidden" name="tipot" id="tipot" style="width: 100px;" class="cajaPequena" value="<?php echo $tipot;?>">
                                <input type="hidden" name="codot" id="codot" style="width: 100px;" class="cajaPequena" value="<?php echo $codot;?>">
                                <input type="hidden" name="tipoexport" id="tipoexport" style="width: 70px;"  readonly="readonly" class="cajaPequena" value="<?php echo $tipoexport;?>">
                                <input type="hidden" name="numero" id="numero" style="width: 70px;"  readonly="readonly" class="cajaPequena" value=""> 
                                <input type="hidden" name="opcion" id="opcion" style="width: 70px;"  readonly="readonly" class="cajaPequena" value=""> 
                                <input type="text" name="ot" id="ot" style="width: 70px;"   class="cajaPequena" value="<?php echo $ot;?>" readonly="readonly" onclick="agrega_ot('');" >
                                <img src="<?php echo img;?>ver.png" name="ver" id="ver" width="16" height="16" border="0" id="ver" onMouseOver="this.style.cursor='pointer'" onclick="agrega_ot('');" title="Buscar OT">
                                    <input type="button" name="btn_limpiar_o" id="btn_limpiar_o" value="Limpiar" onclick="LimpiarOT()" > 
                                . TIPO: <?php echo $filtrotipo;?>
                                 
                            </td>
                            <td align="center" width="35%">FECHA INI.:
                                <span style="width:500px;border:0px solid #000;" id="Fecha1">
                                    <input  name="fecha_ini" id="fecha_ini" title="Fecha" value="<?php echo $fecha_ini; ?>" type="text" readonly="readonly" style='width:80px;' >
                                     <!--  onClick="popUpCalendar(this, frmBusqueda.fecha_ini, 'mm/dd/yyyy');"-->
                                     <img src="<?php echo img;?>calendario.png" name="Calendario1" id="Calendario1" width="25"  border="0" id="Image1" onMouseOver="this.style.cursor='pointer'" title="Calendario" >
                                     <script type="text/javascript">
                                        Calendar.setup({
                                                inputField     :    "fecha_ini",      // id del campo de texto
                                                ifFormat       :    "%d/%m/%Y",       // formato de la fecha, cuando se escriba en el campo de texto
                                                button         :    "Calendario1",   // el id del bot?n que lanzar? el calendario
                                                onUpdate       :    function(){
                                                   $('#tipoexport').val('');
                                                   //$("#frmBusqueda").submit();
                                                }
                                        });
                                        </script>
                                </span>
                            </td>
                            <td align="center" width="25%">FECHA FIN:
                                <span style="width:500px;border:0px solid #000;" id="Fecha1" >
                                    <input  name="fecha_fin" id="fecha_fin" title="Fecha" value="<?php echo $fecha_fin;?>" type="text" readonly="readonly" style='width:80px;'>
                                    <img src="<?php echo img;?>calendario.png" name="Calendario2" id="Calendario2" width="25"  border="0" id="Image1" onMouseOver="this.style.cursor='pointer'" title="Calendario" ALIGN=BASELINE>
                                    <script type="text/javascript">
                                            Calendar.setup({
                                                    inputField     :    "fecha_fin",      // id del campo de texto
                                                    ifFormat       :    "%d/%m/%Y",       // formato de la fecha, cuando se escriba en el campo de texto
                                                    button         :    "Calendario2",   // el id del bot?n que lanzar? el calendario
                                                    onUpdate       :    function(){
                                                       $('#tipoexport').val('');
                                                       //$("#frmBusqueda").submit();
                                                    }
                                            });
                                    </script>
                                </span>
                            </td> 
                        </tr>
                        <tr>
                            <td align="right">RESPONSABLE:</td>
                            <td align="left">
                                <div style="text-align:left;"><?php echo $filtroresponsable;?></div>
                            </td>
                            <td align="left">
    <!-- nuevo campo para buscar productos -->
                                <!--div style="float:left;width:100%;text-align: center;">PRODUCTO:<?php //  echo $filtroproducto;?></div-->
                                <div style="float:left;width:100%;text-align: center;">PRODUCTO:
                                 
                                <input type="text" name="pro_descripcion" id="pro_descripcion" style="width: 200px;"   class="cajaPequena" value="<?php echo $pro_descripcion;?>" readonly="readonly" onclick="BuscarProducto('');" >
                                <input type="hidden" name="pro_codigo" id="pro_codigo" style="width: 100px;" class="cajaPequena" value="<?php  echo $pro_codigo;?>">
                                
                                
                                <img src="<?php echo img;?>ver.png" name="ver" id="ver" width="16" height="16" border="0" id="ver" onMouseOver="this.style.cursor='pointer'" onclick="BuscarProducto('');" title="Buscar OT">
                                <input type="button" name="btn_limpiar_p" id="btn_limpiar_p" value="Limpiar" onclick="LimpiarProducto();" >
                                 
                                 </div>
     <!-- nuevo campo para buscar productos -->         
                            </td>
                            <td>Limpiar Fechas<input type='checkbox' name='estado' id='estado' onclick=habilitar()></td>
                        </tr>                                                          
                    </tbody>
                </table>
            </form> 
        </div> 
       <div class="case_botones">
                      Registros: <?php echo $registros;?>
            <ul class="lista_botones"><li id="salir" class="requisiciones_x_ot">Salir</li></ul>            
            <ul class="lista_botones"><li id="excel" class="requisiciones_x_ot">Ver Excel</li></ul>
        <!--   <ul class="lista_botones"><li id="pdf" class="requisiciones_x_ot">Ver Pdf</li></ul>-->
            <ul class="lista_botones"><li id="html" class="requisiciones_x_ot">Ver Html</li></ul>  
	</div> 
        <div style = "float: left; width: 100%;height: 600px;border:1px solid #000;">
            <!--div style = "float: left; height:50px; width: 99%;border:1px solid #000;">
                <table border='1' style='width:100%;'>
                    <tr align='center' style="height:50px;">
                        <td style='width:7%;'><div>FECHA<br>REQ.</div></td>
                        <td style='width:3%;'><div>SERIE<br>REQ.</div></td>                
                        <td style='width:7%;'><div>NUMERO<br>REQ.</div></td>
                        <td style='width:9%;'><div>TIPO</div></td>
                        <td style='width:34%;'><div>DESCRIPCION</div></td>
                        <td style='width:8%;'><div>CANTIDAD</div></td>
                        <td style='width:5%;'><div>UNIDAD</div></td>
                        <td style='width:9%;'><div>PESO</div></td>
                        <td style='width:9%;'><div>O.C.</div></td>
                        <td style='width:9%;'><div>ATENCION</div></td>
                    </tr>
                </table>
            </div-->
            
            <div style = "float: left; height: 100%;width:100%;overflow: auto;border:1px solid #000;">
                <table heigth="50%" border="1" id="tabla_cabecera">
                    <tr style='font:12px; font:arial;color:#fff;background:#8AA8F3;'>
                       
                       <?php //if($codot=='0001935' or $codot=='0001931') { 
                        
                       echo '<td colspan="10" align="center">GENERALES</td>';
                       echo '<td colspan="9" align="center">CANTIDADES</td>';
                       echo '<td colspan="2" align="center">PESOS</td>';
                       echo '<td colspan="3" align="center">PRECIOS</td>';
                       echo '<td colspan="8" align="center">FECHAS</td>';
                       echo '<td colspan="8" align="center">SEGUIMIENTO</td>';
                       
                      /* }
                       else
                       echo '<td colspan="9" align="center">GENERALES</td>';
                       echo '<td colspan="5" align="center">CANTIDADES</td>';
                       echo '<td colspan="2" align="center">PESOS</td>';
                       echo '<td colspan="5" align="center">FECHAS</td>';
                       echo '<td colspan="6" align="center">SEGUIMIENTO</td>';  */ ?>
                    </tr>
                    <tr align="center" style="height:50px;font:12px; font:arial;color:#fff;background:#8AA8F3;">
                        <?php
                        echo '<td style="width:3%;"><div>TIPO</div></td>';
                        echo '<td style="width:7%;"><div>NUMERO<br>REQ.</div></td>';
                        echo '<td style="width:3%;">OT</div></td>';
                        echo '<td style="width:25%;">RESPONSABLE</div></td>';
                        echo ' <td style="width:3%;">DPTO</div></td>';
//                        echo ' <td style="width:9%;"><div>TIPO</div></td>';
                        echo '<td style="width:9%;"><div>CODIGO</div></td>';
                        echo '<td style="width:9%;"><div>ALMACEN</div></td>';
                        echo '<td style="width:9%;"><div>MATERIAL</div></td>';
                        echo '<td style="width:9%;"><div>LINEA</div></td>';
                        echo '<td style="width:34%;"><div>PRODUCTO</div></td>';
                        echo '<td style="width:5%;"><div>UNIDAD</div></td>';
                        echo '<td style="width:8%;"><div>SOLICITADO</div></td>';
                        echo '<td style="width:8%;"><div>COMPRADO</div></td>';
//                        echo '<td style="width:8%;"><div>VALORIZADO</div></td>';
                        echo '<td style="width:8%;"><div>ALMACENADO</div></td>';
                        echo '<td style="width:8%;"><div>ATENDIDO</div></td>';
//                        echo '<td style="width:8%;"><div>PENDIENTE x ATENDER</div></td>';
                        echo '<td style="width:8%;"><div>STOCK</div></td>';
                        
                      //  print_r($codot);die;
                     //   if($codot=='0001935' or $codot=='0001931') { 
                        
                        echo '<td style=width:8%;><div>STOCK<BR>TRANS.</div></td>';
                        echo '<td style=width:8%;><div>STOCK<BR>COMPROM.</div></td>';
                        echo '<td style=width:8%;><div>STOCK<br>DISP</div></td>';
                        
                    //      }?>
                        <td style='width:9%;'><div>PESO<br>SOLIC(KG)</div></td>
                        <td style='width:9%;'><div>PESO<br>ATEND(KG)</div></td>
                        <td style='width:9%;'><div>TOTAL SOLI<br>S/.</div></td>
                        <td style='width:9%;'><div>TOTAL COMP<br>S/.</div></td>
                        <td style='width:9%;'><div>TOTAL ATEND<br>S/.</div></td>
                        <td style="width:7%;"><div>FECHA<br>EMISION REQ.</div></td>
                        <td style="width:7%;"><div>FECHA<br>APROBACION REQ.</div></td>
                        <td style="width:7%;"><div>FECHA<br>SOLI OC.</div></td>
                        <td style="width:7%;"><div>FECHA<br>OC.</div></td>
                        <td style="width:7%;"><div>FECHA<br>APROBACION OC.</div></td>
                        <td style="width:7%;"><div>FECHA<br>ING.ALMACEN.</div></td>       
                        <td style="width:7%;"><div>FECHA<br>REG.ALMACEN.</div></td>     
                        <td style="width:7%;"><div>FECHA<br>VALE</div></td>   
                        <td style='width:9%;'><div>PROCESO<br>DE COMPRA</div></td>
                        <td style='width:9%;'><div>S.C.</div></td>
                        <td style='width:9%;'><div>O.C.</div></td>
                        <td style='width:9%;'><div>NEA</div></td>
                        <td style='width:9%;'><div>VALESA</div></td>
                        <td style='width:9%;'><div>OBSERVACION</div></td>
                        <td style='width:9%;'><div>RUC</div></td>
                        <td style='width:9%;'><div>RAZON SOCIAL</div></td>
                    </tr>
                    <?php echo $fila;?>
                    <?php
                    if($fila==''){
                        ?>
                        <tr><td colspan="25" align="center">::: NO EXISTEN REGISTROS :::</td></tr>    
                        <?php
                    }
                    ?>
                </table>
            </div>
        </div>	
    </div>
</body>
</html>
