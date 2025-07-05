<!DOCTYPE html>
<html>
<head>
<!-- Calendario -->
<link rel="stylesheet" href="<?php echo css;?>estilos.css" type="text/css">
<link rel="stylesheet" href="<?php echo css;?>nav.css" type="text/css">
<link rel="stylesheet" href="<?php echo css;?>theme.css" type="text/css">
<link rel="stylesheet" href="<?php echo css;?>calendario/calendar-win2k-2.css" type="text/css" media="all" title="win2k-cold-1"/>
<link rel="stylesheet" href="<?php echo css;?>jquery-ui.css" type="text/css">
<!-- Calendario -->	
<script type="text/javascript" src="<?php echo js;?>constants.js"></script> 
<script type="text/javascript" src="<?php echo js;?>calendario/calendar.js"></script>
<script type="text/javascript" src="<?php echo js;?>calendario/calendar-es.js"></script>
<script type="text/javascript" src="<?php echo js;?>calendario/calendar-setup.js"></script>
<script type="text/javascript" src="<?php echo js;?>jquery.js"></script>
<script type="text/javascript" src="<?php echo js;?>jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo js;?>highcharts.js"></script>
<script type="text/javascript" src="<?php echo js;?>exporting.js"></script>
<script type="text/javascript" src="<?php echo js;?>personal/asistencia.js"></script>
<script>
function grabar(obj){
    if(confirm('Esta seguro que desea modificar los registros de marcacion?')){
        obj.disabled = true;
        document.getElementById("btncerrar").disabled = true;
        $("#accion").val("G");
        $("#frmBusqueda").submit();
    }
}
function cerrar(){
    if(confirm('Luego de esto no podra hacer modificaciones para este dia. Esta seguro?')){
        $("#accion").val("C");
        $("#frmBusqueda").submit();
    } 
}

function onlyNumbers(evento) {
    var key = evento.which ? evento.which : evento.keyCode;
    
 
    var keychar = String.fromCharCode(key);
    if(key === 8 || key === 16 || key === 190|| key === 109) {
        return true;
    }
    if((("0123456789abcdefghij`").indexOf(keychar) > -1)) {
        return true; 
    }
    else {
        return false;
    }
}

function setTrueRecord(obj) {
    var id = obj.id.substring(1);
    document.getElementById("modo" + id).value = "e";
}

function validarCampo(valor) {
    var val = valor.value;
    var id = valor.id;
    
    if(val == '-'){
        return;
    }
    if(val != "") {
        var longitud = val.length;
        if(longitud < 5) {
            window.alert("Debe cumplir con el formato hh:mm o hh:mm:ss");
            valor.value = "";
            return;
        }
        else if(longitud == 5) {
            if(!val.substr(2,1) == ":") {
                window.alert("Debe usar los : como separador");
                valor.value = "";
                return;
            }
        }
        if(longitud > 5 && longitud < 8) {
            window.alert("Debe cumplir con el formato hh:mm o hh:mm:ss");
            valor.value = "";
            return;
        }
        else if(longitud == 8) {
            if(!val.substr(5,1) == ":") {
                window.alert("Debe usar los : como separador");
                return;
            }
        }
        if(parseInt(val.substr(0,2)) > 24 || parseInt(val.substr(0,2)) < 0) {
            window.alert("Las horas deben estar entre 0 y 24");
            valor.value = "";
            return;
        }
        if(parseInt(val.substr(3,2)) > 60 || parseInt(val.substr(3,2)) < 0) {
            window.alert("Los minutos deben estar entre 0 y 60");
            valor.value = "";
            return;
        }
    }
//    $("#i000002").css('background-color','red');
//    return;
}
</script>   
</head>
<body  style='font-size:62.5%;'>
<form id='frmBusqueda' method='post'>    
    <div style="text-align:left;widt:99%;font-size: 12px;font-family: Arial;font-weight: bold;">REGULARIZA MARCACIONES</div>    
    <div style="align:left;border:0px solid #000;height:40px;margin-top: 8px;">
        <table width="100%" border="0">
            <tr>
                <td align="left">TIPO: <?php echo $seltrabajador;?></td>
                <td align="left">C.COSTO: <?php echo $selccosto;?></td>
                <td align="left">
                    <span style="width:150px;border:0px solid #000;">FECHA: 
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
                </td>
                <td>
                    <ul class="lista_botones"><li id="html" class="regulariza">Buscar</li></ul>  
                    <ul class="lista_botones"><li id="excel" class="regulariza">Excel</li></ul>
                </td>
            </tr>
        </table>
    </div>
    <div style = "float: left; height:50px; width: 99%;border:1px solid #000;">
        <table border='1' style='width:100%;'>
            <tr align='center' style="height:50px;">
                <td style='width:7%;'><div>TIPO</div></td>
                <td style='width:10%;'><div>DNI</div></td>
                <td style='width:20%;'><div>NOMBRES</div></td>                
                <td style='width:20%;'><div>C.COSTO</div></td>
                <td style='width:10%;'><div>FECHA</div></td>
                <td style='width:8%;'><div>H.INGRESO</div></td>
                <td style='width:8%;'><div>H.SALIDA</div></td>
                <td style='width:8%;'><div>SALIDA REF</div></td>
                <td style='width:8%;'><div>INGRESO REF</div></td>
            </tr>
        </table>
    </div>
    <div style = "float: left; height: 440px;overflow: auto; width: 100%;border:1px solid #000;">
        <table border='1' style='width:100%;'><?php echo $fila;?></table>        
    </div>
    <?php
    if($estado!=2){
        ?>
        <div style="margin-top:10px;">
            <input type='hidden' id="btncerrar" value='Cerrar marcaciones' onclick='cerrar();'>
            <input type='button' value='Grabar' onclick='grabar(this);'>
            <input type='hidden' name="accion" id="accion">
        </div>        
        <?php
    }
    ?>
</form>            
</body>
</html>