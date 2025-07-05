<html>
<head>
    <title><?php echo titulo;?></title>   
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Content-Language" content="es"> 
    <link rel="stylesheet" href="<?php echo css;?>estilos.css" type="text/css">
    <link rel="stylesheet" href="<?php echo css;?>calendario/calendar-win2k-2.css" type="text/css" media="all" title="win2k-cold-1"/>	
    <link rel="stylesheet" href="<?php echo css;?>basic.css" type="text/css">
    <link rel="stylesheet" href="<?php echo css;?>nav.css" type="text/css">
    <link rel="stylesheet" href="<?php echo css;?>theme.css" type="text/css">
    <link rel="stylesheet" href="<?php echo css;?>jquery.pnotify.css" type="text/css"></link>
    <script type="text/javascript" src="<?php echo js;?>constants.js"></script> 
    <script type="text/javascript" src="<?php echo js;?>calendario/calendar.js"></script>
    <script type="text/javascript" src="<?php echo js;?>calendario/calendar-es.js"></script>
    <script type="text/javascript" src="<?php echo js;?>calendario/calendar-setup.js"></script>
    <script type="text/javascript" src="<?php echo js;?>jquery.js"></script>
    <script type="text/javascript" src="<?php echo js;?>jquery.min.1.4.2.js"></script>
    <script type="text/javascript" src="<?php echo js;?>almacen/producto.js"></script>
    <script type="text/javascript" src="<?php echo js;?>jquery.pnotify.min.js"></script>
    <script type="text/javascript" src="<?php echo js;?>blockui.js"></script>
    <script type="text/javascript">
        function unloadPage(){
            location.reload();
        }
        
        function reloadMovements(){
            $.blockUI({ 
                message: 'Espere un momento por favor.',
                css: { 
                   border: 'none',
                   font: '20px',
                    padding: '15px', 
                    backgroundColor: '#000', 
                    '-webkit-border-radius': '10px', 
                    '-moz-border-radius': '10px', 
                    opacity: .5, 
                    color: '#fff' 
                } 
            });
            
            $.ajax({
                url:'<? echo base_url(); ?>index.php/almacen/producto/upd_Comprometido',
                dataType:'html',
                async: true,
                type:'post',
                /*data:{'arr':'<? echo addslashes($arr_data_products); ?>'  ,
                'n':'valorn1'},*/
                success : function(response){
                   alert(response);
                   location.reload();
                }
            });
        }
        
        function excel(){
            var mapForm = document.createElement("form");
            mapForm.target = "Map";
            mapForm.method = "POST";
            
            mapForm.action = "<? echo base_url(); ?>index.php/almacen/producto/expo";

            var mapInput = document.createElement("input");
            mapInput.type = "hidden";
            mapInput.name = "arr";
            mapInput.value = "<? echo addslashes($arr_data_products); ?>";
            mapForm.appendChild(mapInput);

            document.body.appendChild(mapForm);
            map = window.open("", "Map", "status=0,title=0,height=30,width=40,scrollbars=1");

            if (map) {
                mapForm.submit();
                
            } else {
                alert('Habilitar popups para poder exportar reporte.');
            }


            /*$.ajax({
                url:'<? echo base_url(); ?>index.php/almacen/producto/expo',
                dataType:'html',
                async: true,
                type:'post',
                data:{'arr':'<? echo addslashes($arr_data_products); ?>'  ,
                'n':'valorn1'},
            success : function(response){
                console.log(response);
                //window.location.href = response.url;
                //console.log(response);
            },
            error : function(response){
                if(response.responseText != undefined)
                    $('body').append(response.responseText);            
                    console.warn(response);
                }
            });*/
        }
        
        function popup(url,ancho,alto){
            var posicion_x; 
            var posicion_y; 
            posicion_x  =(screen.width/2)-(ancho/2); 
            posicion_y  =(screen.height/2)-(alto/2); 
            window.showModalDialog(url, "mimco.com.pe", "width="+ancho+",height="+alto+",menubar=0,toolbar=0,directories=0,scrollbars=yes,resizable=no,left="+posicion_x+",top="+posicion_y+"");
        }
        
        
        $(document).ready(function() {	
            $('div.title').each(function(i){
                $(this).prepend('<a name="ex_' + (i+1) + '" />');
                $('#links').append('<a title="' + $('span', this).html() + '" class="ui-state-default ui-corner-all" href="#ex_' + (i+1) + '"><span style="float: left; margin-right: 0.3em; margin-top : -2px;" class="ui-icon ui-icon-triangle-1-e"></span> Example #' + (i+1) + '</a>');
            });


        /*var text_header = '<? echo $tbl_headers; ?>' 
        var text_data ='<?php if($fila==""){echo "<td align=\"center\" colspan=\'11\'>NO EXISTEN REGISTROS</td>";}else{echo addslashes($fila);}?>';
                        
                                
        $('#0').html(text_header+text_data).fixheadertable({ 
            caption : 'Stock de Productos', 
            colratio : [100, 70, 70, 600, 70, 80,80,80,80], 
            height : screen.height-440, 
            width : screen.width-30, 
            addTitles : true,
            zebra : true, 
            sortable : true,
            sortedColId : 3, 
            resizeCol : true,
            pager : true,
            rowsPerPage	 : 50,
            //sortType : ['integer', 'string', 'string', 'string', 'string', 'date'],
            sortType : ['string','string','string','string','string','integer','integer','integer','integer'],
            dateFormat : 'm/d/Y'
        });*/
				
    });
    </script>
    <style>a{font-weight: bold;text-decoration:underline;}</style>          
