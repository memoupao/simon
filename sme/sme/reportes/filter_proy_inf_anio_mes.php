<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauser.inc.php"); ?>
<?php

require_once (constant('PATH_CLASS') . "BLProyecto.class.php");

$aTipoInf = $objFunc->__Request('tipInf');

switch ($aTipoInf) {
    case 'IM':
        $aTitle = "Búsqueda de Informes Mensuales";
        break;
    
    case 'IT':
        $aTitle = "Búsqueda de Informes Trimestrales";
        break;
    
    case 'IF':
        $aTitle = "Búsqueda de Informes Financieros";
        break;
    case 'ISE':
        $aTitle = "Búsqueda de Informes Entregables";
        break;
    default:
        $aTitle = "Búsqueda";
}

$objProy = new BLProyecto();

$ObjSession->VerifyProyecto();
$idProy = "";
$aProyect = null;
if ($ObjSession->CodProyecto != "" && $ObjSession->VerProyecto > 0) {
    $aProyect = $objProy->GetProyecto($ObjSession->CodProyecto, $ObjSession->VerProyecto);
}
?>

<?php if($idProy=='') { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/templateIframe.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $aTitle; ?></title>
<script language="javascript" type="text/javascript"
	src="../../jquery.ui-1.5.2/jquery-1.2.6.js"></script>
<link href="../../css/itemplate.css" rel="stylesheet" type="text/css"
	media="all" />
</head>

<body>
	<form id="frmMain" name="frmMain" method="post" action="#"
		accept-charset='UTF-8'>
 <?php } ?>
 

<div id="divBodyAjax" class="Filter">
			<fieldset id="proyecto" style="font-size: 10px;">
				<legend>Búsqueda de Informes</legend>
				<table width="100%" border="0" cellpadding="0" cellspacing="2"
					class="TableEditReg">
					<tr>
						<td width="105" height="21" align="left" valign="middle"><strong>Proyecto</strong>
						</td>
						<td colspan="2" align="left" valign="bottom" nowrap="nowrap"><input
							name="txtCodProy" type="text" id="txtCodProy" size="16"
							style="text-align: center;" readonly="readonly"
							value="<?php echo($aProyect['t02_cod_proy']);?>" /> <input
							type="image" name="btnBuscar" id="btnBuscar" border='0'
							src="../../img/gosearch.gif" width="14" height="15" class="Image"
							title="Seleccionar Proyectos" /> <input name="txtNomejecutor"
							type="text" readonly="readonly" id="txtNomejecutor" size="50"
							value="<?php echo( $aProyect['t01_nom_inst']);?>" /></td>
					</tr>
					<tr>
						<td height="35" align="left" valign="middle"><strong>Titulo del
								Proyecto</strong></td>
						<td colspan="2" align="left" valign="middle" nowrap="nowrap"><textarea
								name="txtNomproyecto" cols="70" rows="2" readonly="readonly"
								id="txtNomproyecto"><?php echo($aProyect['t02_nom_proy']);?></textarea>
						</td>
					</tr>
					<tr>
						<td height="23" valign="middle" nowrap="nowrap" align="left"><strong id="titInforme">Informe</strong>
						</td>
						<td width="226" align="left" valign="middle" nowrap="nowrap"><select
							name="cboInforme" id="cboInforme" style="width: 100px;" /></td>
						<td width="286" rowspan="2" align="middle" valign="middle"
							nowrap="nowrap"><span class="Button">
								<button onclick="NuevoReporte(); return false;" value="Nuevo">
									Ver Reporte</button>
						</span></td>
					</tr>
				</table>
			</fieldset>
			<input type='hidden' id='docpath'
				value='<?php echo(constant("DOCS_PATH"));?>' /> <input type="hidden"
				id="cboversion" value="<?php echo($aProyect['t02_version']);?>" /> <input
				type='hidden' id='tipoInf' value='<?php echo $aTipoInf; ?>' />
		</div>

<?php if($view=='') { ?>
</form>
</body>
</html>
<?php } ?>

