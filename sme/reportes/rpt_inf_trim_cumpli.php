<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauser.inc.php"); ?>
<?php

require (constant("PATH_CLASS") . "BLProyecto.class.php");
require (constant("PATH_CLASS") . "BLInformes.class.php");

$idProy = $objFunc->__Request('idProy');
$idAnio = $objFunc->__Request('anio');
$idTrim = $objFunc->__Request('trim');
// $vers = $objFunc->__Request('vs');

$objProy = new BLProyecto();
$ultima_vs = $objProy->MaxVersion($idProy);
$Proy_Datos_Bas = $objProy->GetProyecto($idProy, $ultima_vs);

$objInf = new BLInformes();
$rowInf = $objInf->InformeTrimestralSeleccionar($idProy, $idAnio, $idTrim, 1);

$rsDet = $objInf->RptCumplimiento_trim($idProy, $idAnio, $idTrim);

?>


<?php if($objFunc->__QueryString()=="") { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/tempReports.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>% Cumplimiento del Mes</title>
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
			<table width="95%" border="0" align="center" cellpadding="0"
				cellspacing="1" class="TableGrid">
				<tr>
					<th width="17%" align="left">CODIGO DEL PROYECTO</th>
					<td colspan="4" align="left"><?php echo($Proy_Datos_Bas['t02_cod_proy']);?></td>
					<th width="7%" align="left" nowrap="nowrap">INICIO</th>
					<td width="20%" align="left"><?php echo($Proy_Datos_Bas['t02_fch_ini']);?></td>
				</tr>
				<tr>
					<th align="left" nowrap="nowrap">DESCRIPCION PROYECTO</th>
					<td colspan="4" align="left"><?php echo($Proy_Datos_Bas['t02_nom_proy']);?></td>
					<th align="left" nowrap="nowrap">TERMINO</th>
					<td align="left"><?php echo($Proy_Datos_Bas['t02_fch_ter']);?></td>
				</tr>
				<tr>
					<th height="32" align="left">PERIODO DE INFORMACION</th>
					<td width="7%" align="center"><?php echo("Año".$idAnio);?></td>
					<td width="7%" align="center"><?php echo("Trimestre ".$idTrim);?></td>
					<th width="24%">FECHA PRESENTACION DEL INFORME</th>
					<td width="18%" align="left"><?php echo($rowInf['t25_fch_pre']);?></td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td height="35" colspan="7" align="left" valign="bottom">(*) Para
						el cálculo del % de Cumplimiento, solo se consideran las
						Subactividades planeadas que esten dentro del periodo en cuesion.<br />
						(*) Se esta considerando como 100%, a todas las subactividades,
						donde lo ejecutado supera a lo planeado.
					</td>
				</tr>
			</table>
			<br />
			<table width="73%" align="center" cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						<td width="7%" height="33" align="center" valign="middle">CODIGO</td>
						<td width="32%" align="center" valign="middle">SUBACTIVIDAD</td>
						<td width="14%" align="center" valign="middle">U.M</td>
						<td width="11%" align="center" valign="middle">META GLOBAL</td>
						<td width="12%" align="center" valign="middle">META PLANEADA</td>
						<td width="11%" align="center" valign="middle">EJECUTADO</td>
						<td width="13%" align="center" valign="middle">% CUMPLIMIENTO</td>
					</tr>
				</thead>

				<tbody class="data" bgcolor="#FFFFFF">
    <?php
    $Index = 0;
    $Suma = 0;
    while ($row = mysqli_fetch_assoc($rsDet)) {
        $Suma += $row['cumplimiento'];
        ?>
    <tr style="font-size: 11px;">
						<td align="center" valign="top"><?php echo($row['codigo']);?>&nbsp;</td>
						<td align="left" valign="top"><?php echo($row['subactividad']);?>&nbsp;</td>
						<td align="center" valign="top"><?php echo($row['unidad_medida']);?>&nbsp;</td>
						<td align="center" valign="top"><?php echo($row['meta_total']);?>&nbsp;</td>
						<td align="center" valign="top"><?php echo($row['planeado']);?>&nbsp;</td>
						<td align="center" valign="top"><?php echo($row['informado']);?>&nbsp;</td>
						<td align="right" valign="top"><?php echo($row['cumplimiento']." %");?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
					</tr>
    <?php
        $Index ++;
    } // End While
    $rsDet->free();
    ?>
  </tbody>

				<tr>
					<td height="19" colspan="2" align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<th colspan="3" align="right" valign="middle">N&ordm;
						SubActividades Planeadas</th>
					<td align="right" valign="middle"><b><?php echo($Index);?></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				</tr>
				<tr>
					<td height="25" colspan="2" align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<th colspan="3" align="right" valign="middle">Promedio Cumplimiento</th>
					<td align="right" valign="middle"><b><?php echo(number_format(($Suma/$Index),2));?></b>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				</tr>
				<tfoot>
					<tr>
						<td height="15" colspan="2" align="left" valign="middle">&nbsp;</td>
						<td align="left" valign="middle">&nbsp;</td>
						<td align="left" valign="middle">&nbsp;</td>
						<td colspan="2" align="right" valign="middle">&nbsp;</td>
						<td align="right" valign="middle">&nbsp;</td>
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