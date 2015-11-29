<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>

<?php
require (constant("PATH_CLASS") . "BLPOA.class.php");
require (constant("PATH_CLASS") . "BLTablasAux.class.php");

$objPOA = new BLPOA();
$objHC = new HardCode();

error_reporting("E_PARSE");
$idProy = $objFunc->__Request('idProy');
$idAnio = $objFunc->__Request('idAnio');

$action = $objFunc->__Request('mode');

if (md5("ajax_edit") == $action) {
    $objFunc->SetSubTitle('Modificación de Costos del POA');
    $row = $objPOA->POA_Seleccionar($idProy, $idAnio);
    /* Obtener Version del Proyecto, para el POA en Cuestion */
    $idVersion = $row['version'];
}

$IsMF = false;
if ($ObjSession->PerfilID == $objHC->GP || $ObjSession->PerfilID == $objHC->RA) {
    $IsMF = true;
}

$isSE = false;
if ($ObjSession->PerfilID == $objHC->SE) {
    $isSE = true;
}

if ($action == "") {
    ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<title>POA</title>
<script language="javascript" type="text/javascript"
	src="../../../jquery.ui-1.5.2/jquery-1.2.6.js"></script>
<script src="../../../SpryAssets/SpryTabbedPanels.js"
	type="text/javascript"></script>
<link href="../../../SpryAssets/SpryTabbedPanels.css" rel="stylesheet"
	type="text/css" />
<link href="../../../css/itemplate.css" rel="stylesheet" type="text/css"
	media="all" />
</head>

<body>
	<form id="frmMain" name="frmMain" method="post" action="#">
  <?php
}
?>

  <div>
			<div id="toolbar" style="height: 4px;" class="BackColor">
				<table width="100%" border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td width="6%">
							<button class="Button" onclick="GuardarPOA(); return false;"
								value="Exportar" style="white-space: nowrap;">Guardar</button>
						</td>
						<td width="15%"><button class="Button"
								onclick="btnCancelar_Clic(); return false;" value="Cancelar"
								style="white-space: nowrap;">Cerrar y Volver</button></td>
						<td width="15%">
				            <input type="checkbox" id="vb_se" name="vb_se" <?php echo($row['vb_se_fin']==1?'checked':'');?> <?php echo($IsMF?'disabled':'');?> <?php echo ($isSE || $IsMF)?'':'class="hide"'; ?>/>
    				        <?php if($isSE || $IsMF){?>
    				        V°B° SE
    			            <?php }?>
			                <input type="hidden" id="vb_se_fin" name="vb_se_fin" class="Cabecera"/>
    			        </td>
						<td width="14%"><button class="Button"
								onclick="Exportar(); return false;" value="Exportar"
								style="white-space: nowrap;">Exportar</button></td>
						<td width="18%">
							<div
								style="float: 60px; left: 560px; position: absolute; top: 45px; width: 140px;">
								Año &nbsp;&nbsp; <select name="cboanio" id="cboanio"
									style="width: 100px;" class="Cabecera" disabled="disabled">
            <?php
            $objProy = new BLProyecto();
            $ver_proy = $objProy->MaxVersion($idProy);
            $rs = $objProy->ListaAniosProyecto($idProy, $ver_proy);
            $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $idAnio);
            $objProy = NULL;
            ?>
          </select> <input type="hidden" name="cboversion"
									id="cboversion" value="<?php echo($idVersion); ?>" />
							</div>
						</td>
						<td width="47%" align="right"><?php echo($objFunc->SubTitle) ;?></td>
					</tr>
				</table>
			</div>
			<div align="left">
				<table width="438" border="0" align="left" cellpadding="0"
					cellspacing="0" class="TableEditReg" style="display: inline-block;">
					<tr>
						<td width="438">
    <?php if($objHC->RA==$ObjSession->PerfilID || $objHC->GP==$ObjSession->PerfilID || $objHC->FE==$ObjSession->PerfilID) { ?>
          <fieldset>
								<legend>Observaciones de Gestor de Proyectos</legend>
								<textarea name="txtobserv_cmf" cols="70" rows="3"
									id="txtobserv_cmf" class="Cabecera"><?php echo($row['t02_obs_cmf']);?></textarea>
							</fieldset>
          <?php } ?>
          <?php if($objHC->Ejec==$ObjSession->PerfilID) { ?>
          <input type="hidden" name="txtobserv_cmf" id="txtobserv_cmf"
							class="Cabecera" value="<?php echo($row['t02_obs_cmf']);?>" />
							<fieldset>
								<legend>Observaciones de Gestor de Proyectos</legend>
								<div
									style="overflow: auto; max-height: 90px; min-height: 10px; color: #F00; font-size: 10px;">
          <?php echo(nl2br($row['t02_obs_cmf']));?>
          </div>
							</fieldset>
          <?php } ?>
    </td>
					</tr>
				</table>
				<input name="t02_cod_proy" type="hidden" class="Cabecera"
					id="t02_cod_proy" value="<?php echo($idProy);?>" /> <input
					name="t02_anio" type="hidden" class="Cabecera" id="t02_anio"
					value="<?php echo($idAnio);?>" />

				<div
					style="float: 60px; left: 560px; position: absolute; top: 70px; width: 200px; white-space: nowrap;">
					<table class="TableEditReg">
						<tr>
							<td><label for="t02_estado_mf"> Estado&nbsp;&nbsp;</label> <select
								name="t02_estado_mf" class="Cabecera" id="t02_estado_mf"
								style="width: 130px;">
