<head>
    <title><?php echo $title;  ?></title>
</head>
<?php

?>
<style>
    .label{
        width: 100px;
        float:left;
        text-align: right;
        margin-right: 10px;
        clear:left;
    }
    .txt{
        
        width: 150px;
        float:left;
    }
    .clear{
        clear: both;
        height:5px;
    }
</style>
<form method="post" action="<?php echo base_url(); ?>index.php/scire/scire/export_bank_val">
    <input type="hidden" name="var_bank" value = "<?php echo @$var_bank; ?>">
    <input type="hidden" name="var_validate" value = "S">
    
    <div class="label">Cuenta</div>
    <input type="text" name="txt_cuenta" class="txt" value = "<?php echo @$var_cuenta; ?>">
    
    <div class="clear"></div>

    <div class="label">Moneda</div>
    <input type="text" name="txt_moneda" class="txt" value = "PEN"> &nbsp;: PEN - USD
    
    <div class="clear"></div>
    
    <div class="label">Proceso</div>
    <input type="text" name="txt_proceso" class="txt" value = "A"> &nbsp;: <strong>A</strong>: Inmediato - <strong>F</strong>: Fecha futura - <strong>H</strong>: Horario del ejecución
    
    <div class="clear"></div>
    
    <div class="label">Fecha</div>
    <input type="text" name="txt_fecha" class="txt" value = "<?php echo date('Ymd')?>"> &nbsp;: Si <strong>Proceso</strong> = F ... AAAAMMDD
    
    <div class="clear"></div>
    
    <div class="label">Horario</div>
    <input type="text" name="txt_horario" class="txt" value = "D"> &nbsp;: Si <strong>Proceso</strong> = H ... <strong>B</strong>: 11:00 - <strong>C</strong>: 15:00 - <strong>D</strong>: 19:00
    
    <div class="clear"></div>
    
    <div class="label">Referencia</div>
    <input type="text" name="txt_referencia" class="txt" value = "<?php echo @$var_referencia; ?>">
    
    <div class="clear"></div>
    <input type="submit" value="Generar" style="float:right; cursor:pointer; margin-right: 30px">
</form>    



<?php


/*
if(@$export){
$downloadfile="string.txt";
header("Content-disposition: attachment; filename=$downloadfile");
header("Content-Type: application/force-download");
header("Content-Transfer-Encoding: binary");
header("Content-Length: ".strlen($data));
header("Pragma: no-cache");
header("Expires: 0");
echo $data;
}*/
?>
