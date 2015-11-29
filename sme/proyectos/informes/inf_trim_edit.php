<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>

<?php
require_once (constant("PATH_CLASS") . "BLInformes.class.php");
require_once (constant("PATH_CLASS") . "HardCode.class.php");
require_once (constant('PATH_CLASS') . "BLBene.class.php");

$objInf = new BLInformes();
$HC = new HardCode();

error_reporting("E_PARSE");
$idProy = $objFunc->__Request('idProy');
$idAnio = $objFunc->__Request('idanio');
$idTrim = $objFunc->__Request('idtrim');
$idVs = $objFunc->__Request('idversion');

$action = $objFunc->__GET('mode');
$view = $objFunc->__GET('accion');
// $objFunc->Debug(true);
$objBene = new BLBene();

$rsss = $objBene->ListadoBeneficiarios($idProy);

if (md5("ajax_new") == $action) {
    $objFunc->SetSubTitle('Informe Trimestral - Nuevo Registro');
    $idVs = 1;
    $idAnio = 1;
    $rowFinanciero = 0; // modificado 28/11/2011
    $row = $objInf->InformeTrimestralUltimo($idProy);
    $idAnio = $row['t25_anio'];
    $idTrim = $row['t25_trim'];
    
    list ($idAnio, $idTrim) = $objInf->GetStartingYearMoth($idProy, $idAnio, $idTrim);
    list ($aPerNum, $aPerTxt) = $objInf->GetStartingPeriod($idProy, $idAnio, $idTrim * 3, true);
    $row['t25_periodo'] = $aPerTxt ? $aPerTxt : $row['t25_periodo'];
}
if (md5("ajax_edit") == $action) {
    $objFunc->SetSubTitle('Informe Trimestral - Editar Registro');
    $row = $objInf->InformeTrimestralSeleccionar($idProy, $idAnio, $idTrim, $idVs);
    $rowFinanciero = $objInf->InformeFinancieroSeleccionar($idProy, $idAnio, $idTrim * 3); // modificado 28/11/2011
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

<title>Detalle del Informe Trimestral</title>
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->
<script language="javascript" type="text/javascript"
	src="../../../jquery.ui-1.5.2/jquery-1.2.6.js"></script>
<script src="../../../SpryAssets/SpryTabbedPanels.js"
	type="text/javascript"></script>
<link href="../../../SpryAssets/SpryTabbedPanels.css" rel="stylesheet"
	type="text/css" />
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
<script src="../../../jquery.ui-1.5.2/jquery.maskedinput.js"
			type="text/javascript"></script>
		<script src="../../../jquery.ui-1.5.2/ui/ui.datepicker.js"
			type="text/javascript"></script>
		<link href="../../../jquery.ui-1.5.2/themes/ui.datepicker.css"
			rel="stylesheet" type="text/css" />
		<div id="toolbar" style="height: 4px;" class="BackColor">
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td width="9%"><button id="btn_Guardar" class="Button"
							onclick="Guardar_InformeCab(); return false;" value="Guardar">Guardar
						</button></td>
					<td width="9%"><button class="Button"
							onclick="btnCancelar_Clic(); return false;" value="Cancelar"
							style="white-space: nowrap;">Cerrar y Volver</button></td>
					<td width="1%" align="right">&nbsp;</td>


					<!--select name="cboEnviarA" id="cboEnviarA" style="width:130px;" onchange="EnviarInformeA();">
            <option value="" selected="selected"></option>
            <option value="<?php echo($HC->EstInf_Rev);?>">Revisión</option>
            <option value="<?php echo($HC->EstInf_Corr);?>">Corrección</option>
            <option value="<?php echo($HC->EstInf_Aprob);?>">V.B. Monitor Técnico</option>
          </select-->

					<td width="17%" align="right"><button class="Button"
							onclick="Enviar_Revision(); return false;"
							value="Enviar a Revision" id="btn_ERevision"
							<?php if($ObjSession->PerfilID==$HC->MT || $rowFinanciero['inf_fi_ter']==0){ ?>
							disabled <?php } ?>>Enviar a Revisión</button></td>
					<td width="17%"><button class="Button"
							onclick="Enviar_Correcion(); return false;"
							value="Enviar a Correcion" id="btn_ECorrecion"
							<?php if($ObjSession->PerfilID==$HC->Ejec){ echo "disabled";  } ?>>Enviar
							a Correción</button></td>
					<td width="15%"><button class="Button"
							onclick="Enviar_Aprobacion(); return false;"
							value="Aprobar el Imforme" id="btn_AprobarInf"
							style="white-space: nowrap;"
							<?php if($ObjSession->PerfilID==$HC->Ejec){ echo "disabled";  } ?>>
							VB Monitor</button></td>

					<td width="14%">&nbsp;</td>
					<td width="1%">&nbsp;</td>
					<td width="45%" align="right"><?php echo($objFunc->SubTitle) ;?></td>
				</tr>
			</table>
		</div>
    
	<?php
// Agregado el 28/11/2011
$objInf = new BLInformes();
$mostrar = false;
$mostrar = $objInf->vInformeTrimestral($idProy, $idAnio, $idTrim);
?>
  <div>
			<div id="divCabeceraInforme">
				<h1 style="font-weight: bold; font-size: 12px; color: red">La
					información ingresada tiene carácter de declaración jurada</h1>
				<table width="100%" border="0" cellspacing="0" cellpadding="0"
					class="TableEditReg">
					<tr>
						<td colspan="4"><strong>1. Caratula</strong></td>
					</tr>
					<tr>
						<td width="9%" height="25">Año</td>
						<td width="32%"><select name="cboanio" id="cboanio"
							style="width: 100px;" class="InfTrim">
        <?php
        $objProy = new BLProyecto();
        $ver_proy = $objProy->MaxVersion($idProy);
        $rs = $objProy->ListaAniosProyecto($idProy, $ver_proy);
        $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $idAnio);
        $objProy = NULL;
        ?>
        </select> <input name="t25_anio" type="hidden" id="t25_anio"
							value="<?php echo($idAnio);?>" /> <input name="t25_ver_inf"
							type="hidden" id="t25_ver_inf"
							value="<?php echo($row['vsinf']);?>" /></td>
						<td width="14%">Trimestre</td>
						<td width="45%"><select name="cbotrim" id="cbotrim"
							style="width: 130px;" onchange="LoadIndicadoresProposito(true);"
							class="InfTrim">
								<option value="" selected="selected"></option>
         <?php
        require (constant("PATH_CLASS") . "BLTablasAux.class.php");
        $objTablas = new BLTablasAux();
        $rs = $objTablas->ListadoTrimestres();
        $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $idTrim);
        ?>
        </select> <input name="t25_trim" type="hidden" id="t25_trim"
							value="<?php echo($idTrim);?>" /></td>
					</tr>
					<tr>
						<td height="27" nowrap="nowrap">Periodo Ref.</td>
						<td nowrap="nowrap"><input name="t25_periodo" type="text"
							class="InfTrim" id="t25_periodo"
							value="<?php echo($row['t25_periodo'])?>" size="40"
							maxlength="50" /></td>
						<td nowrap="nowrap">Fecha de Presentación</td>
						<td><input name="t25_fch_pre" type="text" class="InfTrim"
							id="t25_fch_pre" value="<?php echo($row['t25_fch_pre'])?>"
							size="20" maxlength="12" /></td>
					</tr>
					<tr>
						<td height="26">Estado</td>
						<td><select name="t25_estado" class="InfTrim" id="t25_estado"
							style="width: 130px;" disabled>
								<option value=""></option>
        <?php
        $rs = $objTablas->EstadoInformes();
        $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $row['t25_estado']);
        $objTablas = NULL;
        ?>
        </select>
        <?php
        if (md5("ajax_edit") == $action) {
            echo ("Revision: " . $idVs);
        }
        ?>
        </td>
						<!--td nowrap="nowrap">&nbsp;</td-->
		<?php if($ObjSession->PerfilID==$HC->Ejec){?>
        <td valign="middle" colspan="2">
			<?php if($rowFinanciero['inf_fi_ter']==1){ ?>
				<span id="infTerminado"
							style="border: 1px solid #999999; padding: 5px; color: #999999; margin-right: 15px;">El
								Informe financiero esta terminado</span>
			<?php } else if($mostrar){?>
				<span id="infTerminado"
							style="border: 1px solid #FF0000; padding: 5px; color: #FF0000; margin-right: 15px;">Para
								enviar a revisión termine de elaborar el Informe Financiero</span>
			<?php } ?>
		</td>
		<?php } ?>
	  </tr>
      
      <?php ?>
      <tr>
						<td colspan="4"><font style="font-weight: bold; color: #00F;">Observaciones
								del Monitor Técnico </font> <br /> <!--textarea name="txtIndPropdif[]" cols="2500" rows="2" id="txtIndPropdif[]" style="padding:0px; width:100%;"><?php echo($row['dificultades']);?></textarea-->
							<textarea name="txtObs" cols="2500" rows="2" id="txtObs"
								style="padding: 0px; width: 100%;"><?php echo($row['obs_mt']);?></textarea>

						</td>
					</tr>


					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
				</table>
			</div>
    
	
	<?php
