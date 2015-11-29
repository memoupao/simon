<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>

<?php
// require(constant("PATH_CLASS")."BLInformes.class.php");
require_once (constant("PATH_CLASS") . "BLMonitoreoFinanciero.class.php");
require_once (constant("PATH_CLASS") . "BLFuentes.class.php");
require_once (constant("PATH_CLASS") . "HardCode.class.php");

// $objInf = new BLInformes();
$objInf = new BLMonitoreoFinanciero();

$HardCode = new HardCode();

// error_reporting("E_PARSE");
$idProy = $objFunc->__Request('idProy');
$idInforme = $objFunc->__Request('idnum');

$action = $objFunc->__GET('mode');

$ver = "";

if (md5("ajax_new") == $action) {
    $objFunc->SetSubTitle('Informe Financiero - Nuevo Registro');
    $row = 0;
    // $idInforme = 0;
}
if (md5("ajax_edit") == $action) {
    $objFunc->SetSubTitle('Informe Financiero - Editar Registro');
    $row = $objInf->Inf_MF_Seleccionar($idProy, $idInforme);
    
    if (($row['t51_estado'] == 247 || $row['t51_estado'] == 249) && $ObjSession->PerfilID == $HardCode->MF) {
        $ver = "disabled";
    }
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

<title>Detalle del Informe de Monitoreo Financiero</title>

<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->
<script src="../../../jquery.ui-1.5.2/jquery-1.2.6.js"
	type="text/javascript"></script>
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
   <div id="toolbar" style="height: 4px;" class="BackColor">
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td width="8%" align="rigth"><button class="Button"
							onclick="Guardar_InformeCab(); return false;" value="Guardar"
							<?php echo $ver; ?>>Guardar</button></td>
					<td width="16%"><button class="Button"
							onclick="btnCancelar_Clic(); return false;" value="Cancelar"
							style="white-space: nowrap;">Cerrar y Volver</button></td>
		  
		  
		  <?php if($ObjSession->PerfilID == $HardCode->MF || $ObjSession->PerfilID == $HardCode->Admin) { ?>
		  <td width="41%" nowrap="nowrap">
						<button class="Button" onclick="enviarRevision(); return false;"
							value="Enviar a Revision" <?php echo $ver; ?>>Enviar a Revision</button>
					</td>
		  <?php }else if($ObjSession->PerfilID==$HardCode->Admin || $ObjSession->PerfilID==$HardCode->CMF){ ?>
		  <td width="15%" nowrap="nowrap">
						<button class="Button" onclick="enviarCorreccion(); return false;"
							value="Enviar a Correccion"
							<?php if($row['t51_estado']==246 || $row['t51_estado']==248) echo "disabled"; ?>>Enviar
							a Corrección</button>
					</td>
					<td width="26%" nowrap="nowrap">
						<button class="Button" onclick="enviarAprobacion(); return false;"
							value="Aprobación"
							<?php if($row['t51_estado']==246 || $row['t51_estado']==248) echo "disabled"; ?>>Aprobación</button>

					</td>
		  <?php } ?>
		  
		  
		  
		  
         
          <td width="30%" align="right"><?php echo($objFunc->SubTitle) ;?></td>
				</tr>
			</table>
		</div>
		<div>
			<div id="divCabeceraInforme">
				<table width="100%" border="0" cellspacing="0" cellpadding="0"
					class="TableEditReg">
					<tr>
						<td colspan="3"><strong>1. Caratula</strong></td>

						<td colspan="1" align="left" style="padding: 2px;">
		           
          <?php if( is_array($row))  { ?>
          <font style="font-size: 11px;"><?php  echo("Exportar Anexo"); ?></font>
							<select name="cboExportarAnexo" id="cboExportarAnexo"
							style="width: 200px; font-size: 10px;"
							onchange="ExportarAnexos();">
								<option value="" selected="selected"></option>
								<option value="1" style="color: #009;">Anexo 1: Resumen
									Financiero del Proyecto</option>
								<option value="2" style="color: #093;">Anexo 2: Resumen del
									Excedente por Ejecutar</option>
								<option value="3" style="color: #F00;">Anexo 3: Presupuesto
									Ejecutado acumulado</option>
						</select>
          <?php } ?>

		</td>
					</tr>

					<tr>
						<td width="21%" height="25" nowrap="nowrap">Periodo de Referencia</td>
						<td width="13%" nowrap="nowrap"><input name="t51_fch_pre"
							type="hidden" class="Cabecera" id="t51_num"
							value="<?php echo($idInforme);?>" /> Desde<br /> <select
							name="cboper_ini" class="Cabecera" id="cboper_ini"
							style="width: 150px;">
								<option value="" selected="selected"></option>
          <?php
        $rs = $objInf->ListadoPeriodosEjecutados($idProy);
        $objFunc->llenarComboI($rs, 'codigo', 'periodo', $row['t51_per_ini']);
        ?>
        </select></td>
						<td width="8%" align="center"><input name="t51_mes" type="hidden"
							id="t51_mes" value="<?php echo($idMes);?>" /></td>
						<td width="58%">Hasta <br /> <select name="cboper_fin"
							class="Cabecera" id="cboper_fin" style="width: 150px;">
								<option value="" selected="selected"></option>
          <?php
        $rs = $objInf->ListadoPeriodosEjecutados($idProy);
        $objFunc->llenarComboI($rs, 'codigo', 'periodo', $row['t51_per_fin']);
        ?>
        </select></td>
					</tr>
					<tr>
						<td height="27">Fecha de Presentación</td>
						<td nowrap="nowrap"><input name="t51_fch_pre" type="text"
							class="Cabecera" id="t51_fch_pre"
							value="<?php echo($row['fecpre'])?>" size="20" maxlength="12" /></td>
						<td align="center" nowrap="nowrap">Estado</td>
						<td><select name="t51_estado" class="Cabecera" id="t51_estado"
							style="width: 110px;" disabled>
								<option value=""></option>
            <?php
            require (constant("PATH_CLASS") . "BLTablasAux.class.php");
            $objTablas = new BLTablasAux();
            $rs = $objTablas->EstadoInformesFinanc();
            $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $row['t51_estado']);
            ?>
            </select></td>
					</tr>
					<tr>
						<td colspan="4"><font style="font-weight: bold; color: #00F;">Observaciones
								del Coordinador de Monitoreo Financiero </font> <br> <textarea
								id="txtObsCMT" style="padding: 0px; width: 100%;" rows="2"
								cols="2500" name="txtObsCMT"
								<?php if($ObjSession->PerfilID!=$HardCode->CMF){echo 'disabled';} ?>><?php print $row['t51_obs_cmt'] ?></textarea>
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

			<div id="ssTabInforme" class="TabbedPanels">
				<ul class="TabbedPanelsTabGroup">
					<li class="TabbedPanelsTab" tabindex="0"
						onclick="LoadEvaluacion(false);">Evaluación</li>
					<li class="TabbedPanelsTab" tabindex="1">Avances Presupuestal</li>
					<li class="TabbedPanelsTab" tabindex="2">Avance Fisico</li>
					<li class="TabbedPanelsTab" tabindex="3"
						onclick="LoadExcedentes(false);">Excedentes</li>
					<li class="TabbedPanelsTab" tabindex="4"
						onclick="LoadObservaciones(false);">Observaciones</li>
					<li class="TabbedPanelsTab" tabindex="5"
						onclick="LoadConclusiones(false);">Conclusiones</li>
					<li class="TabbedPanelsTab" tabindex="6"
						onclick="LoadAnexos(false);">Anexos</li>
				</ul>
				<div class="TabbedPanelsContentGroup">
					<div class="TabbedPanelsContent">
						<div id="divEvaluacion"></div>
					</div>
					<div class="TabbedPanelsContent">
						<table width="100%" border="0" cellspacing="0" cellpadding="0"
							class="TableEditReg">
							<tr>
								<td width="8%">&nbsp;</td>
								<td colspan="2">&nbsp;</td>
								<!--td width="36%" rowspan="4" align="center" valign="middle" osktgui-->
								<td width="36%" rowspan="4" align="center" valign="bottom">
									<!--div style="text-align:center; color:navy; font-weight:bold; font-size:10px; cursor:pointer; width:120px;" title="Guardar Comentarios"  onclick="GuardarComentarios();" > <img src="../../../img/aplicar.png" width="24" height="24" alt="Guardar Avances" /><br />
                Guardar Comentarios </div osktgui--> <input
									type="button" value="Guardar Comentarios"
									title="Guardar Comentarios" onclick="GuardarComentarios();"
									class="btn_save_custom" <?php echo $ver; ?> />
								</td>
							</tr>
							<tr>
								<td nowrap="nowrap"><b>Fuente Financ.</b></td>
								<td width="42%"><select name="cboFuenteFinanc"
									id="cboFuenteFinanc" style="width: 300px;"
									onchange="LoadAvancePresupuestal();">
					<?php
    $objFuente = new BLFuentes();
    $rs = $objFuente->ContactosListado($idProy);
    $objFunc->llenarCombo($rs, "t01_id_inst", "t01_sig_inst");
    $objFuente = NULL;
    ?>
				  </select></td>
								<td width="14%" align="left">&nbsp;</td>
							</tr>
							<tr>
								<td nowrap="nowrap"><b>Componente</b>&nbsp;</td>
								<td><select name="cbocomponente_fe" id="cbocomponente_fe"
									style="width: 500px;" onchange="LoadAvancePresupuestal();">
										<option value=""></option>
                <?php
                $rs = $objInf->ListaComponentes($idProy);
                $objFunc->llenarComboI($rs, 't08_cod_comp', 'componente', '');
                ?> 
                <option value="mp" style="color: navy;"><?php echo($HardCode->CodigoMP);?>.- Manejo del Proyecto</option>
								</select></td>
								<td align="left">&nbsp; <!--img src="../../../img/btnRecuperar.gif" width="17" height="17" style="cursor:pointer;" onclick="LoadAvancePresupuestal();" title="Refrescar Presupuesto" / osktgui -->
									<input type="button" value="Refrescar" class="btn_save_custom"
									onclick="LoadAvancePresupuestal();"
									title="Refrescar Presupuesto" />
								</td>
							</tr>
							<tr>
								<td colspan="3"></td>
							</tr>
						</table>
						<div id="divAvancePresupFE" class="TableGrid">
							<table width="100%" border="0" cellpadding="0" cellspacing="0"
								class="TableEditReg">
								<tr>
									<td height="207">&nbsp;</td>
								</tr>
							</table>
						</div>
					</div>
					<div class="TabbedPanelsContent">
						<div id="">
							<table width="98%" border="0" cellspacing="0" cellpadding="0"
								class="TableEditReg">
								<tr>
									<td width="7%" align="left" valign="middle" nowrap="nowrap"><b>Componente</b></td>
									<td align="left" valign="middle" nowrap="nowrap"><select
										name="cbocomponente_fte" id="cbocomponente_fte"
										style="width: 500px;" onchange="LoadAvanceFisico();">
											<option value=""></option>
                  <?php
                $rs = $objInf->ListaComponentes($idProy);
                $objFunc->llenarComboI($rs, 't08_cod_comp', 'componente', '');
                ?>
                 <!-- <option value="mp" style="color:navy; display:none;">10.- Manejo del Proyecto</option>-->
									</select> <!--img src="../../../img/btnRecuperar.gif" width="17" height="17" style="cursor:pointer;" onclick="LoadAvanceFisico();" title="Refrescar Avance Fisico" / osktgui-->
										<input type="button" value="Refrescar" class="btn_save_custom"
										onclick="LoadAvanceFisico();" title="Refrescar Avance Fisico" />

									</td>
									<td width="2%" align="center">&nbsp;</td>
									<!--td width="36%" align="center" valign="middle" osktgui-->
									<td width="36%" align="center" valign="bottom">
										<!--div style="text-align:center; color:navy; font-weight:bold; font-size:10px; cursor:pointer; width:112px;" title="Guardar Comentarios"  onclick="GuardarComentarios2();" > <img src="../../../img/aplicar.png" width="24" height="24" alt="Guardar Gastos" /><br />
                  Guardar Comentarios </div osktgui--> <input
										type="button" value="Guardar Comentarios"
										class="btn_save_custom" title="Guardar Comentarios"
										onclick="GuardarComentarios2();" <?php echo $ver; ?> />
									</td>
								</tr>
							</table>
							<div id="divAvancePresupFuentes" class="TableGrid">
								<table width="100%" border="0" cellpadding="0" cellspacing="0"
									class="TableEditReg">
									<tr>
										<td height="207">&nbsp;</td>
									</tr>
								</table>
							</div>
						</div>
					</div>
					<div class="TabbedPanelsContent">
						<div id="divExcedenteEjec"></div>
					</div>
					<div class="TabbedPanelsContent">
						<div id="divObservaciones"></div>
					</div>
					<div class="TabbedPanelsContent">
						<div id="divConclusiones"></div>
					</div>
					<div class="TabbedPanelsContent">
						<div id="divAnexos"></div>
					</div>
				</div>
			</div>
			<p>&nbsp;</p>
		</div>
		<div id="panelPopup" class="popupContainer"
			style="height: 500px; visibility: hidden;">
			<div class="popupBox">
				<div class="popupBar">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td width="100%"><span id="titlePopup"></span></td>
							<td align="right"><a class="popupClose" href="javascript:;"
								onclick="spryPopupDialog01.displayPopupDialog(false);"><b>X</b></a></td>
						</tr>
					</table>
				</div>

				<div class="popupContent" style="background-color: #FFF;">
					<div id="popupText"></div>

					<div id="divChangePopup"
						style="background-color: #FFF; color: #333;"></div>

				</div>
			</div>
		</div>
		<script language="javascript" type="text/javascript">
    function ExportarAnexos()
	{
		var idAnx = $('#cboExportarAnexo').val();
		if(idAnx=="") {return ;}
		
		var arrayControls = new Array();
			arrayControls[0] = "idProy=<?php echo($idProy);?>";			
			arrayControls[1] = "idInforme=<?php echo($idInforme);?>" ;
			arrayControls[2] = "idFte=<?php echo($HardCode->codigo_Fondoempleo);?>" ;
            arrayControls[3] = "no_filter=1";
		var params = arrayControls.join("&"); 
		var sID = "0" ;
		switch(idAnx)
		{
			case "1" : sID = "39"; break;
			case "2" : sID = "40"; break;
			case "3" : sID = "41"; break;
		}
		showReport(sID, params);
		return;
	}
	
	function showReport(reportID, params)
	{
	 var newURL = "<?php echo constant('PATH_RPT') ;?>reportviewer.php?ReportID=" + reportID + "&" + params ;
	 window.open(newURL,"wrAnexos","fullscreen,scrollbars");
	}
  
    function btnImportar_Clic()
	{
		LoadImportarGastos();
		spryPopupImportar.displayPopupDialog(true);
		return false ;
	}
    
	function LoadImportarGastos()
	{
		var anio = $('#cboanio').val();
		var mes = $('#cbomes').val();
		var BodyForm = "mode=<?php echo(md5("importar_xls"));?>&idProy=<?php echo($idProy);?>&idAnio="+anio+"&idMes="+mes;
	 	var sURL = "inf_financ_imp_gastos.php";
		$('#divImportarGastos').html("<p align='center'><img src='../../../img/indicator.gif' width='16' height='16' /><br>Cargando..<br></p>");
	 	var req = Spry.Utils.loadURL("POST", sURL, true, SuccessImportarGastos, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
	}
	function SuccessImportarGastos(req)
	{
	   var respuesta = req.xhRequest.responseText;
	   $("#divImportarGastos").html(respuesta);
 	   return;
	}
	
	
	
	function enviarRevision(){
	
		var t02_cod_proy = "<?php echo $idProy; ?>";
		var url = "../../proyectos/planifica/proy_aprueba.php?idProy="+t02_cod_proy+"&reff=1&estadoeff=247&codigoeff=<?php echo $idInforme; ?>&action=<?php echo(md5('solicita')); ?>";					
		loadPopup("Enviar a Revision", url);
	}
	function enviarCorreccion(){
		var t02_cod_proy = "<?php echo $idProy; ?>";		
		var url = "../../proyectos/planifica/proy_aprueba.php?idProy="+t02_cod_proy+"&ceff=1&estadoeff=248&codigoeff=<?php echo $idInforme; ?>&action=<?php echo(md5('solicita')); ?>";					
		loadPopup("Correcion", url);
	}
	function enviarAprobacion(){
		var t02_cod_proy = "<?php echo $idProy; ?>";		
		var url = "../../proyectos/planifica/proy_aprueba.php?idProy="+t02_cod_proy+"&aeff=1&estadoeff=249&codigoeff=<?php echo $idInforme; ?>&action=<?php echo(md5('solicita')); ?>";					
		loadPopup("Aprobación de la Evaluacion Financiera", url);
	}
   function btnGuardarMsg(){
	document.location = document.location;
   }
    function Guardar_InformeCab		()
	{
		<?php $ObjSession->AuthorizedPage(); ?>	
		
		var ini = $('#cboper_ini').val();
		var fin = $('#cboper_fin').val();
		var est = $('#t51_estado').val();
	
		if(ini=="" || ini==null){alert("Seleccione Periodo Inicial del Informe"); $("#cboper_ini").focus(); return false;}
		if(fin=="" || fin==null){alert("Seleccione Periodo Final del Informe"); $("#cboper_fin").focus(); return false;}
		if (parseInt(ini) >= parseInt(fin)) {alert("Periodo inicial no puede ser mayor que el periodo final"); $("#cboper_ini").focus(); return false;}
		
		var arrParams = new Array();
			arrParams[0] = "proy="    + $("#txtCodProy").val();
			arrParams[1] = "per_ini=" + ini ;
			arrParams[2] = "per_fin=" + fin ;
			arrParams[3] = "fchpres=" + $("#t51_fch_pre").val();
			arrParams[4] = "estado="  + est ;
			arrParams[5] = "obs="     + $("#t51_obs").val() == 'undefined' ? '' :  $("#t51_obs").val()  ; 
			arrParams[6] = "conclu="  + $("#t51_conclu").val() == 'undefined' ? '' :  $("#t51_conclu").val()  ;  
			arrParams[7] = "calif="   + $("#t51_califica").val() == 'undefined' ? '' :  $("#t51_califica").val()  ;  
			arrParams[8] = "idnum=<?php echo($idInforme);?>";  
			arrParams[9] = "obs_cmt="+ $("#txtObsCMT").val(); 
			
		var BodyForm = arrParams.join("&");
		
		var sURL = "inf_financ_process.php?action=<?php echo($action);?>";
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
		var num = respuesta.substring(0,7);
		alert($('<div></div>').html(respuesta.replace(num,"")).text());
		num = num.replace(ret,"");
		btnEditar_Clic(num);
	  }
	  else
	  {alert($('<div></div>').html(respuesta).text());}  
	  
	}
	
	<?php if(md5("ajax_new")==$action) { ?>
		$("#t51_estado").val("<?php echo($HardCode->EstInf_ElaF);?>");
		$("#t51_estado option").attr('disabled','disabled');
		$("#t51_estado option:selected").removeAttr('disabled');
	
	<?php }else { ?>
	
		$("#t51_estado option").attr('disabled','disabled');
		$("#t51_estado option:selected").removeAttr('disabled');

	
	
	
	var estado = $("#t51_estado").val();
	var Elaboracion= "<?php echo($HardCode->EstInf_ElaF);?>";
	var AprobadoCMF = "<?php echo($HardCode->EstInf_AprobF);?>";
	var Correccion = "<?php echo($HardCode->EstInf_CorrF);?>";
	var Revision   = "<?php echo($HardCode->EstInf_RevF);?>";
	
	
		
	<?php if($ObjSession->PerfilID == $HardCode->CMF )  { ?>
		if(estado==Revision) 
		  {
			  $('#t51_estado option[value="'+Correccion+'"]').removeAttr('disabled');
			  $('#t51_estado option[value="'+AprobadoCMF+'"]').removeAttr('disabled');
		  }
	<?php } ?>
	
	<?php if($ObjSession->PerfilID == $HardCode->MF || $ObjSession->PerfilID == $HardCode->Admin) { ?>
//	alert("----"+estado+"--"+Elaboracion);
		if(estado==Elaboracion || estado==Correccion) 
		  {
			  $('#t51_estado option[value="'+Revision+'"]').removeAttr('disabled');
		  }
	<?php
    
}
}
?>
  </script>

		<script language="javascript">
	function onErrorLoad			(req)
	{
		alert("Ocurrio un error al cargar los datos");
	}
	function LoadAvancePresupuestal		()
	{
		var comp  = $('#cbocomponente_fe').val();
		var idNum = "<?php echo($idInforme)?>";
		var BodyForm = "action=<?php echo(md5("lista_presupuesto"));?>&idProy=<?php echo($idProy);?>&idComp="+ comp  + "&idNum="+idNum+"&idFuente="+$('#cboFuenteFinanc').val() ;
		var sURL = "";
		if(comp=="mp")
		{ sURL = "inf_financ_presup_mp.php"; }
		else
		{ sURL = "inf_financ_presup.php"; }
		
		$('#divAvancePresupFE').html("<p align='center'><img src='../../../img/indicator.gif' width='16' height='16' /><br>Cargando..<br></p>");
	 	var req = Spry.Utils.loadURL("POST", sURL, true, SuccessPresup, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
	}
	function SuccessPresup			(req)
	{
	   var respuesta = req.xhRequest.responseText;
	   $("#divAvancePresupFE").html(respuesta);
 	   return;
	}
	function LoadAvanceFisico		()
	{
		var comp  = $('#cbocomponente_fte').val();
		var idNum = "<?php echo($idInforme)?>";
		var BodyForm = "action=<?php echo(md5("lista_presupuesto"));?>&idProy=<?php echo($idProy);?>&idComp="+ comp  + "&idNum="+idNum ;
		var sURL = "";
		if(comp=="mp")
		{ sURL = "inf_financ_fisico_mp.php"; }
		else
		{ sURL = "inf_financ_fisico.php"; }
				
		$('#divAvancePresupFuentes').html("<p align='center'><img src='../../../img/indicator.gif' width='16' height='16' /><br>Cargando..<br></p>");
	 	var req = Spry.Utils.loadURL("POST", sURL, true, SuccessPresup2, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
	}
	function SuccessPresup2			(req)
	{
	   var respuesta = req.xhRequest.responseText;
	   $("#divAvancePresupFuentes").html(respuesta);
 	   return;
	}
	function GuardarComentarios	()
	{
	<?php $ObjSession->AuthorizedPage(); ?>	
	
	if('<?php echo($idInforme);?>'=='')
	{
		alert('Primero debe Grabar la Cabecera del Informe, y Establecer el Periodo de Referencia');
		return ;
	}
	
	var BodyForm=$("#FormData .presup").serialize();
	
	if(BodyForm=='')
	{
		alert("No hay Datos para Grabar...");
		return;
	}
	
	if(confirm("Estas seguro de Guardar los gastos ingresados para el Informe  ?"))
	  {
		var sURL = "inf_financ_process.php?action=<?php echo(md5('ajax_coment_avance_presup'));?>";
		var req = Spry.Utils.loadURL("POST", sURL, true, ComentariosSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
	  }
	}
	function ComentariosSuccessCallback	(req)
	{
	  var respuesta = req.xhRequest.responseText;
	  respuesta = respuesta.replace(/^\s*|\s*$/g,"");
	  var ret = respuesta.substring(0,5);
	  if(ret=="Exito")
	  {
		LoadAvancePresupuestal();
		alert(respuesta.replace(ret,""));
	  }
	  else
	  {alert(respuesta);}  
	}
	function GuardarComentarios2	()
	{
	<?php $ObjSession->AuthorizedPage(); ?>	
	<?php
if ($idInforme == '') {
    echo ("alert('Primero debe Grabar la Cabecera del Informe, y Establecer el Periodo de Referencia');");
    echo ("return;");
}
?>
	var BodyForm=$("#FormData .fisico").serialize();
	if(BodyForm=='')
	{
		alert("No hay Datos para Grabar...");
		return;
	}
	
	if(confirm("Estas seguro de Guardar los Comentarios acerca del Avance Fisico ingresados para el Informe ?"))
	  {
		var sURL = "inf_financ_process.php?action=<?php echo(md5('ajax_coment_avance_fisico'));?>";
		var req = Spry.Utils.loadURL("POST", sURL, true, Comentarios2SuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
	  }
	}
	function Comentarios2SuccessCallback(req)
	{
	  var respuesta = req.xhRequest.responseText;
	  respuesta = respuesta.replace(/^\s*|\s*$/g,"");
	  var ret = respuesta.substring(0,5);
	  if(ret=="Exito")
	  {
		LoadAvanceFisico();
		alert(respuesta.replace(ret,""));
	  }
	  else
	  {alert(respuesta);}  
	}
	
	
	function LoadEvaluacion(arg)
	{
		
		if($('#divEvaluacion').html()!="")
		{ if(!arg){return false;} 	}
				
		var BodyForm = "action=<?php echo(md5("lista_Evaluacion"));?>&idProy=<?php echo($idProy);?>&idnum=<?php echo($idInforme);?>&ver=<?php echo $ver; ?>";
	 	var sURL = "inf_financ_docs.php";
		$('#divEvaluacion').html("<p align='center'><img src='<?php echo(constant("PATH_IMG"));?>indicator.gif' width='16' height='16' /><br>Cargando..<br></p>");
	 	var req = Spry.Utils.loadURL("POST", sURL, true, SuccessLoadEvaluacion, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
	}
	function SuccessLoadEvaluacion			(req)
	{
	   var respuesta = req.xhRequest.responseText;
	   $("#divEvaluacion").html(respuesta);
 	   return;
	}
	function LoadObservaciones(arg)
	{
		if($('#divObservaciones').html()!="")
		{ if(!arg){return false;} 	}
		
		var anio  = $('#cboanio').val();
		var mes   = $('#cbomes').val();
		
		var BodyForm = "action=<?php echo(md5("lista_observa"));?>&idProy=<?php echo($idProy);?>&idNum=<?php echo($idInforme);?>&ver=<?php echo $ver; ?>";
	 	var sURL = "inf_financ_observa.php";
		$('#divObservaciones').html("<p align='center'><img src='../../../img/indicator.gif' width='16' height='16' /><br>Cargando..<br></p>");
	 	var req = Spry.Utils.loadURL("POST", sURL, true, SuccessLoadObservaciones, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
	}
	
	function SuccessLoadObservaciones			(req)
	{
	   var respuesta = req.xhRequest.responseText;
	   $("#divObservaciones").html(respuesta);
 	   return;
	}
	
	function LoadConclusiones(arg)
	{
		if($('#divConclusiones').html()!="")
		{ if(!arg){return false;} 	}
		
		var BodyForm = "action=<?php echo(md5("lista_conclusiones"));?>&idProy=<?php echo($idProy);?>&idNum=<?php echo($idInforme);?>&ver=<?php echo $ver; ?>";
	 	var sURL = "inf_financ_conclusiones.php";
		$('#divConclusiones').html("<p align='center'><img src='../../../img/indicator.gif' width='16' height='16' /><br>Cargando..<br></p>");
	 	var req = Spry.Utils.loadURL("POST", sURL, true, SuccessLoadConclusiones, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
	}
	function SuccessLoadConclusiones			(req)
	{
	   var respuesta = req.xhRequest.responseText;
	   $("#divConclusiones").html(respuesta);
 	   return;
	}
	
	function LoadExcedentes(arg)
	{
		if($('#divExcedenteEjec').html()!="")
		{ if(!arg){return false;} 	}
		
		var BodyForm = "action=<?php echo(md5("lista_excedentes"));?>&idProy=<?php echo($idProy);?>&idNum=<?php echo($idInforme);?>&ver=<?php echo $ver; ?>";
	 	var sURL = "inf_financ_excedente.php";
		$('#divExcedenteEjec').html("<p align='center'><img src='../../../img/indicator.gif' width='16' height='16' /><br>Cargando..<br></p>");
	 	var req = Spry.Utils.loadURL("POST", sURL, true, SuccessLoadExcedentes, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
	}
	function SuccessLoadExcedentes			(req)
	{
	   var respuesta = req.xhRequest.responseText;
	   $("#divExcedenteEjec").html(respuesta);
 	   return;
	}
	
	function LoadAnexos(recargar)
	{
		if($('#divAnexos').html()!="")
		{
			if(!recargar){return false;}
		}
		var anio = $('#cboanio').val();
		var mes = $('#cbomes').val();
		var BodyForm = "action=<?php echo(md5("lista_anexos"));?>&idProy=<?php echo($idProy);?>&idNum=<?php echo($idInforme);?>&ver=<?php echo $ver; ?>";
	 	var sURL = "inf_financ_anx.php";
		$('#divAnexos').html("<p align='center'><img src='../../../img/indicator.gif' width='16' height='16' /><br>Cargando..<br></p>");
	 	var req = Spry.Utils.loadURL("POST", sURL, true, SuccessAnexos, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
	}
	function SuccessAnexos(req)
	{
	   var respuesta = req.xhRequest.responseText;
	   $("#divAnexos").html(respuesta);
 	   return;
	}
	
	LoadEvaluacion(true);
	
  </script>
  <?php if($idProy=="") { ?>
</form>
	<script type="text/javascript">
<!--
var TabsInforme = new Spry.Widget.TabbedPanels("ssTabInforme", {defaultTab:1});
//-->
</script>
	<!-- InstanceEndEditable -->
</body>

<!-- InstanceEnd -->
</html>
<?php } ?>