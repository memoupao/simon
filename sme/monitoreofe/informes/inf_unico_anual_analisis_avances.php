<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>

<?php
require (constant("PATH_CLASS") . "BLInformes.class.php");
require_once (constant("PATH_CLASS") . "HardCode.class.php");

$objHC = new HardCode();

error_reporting("E_PARSE");
$idProy = $objFunc->__POST('idProy');
$idVersion = $objFunc->__POST('t55_ver_inf');
$idNum = $objFunc->__POST('idNum');
$idAnio = $objFunc->__POST('idAnio');

if ($idProy == "" && $idNum == "") {
    $idProy = $objFunc->__GET('idProy');
    $idVersion = $objFunc->__GET('t55_ver_inf');
    $idNum = $objFunc->__GET('idNum');
}

if ($idProy == "") {
    ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/templateIframe.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->

<title>Analisis de Avances</title>
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->
<script language="javascript" type="text/javascript"
	src="../../../jquery.ui-1.5.2/jquery-1.2.6.js"></script>
<!-- InstanceEndEditable -->
<link href="../../../css/itemplate.css" rel="stylesheet" type="text/css"
	media="all" />
</head>

<body>
	<!-- InstanceBeginEditable name="tBody" -->
	<form id="frmMain" name="frmMain" method="post" action="#">
<?php
}
?>
<?php

$objInf = new BLInformes();

$row = $objInf->Inf_UA_Seleccionar($idProy, $idNum);

?>
<table width="750" border="0" cellspacing="1" cellpadding="0">
			<tr>
				<td width="40%" class="TableEditReg">&nbsp;</td>
				<td>Terminado MT<input
					<?php if($ObjSession->PerfilID != $objHC->MT ){ echo 'disabled'; } ?>
					id="t55_mt" type="checkbox" value="1"
					<?php if($row['t55_mt']=='1'){echo('checked="checked"');} ?>
					name="t55_mt" class="AnalisisAvances"></br> Terminado MF<input
					<?php if($ObjSession->PerfilID != $objHC->MF ){ echo 'disabled'; } ?>
					id="t55_mf" type="checkbox" value="1"
					<?php if($row['t55_mf']=='1'){echo('checked="checked"');} ?>
					name="t55_mf" class="AnalisisAvances">
				</td>
				<td width="8%" rowspan="2" align="center" class="TableEditReg"><input
					type="button" value="Refrescar" class="btn_save_custom"
					title="Refrescar datos de Analisis de Avances"
					onclick="LoadAnalisisAvanc(true);" /></td>
				<td width="10%" rowspan="2" align="right" valign="middle"><input
					id='gdrAnalisisAvancBtn' type="button" value="Guardar"
					class="btn_save_custom" title="Guardar Problemas y Soluciones"
					onclick="Guardar_AnalisisAvances();" /></td>
			</tr>
			<tr>
				<td><span style="font-weight: bold;">Análisis de Avances del
						Proyecto</span></td>
			</tr>
		</table>
		<div id="divTableLista" class="TableGrid">
	<?php
