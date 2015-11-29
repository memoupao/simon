<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>

<?php
require (constant("PATH_CLASS") . "BLInformes.class.php");
require_once (constant("PATH_CLASS") . "BLMonitoreoFinanciero.class.php");
require_once (constant("PATH_CLASS") . "HardCode.class.php");

$objInf = new BLInformes();
$HardCode = new HardCode();

error_reporting("E_PARSE");
$idProy = $objFunc->__POST('idProy');
$idAnio = $objFunc->__POST('idanio');
$idVs = $objFunc->__POST('idversion');

if ($idProy == "" && $idAnio == "") {
    $idProy = $objFunc->__GET('idProy');
    $idAnio = $objFunc->__GET('idanio');
    $idVs = $objFunc->__GET('idversion');
}

$action = $objFunc->__GET('mode');
// $objFunc->Debug(true);

if (md5("ajax_new") == $action) {
    $objFunc->SetSubTitle('Informe Único Anual - Nuevo Registro');
    $row = 0;
    $idVs = 1;
    $idAnio = 1;
    
    $rowc = $objInf->InformeUnicoAnualUltimo($idProy);
    // file_put_contents('hugo.txt',$rowc);
    $idAnio = $rowc['sig_anio'];
    $idFecha = date(d . '/' . m . '/' . Y);
    $idEstado = $HardCode->EstInf_Ela;
    $idPeriodo = $rowc['periodo'];
    $ini = $rowc['ini'];
    $fin = $rowc['fin'];
}
if (md5("ajax_edit") == $action || md5("ajax_view") == $action) {
    $objFunc->SetSubTitle('Informe Único Anual - Editar Registro');
    $row = $objInf->InformeUnicoAnualSeleccionar($idProy, $idAnio);
    $idAnio = $row['t55_anio'];
    $idnum = $row['t55_id'];
    $idFecha = $row['t55_fch_pre'];
    $idEstado = $row['t55_estado'];
    $idPeriodo = $row['t55_periodo'];
    $ini = $row['ini'];
    $fin = $row['fin'];
}

$isViewOnly = md5("ajax_view") == $action ? '1' : '0';

list ($t55_mt_flg, $t55_mf_flg, $t55_cmt_flg, $t55_cmf_flg) = $row && is_array($row) ? array(
    $row['t55_mt'],
    $row['t55_mf'],
    $row['t55_cmt'],
    $row['t55_cmf']
) : array(
    0,
    0,
    0,
    0
);

