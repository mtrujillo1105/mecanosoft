<meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 



<?php
require_once "conexion-odbc.php";

$ene1="01/01/2011";
$ene2="01/31/2011";

$sql1="SELECT 
    SUM(gcantidad), 
    gnumguia, 
    gserguia,Fecemi  
    FROM requis 
    WHERE tipo='R' 
    AND user='X'
    AND codot <> '0001999' 
    AND fecemi between CTOD('".$ene1."') 
    AND CTOD('".$ene2."') 
    GROUP BY gnumguia, gserguia,Fecemi";
$result1 = odbc_exec($cid,$sql1);
$j1  = 0;
$c1  = 0;
while(odbc_fetch_row($result1)){
	$cantidad1 	= odbc_result($result1,1); 
	$serie1 	= odbc_result($result1,3);
	$numero1 	= odbc_result($result1,2);
	$sql2     	=  "SELECT 
                            SUM(cantidad),
                            Fecha 
                            FROM kardex 
                            WHERE serreq='".$serie1."' 
                            AND numreq='".$numero1."' 
                            AND fecha between ctod('".$ene1."') 
                            AND ctod('".$ene2."')"; 
	$result2     	= odbc_exec($cid,$sql2);
	$k_cant1	= odbc_result($result2,1);
        $fecha          = odbc_result($result2,2);

	
	if($cantidad1==$k_cant1){	
	$j1++;
        $dias_aten = date("d",($fecha-$ene1));
        if($dias_aten <=1) $menos24++;
        if($dias_aten >1 && $dias_aten<2) $entre24_48++;
        if($dias_aten >= 2) $mas48++;
	}

 $c1++; 
}
if($c!=0){
    $valor1 = ($j1/$c1)*100;	
    $valor1 = number_format($valor1, 2, '.', '');
}
else{
    $valor1 = "--";
}





?>

