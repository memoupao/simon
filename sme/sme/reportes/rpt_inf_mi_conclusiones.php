<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauser.inc.php"); ?>
<?php

require (constant("PATH_CLASS") . "BLProyecto.class.php");
require (constant("PATH_CLASS") . "BLInformes.class.php");

$idProy = $objFunc->__Request('idProy');
$idfecha = $objFunc->__Request('idFecha');

$objProy = new BLProyecto();
$ultima_vs = $objProy->MaxVersion($idProy);
$Proy_Datos_Bas = $objProy->GetProyecto($idProy, $ultima_vs);

$objInf = new BLInformes();
$rowInf = $objInf->InformeMISeleccionarFecha($idProy, $idfecha);
$row = $objInf->InformeMISeleccionar($idProy, $rowInf['t45_id'], $rowInf['t45_ver_inf']);

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
			<style>
.Resumen {
	font-size: 10px;
}
</style>
			<fieldset class="Resumen">
				<legend style="color: #2A3F55;">Datos del Proyecto</legend>
				<table width="100%" border="0" cellspacing="1" cellpadding="0"
					class="Resumen">
					<tr>
						<td width="7%" class="ClassField">CODIGO</td>
						<td width="93%" class="ClassText"><?php echo($Proy_Datos_Bas['t02_cod_proy']);?></td>
					</tr>
					<tr>
						<td class="ClassField">DESCRIPCION</td>
						<td class="ClassText"><?php echo($Proy_Datos_Bas['t02_nom_proy']);?></td>
					</tr>
					<tr>
						<td class="ClassField">PERIODO</td>
						<td class="ClassText"><?php echo($Proy_Datos_Bas['t02_fch_ini']);?> - <?php echo($Proy_Datos_Bas['t02_fch_ter']);?></td>
					</tr>
				</table>

			</fieldset>
			<br />
			<table width="750" cellpadding="0" cellspacing="0"
				style="color: black;">
				<thead>
				</thead>
				<tbody class="data" bgcolor="#FFFFFF">
					<tr>
						<td align="left" valign="middle"><span class="ClassField">FECHA
								PRESENTACION DEL INFORME: </span> <span class="ClassText"><?php echo($row['t45_fch_pre']);?></span></td>
					</tr>
					<tr>
						<td align="left" valign="middle"><span class="ClassField">Logros</span><br />
							<div class="ClassText"
								style="border: 1px solid #A0A0A4; padding: 5px;"><?php echo( nl2br($row['t45_logros']));?> </div>
						</td>
					</tr>
					<tr>
						<td align="left" valign="middle"><span class="ClassField">Dificultades</span><br />
							<div class="ClassText"
								style="border: 1px solid #A0A0A4; padding: 5px;"><?php echo( nl2br($row['t45_dificul']));?> </div>
						</td>
					</tr>
					<tr>
						<td align="left" valign="middle"><span class="ClassField">Recomendaciones
								al Proyecto</span> <br />
							<div class="ClassText"
								style="border: 1px solid #A0A0A4; padding: 5px;"><?php echo( nl2br($row['t45_reco_proy']));?> </div>
						</td>
					</tr>
					<tr>
						<td align="left" valign="middle"><span class="ClassField">Recomendaciones
								a Fondoempleo</span> <br />
							<div class="ClassText"
								style="border: 1px solid #A0A0A4; padding: 5px;"><?php echo( nl2br($row['t45_reco_fe']));?> </div>
						</td>
					</tr>
					<tr>
						<td align="left" valign="middle"><span class="ClassField">Calificación</span>
							<br />
							<div class="ClassText"
								style="border: 1px solid #A0A0A4; padding: 5px;"><?php echo( nl2br($row['t45_califica']));?></div></td>
					</tr>
				</tbody>
				<tfoot>
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