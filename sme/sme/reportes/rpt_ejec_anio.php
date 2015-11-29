<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauser.inc.php"); ?>
<?php

require (constant("PATH_CLASS") . "BLFE.class.php");
require (constant("PATH_CLASS") . "BLProyecto.class.php");

$objFE = new BLFE();
$objProy = new BLProyecto();

$Concurso = $objFunc->__Request('cboConcurso');
$Anio = $objFunc->__Request('cboAnios');
$sector = $objFunc->__Request('sector');
$region = $objFunc->__Request('region');

$anio_min = $objProy->AnioMenor();
$anio_max = $objProy->AnioMax();
$rsProyectos = $objFE->ReCromPlaneadoAnio($Anio, $Concurso, $sector, $region);
?>

<?php if($objFunc->__QueryString()=="") { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/tempReports.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title></title>
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
			<table width="620px" align="center" cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						<td width="100px" valign="middle"><p align="center">Codigo
								Proyecto</p></td>
						<td width="230px" valign="middle"><p align="center">Presupuesto
								Planeado</p></td>
						<td width="230px" valign="middle"><p align="center">Mondo
								Ejecutado</p></td>
						<td width="100px" valign="middle"><p align="center">% Ejecutado</p></td>
					</tr>
				</thead>
				<tbody class="data" bgcolor="#FFFFFF" style="font-size: 11px;">
			<?php
$totalp = 0;
$totale = 0;

while ($row = mysqli_fetch_assoc($rsProyectos)) {
    $totalp += $row['planeado'];
    $totale += $row['ejecutado'];
    $porcentaje = ($row['ejecutado'] / $row['planeado']);
    ?>
				<tr>
						<td align="center"><?php echo $row['t02_cod_proy']; ?></td>
						<td align="right"><?php echo number_format($row['planeado'], 2); ?></td>
						<td align="right"><?php echo number_format($row['ejecutado'], 2); ?></td>
						<td align="right"><?php echo number_format($porcentaje * 100, 2); ?></td>
					</tr>
			<?php
}
?>
			</tbody>
				<tfoot>
					<tr>
						<td>Total</td>
						<td class='total'><?php echo number_format($totalp, 2); ?></td>
						<td class='total'><?php echo number_format($totale, 2); ?></td>
						<td class='total'><?php echo number_format(($totale/$totalp) * 100, 2); ?></td>
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
<?php } ?>