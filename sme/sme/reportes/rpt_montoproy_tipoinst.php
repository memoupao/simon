<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauser.inc.php"); ?>
<?php

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
<title>Monto de Proyectos por tipo de Institucion</title>
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
				<table width="441" border="0" cellspacing="0" cellpadding="0">
					<thead>
						<tr>
							<td width="347" align="center"><strong>TIPO INSTITUCIONES</strong></td>
							<td width="88" align="center"><strong>MONTO TOTAL</strong></td>
						</tr>
					</thead>
					<tbody class="data">
  <?php
require (constant("PATH_CLASS") . "BLReportes.class.php");
$objRep = new BLReportes();
// file_put_contents('hugo.txt',$idConcurso."_".$cboEstado);
$ret = $objRep->ListaMontoProy_TiposInst($idConcurso, $cboEstado);
$SumaTotal = 0;
while ($row = mysqli_fetch_assoc($ret)) {
    $SumaTotal += $row["cantidad"];
    ?>
  <tr style="height: 8px;">
							<td align="left"><?php echo($row["tipoinst"]);?></td>
							<td align="center"><?php echo( number_format($row["cantidad"]));?></td>
						</tr>
 
  <?php

}

if (mysqli_num_rows($ret) == 0) {
    ?>
     	<tr>
							<td align="left" style="font-weight: bold; color: #E00">No
								Existen registros que coincidan con el criterio de búsqueda</td>
							<td align="center"><?php echo( number_format($SumaTotal));?></td>
						</tr>
       	<?php
}
?>
    </tbody>
					<tfoot>
						<tr>
							<td>Total</td>
							<td align="center"><?php echo( number_format($SumaTotal));?></td>
						</tr>
					</tfoot>
				</table> <?php
    
if (mysqli_num_rows($ret) > 0) {
        ?>
    </div>
			<br />
			<div align="center">
				<img
					src="rpt_montoproy_tipoinst.img.php?idConcurso=<?php echo($idConcurso);?>&cboEstado=<?php echo($cboEstado);?>" />

			</div>
			<br /> <?php }?>
<!-- InstanceEndEditable -->
		</div>
<?php if($objFunc->__QueryString()=="") { ?>
</form>
</body>
<!-- InstanceEnd -->
</html>
<?php  } ?>