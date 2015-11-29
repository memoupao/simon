<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauser.inc.php"); ?>
<?php

require_once (constant("PATH_CLASS") . "BLProyecto.class.php");
require_once (constant("PATH_CLASS") . "BLTablasAux.class.php");

$idProy = 1;
$objTablas = new BLTablasAux();
$idProy = $objFunc->__Request('idProy');

$idConcurso = $objFunc->__Request('cboConcurso');
$cboEstado = $objFunc->__Request('cboEstado');
?>


<?php  if($idProy=='') { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/tempReports.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Reporte numero de proyectos x Region</title>
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
				<table width="640" border="0" cellspacing="0" cellpadding="0">
					<thead>
						<tr>
							<td width="250" align="center"><strong>REGIONES</strong></td>
							<td width="90" align="center"><strong>NUMERO PROYECTOS</strong></td>
							<td width="100" align="center"><strong>APORTE FONDOEMPLEO (S/.)</strong></td>
							<td width="100" align="center"><strong>APORTE OTROS (S/.)</strong></td>
							<td width="100" align="center"><strong>TOTAL (S/.)</strong></td>
						</tr>
					</thead>
					<tbody class="data">
		<?php
require (constant("PATH_CLASS") . "BLReportes.class.php");
$objRep = new BLReportes();
$ret = $objRep->ListaNroProy_Region($idConcurso, $cboEstado);
$aSumas = array(
    'NroProy' => 0,
    'SumFE' => 0,
    'SumOtros' => 0,
    'SumTodos' => 0
);

while ($row = mysqli_fetch_assoc($ret)) {
    $aTotRegion = $row["FE"] + $row["OTROS"];
    $aSumas['NroProy'] += $row["cantidad"];
    $aSumas['SumFE'] += $row["FE"];
    $aSumas['SumOtros'] += $row["OTROS"];
    $aSumas['SumTodos'] += $aTotRegion;
    ?>
  			<tr>
							<td align="left"><?php echo($row["region"] ? $row["region"] : 'SIN REGION ASIGNADA');?></td>
							<td align="center"><?php echo( number_format($row["cantidad"]));?></td>
							<td align="right"><?php echo( number_format($row["FE"], 2));?></td>
							<td align="right"><?php echo( number_format($row["OTROS"], 2));?></td>
							<td align="right"><?php echo( number_format($aTotRegion, 2));?></td>
						</tr>
   		<?php

}

if (mysqli_num_rows($ret) == 0) {
    ?>
			<tr>
							<td align="left" style="font-weight: bold; color: #E00">No
								Existen registros que coincidan con el criterio de búsqueda</td>
							<td align="center">0</td>
							<td align="right">0</td>
							<td align="right">0</td>
							<td align="right">0</td>
						</tr>
  		<?php }	?>
		</tbody>
					<tfoot>
						<tr>
							<td>Totales</td>
							<td><?php echo( number_format($aSumas['NroProy']) );?></td>
							<td class='total'><?php echo( number_format($aSumas['SumFE'], 2) );?></td>
							<td class='total'><?php echo( number_format($aSumas['SumOtros'], 2) );?></td>
							<td class='total'><?php echo( number_format($aSumas['SumTodos'], 2) );?></td>
						</tr>
					</tfoot>
				</table>
			</div>

			<br />
			<br />

			<table width="760" border="0" cellspacing="0" cellpadding="0">
				<thead>
					<tr>
						<td width="189" align="center"><strong>REGION</strong></td>
						<td width="179" align="center"><strong>TIPO DE INSTITUCION</strong></td>
						<td width="79" align="center"><strong>NUMERO PROYECTOS</strong></td>
						<td width="99" align="center"><strong>APORTE FONDOEMPLEO (S/.)</strong></td>
						<td width="99" align="center"><strong>APORTE OTROS (S/.)</strong></td>
						<td width="109" align="center"><strong>TOTAL (S/.)</strong></td>
					</tr>
				</thead>
				<tbody class="data">
			<?php
$aResult = $objRep->ListaNroProy_TiposInstRegion($idConcurso, $cboEstado);
$aRegionArr = array();
$aRegion = null;
$aCntTot = 0;
$aFeTot = 0;
$aOtrTot = 0;

while ($aRow = mysqli_fetch_assoc($aResult)) {
    if ($aRegion != $aRow['region']) {
        $aRegion = $aRow['region'];
        $aRegionArr[$aRegion] = array();
    }
    $aRegionArr[$aRegion][] = array(
        'INST' => $aRow['tipoinst'],
        'NROPROY' => $aRow['cantidad'],
        'FE' => $aRow['FE'],
        'OTROS' => $aRow['otros'],
        'TOTAL' => $aRow['FE'] + $aRow['otros']
    );
    $aCntTot += $aRow['cantidad'];
    $aFeTot += $aRow['FE'];
    $aOtrTot += $aRow['otros'];
} // while

foreach ($aRegionArr as $aReg => $aRegData) {
    ?>
		  			<tr>
						<td rowspan="<?php echo count($aRegData); ?>" align="left"><?php echo $aReg ? $aReg : 'SIN REGION ASIGNADA'; ?></td>
						<td align="left"> <?php echo $aRegData[0]['INST']; ?></td>
						<td align="center"> <?php echo number_format($aRegData[0]['NROPROY']); ?></td>
						<td align="right"><?php echo number_format($aRegData[0]['FE'], 2); ?></td>
						<td align="right"><?php echo number_format($aRegData[0]['OTROS'], 2); ?></td>
						<td align="right"><?php echo number_format($aRegData[0]['TOTAL'], 2); ?></td>
					</tr>
		  	<?php
    for ($i = 1; $i < count($aRegData); $i ++) {
        ?>
				  			<tr>
						<td align="left"> <?php echo $aRegData[$i]['INST']; ?></td>
						<td align="center"> <?php echo number_format($aRegData[$i]['NROPROY']); ?></td>
						<td align="right"><?php echo number_format($aRegData[$i]['FE'], 2); ?></td>
						<td align="right"><?php echo number_format($aRegData[$i]['OTROS'], 2); ?></td>
						<td align="right"><?php echo number_format($aRegData[$i]['TOTAL'], 2); ?></td>
					</tr>  				
		  	<?php
    } // for
} // foreach
?>
		</tbody>
				<tfoot>
					<tr>
						<td colspan="2">Totales</td>
						<td><?php echo( number_format($aCntTot) );?></td>
						<td class='total'><?php echo( number_format($aFeTot, 2) );?></td>
						<td class='total'><?php echo( number_format($aOtrTot, 2) );?></td>
						<td class='total'><?php echo( number_format($aFeTot + $aOtrTot, 2) );?></td>
					</tr>
				</tfoot>
			</table>

			<br />
			<br />

			<table width="760" border="0" cellspacing="0" cellpadding="0">
				<thead>
					<tr>
						<td width="347" align="center"><strong>REGION</strong></td>
						<td width="347" align="center"><strong>ACTIVIDAD ECONOMICA
								PRINCIPAL</strong></td>
						<td width="179" align="center"><strong>TIPO DE INSTITUCION</strong></td>
						<td width="179" align="center"><strong>NUMERO PROYECTOS</strong></td>
						<td width="347" align="center"><strong>APORTE FONDOEMPLEO (S/.)</strong></td>
						<td width="347" align="center"><strong>APORTE OTROS (S/.)</strong></td>
						<td width="347" align="center"><strong>TOTAL (S/.)</strong></td>
					</tr>
				</thead>
				<tbody class="data">
			<?php
$aResult = $objRep->ListaNroProy_RegSecInst($idConcurso, $cboEstado);
$aRegionArr = array();
$aRegion = null;
$aSector = null;
$aRegIdx = - 1;
$aSecIdx = - 1;
$aCntTot = 0;
$aFeTot = 0;
$aOtrTot = 0;

while ($aRow = mysqli_fetch_assoc($aResult)) {
    if ($aRegion != $aRow['region']) {
        $aRegion = $aRow['region'];
        $aRegIdx = count($aRegionArr);
        $aRegionArr[$aRegIdx] = array();
        $aSector = null;
    }
    if ($aSector != $aRow['actividad']) {
        $aSector = $aRow['actividad'];
        $aSecIdx = count($aRegionArr[$aRegIdx]);
        $aRegionArr[$aRegIdx][$aSecIdx] = array();
    }
    $aRegionArr[$aRegIdx][$aSecIdx][] = array(
        'REGION' => $aRegion ? $aRegion : 'SIN REGION ASIGNADA',
        'SECTOR' => $aSector ? $aSector : 'SIN SECTOR ASIGNADO',
        'INST' => $aRow['tipoinst'],
        'NROPROY' => $aRow['cantidad'],
        'FE' => $aRow['FE'],
        'OTROS' => $aRow['otros'],
        'TOTAL' => $aRow['FE'] + $aRow['otros']
    );
    $aCntTot += $aRow['cantidad'];
    $aFeTot += $aRow['FE'];
    $aOtrTot += $aRow['otros'];
} // while

for ($i = 0; $i < count($aRegionArr); $i ++) {
    $aRowCount = 0;
    foreach ($aRegionArr[$i] as $arr)
        $aRowCount += count($arr);
    $aRegion = $aRegionArr[$i][0][0]['REGION'];
    
    for ($h = 0; $h < count($aRegionArr[$i]); $h ++) {
        $aSector = $aRegionArr[$i][$h][0]['SECTOR'];
        
        for ($j = 0; $j < count($aRegionArr[$i][$h]); $j ++) {
            ?>
			  					<tr>
			  				<?php
            if ($h == 0 && $j == 0) {
                ?>
			  					<td rowspan="<?php echo $aRowCount; ?>" align="left"><?php echo $aRegionArr[$i][$h][$j]['REGION']; ?></td>
						<td rowspan="<?php echo count($aRegionArr[$i][$h]); ?>"
							align="left"><?php echo $aRegionArr[$i][$h][$j]['SECTOR']; ?></td>
			  					<?php
            } elseif ($j == 0) {
                ?>
			  					<td rowspan="<?php echo count($aRegionArr[$i][$h]); ?>"
							align="left"><?php echo $aRegionArr[$i][$h][$j]['SECTOR']; ?></td>
			  					<?php
            }
            ?>
				  				<td align="left"><?php echo htmlentities($aRegionArr[$i][$h][$j]['INST']); ?></td>
						<td align="center"><?php echo number_format($aRegionArr[$i][$h][$j]['NROPROY']); ?></td>
						<td align="right"><?php echo number_format($aRegionArr[$i][$h][$j]['FE'], 2); ?></td>
						<td align="right"><?php echo number_format($aRegionArr[$i][$h][$j]['OTROS'], 2); ?></td>
						<td align="right"><?php echo number_format($aRegionArr[$i][$h][$j]['TOTAL'], 2); ?></td>
					</tr>
			  				<?php
        }
    }
}
?>
		</tbody>
				<tfoot>
					<tr>
						<td colspan="3">Totales</td>
						<td><?php echo( number_format($aCntTot) );?></td>
						<td class='total'><?php echo( number_format($aFeTot, 2) );?></td>
						<td class='total'><?php echo( number_format($aOtrTot, 2) );?></td>
						<td class='total'><?php echo( number_format($aFeTot + $aOtrTot, 2) );?></td>
					</tr>
				</tfoot>
			</table>
			<br /> <br /> <br />

			<!-- InstanceEndEditable -->
		</div>
<?php if($objFunc->__QueryString()=="") { ?>
</form>
</body>
<!-- InstanceEnd -->
</html>
<?php  } ?>