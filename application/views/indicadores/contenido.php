<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
        <link rel="stylesheet" href="<?php echo css;?>tabla.css" type="text/css">
    </head>
    <body>
        <table class="table_kpi">
            <tr>
                <th>SERIEOC</th>
                <th>NUMEROOC</th>
                <th>FECHAOC</th>
                <th>FECHA_APROB_OC</th>
                <th>FECHA_REG_OC</th>
                <th>SERIENEA</th>
                <th>NUMERONEA</th>
                <th>FECHANEA</th>
                <th>FECHAGUIA</th>
                <th>FECAHREG_NEA</th>
                <th>CODPRO</th>
                <th>DESPRO</th>
                <th>INDICADOR_DIAS</th>
            </tr>
            <?php echo $fila; ?>
        </table>
    </body>
</html>