$aDisTecTxt1 = $ObjSession->PerfilID != $objHC->MT && $ObjSession->PerfilID != $objHC->CMT && $ObjSession->PerfilID != $objHC->Admin ? 'disabled' : '';
$aDisFinTxt1 = $ObjSession->PerfilID != $objHC->MF && $ObjSession->PerfilID != $objHC->CMF && $ObjSession->PerfilID != $objHC->Admin ? 'disabled' : '';
?>
  <table width="750" cellpadding="0" cellspacing="0">
				<thead>
				</thead>
				<tbody class="data" bgcolor="#FFFFFF">
					<tr>
						<td align="left" valign="middle"><span
							style="font-weight: bold; font-size: 12px;">Análisis de Avances
								en relación a los componentes </span> <br> <textarea
								name="t55_avance_comp" rows="9" id="t55_avance_comp"
								style="padding: 0px; width: 100%;" class="AnalisisAvances"
								<?php echo $aDisTecTxt1; ?>><?php echo($row['t55_avan_comp']);?></textarea></td>
					</tr>
					<tr>
						<td align="left" valign="middle"><span
							style="font-weight: bold; font-size: 12px;">Análisis de Avances
								en Capacitación, Asistencia Técnica y Otros Servicios a
								Beneficiarios</span><br> <textarea name="t55_avance_cap"
								rows="9" id="t55_avance_cap" style="padding: 0px; width: 100%;"
								class="AnalisisAvances" <?php echo $aDisTecTxt1; ?>><?php echo($row['t55_avan_cap']);?></textarea></td>
					</tr>
					<tr>
						<td align="left" valign="middle"><span
							style="font-weight: bold; font-size: 12px;">Análisis de Avance
								financiero </span><br> <textarea name="t55_avance_fin" rows="9"
								id="t55_avance_fin" style="padding: 0px; width: 100%;"
								class="AnalisisAvances" <?php echo $aDisFinTxt1; ?>><?php echo($row['t55_avan_fin']);?></textarea></td>
					</tr>
				</tbody>
				<tfoot>
				</tfoot>
			</table>
			<input type="hidden" name="t02_cod_proy"
				value="<?php echo($idProy);?>" class="AnalisisAvances" /> <input
				type="hidden" name="t55_id" value="<?php echo($idNum);?>"
				class="AnalisisAvances" /> <input type="hidden" name="t55_ver_inf"
				value="<?php echo($idVersion);?>" class="AnalisisAvances" /> <input
				type='hidden' id='isMt1'
				value=<?php echo (!$aDisTecTxt1 ? '1' : '0'); ?> /> <input
				type='hidden' id='isMf1'
				value=<?php echo (!$aDisFinTxt1 ? '1' : '0'); ?> />

			<script language="javascript" type="text/javascript">

	$(document).ready(function(){
		checkInfStatus();
		checkMTAccess();
		checkMFAccess();
	});
	
	function checkInfStatus()
	{
		$('#t55_estado').val('<?php echo $row['t55_estado']; ?>');
		if ($('#t55_estado').val() == '<?php echo $objHC->EstInf_Rev; ?>')
			$('#revisionBtn').hide();
	}
	
	function checkMTAccess()
	{
		var aUsrPrf = $('#usrPrf').val();
		var aMtPrf  = '<?php echo $objHC->MT;?>';
		var aCmtPrf  = '<?php echo $objHC->CMT;?>';
		
		if ((aUsrPrf == aMtPrf && $('#t55_mt').attr('checked') == '1') ||
			(aUsrPrf == aCmtPrf && $('#t55_cmt_flg').val() == '1'))
			$('#t55_avance_comp, #t55_avance_cap, #t55_mt, #gdrAnalisisAvancBtn').attr('disabled', 'disabled');
		if (aUsrPrf == aCmtPrf && $('#t55_cmt_flg').val() != '1')
			$('#t55_mt, #gdrAnalisisAvancBtn').removeAttr("disabled");
	}

	function checkMFAccess()
	{
		var aUsrPrf = $('#usrPrf').val();
		var aMfPrf  = '<?php echo $objHC->MF;?>';
		var aCmfPrf  = '<?php echo $objHC->CMF;?>';
		
		if ((aUsrPrf == aMfPrf && $('#t55_mf').attr('checked') == '1') ||
			(aUsrPrf == aCmfPrf && $('#t55_cmf_flg').val() == '1'))
			$('#t55_avance_fin, #t55_mf, #gdrAnalisisAvancBtn').attr('disabled', 'disabled');
		if (aUsrPrf == aCmfPrf && $('#t55_cmf_flg').val() != '1')
			$('#t55_mf, #gdrAnalisisAvancBtn').removeAttr("disabled");
	}


function Guardar_AnalisisAvances()
{
<?php $ObjSession->AuthorizedPage(); ?>	

	var BodyForm=$("#FormData .AnalisisAvances").serialize();
	
	if ($('#isMt1').val() == '1') {
		BodyForm += '&t55_avance_fin=' + $('#t55_avance_fin').val();
		BodyForm += '&t55_mf=' + ($('#t55_mf').attr('checked') ? 1 : 0);
		if ($('#t55_mt').attr('disabled'))
			BodyForm += '&t55_mt=' + ($('#t55_mt').attr('checked') ? 1 : 0);
	}
	if ($('#isMf1').val() == '1') {
		BodyForm += '&t55_avance_comp=' + $('#t55_avance_comp').val();
		BodyForm += '&t55_avance_cap=' + $('#t55_avance_cap').val();
		BodyForm += '&t55_mt=' + ($('#t55_mt').attr('checked') ? 1 : 0);
		if ($('#t55_mf').attr('disabled'))
			BodyForm += '&t55_mf=' + ($('#t55_mf').attr('checked') ? 1 : 0);
	}
	
	if (($('#t55_mt').attr('checked') == 1 && $('#t55_mf').attr('checked') == 1) && 
		($('#t55_estado').val() == '<?php echo $objHC->EstInf_Ela; ?>' || 
		 $('#t55_estado').val() == '<?php echo $objHC->EstInf_Corr; ?>')) {
		BodyForm += '&t55_estado=' + '<?php echo $objHC->EstInf_Rev; ?>';
	}
	
	if ($('#t55_estado').val() == '<?php echo $objHC->EstInf_Rev; ?>' && 
		($('#t55_mt').attr('checked') == false || $('#t55_mf').attr('checked') == false)) {
		BodyForm += '&t55_estado=' + '<?php echo $objHC->EstInf_Corr; ?>';
	}
	
	if(confirm("Estas seguro de Guardar los Comentarios de Avances, para el Informe?"))
	{
		var sURL = "inf_unico_anual_process.php?" + 'action=<?php echo(md5("ajax_analisis_avances"));?>';
		var req = Spry.Utils.loadURL("POST", sURL, true, AnalisisAvancesSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
	}
}
function AnalisisAvancesSuccessCallback	(req)
{
var respuesta = req.xhRequest.responseText;
respuesta = respuesta.replace(/^\s*|\s*$/g,"");
var ret = respuesta.substring(0,5);
if(ret=="Exito")
{
LoadAnalisisAvanc(true);
alert(respuesta.replace(ret,""));
}
else
{alert(respuesta);}  
}
</script>

		</div>
<?php if($idProy=="") { ?>
</form>
	<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd -->
</html>
<?php } ?>