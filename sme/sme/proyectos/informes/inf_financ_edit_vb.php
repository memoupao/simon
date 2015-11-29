<?php
include("../../../includes/constantes.inc.php");
include("../../../includes/validauser.inc.php");
require (constant("PATH_CLASS") . "BLInformes.class.php");

$objInf = new BLInformes();
$idProy = $objFunc->__Request('idProy');
$idAnio = $objFunc->__Request('idanio');
$idMes = $objFunc->__Request('idmes');
$monitor_fe = false;
$mode_vista = $objFunc->__GET('mode_vista');
$action = $objFunc->__GET('mode');

if (md5("ajax_edit") == $action) {
    $objFunc->SetSubTitle('Informe Financiero - Editar Registro');
    $row = $objInf->InformeFinancieroSeleccionar($idProy, $idAnio, $idMes);
}

if (md5("mf") == $mode_vista) {
    $monitor_fe = true;
}

$HardCode = new HardCode();

$IsMF = false;
if ($ObjSession->PerfilID == $HardCode->GP || $ObjSession->PerfilID == $HardCode->RA) {
    $IsMF = true;
}

$isSE = false;
if ($ObjSession->PerfilID == $HardCode->SE) {
    $isSE = true;
}


if ($idProy == "") {
    ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=charset=utf-8" />
<title>Detalle del Informe Mensual</title>
<script language="javascript" type="text/javascript" src="../../../jquery.ui-1.5.2/jquery-1.2.6.js"></script>
<script src="../../../SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
<link href="../../../SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />
<link href="../../../css/itemplate.css" rel="stylesheet" type="text/css" media="all" />
</head>

<body>
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
				    <td width="10%">Proyecto: <?php echo($idProy);?></td>
					<td width="10%"><button class="Button" onclick="Guardar_InformeCab(); return false;" value="Guardar">Guardar</button></td>
					<td width="18%"><button class="Button" onclick="btnCancelar_Clic(); return false;" value="Cancelar" style="white-space: nowrap;">Cerrar y Volver</button></td>
					<td width="7%" nowrap="nowrap">
           <?php if( is_array($row)) { echo("Exportar Anexo"); }?>
          </td>
					<td width="15%">
          <?php if( is_array($row)) { ?>
          <select name="cboExportarAnexo" id="cboExportarAnexo"
						style="width: 150px; font-size: 10px;"
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
					<td width="15%">
			 <?php if($monitor_fe){?>
				<button class="Button" onclick="return false;" disabled="disabled"
							value="Cancelar">V°B° Gestor de Proyecto</button>
			<?php }?>
			<?php if($isSE || $IsMF){?>
				<input type="checkbox" id="vb_se" name="vb_se" <?php echo($row['vb_se']==1?'checked':'');?> <?php echo($IsMF?'disabled':'');?>/>V°B° SE
			<?php }?>
		  </td>
					<td width="25%" align="right"><?php echo($objFunc->SubTitle) ;?> </td>
				</tr>
			</table>
		</div>

		<div>
			<div id="divCabeceraInforme">
				<table width="100%" border="0" cellspacing="0" cellpadding="0"
					class="TableEditReg">
					<tr>
						<td colspan="6"><strong>1. Caratula </strong></td>
					</tr>
					<tr>
						<td width="9%" height="25">Año</td>
						<td width="18%"><select name="cboanio" id="cboanio"
							style="width: 100px;">
        <?php
        $objProy = new BLProyecto();
        $ver_proy = $objProy->MaxVersion($idProy);
        $rs = $objProy->ListaAniosProyecto($idProy, $ver_proy);
        $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $idAnio);
        $objProy = NULL;
        ?>
        </select> <input name="t40_anio" type="hidden" id="t40_anio"
							value="<?php echo($idAnio);?>" /></td>
						<td width="5%">Mes <input name="t40_mes" type="hidden"
							id="t40_mes" value="<?php echo($idMes);?>" /></td>
						<td width="17%"><select name="cbomes" id="cbomes"
							style="width: 110px;">
								<option value="" selected="selected"></option>
         <?php
        require (constant("PATH_CLASS") . "BLTablasAux.class.php");
        $objTablas = new BLTablasAux();
        $rs = $objTablas->ListadoMeses();
        $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $idMes);
        ?>
        </select></td>
						<td width="6%" align="center" nowrap="nowrap">Mes Ref.</td>
						<td width="25%"><select name="t40_periodo" id="t40_periodo"
							style="width: 110px;">
								<option value="" selected="selected"></option>
          <?php
        $rs = $objTablas->ListadoMesesCalendario();
        $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $row['t40_periodo']);
        ?>
        </select></td>
						<td width="20%"></td>
					</tr>
					<tr>
						<td height="27">Fecha de Presentación</td>
						<td nowrap="nowrap"><input name="t40_fch_pre" type="text"
							id="t40_fch_pre" value="<?php echo($row['t40_fch_pre']);?>"
							size="20" maxlength="12" readonly="readonly" /></td>

						<td nowrap="nowrap">Estado</td>
						<td colspan="1"><select name="t40_est_eje" id="t40_est_eje"
							style="width: 110px;" onchange="EnviarInformeA();"
							<?php if(($ObjSession->PerfilID!=$HardCode->GP) && ( $ObjSession->PerfilID==$HardCode->Ejec && $row['t40_est_eje']!=47) ) { ?>
							disabled <?php }?>>
								<option value=""></option>
          <?php
        $rs = $objTablas->EstadoInformes();
        $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $row['t40_est_eje']);
        ?>
        </select></td>
		<?php if($ObjSession->PerfilID==$HardCode->Ejec || $ObjSession->PerfilID==$HardCode->FE){?>
		<td colspan="3" align="left"><input type='checkbox' name='IFTerminado'
							id="IFTerminado" <?php if($row['inf_fi_ter']== 1){?> echo checked
							<?php } ?>>Informe Finaciero Terminado<br></td>
		<?php } ?>
      </tr>



					<tr>
						<td height="43">Observaciones</td>
						<td colspan="5"><textarea name="t40_obs" cols="100" rows="2"
								id="t40_obs"><?php echo($row['t40_obs']);?></textarea></td>
					</tr>

      <?php if($IsMF) {  ?>
      <tr>
						<td>Observaciones del Gestor de Proyectos</td>
						<td colspan="5"><textarea name="t40_obs_moni" cols="100" rows="2"
								id="t40_obs_moni"><?php echo($row['t40_obs_moni']);?></textarea></td>
					</tr>
      <?php } else {  ?>
	  <input type="hidden" id="t40_obs_moni" name="t40_obs_moni"
						value="<?php echo($row['t40_obs_moni']);?>" />
	  <?php
        if ($row['t40_obs_moni'] != "") {
            ?>
      <tr>
						<td>Observaciones del Gestor de Proyectos</td>
						<td colspan="5"><?php echo($row['t40_obs_moni']);?></td>
					</tr>
       <?php

}
    }
    ?>
    </table>
			</div>

	<?php