if ($idProy == "") {
    ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/templateIframe.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->

<title>Detalle del Informe Único Anual</title>
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
        <?php
        if (! $isViewOnly) {
            ?>
          	<td width="9%"><button class="Button"
							onclick="Guardar_InformeCab(); return false;" value="Guardar">Guardar
						</button></td>
        <?php
        }
        ?>
          <td width="15%"><button class="Button"
							onclick="btnCancelar_Clic(); return false;" value="Cancelar">
							Cerrar y Volver</button></td>
					<td width="15%">
			<?php
if (md5("ajax_edit") == $action && $idEstado == $HardCode->EstInf_Ela) {
    ?>
					<button id='revisionBtn' class="Button"
							onclick="btnRevision_Clic(); return false;" value="Revision"
							disabled style='display: none'>Enviar a Revisión</button>
			<?php
}
?>
		  </td>
					<td width="9%">&nbsp;</td>
					<td width="2%">&nbsp;</td>
					<td width="2%">&nbsp;</td>
					<td width="47%" align="right"><?php echo($objFunc->SubTitle) ;?></td>
				</tr>
			</table>
		</div>


		<div id="divCabeceraInforme">
			<table width="100%" border="0" cellspacing="0" cellpadding="0"
				class="TableEditReg">
				<tr>
					<td colspan="6"><strong>1. Caratula</strong></td>
				</tr>
				<tr>
					<td width="9%" height="25">Año</td>
					<td width="32%"><select name="cboanio" id="cboanio"
						style="width: 100px;">
        <?php
        $objProy = new BLProyecto();
        $ver_proy = $objProy->MaxVersion($idProy);
        $rs = $objProy->ListaAniosProyecto($idProy, $ver_proy);
        $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $idAnio);
        $objProy = NULL;
        ?>
        </select> <input type='hidden' id='roMode'
						value='<?php echo $isViewOnly; ?>' /> <input name="t55_anio"
						type="hidden" id="t55_anio" value="<?php echo($idAnio);?>" /> <input
						name="t55_id" type="hidden" id="t55_id"
						value="<?php echo($idnum);?>" /> <input name="t55_ver_inf"
						type="hidden" id="t55_ver_inf"
						value="<?php echo($row['vsinf']);?>" /></td>
					<td width="14%"></td>
					<td width="45%"><select name="cbotrim" id="cbotrim"
						style="width: 130px;">
							<option value="" selected="selected"></option>
         <?php
        require (constant("PATH_CLASS") . "BLTablasAux.class.php");
        $objTablas = new BLTablasAux();
        $rs = $objTablas->ListadoTrimestres();
        $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $idTrim);
        ?>
        </select> <input name="t55_trim" type="hidden" id="t55_trim"
						value="<?php echo($idTrim);?>" /></td>
					<td nowrap="nowrap">&nbsp;</td>
					<td nowrap="nowrap">&nbsp;</td>
				</tr>
				<tr>
					<td height="27" nowrap="nowrap">Periodo Ref.</td>
					<td width="11%" nowrap="nowrap">&nbsp;Desde<br /> <select
						name="finicio" class="Cabecera" id="finicio" style="width: 120px;">
							<option value="" selected="selected"></option>
            <?php
            $objInfMF = new BLMonitoreoFinanciero();
            $rs = $objInfMF->ListadoPeriodosEjecutados($idProy);
            $objFunc->llenarComboI($rs, 'codigo', 'periodo', $ini);
            ?>
          </select>&nbsp;
					</td>


					<td width="19%" nowrap="nowrap">&nbsp;Hasta <br /> <select
						name="ffinal" class="Cabecera" id="ffinal" style="width: 120px;">
							<option value="" selected="selected"></option>
            <?php
            $rs = $objInfMF->ListadoPeriodosEjecutados($idProy);
            $objFunc->llenarComboI($rs, 'codigo', 'periodo', $fin);
            ?>
          </select></td>
					<!-- <td nowrap="nowrap"><input name="t55_periodo" type="text" id="t55_periodo" size="40" maxlength="50" value="<?php echo($idPeriodo)?>" /></td> -->
					<td nowrap="nowrap" style="text-align: right;">Fecha de
						Presentación</td>
					<td><input name="t55_fch_pre" type="text" id="t55_fch_pre"
						value="<?php echo($idFecha)?>" size="20" maxlength="12" /></td>
				</tr>
				<tr>
					<td height="26">Estado</td>
					<td><select name="t55_estado" id="t55_estado" style="width: 130px;">
        <?php
        $rs = $objTablas->EstadoInformes();
        while ($aRow = mysql_fetch_assoc($rs)) {
            if ($aRow['codigo'] == $HardCode->EstInf_VBMon)
                continue;
            $selected = ($aRow['codigo'] == $idEstado) ? "selected" : '';
            echo "<option value='" . $aRow['codigo'] . "' $selected disabled>" . $aRow['descripcion'] . "</option>\n";
        }
        $objTablas = NULL;
        ?>
        </select></td>
					<td nowrap="nowrap">&nbsp;</td>
					<td nowrap="nowrap">&nbsp;</td>
					<td nowrap="nowrap">&nbsp;</td>
					<td valign="middle">&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
			</table>
		</div>

		<div id="ssTabInforme" class="TabbedPanels">
			<ul class="TabbedPanelsTabGroup">
				<li class="TabbedPanelsTab" tabindex="0">2. Avan. Proy.</li>
				<li class="TabbedPanelsTab" tabindex="1">3. Prg. vs Avan. Comp.</li>
				<li class="TabbedPanelsTab" tabindex="2">4. Prg. vs Sub Act. Cri.</li>
				<li class="TabbedPanelsTab" tabindex="3"
					onclick="LoadCalifMonitor(false);">5. Calif. Monitor</li>
				<li class="TabbedPanelsTab" tabindex="4"
					onclick="LoadAnalisisAvanc(false);">6. Análisis</li>
				<li class="TabbedPanelsTab" tabindex="5"
					onclick="LoadInfAdicional(false);">7. Información Adicional</li>
			</ul>
			<div class="TabbedPanelsContentGroup">

				<div class="TabbedPanelsContent">
					<div id="divAvancesProyecto" style="min-height: 200px;"></div>
				</div>

				<div class="TabbedPanelsContent">
					<table width="100%" border="0" cellspacing="0" cellpadding="0"
						class="TableEditReg">
						<tr>
							<td width="8%">&nbsp;</td>
							<td colspan="2">&nbsp;</td>
							<td width="36%" rowspan="3" align="center" valign="middle"><br />
							</td>
						</tr>
						<tr>
							<td><b>Componente</b>&nbsp;</td>
							<td><select name="cbocomponente_pva" id="cbocomponente_pva"
								style="width: 500px;" onChange="LoadPrgvsAvanComponente();">
									<option value=""></option>
                <?php
                $rs = $objInf->ListaComponentes($idProy);
                $objFunc->llenarComboI($rs, 't08_cod_comp', 'componente', '');
                ?>               
              </select></td>
							<td width="3%" align="center">
								<!--img src="../../../img/btnRecuperar.gif" width="17" height="17" alt="Guardar Avances" style="cursor:pointer;" onclick="LoadPrgvsAvanComponente();" title="Refrescar Indicadores" / osktgui-->
								<input type="button" value="Refrescar" class="btn_save_custom"
								onclick="LoadPrgvsAvanComponente();"
								title="Refrescar Indicadores" />
							</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td colspan="2">&nbsp;</td>
						</tr>
					</table>
					<div id="divPrgAvanceComp" class="TableGrid">
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
						<table width="100%" border="0" cellspacing="0" cellpadding="0"
							class="TableEditReg">
							<tr>
								<td width="8%">&nbsp;</td>
								<td colspan="2">&nbsp;</td>
								<td width="36%" rowspan="3" align="center" valign="middle"></td>
							</tr>
							<tr>
								<td><b>Actividad</b>&nbsp;</td>
								<td width="53%"><select name="cboactividad_sub"
									id="cboactividad_sub" style="width: 500px;"
									onchange="LoadPrgSActCriticas();">
										<option value=""></option>
                  <?php
                $rs = $objInf->ListaActividades($idProy);
                $objFunc->llenarComboI($rs, 'codigo', 'actividad');
                ?>
                </select></td>
								<td width="3%" align="center">
									<!--img src="../../../img/btnRecuperar.gif" width="17" height="17" alt="Guardar Avances" style="cursor:pointer;" onclick="LoadPrgSActCriticas();" title="Refrescar SubActividades" /-->
									<input type="button" value="Refrescar" class="btn_save_custom"
									onclick="LoadPrgSActCriticas();"
									title="Refrescar SubActividades" />
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td colspan="2">&nbsp;</td>
							</tr>
						</table>
						<div id="divPrgSActCriticas" class="TableGrid">
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
					<div id="divCalifMonitor" style="min-height: 200px;"></div>
				</div>
				<div class="TabbedPanelsContent">
					<div id="divAnalisisAvanc"></div>
				</div>
				<div class="TabbedPanelsContent">
					<div id="divInfAdicional"></div>
				</div>
			</div>
			<p>&nbsp;</p>
		</div>

		<input type='hidden' id='usrPrf'
			value='<?php echo $ObjSession->PerfilID; ?>' /> <input type='hidden'
			id='t55_mt_flg'
			value='<?php echo isset($t55_mt_flg) && $t55_mt_flg != '' ? $t55_mt_flg : '0'; ?>' />
		<input type='hidden' id='t55_mf_flg'
			value='<?php echo isset($t55_mf_flg) && $t55_mf_flg != '' ? $t55_mf_flg : '0'; ?>' />
		<input type='hidden' id='t55_cmt_flg'
			value='<?php echo isset($t55_cmt_flg) && $t55_cmt_flg != '' ? $t55_cmt_flg : '0'; ?>' />
		<input type='hidden' id='t55_cmf_flg'
			value='<?php echo isset($t55_cmf_flg) && $t55_cmf_flg != '' ? $t55_cmf_flg : '0'; ?>' />

		<script language="javascript" type="text/javascript">

	function btnRevision_Clic(){
		if(confirm("¿Estas seguro de enviar el informe a revisión?")) {
			var anio = $('#cboanio').val();
			var BodyForm = "idProy="+$('#txtCodProy').val()+ "&id="+anio;
			var sURL = "inf_unico_anual_process.php?action=<?php echo(md5("ajax_envrev"))?>";
			var req = Spry.Utils.loadURL("POST", sURL, true, MySuccessCall, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCall});
		}
	}

	function Guardar_InformeCab		()
	{
		<?php $ObjSession->AuthorizedPage(); ?>	
		
		var anio = $('#cboanio').val();
		var per = $('#t55_periodo').val();
		var est = $('#t55_estado').val();
		var ini = $("#finicio").val();
		var fin = $("#ffinal").val();
		var per = $("#finicio option[selected]").text() + ' - ' + $("#ffinal option[selected]").text();
	
		if (anio=="" || anio==null){alert("Seleccione A\u00f1o del Informe"); return false;}
		if (per=="" || per==null){alert("No se ha especificado el periodo del Informe"); return false;}
		if (parseInt(ini) >= parseInt(fin)) {alert("Periodo de Referencia inicial no puede ser mayor que el final."); return false;}
		if (est=="" || est==null){alert("Seleccione Estado del Informe"); return false;}
		var BodyForm="cboanio="+anio+"&t55_anio="+$("#t55_anio").val()+"&t55_periodo="+per+"&fini="+ini+"&ffin="+fin+"&t55_fch_pre="+$("#t55_fch_pre").val()+"&t55_estado="+est+"&t02_cod_proy="+$('#txtCodProy').val();  ;
		var sURL = "inf_unico_anual_process.php?action=<?php echo($action);?>";
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
		btnEditar_Clic($('#cboanio').val(),"", "");
	  }
	  else
	  {
		  alert(respuesta);
	  }  
	  
	}
  </script>

		<script language="javascript">

   function LoadPrgvsAvanComponente()
	{
		var comp = $('#cbocomponente_pva').val(); 
		var anio = $('#cboanio').val();
		var idnum = $('#t55_id').val();
		if(anio=="" || anio==null){alert("Seleccione A\u00f1o del Informe"); $('#cbocomponente_pva').val(null); return false;}
		var BodyForm = "action=<?php echo(md5("lista_ind_comp"));?>&idProy=<?php echo($idProy);?>&idComp="+ comp + "&idAnio="+anio+"&idnum="+idnum;
	 	var sURL = "inf_unico_anual_prog_avan_comp.php";
		$('#divPrgAvanceComp').html("<p align='center'><img src='../../../img/indicator.gif' width='16' height='16' /><br>Cargando..<br></p>");
	 	var req = Spry.Utils.loadURL("POST", sURL, true, SuccessPrgvsAvanComponente, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
	}
	function SuccessPrgvsAvanComponente	 (req)
	{
	   var respuesta = req.xhRequest.responseText;
	   $("#divPrgAvanceComp").html(respuesta);
 	   return;
	}
    
	function onErrorLoad			(req)
	{
		alert("Ocurrio un error al cargar los datos");
	}
	
	function LoadPrgSActCriticas	()
	{
		var activ = $('#cboactividad_sub').val(); 
		var anio = $('#cboanio').val();
		var idnum = $('#t55_id').val();
		if(anio=="" || anio==null){alert("Seleccione A\u00f1o del Informe"); $('#cboactividad_sub').val(null); return false;}
		var BodyForm = "action=<?php echo(md5("lista_sub_act"));?>&idProy=<?php echo($idProy);?>&idActiv="+ activ + "&idAnio="+anio+"&idnum="+idnum;
		 
	 	var sURL = "inf_unico_anual_prog_sub_act.php";
		$('#divPrgSActCriticas').html("<p align='center'><img src='../../../img/indicator.gif' width='16' height='16' /><br>Cargando..<br></p>");
	 	var req = Spry.Utils.loadURL("POST", sURL, true, SuccessSubAct, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
	}
	function SuccessSubAct			(req)
	{
	   var respuesta = req.xhRequest.responseText;
	   $("#divPrgSActCriticas").html(respuesta);
 	   return;
	}
	
	function LoadAvanceProyecto(recargar)
	{
		if($('#divAvancesProyecto').html()!="")
		{ if(!recargar){return false;}	}
		var anio = $('#cboanio').val();
		var idnum = $('#t55_id').val();
		
		if(anio=="" || anio==null){alert("Seleccione A\u00f1o del Informe"); return false;}
		var BodyForm = "action=<?php echo(md5("avance_presupuestal"));?>&idProy=<?php echo($idProy);?>&idAnio=" 
						+ anio + "&t25_ver_inf=" + $('#t25_ver_inf').val() + "&idnum=" + idnum
						+ "&mode=<?php echo $action; ?>";
	 	
		var sURL = "inf_unico_anual_avances.php";
		
		$('#divAvancesProyecto').html("<p align='center'><img src='../../../img/indicator.gif' width='16' height='16' /><br>Cargando..<br></p>");
	 	var req = Spry.Utils.loadURL("POST", sURL, true, SuccessAvanceProyecto, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
	}
	function SuccessAvanceProyecto		(req)
	{
	   var respuesta = req.xhRequest.responseText;
	   $("#divAvancesProyecto").html(respuesta);
 	   return;
	}
	
	function LoadCalifMonitor(recargar)
	{
		if($('#divCalifMonitor').html()!="")
		{
			if(!recargar){return false;}
		}
		var anio = $('#cboanio').val();
		var idnum = $('#t55_id').val();
		var aVerInf  = $('#t55_ver_inf').val();
		if(anio=="" || anio==null){alert("Seleccione A\u00f1o del Informe"); return false;}
		var BodyForm = "action=<?php echo(md5("lista_analisis"));?>&idProy=<?php echo($idProy);?>&idAnio="+anio+"&t25_ver_inf="+aVerInf+"&idNum="+idnum;
	 	var sURL = "inf_unico_anual_calificacion.php";
		$('#divCalifMonitor').html("<p align='center'><img src='../../../img/indicator.gif' width='16' height='16' /><br>Cargando..<br></p>");
	 	var req = Spry.Utils.loadURL("POST", sURL, true, SuccessCalifMonitor, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
	}
	function SuccessCalifMonitor		(req)
	{
	   var respuesta = req.xhRequest.responseText;
	   $("#divCalifMonitor").html(respuesta);
 	   return;
	}
	
	
	function LoadAnalisisAvanc(recargar)
	{
		if($('#divAnalisisAvanc').html()!="")
		{
			if(!recargar){return false;}
		}
		var idnum = $('#t55_id').val();
		var anio = $('#cboanio').val();
		var idnum = $('#t55_id').val();
		if(anio=="" || anio==null){alert("Seleccione A\u00f1o del Informe"); return false;}
		var BodyForm = "action=<?php echo(md5("lista_analisis"));?>&idProy=<?php echo($idProy);?>&idAnio="+anio+"&t25_ver_inf="+$('#t25_ver_inf').val()+"&idNum="+idnum;
	 	var sURL = "inf_unico_anual_analisis_avances.php";
		$('#divAnalisisAvanc').html("<p align='center'><img src='../../../img/indicator.gif' width='16' height='16' /><br>Cargando..<br></p>");
	 	var req = Spry.Utils.loadURL("POST", sURL, true, SuccessAnalisisAvanc, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
	}
	function SuccessAnalisisAvanc		(req)
	{
	   var respuesta = req.xhRequest.responseText;
	   $("#divAnalisisAvanc").html(respuesta);
 	   return;
	}
	
	function LoadInfAdicional(recargar)
	{
		if($('#divInfAdicional').html()!="")
		{
			if(!recargar){return false;}
		}
		var idnum = $('#t55_id').val();
	
		var num = $('#txtnuminf').val();
		var BodyForm = "action=<?php echo(md5("lista_calificacion"));?>&idProy=<?php echo($idProy);?>&idNum="+idnum;
	 	var sURL = "inf_unico_anual_califica_conclu.php";
		$('#divInfAdicional').html("<p align='center'><img src='../../../img/indicator.gif' width='16' height='16' /><br>Cargando..<br></p>");
	 	var req = Spry.Utils.loadURL("POST", sURL, true, SuccessCalifica, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
	}
	
	function SuccessCalifica(req)
	{
	   var respuesta = req.xhRequest.responseText;
	   $("#divInfAdicional").html(respuesta);
 	   return;
	}

	$("#t55_fch_pre").datepicker();
	$("#t55_fch_pre").mask("99/99/9999");
	
	$("#cbotrim").css("display", "none");
	
	LoadAvanceProyecto();
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