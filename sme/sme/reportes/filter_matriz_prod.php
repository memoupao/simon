<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauser.inc.php"); ?>
<?php

$NoInclude = true;
require (constant("PATH_CLASS") . "BLTablasAux.class.php");
$objTablas = new BLTablasAux();
// $objRep = new BlReportes
?>
<?php if(!$NoInclude) { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/tempFilterReports.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>REPORTES</title>
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
<!-- InstanceBeginEditable name="BodyAjax" -->
		<style>
.Filter {
	font-size: 10px;
	font: Arial, Helvetica, sans-serif;
	padding: 3px;
	overflow: hidden;
}

.Filter fieldset {
	border: 1px solid #2A3F55;
}

.Filter fieldset legend {
	color: #2A3F55;
	font-weight: bold;
}

.Filter select {
	font-size: 10px;
	color: navy;
	font-weight: normal;
}

.Filter .Field {
	color: #000080;
	font-weight: bold;
	font-size: 11px;
	padding: 4px;
}

.Filter .Button {
	padding: 2px;
	padding-bottom: 3px;
	padding-top: 3px;
	padding-left: 8px;
	padding-right: 8px;
	background-color: #EEE;
	border: solid 1px #999;
	cursor: pointer;
}
</style>
		<div id="divBodyAjax" class="Filter">
			<fieldset>
				<legend>Busqueda de Proyectos</legend>
				<table width="90%" cellpadding="0" cellspacing="0">
					<thead>
					</thead>
					<tbody>
						<tr>
							<td width="132" align="left" valign="middle" class="Field">Concurso<br /></td>
							<td width="140" align="left" valign="middle"><select
								name="cboConcurso" id="cboConcurso" style="width: 60px;"
								class="filterparams">
									<option value="*" selected="selected">Todos</option>
					<?php
    $rs = $objTablas->ListaConcursosActivos();
    $objFunc->llenarComboI($rs, 'codigo', 'abreviatura', $objFunc->__Request("cboConcurso"));
    ?>
				</select></td>
							<td width="12" align="left" valign="middle">&nbsp;</td>
							<td width="109" align="left" valign="middle" class="Field">Región</td>
							<td width="130" align="left" valign="middle"><select
								name="cboRegion" id="cboRegion" style="width: 130px;"
								class="filterparams">
									<option value="*">Todos</option>
					<?php
    $rs = $objTablas->ListaDepartamentos();
    $objFunc->llenarComboI($rs, 'codigo', 'descripcion', $objFunc->__Request("cboRegion"));
    ?>
				</select></td>
							<td width="86" align="left" valign="middle">&nbsp;</td>
							<td width="153" rowspan="2" valign="middle">
								<button class="Button" onclick="NuevoReporte(); return false;"
									value="Nuevo">Buscar</button>
							</td>
							<td width="345" align="left" valign="middle">&nbsp;</td>
						</tr>
						<tr>
							<td align="left" valign="middle" nowrap="nowrap" class="Field">Sector
								Productivo<br />
							</td>
							<td align="left" valign="middle"><select name="cboSectorProd"
								id="cboSectorProd" style="width: 140px;" class="filterparams">
									<option value="*">Todos</option>
					<?php
    $rs = $objTablas->SectoresProductivos();
    $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $objFunc->__Request("cboSectorProd"));
    ?>
				</select></td>
							<td align="left" valign="middle">&nbsp;</td>
							<td align="left" valign="middle" nowrap="nowrap" class="Field">Tipo
								Intitución</td>
							<td align="left" valign="middle"><select name="cboTipoInst"
								id="cboTipoInst" style="width: 130px;" class="filterparams">
									<option value="*">Todos</option>
					<?php
    $rs = $objTablas->TipoUnidades();
    $objFunc->llenarCombo($rs, 'codigo', 'abreviado', $objFunc->__Request("cboTipoInst"));
    ?>
				</select></td>
							<td align="left" valign="middle">&nbsp;</td>
							<td align="left" valign="middle">&nbsp;</td>
						</tr>
						<tr>
							<td align="left" valign="middle" nowrap="nowrap" class="Field">Estado
								Proyecto</td>
							<td align="left" valign="middle"><select name="cboEstado"
								id="cboEstado" style="width: 140px;" class="filterparams">
									<option value="*">Todos</option>
					<?php
    $rs = $objTablas->ListaEstados();
    $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $objFunc->__Request("cboEstado"));
    ?>
				</select></td>
							<td align="left" valign="middle" colspan='5'>&nbsp;</td>
						</tr>
					</tbody>
					<tfoot>
					</tfoot>
				</table>
			</fieldset>

			<script language="JavaScript" type="text/javascript">
function NuevoReporte()
{
	var params = $(".filterparams").serialize();
	var sID = "<?php echo($objFunc->__Request('ReportID'));?>" ;
	showReport(sID, params);
}
</script>
		</div>
		<!-- InstanceEndEditable -->
<?php if(!$NoInclude) { ?>
</form>
</body>
<!-- InstanceEnd -->
</html>
<?php } ?>