<table border="0" cellpadding="0" cellspacing="0" width="1312" style="border-collapse:
 collapse;width:984pt">
	<colgroup>
		<col width="192" style="width: 144pt">
		<col width="80" span="14" style="width:60pt">
	</colgroup>
	<tr height="28" style="height:21.0pt">
		<td height="28" colspan="4" width="432" style="height: 21.0pt; width: 324pt; font-size: 16.0pt; font-weight: 700; color: black; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border: medium none; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		Gestión de los Pedidos en Logística Integral</td>
		<td width="80" style="width: 60pt; color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border: medium none; padding-left: 1px; padding-right: 1px; padding-top: 1px">&nbsp;</td>
		<td width="80" style="width: 60pt; color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border: medium none; padding-left: 1px; padding-right: 1px; padding-top: 1px">&nbsp;</td>
		<td width="80" style="width: 60pt; color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border: medium none; padding-left: 1px; padding-right: 1px; padding-top: 1px">&nbsp;</td>
		<td width="80" style="width: 60pt; color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border: medium none; padding-left: 1px; padding-right: 1px; padding-top: 1px">&nbsp;</td>
		<td width="80" style="width: 60pt; color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border: medium none; padding-left: 1px; padding-right: 1px; padding-top: 1px">&nbsp;</td>
		<td width="80" style="width: 60pt; color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border: medium none; padding-left: 1px; padding-right: 1px; padding-top: 1px">&nbsp;</td>
		<td width="80" style="width: 60pt; color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border: medium none; padding-left: 1px; padding-right: 1px; padding-top: 1px">&nbsp;</td>
		<td width="80" style="width: 60pt; color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border: medium none; padding-left: 1px; padding-right: 1px; padding-top: 1px">&nbsp;</td>
		<td width="80" style="width: 60pt; color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border: medium none; padding-left: 1px; padding-right: 1px; padding-top: 1px">&nbsp;</td>
		<td width="80" style="width: 60pt; color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border: medium none; padding-left: 1px; padding-right: 1px; padding-top: 1px">&nbsp;</td>
		<td width="80" style="width: 60pt; color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border: medium none; padding-left: 1px; padding-right: 1px; padding-top: 1px">&nbsp;</td>
	</tr>
	<tr height="29" style="height:21.75pt">
		<td height="29" colspan="2" style="height: 21.75pt; font-size: 16.0pt; font-weight: 700; color: black; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border: medium none; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		Enero a Diciembre 2011</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border: medium none; padding-left: 1px; padding-right: 1px; padding-top: 1px">&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border: medium none; padding-left: 1px; padding-right: 1px; padding-top: 1px">&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border: medium none; padding-left: 1px; padding-right: 1px; padding-top: 1px">&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border: medium none; padding-left: 1px; padding-right: 1px; padding-top: 1px">&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border: medium none; padding-left: 1px; padding-right: 1px; padding-top: 1px">&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border: medium none; padding-left: 1px; padding-right: 1px; padding-top: 1px">&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border: medium none; padding-left: 1px; padding-right: 1px; padding-top: 1px">&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border: medium none; padding-left: 1px; padding-right: 1px; padding-top: 1px">&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border: medium none; padding-left: 1px; padding-right: 1px; padding-top: 1px">&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border: medium none; padding-left: 1px; padding-right: 1px; padding-top: 1px">&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border: medium none; padding-left: 1px; padding-right: 1px; padding-top: 1px">&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border: medium none; padding-left: 1px; padding-right: 1px; padding-top: 1px">&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border: medium none; padding-left: 1px; padding-right: 1px; padding-top: 1px">&nbsp;</td>
	</tr>
	<tr height="41" style="height: 30.75pt">
		<td height="41" style="height: 30.75pt; text-align: center; color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; vertical-align: center; white-space: nowrap; border-left: 1.5pt solid windowtext; border-right: 1.0pt solid windowtext; border-top: 1.5pt solid windowtext; border-bottom: 1.5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		Concepto&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Mes</td>
		<td style="text-align: center; color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; vertical-align: center; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: 1.5pt solid windowtext; border-bottom: 1.5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		Enero</td>
		<td style="text-align: center; color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; vertical-align: center; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: 1.5pt solid windowtext; border-bottom: 1.5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		Febrero</td>
		<td style="text-align: center; color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; vertical-align: center; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: 1.5pt solid windowtext; border-bottom: 1.5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		Marzo</td>
		<td style="text-align: center; color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; vertical-align: center; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: 1.5pt solid windowtext; border-bottom: 1.5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		Abril</td>
		<td style="text-align: center; color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; vertical-align: center; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: 1.5pt solid windowtext; border-bottom: 1.5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		Mayo</td>
		<td style="text-align: center; color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; vertical-align: center; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: 1.5pt solid windowtext; border-bottom: 1.5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		Junio</td>
		<td style="text-align: center; color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; vertical-align: center; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: 1.5pt solid windowtext; border-bottom: 1.5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		Julio</td>
		<td style="text-align: center; color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; vertical-align: center; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: 1.5pt solid windowtext; border-bottom: 1.5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		Agosto</td>
		<td style="text-align: center; color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; vertical-align: center; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: 1.5pt solid windowtext; border-bottom: 1.5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		Septiembre</td>
		<td style="text-align: center; color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; vertical-align: center; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: 1.5pt solid windowtext; border-bottom: 1.5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		Octubre</td>
		<td style="text-align: center; color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; vertical-align: center; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: 1.5pt solid windowtext; border-bottom: 1.5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		Noviembre</td>
		<td style="text-align: center; color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; vertical-align: center; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: 1.5pt solid windowtext; border-bottom: 1.5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		Diciembre</td>
		<td style="text-align: center; color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; vertical-align: center; white-space: nowrap; border-left: medium none; border-right: 1.5pt solid windowtext; border-top: 1.5pt solid windowtext; border-bottom: 1.5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		Total</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: center; white-space: nowrap; border: medium none; padding-left: 1px; padding-right: 1px; padding-top: 1px">&nbsp;</td>
	</tr>
	
	
	
	
	<tr height="21" style="height:15.75pt">
		<td height="21" style="height: 15.75pt; color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: 1.5pt solid windowtext; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.5pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border: medium none; padding-left: 1px; padding-right: 1px; padding-top: 1px">&nbsp;</td>
	</tr>
	
	
	<tr height="20" style="height:15.0pt">
		<td height="20" style="height: 15.0pt; color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: 1.5pt solid windowtext; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		Requisiciones efectuadas</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		<?php echo $c1;?></td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		<?php echo $c2;?></td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		<?php echo $c3;?></td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		c4</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		c5</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		c6</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		c7</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		c8</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		c9</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		c10</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		c11</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		c12</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.5pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		<?php $total1=$c1+$c2+$c3; echo $total1;?></td>
                <td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border: medium none; padding-left: 1px; padding-right: 1px; padding-top: 1px">&nbsp;</td>
	</tr>
	
	
	
	<tr height="20" style="height:15.0pt">
		<td height="20" style="height: 15.0pt; color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: 1.5pt solid windowtext; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		Requisiciones atendidas</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		<?php echo $j1;?></td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		<?php echo $j2;?></td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		<?php echo $j3;?></td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.5pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		<?php $total2=$j+$j2+$j3; echo $total2;?></td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border: medium none; padding-left: 1px; padding-right: 1px; padding-top: 1px">&nbsp;</td>
	</tr>
	
	
	
	<tr height="20" style="height:15.0pt">
		<td height="20" style="height: 15.0pt; color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: 1.5pt solid windowtext; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		Indice de atención en %</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		<?php echo $valor1; echo "%";?></td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		<?php echo $valor2; echo "%";?></td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		<?php echo $valor3; echo "%";?></td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.5pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		<?php $total3=$valor+$valor2+$valor3; echo $total3;?></td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border: medium none; padding-left: 1px; padding-right: 1px; padding-top: 1px">&nbsp;</td>
	</tr>
	
	
	
	<tr height="20" style="height:15.0pt">
		<td height="20" style="height: 15.0pt; color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: 1.5pt solid windowtext; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.5pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border: medium none; padding-left: 1px; padding-right: 1px; padding-top: 1px">&nbsp;</td>
	</tr>
	
	
	
	
	<tr height="20" style="height:15.0pt">
		<td height="20" style="height: 15.0pt; color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: 1.5pt solid windowtext; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		Indices por período</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.5pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border: medium none; padding-left: 1px; padding-right: 1px; padding-top: 1px">&nbsp;</td>
	</tr>
	
	
	
	
	<tr height="20" style="height:15.0pt">
		<td height="20" style="height: 15.0pt; color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: 1.5pt solid windowtext; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		Atendidas en 24 hrs o menos</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		1dia</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.5pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border: medium none; padding-left: 1px; padding-right: 1px; padding-top: 1px">&nbsp;</td>
	</tr>
	
	
	
	
	<tr height="20" style="height:15.0pt">
		<td height="20" style="height: 15.0pt; color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: 1.5pt solid windowtext; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		Atendidas entre 24 y 48 hrs</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		1 a 2dias</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.5pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border: medium none; padding-left: 1px; padding-right: 1px; padding-top: 1px">&nbsp;</td>
	</tr>
	
	
	
	<tr height="20" style="height:15.0pt">
		<td height="20" style="height: 15.0pt; color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: 1.5pt solid windowtext; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		Atendidas en más de 48 hrs</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		2 dias a mas</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.5pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border: medium none; padding-left: 1px; padding-right: 1px; padding-top: 1px">&nbsp;</td>
	</tr>
	
	
	
	
	<tr height="20" style="height:15.0pt">
		<td height="20" style="height: 15.0pt; color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: 1.5pt solid windowtext; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.5pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border: medium none; padding-left: 1px; padding-right: 1px; padding-top: 1px">&nbsp;</td>
	</tr>
	
	
	
	
	<tr height="20" style="height:15.0pt">
		<td height="20" style="height: 15.0pt; font-weight: 700; color: black; font-size: 11.0pt; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: 1.5pt solid windowtext; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		Transporte</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.5pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border: medium none; padding-left: 1px; padding-right: 1px; padding-top: 1px">&nbsp;</td>
	</tr>
	
	
	
	<tr height="20" style="height:15.0pt">
		<td height="20" style="height: 15.0pt; color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: 1.5pt solid windowtext; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		Requisiciones efectuadas</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		solicitadas</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.5pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border: medium none; padding-left: 1px; padding-right: 1px; padding-top: 1px">&nbsp;</td>
	</tr>
	
	
	
	
	<tr height="20" style="height:15.0pt">
		<td height="20" style="height: 15.0pt; color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: 1.5pt solid windowtext; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		Requisiciones atendidas</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		ok</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.5pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border: medium none; padding-left: 1px; padding-right: 1px; padding-top: 1px">&nbsp;</td>
	</tr>
	
	
	

	<tr height="20" style="height:15.0pt">
		<td height="20" style="height: 15.0pt; color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: 1.5pt solid windowtext; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		Indice de atención en %</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		resto</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.5pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border: medium none; padding-left: 1px; padding-right: 1px; padding-top: 1px">&nbsp;</td>
	</tr>
	
	
	
	
	<tr height="20" style="height:15.0pt">
		<td height="20" style="height: 15.0pt; color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: 1.5pt solid windowtext; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.5pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border: medium none; padding-left: 1px; padding-right: 1px; padding-top: 1px">&nbsp;</td>
	</tr>
	
	
	
	
	<tr height="20" style="height:15.0pt">
		<td height="20" style="height: 15.0pt; color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: 1.5pt solid windowtext; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		Indices por período</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.5pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border: medium none; padding-left: 1px; padding-right: 1px; padding-top: 1px">&nbsp;</td>
	</tr>
	
	
	
	<tr height="20" style="height:15.0pt">
		<td height="20" style="height: 15.0pt; color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: 1.5pt solid windowtext; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		Atendidas en 24 hrs o menos</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.5pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border: medium none; padding-left: 1px; padding-right: 1px; padding-top: 1px">&nbsp;</td>
	</tr>
	
	
	
	
	<tr height="20" style="height:15.0pt">
		<td height="20" style="height: 15.0pt; color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: 1.5pt solid windowtext; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		Atendidas entre 24 y 48 hrs</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.5pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border: medium none; padding-left: 1px; padding-right: 1px; padding-top: 1px">&nbsp;</td>
	</tr>
	
	
	
	
	
	<tr height="20" style="height:15.0pt">
		<td height="20" style="height: 15.0pt; color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: 1.5pt solid windowtext; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		Atendidas en más de 48 hrs</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.5pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border: medium none; padding-left: 1px; padding-right: 1px; padding-top: 1px">&nbsp;</td>
	</tr>
	
	
	
	
	
	<tr height="20" style="height:15.0pt">
		<td height="20" style="height: 15.0pt; color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: 1.5pt solid windowtext; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.5pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border: medium none; padding-left: 1px; padding-right: 1px; padding-top: 1px">&nbsp;</td>
	</tr>
	
	
	
	
	
	<tr height="20" style="height:15.0pt">
		<td height="20" style="height: 15.0pt; font-weight: 700; color: black; font-size: 11.0pt; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: 1.5pt solid windowtext; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		Demás requisiciones</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.5pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border: medium none; padding-left: 1px; padding-right: 1px; padding-top: 1px">&nbsp;</td>
	</tr>
	<tr height="20" style="height:15.0pt">
		<td height="20" style="height: 15.0pt; color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: 1.5pt solid windowtext; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		Requisiciones efectuadas</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.5pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border: medium none; padding-left: 1px; padding-right: 1px; padding-top: 1px">&nbsp;</td>
	</tr>
	<tr height="20" style="height:15.0pt">
		<td height="20" style="height: 15.0pt; color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: 1.5pt solid windowtext; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		Requisiciones atendidas</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.5pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border: medium none; padding-left: 1px; padding-right: 1px; padding-top: 1px">&nbsp;</td>
	</tr>
	<tr height="20" style="height:15.0pt">
		<td height="20" style="height: 15.0pt; color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: 1.5pt solid windowtext; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		Indice de atención en %</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.5pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border: medium none; padding-left: 1px; padding-right: 1px; padding-top: 1px">&nbsp;</td>
	</tr>
	<tr height="20" style="height:15.0pt">
		<td height="20" style="height: 15.0pt; color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: 1.5pt solid windowtext; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.5pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border: medium none; padding-left: 1px; padding-right: 1px; padding-top: 1px">&nbsp;</td>
	</tr>
	<tr height="20" style="height:15.0pt">
		<td height="20" style="height: 15.0pt; color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: 1.5pt solid windowtext; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		Indices por período</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.5pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border: medium none; padding-left: 1px; padding-right: 1px; padding-top: 1px">&nbsp;</td>
	</tr>
	<tr height="20" style="height:15.0pt">
		<td height="20" style="height: 15.0pt; color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: 1.5pt solid windowtext; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		Atendidas en 24 hrs o menos</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.5pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border: medium none; padding-left: 1px; padding-right: 1px; padding-top: 1px">&nbsp;</td>
	</tr>
	<tr height="20" style="height:15.0pt">
		<td height="20" style="height: 15.0pt; color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: 1.5pt solid windowtext; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		Atendidas entre 24 y 48 hrs</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.5pt solid windowtext; border-top: medium none; border-bottom: .5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border: medium none; padding-left: 1px; padding-right: 1px; padding-top: 1px">&nbsp;</td>
	</tr>
	<tr height="21" style="height:15.75pt">
		<td height="21" style="height: 15.75pt; color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: 1.5pt solid windowtext; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: 1.5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		Atendidas en más de 48 hrs</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: 1.5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: 1.5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: 1.5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: 1.5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: 1.5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: 1.5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: 1.5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: 1.5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: 1.5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: 1.5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: 1.5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: 1.5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border-left: medium none; border-right: 1.5pt solid windowtext; border-top: medium none; border-bottom: 1.5pt solid windowtext; padding-left: 1px; padding-right: 1px; padding-top: 1px">
		&nbsp;</td>
		<td style="color: black; font-size: 11.0pt; font-weight: 400; font-style: normal; text-decoration: none; font-family: Calibri, sans-serif; text-align: general; vertical-align: bottom; white-space: nowrap; border: medium none; padding-left: 1px; padding-right: 1px; padding-top: 1px">&nbsp;</td>
	</tr>
</table>


