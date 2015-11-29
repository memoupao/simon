<?php // content="text/plain; charset=utf-8"

include '../../../includes/constantes.inc.php';
// include("../../../includes/validauser.inc.php");

/* require_once '/'.constant('FOLDER').'/lib/jpgraph3.5/jpgraph.php';
require_once '/'.constant('FOLDER').'/lib/jpgraph3.5/jpgraph_bar.php'; */

require_once '../../../lib/jpgraph3.5/jpgraph.php';
require_once '../../../lib/jpgraph3.5/jpgraph_bar.php';

if (isset($_GET['x']) && !empty($_GET['x']) && isset($_GET['y']) && !empty($_GET['y'])) {

	$datay = explode('@',$_GET['y']);
	$datax = explode('@',$_GET['x']);
	
} else {
	exit;
}

$iWidth = 600;
$iHeight = 400;
if (isset($_GET['w']) && isset($_GET['h']) ) {
	$iWidth = (int)$_GET['w'];
	$iHeight = (int)$_GET['h'];
}


// Create the graph. These two calls are always required
$graph = new Graph($iWidth,$iHeight,'auto');
$graph->SetScale("textlin");

//$theme_class="DefaultTheme";
//$graph->SetTheme(new $theme_class());

// set major and minor tick positions manually
$graph->yaxis->SetTickPositions(array(0,30,60,90,120,150), array(15,45,75,105,135));
$graph->SetBox(false);

//$graph->ygrid->SetColor('gray');
$graph->ygrid->SetFill(false);
$graph->xaxis->SetTickLabels($datax);
$graph->yaxis->HideLine(false);
$graph->yaxis->HideTicks(false,false);
//$graph->xaxis->SetLabelAngle(30);

// Create the bar plots
$b1plot = new BarPlot($datay);

// ...and add it to the graPH
$graph->Add($b1plot);


$b1plot->SetColor("white");
$b1plot->SetFillGradient("navy","lightsteelblue",GRAD_MIDVER);

if (isset($_GET['title'])) {
	$graph->title->Set($_GET['title']);
}

// Display the graph
$graph->Stroke();
