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
<title>Aportes por Tipo de Institucion</title>
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
				<table width="431" border="0" cellspacing="0" cellpadding="0">
					<thead>
						<tr>
							<td width="249" align="center"><strong>TIPOS DE INSTITUCION</strong></td>
							<td width="90" align="center"><strong>APORTE FONDOEMPLEO</strong></td>
							<td width="90" align="center"><strong> APORTE OTROS</strong></td>
							<td width="90" align="center"><strong>TOTAL</strong></td>
						</tr>
					</thead>
					<tbody class="data">
    <?php
    require (constant("PATH_CLASS") . "BLReportes.class.php");
    $objRep = new BLReportes();
    $ret = $objRep->ListaAportesProy_TiposInst($idConcurso, $cboEstado);
    $SumaTot = 0;
    while ($row = mysqli_fetch_assoc($ret)) {
        $SumaFe += $row["aportefe"];
        $SumaOt += $row["aporteotros"];
        $SumaTot += ($row["aportefe"] + $row["aporteotros"]);
        ?>
    <tr>
							<td align="left"><?php echo($row["tipoinst"]);?></td>
							<td align="right"><?php echo( number_format($row["aportefe"]));?></td>
							<td align="right"><?php echo( number_format($row["aporteotros"]));?></td>
							<td align="right"><?php echo( number_format($row["aportefe"] + $row["aporteotros"]));?></td>
						</tr>
    <?php
    
}
    ?>
  </tbody>
					<tfoot>
						<tr>
							<td>Total</td>
							<td align="right"><?php echo( number_format($SumaFe));?></td>
							<td align="right"><?php echo( number_format($SumaOt));?></td>
							<td align="right"><?php echo( number_format($SumaTot));?></td>
						</tr>
					</tfoot>
				</table>
			</div>
			<br />
			<div align="center">
				<img
					src="<?php echo(constant("PATH_RPT"));?>rpt_apo_proy_tipoinst.img.php?idConcurso=<?php echo($idConcurso);?>&cboEstado=<?php echo($cboEstado);?>" />
			</div>
			<br />
			<!-- InstanceEndEditable -->
		</div>
<?php if($objFunc->__QueryString()=="") { ?>
</form>
</body>
<!-- InstanceEnd -->
</html>
<?php  } ?>