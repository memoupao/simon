<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauser.inc.php"); ?>
<?php

require_once (constant("PATH_CLASS") . "BLProyecto.class.php");
require_once (constant("PATH_CLASS") . "BLFuentes.class.php");

$idProy = $objFunc->__Request('idProy');
$idVersion = $objFunc->__Request('idVersion');

$objP = new BLProyecto(Proyecto);
$LP = $objP->GetProyecto($idProy, $idVersion);
?>
<?php if($idProy=='') { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/tempReports.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Reporte Fuentes de Financiamiento</title>
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
			<table width="99%" border="0" align="center" cellpadding="0"
				cellspacing="1" class="TableGrid">
				<tr>
					<th width="18%" align="left">CODIGO DEL PROYECTO</th>
					<td width="55%" align="left"><?php echo($LP['t02_cod_proy']);?></td>
					<th width="7%" align="left" nowrap="nowrap">INICIO</th>
					<td width="20%" align="left"><?php echo($LP['t02_fch_ini']);?></td>
				</tr>
				<tr>
					<th align="left" nowrap="nowrap">DESCRIPCION DEL PROYECTO</th>
					<td align="left"><?php echo($LP['t02_nom_proy']);?></td>
					<th align="left" nowrap="nowrap">TERMINO</th>
					<td align="left"><?php echo($LP['t02_fch_ter']);?></td>
				</tr>
				<tr>
					<th align="left">&nbsp;</th>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
			</table>

			<table width="75%" align="center" cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						<td width="3%" height="33" align="center" valign="top">&nbsp;</td>
						<td width="16%" align="center" valign="top">SIGLAS</td>
						<td width="55%" align="center" valign="top">NOMBRE DE LA
							INTITUCION FINANCIADORA</td>
						<td width="26%" align="center" valign="top">MONTO APORTADO</td>
					</tr>
				</thead>

				<tbody class="data" bgcolor="#FFFFFF">
    <?php
    
    $objFuentes = new BLFuentes();
    $rsFuentes = $objFuentes->ListadoFuentesFinan($idProy);
    $Index = 1;
    $total = 0;
    while ($row = mysqli_fetch_assoc($rsFuentes)) {
        $total += $row['monto_aportado'];
        ?>
    <tr style="font-size: 11px;">
						<td align="left" valign="top"><?php echo($Index);?>&nbsp;</td>
						<td align="left" valign="top"><?php echo($row['t01_sig_inst']);?>&nbsp;</td>
						<td align="left" valign="top"><?php echo($row['t01_nom_inst']);?>&nbsp;</td>
						<td align="center" valign="top"><div
								style="width: 70px;; text-align: right;"><?php echo(number_format($row['monto_aportado'],2));?></div>
						</td>
					</tr>
    <?php
        $Index ++;
    } // End While
    $rsFuentes->free();
    ?>
	<tr>
						<td align="center" valign="middle" colspan="3">Total</td>
						<td align="center" valign="middle"><div
								style="width: 70px;; text-align: right;"><?php echo(number_format($total,2));?></div></td>

					</tr>
				</tbody>
				<tfoot>
					<tr>
						<td align="left" valign="middle">&nbsp;</td>
						<td align="left" valign="middle">&nbsp;</td>
						<td align="left" valign="middle">&nbsp;</td>
						<td align="left" valign="middle">&nbsp;</td>

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