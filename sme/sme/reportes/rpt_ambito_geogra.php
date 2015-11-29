<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauser.inc.php"); ?>
<?php

require_once (constant("PATH_CLASS") . "BLProyecto.class.php");
$idProy = $objFunc->__Request('idProy');
$objProy = new BLProyecto();
$ls_version = $objProy->MaxVersion($idProy);
$Proy = $objProy->GetProyecto($idProy, $ls_version);
?>


<?php  if($idProy=='') { ?>
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
			<table width="90%" border="0" align="center" cellpadding="0"
				cellspacing="1" class="TableGrid">
				<tr>
					<th width="8%" align="left">PROYECTO</th>
					<td width="65%" align="left"><?php  echo($Proy['t02_cod_proy']);?></td>
					<th width="7%" align="left" nowrap="nowrap">INICIO:</th>
					<td width="20%" align="left"><?php echo($Proy['t02_fch_ini']);?></td>
				</tr>
				<tr>
					<th align="left" nowrap="nowrap">&nbsp;</th>
					<td align="left"><?php  echo($Proy['t02_nom_proy']);?></td>
					<th align="left" nowrap="nowrap">TéRMINO:</th>
					<td align="left"><?php echo($Proy['t02_fch_ter']);?></td>
				</tr>
				<tr>
					<th align="left">&nbsp;</th>
					<td align="left"><?php echo($Proy['t01_sig_inst']." - ".$Proy['t01_nom_inst']);?></td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
			</table>

			<table width="90%" align="center" cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						<td width="21%" height="33" align="center" valign="top">Departamento</td>
						<td width="17%" align="center" valign="top">Provincia</td>
						<td width="30%" align="center" valign="top">Distrito</td>
						<td width="30%" align="center" valign="top">Observaciones</td>
					</tr>
				</thead>

				<tbody class="data" bgcolor="#FFFFFF">
    <?php
    $rsAmbitoGeo = $objProy->AmbitoGeo_Listado($idProy, $ls_version);
    
    $Index = 1;
    while ($row = mysqli_fetch_assoc($rsAmbitoGeo)) {
        
        ?>
    <tr style="font-size: 11px;">
						<td align="left" valign="top"><?php echo($row['dpto']);?></td>
						<td align="left" valign="top"><?php echo($row['prov']);?></td>
						<td align="left" valign="top"><?php echo($row['dist']);?></td>
						<td align="left" valign="top"><?php echo($row['t03_obs']);?></td>
					</tr>
    <?php
        $Index ++;
    } // /End While
    $rsAmbitoGeo->free();
    ?>
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
<?php  } ?>