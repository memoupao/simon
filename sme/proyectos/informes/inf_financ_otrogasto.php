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

<title>Otros Gastos</title>
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
					<!--div style="text-align:center; color:navy; font-weight:bold; font-size:10px; cursor:pointer; width:60px;" title="Refrescar datos de Otros Gastos"  onclick="LoadOtrosGastos(true);" > <img src="../../../img/gestion.jpg" width="24" height="24" /><br />
      Refrescar </div--> <input type="button" value="Refrescar"
					class="btn_save_custom" title="Refrescar datos de Otros Gastos"
					onclick="LoadOtrosGastos(true);" />
				</td>
				<td width="10%" rowspan="2" align="right" valign="middle">
					<!--div style="text-align:center; color:navy; font-weight:bold; font-size:10px; cursor:pointer; width:50px;" title="Guardar Otros Gastos"  onclick="Guardar_OtrosGastos();" > <img src="../../../img/aplicar.png" width="22" height="22" /><br />
      Guardar  </div modificado--> <input type="button" value="Guardar"
					id="btnGOtros" class="btn_save_custom" title="Guardar Otros Gastos"
					onclick="Guardar_OtrosGastos();" />
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
		</table>
		<div id="divTableLista" class="TableGrid">
			<table width="775" cellpadding="0" cellspacing="0">
				<thead>
				</thead>
				<tbody class="data" bgcolor="#FFFFFF">
					<tr class="SubtitleTable"
						style="border: solid 1px #CCC; background-color: #eeeeee;">
						<td width="130" align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">MONTOS S/.</td>
						<td width="541" height="23" colspan="6" align="center"
							valign="middle">OBSERVACIONES ACERCA DE LOS MONTOS REPORTADOS
							(GESTOR)</td>
					</tr>
     <?php
    $objInf = new BLInformes();
    $iRs = $objInf->ListaProblemasSoluciones($idProy, $idAnio, $idMes, $idVersion);
    $RowIndex = 0;
    $t20_dificul = "";
    $t20_program = "";
    $iRs->free();
    ?>

     <tr>
						<td nowrap="nowrap" class="TableGrid"><strong>OTROS INGRESOS</strong></td>
						<td width="102" align="left" valign="middle"><input
							name="t40_otro_ing" type="text" id="t40_otro_ing"
							value="<?php echo($row['t40_otro_ing'])?>" size="18"
							maxlength="18" style="text-align: right;" class="otrosgastos" /></td>
						<td colspan="6" align="left" nowrap="nowrap"><textarea
								name="t40_otro_ing_obs" cols="90" rows="3" id="t40_otro_ing_obs"
								class="otrosgastos"><?php echo($row['t40_otro_ing_obs'])?></textarea></td>
					</tr>
					<tr>
						<td nowrap="nowrap" class="TableGrid"><strong>ABONOS DEL BANCO</strong></td>
						<td align="left" valign="middle"><input name="t40_abo_bco"
							type="text" id="t40_abo_bco"
							value="<?php echo($row['t40_abo_bco'])?>" size="18"
							maxlength="18" style="text-align: right;" class="otrosgastos" /></td>
						<td colspan="6" align="left" nowrap="nowrap"><textarea
								name="t40_abo_bco_obs" cols="90" rows="3" id="t40_abo_bco_obs"
								class="otrosgastos"><?php echo($row['t40_abo_bco_obs'])?></textarea></td>
					</tr>
					<tr>
						<td height="39" colspan="8" nowrap="nowrap" class="TableGrid"><font
							style="font-size: 12px; color: #036;">El objetivo de este cuadro
								es mostrar el saldo en efectivo con que cuenta la Institución
								al mes de la presentación del reporte mensual.</font></td>
					</tr>
				</tbody>
				<tfoot>
				</tfoot>
			</table>
			<p>
				<input name="t02_cod_proy" type="hidden"
					value="<?php echo($idProy);?>" class="otrosgastos" /> <input
					name="t40_anio" type="hidden" value="<?php echo($idAnio);?>"
					class="otrosgastos" /> <input name="t40_mes" type="hidden"
					value="<?php echo($idMes);?>" class="otrosgastos" />

				<script language="javascript" type="text/javascript">
	function Guardar_OtrosGastos	()
	{
	<?php $ObjSession->AuthorizedPage(); ?>	

	var BodyForm=$("#FormData .otrosgastos").serialize();
	
	if(confirm("Estas seguro de Guardar los datos ingresados para el Informe ?"))
	  {
		var sURL = "inf_financ_process.php?action=<?php echo(md5('ajax_otros_gastos'));?>";
		var req = Spry.Utils.loadURL("POST", sURL, true, OtrosGastosSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
	  }
	}
	function OtrosGastosSuccessCallback	(req)
	{
	  var respuesta = req.xhRequest.responseText;
	  respuesta = respuesta.replace(/^\s*|\s*$/g,"");
	  var ret = respuesta.substring(0,5);
	  if(ret=="Exito")
	  {
		LoadOtrosGastos(true);
		alert(respuesta.replace(ret,""));
	  }
	  else
	  {alert(respuesta);}  
	} 
	<?php

$HardCode = new HardCode();
if ($ObjSession->PerfilID == $HardCode->Ejec && $row['inf_fi_ter'] == 1) {
    ?>
			$("#btnGOtros").attr("disabled","disabled");
	<?php } ?>
		
	$("#t40_otro_ing").numeric().pasteNumeric();
	$("#t40_abo_bco").numeric().pasteNumeric();
	
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