if ($mostrar) {
    ?>
    <div id="ssTabInforme" class="TabbedPanels">
				<ul class="TabbedPanelsTabGroup">
					<li class="TabbedPanelsTab" tabindex="0">2. Proposito</li>
					<li class="TabbedPanelsTab" tabindex="1">3. Componentes</li>
					<li class="TabbedPanelsTab" tabindex="2">4. Actividades</li>
					<li class="TabbedPanelsTab" tabindex="3">5. Actividades</li>
					<li class="TabbedPanelsTab" tabindex="4"
						onclick="LoadPlanesEspecificos(false);">6. Planes Específicos</li>
					<li class="TabbedPanelsTab" tabindex="5"
						onclick="LoadAnalisis(false);">7. Analisis</li>
					<li class="TabbedPanelsTab" tabindex="6"
						onclick="LoadAnexosFotograficos(false);">8. Anexos</li>
				</ul>
				<!-- Tab Proposito -->
				<div class="TabbedPanelsContentGroup">
					<div class="TabbedPanelsContent">
						<div id="divIndicadoresProposito" class="TableGrid"></div>
					</div>

					<!-- Tab Componentes -->
					<div class="TabbedPanelsContent">
						<table width="100%" border="0" cellspacing="0" cellpadding="0"
							class="TableEditReg">
							<tr>
								<td width="8%">&nbsp;</td>
								<td colspan="2">&nbsp;</td>
								<td width="36%" rowspan="3" align="left" valign="middle"><input
									type="button" value="Guardar Avances"
									class="btn_save_custom btn_save"
									title="Guardar Avance de Indicadores de Componente"
									onclick="Guardar_AvanceIndComp();" /></td>
							</tr>
							<tr>
								<td><b>Componente</b>&nbsp;</td>
								<td width="53%"><select name="cbocomponente_ind"
									id="cbocomponente_ind" style="width: 500px;"
									onchange="LoadIndicadoresComponente();">
										<option value="" selected="selected"></option>
						<?php
    $rs = $objInf->ListaComponentes($idProy);
    $objFunc->llenarComboI($rs, 't08_cod_comp', 'componente', '');
    ?>
					</select></td>
								<td width="3%" align="center"><input type="button"
									value="Refrescar" class="btn_save_custom"
									onclick="LoadIndicadoresComponente();"
									title="Refrescar Indicadores" /></td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td colspan="2">&nbsp;</td>
							</tr>
						</table>
						<div id="divAvanceIndicadoresComponentes" class="TableGrid"></div>
					</div>

					<!-- Tab Actividades -->
					<div class="TabbedPanelsContent">
						<table width="100%" border="0" cellspacing="0" cellpadding="0"
							class="TableEditReg">
							<tr>
								<td width="8%">&nbsp;</td>
								<td colspan="2">&nbsp;</td>
								<td width="36%" rowspan="3" align="left" valign="middle"><input
									type="button" value="Guardar Avance"
									title="Guardar Avance de Indicadores de Actividad"
									onclick="Guardar_AvanceIndAct();"
									class="btn_save_custom btn_save" /></td>
							</tr>
							<tr>
								<td><b>Actividad</b>&nbsp;</td>
								<td width="53%"><select name="cboactividad_ind"
									id="cboactividad_ind" style="width: 500px;"
									onchange="LoadIndicadoresActividad();">
										<option value=""></option>
						<?php
    $rs = $objInf->ListaActividades($idProy);
    $objFunc->llenarComboI($rs, 'codigo', 'actividad');
    ?>
					</select></td>
								<td width="3%" align="center"><input type="button"
									value="Refrescar" class="btn_save_custom"
									onclick="LoadIndicadoresActividad();"
									title="Refrescar Indicadores" /></td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td colspan="2">&nbsp;</td>
							</tr>
						</table>
						<div id="divAvanceActividades" class="TableGrid">
							<table width="100%" border="0" cellpadding="0" cellspacing="0"
								class="TableEditReg">
								<tr>
									<td height="207">&nbsp;</td>
								</tr>
							</table>
						</div>
					</div>

					<!-- Tab Actividades -->
					<div class="TabbedPanelsContent">
						<div id="">
							<table width="100%" border="0" cellspacing="0" cellpadding="0"
								class="TableEditReg">
								<tr>
									<td width="8%">&nbsp;</td>
									<td colspan="2">&nbsp;</td>
									<td width="36%" rowspan="3" align="left" valign="middle"><input
										type="button" value="Guardar Avance"
										title="Guardar Avance de Actividades"
										onclick="Guardar_AvanceSubAct();"
										class="btn_save_custom btn_save" /></td>
								</tr>
								<tr>
									<td><b>Actividad</b>&nbsp;</td>
									<td width="53%"><select name="cboactividad_sub"
										id="cboactividad_sub" style="width: 500px;"
										onchange="LoadSubActividades();">
											<option value=""></option>
							<?php
    $rs = $objInf->ListaActividades($idProy);
    $objFunc->llenarComboI($rs, 'codigo', 'actividad');
    ?>
						</select></td>
									<td width="3%" align="center"><input type="button"
										value="Refrescar" class="btn_save_custom"
										onclick="LoadSubActividades();"
										title="Refrescar Actividades" /></td>
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td colspan="2">&nbsp;</td>
								</tr>
							</table>
							<div id="divAvanceSubActividades" class="TableGrid">
								<table width="100%" border="0" cellpadding="0" cellspacing="0"
									class="TableEditReg">
									<tr>
										<td height="207">&nbsp;</td>
									</tr>
								</table>
							</div>
						</div>
					</div>
					<!-- Tab Planes Especificos -->
					<div class="TabbedPanelsContent">
		<?php if($rsss->num_rows <= 0){	?>
			<div style="color: red;">Añn no se han reprotados los
							beneficiarios para enviar a revisión</div>
		<?php } ?>
		<div id="divPlanesEspecificos" style="min-height: 200px;"></div>
					</div>

					<!-- Tab Analisis -->
					<div class="TabbedPanelsContent">
						<div id="divAnalisis"></div>
					</div>

					<!-- Tab Anexos -->
					<div class="TabbedPanelsContent">
						<div id="divAnexosFotograficos"></div>
					</div>
				</div>
			</div>
	<?php } else{?>
			<br />
			<span
				style="font-weight: bold; background-color: #EEEEEE; margin-right: 15px; font-size: 12px; float: right; color: #999999; padding: 5px 10px; border: solid 1px #999999;">Guardar
				la Caratula para continuar con el llenado de datos</span>
	<?php } ?>
    <p>&nbsp;</p>
		</div>
		<script language="javascript" type="text/javascript">
    ConfigInfTrim();
	function ConfigInfTrim()
	{
		$(".InfTrim:text").attr('readonly', 'readonly');
		$("select.InfTrim>option:not(:selected)").attr('disabled', 'disabled');
		<?php if($action == md5("ajax_edit")) { ?>
			$('#btn_Guardar').attr("disabled","disabled");		
		<?php } ?>
		<?php if($view==md5("ver")) { ?>
		$('.InfTrim').attr("disabled","disabled");
		<?php } ?>
		<?php if (($ObjSession->PerfilID==$HC->Ejec) || ($view==md5("ver"))) { ?>
			$('#txtObs').attr('disabled', 'disabled');
		<?php } ?>
	}
  
    function EnviarInformeA()
	{
		<?php $ObjSession->AuthorizedPage('EDITAR'); ?>	
		
		var estado = $("#cboEnviarA").val();
		var nomestado = $("#cboEnviarA>option:selected").text() ;
		
		if(estado==""){
			return false;
		}
		
		var anio = "<?php echo($idAnio);?>";
		var trim = "<?php echo($idTrim);?>";
		var ver  = "<?php echo($idVs);?>";	
			
		if(anio=="" || anio==null){alert("Seleccione A\u00f1o del Informe"); return false;}
		if(trim=="" || trim==null){alert("Seleccione Trimestre del Informe");  return false;}
		
		var BodyForm = "idProy=<?php echo($idProy);?>&idAnio="+anio+"&idTrim="+trim+"&vs="+ver+"&estado="+estado+"&nomestado="+nomestado;
	 	
		if(confirm("Esta seguro de que desea enviar el informe a estado \""+nomestado +"\""))
		{
			var sURL = "inf_trim_process.php?action=<?php echo(md5('ajax_envio_inf_trim'));?>";
			var req = Spry.Utils.loadURL("POST", sURL, true, EnviarInformeA_Callback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
		}
	}
	
	
	function EnviarInformeA_Callback	(req)
	{
	  var respuesta = req.xhRequest.responseText;
	  respuesta = respuesta.replace(/^\s*|\s*$/g,"");
	  var ret = respuesta.substring(0,5);
	  if(ret=="Exito")
	  {
		btnCancelar_Clic();
		dsLista.loadData();
		alert(respuesta.replace(ret,""));
	  }
	  else
	  {alert(respuesta);}  
	}
	
	///
	
	function Enviar_Revision()
	{
	<?php $ObjSession->AuthorizedPage(); ?>	
		

		var anio = "<?php echo($idAnio);?>";
		var trim = "<?php echo($idTrim);?>";
		var ver  = "<?php echo($idVs);?>";	
		var nomestado = "Revisión" 		 
		var estado = "<?php echo($HC->EstInf_Rev);?>";		
		if(anio=="" || anio==null){alert("Seleccione A\u00f1o del Informe"); return false;}
		if(trim=="" || trim==null){alert("Seleccione Trimestre del Informe");  return false;}
		var aConfMsg = "Aún no se han reportado los beneficiarios para enviar a revisión.\nDesea enviar de todas maneras a Revisión?";
		
		$.getJSON("inf_trim_process.php", 
				{'action': '<?php echo md5('ajax_count_benef');?>', 'idProy': '<?php echo($idProy);?>', 'idAnio': anio, 'idTrim': trim},
				function(pData) {
					var goAhead = true;
					if (pData.total_benef == 0) {
						if (!confirm($('<div/>').html(aConfMsg).text()))
							goAhead = false;
					}

					if (goAhead) {
						var BodyForm = "idProy=<?php echo($idProy);?>&idAnio="+anio+"&idTrim="+trim+"&vs="+ver+"&estado="+estado+"&nomestado="+nomestado;
						
						if(confirm("Esta seguro de que desea enviar el informe a estado \""+nomestado +"\""))
						{
							var sURL = "inf_trim_process.php?action=<?php echo(md5('ajax_envio_inf_trim'));?>";
							var req = Spry.Utils.loadURL("POST", sURL, true, Enviar_RevisionCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
						}
					}
				});
	}
	function Enviar_RevisionCallback	(req)
	{
	  var respuesta = req.xhRequest.responseText;
	  respuesta = respuesta.replace(/^\s*|\s*$/g,"");
	  var ret = respuesta.substring(0,5);
	  if(ret=="Exito")
	  {
		btnCancelar_Clic();
		dsLista.loadData();
		alert(respuesta.replace(ret,""));
	  }
	  else
	  {alert(respuesta);}  
	}
	
	function Enviar_Correcion()
	{
	<?php $ObjSession->AuthorizedPage(); ?>	
		
		
		var anio = "<?php echo($idAnio);?>";
		var trim = "<?php echo($idTrim);?>";
		var ver  = "<?php echo($idVs);?>";	
		var nomestado = "Corrección"; 
		var obs=$('#txtObs').val();
		var estado = "<?php echo($HC->EstInf_Corr);?>";		

		
		if(anio=="" || anio==null){alert("Seleccione A\u00f1o del Informe"); return false;}
		if(trim=="" || trim==null){alert("Seleccione Trimestre del Informe");  return false;}
		
		
		var BodyForm = "idProy=<?php echo($idProy);?>&idAnio="+anio+"&idTrim="+trim+"&vs="+ver+"&estado="+estado+"&nomestado="+nomestado+"&obs="+obs;
	 
		if(confirm("Esta seguro de que desea enviar el informe a estado \""+nomestado +"\""))
		{
			var sURL = "inf_trim_process.php?action=<?php echo(md5('ajax_envio_inf_trim_corr'));?>";
			var req = Spry.Utils.loadURL("POST", sURL, true, Enviar_CorrecionCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
		}
	}
	function Enviar_CorrecionCallback	(req)
	{
	  var respuesta = req.xhRequest.responseText;
	  respuesta = respuesta.replace(/^\s*|\s*$/g,"");
	  var ret = respuesta.substring(0,5);
	  if(ret=="Exito")
	  {
		btnCancelar_Clic();
		dsLista.loadData();
		alert(respuesta.replace(ret,""));
	  }
	  else
	  {alert(respuesta);}  
	}
	
	function Enviar_Aprobacion()
	{
	<?php $ObjSession->AuthorizedPage(); ?>	
		
		
		var anio = "<?php echo($idAnio);?>";
		var trim = "<?php echo($idTrim);?>";
		var ver  = "<?php echo($idVs);?>";	
		var nomestado = "V.B. Monitor Técnico" 		 
		var estado = "<?php echo($HC->EstInf_Aprob);?>";		

		if(anio=="" || anio==null){alert("Seleccione A\u00f1o del Informe"); return false;}
		if(trim=="" || trim==null){alert("Seleccione Trimestre del Informe");  return false;}
	
		var BodyForm = "idProy=<?php echo($idProy);?>&idAnio="+anio+"&idTrim="+trim+"&vs="+ver+"&estado="+estado+"&nomestado="+nomestado;
	 	
		if(confirm("Esta seguro de que desea enviar el informe a estado \""+nomestado +"\""))
		{
			var sURL = "inf_trim_process.php?action=<?php echo(md5('ajax_envio_inf_trim'));?>";
			var req = Spry.Utils.loadURL("POST", sURL, true, Enviar_AprobacionCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
		}
	}
	function Enviar_AprobacionCallback	(req)
	{
	  var respuesta = req.xhRequest.responseText;
	  respuesta = respuesta.replace(/^\s*|\s*$/g,"");
	  var ret = respuesta.substring(0,5);
	  if(ret=="Exito")
	  {
		btnCancelar_Clic();
		dsLista.loadData();
		alert(respuesta.replace(ret,""));
	  }
	  else
	  {alert(respuesta);}  
	}
	
	
	///
	
	
    function Guardar_InformeCab		()
	{
	<?php $ObjSession->AuthorizedPage('EDITAR'); ?>	
	
	var anio = $('#cboanio').val();
	var trim = $('#cbotrim').val();
	var per = $('#t25_periodo').val();
	var est = $('#t25_estado').val();

	if(anio=="" || anio==null){alert("Seleccione A\u00f1o del Informe"); return false;}
	if(trim=="" || trim==null){alert("Seleccione Trimestre, del Informe");  return false;}
	if(per=="" || per==null){alert("No se ha especificado el periodo del Informe"); return false;}
	if(est=="" || est==null){alert("Seleccione Estado del Informe"); return false;}
	
	//var BodyForm= serializeDIV('divCabeceraInforme') + "&t02_cod_proy="+$('#txtCodProy').val();  
	var BodyForm="cboanio="+anio+"&t25_anio="+$("#t25_anio").val()+"&t25_ver_inf="+$("#t25_ver_inf").val()+"&cbotrim="+trim+"&t25_trim="+$("#t25_trim").val()+"&t25_periodo="+per+"&t25_fch_pre="+$("#t25_fch_pre").val()+"&t25_estado="+est+"&t02_cod_proy="+$('#txtCodProy').val()+"&obs_mt="+$('#txtObs').val();  
	var sURL = "inf_trim_process.php?action=<?php echo($action);?>";
	var req = Spry.Utils.loadURL("POST", sURL, true, informeSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
	
	}
	function informeSuccessCallback	(req)
	{
	
	  var respuesta = req.xhRequest.responseText;
	  respuesta = respuesta.replace(/^\s*|\s*$/g,"");
	  var ret = respuesta.substring(0,5);
	  if(ret=="Exito")
	  {
		dsLista.loadData();
		var vs = respuesta.substring(0,7);
		alert(respuesta.replace(vs,""));
		vs = vs.replace(ret,"");
		btnEditar_Clic($('#cboanio').val(), $('#cbotrim').val(), vs);
	  }
	  else
	  {
		  alert(respuesta);
	  }  
	  
	}
  </script>

		<script language="javascript">
    function LoadIndicadoresProposito(recargar)
	{
		if($('#divIndicadoresProposito').html()!="")
		{ if(!recargar){return false;}	}
		var anio = $('#cboanio').val();
		var trim = $('#cbotrim').val();
		if(anio=="" || anio==null){alert("Seleccione A\u00f1o del Informe"); return false;}
		if(trim=="" || trim==null){alert("Seleccione Trimestre, del Informe");  return false;}
		var BodyForm = "action=<?php echo(md5("lista_ind_prop"));?>&idProy=<?php echo($idProy);?>&idAnio="+anio+"&idTrim="+trim+"&t25_ver_inf="+$('#t25_ver_inf').val();
	 	var sURL = "inf_trim_ind_prop.php?view=<?php echo($view)?>";
		$('#divIndicadoresProposito').html("<p align='center'><img src='../../../img/indicator.gif' width='16' height='16' /><br>Cargando..<br></p>");
	 	var req = Spry.Utils.loadURL("POST", sURL, true, SuccessIndicProposito, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
	}
	function SuccessIndicProposito		(req)
	{
	   var respuesta = req.xhRequest.responseText;
	   $("#divIndicadoresProposito").html(respuesta);
	   configSubPages();
 	   return;
	}
   
   
   function LoadIndicadoresComponente()
	{
		var comp = $('#cbocomponente_ind').val(); 
		var anio = $('#cboanio').val();
		var trim = $('#cbotrim').val();
		if(anio=="" || anio==null){alert("Seleccione A\u00f1o del Informe"); $('#cbocomponente_ind').val(null); return false;}
		if(trim=="" || trim==null){alert("Seleccione Trimestre, del Informe");  $('#cbocomponente_ind').val(null); return false;}
		var BodyForm = "action=<?php echo(md5("lista_ind_comp"));?>&idProy=<?php echo($idProy);?>&idComp="+ comp + "&idAnio="+anio+"&idTrim="+trim;
	 	var sURL = "inf_trim_ind_comp.php?view=<?php echo($view)?>";
		$('#divAvanceIndicadoresComponentes').html("<p align='center'><img src='../../../img/indicator.gif' width='16' height='16' /><br>Cargando..<br></p>");
	 	var req = Spry.Utils.loadURL("POST", sURL, true, SuccessIndicadoresComp, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
	}
	function SuccessIndicadoresComp	 (req)
	{
	   var respuesta = req.xhRequest.responseText;
	   $("#divAvanceIndicadoresComponentes").html(respuesta);
 	   return;
	}
  
  
  
    function LoadIndicadoresActividad()
	{
		var activ = $('#cboactividad_ind').val(); 
		var anio = $('#cboanio').val();
		var trim = $('#cbotrim').val();
		if(anio=="" || anio==null){alert("Seleccione A\u00f1o del Informe"); $('#cboactividad_ind').val(null); return false;}
		if(trim=="" || trim==null){alert("Seleccione Trimestre, del Informe");  $('#cboactividad_ind').val(null); return false;}
		var BodyForm = "action=<?php echo(md5("lista_ind_act"));?>&idProy=<?php echo($idProy);?>&idActiv="+ activ + "&idAnio="+anio+"&idTrim="+trim;
	 	var sURL = "inf_trim_ind_act.php?view=<?php echo($view)?>";
		$('#divAvanceActividades').html("<p align='center'><img src='../../../img/indicator.gif' width='16' height='16' /><br>Cargando..<br></p>");
	 	var req = Spry.Utils.loadURL("POST", sURL, true, SuccessIndicadoresAct, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
	}
	function SuccessIndicadoresAct	 (req)
	{
	   var respuesta = req.xhRequest.responseText;
	   $("#divAvanceActividades").html(respuesta);
 	   return;
	}
	function onErrorLoad			(req)
	{
		alert("Ocurrio un error al cargar los datos");
	}
	
	function LoadSubActividades		()
	{
		var activ = $('#cboactividad_sub').val(); 
		var anio = $('#cboanio').val();
		var trim = $('#cbotrim').val();
		if(anio=="" || anio==null){alert("Seleccione A\u00f1o del Informe"); $('#cboactividad_sub').val(null); return false;}
		if(trim=="" || trim==null){alert("Seleccione Trimestre, del Informe");  $('#cboactividad_sub').val(null); return false;}
		var BodyForm = "action=<?php echo(md5("lista_sub_act"));?>&idProy=<?php echo($idProy);?>&idActiv="+ activ + "&idAnio="+anio+"&idTrim="+trim;
	 	var sURL = "inf_trim_sub_act.php?view=<?php echo($view)?>";
		$('#divAvanceSubActividades').html("<p align='center'><img src='../../../img/indicator.gif' width='16' height='16' /><br>Cargando..<br></p>");
	 	var req = Spry.Utils.loadURL("POST", sURL, true, SuccessSubAct, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
	}
	function SuccessSubAct			(req)
	{
	   var respuesta = req.xhRequest.responseText;
	   $("#divAvanceSubActividades").html(respuesta);
 	   return;
	}
	
	
	
	function LoadPlanesEspecificos(recargar)
	{
		if($('#divPlanesEspecificos').html()!="")
		{ if(!recargar){return false;}	}
		var anio = $('#cboanio').val();
		var trim = $('#cbotrim').val();
		if(anio=="" || anio==null){alert("Seleccione A\u00f1o del Informe"); return false;}
		if(trim=="" || trim==null){alert("Seleccione Trimestre, del Informe");  return false;}
		var BodyForm = "action=<?php echo(md5("lista_ind_prop"));?>&idProy=<?php echo($idProy);?>&idAnio="+anio+"&idTrim="+trim+"&t25_ver_inf="+$('#t25_ver_inf').val();
	 	var sURL = "inf_trim_planes.php?view=<?php echo($view)?>";
		$('#divPlanesEspecificos').html("<p align='center'><img src='../../../img/indicator.gif' width='16' height='16' /><br>Cargando..<br></p>");
	 	var req = Spry.Utils.loadURL("POST", sURL, true, SuccessPlanesEspecificos, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
	}
	function SuccessPlanesEspecificos		(req)
	{
	   var respuesta = req.xhRequest.responseText;
	   $("#divPlanesEspecificos").html(respuesta);
 	   return;
	}
	
	
	function LoadAnalisis(recargar)
	{
		if($('#divAnalisis').html()!="")
		{
			if(!recargar){return false;}
		}
		
		var anio = $('#cboanio').val();
		var trim = $('#cbotrim').val();
		if(anio=="" || anio==null){alert("Seleccione A\u00f1o del Informe"); return false;}
		if(trim=="" || trim==null){alert("Seleccione Trimestre, del Informe");  return false;}
		var BodyForm = "action=<?php echo(md5("lista_prob_solu"));?>&idProy=<?php echo($idProy);?>&idAnio="+anio+"&idTrim="+trim+"&t25_ver_inf="+$('#t25_ver_inf').val();
	 	var sURL = "inf_trim_analisis.php?view=<?php echo($view)?>";
		$('#divAnalisis').html("<p align='center'><img src='../../../img/indicator.gif' width='16' height='16' /><br>Cargando..<br></p>");
	 	var req = Spry.Utils.loadURL("POST", sURL, true, SuccessProbSoluc, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
	}
	function SuccessProbSoluc		(req)
	{
	   var respuesta = req.xhRequest.responseText;
	   $("#divAnalisis").html(respuesta);
	   configSubPages();
 	   return;
	}
	function LoadAnexosFotograficos(recargar)
	{
		if($('#divAnexosFotograficos').html()!="")
		{
			if(!recargar){return false;}
		}
		var anio = $('#cboanio').val();
		var trim = $('#cbotrim').val();
		if(anio=="" || anio==null){alert("Seleccione A\u00f1o del Informe"); return false;}
		if(trim=="" || trim==null){alert("Seleccione Trimestre, del Informe");  return false;}
		var BodyForm = "action=<?php echo(md5("lista_anx_foto"));?>&idProy=<?php echo($idProy);?>&idAnio="+anio+"&idTrim="+trim+"&t25_ver_inf="+$('#t25_ver_inf').val();
	 	var sURL = "inf_trim_anx_foto.php?view=<?php echo($view)?>";
		$('#divAnexosFotograficos').html("<p align='center'><img src='../../../img/indicator.gif' width='16' height='16' /><br>Cargando..<br></p>");
	 	var req = Spry.Utils.loadURL("POST", sURL, true, SuccessAnxFotos, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
	}
	
	function SuccessAnxFotos(req)
	{
	   var respuesta = req.xhRequest.responseText;
	   $("#divAnexosFotograficos").html(respuesta);
 	   return;
	}

	function configSubPages()
	{
		<?php
			$aStatus =  $row['t25_estado'];
			$aProfile = $ObjSession->PerfilID;
			
			if (($view == md5('ver')) || 
				($aProfile == $HC->Ejec && 
				 ($aStatus == $HC->EstInf_Aprob ||  
				  $aStatus == $HC->EstInf_AprobCMT))) { ?>
			$('.btn_save').attr('disabled', 'disabled');
		<?php } ?>
	}
	configSubPages();	
  </script>
   
 
  <?php if($idProy=="") { ?>
</form>
	<script type="text/javascript">
<!--
var TabsInforme = new Spry.Widget.TabbedPanels("ssTabInforme", {defaultTab:2});
//-->
</script>
	<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd -->
</html>
<?php } ?>