<script type="text/javascript">
	$(document).ready(function(){
		$('#btnBuscar').click(function(pEvent){
			pEvent.stopPropagation();
			pEvent.preventDefault();
			selectProject();
		});
		<?php if ($aTipoInf!='IM') {?>
		loadYears();
		<?php } else { ?>
		$('#cboInforme, #titInforme').hide();
		<?php } ?>
	});
	
	function selectProject()
	{
		var arrayControls = ["ctrl_idinst=txtidInst", "ctrl_idproy=txtCodProy", 
							"ctrl_idversion=cboversion", "ctrl_ejecutor=txtNomejecutor", 
							"ctrl_proyecto=txtNomproyecto", "evaljs=LoadInformesFinanc"];
		var params = "?" + arrayControls.join("&"); 
		var sUrl = $('#docpath').val() + "sme/proyectos/datos/lista_proyectos.php" + params;
		var aPopup = window.open(sUrl, "BuscaProy", "width=603,height=400,menubar=no,scrollbars=yes,location=no,resizable=no,status=no", true);
		
		var aPopupWatcher = setInterval(function() {
			if (aPopup.closed) {
				loadYears();
				clearInterval(aPopupWatcher);
			}
		}, 200);
	}
	
	function loadYears()
	{
		var aSelect = $('#cboInforme').empty();
		var aTipInf = $('#tipoInf').val();
		var aAction = aTipInf == 'IM' ? 'infMensOpts' : 
										(aTipInf == 'IT' ? 'infTrimOpts' : 
															(aTipInf == 'IF' ? 'infFinOpts' : 
																	(aTipInf == 'ISE' ? 'infSupEntrOpts':'') ));
		var aUrl = '<?php echo(constant("PATH_SME")."reportes/filter_process_json.php");?>';
		var aParams = {'action': aAction, 'idProy':$('#txtCodProy').val(), 'idVersion': <?php echo $ObjSession->VerProyecto; ?> };
		$.getJSON(aUrl, aParams, function(pData){
			var aYear = null;
			var aOptsTxt = '';

			if (aTipInf == 'ISE') {
				$.each(pData, function(pIdx, pItem){
// 					if (aYear != pItem.anio) {
// 						if (aYear != null) aOptsTxt += "</optgroup>";
// 						aYear = pItem.anio;
// 						aOptsTxt += "<optgroup label='Año " + aYear + "'>";
// 					}
					aOptsTxt += buildOptionEntregable(pItem);
				});
				if (pData.length > 0) aOptsTxt += "</optgroup>";
				
				
			} else {
				$.each(pData, function(pIdx, pItem){
					if (aYear != pItem.anio) {
						if (aYear != null) aOptsTxt += "</optgroup>";
						aYear = pItem.anio;
						aOptsTxt += "<optgroup label='Año " + aYear + "'>";
					}
					aOptsTxt += buildOption(pItem);
				});
				if (pData.length > 0) aOptsTxt += "</optgroup>";
			}
			
			
			aSelect.append(aOptsTxt);
		});
		
		function buildOption(pItem)
		{
			var aOptTxt = "<option value='" + pItem.anio + "." +
							(pItem.mes ? pItem.mes : pItem.trim) + "." +
							pItem.verInf + "'>";
			if (pItem.mes) aOptTxt += "Mes " + pItem.mes;
			if (pItem.trim)aOptTxt += "Trim. " + pItem.trim;
			aOptTxt += "</option>";
			
			return aOptTxt;
		}

		function buildOptionEntregable(pItem)
		{			
			var aOptTxt = "<option value='" + pItem.anio + "." +
							(pItem.verInf) + "." +
							pItem.identregable + '.' +
							pItem.entregable + "'>";			
			if (pItem.entregable) aOptTxt += "Entregable " + pItem.entregable;
			aOptTxt += "</option>";
			
			return aOptTxt;
		}

		
	}
	
</script>

<script type="text/javascript">

function NuevoReporte()
{

	var aTipInf = $('#tipoInf').val();
	
	if ($('#txtCodProy').val() == '') {
		alert('Seleccione un Proyecto');
		return;
	}
	var aInfData = $('#cboInforme').val().split('.');

	if (aTipInf == 'ISE') {
		var aParams = ["idProy=" + $('#txtCodProy').val(), 
						 "idAnio=" + aInfData[0],						 						 						 
						 "idVersion=" + aInfData[1],
						 "idTrim=" + aInfData[2],
						 'numEntre='+aInfData[3]].join('&');
	} else {
		var aParams = ["idProy=" + $('#txtCodProy').val(), 
						 "idAnio=" + aInfData[0],
						 "idMes=" + aInfData[1],
						 "idTrim=" + aInfData[1],
						 "idVers=" + aInfData[2],
						 "idVersion=" + aInfData[2]].join('&');
	}
	
	
	var sID = "<?php echo($objFunc->__Request('ReportID'));?>" ;
	showReport(sID, aParams);
}

</script>
