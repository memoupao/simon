<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauser.inc.php"); ?>

<?php
require (constant("PATH_CLASS") . "BLMonitoreo.class.php");
require (constant("PATH_CLASS") . "BLTablasAux.class.php");

$objMoni = new BLMonitoreo();

error_reporting("E_PARSE");
$idProy = $objFunc->__POST('idProy');
$idVta = $objFunc->__POST('idVta');

if ($idProy == "" && $idVta == "") {
    $idProy = $objFunc->__GET('idProy');
    $idVta = $objFunc->__GET('idVta');
}

$action = $objFunc->__GET('mode');

if (md5("ajax_new") == $action) {
    $objFunc->SetSubTitle('Plan Específico de Visitas - Nuevo Registro');
    $row = 0;
}
if (md5("ajax_edit") == $action) {
    $objFunc->SetSubTitle('Plan Específico de Visitas - Editar Registro');
    $row = $objMoni->PlanVisitaActivSeleccionar($idProy, $idVta);
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

<title>Detalle del Plan de Visitas</title>
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->
<script language="javascript" type="text/javascript"
	src="../../jquery.ui-1.5.2/jquery-1.2.6.js"></script>
<script src="../../SpryAssets/SpryTabbedPanels.js"
	type="text/javascript"></script>
<link href="../../SpryAssets/SpryTabbedPanels.css" rel="stylesheet"
	type="text/css" />
<!-- InstanceEndEditable -->
<link href="../../css/itemplate.css" rel="stylesheet" type="text/css"
	media="all" />
</head>

<body>
	<!-- InstanceBeginEditable name="tBody" -->
	<form id="frmMain" name="frmMain" method="post" action="#">
  <?php
}
?>
   <div id="toolbar" style="height: 4px;" class="BackColor">
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td width="9%"><button class="Button"
							onclick="Guardar_Cabecera(); return false;" value="Guardar">Guardar
						</button></td>
					<td width="25%"><button class="Button"
							onclick="btnCancelar_Clic(); return false;" value="Cancelar">
							Cerrar y Volver</button></td>
					<td width="9%">&nbsp;</td>
					<td width="2%">&nbsp;</td>
					<td width="2%">&nbsp;</td>
					<td width="2%">&nbsp;</td>
					<td width="47%" align="right"><?php echo($objFunc->SubTitle) ;?></td>
				</tr>
			</table>
		</div>

		<div>
			<div id="divCabeceraInforme">
				<br />
				<table width="100%" border="0" cellspacing="0" cellpadding="0"
					class="TableEditReg">
					<tr>
						<td width="8%" height="25">Numero</td>
						<td width="8%"><input name="txtnuminf" type="text" id="txtnuminf"
							style="text-align: center;"
							value="<?php echo($row['t32_id_vta'])?>" size="5" maxlength="5"
							readonly="readonly" /> <input name="t32_id_vta" type="hidden"
							id="t32_id_vta" value="<?php echo($idVta);?>" /></td>
						<td width="11%" nowrap="nowrap">Fecha Inicio Visita</td>
						<td width="22%"><input name="t32_fch_vta" type="text"
							id="t32_fch_vta" value="<?php echo($row['t32_fch_vta'])?>"
							size="15" maxlength="12" style="text-align: center;" /></td>
						<td width="12%" align="right" nowrap="nowrap">Fecha Término
							Visita</td>
						<td width="12%"><input name="t32_fch_vtater" type="text"
							id="t32_fch_vtater" value="<?php echo($row['t32_fch_ter'])?>"
							size="15" maxlength="12" style="text-align: center;" /></td>
						<td width="27%">&nbsp;</td>
					</tr>
					<tr>
						<td height="27" nowrap="nowrap">Objetivos</td>
						<td colspan="6" nowrap="nowrap"><textarea name="t32_obj_vta"
								cols="110" rows="2" id="t32_obj_vta"><?php echo($row['t32_obj_vta'])?></textarea></td>
					</tr>
					<tr>
						<td>Personas Entrevistadas</td>
						<td colspan="6"><textarea name="t32_per_ent" cols="110" rows="2"
								id="t32_per_ent"><?php echo($row['t32_per_ent'])?></textarea></td>
					</tr>
					<tr>
						<td>Recursos necesarios</td>
						<td colspan="6"><textarea name="t32_rec_nec" cols="110" rows="2"
								id="t32_rec_nec"><?php echo($row['t32_rec_nec'])?></textarea></td>
					</tr>
				</table>
			</div>
			<br />
			<div id="ssTabInforme" class="TabbedPanels">
				<ul class="TabbedPanelsTabGroup">
					<li class="TabbedPanelsTab" tabindex="0">Actividades del Plan de
						Visita</li>
				</ul>
				<div class="TabbedPanelsContentGroup">
					<div class="TabbedPanelsContent">
						<div id="divActividadesVisita" class="TableGrid"></div>
					</div>
				</div>
			</div>
			<p>&nbsp;</p>
		</div>
		<script language="javascript" type="text/javascript">
    function Guardar_Cabecera		()
	{
	<?php $ObjSession->AuthorizedPage(); ?>	
	var fec = $("#t32_fch_vta").val();
	var obj = $('#t32_obj_vta').val();

	if(fec=="" || fec==null){alert("Ingrese fecha de la visita");  return false;}
	if(obj=="" || obj==null){alert("Ingrese Objetivo de la Visita"); return false;}

	var arrParams = new Array();
		arrParams[0] = "t02_cod_proy=" + $("#txtCodProy").val(); 
		arrParams[1] = "t32_fch_vta=" + fec ;
		arrParams[2] = "t32_obj_vta=" + obj ;
		arrParams[3] = "t32_per_ent=" + $("#t32_per_ent").val();
		arrParams[4] = "t32_rec_nec=" + $("#t32_rec_nec").val();
		arrParams[5] = "t32_id_vta=" + $("#t32_id_vta").val();
		arrParams[6] = "t32_fch_vtater=" + $("#t32_fch_vtater").val();
		
	var BodyForm = arrParams.join("&");
	var sURL = "plan_visita_act_process.php?action=<?php echo($action);?>";
	var req = Spry.Utils.loadURL("POST", sURL, true, GuardarSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
	
	}
	function GuardarSuccessCallback	(req)
	{
	  var respuesta = req.xhRequest.responseText;
	  respuesta = respuesta.replace(/^\s*|\s*$/g,"");
	  var ret = respuesta.substring(0,5);
	  if(ret=="Exito")
	  {
		dsLista.loadData();
		var num = respuesta.substring(0,6);
		alert(respuesta.replace(num,""));
		num = num.replace(ret,"");
		btnEditar_Clic(num);
	  }
	  else
	  {
		  alert(respuesta);
	  }  
	  
	}
  </script>

		<script language="javascript">
  LoadActividades();
  jQuery("#t32_fch_vta").datepicker();
  jQuery("#t32_fch_vtater").datepicker();
  function LoadActividades()
	{
		var idProy=$('#txtCodProy').val();
		var idVta = $('#t32_id_vta').val();
		var url = "plan_visita_act_edit_list.php?action=<?php echo(md5("lista"));?>&idProy="+idProy+"&idVta="+idVta;
		loadUrlSpry("divActividadesVisita",url);

	}
  </script>
   
 
  <?php if($idProy=="") { ?>
</form>
	<script type="text/javascript">
<!--
var TabsInforme = new Spry.Widget.TabbedPanels("ssTabInforme");
jQuery("#t32_fch_vta2").datepicker();
//-->
</script>
	<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd -->
</html>
<?php } ?>