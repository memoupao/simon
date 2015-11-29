<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauser.inc.php"); ?>
<?php

require_once (constant("PATH_CLASS") . "BLProyecto.class.php");
require_once (constant("PATH_CLASS") . "BLTablasAux.class.php");

$objTablas = new BLTablasAux();
$idProy = $objFunc->__Request('idProy');
$idConcurso = $objFunc->__Request('cboConcurso');
$cboEstado = $objFunc->__Request('cboEstado');

$objProy = new BLProyecto();
$ls_version = $objProy->MaxVersion($idProy);
$Proy = $objProy->GetProyecto($idProy, $ls_version);
$ls_filter = "";
?>

<?php  if($idProy=='') { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/tempReports.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Monitor Externo</title>
<!-- InstanceEndEditable -->
<script language="javascript" type="text/javascript"
	src="../../jquery.ui-1.5.2/jquery-1.2.6.js"></script>
<link href="../../css/reportes.css" rel="stylesheet" type="text/css"
	media="all" />
<!-- InstanceBeginEditable name="head" -->
<!-- InstanceEndEditable -->
</head>

<body>
	<form id="frmMain" name="frmMain" method="post" action="#">
<?php } ?>


<div id="divBodyAjax" class="TableGrid">
			<!-- InstanceBeginEditable name="BodyAjax" -->
			<div class="TableGrid" align="center">
<?php
require (constant("PATH_CLASS") . "BLReportes.class.php");
$objRep = new BLReportes();
$ret = $objRep->ListaNroProy_TiposInst($idConcurso, $cboEstado);
$aReportData = array();
$SumaTotal = 0;
$SumaFE = 0;
$SumaOtros = 0;
$SumaPreTotal = 0;
$aSerie1Arr = array();
$aSerie2Arr = array();
$aSerie2RealArr = array();
$aLabelsArr = array();

while ($row = mysqli_fetch_assoc($ret)) {
    $totalp = $row["FE"] + $row["otros"];
    $aReportRow = array(
        'tipoinst' => $row['tipoinst'],
        'cantidad' => $row['cantidad'],
        'FE' => $row['FE'],
        'otros' => $row['otros'],
        'total' => $totalp,
        'cod_tipo_inst' => $row['t01_tipo_inst']
    );
    
    $SumaTotal += $aReportRow["cantidad"];
    $SumaFE += $aReportRow["FE"];
    $SumaOtros += $aReportRow["otros"];
    $SumaPreTotal += $aReportRow["total"];
    
    $aSerie1Arr[] = $aReportRow["FE"];
    $aSerie2Arr[] = $aReportRow["otros"] - $aReportRow["FE"];
    $aSerie2RealArr[] = $aReportRow["otros"];
    $aLabelsArr[] = "'" . strtoupper($aReportRow["tipoinst"]) . "'";
    
    $aReportData[] = $aReportRow;
} // while

if (count($aReportData) > 0) {
    ?>
		<div align="center">
					<div id="chart1" style="width: 700px; height: 600px" />
				</div>
				<br />
				<br />
	<?php
} // if

?>
	<script src="../../js/jquery.js" type="text/javascript"></script>
				<script src="../../js/raphael-min.js" type="text/javascript"></script>
				<script src="../../js/elycharts.js" type="text/javascript"></script>
				<script type="text/javascript">
	
		function formatToolTip(pToolTipArr) {
			var aToolTipArr = [];
			for(var i=0; i<pToolTipArr.length; i++) aToolTipArr[i] = "'" + pToolTipArr[i].toFixed(2) + "'";
			return aToolTipArr;
		}

		$.elycharts.templates['line_basic_6'] = {
			type: "line",
			margins: [10, 40, 80, 80],
			defaultSeries: {
				highlight: {
					newProps: { r: 8, opacity: 1 },
					overlayProps: { fill: "white", opacity: 0.2 }
				}
	  		},
	  		series: {
				serie1: { color: "90-#003000-#009000", tooltip: { frameProps: { stroke: "green" } } },
				serie2: { color: "90-#000060-#0000B0", tooltip: { frameProps: { stroke: "blue" } } }
			},
			defaultAxis: {
				labels: true
			},
			axis: {
				x: {
					labelsRotate: 90,
					labelsProps: { font: "10px Verdana" }
	    		}
	  		},
			features: {
				grid: {
					draw: true,
					forceBorder: true,
					ny: 5
				}
			},	
			barMargins: 10
		};

		$(function() {
			var aToolTp1 = formatToolTip(<?php echo '[' . implode(',', $aSerie1Arr) . ']'; ?>);
			var aToolTp2 = formatToolTip(<?php echo '[' . implode(',', $aSerie2RealArr) . ']'; ?>);
			$("#chart1").chart({
				template: "line_basic_6",
				values: {
					serie1: <?php echo '[' . implode(',', $aSerie1Arr) . ']'; ?>,
					serie2: <?php echo '[' . implode(',', $aSerie2Arr) . ']'; ?>
				},
				tooltips: {
					serie1: aToolTp1,
					serie2: aToolTp2
		  		},
		  		legend: {
		  			serie1: "Fondoempleo",
		  			serie2: "Otros"
		  		},
				labels: <?php echo '[' . implode(',', $aLabelsArr) . ']'; ?>,
				defaultSeries: {
					type: "bar",
					stacked: true
				},
				axis: { r: { max: 100, suffix: "%" } }
			});
		});
	</script>

				<table width="640" border="0" cellspacing="0" cellpadding="0">
					<thead>
						<tr>
							<td width="259" align="center"><strong>TIPO INSTITUCIONES</strong></td>
							<td width="79" align="center"><strong>NUMERO PROYECTOS</strong></td>
							<td width="99" align="center"><strong>FONDOEMPLEO (S/.)</strong></td>
							<td width="99" align="center"><strong>OTROS (S/.)</strong></td>
							<td width="99" align="center"><strong>TOTAL (S/.)</strong></td>
						</tr>
					</thead>
					<tbody class="data">
	<?php