// Agregado el 28/11/2011
$objInf = new BLInformes();
$mostrar = false;
$mostrar = $objInf->vPIFinancieroGuardado($idProy, $idAnio, $idMes);
if ($mostrar) {
    ?>

    <div id="ssTabInforme" class="TabbedPanels">
				<ul class="TabbedPanelsTabGroup">
					<li class="TabbedPanelsTab" tabindex="1">Gastos / Fondoempleo</li>
					<li class="TabbedPanelsTab" tabindex="2">Gastos / Contrapartidas</li>
					<li class="TabbedPanelsTab" tabindex="3"
						onclick="LoadOtrosGastos(false);">Otros Ingresos / Bancos</li>
					<li class="TabbedPanelsTab" tabindex="4"
						onclick="LoadExcedentes(false);">Excedentes por Ejecutar</li>
					<li class="TabbedPanelsTab" tabindex="5"
						onclick="LoadAnexos(false);">Anexos</li>
				</ul>
				<div class="TabbedPanelsContentGroup">
					<div class="TabbedPanelsContent">
						<table width="100%" border="0" cellspacing="0" cellpadding="0"
							class="TableEditReg">
							<tr>
								<td width="8%">&nbsp;</td>
								<td colspan="2">&nbsp;</td>
								<!--td width="36%" rowspan="3" align="center" valign="middle" osktgui-->
								<td width="30%" rowspan="3" align="center" valign="middle">
                <?php if($IsMF) {  ?>
                <!--div style="text-align:center; color:navy; font-weight:bold; font-size:10px; cursor:pointer; width:120px;"  onclick="Guardar_ComentariosGastos();" >
                <img src="../../../img/aplicar.png" width="24" height="24" alt="Guardar Comentarios" /><br />
                Guardar Comentarios
                </div osktgui--> <input value="Guardar Comentarios"
									title="Guardar Comentarios"
									onclick="Guardar_ComentariosGastos();" class="btn_save_custom" />
                <?php } else {  ?>
                <!--div style="text-align:center; color:navy; font-weight:bold; font-size:10px; cursor:pointer; width:120px;" title="Guardar Avance de Costos"  onclick="Guardar_AvanceGastos();" >
                <img src="../../../img/aplicar.png" width="24" height="24" alt="Guardar Avances" /><br />
                Guardar Avance
                </div osktgui--> <input value="Guardar Avance"
									id="btnGastosFE" title="Guardar Avance de Costos"
									onclick="Guardar_AvanceGastos();" class="btn_save_custom"
									style="width: 100px;" />
                <?php } ?>
                </td>
							</tr>
							<tr>
								<td nowrap="nowrap"><b>Componente</b>&nbsp;</td>
								<td width="42%"><select name="cbocomponente_fe"
									id="cbocomponente_fe" style="width: 500px;"
									onchange="LoadPresupuesto();">
										<option value=""></option>

                  <?php
    $rs = $objInf->ListaComponentes($idProy);
    $objFunc->llenarComboI($rs, 't08_cod_comp', 'componente', '');
    ?>
                   <option value="mp" style="color: navy;"><?php echo($HardCode->CodigoMP);?>.- Manejo del Proyecto</option>
								</select></td>
								<!--td width="14%" align="left" -->
								<td width="20%" align="left">&nbsp; <!--img src="../../../img/btnRecuperar.gif" width="17" height="17" style="cursor:pointer;" onclick="LoadPresupuesto();" title="Refrescar Presupuesto" / osktgui-->
									<input type="button" value="Refrescar" class="btn_save_custom"
									onclick="LoadPresupuesto();" title="Refrescar Presupuesto" />
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td colspan="2">&nbsp;</td>
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
									<td height="30" colspan="2" align="left" valign="middle"
										nowrap="nowrap"><b>Fuente de Financiamiento</b></td>
									<td valign="middle"><select name="cbofuentes" id="cbofuentes"
										style="width: 300px;" onchange="LoadPresupuesto2();"
										class="presup">
											<option value=""></option>
                    <?php
    require (constant("PATH_CLASS") . "BLPresupuesto.class.php");
    $objPresup = new BLPresupuesto();
    $rs = $objPresup->ListaFuentesFinanciamiento($idProy, false);
    $objFunc->llenarComboI($rs, 't01_id_inst', 't01_nom_inst');
    $objPresup = NULL;
    ?>
                </select></td>
									<td valign="middle">&nbsp;</td>
									<td width="2%" rowspan="2" align="center">&nbsp;</td>

									<!--td width="36%" rowspan="2" align="center" valign="middle" osktgui-->
									<td width="36%" rowspan="2" align="center" valign="bottom">
                 <?php if($IsMF) {  ?>
                <!--div style="text-align:center; color:navy; font-weight:bold; font-size:10px; cursor:pointer; width:120px;"  onclick="Guardar_ComentariosGastosFTE();" >
                <img src="../../../img/aplicar.png" width="24" height="24" alt="Guardar Comentarios" /><br />
                Guardar Comentarios
                </div osktgui--> <input value="Guardar Comentarios"
										title="Guardar Comentarios"
										onclick="Guardar_ComentariosGastosFTE();"
										class="btn_save_custom" />
                <?php } else {  ?>
                  <!--div style="text-align:center; color:navy; font-weight:bold; font-size:10px; cursor:pointer; width:112px;" title="Guardar Gastos de la Fuente de Financiamiento"  onclick="Guardar_AvanceGastosFTE();" >
                    <img src="../../../img/aplicar.png" width="24" height="24" alt="Guardar Gastos" /><br />
                    Guardar Gastos
                  </div osktgui--> <input id="btnGastosCP"
										value="Guardar Gastos"
										style="width: 90px; margin-bottom: 3px; padding: 4px;"
										title="Guardar Gastos de la Fuente de Financiamiento"
										onclick="Guardar_AvanceGastosFTE();" class="btn_save_custom" />
                <?php } ?>
                </td>
								</tr>
								<tr valign="middle">
									<td width="7%" height="30" nowrap="nowrap"><b>Componente</b></td>
									<td colspan="3" nowrap="nowrap">
										<!--select name="cbocomponente_fte" id="cbocomponente_fte" style="width:500px;" onchange="LoadPresupuesto2();" osktgui-->
										<select name="cbocomponente_fte" id="cbocomponente_fte"
										style="width: 450px;" onchange="LoadPresupuesto2();">
											<option value=""></option>
                 <?php
    $rs = $objInf->ListaComponentes($idProy);
    $objFunc->llenarComboI($rs, 't08_cod_comp', 'componente', '');
    ?>
                   <option value="mp" style="color: navy;"><?php echo($HardCode->CodigoMP);?>.- Manejo del Proyecto</option>
									</select> <!--img src="../../../img/btnRecuperar.gif" width="17" height="17" style="cursor:pointer;" onclick="LoadPresupuesto2();" title="Refrescar Presupuesto" / osktgui-->
										<input type="button" value="Refrescar" class="btn_save_custom"
										onclick="LoadPresupuesto2();" title="Refrescar Presupuesto" />
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
						<div id="divOtrosGastos"></div>
					</div>
					<div class="TabbedPanelsContent">
						<div id="divExcedenteEjec"></div>
					</div>
					<div class="TabbedPanelsContent">
						<div id="divAnexos"></div>
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
    function EnviarInformeA()
    {
    	<?php $ObjSession->AuthorizedPage('EDITAR'); ?>

    	var estado = $("#t40_est_eje").val();
    	var nomestado = $("#t40_est_eje>option:selected").text() ;
    	if(estado==""){return false;}


    	if(confirm("Esta seguro de que desea enviar el informe a estado \""+nomestado +"\""))
    	{
    		Guardar_InformeCab();
    	}
    }

	function ExportarAnexos()
	{
		var idAnx = $('#cboExportarAnexo').val();
		if(idAnx=="") {return ;}

		var arrayControls = new Array();
			arrayControls[0] = "idProy=<?php echo($idProy);?>";
			arrayControls[1] = "idAnio=<?php echo($idAnio);?>" ;
			arrayControls[2] = "idMes=<?php echo($idMes);?>" ;
			arrayControls[3] = "idFte=<?php echo($HardCode->codigo_Fondoempleo);?>" ;

		var params = arrayControls.join("&");
		var sID = "0" ;
		switch(idAnx)
		{
			case "1" : sID = "36"; break;
			case "2" : sID = "37"; break;
			case "3" : sID = "38"; break;
		}
		showReport(sID, params);
		return;
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

    function Guardar_InformeCab()
	{
		<?php $ObjSession->AuthorizedPage(); ?>

		var anio = $('#cboanio').val();
		var mes = $('#cbomes').val();
		var per = $('#t40_periodo').val();
		var est = $('#t40_est_eje').val();

		if(anio=="" || anio==null){alert("Seleccione A\u00f1o del Informe"); return false;}
		if(mes=="" || mes==null){alert("Seleccione Mes del Informe");  return false;}
		if(per=="" || per==null){alert("No se ha especificado el periodo de referencia del Informe"); return false;}
		if(est=="" || est==null){alert("Seleccione Estado del Informe"); return false;}

		var arrParams = new Array();
			arrParams[0] = "t02_cod_proy=" + '<?php echo($idProy);?>';
			arrParams[1] = "cboanio=" + anio ;
			arrParams[2] = "cbomes=" + mes ;
			arrParams[3] = "t40_fch_pre=" + $("#t40_fch_pre").val();
			arrParams[4] = "t40_periodo=" + per ;
			arrParams[6] = "t40_est_eje=" + est ;
			arrParams[7] = "t40_obs=" + $("#t40_obs").val();
			arrParams[8] = "t40_est_mon=" + est ;
			arrParams[9] = "t40_anio=" + $("#t40_anio").val();
			arrParams[10] = "t40_mes=" + $("#t40_mes").val();
			arrParams[11] = "t40_est_mon=" + est ;
			arrParams[12] = "t40_obs_moni=" + $("#t40_obs_moni").val();

			var checkeado=$("#IFTerminado").attr("checked");
			var checkbox;
			if(checkeado) {
			 checkbox=1;
			} else {
			 checkbox=0;
			}

		    arrParams[13] = "inf_fi_ter=" + checkbox;

		    checkeado = $("#vb_se").attr("checked");
			if(checkeado) {
			 checkbox=1;
			} else {
			 checkbox=0;
			}

			arrParams[14] = "vb_se=" + checkbox;

		var BodyForm = arrParams.join("&");

		var sURL = "inf_financ_process.php?action=<?php echo(md5("ajax_edit_vb"));?>";
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
		alert(respuesta.replace(ret,""));
		btnEditar_Clic('<?php echo($idProy);?>', $('#cboanio').val(), $('#cbomes').val());
	  }
	  else
	  {alert(respuesta);}

	}
  </script>

		<script language="javascript">
	function onErrorLoad(req)
	{
		alert("Ocurrio un error al cargar los datos");
	}
	function LoadPresupuesto()
	{
		var anio  = $('#cboanio').val();
		var mes   = $('#cbomes').val();
		var comp  = $('#cbocomponente_fe').val();
		var idFte = "<?php echo($HardCode->codigo_Fondoempleo)?>";
		var BodyForm = "action=<?php echo(md5("lista_presupuesto"));?>&idProy=<?php echo($idProy);?>&idComp="+ comp  + "&idAnio="+anio+"&idMes="+mes+"&idFte="+idFte;
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
	function LoadPresupuesto2		()
	{
		var fte = $('#cbofuentes').val();
		var anio  = $('#cboanio').val();
		var mes   = $('#cbomes').val();
		var comp  = $('#cbocomponente_fte').val();

		var BodyForm = "action=<?php echo(md5("lista_presupuesto"));?>&idProy=<?php echo($idProy);?>&idComp="+ comp  + "&idAnio="+anio+"&idMes="+mes+"&idFte="+fte;
		var sURL = "";
		if(comp=="mp")
		{ sURL = "inf_financ_presup_mp.php"; }
		else
		{ sURL = "inf_financ_presup.php"; }

		$('#divAvancePresupFuentes').html("<p align='center'><img src='../../../img/indicator.gif' width='16' height='16' /><br>Cargando..<br></p>");
	 	var req = Spry.Utils.loadURL("POST", sURL, true, SuccessPresup2, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
	}
	function SuccessPresup2			(req)
	{
	   var respuesta = req.xhRequest.responseText;
	   $("#divAvancePresupFuentes").html(respuesta);
 	   return;
	}
	function Guardar_AvanceGastos	()
	{
	<?php $ObjSession->AuthorizedPage(); ?>

	var BodyForm=$("#FormData .presup").serialize();

	if(confirm("Estas seguro de Guardar los gastos ingresados para el Informe  ?"))
	  {
		var sURL = "inf_financ_process.php?action=<?php echo(md5('ajax_gastos_fe'));?>";
		var req = Spry.Utils.loadURL("POST", sURL, true, GastosSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
	  }
	}
	function GastosSuccessCallback	(req)
	{
	  var respuesta = req.xhRequest.responseText;
	  respuesta = respuesta.replace(/^\s*|\s*$/g,"");
	  var ret = respuesta.substring(0,5);
	  if(ret=="Exito")
	  {
		LoadPresupuesto();
		alert(respuesta.replace(ret,""));
	  }
	  else
	  {alert(respuesta);}
	}

	function Guardar_ComentariosGastos	()
	{
	<?php $ObjSession->AuthorizedPage(); ?>

	var BodyForm=$("#FormData .presup").serialize();

	if(confirm("Estas seguro de Guardar los comentario ingresados para los gastos  ?"))
	  {
		var sURL = "inf_financ_process.php?action=<?php echo(md5('ajax_coment_fe'));?>";
		var req = Spry.Utils.loadURL("POST", sURL, true, GastosSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
	  }
	}

	function Guardar_AvanceGastosFTE	()
	{
	<?php $ObjSession->AuthorizedPage(); ?>

	var BodyForm=$("#FormData .presup").serialize();

	if(confirm("Estas seguro de Guardar los gastos de la Fuente de Financiamiento ingresados para el Informe  ?"))
	  {
		var sURL = "inf_financ_process.php?action=<?php echo(md5('ajax_gastos_fte'));?>";
		var req = Spry.Utils.loadURL("POST", sURL, true, GastosSuccessCallbackFTE, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
	  }
	}
	function GastosSuccessCallbackFTE	(req)
	{
	  var respuesta = req.xhRequest.responseText;
	  respuesta = respuesta.replace(/^\s*|\s*$/g,"");
	  var ret = respuesta.substring(0,5);
	  if(ret=="Exito")
	  {
		LoadPresupuesto2();
		alert(respuesta.replace(ret,""));
	  }
	  else
	  {alert(respuesta);}
	}
	function Guardar_ComentariosGastosFTE	()
	{
	<?php $ObjSession->AuthorizedPage(); ?>

	var BodyForm=$("#FormData .presup").serialize();

	if(confirm("Estas seguro de Guardar los comentarios  ?"))
	  {
		var sURL = "inf_financ_process.php?action=<?php echo(md5('ajax_comentarios_fte'));?>";
		var req = Spry.Utils.loadURL("POST", sURL, true, GastosSuccessCallbackFTE, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
	  }
	}
	function LoadOtrosGastos(arg)
	{

		if($('#divOtrosGastos').html()!="")
		{ if(!arg){return false;} 	}

		var anio  = $('#cboanio').val();
		var mes   = $('#cbomes').val();

		var BodyForm = "action=<?php echo(md5("lista_OtrosGastos"));?>&idProy=<?php echo($idProy);?>&idAnio="+anio+"&idMes="+mes;
	 	var sURL = "inf_financ_otrogasto.php";
		$('#divOtrosGastos').html("<p align='center'><img src='../../../img/indicator.gif' width='16' height='16' /><br>Cargando..<br></p>");
	 	var req = Spry.Utils.loadURL("POST", sURL, true, SuccessLoadOtrosGastos, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
	}
	function SuccessLoadOtrosGastos			(req)
	{
	   var respuesta = req.xhRequest.responseText;
	   $("#divOtrosGastos").html(respuesta);
 	   return;
	}
	function LoadExcedentes(arg)
	{
		if($('#divExcedenteEjec').html()!="")
		{ if(!arg){return false;} 	}

		var anio  = $('#cboanio').val();
		var mes   = $('#cbomes').val();

		var BodyForm = "action=<?php echo(md5("lista_excedentes"));?>&idProy=<?php echo($idProy);?>&idAnio="+anio+"&idMes="+mes;
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
		var BodyForm = "action=<?php echo(md5("lista_anexos"));?>&idProy=<?php echo($idProy);?>&idAnio="+anio+"&idMes="+mes;
	 	var sURL = "inf_financ_anexos.php";
		$('#divAnexos').html("<p align='center'><img src='../../../img/indicator.gif' width='16' height='16' /><br>Cargando..<br></p>");
	 	var req = Spry.Utils.loadURL("POST", sURL, true, SuccessAnexos, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
	}
	function SuccessAnexos(req)
	{
	   var respuesta = req.xhRequest.responseText;
	   $("#divAnexos").html(respuesta);
 	   return;
	}
  </script>
		<script language="javascript" type="text/javascript">
	//jQuery("#t40_fch_pre").datepicker();
	if ($.isFunction($.mask)) {
		$("#t40_fch_pre").mask("99/99/9999");
	}


	<?php if(md5("ajax_new")==$action || md5("ajax_edit")==$action) { ?>
		$("#t40_est_eje option").attr('disabled','disabled');
		$("#t40_est_eje option:selected").removeAttr('disabled');

		$("#cboanio option").attr('disabled','disabled');
		$("#cboanio option:selected").removeAttr('disabled');

		$("#cbomes option").attr('disabled','disabled');
		$("#cbomes option:selected").removeAttr('disabled');

		$("#t40_periodo option").attr('disabled','disabled');
		$("#t40_periodo option:selected").removeAttr('disabled');

	<?php } ?>


	var estado = $("#t40_est_eje").val();
	var Elaboracion= "<?php echo($HardCode->EstInf_Ela);?>";
	var AprobadoMT = "<?php echo($HardCode->EstInf_Aprob);?>";
	var Correccion = "<?php echo($HardCode->EstInf_Corr);?>";
	var Revision   = "<?php echo($HardCode->EstInf_Rev);?>";

	$('#t40_est_eje option[value="'+<?php echo($HardCode->especTecAprobRA);?>+'"]').remove();

	<?php if($ObjSession->PerfilID == $HardCode->GP || $ObjSession->PerfilID == $HardCode->RA )  { ?>
		if(estado==Revision)
		  {
			  $('#t40_est_eje option[value="'+Correccion+'"]').removeAttr('disabled');
			  $('#t40_est_eje option[value="'+AprobadoMT+'"]').removeAttr('disabled');
		  }
	<?php } ?>

	<?php if($ObjSession->PerfilID == $HardCode->SE)  { ?>
    	if(estado==Revision)
    	  {
    		$('#t40_est_eje option[value="'+Correccion+'"]').removeAttr('disabled');
    	  }
    <?php } ?>

	<?php if($ObjSession->PerfilID == $HardCode->Ejec || $ObjSession->PerfilID == $HardCode->FE) { ?>
		if(estado==Elaboracion || estado==Correccion)
		  {
			  $('#t40_est_eje option[value="'+Revision+'"]').removeAttr('disabled');
		  }
	<?php } ?>

	<?php if($ObjSession->PerfilID == $HardCode->Ejec && $row['inf_fi_ter']==1){ ?>
			$("#t40_obs").attr("readonly","readonly");
			$("#btnGastosCP").attr("disabled","disabled");
			$("#btnGastosFE").attr("disabled","disabled");
	<?php } ?>


  </script>
  <?php if($idProy=="") { ?>
</form>
	<script type="text/javascript">
<!--
var TabsInforme = new Spry.Widget.TabbedPanels("ssTabInforme");
//-->
</script>
</body>
</html>
<?php } ?>