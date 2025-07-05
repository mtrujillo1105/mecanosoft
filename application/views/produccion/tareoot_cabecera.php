<style>
    .tabla_cabecera tr{cursor:pointer;}
</style>   
<div style = "display: table; width: 100%;border:0px solid #000;">
    <div style = "float: left; height:40px; width: 99%;border:0px solid #000;">
        <table border='1' style='width:98%;' style="height:40px;margin-bottom: 0px;">
            <tr align='center' height="40px;">
                <td style='width:3%;'><div>No</div></td>
                <td style='width:25%;'><div>DATOS PERSONAL</div></td>   
                 <td style='width:11%;'><div>CONDICION</div></td>     
                <td style='width:7%;'><div>ENTRADA</div></td>
                <td style='width:7%;'><div>SALIDA</div></td>
                <td style='width:7%;'><div>H.TRABAJ</div></td>
                <td style='width:27%;'><div>C.LABOR</div></td>
                <td style='width:19%;'><div>No OTs</div></td>
            </tr>
        </table>
    </div>
    <div style = "float: left; height: 200px;overflow: auto; width: 100%;border-bottom:1px solid #ccc;">
        <table border='1' style='width:98.5%;align:left;' id='tabla_cabecera' class='tabla_cabecera'>
            <?php echo $fila_cabecera;?>
        </table>        
    </div>
</div>