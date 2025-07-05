<?php
require_once "../../libreria/conexion.php";
$hoy = date("d/m/Y",time());
$cantidad = 0;
if($_POST['fInicio']!="" && $_POST['fFin']!=""){
	$entidad = "01";
	$tipOt   = "'08','10','12'";
	$sql = "
			select
			Ot.CodOt as codigo,
			Ot.NroOt as numero,  
			year(Ot.FecOt) as anio,
			month(Ot.FecOt) as mes,
			(select cli.RazCli from clientes as cli where cli.CodCli=Ot.CodCli and cli.codEnt=Ot.codEnt) as cliente,
			Ot.DirOt as site,
			substring(Ot.DesOt,1,50) as detalleas,
			Ot.FecOt,
			Ot.OrdOt,
			case when (Ot.EstOt=2) then 'S' else 'D' end as moneda,	
			case when (Ot.EstOt=3) then ot.ImpOt else null end as dolares,
			case when (Ot.EstOt=2) then ot.ImpOt else null end as soles,
			case when (Ot.EstOt=3) then detalles.importe else null end as factDolares,
			case when (Ot.EstOt=2) then detalles.importe else null end as factSoles,
			detalles.igv as factIgv,
			detalles.total as factTotal,
			detalles.aniofact as aniofac,
			detalles.mesfact as mesfact,
			case when (ot.EstOt=3) then (ot.ImpOt-(case when (detalles.importe is null) then 0 else detalles.importe end)) else null end as saldoDolares,
			case when (ot.EstOt=2) then (ot.ImpOt-(case when (detalles.importe is null) then 0 else detalles.importe end)) else null end as saldoSoles,
			  case when (Ot.EstOt=2) 
				  then 
					  '' 
				  else 
					(
						select 
						x.valor_2 
						from Tabla_M_Detalle as x 
						where x.codent='".$entidad."' 
						and cod_tabla=(
							replicate('0',(2 -len(month(Ot.FecOt))))+CONVERT(varchar(2),month(Ot.FecOt))+substring(convert(varchar(4),year(Ot.FecOt)),3,2)
						)
						and cod_argumento = day(Ot.FecOt)
					) 
				  end
			  as tc
			from ot
			left join (
			  SELECT 
			  doc.codot as codigo,
			  year(doc.fecdcto) as aniofact,
			  MONTH(doc.fecdcto) as mesfact,
			  SUM(doc.subtotal) as importe,
			  sum(doc.igv) as igv,
			  sum(doc.total) as total
			  FROM DOCUMENTOS as doc
			  inner join ot as ot ON (ot.codot=doc.codot and ot.codent=doc.codent)
			  WHERE doc.CODENT='".$entidad."' 
			  AND doc.TIPDCTO='FV'
			  and ot.tipot in (".$tipOt.")
			  group by doc.codot,year(doc.fecdcto),MONTH(doc.fecdcto)
			) as detalles on (detalles.codigo=ot.codot)
			where ot.codent='".$entidad."'
			and ot.tipot in (".$tipOt.")
			and ot.fecOt between '".$_POST['fInicio']."' and '".$_POST['fFin']."'
			order by Ot.CodOt
	";
	$result = mssql_query($sql);
	$fila="";
	$codigo = "";
	$nroot  = "";
	$anio   = "";
	$mes    = "";
	$cliente     = "";
	$site        = "";
	$descripcion = "";
	$fecOt       = "";
	$oc          = "";
	$moneda      = "";
	$dolares     = "";
	$soles       = "";
	$factDol     = "";
	$factSol     = "";
	$facIgv      = "";
	$totFac      = "";
	$anioFac     = "";
	$mesFac      = "";
	$saldoDol    = "";
	$saldoSol    = "";
	$codigo_ant  = "";
	$tc          = "";
	$item   = 1;
	while($row=mssql_fetch_array($result)){
		$codigo = $row[0];
		$nroot  = $row[1];
		$anio   = $row[2];
		$mes    = $row[3];
		$cliente     = $row[4];
		$site        = $row[5];
		$descripcion = $row[6];
		$fecOt       = $row[7];
		$oc          = $row[8];
		$moneda      = $row[9];
		$dolares     = $row[10];
		$soles       = $row[11];
		$factDol     = $row[12];
		$factSol     = $row[13];
		$facIgv      = $row[14];
		$totFac      = $row[15];
		$anioFac     = $row[16];
		$mesFac      = $row[17];
		$saldoDol    = $row[18];
		$saldoSol    = $row[19];
		$tc          = $row[20];
		if($codigo==$codigo_ant){
			$dolares = "";
			$soles   = "";		
			//$factSol = $factSol_ant + $factSol;
			//$factDol = $factDol_ant + $factDol;
			$saldoDol = $dolares - $factDol;
			$saldoSol = $soles - $factSol;	
		}
		$fila.="<tr>";
		$fila.="<td>".$codigo."</td>";
		$fila.="<td>".$nroot."</td>";
		$fila.="<td>".$anio."</td>";
		$fila.="<td>".$mes."</td>";
		$fila.="<td>".$cliente."</td>";
		$fila.="<td>".$site."</td>";
		$fila.="<td>".$descripcion."</td>";
		$fila.="<td>".$fecOt."</td>";
		$fila.="<td>".$oc."</td>";
		$fila.="<td>".$moneda."</td>";
		$fila.="<td>".($dolares==""?"":number_format($dolares,2,",",""))."</td>";
		$fila.="<td>".($soles==""?"":number_format($soles,2,",",""))."</td>";
		$fila.="<td>".($row[12]==""?"":number_format($row[12],2,",",""))."</td>";
		$fila.="<td>".($row[13]==""?"":number_format($row[13],2,",",""))."</td>";
		$fila.="<td>".($facIgv==""?"":number_format($facIgv,2,",",""))."</td>";
		$fila.="<td>".($totFac==""?"":number_format($totFac,2,",",""))."</td>";
		$fila.="<td>".$anioFac."</td>";
		$fila.="<td>".$mesFac."</td>";
		$fila.="<td>".($saldoDol==""?"0":number_format($saldoDol,2,",",""))."</td>";
		$fila.="<td>".($saldoSol==""?"0":number_format($saldoSol,2,",",""))."</td>";
		$fila.="<td>".($tc==""?"":number_format($tc,2,",",""))."</td>";
		$fila.="</tr>";	
		$codigo_ant = $codigo;
		$factDol_ant = $factDol;
		$factSol_ant = $factSol;
		$item++;
	}	
	$cantidad = mssql_num_rows($result);
}
else{
	$fila = "";
}
?>
<html>
<head>
<!-- Calendario -->	
<link rel="stylesheet" href="<?php echo css;?>estilos.css" type="text/css">
<link rel="stylesheet" href="<?php echo css;?>nav.css" type="text/css">
<link rel="stylesheet" href="<?php echo css;?>theme.css" type="text/css">
<link rel="stylesheet" href="<?php echo css;?>calendario/calendar-win2k-2.css" type="text/css" media="all" title="win2k-cold-1"/>	
<!-- Calendario -->
<script type="text/javascript" src="<?php echo js;?>calendario/calendar.js"></script>
<script type="text/javascript" src="<?php echo js;?>calendario/calendar-es.js"></script>
<script type="text/javascript" src="<?php echo js;?>calendario/calendar-setup.js"></script>
<script type="text/javascript" src="<?php echo js;?>jquery.js"></script>
<script type="text/javascript" src="<?php echo js;?>personal/asistencia.js"></script>
</head>
<body>
<form method="post">
<div><h1>Reporte de Ots facturadas</h1></div>
<div>
	<span>F.Inicio</span>
	<span style="width:500px;border:0px solid #000;">
		<input  name="fInicio" id="fInicio" title="Fecha Inicio" value="01/01/2009" type="text" class="cajaPequena" readonly="readonly">									
		<img src="img/calendario.png" name="Calendario1" id="Calendario1" width="16" height="16" border="0" id="Image1" onMouseOver="this.style.cursor='pointer'" title="Calendario">
		<script type="text/javascript">
				Calendar.setup({
						inputField     :    "fInicio",      // id del campo de texto
						ifFormat       :    "%d/%m/%Y",       // formato de la fecha, cuando se escriba en el campo de texto
						button         :    "Calendario1"   // el id del botón que lanzará el calendario
				});
		</script>		
	</span>
	<span>F.Fin</span>
	<span>
		<input  name="fFin" id="fFin" title="Fecha Inicio" value="<?php echo $hoy;?>" type="text" class="cajaPequena" readonly="readonly">									
		<img src="img/calendario.png" name="Calendario2" id="Calendario2" width="16" height="16" border="0" id="Image1" onMouseOver="this.style.cursor='pointer'" title="Calendario">
		<script type="text/javascript">
				Calendar.setup({
						inputField     :    "fFin",      // id del campo de texto
						ifFormat       :    "%d/%m/%Y",       // formato de la fecha, cuando se escriba en el campo de texto
						button         :    "Calendario2"   // el id del botón que lanzará el calendario
				});
		</script>		
	</span>	
	<span><input type="submit" value="Calcular"></span>
</div>
<div><?php echo $cantidad;?></div>
<div style="margin-top:5px;">
	<table border='1' width='95%'>
		<tr>
			<td>CODIGO</td>
			<td>NRO OT</td>
			<td>ANIO</td>
			<td>MES</td>
			<td>CLIENTE</td>
			<td>SITE</td>
			<td>DESCRIPCION</td>
			<td>FEC OT</td>
			<td>O.COMPRA</td>
			<td>MONEDA</td>
			<td>DOLARES</td>
			<td>SOLES</td>
			<td>FACT. $.</td>
			<td>FACT S/.</td>
			<td>IGV FACT.</td>
			<td>TOT- FACT-</td>
			<td>ANIO FACT.</td>
			<td>MES FACT.</td>
			<td>SALDO $</td>
			<td>SALDO S/.</td>
			<td>T.C.</td>
		</tr>
		<?php echo $fila;?>
	</table>
</div>	
</form>
</body>
</html>