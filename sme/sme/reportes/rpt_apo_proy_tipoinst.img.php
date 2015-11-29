<?php
require_once ("../../lib/jpgraph-3.0.6/src/jpgraph.php");
require_once ('../../lib/jpgraph-3.0.6/src/jpgraph_bar.php');
require_once ('../../lib/jpgraph-3.0.6/src/jpgraph_line.php');

include ("../../includes/validauser.inc.php");
?>
<?php

require (constant("PATH_CLASS") . "BLReportes.class.php");
$cboEstado = $objFunc->__Request('cboEstado');
$idConcurso = $objFunc->__Request('idConcurso');
$objRep = new BLReportes();
$ret = $objRep->ListaAportesProy_TiposInst($idConcurso, $cboEstado);
$valMax = 0;
while ($row = mysqli_fetch_assoc($ret)) {
    if ($valMax > $row["aportefo"]) {
        $valMax = $row["aportefo"];
    }
    
    $data1y[] = $row["aportefe"] / 1000000;
    $data2y[] = $row["aporteotros"] / 1000000;
    $arrLabel[] = $row["tipoinst"];
}

// Create the graph. These two calls are always required
$graph = new Graph(750, 320, 'auto');
$graph->SetScale("textlin");
$graph->SetY2OrderBack(false);
$graph->SetMargin(50, 50, 40, 40);

/* $graph->yaxis->SetTickPositions(array(0,50,100,150,200,250,300,350)); */

$graph->ygrid->SetFill(false);
$graph->yaxis->HideLine(false);
$graph->yaxis->HideTicks(false, false);
// Setup month as labels on the X-axis
$graph->xaxis->SetTickLabels($arrLabel);

// Create the bar plots
$b3plot = new BarPlot($data1y);
$b4plot = new BarPlot($data2y);

// Create the grouped bar plot
$gbbplot = new AccBarPlot(array(
    $b3plot,
    $b4plot
));
$gbplot = new GroupBarPlot(array(
    $gbbplot
));
// ...and add it to the graPH
$graph->Add($gbplot);

$b3plot->SetColor("#FE9A2E");
$b3plot->SetFillColor("#FE9A2E");
$b3plot->SetLegend("Aporte Fondoempleo");

$b4plot->SetColor("#2EFE2E");
$b4plot->SetFillColor("#2EFE2E");
$b4plot->SetLegend("Aporte Otros");

$b3plot->SetValuePos('top');
$b3plot->value->Show();

$b4plot->SetValuePos('top');
$b4plot->value->Show();

$graph->legend->SetFrameWeight(1);
$graph->legend->SetColumns(2);
$graph->legend->SetColor('#4E4E4E', '#00A78A');
$band = new PlotBand(VERTICAL, BAND_RDIAG, 11, "max", 'khaki4');
$band->ShowFrame(true);
$band->SetOrder(DEPTH_BACK);
$graph->Add($band);

// $graph->img->SetImgFormat('png');

$graph->title->Set("Aportes por Tipo de Institución");

// Display the graph
$graph->Stroke();

?>


