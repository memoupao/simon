<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauser.inc.php"); ?>
<?php

require_once (constant("PATH_CLASS") . "BLEjecutor.class.php");
// require_once(constant("PATH_CLASS")."BLBene.class.php");

$id = $objFunc->__Request('idME');
$mat = explode('.', $id);

$idInst = $mat[0];
$idME = $mat[1];

$objEjec = new BLEjecutor();
$ME = $objEjec->ContactosSeleccionar($idInst, $idME);

$Inst = $objEjec->GetEjecutor($idInst);

?>


<?php  if($id=='') { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/tempReports.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Monitor Externo</title>
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
			<table width="70%" border="0" align="center" cellpadding="0"
				cellspacing="1" class="TableGrid">
				<tr>
					<th width="13%" align="left">INSTITUCIóN</th>
					<td width="60%" align="left"><?php echo($Inst['t01_sig_inst']);?></td>
					<th width="7%" align="left" nowrap="nowrap">&nbsp;</th>
					<td width="20%" align="left">&nbsp;</td>
				</tr>
				<tr>
					<th align="left" nowrap="nowrap">&nbsp;</th>
					<td align="left"><?php echo($Inst['t01_nom_inst']);?></td>
					<th align="left" nowrap="nowrap">&nbsp;</th>
					<td align="left">&nbsp;</td>
				</tr>
				<tr>
					<th align="left">&nbsp;</th>
					<td align="left"><?php echo($Inst['t01_dire_inst']." ".$Inst['t01_ciud_inst']."<br>Teléfono:".$Inst['t01_fono_inst']."<br>Fax:".$Inst['t01_fax_inst']);?></td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
			</table>

			<table width="70%" align="center" cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						<td width="8%" height="33" align="center" valign="top">DNI</td>
						<td width="28%" align="center" valign="top">Apellidos y Nombres</td>
						<td width="20%" align="center" valign="top">Teléfono</td>
						<td width="21%" align="center" valign="top">Celular</td>
						<td width="23%" align="center" valign="top">Mail</td>
					</tr>
				</thead>

				<tbody class="data" bgcolor="#FFFFFF">
    <?php
    /*
     * $objBenef = new BLBene(); $rsBenef = $objBenef->ListadoBeneficiarios($idProy); $Index = 1 ; while($row = mysqli_fetch_assoc($rsBenef)) {
     */
    ?>
    <tr style="font-size: 11px;">
						<td align="left" valign="top"><?php echo($ME['t01_dni_cto']);?></td>
						<td align="left" valign="top"><?php echo($ME['t01_ape_pat']." ".$ME['t01_ape_mat'].", ".$ME['t01_nom_cto']);?></td>
						<td align="left" valign="top"><?php echo($ME['t01_fono_ofi']);?></td>
						<td align="left" valign="top"><?php echo($ME['t01_cel_cto']);?></td>
						<td align="left" valign="top"><?php echo($ME['t01_mail_cto']);?></td>
					</tr>
    <?php 
/*
           * $Index++; }//End While $rsBenef->free();
           */
    ?>
  </tbody>
				<tfoot>
					<tr>
						<td align="left" valign="middle">&nbsp;</td>
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
<?php  } ?>