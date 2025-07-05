    <style>
        .tabla_detalle tr{cursor:pointer;}
    </style>

<div style = "display: table; width: 99%;">
    <div style='display:none;'><input type='text' name='txtDetalle' id='txtDetalle' value="<?php echo $nrofilas;?>"></div>
    <div style = "float: left; height:50px; width: 99%;">
        <table border='1' style='width:100%;height:50px;'>
            <tr align='center' >
                <td style='width:1%;'><div>Item</div></td>
                <td style='width:9%;'><div>NroOt</div></td>                
                <td style='width:20%;'><div>Site de trabajo</div></td>
                <td style='width:17%;'><div>Area</div></td>
                <td style='width:6%;'><div>Horas</div></td>
                <td style='width:7%;'><div>Cantidad</div></td>
                <td style='width:20%;'><div>Descripcion</div></td>
                <td style='width:7%;'><div>Borrar</div></td>
            </tr>
        </table>
    </div>
    <div style = "float: left; height: 175px;overflow: auto; width: 100%;">
        <form id='frmDetalle' onsubmit="valida_tareo_total();" method='post'>
            <div>
                <table border='1' style='width:100%;' id='tabla_detalle'><?php echo $fila_detalle;?></table> 
            </div>
            <?php
            if($estado!='C'){
            ?>
                <div style='float:left;width:100%;margin-top:5px;margin-down:5px;'>
                    <center>
                        <input type='hidden' name="codres" id="codres" value="<?php echo $codres;?>">
                        <input type='hidden' name="fecha" id="fecha" value="<?php echo $fecha;?>">
                        <input type='hidden' name="dni" id="dni" value="<?php echo $dni;?>">
                        <input type='button' onclick='graba_tareo_total();' name="btn1" id="btn1" value='GRABAR'>
                    </center>
                </div>	
            <?php
            }
            ?>
        </form>
    </div>
</div>