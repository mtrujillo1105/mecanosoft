<table align="center" width="100%" cellspacing="0" cellpadding="0" border="0" >
<THEAD>
    <tr class="cabeceraTabla">
        <td width="8%"><b><font size="1">N&Uacute;MERO</font></b></td>
        <td width="9%"><b><font size="1">FECHA</font></b></td>
        <td width="9%"><b><font size="1">PROYECTO</font></b></td>
        <td width="12%"><b><font size="1">SITE</font></b></td>
        <td width="13%"><b><font size="1">RAZ&Oacute;N SOCIAL </font></b> </td>
        <td width="5%"><b><font size="1">MONTO TOTAL</font></b></td>
        <td width="15%"><b><font size="1">ESTADO</font></b></td>
    </tr>
</THEAD>    
<tbody>
    <?php
    if($fila!=""){
        echo $fila;    
    }
    else{
        echo "<tr><td colspan='7' align='center'>NO EXISTEN REGISTROS.</td></tr>";
    }
    ?>
    </tbody>
</table>