<?php
$objTablas = new BLTablasAux();
$rs = $objTablas->EstadoInformesPOAFin();
$objFunc->llenarCombo($rs, 'codigo', 'descripcion', $row['t02_estado_mf']);
$objTablas = NULL;
?>
</select> <input name="chkAprobado" id="chkAprobado" type="hidden"
								class="Cabecera" value="<?php echo($row['t02_aprob_cmf']);?>" />

							</td>
						</tr>
					</table>
				</div>

			</div>
			<p>&nbsp;</p>
			<div id="ssTabPOA" class="TabbedPanels">
				<ul class="TabbedPanelsTabGroup">
					<li class="TabbedPanelsTab" tabindex="0">Costos Operativos</li>
					<li class="TabbedPanelsTab" tabindex="1"
						onclick="LoadPersonal(false);">Manejo del Proyecto</li>
				</ul>
				<div class="TabbedPanelsContentGroup">
					<div class="TabbedPanelsContent">
						<div id="divCostosOperativos">
							<br /> <br /> <br /> <br /> <br /> <br /> <br /> <br /> <br /> <br />
						</div>
					</div>
					<div class="TabbedPanelsContent"
						style="display: block; min-height: 500px;">
						<div id="divManejoProyecto">
							<div id="ssTabMP" class="TabbedPanels">
								<ul class="TabbedPanelsTabGroup">
									<li class="TabbedPanelsTab" tabindex="0">Personal Proyecto</li>
									<li class="TabbedPanelsTab" tabindex="1"
										onclick="LoadEquipamiento(false);">Equipamiento Proyecto</li>
									<li class="TabbedPanelsTab" tabindex="2"
										onclick="LoadGastoFuncion(false);">Gastos Funcionamiento</li>
									<li class="TabbedPanelsTab" tabindex="3"
										onclick="LoadGastoAdminis(false);">Costos Administrativos</li>
									<li class="TabbedPanelsTab" tabindex="4"
										onclick="LoadLineaBase(false);">Linea Base / Imprevistos</li>
								</ul>
								<div class="TabbedPanelsContentGroup">
									<div class="TabbedPanelsContent">
										<div id="divPersonal"></div>
									</div>
									<div class="TabbedPanelsContent">
										<div id="divEquipamiento"></div>
									</div>
									<div class="TabbedPanelsContent">
										<div id="divGastosFunc"></div>
									</div>
									<div class="TabbedPanelsContent">
										<div id="divGastosAdmin"></div>
									</div>
									<div class="TabbedPanelsContent">
										<div id="divLineaBase"></div>
									</div>
								</div>
							</div>
						</div>

					</div>
				</div>
			</div>
		</div>

		<script language="javascript" type="text/javascript">
   function GuardarPOA()
	{
	<?php $ObjSession->AuthorizedPage(); ?>


	var anio = $('#cboanio').val();

	if(anio=="" || anio==null){alert("No se cargado correctamente el Año del POA"); return false;}

	var checkeado = $("#vb_se").attr("checked");
    var checkbox = 0;
	if(checkeado) {
	    checkbox=1;
	}

	$("#vb_se_fin").val(checkbox);

	var BodyForm= $("#FormData .Cabecera").serialize() ;
	var sURL = "poa_tec_process.php?action=<?php echo(md5("ajax_edit_cmf"));?>" ;
	var req = Spry.Utils.loadURL("POST", sURL, true, POASuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });

	}
	function POASuccessCallback	(req)
	{
	  var respuesta = req.xhRequest.responseText;
	  respuesta = respuesta.replace(/^\s*|\s*$/g,"");
	  var ret = respuesta.substring(0,5);
	  if(ret=="Exito")
	  {
		dsLista.loadData();
		var anio = respuesta.substring(0,7);
		alert(respuesta.replace(anio,""));
		anio = anio.replace(ret,"");
		btnEditar_Clic(anio);
	  }
	  else
	  {alert(respuesta);}
	}

   function Exportar()
   {
	    var reportID = 12 ;
		var arrayControls = new Array();
			arrayControls[0] = "idProy=<?php echo($idProy);?>";
			arrayControls[1] = "idVersion=<?php echo($idVersion);?>";
			arrayControls[2] = "no_filter=1";
		var params = arrayControls.join("&");

	    var newURL = "<?php echo constant('PATH_RPT') ;?>reportviewer.php?ReportID=" + reportID + "&" + params ;
		var win =  window.open(newURL, "w_exportpoafin", "fullscreen,scrollbars");
		win.focus();
		return;
   }

   var htmlLoading = "<p align='center'><img src='<?php echo(constant("PATH_IMG"));?>indicator.gif' width='16' height='16' /><br>Cargando..<br></p>";

  	function LoadCostosOperativos()
	{
		var idcomp=$('#cboComponente_ope').val();
		if(idcomp=="" || idcomp==null){idcomp=1;}

		var idAct=$('#cboActividad_ope').val();
		if(idAct=="" || idAct==null){idAct=1;}

		var BodyForm = "action=<?php echo(md5("lista_personal"));?>&idProy=<?php echo($idProy);?>&idVersion=<?php echo($idVersion);?>&idComp="+idcomp+"&idAct="+idAct;
	 	var sURL = "poa_fin_cost_ope.php";
		$('#divCostosOperativos').html(htmlLoading);
	 	var req = Spry.Utils.loadURL("POST", sURL, true, SuccessCostosOperativos, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
	}
    function SuccessCostosOperativos	 	(req)
	{
	   var respuesta = req.xhRequest.responseText;
	   $("#divCostosOperativos").html(respuesta);
 	   return;
	}

    //Cargar Personal
	function LoadPersonal			(recargar)
	{
		if($('#divPersonal').html()!="")
		{
			if(!recargar){return false;}
		}

		var BodyForm = "action=<?php echo(md5("lista_personal"));?>&idProy=<?php echo($idProy);?>&idVersion=<?php echo($idVersion);?>";
	 	var sURL = "poa_fin_personal.php";
		$('#divPersonal').html(htmlLoading);
	 	var req = Spry.Utils.loadURL("POST", sURL, true, SuccessPersonal, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
	}
	function SuccessPersonal	 	(req)
	{
	   var respuesta = req.xhRequest.responseText;
	   $("#divPersonal").html(respuesta);
 	   return;
	}
	function onErrorLoad			(req)
	{
		alert("Ocurrio un error al cargar los datos");
	}
	function LoadEquipamiento		(recargar)
	{
		if($('#divEquipamiento').html()!="")
		{
			if(!recargar){return false;}
		}
		var BodyForm = "action=<?php echo(md5("lista_equipamiento"));?>&idProy=<?php echo($idProy);?>&idVersion=<?php echo($idVersion);?>";
	 	var sURL = "poa_fin_equipa.php";
		$('#divEquipamiento').html(htmlLoading);
	 	var req = Spry.Utils.loadURL("POST", sURL, true, SuccessEquipamiento, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
	}
	function SuccessEquipamiento	(req)
	{
	   var respuesta = req.xhRequest.responseText;
	   $("#divEquipamiento").html(respuesta);
 	   return;
	}
	function LoadGastoFuncion		(recargar)
	{
		if($('#divGastosFunc').html()!="")
		{
			if(!recargar){return false;}
		}
		var BodyForm = "action=<?php echo(md5("lista_equipamiento"));?>&idProy=<?php echo($idProy);?>&idVersion=<?php echo($idVersion);?>";
	 	var sURL = "poa_fin_gast_func.php";
		$('#divGastosFunc').html(htmlLoading);
	 	var req = Spry.Utils.loadURL("POST", sURL, true, SuccessGastoFuncion, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
	}
	function SuccessGastoFuncion	(req)
	{
	   var respuesta = req.xhRequest.responseText;
	   $("#divGastosFunc").html(respuesta);
 	   return;
	}

	function LoadGastoAdminis(recargar)
	{
		if($('#divGastosAdmin').html()!="")
		{
			if(!recargar){return false;}
		}
		var BodyForm = "action=<?php echo(md5("gastos_administ"));?>&idProy=<?php echo($idProy);?>&idVersion=<?php echo($idVersion);?>";
	 	var sURL = "poa_fin_gastos_adm.php";
		$('#divGastosAdmin').html(htmlLoading);
	 	var req = Spry.Utils.loadURL("POST", sURL, true, SuccessGastoAdminis, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
	}
	function SuccessGastoAdminis	(req)
	{
	   var respuesta = req.xhRequest.responseText;
	   $("#divGastosAdmin").html(respuesta);
 	   return;
	}

	function LoadLineaBase(recargar)
	{
		if($('#divLineaBase').html()!="")
		{
			if(!recargar){return false;}
		}
		var BodyForm = "action=<?php echo(md5("linea_base"));?>&idProy=<?php echo($idProy);?>&idVersion=<?php echo($idVersion);?>";
	 	var sURL = "mp_linea_base.php";
		$('#divLineaBase').html(htmlLoading);
	 	var req = Spry.Utils.loadURL("POST", sURL, true, SuccessLineaBase, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
	}

	function SuccessLineaBase		(req)
	{
	   var respuesta = req.xhRequest.responseText;
	   $("#divLineaBase").html(respuesta);
 	   return;
	}

	$("#t02_estado_mf option").attr('disabled','disabled');
	$("#t02_estado_mf option:selected").removeAttr('disabled');

	var estado = $("#t02_estado_mf").val();
	var Elaboracion= "<?php echo($objHC->EstInf_ElaFin);?>";
	var especFinVBGP = "<?php echo($objHC->especFinVBGP);?>";
	var Correccion = "<?php echo($objHC->EstInf_CorrFin);?>";
	var Revision   = "<?php echo($objHC->EstInf_RevFin);?>";
	var especFinAprobRA  = "<?php echo($objHC->especFinAprobRA);?>";

	<?php if($ObjSession->PerfilID == $objHC->GP || $ObjSession->PerfilID == $objHC->RA || $ObjSession->PerfilID == $objHC->FE) { ?>
		if(estado==Revision)
		  {
			  $('#t02_estado_mf option[value="'+Correccion+'"]').removeAttr('disabled');
			  $('#t02_estado_mf option[value="'+especFinVBGP+'"]').removeAttr('disabled');
		  }
	<?php } ?>

	<?php if($ObjSession->PerfilID == $objHC->Ejec && md5("ajax_edit")==$action) { ?>
		if(estado==Elaboracion || estado==Correccion)
		  {
			  $('#t02_estado_mf option[value="'+Revision+'"]').removeAttr('disabled');
		  }
	<?php } ?>
	<?php if($ObjSession->PerfilID == $objHC->RA) { ?>
		if(estado==especFinVBGP)
		{
			$('#t02_estado_mf option[value="'+Correccion+'"]').removeAttr('disabled');
			$('#t02_estado_mf option[value="'+especFinAprobRA+'"]').removeAttr('disabled');
		}
	<?php } ?>


	<?php
if ($idProy != "") {
    echo ("LoadCostosOperativos();");
}
?>

  </script>

  <?php if($action=="") { ?>
</form>
	<script type="text/javascript">
<!--
var TabsPOA = new Spry.Widget.TabbedPanels("ssTabPOA");
var TabsMP  = new Spry.Widget.TabbedPanels("ssTabMP");
//-->
</script>
</body>
</html>
<?php } ?>