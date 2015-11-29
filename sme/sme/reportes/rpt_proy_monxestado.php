<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauser.inc.php"); ?>
<?php

require (constant("PATH_CLASS") . "BLReportes.class.php");

$objRep = new BLReportes();

$idProy = 1;
$idConcurso = $objFunc->__Request('cboConcurso');
$cboEstado = $objFunc->__Request('cboEstado');
?>


<?php  if($idProy=='') { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/tempReports.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Número de Proyectos y Montos según estado de ejecución</title>
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
<?php
$aResult = $objRep->ListaNroProy_MontoEstadoEjecucion();
$aReportData = array();
$aConcurso = null;
$aRegion = null;
$aConIdx = - 1;
$aRegIdx = - 1;

while ($aRow = mysqli_fetch_assoc($aResult)) {
    if ($aConcurso != $aRow['concurso']) {
        $aConcurso = $aRow['concurso'];
        $aConIdx = count($aReportData);
        $aRegion = null;
        $aReportData[$aConIdx] = array();
    }
    if ($aRegion != $aRow['region']) {
        $aRegion = $aRow['region'];
        $aRegIdx = count($aReportData[$aConIdx]);
        $aReportData[$aConIdx][$aRegIdx] = array();
    }
    $aReportData[$aConIdx][$aRegIdx][] = array(
        'CONCURSO' => $aConcurso,
        'REGION' => $aRegion ? $aRegion : 'SIN REGION ASIGNADA',
        'ESTADO' => $aRow['estado'],
        'PRESUPUESTO' => $aRow['presupuesto'],
        'NROPROY' => $aRow['nro_proy']
    );
}
?>

	<table width="500px" border="0" cellspacing="0" cellpadding="0">
				<thead>
					<tr>
						<td width="69px" align="center">CONSURSO</td>
						<td width="169px" align="center">REGIóN</td>
						<td width="89px" align="center">ESTADO DE EJECUCIóN</td>
						<td width="49px" align="center">NRO. PROYECTOS</td>
						<td width="119px" align="center">PRESUPUESTO</td>
					</tr>
				</thead>
				<tbody class='data'>
		<?php
$aConPreTotal = 0;
$aConProTotal = 0;
$aGenPreTotal = 0;
$aGenProTotal = 0;

for ($i = 0; $i < count($aReportData); $i ++) {
    $aConCount = 0;
    foreach ($aReportData[$i] as $arr)
        $aConCount += count($arr);
    $aConcurso = $aReportData[$i][0][0]['CONCURSO'];
    
    for ($h = 0; $h < count($aReportData[$i]); $h ++) {
        $aRegion = $aReportData[$i][$h][0]['REGION'];
        
        for ($j = 0; $j < count($aReportData[$i][$h]); $j ++) {
            ?>
		  					<tr>
		  				<?php
            if ($h == 0 && $j == 0) {
                ?>
		  					<td rowspan="<?php echo $aConCount + 1; ?>" align="center"
							valign="middle"><?php echo $aReportData[$i][$h][$j]['CONCURSO']; ?></td>
						<td rowspan="<?php echo count($aReportData[$i][$h]); ?>"
							align="left"><?php echo $aReportData[$i][$h][$j]['REGION']; ?></td>
		  					<?php
            } elseif ($j == 0) {
                ?>
		  					<td rowspan="<?php echo count($aReportData[$i][$h]); ?>"
							align="left"><?php echo $aReportData[$i][$h][$j]['REGION']; ?></td>
		  					<?php
            }
            ?>
			  				<td align="left"><?php echo $aReportData[$i][$h][$j]['ESTADO']; ?></td>
						<td align="center"><?php echo number_format($aReportData[$i][$h][$j]['NROPROY']); ?></td>
						<td align="right"><?php echo number_format($aReportData[$i][$h][$j]['PRESUPUESTO'], 2); ?></td>
					</tr>
		  				<?php
            $aConProTotal += $aReportData[$i][$h][$j]['NROPROY'];
            $aConPreTotal += $aReportData[$i][$h][$j]['PRESUPUESTO'];
        }
    }
    $aGenProTotal += $aConProTotal;
    $aGenPreTotal += $aConPreTotal;
    ?>
	  				<tr class='subtotal'>
						<td align='left' colspan='2'>Sub Total</td>
						<td align='center'><?php echo number_format($aConProTotal); ?></td>
						<td align='right'><?php echo number_format($aConPreTotal, 2); ?></td>
					</tr>
	  			<?php
    $aConProTotal = 0;
    $aConPreTotal = 0;
}

?>
		</tbody>
				<tfoot>
					<tr>
						<td colspan='3''>TOTAL</td>
						<td><?php echo number_format($aGenProTotal); ?></td>
						<td><?php echo number_format($aGenPreTotal, 2); ?></td>
					</tr>
				</tfoot>
			</table>

			<br />
			<!-- InstanceEndEditable -->
		</div>
<?php if($objFunc->__QueryString()=="") { ?>
</form>
</body>
<!-- InstanceEnd -->
</html>
<?php  } ?>