<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauser.inc.php"); ?>
<?php

$idProy = 1;
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
<title>Proyectos segun sector y tipo de Institucion</title>
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
 <?php
require (constant("PATH_CLASS") . "BLReportes.class.php");
require_once (constant("PATH_CLASS") . "BLTablasAux.class.php");

$objTablas = new BLTablasAux();
$objRep = new BLReportes();
$ret = $objRep->ListaNroProy_SecInst($idConcurso, $cboEstado);
$SumaTotal = 0;

$rowFields = $objRep->iGetArrayFields($ret);
$tipoinsts = NULL;
for ($x = 0; $x < count($rowFields); $x ++) {
    if ($x > 1) {
        $tipoinsts[] = $rowFields[$x];
    }
}

$ti = $objRep->ListaTip_Inst();

/* $SumaTotal += $row["cantidad"] ; */
?>
<?php


if (count($tipoinsts) > 0) {
    ?>

    <br />
			<div align="center">
				<img
					src="rpt_secproy_tipoinst.img.php?idConcurso=<?php echo($idConcurso);?>&cboEstado=<?php echo($cboEstado);?>" />
			</div>
			<br /> <?php }?>

<div class="TableGrid" align="center">

				<table id='tipoInstTbl' width="441" border="0" cellspacing="0"
					cellpadding="0">
					<thead>
						<tr>
							<td width="347" align="center"><strong>SECTOR</strong></td>
			<?php
$sumas = NULL;
foreach ($tipoinsts as $row) {
    $sumas[] = 0;
    ?>
			<td width="88" align="center"> <?php echo $row;?></td>
	  		<?php } ?>
			<td width="88" align="center">TOTAL PROYECTOS</td>
						</tr>
					</thead>
					<tbody class="data">
		<?php
while ($row = mysqli_fetch_array($ret)) {
    ?>
		<tr style="height: 8px;">
		<?php
    $suma = 0;
    $aFieldsCnt = mysqli_num_fields($ret);
    for ($x = 1; $x < $aFieldsCnt; $x ++) {
        if ($x <= $aFieldsCnt - 4)
            $suma += $row[$x];
        if ($x > $aFieldsCnt - 4) {
            $anAmt = (float) preg_replace('/[^0-9.]+/', '', $row[$x]);
            $sumas[$x - 1] = number_format((float) preg_replace('/[^0-9.]+/', '', $sumas[$x - 1]) + $anAmt, 2);
        } else {
            $sumas[$x - 1] += $row[$x];
        }
        ?>
			<td align="center">
				<?php
        if ($x == 1 && ! $row[$x])
            echo "SIN SECTOR ASIGNADO";
        else
            echo ($row[$x]);
        ?>
			</td>	 
		<?php	} //for	?>
			<td align="center"><?php echo($suma);?></td>
						</tr>
		<?php }  // while	?>
		<?php
if (mysqli_num_rows($ret) == 0) {
    ?>
		<tr>
							<td align="left" style="font-weight: bold; color: #E00">No
								Existen registros que coincidan con el criterio de búsqueda</td>
							<td align="center"><?php echo( number_format($SumaTotal));?></td>
						</tr>	
		<?php }  //if	 ?>
	</tbody>
					<tfoot>
						<tr style="height: 8px;">
							<td align="center">&nbsp;</td>
			<?php
for ($x = 1; $x < count($sumas); $x ++) {
    if ($x <= $aFieldsCnt - 5)
        $sumat += $sumas[$x];
    ?>
			<td align="center"><?php echo($sumas[$x]);?></td>
			<?php } // for ?>
			<td align="left"><?php echo($sumat);?></td>
						</tr>
					</tfoot>
				</table>

				<br />
				<br />
				<table width="441" border="0" cellspacing="0" cellpadding="0">
					<thead>
						<tr>
							<td width="347" align="center"><strong>REGION</strong></td>
							<td width="347" align="center"><strong>ACTIVIDAD ECONOMICA
									PRINCIPAL</strong></td>
							<td width="347" align="center"><strong>PROYECTOS</strong></td>
							<td width="347" align="center"><strong>FE</strong></td>
							<td width="347" align="center"><strong>OTROS</strong></td>
							<td width="347" align="center"><strong>TOTAL</strong></td>
						</tr>
					</thead>
					<tbody class="data">
  	<?php
$aResult = $objRep->ListaNroProy_SecReg2($idConcurso, $cboEstado);
$aProyRegArr = array();
$aRegion = null;
$aProyTot = 0;
$aFeTot = 0;
$aOtrTot = 0;

while ($aRow = mysqli_fetch_assoc($aResult)) {
    if ($aRegion != $aRow['region']) {
        $aRegion = $aRow['region'];
        $aProyRegArr[$aRegion] = array();
    }
    $aProyRegArr[$aRegion][] = array(
        'ACTIVIDAD' => $aRow['actividad'],
        'CANTIDAD' => $aRow['cantidad'],
        'FE' => $aRow['FE'],
        'OTROS' => $aRow['OTROS'],
        'TOTAL' => $aRow['FE'] + $aRow['OTROS']
    );
    $aProyTot += (float) preg_replace('/[^0-9.]+/', '', $aRow['cantidad']);
    $aFeTot += (float) preg_replace('/[^0-9.]+/', '', $aRow['FE']);
    $aOtrTot += (float) preg_replace('/[^0-9.]+/', '', $aRow['OTROS']);
}

foreach ($aProyRegArr as $aReg => $aRegData) {
    ?>
  			<tr>
							<td rowspan="<?php echo count($aRegData); ?>" align="left"><?php echo $aReg; ?></td>
							<td align="left"> <?php echo $aRegData[0]['ACTIVIDAD']; ?></td>
							<td align="center"><?php echo number_format($aRegData[0]['CANTIDAD']); ?></td>
							<td align="right"><?php echo number_format($aRegData[0]['FE'], 2); ?></td>
							<td align="right"><?php echo number_format($aRegData[0]['OTROS'], 2); ?></td>
							<td align="right"><?php echo number_format($aRegData[0]['TOTAL'], 2); ?></td>
						</tr>
  			<?php
    for ($i = 1; $i < count($aRegData); $i ++) {
        ?>
		  			<tr>
							<td align="left"> <?php echo $aRegData[$i]['ACTIVIDAD']; ?></td>
							<td align="center"><?php echo number_format($aRegData[$i]['CANTIDAD']); ?></td>
							<td align="right"><?php echo number_format($aRegData[$i]['FE'], 2); ?></td>
							<td align="right"><?php echo number_format($aRegData[$i]['OTROS'], 2); ?></td>
							<td align="right"><?php echo number_format($aRegData[$i]['TOTAL'], 2); ?></td>
						</tr>  				
  				<?php
    }
}
?>

  </tbody>
					<tfoot>
						<tr style="height: 8px;">
							<td align="center" colspan="2">Total</td>
							<td><?php echo number_format($aProyTot); ?></td>
							<td><?php echo number_format($aFeTot, 2); ?></td>
							<td><?php echo number_format($aOtrTot, 2); ?></td>
							<td><?php echo number_format($aFeTot + $aOtrTot, 2); ?></td>
						</tr>
					</tfoot>
				</table>

			</div>

			<!-- InstanceEndEditable -->
		</div>
<?php if($objFunc->__QueryString()=="") { ?>
</form>
	<script type='text/javascript'>
<!--
	
// -->
</script>
</body>
<!-- InstanceEnd -->
</html>
<?php  } ?>