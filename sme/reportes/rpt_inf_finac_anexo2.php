<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauser.inc.php"); ?>
<?php

// require_once(constant("PATH_CLASS")."HardCode.class.php");
require_once (constant("PATH_CLASS") . "BLProyecto.class.php");

require_once (constant("PATH_CLASS") . "BLInformes.class.php");

$idProy = $objFunc->__Request('idProy');
$idAnio = $objFunc->__Request('idAnio');
$idMes = $objFunc->__Request('idMes');

$objProy = new BLProyecto();
$ultima_vs = $objProy->MaxVersion($idProy);
$rowProy = $objProy->ProyectoSeleccionar($idProy, $ultima_vs);

$objProy = NULL;

$objInformes = new BLInformes();

$rowInf = $objInformes->InformeFinancieroSeleccionar($idProy, $idAnio, $idMes);

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
			<table width="950" cellpadding="0" cellspacing="0"
				style="border: none;">
				<tr>
					<td width="1176" colspan="8" align="center" valign="middle"><span
						class="ClassField" style="text-transform: uppercase;">CONVENIO&nbsp; INSTITUCION (<?php echo($rowProy['t01_sig_inst']);?>)  -    FONDOEMPLEO</span></td>
				</tr>
				<tr>
					<td width="1176" colspan="8" rowspan="2" align="center"
						valign="middle"><span class="ClassField">&quot;<font
							style="text-transform: uppercase"><?php echo($rowProy['t02_nom_proy']);?></font>&quot;
					</span></td>
				</tr>
				<tr>
				</tr>
				<tr>
					<td align="center" valign="middle"></td>
					<td align="center" valign="middle"></td>
					<td align="center" valign="middle"></td>
					<td align="center" valign="middle"></td>
					<td align="center" valign="middle"></td>
					<td align="center" valign="middle"></td>
					<td align="center" valign="middle"></td>
					<td align="center" valign="middle"></td>
				</tr>
				<tr>
					<td colspan="8" align="center" valign="middle"><span
						class="ClassField" style="text-transform: uppercase;">RESUMEN DEL EXCEDENTE POR EJECUTAR     AL <?php echo($objFunc->FechaLarga($rowInf['t40_fch_pre']));?></span></td>
				</tr>
				<tr>
					<td colspan="8" align="center" valign="middle"><span
						class="ClassField">(Expresados en Moneda Nacional)</span></td>
				</tr>
			</table>
			<BR />
			<table width="950" cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						<td width="115" align="center" valign="bottom"
							style="border: none;">MES</td>
						<td width="126" align="center" valign="top" style="border: none;">CUENTA</td>
						<td width="115" align="center">Caja Chica</td>
						<td width="119" align="center">BANCOS</td>
						<td width="102" align="center" valign="middle">Entregas a Rendir
							Cuenta</td>
						<td width="98" align="center" valign="middle">Cuentas por cobrar</td>
						<td width="78" align="center" valign="middle">Cuentas por Pagar</td>
						<td width="107" align="center">TOTAL</td>
					</tr>
				</thead>
				<tbody class="data">
					<tr>
						<td colspan="2" align="left"><?php echo($objFunc->FechaLarga($rowInf['t40_fch_pre']));?></td>
						<td align="right"><?php echo(number_format($rowInf['t40_caja'],2));?></td>
						<td align="right"><?php echo(number_format($rowInf['t40_bco_mn'],2));?></td>
						<td align="right"><?php echo(number_format($rowInf['t40_ent_rend'],2));?></td>
						<td align="right"><?php echo(number_format($rowInf['t40_cxc'],2));?></td>
						<td align="right"><?php echo(number_format($rowInf['t40_cxp'],2));?></td>
						<td align="right"><?php echo(number_format($rowInf['t40_exc'],2));?></td>
					</tr>
					<tr>
						<td colspan="2" align="left">&nbsp;</td>
						<td align="right">&nbsp;</td>
						<td align="right">&nbsp;</td>
						<td align="right">&nbsp;</td>
						<td align="right">&nbsp;</td>
						<td align="right">&nbsp;</td>
						<td align="right">&nbsp;</td>
					</tr>
				</tbody>
				<tbody class="data">
					<tr>
						<td colspan="2" align="left" bgcolor="#E8E8E8">TOTAL GENERAL</td>
						<td align="right" bgcolor="#E8E8E8">&nbsp;</td>
						<td align="right" bgcolor="#E8E8E8">&nbsp;</td>
						<td align="right" bgcolor="#E8E8E8">&nbsp;</td>
						<td align="right" bgcolor="#E8E8E8">&nbsp;</td>
						<td align="right" bgcolor="#E8E8E8">&nbsp;</td>
						<td align="right" bgcolor="#E8E8E8"><?php echo(number_format($rowInf['t40_exc'],2));?></td>
					</tr>
				</tbody>
			</table>
			<p>
				<br />
			</p>

			<br />

			<!-- InstanceEndEditable -->
		</div>
<?php if($objFunc->__QueryString()=="") { ?>
</form>
</body>
<!-- InstanceEnd -->
</html>
<?php } ?>