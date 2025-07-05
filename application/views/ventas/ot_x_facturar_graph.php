<?php
session_start();
require_once "../../libreria/jpgraph/jpgraph.php";
require_once "../../libreria/jpgraph/jpgraph_bar.php";
$monto_dolares = unserialize($_SESSION['monto_dolares']);
$monto_soles   = unserialize($_SESSION['monto_soles']);
if($_GET['tipo']=="D"){
	$grafico = new Graph(550, 400, "auto");
	$grafico->SetScale("textlin");
	$grafico->title->Set("Por facturar en dolares");
	$grafico->xaxis->title->Set("Quincena");
	$grafico->yaxis->title->Set("Monto $/.");
	$barplot1 = new BarPlot($monto_dolares);
	$barplot1->SetColor("red");
	$barplot1->SetFillColor("red");
	$grafico->Add($barplot1);
	$grafico->Stroke();
}
elseif($_GET['tipo']=="S"){
	$grafico2 = new Graph(550, 400, "auto");
	$grafico2->SetScale("textlin");
	$grafico2->title->Set("Por facturar en Soles");
	$grafico2->xaxis->title->Set("Quincena");
	$grafico2->yaxis->title->Set("Monto S/.");
	$barplot1 = new BarPlot($monto_soles);
	$barplot1->SetColor("red");
	$barplot1->SetFillColor("red");
	$grafico2->Add($barplot1);
	$grafico2->Stroke();
}
?>