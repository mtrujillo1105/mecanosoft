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
    <script type="text/javascript" src="<?php echo js;?>personal/asistencia.js"></script>
</head>
<body>
<div id="container">
    <div class="header">REPORTE DEL PERSONAL PARA <?php $this->entidad = $this->session->userdata('entidad'); if($this->entidad=='01'){echo " METALES";} else{echo " GALVANIZADO";} ?></div>	
    <div class="case_top2">
        <form id='frmBusqueda' method="post">
            <table width="98%" cellspacing="0" cellpadding="3" border="0" style="margin-top:5px;">
                <tbody>
                    <tr>
                        <td align="left" width="12%"><span>TIPO TRABAJADOR:</span></td>
                        <td align="left"  width="38%">
                            <?php echo $seltrabajador;?>
                        </td>                        
                        <td align="left">
                            <span style="width:150px;border:0px solid #000;">Del: 
                                <input  name="fInicio" id="fInicio" title="Fecha Inicio" value="<?php echo $fInicio;?>" type="text" class="cajaPequena" readonly="readonly"/>									
                                <img src="<?php echo img;?>calendario.png" name="Calendario1" id="Calendario1" width="16" height="16" border="0" id="Image1" onMouseOver="this.style.cursor='pointer'" title="Calendario">      
                                <script type="text/javascript">
                                    Calendar.setup({
                                        inputField     :    "fInicio",      // id del campo de texto
                                        ifFormat       :    "%d/%m/%Y",       // formato de la fecha, cuando se escriba en el campo de texto
                                        button         :    "Calendario1"   // el id del botï¿½n que lanzarï¿½ el calendario
                                    });
                                </script>		
                            </span>	
                            <span>AL</span>
                            <span style="width:150px;border:0px solid #000;">
                                <input  name="fFin" id="fFin" title="Fecha Inicio" value="<?php  echo $fFin; ?>" type="text" class="cajaPequena" readonly="readonly">									
                                <img src="<?php echo img;?>calendario.png" name="Calendario2" id="Calendario2" width="16" height="16" border="0" id="Image1" onMouseOver="this.style.cursor='pointer'" title="Calendario">     
                                <script type="text/javascript">
                                    Calendar.setup({
                                        inputField     :    "fFin",      // id del campo de texto
                                        ifFormat       :    "%d/%m/%Y",       // formato de la fecha, cuando se escriba en el campo de texto
                                        button         :    "Calendario2"   // el id del botï¿½n que lanzarï¿½ el calendario
                                    });
                                </script>	
                                &nbsp;
                            </span>
                        </td>  
                        <td align="left">
                            TURNO: <?php echo $selproyecto;?>
                        </td>
                    </tr>   
                    <tr>
                        
                        
                         <td align="left">
                            <span>CENTRO DE COSTOS:  </span>					
                        </td> 
                        <td align="left">					
                            <?php echo $selccosto_conta; ?>
                        </td> 
                        <td align="left">
                            <span>AREA: <?php echo $selccosto; ?> </span>					
                        </td> 
                       
                        <td align="left">
                            <span>
                                <label>Consolidado<input type='radio' name="tipodetalle" id="tipodetalle" value="C" <?=($tipodetalle=='C'?"checked='checked'":"");?> onclick="$(this).val('C');"></label>                                  
                                <label>Detallado<input type='radio' name="tipodetalle" id="tipodetalle" value="D" <?=($tipodetalle=='D'?"checked='checked'":"");?> onclick="$(this).val('D');"></label>                                                                                                   
                            </span>					
                        </td>    
                    </tr>
                </tbody>
            </table>                     
        </form>
    </div>
    <div class="case_botones">
        Registros: <?php echo $registros;?>
        <ul class="lista_botones"><li id="salir" class="personal">Salir</li></ul>          
        <ul class="lista_botones"><li id="excel" class="personal">Ver Excel</li></ul>
        <ul class="lista_botones"><li id="html" class="personal">Ver Html</li></ul>  
        <ul class="lista_botones"><li id="otro" class="personal">Regularizar</li></ul>  
    </div>  
    <div id="idcontenido" style ="display: table;float:none; width: 100%;border:0px solid #000;height:450px;">
        <?php echo $alerta;?>
        <div style = "height:50px; width:99%;border:0px solid #000;">
            <table border='1' cellpadding='0' style='border-collapse: collapse;' width='100%' height="40px" align=center>
                <?php if($tipodetalle=="C"):?>
                <tr>
                    <td align='center' width="4%"><b>Tipo</b></td>
                    <td align='center' width="6%"><b>DNI</b></td>
                    <td align='center' width="17%"><b>Nombre</b></td>
                    <td align='center' width="20%"><b>Cargo</b></td>
                    <td align='center' width="26%"><b>Centro Costo</b></td>                    
                    <td align='center' width="26%"><b>Area</b></td>  
                    <td align='center' width="4%"><b>Tard<br>(min)</b></td>
                    <td align='center' width="4%"><b>Horas<br>Trabaj</b></td>
                    <td align='center' width="4%"><b>Horas<br>Extra</b></td>
                </tr>                    
                <?php endif;?>
                <?php if($tipodetalle=="D"):?>
                <tr>
                    <td align='center' width="4%"><b>Tipo</b></td>
                    <td align='center' width="6%"><b>DNI</b></td>
                    <td align='center' width="19%"><b>Nombre</b></td>
                    <td align='center' width="16%"><b>Cargo</b></td>
                    <td align='center' width="16%"><b>Horario</b></td>
                    <td align='center' width="20%"><b>Centro Costo</b></td>  
                    <td align='center' width="20%"><b>Area</b></td>                    
                    <td align='center' width="7%"><b>Fecha</b></td>
                    <td align='center' width="4%"><b>Tard<br>(min)</b></td>
                    <td align='center' width="4%"><b>Horas<br>Trabaj</b></td>
                    <td align='center' width="4%"><b>Horas<br>Extra</b></td>
                    <td align='center' width="4%"><b>Hora<br>Ingreso</b></td>
                    <td align='center' width="4%"><b>Hora<br>Salida</b></td>
                    <td align='center' width="4%"><b>Salida<br>Ref</b></td>
                    <td align='center' width="4%"><b>Ingreso<br>Ref</b></td>
                </tr>                                    
                <?php endif;?>
                <?php
                if($filacompuesta!=""){
                    echo $filacompuesta;    
                }
                else{
                    echo "<td colspan='8' align='center'>NO EXISTEN REGISTROS</td>";
                }
                ?>
            </table>        
        </div>
<!--        <div style="margin-top:0px;height:450px;border:0px solid #000;overflow: scroll;">
            <table border='1' cellpadding='0' style='border-collapse: collapse' width='100%' align=center>
                < ?php echo $filacompuesta; ?>
            </table> 
        </div>   -->
    </div>
</div>	
</body>
</html>