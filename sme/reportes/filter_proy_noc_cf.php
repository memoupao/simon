<?php
include ("../../includes/constantes.inc.php");
include ("../../includes/validauser.inc.php");
require_once (constant('PATH_CLASS') . "BLProyecto.class.php");

$srchType = $objFunc->__Request('tip');

switch ($srchType) {
    case 'noc':
        $idElem = $objFunc->__POST('idNOC');
        $aTitle = 'Búsqueda de Solicitudes de Compra';
        $aLabel = 'Solicitud de Compra';
        $aDdWidth = '50px;';
        break;
    
    case 'cf':
        $idElem = $objFunc->__POST('idCF');
        $aTitle = 'Búsqueda de Carta Fianza';
        $aLabel = 'Carta Fianza';
        $aDdWidth = '350px;';
        break;
    case 'cp':
        $idElem = $objFunc->__POST('idCP');
        $aTitle = 'Búsqueda de Solicitudes de Cambio de Personal';
        $aLabel = 'Solicitud Nro.';
        $aDdWidth = '50px;';
        break;
}

$objProy = new BLProyecto();
$ObjSession->VerifyProyecto();
$aProyect = null;
if ($ObjSession->CodProyecto != "" && $ObjSession->VerProyecto > 0) {
    $aProyect = $objProy->GetProyecto($ObjSession->CodProyecto, $ObjSession->VerProyecto);
}
?>

<div id="divBodyAjax" class="Filter">
	<fieldset id="proyecto" style="font-size: 10px;">
		<legend><?php echo $aTitle; ?></legend>
		<table border="0" cellpadding="0" cellspacing="2" class="TableEditReg">
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
				<td height="23" valign="middle" nowrap="nowrap" align="left"><strong><?php echo $aLabel; ?></strong>
				</td>
				<td width="226" align="left" valign="middle" nowrap="nowrap"><select name="cboNum" id="cboNum" style="width:<?php echo $aDdWidth; ?>"/>
				</td>
				<td width="286" rowspan="2" align="right" valign="left"
					nowrap="nowrap"><span class="Button">
						<button onclick="NuevoReporte(); return false;" value="Nuevo"
							id='runReportBtn'>Ver Reporte</button>
				</span></td>
			</tr>
		</table>
	</fieldset>
	<input type='hidden' id='docpath'
		value='<?php echo(constant("DOCS_PATH"));?>' /> <input type="hidden"
		id="cboversion" value="<?php echo($aProyect['t02_version']);?>" /> <input
		type='hidden' id='numTxt' value='<?php echo $idElem; ?>' /> <input
		type='hidden' id='srchTyp' value='<?php echo $srchType; ?>' />
</div>

<script type="text/javascript">

	$(document).ready(function(){
		$('#btnBuscar').click(function(pEvent){
			pEvent.stopPropagation();
			pEvent.preventDefault();
			selectProject();
		});
		cargaDropdown();
	});
	
	function selectProject()
	{
		var arrayControls = ["ctrl_idinst=txtidInst", "ctrl_idproy=txtCodProy", 
							"ctrl_idversion=cboversion", "ctrl_ejecutor=txtNomejecutor", 
							"ctrl_proyecto=txtNomproyecto"];
		var params = "?" + arrayControls.join("&"); 
		var sUrl = $('#docpath').val() + "sme/proyectos/datos/lista_proyectos.php" + params;
		var aPopup = window.open(sUrl, "BuscaProy", "width=603,height=400,menubar=no,scrollbars=yes,location=no,resizable=no,status=no", true);
		
		var aPopupWatcher = setInterval(function() {
			if (aPopup.closed) {
				cargaDropdown();
				clearInterval(aPopupWatcher);
			}
		}, 200);
	}
	
	function cargaDropdown()
	{
		if ($('#txtCodProy').val().trim() == '') {
			$('#runReportBtn').hide();
			return;
		}
		var aSelect = $('#cboNum').empty();
		var aUrl = '<?php echo(constant("PATH_SME")."reportes/filter_process_json.php");?>';
		var aParams = {'action':$('#srchTyp').val(), 'idProy':$('#txtCodProy').val()};
		
		$.getJSON(aUrl, aParams, function(pData){
			var aOptsTxt = '<option value=""> Todos </option>';
			$.each(pData, function(pIdx, pItem){
				aOptsTxt += "<option value='" + pIdx + "'>" + pItem + "</option>";
			})
			if (aOptsTxt != '') {
				aSelect.append(aOptsTxt);
				$('#runReportBtn').show('fast');
				var aNum = $('#numTxt').val().trim();
				$('#cboNum').val( aNum ? aNum : '1' );
			}
			else {
				$('#runReportBtn').hide('fast');
			}
		});
		
	}

	function NuevoReporte()
	{
		if ($('#txtCodProy').val() == '') {
			alert('Seleccione un Proyecto');
			return;
		}
		var aParams = ["idProy=" + $('#txtCodProy').val(), 
					   "idNOC=" + $('#cboNum').val(),
					   "idCP=" + $('#cboNum').val()].join('&');
		var sID = "<?php echo($objFunc->__Request('ReportID'));?>" ;
		showReport(sID, aParams);
	}

</script>
