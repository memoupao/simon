<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauser.inc.php"); ?>
<?php

require (constant("PATH_CLASS") . "BLFE.class.php");
require (constant("PATH_CLASS") . "BLProyecto.class.php");

$objFE = new BLFE();
$objProy = new BLProyecto();

$Concurso = $objFunc->__Request('cboConcurso');
$anio_min = $objProy->AnioMenor();
$anio_max = $objProy->AnioMax();

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

			<table width="98%" align="center" cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						<td width="20%" valign="middle"><p align="center">Tipo Servicio</p></td>
						<td width="20%" valign="middle"><p align="center">Tema</p></td>
			<?php for($x = $anio_min ;$anio_max >= $x; $x++){ ?>
			<td valign="middle">
							<p align="center">
								<a
									href='javascript:ViewDetailsByYear("<?php print $Concurso; ?>", "<?php print $x; ?>")'
									title='Ver detalle por Regiones y Actividad Económica Principal'>Año <?php print $x; ?></a>
							</p>
						</td>
			<?php  } ?>
			<td width="10%" valign="middle"><p align="center">Total</p></td>
					</tr>
				</thead>
				<tbody class="data" bgcolor="#FFFFFF" style="font-size: 11px;">
  	<?php
$serv = array();
$totalAnio = array();
for ($x = $anio_min; $anio_max >= $x; $x ++) {
    $totalAnio[$x] = 0;
    $rsProyectos = $objFE->RepTipoServ($x, $Concurso);
    while ($row = mysqli_fetch_assoc($rsProyectos)) {
        $serv[$row['servicio']][$row['modulo']][$x] += $row['benef'];
        $totalAnio[$x] += $row['benef'];
    }
}

foreach ($serv as $s => $t) {
    $aRowSpan = count($t) + 1;
    echo "<tr><td rowspan='$aRowSpan' align='right' valign='middle'>" . $s . "</td></tr>";
    
    foreach ($t as $id => $tm) {
        $totaltm = 0;
        $aRowTema = "<tr><td align='right' valign='middle'>" . $id . "</td>";
        for ($x = $anio_min; $anio_max >= $x; $x ++) {
            $aNumBen = ($tm[$x] != "" ? $tm[$x] : 0);
            $totaltm += $aNumBen;
            $aRowTema .= "<td align='center' valign='middle'>" . number_format($aNumBen) . "</td>";
        }
        $aRowTema .= "<td align='center' valign='middle'>" . number_format($totaltm) . "</td></tr>";
        echo $aRowTema;
    }
}
?>
	</tbody>
				<tbody
					style="text-align: right; font-size: 10px; font-weight: bold;">
					<tr>
						<td height="24" align="right" valign="middle">TOTAL</td>
						<td align="right" valign="middle"><?php echo "- -"; ?></td>
			<?php
$aTotGen = 0;
for ($x = $anio_min; $anio_max >= $x; $x ++) {
    $aTotGen += $totalAnio[$x];
    echo "<td align='center' valign='middle'>" . number_format($totalAnio[$x]) . "</td>";
}
?>
	 		<td align="center" valign="middle"><?php echo number_format($aTotGen); ?></td>
					</tr>
				</tbody>
			</table>
			<br />
			<script type='text/javascript'>

	function ViewDetailsByYear(pConc, pYear)
	{
		var aParams = 'cboConcurso='+pConc+'&anio='+pYear;
		NewReport("Beneficiarios por Tipo de Servicio Recibido y Tema " + pYear,"rpt_tipo_serv_rec_det.php",aParams);
	}
	
</script>

			<!-- InstanceEndEditable -->
		</div>
<?php if($objFunc->__QueryString()=="") { ?>
</form>
</body>
<!-- InstanceEnd -->
</html>
<?php } ?>