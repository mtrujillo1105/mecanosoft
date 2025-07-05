<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title><?php echo titulo;?></title>
    <META HTTP-EQUIV="Refresh" content="300"> 
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="Content-Language" content="es">     
    <link rel="stylesheet" href="<?php echo css;?>estilos.css" type="text/css">
</head>
<body>
<div class="container">
    <div class="case_header">ORDENES DE TRABAJO</div>
    <div class="case_search">
        <form method="post" id="frmBusqueda">
            <table width="98%" cellspacing="0" cellpadding="3" border="0" class="fuente8">
                <tr>
                    <td align='left'>Campo: <?php echo $selcampos;?></td>
                    <td align='left'>
                        Valor: <input id="valor" class="cajaMedia" type="text" maxlength="100" value="" name="valor">
                    </td>
                    <td></td>
                    <td>
                        <div class="case_botones">
                            <ul class="lista_botones"><li id="buscar">Buscar</li></ul>   
                        </div>                           
                    </td>
                </tr>
            </table>
        </form>            
    </div>
    <div class="case_registro">N de proveedores encontrados:&nbsp;105</div>        
    <div class="case_header">RELACIÓN DE ORDENES DE TRABAJO</div>  
    <div>
        <table width="100%" cellspacing="0" cellpadding="3" border="0">
            <THEAD>
                <tr class="cabeceraTabla">
                    <td width="5%">ITEM</td>
                    <td width="13%">O.T.</td>
                    <td width="7%">FECHA<br>APERTURA</td>
                    <td width="20%">NOMBRE O RAZ&Oacute;N SOCIAL </td>                    
                    <td width="26%">DESCRIPCION</td>
                    <td width="9%">PESO<BR>(KG)</td>
                    <td width="9%">IMPORTE<BR>S/.</td>
                    <td width="5%">FECHA<BR>ENTREGA</td>
                    <td width="2%">&nbsp;</td>
                    <td width="2%">&nbsp;</td>
                    <td width="2%">&nbsp;</td>
                </tr>
            </THEAD>    
            <tbody>
            <?php
            if(count($lista)>0){
                foreach($lista as $indice=>$valor)
                {
                    $class = $indice%2==0?'itemParTabla':'itemImparTabla';
                    ?>
                    <tr class="<?php echo $class." itemTabla";?>" id="<?php echo $valor->codigo;?>">
                        <td class=""><div align="center"><?php echo $indice + 1;?></div></td>
                        <td><div align="left"><?php echo $valor->numero;?></div></td>
                        <td><div align="center"><?php echo $valor->fecha;?></div></td>
                        <td><div align="center"><?php echo $valor->rsocial;?></div></td>
                        <td><div align="center"><?php echo $valor->descripcion;?></div></td>
                        <td><div align="center"><?php echo $valor->peso;?></div></td>
                        <td><div align="center"><?php echo $valor->importe;?></div></td>
                        <td><div align="center"><?php echo $valor->fechaentrega;?></div></td>
                        <td><div align="center"><a href='#'><img src='<?php echo img;?>modificar.png' width='16' height='16' border='0' title='Modificar'></a></div></td>
                    </tr>
                    <?php
                }
            }
            else{
            ?>
                    <tr><td colspan="8" class="mensaje">No hay ning&uacute;n registro que cumpla con los criterios de b&uacute;squeda</td></tr>
            <?php
            }
            ?>
            </tbody>
            </table>
    </div>                
</div>
</body>
</html>