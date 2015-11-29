<?php 
include("../../../includes/constantes.inc.php"); 
include("../../../includes/validauser.inc.php"); 

require_once (constant("PATH_CLASS") . "BLInformes.class.php");
require_once (constant("PATH_CLASS") . "HardCode.class.php");
error_reporting("E_PARSE");
$idProy = $objFunc->__POST('idProy');
$idAnio = $objFunc->__POST('idAnio');
$idMes = $objFunc->__POST('idMes');

if ($idProy == "" && $idAnio == "" && $idMes == "") {
    $idProy = $objFunc->__GET('idProy');
    $idAnio = $objFunc->__GET('idAnio');
    $idMes = $objFunc->__GET('idMes');
}

$objInf = new BLInformes();
$row = $objInf->InformeFinancieroSeleccionar($idProy, $idAnio, $idMes);

if ($idProy == "") {
    ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/templateIframe.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->

<title>Excedentes por ejecutar</title>
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
<script language="javascript" type="text/javascript"
			src="../../../jquery.ui-1.5.2/jquery.numeric.js"></script>
		<script src="../../../js/commons.js" type="text/javascript"></script>
		<table width="750" border="0" cellspacing="1" cellpadding="0">
			<tr>
				<td width="82%" class="TableEditReg">&nbsp;</td>
				<td width="8%" rowspan="2" align="center" class="TableEditReg">
					<!--div style="text-align:center; color:navy; font-weight:bold; font-size:10px; cursor:pointer; width:60px;" title="Refrescar datos"  onclick="LoadExcedentes(true);" > <img src="../../../img/gestion.jpg" width="24" height="24" /><br />
      Refrescar </div--> <input type="button" value="Refrescar"
					class="btn_save_custom" title="Refrescar datos"
					onclick="LoadExcedentes(true);" />
				</td>
				<td width="10%" rowspan="2" align="right" valign="middle">
					<!--div style="text-align:center; color:navy; font-weight:bold; font-size:10px; cursor:pointer; width:50px;" title="Guardar "  onclick="Guardar_Excedentes();" > <img src="../../../img/aplicar.png" width="22" height="22" /><br />
      Guardar  </div--> <input type="button" id="btnGExc"
					value="Guardar" class="btn_save_custom" title="Guardar "
					onclick="Guardar_Excedentes();" />
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
		</table>
		<div id="divTableLista" class="TableGrid">
			<table width="750" cellpadding="0" cellspacing="0">
				<thead>
				</thead>
				<tbody class="data" bgcolor="#FFFFFF">
					<tr class="SubtitleTable"
						style="border: solid 1px #CCC; background-color: #FFF;">
						<td align="center" valign="middle">FECHA AL:</td>
						<td height="23" colspan="6" align="left" valign="middle"><input
							name="t40_cor_ctb" type="text" id="t40_cor_ctb"
							value="<?php echo($row['t40_cor_ctb'])?>" size="20"
							maxlength="10" class="excedentes" /></td>
					</tr>
					<tr class="SubtitleTable"
						style="border: solid 1px #CCC; background-color: #eeeeee;">
						<td width="102" align="center" valign="middle">&nbsp;</td>
						<td width="102" align="center" valign="middle">MONTO S/.</td>
						<td height="23" colspan="5" align="center" valign="middle">OBSERVACIONES
							AL MONTO REPORTADO ( GESTOR )</td>
					</tr>

					<tr>
						<td class="SubtitleTable"
							style="border: solid 1px #CCC; background-color: #eeeeee;">CAJA
							CHICA</td>
						<td height="25" align="right" valign="middle"><input
							name="t40_caja" type="text" id="t40_caja"
							value="<?php echo($row['t40_caja'])?>" size="18" maxlength="18"
							style="text-align: right;" class="excedentes" /></td>
						<td colspan="5" align="left" valign="middle"><textarea
								name="t40_caja_obs" cols="90" rows="2" id="t40_caja_obs"
								class="excedentes"><?php echo($row['t40_caja_obs'])?></textarea></td>
					</tr>
					<tr>
						<td class="SubtitleTable"
							style="border: solid 1px #CCC; background-color: #eeeeee;">BANCO
							MONEDA NACIONAL</td>
						<td height="25" align="right" valign="middle"><input
							name="t40_bco_mn" type="text" id="t40_bco_mn"
							value="<?php echo($row['t40_bco_mn'])?>" size="18" maxlength="18"
							style="text-align: right;" class="excedentes" /></td>
						<td colspan="5" align="left" valign="middle"><textarea
								name="t40_bco_mn_obs" cols="90" rows="2" id="t40_bco_mn_obs"
								class="excedentes"><?php echo($row['t40_bco_mn_obs'])?></textarea></td>
					</tr>
					<tr>
						<td class="SubtitleTable"
							style="border: solid 1px #CCC; background-color: #eeeeee;">ENTREGAS
							A RENDIR CUENTA</td>
						<td height="25" align="right" valign="middle"><input
							name="t40_ent_rend" type="text" id="t40_ent_rend"
							value="<?php echo($row['t40_ent_rend'])?>" size="18"
							maxlength="18" style="text-align: right;" class="excedentes" /></td>
						<td colspan="5" align="left" valign="middle"><textarea
								name="t40_ent_rend_obs" cols="90" rows="2" id="t40_ent_rend_obs"
								class="excedentes"><?php echo($row['t40_ent_rend_obs'])?></textarea></td>
					</tr>
					<tr>
						<td class="SubtitleTable"
							style="border: solid 1px #CCC; background-color: #eeeeee;">CUENTAS
							X PAGAR</td>
						<td height="25" align="right" valign="middle"><input
							name="t40_cxp" type="text" id="t40_cxp"
							value="<?php echo($row['t40_cxp'])?>" size="18" maxlength="18"
							style="text-align: right;" class="excedentes" /></td>
						<td colspan="5" align="left" valign="middle"><textarea
								name="t40_cxp_obs" cols="90" rows="2" id="t40_cxp_obs"
								class="excedentes"><?php echo($row['t40_cxp_obs'])?></textarea></td>
					</tr>
					<tr>
						<td class="SubtitleTable"
							style="border: solid 1px #CCC; background-color: #eeeeee;">CUENTAS
							X COBRAR</td>
						<td height="25" align="right" valign="middle"><input
							name="t40_cxc" type="text" id="t40_cxc"
							value="<?php echo($row['t40_cxc'])?>" size="18" maxlength="18"
							style="text-align: right;" class="excedentes" /></td>
						<td colspan="5" align="left" valign="middle"><textarea
								name="t40_cxc_obs" cols="90" rows="2" id="t40_cxc_obs"
								class="excedentes"><?php echo($row['t40_cxc_obs'])?></textarea></td>
					</tr>
				</tbody>
				<tfoot>
					<tr style="color: #FFF;">
						<td height="27" nowrap="nowrap" class="TableGrid"><strong>TOTAL</strong></td>
						<td align="right" valign="middle"><strong><span id="spnTotalEXC"><?php echo(number_format($row['t40_exc'],2));?></span></strong></td>
						<td width="138" align="center" nowrap="nowrap">&nbsp;</td>
						<td width="92" align="center" nowrap="nowrap">&nbsp;</td>
						<td width="95" align="center" nowrap="nowrap">&nbsp;</td>
						<td width="105" align="center" nowrap="nowrap">&nbsp;</td>
						<td width="114" align="center" nowrap="nowrap" bgcolor="#CC9933">&nbsp;</td>
					</tr>

				</tfoot>
			</table>
			<table width="750" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td height="40"><font style="font-size: 12px; color: #036;">El
							objetivo de este cuadro es mostrar como está distribuido el
							Excedente por Ejecutar en las diversas cuentas de Caja y Bancos,
							Rendiciones de Cuentas, Cuentas por Cobrar, Cuentas por Pagar y
							otros que reflejen efectivo a ejecutar por la Institución .</font></td>
				</tr>
			</table>
			<p>
				<input type="hidden" name="t02_cod_proy"
					value="<?php echo($idProy);?>" class="excedentes" /> <input
					name="t40_anio" type="hidden" id="t40_anio"
					value="<?php echo($idAnio);?>" class="excedentes" /> <input
					name="t40_mes" type="hidden" id="t40_mes"
					value="<?php echo($idMes);?>" class="excedentes" />

				<script language="javascript" type="text/javascript">
    function Guardar_Excedentes	()
	{
	<?php $ObjSession->AuthorizedPage(); ?>	

	var BodyForm=$("#FormData .excedentes").serialize();
	
	if(confirm("Estas seguro de Guardar los datos ingresados para el Informe ?"))
	  {
		var sURL = "inf_financ_process.php?action=<?php echo(md5('ajax_excedentes_ejecutar'));?>";
		var req = Spry.Utils.loadURL("POST", sURL, true, ExcedentesSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
	  }
	}
	function ExcedentesSuccessCallback	(req)
	{
	  var respuesta = req.xhRequest.responseText;
	  respuesta = respuesta.replace(/^\s*|\s*$/g,"");
	  var ret = respuesta.substring(0,5);
	  if(ret=="Exito")
	  {
		LoadExcedentes(true);
		alert(respuesta.replace(ret,""));
	  }
	  else
	  {alert(respuesta);}  
	}  
	<?php

$HardCode = new HardCode();
if ($ObjSession->PerfilID == $HardCode->Ejec && $row['inf_fi_ter'] == 1) {
    ?>
			$("#btnGExc").attr("disabled","disabled");
	<?php } ?>
	
	
	
	jQuery("#t40_cor_ctb").datepicker();
	$("#t40_cor_ctb").mask("99/99/9999");
			
	$("#t40_caja").numeric().pasteNumeric();
	$("#t40_bco_mn").numeric().pasteNumeric();
	$("#t40_ent_rend").numeric().pasteNumeric();
	$("#t40_cxp").numeric().pasteNumeric();
	$("#t40_cxc").numeric().pasteNumeric();
	
  </script>
			</p>
		</div>
<?php if($idProy=="") { ?>
</form>
	<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd -->
</html>
<?php } ?>