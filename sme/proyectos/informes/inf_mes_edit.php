<?php
include("../../../includes/constantes.inc.php");
include("../../../includes/validauser.inc.php");
require (constant("PATH_CLASS") . "BLInformes.class.php");
// require (constant("PATH_CLASS") . "HardCode.class.php");
require (constant("PATH_CLASS") . "BLTablasAux.class.php");

$objInf = new BLInformes();
$objHC = new HardCode();

error_reporting("E_PARSE");
$idProy = $objFunc->__Request('idProy');
$idAnio = $objFunc->__Request('idanio');
$idMes = $objFunc->__Request('idmes');
$idVs = $objFunc->__Request('idversion');

$view = $objFunc->__GET('mode');
$accion = $objFunc->__GET('accion');

$perfil = $objFunc->oSession->PerfilID;

$IsMF = false;
if ($ObjSession->PerfilID == $objHC->GP || $ObjSession->PerfilID == $objHC->RA) {
    $IsMF = true;
}

$isSE = false;
if ($ObjSession->PerfilID == $objHC->SE) {
    $isSE = true;
}

if (md5("ajax_new") == $view) {
    $objFunc->SetSubTitle('Informe Mensual - Nuevo Registro');
    $row = 0;
    $rowFinanciero = 0; // modificado 28/11/2011
    $idVs = 1;

    $rowc = $objInf->InformeMensualUltimo($idProy);
	//var_dump($rowc);
    $idAnio = $rowc['new_anio'];
    $idMes = $rowc['new_mes'];
    $idFecha = date(d . '/' . m . '/' . Y);
    $idEstado = 45;
    $idPeriodo = $rowc['periodo'];

    /*
    list ($idAnio, $idMes) = $objInf->GetStartingYearMoth($idProy, $rowc['new_anio'], $rowc['new_mes']);

    list ($aPerNum, $aPerTxt) = $objInf->GetStartingPeriod($idProy, $idAnio,$idMes);

    $idPeriodo = $aPerTxt ? $aPerTxt : $idPeriodo; */


}

