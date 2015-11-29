<?php
require_once ("../../lib/jpgraph-3.0.6/src/jpgraph.php");
require_once ('../../lib/jpgraph-3.0.6/src/jpgraph_bar.php');
include ("../../includes/validauser.inc.php");
?>
<?php

require (constant("PATH_CLASS") . "BLReportes.class.php");

$cboEstado = $objFunc->__Request('cboEstado');
$idConcurso = $objFunc->__Request('idConcurso');
$objRep = new BLReportes();
$ret = $objRep->ListaNroProy_TiposInst($idConcurso, $cboEstado);
$valMax = 0;
while ($row = mysqli_fetch_assoc($ret)) {
    if ($valMax < $row["cantidad"]) {
        $valMax = $row["cantidad"];
    }
    
    $arrData[] = $row["cantidad"];
    $arrLabel[] = $row["tipoinst"];
}
// Create the graph. These two calls are always required
$graph = new Graph(650, 280, 'auto');
$graph->SetScale("textlin");

$graph->SetBox(false);

$graph->ygrid->SetColor('gray');
$graph->ygrid->SetFill(false);
$graph->xaxis->SetTickLabels($arrLabel);
$graph->yaxis->HideLine(false);
$graph->yaxis->HideTicks(false, false);

// Create the bar plots

$b1plot = new BarPlot($arrData);
// ...and add it to the graPH
$graph->Add($b1plot);

$b1plot->SetColor("black"); // Color de Borde
$b1plot->SetValuePos('top');
$b1plot->value->SetFormat('%d');
$b1plot->value->SetFont(FF_FONT1, FS_BOLD);
$b1plot->value->Show();
$b1plot->SetFillGradient("#2A1F55", "white", GRAD_LEFT_REFLECTION);
// $b1plot->SetFillColor('#2A1F55');
$b1plot->SetWidth(30);
$b1plot->SetYBase($valMax + 4); // Tamaño de Alto de las Barras
$graph->title->Set("Numero de Proyectos x Tipo de Instituciones");

// Display the graph

$graph->Stroke();

?>



