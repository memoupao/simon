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
$ret = $objRep->ListaNroProy_SecInst($idConcurso, $cboEstado);
$valMax = 0;

$color_bar = array(
    '#cc1111',
    '#11cccc',
    '#1111cc',
    '#80FF00',
    '#FE9A2E',
    '#088A08',
    '#00FF80',
    '#FF0080',
    '#F7BE81',
    '#4B088A',
    '#6E6E6E'
);

$arrBlob = NULL;
$datay = NULL;

$i = 2;
while ($row = mysqli_fetch_array($ret)) {
    
    $datay = NULL;
    $suma = 0;
    for ($x = 2; $x < mysqli_num_fields($ret); $x ++) {
        /* if($row[$x]>0){ */
        $datay[] = $row[$x];
        /* } */
        $suma += $row[$x];
    }
    
    $arrBlobx = new BarPlot($datay);
    
    $arrBlobx->SetColor("white");
    $arrBlobx->SetFillColor($color_bar[$i]);
    $arrBlobx->SetLegend($row[1]);
    
    $arrBlobx->SetValuePos('top');
    $arrBlobx->value->Show();
    $arrBlobx->value->SetFormat('%d');
    
    $arrBlobx->value->SetFormatCallback('validarceros');
    
    $arrBlob[] = $arrBlobx;
    $i ++;
}

$ti = $objRep->ListaTip_Inst();

while ($row = mysqli_fetch_array($ti)) {
    $datati[] = $row[1];
}

function validarceros($aLabel)
{
    if ($aLabel == 0) {
        return "";
    } else {
        return $aLabel;
    }
}
/*
 * echo("<pre>"); print_r($arrBlob); echo("</pre>"); exit();
 */

// Create the graph. These two calls are always required

// Create the graph. These two calls are always required
$graph = new Graph(600, 300, 'auto');
$graph->SetScale("textlin");

$graph->SetBox(false);

$graph->ygrid->SetFill(false);

$graph->xaxis->SetTickLabels($datati);
$graph->xaxis->SetFont(FF_FONT1, FS_BOLD, 6);

$graph->yaxis->HideLine(false);
$graph->yaxis->HideTicks(false, false);

// Create the grouped bar plot
$gbplot = new GroupBarPlot($arrBlob);

// ...and add it to the graPH
$graph->Add($gbplot);

$graph->legend->Pos(0.05, 0.09);
$graph->legend->SetFont(FF_FONT1, FS_NORMAL, 5);

$graph->title->Set("Proyectos según sector y tipo de Institución");
$graph->title->SetFont(FF_FONT1, FS_BOLD, 8);
// Display the graph
$graph->Stroke();

?>