</head>
<body>
<div id="container">
    <?php echo validation_errors("<div class='error'>",'</div>');
       $this->codot   = $this->session->userdata('codot');?>     
    <div class="header">STOCK DE PRODUCTOS POR OT - (INCLUYE TRANSITO Y COMPROMETIDO)</div>	
    <div class="case_top">
        <form method="post" id="frmBusqueda">
            <table width="98%" cellspacing="3" cellpadding="3" border="0" style="margin-top:5px;">
                <tbody>
                    <tr>
                        <td align="right" width="18%">OT/ C.C.:</td>
                        <td align="left" width="32%">
                            <input type="text" name="ot" id="ot" style="width: 70px;"   class="cajaPequena" value="">
                            <img src="<?php echo img;?>ver.png" name="ver" id="ver" width="16" height="16" border="0" id="ver" onMouseOver="this.style.cursor='pointer'" onclick="agrega_ot('');" title="Buscar OT">                                                    
                        </td>                        
                        <td align="right" width="18%">Fecha</td>
                        <td align="left">
                           <span style="width:500px;border:0px solid #000;" id="Fecha1" >
                                <input  name="fecha" id="fecha" title="Fecha" value="<?php echo $fecha;?>" type="text" readonly="readonly" style='width:80px;'>
                                <img src="<?php echo img;?>calendario.png" name="Calendario2" id="Calendario2" width="25"  border="0" id="Image1" onMouseOver="this.style.cursor='pointer'" title="Calendario" ALIGN=BASELINE>
                                <script type="text/javascript">
                                        Calendar.setup({
                                                inputField     :    "fecha",      // id del campo de texto
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
                        <td align="right" width="18%">Tipo Material</td>
                        <td align="left" width="32%"><?php echo $cboTipoamaterial;?></td>                        
                        <td align="right" width="18%">Linea</td>
                        <td align="left"><?php echo $cboFamilia;?></td>                          
                    </tr>                                   
                    <tr>
                        <td align="right" width="18%">Tipo Almacen</td>
                        <td align="left" width="32%"><?php echo $cboTipoalmacen;?></td>
                        <td align="right" width="18%">Ver todos <?php echo $chknegativo;?>&nbsp;&nbsp;&nbsp;&nbsp;
                          <?php if($this->codot!='0003722'){echo 'Ver precios'. $chkprecio;}?>
                        </td>
                        <td align="left">
                                <div style="float:left; width:60%;" id="divMoneda"><?php echo $cboMoneda;?></div>
                                <div style="float:left;width:40%;text-align:right;">Cantidad:<?php echo $registros;?></div>                                
                        </td>
                    </tr>                                    
                </tbody>
            </table>
            <?php echo $oculto;?>
        </form>
    </div>
    <div id="idcontenido">    
        <div class="case_botones" style="width:80%;border:0px solid #000;text-align: right;float: left;font-family: arial;font-size: 11px;margin-top:4px;">
            <!--div class="theme_mnu_btn">
                <a href="javascript:reloadMovements()" class ="theme_link" >Recalcular REQ/ORD</a>
            </div-->
            Hora del reporte <?php echo $hora_actual;?>
        </div>
	<div class="case_botones" style="width:20%;border:0px solid #000;float: left;">
            <ul class="lista_botones"><li id="salir" class="xfacturar">Salir</li></ul>            
            <ul class="lista_botones"><li id="excel" class="xfacturar">Ver General</li></ul>
            <ul class="lista_botones"><li id="html" class="xfacturar">Ver Html</li></ul>  
	</div>
        <div id="idcontenido2">
            <div style="clear:both;width:100%">
                <table border='0' width='100%'>
                    <thead>
                        <tr align="center" class="cabeceraTabla">
                            <td rowspan='2'>CODIGO</td>
                            <td rowspan='2'>T.ALMACEN</td>
                            <td rowspan='2'>MATERIAL</td>
                            <td rowspan='2'>PRODUCTO</td>	
                            <td rowspan='2'>UNIDAD</td>
                            <td colspan='4' align='center'>STOCK</td>
                            <?php
                            if($checkedprecio){
                                IF ($this->codot!='0001934'){
                                ?>
                            
                                <td colspan='2' align='center'>PRECIO <?=($moneda_doc=='S'?'S/.':'$');?></td>                             
                                <td colspan='2' align='center'>TOTALES <?=($moneda_doc=='S'?'S/.':'$');?></td>                                
                                
                                <?php
                                }
                                else{?>
                                <td colspan='2' align='center'>PRECIO <?=($moneda_doc=='S'?'S/.':'$');?></td>                             
                                <td colspan='2' align='center'>TOTALES <?=($moneda_doc=='S'?'S/.':'$');?></td>     
                                  
                                <?php }
                            }    
                            ?>
                        </tr>
                        <tr align="center" class="cabeceraTabla">
                            <td>STOCK ACTUAL</td>
                            <td>STOCK COMPROM</td>
                            <td>STOCK TRANS</td>		
                            <td>STOCK DISPONIBLE</td>  
                            <?php
                            if($checkedprecio){
                                ?> 
                            
                            
                            <?php if($this->codot!='0001934'){echo '<td>ULTIMA COMPRA';?> <?php echo ($moneda_doc=='S'?'S/.':'$');?><?php echo'</td>';} else{echo '';}?>
                            <?php if($this->codot!='0001934'){echo '<td>PRECIO PROM.';?> <?=($moneda_doc=='S'?'S/.':'$');?><?php echo'</td>';} else{echo '<td colspan="2">COSTO PROMEDIO</td>';}?>
                            <?php if($this->codot!='0001934'){echo '<td>TOTAL ULTIMA COMPRA';?> <?=($moneda_doc=='S'?'S/.':'$');?><?php echo'</td>';} else{echo '';}?>
                            <?php if($this->codot!='0001934'){echo '<td>PRECIO PROM.';?> <?=($moneda_doc=='S'?'S/.':'$');?><?php echo'</td>';} else{echo '<td colspan="2">VALORIZACIÓN</td>';}?>   
                                <?php
                            }
                            ?>  
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if($fila==""){
                            echo "<td align='center' colspan='11'>NO EXISTEN REGISTROS</td>";
                        }
                        else{
                            echo $fila;    
                        }
                        ?>
                    </tbody>
                    <?php
                    if($fila!='' && $checkedprecio){
                        ?>
                        <tfoot>
                            <tr>
                                <td align='right' colspan="11">&nbsp;</td>
                                 <?php if($this->codot!='0001934'){echo '<td align=right>';?> <?php echo number_format($total_precioprod,6);?><?php echo'</td>';} else{echo '';}?>
                                <td align='right'><?php echo number_format($total_preprom,6);?></td>
                            </tr>
                        </tfoot>                                            
                        <?php
                    }
                    ?>
                </table>
                <br/><br/>
            </div>
        </div>
        <div id="iddetalle"></div>
    </div>          
</div>	       
</body>
</html>