if (count($aReportData) == 0) {
    ?>
			<tr>
							<td align="left" style="font-weight: bold; color: #E00">No
								Existen registros que coincidan con el criterio de búsqueda</td>
							<td align="center"><?php echo( number_format($SumaTotal));?></td>
						</tr>
	<?php
} // if

foreach ($aReportData as $aRow) {
    ?>
			<tr style="height: 8px;">
							<td align="left"><a
								href='javascript:ViewProyectsByInstType(<?php echo $aRow["cod_tipo_inst"] ?>)'
								title='Ver Relación de Proyectos para este Tipo de Institución'>
						<?php echo($aRow["tipoinst"]);?>
					</a></td>
							<td align="center"><?php echo( number_format($aRow["cantidad"]));?></td>
							<td align="right"><?php echo( number_format($aRow["FE"], 2));?></td>
							<td align="right"><?php echo( number_format($aRow["otros"], 2));?></td>
							<td align="right"><?php echo( number_format($aRow["total"], 2));?></td>
						</tr>
	<?php
} // foreach
?>
		</tbody>
					<tfoot>
						<tr>
							<td>Total de Proyectos</td>
							<td align="center"><?php echo( number_format($SumaTotal));?></td>
							<td class='total'><?php echo( number_format($SumaFE, 2));?></td>
							<td class='total'><?php echo( number_format($SumaOtros, 2));?></td>
							<td class='total'><a
								href='javascript:ViewProyectsByInstType("*")'
								title='Ver Relación de Proyectos' style='width: auto'>
						<?php echo( number_format($SumaPreTotal, 2));?>
					</a></td>
						</tr>
					</tfoot>
				</table>


				<script type='text/javascript'>
		function ViewProyectsByInstType(pInstType)
		{
			var aParams = 'tinst='+pInstType;
			NewReport("Relacion de Proyectos", "rpt_rel_proy.php",aParams);
		}
	
	</script>

				<br />
				<br />

				<div class='Head'>Montos Asignados por Región</div>
				<br />
<?php
$aResult = $objRep->ListaNroProy_TiposInstRegion($idConcurso, $cboEstado);
$aReportData = array();
$aRegion = null;
$aTotRegion = null;
$aTotGeneral = array(
    'CANTIDAD' => 0,
    'FE' => 0,
    'OTROS' => 0,
    'TOTAL' => 0
);
$aSerie1Arr = array();
$aSerie2Arr = array();
$aSerie2RealArr = array();
$aLabelsArr = array();

function getRegion($pRegion)
{
    return $pRegion ? $pRegion : 'SIN REGION ASIGNADA';
}

while ($aRow = mysqli_fetch_assoc($aResult)) {
    if ($aRegion != getregion($aRow['region'])) {
        if ($aTotRegion != null) {
            $aTotGeneral['CANTIDAD'] += $aTotRegion['CANTIDAD'];
            $aTotGeneral['FE'] += $aTotRegion['FE'];
            $aTotGeneral['OTROS'] += $aTotRegion['OTROS'];
            $aTotGeneral['TOTAL'] += $aTotRegion['TOTAL'];
            
            $aSerie1Arr[] = $aTotRegion["FE"];
            $aSerie2Arr[] = $aTotRegion['OTROS'] - $aTotRegion["FE"];
            $aSerie2RealArr[] = $aTotRegion['OTROS'];
            $aLabelsArr[] = "'" . strtoupper($aRegion) . "'";
            
            $aReportData[$aRegion][] = $aTotRegion;
        }
        $aRegion = getRegion($aRow['region']);
        $aReportData[$aRegion] = array();
        $aTotRegion = array(
            'TIPOINST' => 'Sub Total',
            'CANTIDAD' => 0,
            'FE' => 0,
            'OTROS' => 0,
            'TOTAL' => 0
        );
    }
    $aTotCostDir = $aRow['FE'] + $aRow['otros'];
    $aReportData[$aRegion][] = array(
        'CANTIDAD' => $aRow['cantidad'],
        'TIPOINST' => $aRow['tipoinst'],
        'FE' => $aRow['FE'],
        'OTROS' => $aRow['otros'],
        'TOTAL' => $aTotCostDir
    );
    $aTotRegion['CANTIDAD'] += $aRow['cantidad'];
    $aTotRegion['FE'] += $aRow['FE'];
    $aTotRegion['OTROS'] += $aRow['otros'];
    $aTotRegion['TOTAL'] += $aTotCostDir;
}

