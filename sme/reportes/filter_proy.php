<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauser.inc.php"); ?>
<?php

$NoInclude = true;

require (constant('PATH_CLASS') . "BLProyecto.class.php");
$objProy = new BLProyecto();

$ObjSession->VerifyProyecto();

$row = 0;
if ($ObjSession->CodProyecto != "" && $ObjSession->VerProyecto > 0) {
    $row = $objProy->GetProyecto($ObjSession->CodProyecto, $ObjSession->VerProyecto);
}

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

.Filter input,textarea {
	font-size: 11px;
	color: navy;
	font-weight: normal;
	border: 1px solid #2A3F00;
}

.Filter input:focus,textarea:focus {
	background-color: #FCFDD5;
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
				<table width="99%" border="0" cellpadding="0" cellspacing="2">
					<tr>
						<td width="73" align="left" valign="middle" nowrap="nowrap"
							class="Field">Codigo</td>
						<td width="106" align="left" valign="middle" nowrap="nowrap"><input
							name="txtCodProy" type="text" id="txtCodProy" size="16"
							style="text-align: center;" readonly="readonly"
							value="<?php echo($row['t02_cod_proy']);?>" class="filterparams" /></td>
						<td width="23" align="left" valign="middle" nowrap="nowrap"><input
							type="image" name="btnBuscar" id="btnBuscar"
							src="../../img/gosearch.gif" width="14" height="15" class="Image"
							onclick="Buscarproyectos(); return false;"
							title="Seleccionar Proyectos" /></td>
						<td width="181" align="left" valign="middle" nowrap="nowrap">&nbsp;</td>
						<td width="407" rowspan="2" align="left" valign="middle"
							nowrap="nowrap"><button class="Button"
								onclick="NuevoReporte(); return false;" value="Nuevo">Buscar</button></td>
						<td width="416" align="left" valign="middle" nowrap="nowrap">&nbsp;</td>
					</tr>
					<tr>
						<td width="73" align="left" valign="middle" nowrap="nowrap"
							class="Field">Ejecutor</td>
						<td colspan="3" align="left" valign="middle" nowrap="nowrap"><input
							name="txtNomejecutor" type="text" readonly="readonly"
							id="txtNomejecutor" size="50"
							value="<?php echo( $row['t01_nom_inst']);?>" class="filterparams" /></td>
						<td align="left" valign="middle" nowrap="nowrap">&nbsp;</td>
					</tr>
					<tr>
						<td align="left" valign="middle" class="Field">Titulo del Proyecto</td>
						<td colspan="5" align="left" valign="middle" nowrap="nowrap"><textarea
								name="txtNomproyecto" cols="70" rows="2" readonly="readonly"
								id="txtNomproyecto" class="filterparams"><?php echo($row['t02_nom_proy']);?></textarea>
							<input name="cboversion" type="hidden" id="cboversion"
							value="<?php echo($row['t02_version']);?>" /> <input
							name="txtidInst" type="hidden" id="txtidInst"
							value="<?php echo($row['t01_id_inst']);?>" /></td>
					</tr>
				</table>
			</fieldset>

			<script language="JavaScript" type="text/javascript">
function Buscarproyectos()
{
	
	var pagina = document.URL;
	var viewer = "reportviewer.php";
	var ret = pagina.search(viewer);
	
	var arrayControls = new Array();
		arrayControls[0] = "ctrl_idinst=txtidInst";
		arrayControls[1] = "ctrl_idproy=txtCodProy";
		arrayControls[2] = "ctrl_idversion=cboversion";
		arrayControls[3] = "ctrl_ejecutor=txtNomejecutor";
		arrayControls[4] = "ctrl_proyecto=txtNomproyecto";
		
	var params = "?" + arrayControls.join("&"); 
	var sUrl = "<?php echo(constant("DOCS_PATH"));?>sme/proyectos/datos/lista_proyectos.php" + params;
	
	if(ret < 0)
	{
		window.open(sUrl,"BuscaProy", "width=603, height=400,menubar=no, scrollbars=yes, location=no, resizable=no, status=no",true);
	}
	else
	{
		ChangeStylePopup("PopupDialog");
		spryPopupFilter.displayPopupDialog(true);
		$('#spanTitle').html('Busqueda de Proyectos');
		if($('#divPopupText').html()=='')
		{
    		$('#divPopupText').html($('#divCargando').html());
			var sUrl = "<?php echo(constant("DOCS_PATH"));?>sme/proyectos/datos/lista_proyectos.php" + params;
			var vhtml="<iframe id='ifrmBuscarproy' src='"+sUrl+"' style='width:100%; height:440px;' frameborder='0' scrollbars='0'></iframe>";
			$('#divPopupText').html(vhtml);
		}
    	//var req = Spry.Utils.loadURL("POST", url, true, SuccessLoadFilter, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoadFilter });
	}
}

function HideBusqueda()
{
	spryPopupFilter.displayPopupDialog(false);
	NuevoReporte();
}

function NuevoReporte()
{
	/*
	var params = $(".filterparams").serialize();
	var sID = "<?php echo($objFunc->__Request('ReportID'));?>" ;
	showReport(sID, params);
	*/
	if($('#txtCodProy').val()=='')
	{
		alert("Seleccione un Proyecto");
		return ;
	}
	var arrayControls = new Array();
		arrayControls[0] = "idProy=" + $('#txtCodProy').val();
		arrayControls[1] = "idVersion=" + $('#cboversion').val();
	var params = arrayControls.join("&"); 
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