if (md5("ajax_edit") == $view) {
    $objFunc->SetSubTitle('Informe Mensual - Editar Registro');
    $row = $objInf->InformeMensualSeleccionar($idProy, $idAnio, $idMes, $idVs);
    $rowFinanciero = $objInf->InformeFinancieroSeleccionar($idProy, $idAnio, $idMes); // modificado 28/11/2011
    $idFecha = $row['t20_fch_pre'];
    $idEstado = $row['t20_estado'];
    $idPeriodo = $row['t20_periodo'];
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
    <script src="../../../jquery.ui-1.5.2/jquery.maskedinput.js" type="text/javascript"></script>
	<script src="../../../jquery.ui-1.5.2/ui/ui.datepicker.js" type="text/javascript"></script>
	<link href="../../../jquery.ui-1.5.2/themes/ui.datepicker.css" rel="stylesheet" type="text/css" />
</head>
<body>
	<form id="frmMain" name="frmMain" method="post" action="#">
  <?php
}
?>

		<div id="toolbar" style="height: 4px;" class="BackColor">
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td><button class="Button" onclick="Guardar_InformeCab(); return false;" id="btn_Guardar">Guardar</button></td>
					<td><button class="Button" onclick="btnCancelar_Clic(); return false;">Cerrar y Volver</button></td>
					<td><button class="Button" onclick="Enviar_Revision(); return false;" id="btn_ERevision" <?php if($rowFinanciero['inf_fi_ter']==0){ ?> disabled <?php } ?>>Enviar a Revisión</button></td>
					<td><button class="Button" onclick="Enviar_Correcion(); return false;" id="btn_ECorrecion">Enviar a Corrección</button></td>
					<td><button class="Button" onclick="Enviar_Aprobacion(); return false;" id="btn_AprobarInf">Dar V°B°</button></td>
					<td>
				        <input type="checkbox" id="vb_se" name="vb_se" <?php echo($row['vb_se']==1?'checked':'');?> <?php echo($IsMF?'disabled':'');?> <?php echo ($isSE || $IsMF)?'':'class="hide"'; ?>/>
				        <?php if($isSE || $IsMF){?>
				        V°B° SE
			            <?php }?>
			        </td>
					<td align="right"><?php echo($objFunc->SubTitle) ;?></td>
				</tr>
			</table>
		</div>
        <?php
        // Agregado el 28/11/2011
        $objInf = new BLInformes();
        $mostrar = false;
        $mostrar = $objInf->vInformeMensual($idProy, $idAnio, $idMes);
        ?>
        <div>
			<div id="divCabeceraInforme">
				<h1 style="font-weight: bold; font-size: 12px; color: #48628A;">La información ingresada tiene carácter de declaración jurada</h1>
				<table width="100%" border="0" cellspacing="0" cellpadding="0" class="TableEditReg">
					<tr>
						<td colspan="4"><strong>Carátula</strong></td>
					</tr>
					<tr>
						<td width="10%" height="25">Año</td>
						<td width="26%">
						    <select name="cboanio" id="cboanio" style="width: 100px;" class="InformeM">
                                <?php
                                $objProy = new BLProyecto();
                                $ver_proy = $objProy->MaxVersion($idProy);
                                $rs = $objProy->ListaAniosProyecto($idProy, $ver_proy);
                                $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $idAnio);
                                $objProy = NULL;
                                ?>
                            </select>
                            <input name="t20_anio" type="hidden" id="t20_anio" value="<?php echo($idAnio);?>" />
                            <input name="t20_ver_inf" type="hidden" id="t20_ver_inf" value="<?php echo($row['vsinf'])?>" />
                        </td>
						<td width="18%">Mes</td>
						<td width="46%">
						    <select name="cbomes" id="cbomes" style="width: 130px;" class="InformeM">
								<option value="" selected="selected"></option>
                                <?php
                                    $objTablas = new BLTablasAux();
                                    $rs = $objTablas->ListadoMeses();
                                    $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $idMes);
                                ?>
                            </select>
                            <input name="t20_mes" type="hidden" id="t20_mes" value="<?php echo($idMes);?>" />
                        </td>
					</tr>
					<tr>
						<td height="27" nowrap="nowrap">Periodo Ref.</td>
						<td nowrap="nowrap"><input name="t20_periodo" type="text" id="t20_periodo" size="20" value="<?php echo($idPeriodo)?>" class="InformeM" /></td>
						<td nowrap="nowrap">Fecha de Presentación</td>
						<td><input name="t20_fch_pre" type="text" id="t20_fch_pre" value="<?php echo($idFecha);?>" size="20" maxlength="12" class="InformeM" /></td>
					</tr>
					<tr>
						<td height="26">Estado</td>
						<td align="left" valign="middle">
						    <select name="t20_estado" id="t20_estado" style="width: 130px;" class="InformeM">
								<option value=""></option>
                                <?php
                                    $rs = $objTablas->EstadoInformes();

                                    $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $idEstado);
                                    $objTablas = NULL;
                                ?>
                            </select>
                            <?php
                                if (md5("ajax_edit") == $view) {
                                    //echo ("Revision: " . $idVs);
                                }
                            ?>
                        </td>
						<td valign="middle" align="right" colspan="2">
                		<?php if($ObjSession->PerfilID==$objHC->Ejec){?>
                		<?php if($rowFinanciero['inf_fi_ter']==1){ ?>
                			<span id="infTerminado" style="border: 1px solid #999999; padding: 5px; color: #999999; margin-right: 15px;">El Informe financiero esta terminado</span>

                		<?php } else if($mostrar){?>
                			<span id="infTerminado" style="border: 1px solid #FF0000; padding: 5px; color: #FF0000; margin-right: 15px;">Para enviar a revisión termine de elaborar el Informe Financiero</span>
                		<?php } ?>
                		<?php } ?>
		                </td>
					</tr>
					<tr>
						<td height="26" colspan="4">&nbsp;</td>
					</tr>
				</table>
			</div>
	        <?php
            if ($mostrar) {
            ?>
            <div id="ssTabInforme" class="TabbedPanels">
				<ul class="TabbedPanelsTabGroup">
					<li class="TabbedPanelsTab" tabindex="0">Metas Actividades</li>
					<li class="TabbedPanelsTab" tabindex="1" onclick="LoadProblemasSoluciones(false);">Problemas y Soluciones</li>
					<li class="TabbedPanelsTab" tabindex="2" onclick="LoadAnexosFotograficos(false);">Anexos</li>
				</ul>
				<div class="TabbedPanelsContentGroup">
					<div class="TabbedPanelsContent">
						<div id="">
							<table width="100%" border="0" cellspacing="0" cellpadding="0" class="TableEditReg">
								<tr>
									<td colspan='4'>&nbsp;</td>
								</tr>
								<tr>
									<td><b>Productos</b></td>
									<td width="60%">
									    <select name="cboactividad_sub" id="cboactividad_sub" style="width: 500px;" onchange="LoadSubActividades();">
											<option value=""></option>
    							            <?php
                                                $rs = $objInf->ListaActividades($idProy);
                                                $objFunc->llenarComboI($rs, 'codigo', 'actividad');
                                            ?>
						                </select>
					                </td>
									<td width="110px"><input type="button" value="Refrescar" style="width: 100%" title="Refrescar SubActividades" onclick="LoadSubActividades();" class="btn_save_custom" /></td>
									<td width="110px"><input type="button" value="Guardar Avance" style="width: 100%" title="Guardar Avance de Indicadores de Actividad" onclick="Guardar_AvanceSubAct();" class="btn_save_custom" /></td>
								</tr>
								<tr>
									<td colspan='4'>&nbsp;</td>
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
					<div class="TabbedPanelsContent">
						<div id="divProblemasSoluciones"></div>
					</div>
					<div class="TabbedPanelsContent">
						<div id="divAnexosFotograficos"></div>
					</div>
				</div>
			</div>
	        <?php } else{?>
			<br />
			<span style="font-weight: bold; background-color: #EEEEEE; margin-right: 15px; font-size: 12px; float: right; color: #999999; padding: 5px 10px; border: solid 1px #999999;">Guardar la Carátula para continuar con el llenado de datos</span>
	        <?php } ?>
            <p>&nbsp;</p>
		</div>

		<script language="javascript" type="text/javascript">
	    /*
		// -------------------------------------------------->
		// AQ 2.0 [20-12-2013 11:36]
		// Se retira de lista de estado a "Aprobado"
		// El informe sólo requiere V°B° del GP
		*/
		$("#t20_estado  option[value='257']").remove();
		// --------------------------------------------------<

    function Guardar_InformeCab()
	{
	<?php $ObjSession->AuthorizedPage(); ?>

	$('#btn_Guardar').attr('disabled', 'disabled');

	var anio = $('#cboanio').val();
	var mes = $('#cbomes').val();
	var per = $('#t20_periodo').val();
	var est = $('#t20_estado').val();


	if(anio=="" || anio==null){alert("Seleccione Año del Informe"); return false;}
	if(mes=="" || mes==null){alert("Seleccione Mes del Informe");  return false;}
	if(per=="" || per==null){alert("No se ha especificado el periodo del Informe"); return false;}
	if(est=="" || est==null){alert("Seleccione Estado del Informe"); return false;}

	var checkeado = $("#vb_se").attr("checked");
    var checkbox = 0;
	if(checkeado) {
	    checkbox=1;
	}

	var BodyForm="cboanio="+anio+"&t20_anio="+$("#t20_anio").val()+"&t20_ver_inf="+$("#t20_ver_inf").val()+"&cbomes="+mes+"&t20_mes="+$("#t20_mes").val()+"&t20_periodo="+per+"&t20_fch_pre="+$("#t20_fch_pre").val()+"&t20_estado="+est+"&t02_cod_proy="+$('#txtCodProy').val()+"&vb_se="+checkbox;
	var sURL = "inf_mes_process.php?action=<?php echo($view);?>";
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
		btnEditar_Clic($('#cboanio').val(), $('#cbomes').val(), vs);
	  }
	  else
	  {alert(respuesta);}
	  $('#btn_Guardar').removeAttr('disabled');

	}

	<?php if(md5("ajax_new")==$view || md5("ajax_edit")==$view) { ?>

		$("#cboanio option").attr('disabled','disabled');
		$("#cboanio option:selected").removeAttr('disabled');

		$("#t20_estado option").attr('disabled','disabled');
		$("#t20_estado option:selected").removeAttr('disabled');

		$("#cbomes option").attr('disabled','disabled');
		$("#cbomes option:selected").removeAttr('disabled');

		$("#t20_fch_pre").attr('disabled','disabled');
		$("#t20_periodo").attr('disabled','disabled');

	<?php
}
if (md5("ajax_edit") == $view) {
    ?>
		$("#btn_Guardar").removeAttr('disabled');
	<?php } ?>
		<?php if(md5("ajax_new")==$view) { ?>
		$("#btn_ERevision").attr('disabled','disabled');
	<?php } ?>
  	<?php if($accion==md5("ver") && $view == md5("ajax_edit")) { ?>
	  $('#btn_Guardar').hide();
	  $('#btn_ERevision').hide();
      $('#btn_ECorrecion').hide();
	  $('#btn_AprobarInf').hide();
	  $(".btn_InformeM").removeAttr('onclick');
	  $('.InformeM').attr("disabled","disabled");
	<?php } ?>
	<?php if($perfil==$objHC->Ejec) { ?>
	  	$("#btn_ECorrecion").hide();
	    $("#btn_AprobarInf").hide();
	<?php } ?>
	<?php if($perfil==$objHC->GP) { ?>
	  	$("#btn_ERevision").hide();
		$("#btn_Meta").removeAttr('onclick');
	<?php } ?>

    function LoadIndicadoresActividad()
	{
		var activ = $('#cboactividad_ind').val();
		var anio = $('#cboanio').val();
		var mes = $('#cbomes').val();
		if(anio=="" || anio==null){alert("Seleccione Año del Informe"); $('#cboactividad_ind').val(null); return false;}
		if(mes=="" || mes==null){alert("Seleccione Mes del Informe");  $('#cboactividad_ind').val(null); return false;}
		var BodyForm = "action=<?php echo(md5("lista_ind_act"));?>&idProy=<?php echo($idProy);?>&idActiv="+ activ + "&idAnio="+anio+"&idMes="+mes;
	 	var sURL = "inf_mes_ind_act.php";
		$('#divAvanceActividades').html("<p align='center'><img src='../../../img/indicator.gif' width='16' height='16' /><br>Cargando..<br></p>");
	 	var req = Spry.Utils.loadURL("POST", sURL, true, SuccessIndicadoresAct, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
	}
	function SuccessIndicadoresAct(req)
	{
	   var respuesta = req.xhRequest.responseText;
	   $("#divAvanceActividades").html(respuesta);
 	   return;
	}
	function onErrorLoad(req)
	{
		alert("Ocurrio un error al cargar los datos");
	}
	function Guardar_AvanceIndAct()
	{
	<?php $ObjSession->AuthorizedPage(); ?>
	var BodyForm=$("#FormData").serialize();
	if(BodyForm==""){alert("El Producto seleccionado, no Tiene indicadores !!!"); return;}

		if(confirm("Estas seguro de Guardar el avance de los Indicadores para el Informe ?"))
		{
			var activ = $('#cboactividad_ind').val();
			var anio = $('#cboanio').val();
			var mes = $('#cbomes').val();
			var sURL = "inf_mes_process.php?action=<?php echo(md5('ajax_indicadores_actividad'));?>";
			var req = Spry.Utils.loadURL("POST", sURL, true, indActSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
		}
	}
	function indActSuccessCallback	(req)
	{
	  var respuesta = req.xhRequest.responseText;
	  respuesta = respuesta.replace(/^\s*|\s*$/g,"");
	  var ret = respuesta.substring(0,5);
	  if(ret=="Exito")
	  {
		LoadIndicadoresActividad();
		alert(respuesta.replace(ret,""));
	  }
	  else
	  {alert(respuesta);}
	}

	function LoadSubActividades()
	{
		var activ = $('#cboactividad_sub').val();
		var anio = $('#cboanio').val();
		var mes = $('#cbomes').val();
		if(anio=="" || anio==null){alert("Seleccione Año del Informe"); $('#cboactividad_sub').val(null); return false;}
		if(mes=="" || mes==null){alert("Seleccione Mes del Informe");  $('#cboactividad_sub').val(null); return false;}
		var BodyForm = "action=<?php echo(md5("lista_sub_act"));?>&idProy=<?php echo($idProy);?>&idActiv="+ activ + "&idAnio="+anio+"&idMes="+mes;
	 	var sURL = "inf_mes_sub_act.php";
		$('#divAvanceSubActividades').html("<p align='center'><img src='../../../img/indicator.gif' width='16' height='16' /><br>Cargando..<br></p>");
	 	var req = Spry.Utils.loadURL("POST", sURL, true, SuccessSubAct, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
	}

	function SuccessSubAct(req)
	{
	   var respuesta = req.xhRequest.responseText;
	   $("#divAvanceSubActividades").html(respuesta);
 	   return;
	}

	function Guardar_AvanceSubAct()
	{
    	<?php $ObjSession->AuthorizedPage(); ?>
    	var BodyForm=$("#FormData").serialize();
    	if(BodyForm==""){alert("El Producto seleccionado, no Tiene Actividades !!!"); return;}

        if(confirm("Estas seguro de Guardar el avance de las Actividades para el Informe ?"))
		{
			var sURL = "inf_mes_process.php?action=<?php echo(md5('ajax_sub_actividad'));?>";
			var req = Spry.Utils.loadURL("POST", sURL, true, SubActSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
		}
	}

	function SubActSuccessCallback(req)
	{
	  var respuesta = req.xhRequest.responseText;
	  respuesta = respuesta.replace(/^\s*|\s*$/g,"");
	  var ret = respuesta.substring(0,5);
	  if(ret=="Exito")
	  {
		LoadSubActividades();
		alert(respuesta.replace(ret,""));
	  }
	  else
	  {alert(respuesta);}
	}

	function LoadProblemasSoluciones(recargar)
	{
		if($('#divProblemasSoluciones').html()!="")
		{
			if(!recargar){return false;}
		}

		var anio = $('#cboanio').val();
		var mes = $('#cbomes').val();
		if(anio=="" || anio==null){alert("Seleccione Año del Informe"); return false;}
		if(mes=="" || mes==null){alert("Seleccione Mes del Informe");  return false;}
		var BodyForm = "action=<?php echo(md5("lista_prob_solu"));?>&idProy=<?php echo($idProy);?>&idAnio="+anio+"&idMes="+mes+"&t20_ver_inf="+$('#t20_ver_inf').val()+"&view=<?php echo($accion);?>";
	 	var sURL = "inf_mes_prob_sol.php";
		$('#divProblemasSoluciones').html("<p align='center'><img src='../../../img/indicator.gif' width='16' height='16' /><br>Cargando..<br></p>");
	 	var req = Spry.Utils.loadURL("POST", sURL, true, SuccessProbSoluc, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
	}

	function SuccessProbSoluc(req)
	{
	   var respuesta = req.xhRequest.responseText;
	   $("#divProblemasSoluciones").html(respuesta);
 	   return;
	}

	function Guardar_ProblemasSoluciones()
	{
    	 <?php $ObjSession->AuthorizedPage(); ?>
    	 var BodyForm=$("#FormData").serialize();
		if(confirm("Estas seguro de Guardar los Problemas y Soluciones, para el Informe ?"))
		{
			var sURL = "inf_mes_process.php?action=<?php echo(md5('ajax_problemas_soluc'));?>";
			var req = Spry.Utils.loadURL("POST", sURL, true, ProbSolucSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
		}
	}

	function ProbSolucSuccessCallback	(req)
	{
	  var respuesta = req.xhRequest.responseText;
	  respuesta = respuesta.replace(/^\s*|\s*$/g,"");
	  var ret = respuesta.substring(0,5);
	  if(ret=="Exito")
	  {
		LoadProblemasSoluciones(true);
		alert(respuesta.replace(ret,""));
	  }
	  else
	  {alert(respuesta);}
	}

	function LoadAnexosFotograficos(recargar)
	{
		if($('#divAnexosFotograficos').html()!="")
		{
			if(!recargar){return false;}
		}
		var anio = $('#cboanio').val();
		var mes = $('#cbomes').val();
		if(anio=="" || anio==null){alert("Seleccione Año del Informe"); return false;}
		if(mes=="" || mes==null){alert("Seleccione Mes del Informe");  return false;}
		var BodyForm = "action=<?php echo(md5("lista_anx_foto"));?>&idProy=<?php echo($idProy);?>&idAnio="+anio+"&idMes="+mes+"&t20_ver_inf="+$('#t20_ver_inf').val()+"&view=<?php echo($accion);?>";

		var sURL = "inf_mes_anx_foto.php";
		$('#divAnexosFotograficos').html("<p align='center'><img src='../../../img/indicator.gif' width='16' height='16' /><br>Cargando..<br></p>");
	 	var req = Spry.Utils.loadURL("POST", sURL, true, SuccessAnxFotos, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
	}

	function SuccessAnxFotos(req)
	{
	   var respuesta = req.xhRequest.responseText;
	   $("#divAnexosFotograficos").html(respuesta);
 	   return;
	}

	/*var $EstInf_Elab = 45 ;
	var $EstInf_Rev = 46 ;
	var $EstInf_Aprob = 47 ;
	var $EstInf_Corr = 135 ;*/

	function Enviar_Revision()
	{
	<?php $ObjSession->AuthorizedPage(); ?>

		var anio = $('#cboanio').val();
		var mes = $('#cbomes').val();
		var ver = $('#t20_ver_inf').val();

		var estado = "<?php echo($objHC->EstInf_Rev);?>";

		if(anio=="" || anio==null){alert("Seleccione Año del Informe"); return false;}
		if(mes=="" || mes==null){alert("Seleccione Mes del Informe");  return false;}
		var BodyForm = "action=<?php echo(md5("ajax_envio_rev"));?>&idProy=<?php echo($idProy);?>&idAnio="+anio+"&idMes="+mes+"&t20_ver_inf="+ver+"&estado="+estado;

		if(confirm("Esta seguro de que desea enviar el informe a estado en revisi\u00f3n?"))
		{
			$('.wrapper_wait').show();
			var sURL = "inf_mes_process.php?action=<?php echo(md5('ajax_envio_rev'));?>";
			var req = Spry.Utils.loadURL("POST", sURL, true, Enviar_RevisionCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
		}
	}

	function Enviar_RevisionCallback(req)
	{
	  var respuesta = req.xhRequest.responseText;
	  respuesta = respuesta.replace(/^\s*|\s*$/g,"");
	  var ret = respuesta.substring(0,5);
	  $('.wrapper_wait').hide();

	  if(ret=="Éxito")
	  {
		//btnCancelar_Clic();
		var anio = $('#cboanio').val();
		var mes = $('#cbomes').val();
		var ver = $('#t20_ver_inf').val();
		btnEditar_Clic(anio, mes, ver, '<?php echo(md5("ver"));?>');

		dsLista.loadData();
		alert(respuesta.replace(ret,""));




	  }
	  else
	  {
		  alert(respuesta);
	  }



	}

	function Enviar_Correcion()
	{
	<?php $ObjSession->AuthorizedPage(); ?>

		var anio = $('#cboanio').val();
		var mes = $('#cbomes').val();
		var ver = $('#t20_ver_inf').val();

		var estado = "<?php echo($objHC->EstInf_Corr);?>";


		if(anio=="" || anio==null){alert("Seleccione Año del Informe"); return false;}
		if(mes=="" || mes==null){alert("Seleccione Mes del Informe");  return false;}
		var BodyForm = "action=<?php echo(md5("ajax_envio_corr"));?>&idProy=<?php echo($idProy);?>&idAnio="+anio+"&idMes="+mes+"&t20_ver_inf="+ver+"&estado="+estado;

		if(confirm("Esta seguro de que desea enviar el informe a estado en Correci\u00f3n?"))
		{
			$('.wrapper_wait').show();
			var sURL = "inf_mes_process.php?action=<?php echo(md5('ajax_envio_corr'));?>";
			var req = Spry.Utils.loadURL("POST", sURL, true, Enviar_CorrecionCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
		}
	}

	function Enviar_CorrecionCallback(req)
	{
	  var respuesta = req.xhRequest.responseText;
	  respuesta = respuesta.replace(/^\s*|\s*$/g,"");
	  var ret = respuesta.substring(0,5);
	  $('.wrapper_wait').hide();

	  if(ret=="Éxito")
	  {
		//btnCancelar_Clic();
		var anio = $('#cboanio').val();
		var mes = $('#cbomes').val();
		var ver = $('#t20_ver_inf').val();
		btnEditar_Clic(anio, mes, ver, '<?php echo(md5("ver"));?>');

		dsLista.loadData();
		alert(respuesta.replace(ret,""));


	  }
	  else
	  {
		  alert(respuesta);
	  }


	}

	function Enviar_Aprobacion()
	{
	<?php $ObjSession->AuthorizedPage(); ?>

		var anio = $('#cboanio').val();
		var mes = $('#cbomes').val();
		var ver = $('#t20_ver_inf').val();

		var estado = "<?php echo($objHC->EstInf_Aprob);?>";


		if(anio=="" || anio==null){alert("Seleccione Año del Informe"); return false;}
		if(mes=="" || mes==null){alert("Seleccione Mes del Informe");  return false;}
		var BodyForm = "action=<?php echo(md5("ajax_envio_aprob"));?>&idProy=<?php echo($idProy);?>&idAnio="+anio+"&idMes="+mes+"&t20_ver_inf="+ver+"&estado="+estado;

		if(confirm("Esta seguro de que desea dar V°B° al informe?"))
		{
			$('.wrapper_wait').show();
			var sURL = "inf_mes_process.php?action=<?php echo(md5('ajax_envio_aprob'));?>";
			var req = Spry.Utils.loadURL("POST", sURL, true, Enviar_AprobacionCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
		}
	}

	function Enviar_AprobacionCallback	(req)
	{
	  var respuesta = req.xhRequest.responseText;
	  respuesta = respuesta.replace(/^\s*|\s*$/g,"");
	  var ret = respuesta.substring(0,5);
	  $('.wrapper_wait').hide();
	  if(ret=="Éxito")
	  {
		//btnCancelar_Clic();
		var anio = $('#cboanio').val();
		var mes = $('#cbomes').val();
		var ver = $('#t20_ver_inf').val();
		btnEditar_Clic(anio, mes, ver, '<?php echo(md5("ver"));?>');

		dsLista.loadData();
		alert(respuesta.replace(ret,""));
	  }
	  else
	  {alert(respuesta);}
	}

	//jQuery("#t20_fch_pre").datepicker();
	if ($.isFunction($.fn.mask)) {
		$("#t20_fch_pre").mask("99/99/9999");
	}

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