if ($aTotRegion != null) {
    $aTotGeneral['CANTIDAD'] += $aTotRegion['CANTIDAD'];
    $aTotGeneral['FE'] += $aTotRegion['FE'];
    $aTotGeneral['OTROS'] += $aTotRegion['OTROS'];
    $aTotGeneral['TOTAL'] += $aTotRegion['TOTAL'];
    
    $aSerie1Arr[] = $aTotRegion["FE"];
    $aSerie2Arr[] = $aTotRegion['OTROS'] - $aTotRegion["FE"];
    $aSerie2RealArr[] = $aTotRegion['OTROS'];
    $aLabelsArr[] = "'" . strtoupper($aRegion) . "'";
    
    $aReportData[$aRegion][] = $aTotRegion;
}

?>
<?php

if (count($aReportData) > 0) {
    ?>
	<div align="center">
					<div id="chart2" style="width: 700px; height: 600px" />
				</div>
				<br />
				<br />
<?php
} // if
?>

	<script type='text/javascript'>
		$(function() {
			var aToolTp1 = formatToolTip(<?php echo '[' . implode(',', $aSerie1Arr) . ']'; ?>);
			var aToolTp2 = formatToolTip(<?php echo '[' . implode(',', $aSerie2RealArr) . ']'; ?>);
			$("#chart2").chart({
				template: "line_basic_6",
				values: {
					serie1: <?php echo '[' . implode(',', $aSerie1Arr) . ']'; ?>,
					serie2: <?php echo '[' . implode(',', $aSerie2Arr) . ']'; ?>
				},
				tooltips: {
					serie1: aToolTp1,
					serie2: aToolTp2
		  		},
		  		legend: {
		  			serie1: "Fondoempleo",
		  			serie2: "Otros"
		  		},
				labels: <?php echo '[' . implode(',', $aLabelsArr) . ']'; ?>,
				defaultSeries: {
					type: "bar",
					stacked: true
				},
				axis: { r: { max: 100, suffix: "%" } }
			});
		});
	</script>


				<table width="640" border="0" cellspacing="0" cellpadding="0">
					<thead>
						<tr>
							<td width="347" align="center"><strong>REGION</strong></td>
							<td width="347" align="center"><strong>TIPO DE INSTITUCION</strong></td>
							<td width="88" align="center"><strong>NUMERO PROYECTOS</strong></td>
							<td width="88" align="center"><strong>FONDOEMPLEO (S/.)</strong></td>
							<td width="88" align="center"><strong>OTROS (S/.)</strong></td>
							<td width="88" align="center"><strong>TOTAL (S/.)</strong></td>
						</tr>
					</thead>
					<tbody class="data">
	<?php
foreach ($aReportData as $aReg => $aRegData) {
    printRptNroProyMonRegInstRow($aRegData, 0, count($aRegData), $aReg);
    for ($i = 1; $i < count($aRegData); $i ++) {
        printRptNroProyMonRegInstRow($aRegData, $i);
    } // for
} // foreach
function printRptNroProyMonRegInstRow($pDataArr, $pIdx, $pRowSpan = null, $pReg = null)
{
    if ($pIdx == count($pDataArr) - 1)
        print '<tr class="subtotal">';
    else
        print '<tr>';
    if ($pRowSpan && $pReg)
        print "<td rowspan='$pRowSpan' align='left'>$pReg</td>";
    ?>
				<td align="left"> <?php echo $pDataArr[$pIdx]['TIPOINST']; ?></td>
						<td align="center"><?php echo number_format($pDataArr[$pIdx]['CANTIDAD']); ?></td>
						<td align="right"><?php echo number_format($pDataArr[$pIdx]['FE'], 2); ?></td>
						<td align="right"><?php echo number_format($pDataArr[$pIdx]['OTROS'], 2); ?></td>
						<td align="right"><?php echo number_format($pDataArr[$pIdx]['TOTAL'], 2); ?></td>
		<?php
    print '</tr>';
}
?>
	</tbody>
					<tfoot>
						<tr>
							<td colspan='2'>Totales</td>
							<td align="center"><?php echo( number_format($aTotGeneral['CANTIDAD']));?></td>
							<td class='total'><?php echo( number_format($aTotGeneral['FE'], 2));?></td>
							<td class='total'><?php echo( number_format($aTotGeneral['OTROS'], 2));?></td>
							<td class='total'><?php echo( number_format($aTotGeneral['TOTAL'], 2));?></td>
						</tr>
					</tfoot>
				</table>
				<br />
				<br />
			</div>

			<!-- InstanceEndEditable -->
		</div>

<?php if($objFunc->__QueryString()=="") { ?>
</form>

</body>
<!-- InstanceEnd -->
</html>
<?php  } ?>