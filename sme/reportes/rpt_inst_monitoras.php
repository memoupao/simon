<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauser.inc.php"); ?>
<?php

require_once (constant("PATH_CLASS") . "BLReportes.class.php");

$idProy = $objFunc->__Request('idProy');
$idInst = $objFunc->oSession->IdInstitucion;

$ls_filter = "";

?>


<?php if($objFunc->__QueryString()=="") { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/tempReports.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Directorio de Instituciones</title>
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
			<table width="896" border="0" align="center" cellpadding="0"
				cellspacing="1" class="TableGrid">
				<tr>
					<th width="18%" align="left">&nbsp;</th>
					<td width="55%" align="left"><?php echo($ls_filter);?></td>
					<th width="7%" align="left" nowrap="nowrap">&nbsp;</th>
					<td width="20%" align="left">&nbsp;</td>
				</tr>
			</table>

			<table width="896" align="center" cellpadding="0" cellspacing="0">
				<tbody class="data" bgcolor="#FFFFFF">
    
       
	<?php
$objRep = new BLReportes();
$rsInst = $objRep->InstitucionesMonitorasRepMonitor($idInst);

$Index = 1;

while ($row = mysqli_fetch_assoc($rsInst)) {
    if ($inst != $row['t01_sig_inst']) {
        $flag = 1;
    }
    
    if ($flag == 1) {
        $flag = 0;
        $inst = $row['t01_sig_inst'];
        ?>
		<tr bgcolor="#CCCCFF">
						<td colspan="5" align="left" valign="top">
							<table width="889" cellpadding="0" cellspacing="0"
								style="border: hidden">
								<tr>
									<td style="border: hidden" width="464" align="left"
										valign="top"><strong style="color: blue;">Siglas:</strong> <?php echo($row['t01_sig_inst']);?></td>
									<td style="border: hidden" width="413" align="left"
										valign="top"><strong style="color: blue;">Tipo de
											Institución: </strong> <?php echo($row['institucion']);?></td>
								</tr>
								<tr>
									<td width="464" rowspan="2" align="left" valign="top"
										style="border: hidden"><strong style="color: blue;">Institución:
									</strong><?php echo($row['t01_nom_inst']);?></td>
									<td style="border: hidden"><strong style="color: blue;">Dirección:</strong> <?php echo($row['t01_dire_inst']);?></td>
								</tr>
								<tr>
									<td style="border: hidden" align="left" valign="top"><strong
										style="color: blue;">RUC: </strong><?php echo($row['t01_ruc_inst']);?></td>
								</tr>
							</table>
						</td>
					</tr>
					<tr style="color: #575757; background-color: #EAEAEA;">
						<td width="20%" colspan="-1" align="center" valign="middle">Contacto</td>
						<td height="33" colspan="2" align="center" valign="middle">Código
							del Proyecto</td>
						<td width="50%" colspan="-1" align="center" valign="middle">Nombre
							del Proyecto</td>
						<td width="14%" colspan="-1" align="center" valign="middle">Presupuesto
							Monitoreado</td>
					</tr>		
		<?php
    }
    ?>
 	  
        <tr style="font-size: 11px;">
						<td align="center" valign="top"><?php echo($row['nombres']);?></td>
						<td colspan="2" align="center" valign="top"><?php echo($row['t02_cod_proy']);?></td>
						<td align="left" valign="top"><?php echo($row['t02_nom_proy']);?></td>
						<td align="center" valign="top"><?php echo($row['presupuesto']);?></td>
					</tr>
					<tr style="font-size: 11px;">

					</tr>
      
    <?php
    $Index ++;
} // End While
$rsInst->free();
?>
	
  </tbody>
				<tfoot>
					<tr>
						<td width="3%" height="15" align="left" valign="middle">&nbsp;</td>
						<td width="13%" colspan="-1" align="left" valign="middle">&nbsp;</td>
						<td colspan="-1" align="left" valign="middle">&nbsp;</td>
						<td colspan="-1" align="left" valign="middle">&nbsp;</td>
						<td colspan="-1" align="left" valign="middle">&nbsp;</td>
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