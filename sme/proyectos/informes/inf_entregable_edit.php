<?php
/**
 * CticServices
 *
 * Permite la edición del Informe de Entregable
 *
 * @package     sme/proyectos/informes
 * @author      AQ
 * @since       Version 2.0
 *
 */
include("../../../includes/constantes.inc.php");
include("../../../includes/validauser.inc.php");
require_once (constant("PATH_CLASS") . "BLInformes.class.php");
require_once (constant("PATH_CLASS") . "HardCode.class.php");
require_once (constant('PATH_CLASS') . "BLBene.class.php");

$objInf = new BLInformes();
$HC = new HardCode();

$idProy = $objFunc->__Request('idProy');
$anio = $objFunc->__Request('anio');
$idEntregable = $objFunc->__Request('idEntregable');
$idVersion = $objFunc->__Request('idVersion');

$action = $objFunc->__GET('mode');
$view = $objFunc->__GET('accion');
$objBene = new BLBene();

$rsss = $objBene->ListadoBeneficiarios($idProy);

$objProy = new BLProyecto();
//$idVersion = $objProy->MaxVersion($idProy);

if (md5("ajax_new") == $action) {
    $objFunc->SetSubTitle('Informe de Entregable - Nuevo Registro');
    $row = $objInf->getInformeEntregableUltimo($idProy, $idVersion);

    $anio = $row['anio'];
    $idEntregable = $row['entregable'];
    $periodo = $row['periodo'];
    $fchPresentacion = $row['fch_pre'];
    $estadoInf = $row['estado'];
}

if (md5("ajax_edit") == $action) {
    $objFunc->SetSubTitle('Informe de Entregable - Editar Registro');
    $row = $objInf->getInformeEntregable($idProy, $idVersion, $anio, $idEntregable);
    $periodo = $row['periodo'];
    $fchPresentacion = $row['fch_pre'];
    $estadoInf = $row['estado'];
    $resultado = $row['resultado'];
    $conclusiones = $row['conclusiones'];
}

$IsMF = false;
if ($ObjSession->PerfilID == $HC->GP || $ObjSession->PerfilID == $HC->RA) {
    $IsMF = true;
}

$isSE = false;
if ($ObjSession->PerfilID == $HC->SE) {
    $isSE = true;
}

if ($idProy == "") {
    ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=charset=utf-8" />
    <title>Detalle del Informe de Entregable</title>
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
        <script src="../../../jquery.ui-1.5.2/jquery.maskedinput.js" type="text/javascript"></script>
		<script src="../../../jquery.ui-1.5.2/ui/ui.datepicker.js" type="text/javascript"></script>
		<link href="../../../jquery.ui-1.5.2/themes/ui.datepicker.css" rel="stylesheet" type="text/css" />
		<div id="toolbar" style="height: 4px;" class="BackColor">
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td width="10%"><button id="btn_Guardar" class="Button btn_guardar" onclick="Guardar_InformeCab(); return false;">Guardar</button></td>
					<td width="15%"><button class="Button" onclick="btnCancelar_Clic(); return false;">Cerrar y Volver</button></td>
					<td width="12%"><button class="Button btn_guardar" onclick="Enviar_Revision(); return false;" id="btn_ERevision">Enviar a Revisión</button></td>
					<td width="12%"><button class="Button btn_guardar" onclick="Enviar_Correcion(); return false;" id="btn_ECorrecion">Enviar a Corrección</button></td>
					<td width="12%"><button class="Button btn_guardar" onclick="Enviar_Aprobacion(); return false;" id="btn_AprobarInf">Dar V°B°</button></td>
					<td>
				        <input type="checkbox" id="vb_se" name="vb_se" <?php echo($row['vb_se']==1?'checked':'');?> <?php echo($IsMF?'disabled':'');?> <?php echo ($isSE || $IsMF)?'':'class="hide"'; ?>/>
				        <?php if($isSE || $IsMF){?>
				        V°B° SE
			            <?php }?>
			        </td>
					<td width="25%" align="right"><?php echo($objFunc->SubTitle) ;?></td>
				</tr>
			</table>
		</div>
    	<?php
            $objInf = new BLInformes();
            $mostrar = false;
            $mostrar = $objInf->existeInformeEntregable($idProy, $idVersion, $anio, $idEntregable);
        ?>
        <div>
			<div id="divCabeceraInforme">
				<h4 style="color: #48628A;">La información ingresada tiene carácter de declaración jurada</h4>
				<table width="100%" border="0" cellspacing="0" cellpadding="0" class="TableEditReg">
					<tr>
						<td colspan="4"><b>1. Carátula</b></td>
					</tr>
					<tr>
						<td width="9%" height="25">Año</td>
						<td width="32%">
						    <select name="cboanio" id="cboanio" style="width: 100px;" class="InfTrim">
                                <?php
                                $rs = $objProy->ListaAniosProyecto($idProy, $idVersion);
                                $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $anio);
                                $objProy = NULL;
                                ?>
                            </select>
                            <input name="anio" type="hidden" id="anio" value="<?php echo($anio);?>" />
                        </td>
						<td width="14%">Entregable</td>
						<td width="45%">
						    <select name="cboentregable" id="cboentregable" style="width: 130px;" onchange="LoadIndicadoresProposito(true);" class="InfTrim">
								<option value="" selected="selected"></option>
                                <?php
                                require (constant("PATH_CLASS") . "BLTablasAux.class.php");
                                $objTablas = new BLTablasAux();
                                $rs = $objInf->listarEntregables($idProy, $idVersion, $anio);
                                $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $anio ."-". $idEntregable);
                                ?>
                            </select>
                            <input name="idEntregable" type="hidden" id="idEntregable" value="<?php echo($idEntregable);?>" />
                            <input name="idVersion" type="hidden" id="idVersion" value="<?php echo($idVersion);?>" />
                        </td>
					</tr>
					<tr>
						<td height="27" nowrap="nowrap">Periodo Ref.</td>
						<td nowrap="nowrap">
						    <input name="periodo" type="text" class="InfTrim" id="periodo" value="<?php echo($periodo)?>" size="40" maxlength="50" />
					    </td>
						<td nowrap="nowrap">Fecha de Presentación</td>
						<td>
						    <input name="fchPresentacion" type="text" class="InfTrim" id="fchPresentacion" value="<?php echo($fchPresentacion)?>" size="20" maxlength="12" />
					    </td>
					</tr>
					<tr>
						<td height="26">Estado</td>
						<td>
						    <select name="estadoInf" class="InfTrim" id="estadoInf" style="width: 130px;" disabled>
								<option value=""></option>
                                <?php
                                $rs = $objTablas->EstadoInformes();
                                $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $estadoInf);
                                $objTablas = NULL;
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                    	<td colspan="2">
                    		<font style="font-weight: bold; color: #48628A;">Introducción </font> 
							<br /> 							
							<textarea name="txtIntro" rows="2" id="txtIntro" style="padding: 0px; width: 90%;"><?php echo($row['intro_gp']);?></textarea>
						</td>                    	
						<td colspan="2">
							<font style="font-weight: bold; color: #48628A;">Observaciones del Gestor de Proyectos </font> 
							<br /> 
							<!--textarea name="txtIndPropdif[]" cols="2500" rows="2" id="txtIndPropdif[]" style="padding:0px; width:100%;"><?php echo($row['dificultades']);?></textarea-->
							<textarea name="txtObs" rows="2" id="txtObs" style="padding: 0px; width: 90%;"><?php echo($row['obs_gp']);?></textarea>
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
					<li class="TabbedPanelsTab" tabindex="0">2. Propósito</li>
					<li class="TabbedPanelsTab" tabindex="1">3. Componentes</li>
					<li class="TabbedPanelsTab" tabindex="2">4. Productos</li>
					<li class="TabbedPanelsTab" tabindex="3" onclick="LoadPlanesEspecificos(false);">5. Planes Específicos</li>
					<li class="TabbedPanelsTab" tabindex="4" onclick="LoadAnalisis(false);">6. Análisis</li>
					<li class="TabbedPanelsTab" tabindex="5" onclick="LoadAnexos(false);">7. Anexos</li>
				</ul>

				<div class="TabbedPanelsContentGroup">
				    <!-- Tab Proposito -->
					<div class="TabbedPanelsContent">
						<div id="divIndicadoresProposito" class="TableGrid"></div>
					</div>

					<!-- Tab Componentes -->
					<div class="TabbedPanelsContent">
						<table width="100%" border="0" cellspacing="0" cellpadding="0" class="TableEditReg">
							<tr>
								<td>
								    <b>Componente</b>
								    <select name="cbocomponente_ind" id="cbocomponente_ind" style="width: 500px;" onchange="LoadIndicadoresComponente();">
										<option value="" selected="selected"></option>
    						            <?php
                                            $rs = $objInf->ListaComponentes($idProy);
                                            $objFunc->llenarComboI($rs, 't08_cod_comp', 'componente', '');
                                        ?>
					                </select>
					            </td>
								<td>
								    <button class="boton btn_guardar" onclick="Guardar_AvanceIndComp(); return false;">Guardar</button>
								    <button class="boton" onclick="LoadIndicadoresComponente(); return false;">Refrescar</button>
							    </td>
							</tr>
							<tr>
								<td colspan="2">&nbsp;</td>
							</tr>
						</table>
						<div id="divAvanceIndicadoresComponentes" class="TableGrid"></div>
					</div>

					<!-- Tab Productos -->
					<div class="TabbedPanelsContent">
						<table width="100%" border="0" cellspacing="0" cellpadding="0" class="TableEditReg">
							<tr>
								<td>
								    <b>Producto</b>
								    <select name="cboprod_ind" id="cboprod_ind" style="width: 500px;" onchange="LoadIndicadoresProd();">
										<option value=""></option>
						                <?php
                                        $rs = $objInf->listarProductosEnEntregable($idProy, $idVersion, $anio, $idEntregable);
                                        $objFunc->llenarComboI($rs, 'codigo', 'producto');
                                        ?>
                                    </select>
                                </td>
								<td>
								    <button class="boton btn_guardar" onclick="Guardar_AvanceIndProd(); return false;">Guardar</button>
								    <button class="boton" onclick="LoadIndicadoresProd(); return false;">Refrescar</button>
							    </td>
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

					<!-- Tab Planes Especificos -->
					<div class="TabbedPanelsContent">
            		<?php if($rsss->num_rows <= 0){	?>
            			<div style="color: red;">Aún no se han reportados los beneficiarios para enviar a revisión</div>
            		<?php } ?>
		                <div id="divPlanesEspecificos" style="min-height: 200px;"></div>
					</div>

					<!-- Tab Analisis -->
					<div class="TabbedPanelsContent">
						<div id="divAnalisis"></div>
					</div>

					<!-- Tab Anexos -->
					<div class="TabbedPanelsContent">
						<div id="divAnexos"></div>
					</div>
				</div>
			</div>
	<?php } else{?>
			<br />
			<span style="font-weight: bold; background-color: #EEEEEE; margin-right: 15px; font-size: 12px; float: right; color: #999999; padding: 5px 10px; border: solid 1px #999999;">Guardar
				la Carátula para continuar con el llenado de datos</span>
	<?php } ?>
    <p>&nbsp;</p>
	</div>
	<script language="javascript" type="text/javascript">
    ConfigInfTrim();
	function ConfigInfTrim()
	{
		$(".InfTrim:text").attr('readonly', 'readonly');
		$("select.InfTrim>option:not(:selected)").attr('disabled', 'disabled');

		<?php if($ObjSession->PerfilID==$HC->Ejec) { ?>
		$('#btn_AprobarInf, #btn_ECorrecion').attr("disabled", "disabled");
		$('#txtObs').attr('disabled', 'disabled');
		<?php } ?>

		<?php if($ObjSession->PerfilID==$HC->GP) { ?>
		$('#btn_ERevision').attr("disabled", "disabled");
		<?php } ?>

		<?php if($action == md5("ajax_new")) { ?>
	    $('#btn_ERevision').hide();
    	<?php } ?>

		<?php if($action == md5("ajax_edit")) { ?>
			//$('#btn_Guardar').attr("disabled","disabled");
		<?php } ?>

		<?php if($view==md5("ver")) { ?>
		$('.InfTrim').attr("disabled","disabled");
		$(".btn_guardar").attr("disabled", "disabled");
		$('#txtObs').attr('disabled', 'disabled');
		$('#txtIntro').attr('disabled', 'disabled');
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

		var anio = "<?php echo($anio);?>";
		var entregable = "<?php echo($idEntregable);?>";

		if(anio=="" || anio==null){alert("Seleccione Año del Informe"); return false;}
		if(entregable=="" || entregable==null){alert("Seleccione Entregable del Informe");  return false;}

		var BodyForm = "idProy=<?php echo($idProy);?>&anio="+anio+"&idEntregable="+entregable+"&estado="+estado+"&nomestado="+nomestado;

		if(confirm("Esta seguro de que desea enviar el informe a estado \""+nomestado +"\""))
		{
			var sURL = "inf_entregable_process.php?action=<?php echo(md5('ajax_envio_inf_entregable'));?>";
			var req = Spry.Utils.loadURL("POST", sURL, true, EnviarInformeA_Callback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
		}
	}

	function EnviarInformeA_Callback(req)
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

	function Enviar_Revision()
	{
	    <?php $ObjSession->AuthorizedPage(); ?>

		var anio = "<?php echo($anio);?>";
		var entregable = "<?php echo($idEntregable);?>";
		var nomestado = "Revisión";
		var estado = "<?php echo($HC->EstInf_Rev);?>";
		var obs=$('#txtObs').val();
		var intro=$('#txtIntro').val();
		if(anio=="" || anio==null){alert("Seleccione Año del Informe"); return false;}
		if(entregable=="" || entregable==null){alert("Seleccione Entregable del Informe");  return false;}
		var aConfMsg = "Aún no se han reportado los beneficiarios para enviar a revisión.\nDesea enviar de todas maneras a Revisión?";

		$.getJSON("inf_entregable_process.php",
		{'action': '<?php echo md5('ajax_count_benef');?>', 'idProy': '<?php echo($idProy);?>', 'idVersion': '<?php echo($idVersion);?>', 'anio': anio, 'idEntregable': entregable},
		function(pData) {
			var goAhead = true;
			if (pData.total_benef == 0) {
				if (!confirm($('<div/>').html(aConfMsg).text()))
					goAhead = false;
			}

			if (goAhead) {
				var BodyForm = "idProy=<?php echo($idProy);?>&idVersion="+<?php echo($idVersion);?>+"&anio="+anio+"&idEntregable="+entregable+"&estado="+estado+"&nomestado="+nomestado+"&obs="+obs+'&intro='+intro;

				if(confirm("Esta seguro de que desea enviar el informe a estado \""+nomestado +"\""))
				{
					var sURL = "inf_entregable_process.php?action=<?php echo(md5('ajax_envio_inf_entregable'));?>";
					var req = Spry.Utils.loadURL("POST", sURL, true, Enviar_RevisionCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
				}
			}
		});
	}

	function Enviar_RevisionCallback(req)
	{
        var respuesta = req.xhRequest.responseText;
        respuesta = respuesta.replace(/^\s*|\s*$/g,"");
        var ret = respuesta.substring(0,5);
        if(ret=="Exito")
        {
        	Editar_Clic_view($('#anio').val(), $('#idEntregable').val(), '<?php echo(md5("ver"));?>');
            dsLista.loadData();
            alert(respuesta.replace(ret,""));
        }
        else
        {alert(respuesta);}
	}

	function Enviar_Correcion()
	{
	    <?php $ObjSession->AuthorizedPage(); ?>

		var anio = "<?php echo($anio);?>";
		var entregable = "<?php echo($idEntregable);?>";
		var nomestado = "Corrección";
		var obs=$('#txtObs').val();
		var intro=$('#txtIntro').val();
		var estado = "<?php echo($HC->EstInf_Corr);?>";

		if(anio=="" || anio==null){alert("Seleccione Año del Informe"); return false;}
		if(entregable=="" || entregable==null){alert("Seleccione Entregable del Informe");  return false;}

		var BodyForm = "idProy=<?php echo($idProy);?>&idVersion="+<?php echo($idVersion);?>+"&anio="+anio+"&idEntregable="+entregable+"&estado="+estado+"&nomestado="+nomestado+"&obs="+obs+"&intro="+intro;

		if(confirm("Esta seguro de que desea enviar el informe a estado \""+nomestado +"\""))
		{
			var sURL = "inf_entregable_process.php?action=<?php echo(md5('ajax_envio_inf_entregable'));?>";
			var req = Spry.Utils.loadURL("POST", sURL, true, Enviar_CorrecionCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
		}
	}

	function Enviar_CorrecionCallback(req)
	{
	  var respuesta = req.xhRequest.responseText;
	  respuesta = respuesta.replace(/^\s*|\s*$/g,"");
	  var ret = respuesta.substring(0,5);
	  if(ret=="Exito")
	  {
		    Editar_Clic_view($('#anio').val(), $('#idEntregable').val(), '<?php echo(md5("ver"));?>');
		    dsLista.loadData();
		    alert(respuesta.replace(ret,""));
	  }
	  else
	  {alert(respuesta);}
	}

	function Enviar_Aprobacion()
	{
	    <?php $ObjSession->AuthorizedPage(); ?>

		var anio = "<?php echo($anio);?>";
		var entregable = "<?php echo($idEntregable);?>";
		var nomestado = "VB";
		var estado = "<?php echo($HC->EstInf_Aprob);?>";
		var obs=$('#txtObs').val();
		var intro=$('#txtIntro').val();
		if(anio=="" || anio==null){alert("Seleccione Año del Informe"); return false;}
		if(entregable=="" || entregable==null){alert("Seleccione Entregable del Informe");  return false;}

		var BodyForm = "idProy=<?php echo($idProy);?>&idVersion="+<?php echo($idVersion);?>+"&anio="+anio+"&idEntregable="+entregable+"&estado="+estado+"&nomestado="+nomestado+"&obs="+obs+"&intro="+intro;

		if(confirm("Esta seguro de que desea enviar el informe a estado \""+nomestado +"\""))
		{
			var sURL = "inf_entregable_process.php?action=<?php echo(md5('ajax_envio_inf_entregable'));?>";
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
            Editar_Clic_view($('#anio').val(), $('#idEntregable').val(), '<?php echo(md5("ver"));?>');
            dsLista.loadData();
            alert(respuesta.replace(ret,""));
        }
        else
        {alert(respuesta);}
	}

    function Guardar_InformeCab()
	{
    	<?php $ObjSession->AuthorizedPage('EDITAR'); ?>

    	var anio = $('#cboanio').val();
    	var entregable = $('#cboentregable').val();
    	var per = $('#periodo').val();
    	var est = $('#estadoInf').val();

    	if(anio=="" || anio==null){alert("Seleccione Año del Informe"); return false;}
    	if(entregable=="" || entregable==null){alert("Seleccione Entregable del Informe");  return false;}
    	if(per=="" || per==null){alert("No se ha especificado el periodo del Informe"); return false;}
    	if(est=="" || est==null){alert("Seleccione Estado del Informe"); return false;}

    	var checkeado = $("#vb_se").attr("checked");
        var checkbox = 0;
    	if(checkeado) {
    	    checkbox=1;
    	}

    	var BodyForm="cboanio="+anio+"&anio="+$("#anio").val()+"&cboentregable="+entregable+"&idEntregable="+$("#idEntregable").val()+"&periodo="+per+"&fchPresentacion="+$("#fchPresentacion").val()+"&estadoInf="+est+"&idProy="+$('#txtCodProy').val()+"&obs_gp="+$('#txtObs').val()+"&idVersion="+$("#idVersion").val()+"&vb_se="+checkbox+"&intro_gp="+$('#txtIntro').val();
    	var sURL = "inf_entregable_process.php?action=<?php echo($action);?>";
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
            btnEditar_Clic($('#anio').val(), $('#idEntregable').val());
        }
        else
        {
            alert(respuesta);
        }
	}

    function LoadIndicadoresProposito(recargar)
	{
		if($('#divIndicadoresProposito').html()!="")
		{ if(!recargar){return false;}	}
		var anio = $('#cboanio').val();
		var entregable = $('#cboentregable').val();
		if(anio=="" || anio==null){alert("Seleccione Año del Informe"); return false;}
		var BodyForm = "idProy=<?php echo($idProy);?>&anio="+anio+"&idEntregable="+$('#idEntregable').val()+"&idVersion="+$('#idVersion').val();
	 	var sURL = "inf_entregable_ind_prop.php?view=<?php echo($view)?>";
		$('#divIndicadoresProposito').html("<p align='center'><img src='../../../img/indicator.gif' width='16' height='16' /><br>Cargando..<br></p>");
	 	var req = Spry.Utils.loadURL("POST", sURL, true, SuccessIndicProposito, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
	}

	function SuccessIndicProposito(req)
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
		var entregable = $('#cboentregable').val();
		if(anio=="" || anio==null){alert("Seleccione Año del Informe"); $('#cbocomponente_ind').val(null); return false;}
		var BodyForm = "idProy=<?php echo($idProy);?>&idComp="+comp+"&anio="+anio+"&idEntregable="+$('#idEntregable').val()+"&idVersion="+$('#idVersion').val();
	 	var sURL = "inf_entregable_ind_comp.php?view=<?php echo($view)?>";
		$('#divAvanceIndicadoresComponentes').html("<p align='center'><img src='../../../img/indicator.gif' width='16' height='16' /><br>Cargando..<br></p>");
	 	var req = Spry.Utils.loadURL("POST", sURL, true, SuccessIndicadoresComp, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
	}

	function SuccessIndicadoresComp	 (req)
	{
	   var respuesta = req.xhRequest.responseText;
	   $("#divAvanceIndicadoresComponentes").html(respuesta);
 	   return;
	}

    function LoadIndicadoresProd()
	{
		var prod = $('#cboprod_ind').val();
		var anio = $('#cboanio').val();
		var entregable = $('#cboentregable').val();
		if(anio=="" || anio==null){alert("Seleccione Año del Informe"); $('#cboprod_ind').val(null); return false;}
		//if(entregable=="" || entregable==null){alert("Seleccione Entregable del Informe");  $('#cboprod_ind').val(null); return false;}
		var BodyForm = "idProy=<?php echo($idProy);?>&idProd="+prod+ "&anio="+anio+"&idEntregable="+$('#idEntregable').val()+"&idVersion="+$('#idVersion').val();
	 	var sURL = "inf_entregable_ind_prod.php?view=<?php echo($view)?>";

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

	function LoadPlanesEspecificos(recargar)
	{
		if($('#divPlanesEspecificos').html()!="")
		{ if(!recargar){return false;}	}
		var anio = $('#cboanio').val();
		var entregable = $('#cboentregable').val();
		if(anio=="" || anio==null){alert("Seleccione Año del Informe"); return false;}
		//if(entregable=="" || entregable==null){alert("Seleccione Entregable");  return false;}
		var BodyForm = "idProy=<?php echo($idProy);?>&anio="+anio+"&idEntregable="+$('#idEntregable').val()+"&idVersion="+$('#idVersion').val();
	 	var sURL = "inf_entregable_planes.php?view=<?php echo($view)?>";

	 	console.log($('#idEntregable').val());

		$('#divPlanesEspecificos').html("<p align='center'><img src='../../../img/indicator.gif' width='16' height='16' /><br>Cargando..<br></p>");
	 	var req = Spry.Utils.loadURL("POST", sURL, true, SuccessPlanesEspecificos, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
	}

	function SuccessPlanesEspecificos(req)
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
		var entregable = $('#cboentregable').val();
		if(anio=="" || anio==null){alert("Seleccione Año del Informe"); return false;}
		//if(entregable=="" || entregable==null){alert("Seleccione Entregable");  return false;}
		var BodyForm = "action=<?php echo(md5("lista_prob_solu"));?>&idProy=<?php echo($idProy);?>&anio="+anio+"&idEntregable="+$('#idEntregable').val()+"&idVersion="+$('#idVersion').val();
	 	var sURL = "inf_entregable_analisis.php?view=<?php echo($view)?>";
		$('#divAnalisis').html("<p align='center'><img src='../../../img/indicator.gif' width='16' height='16' /><br>Cargando..<br></p>");
	 	var req = Spry.Utils.loadURL("POST", sURL, true, SuccessProbSoluc, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
	}

	function SuccessProbSoluc(req)
	{
	   var respuesta = req.xhRequest.responseText;
	   $("#divAnalisis").html(respuesta);
	   configSubPages();
 	   return;
	}

	function LoadAnexos(recargar)
	{
		if($('#divAnexos').html()!="")
		{
			if(!recargar){return false;}
		}
		var anio = $('#cboanio').val();
		var entregable = $('#cboentregable').val();
		if(anio=="" || anio==null){alert("Seleccione Año del Informe"); return false;}
		//if(entregable=="" || entregable==null){alert("Seleccione Entregable del Informe");  return false;}
		var BodyForm = "idProy=<?php echo($idProy);?>&anio="+anio+"&idEntregable="+$('#idEntregable').val()+"&idVersion="+$('#idVersion').val();
	 	var sURL = "inf_entregable_anx.php?view=<?php echo($view)?>";
		$('#divAnexos').html("<p align='center'><img src='../../../img/indicator.gif' width='16' height='16' /><br>Cargando..<br></p>");
	 	var req = Spry.Utils.loadURL("POST", sURL, true, SuccessAnx, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
	}

	function SuccessAnx(req)
	{
	   var respuesta = req.xhRequest.responseText;
	   $("#divAnexos").html(respuesta);
 	   return;
	}

	function configSubPages()
	{
		<?php
			$aStatus =  $estadoInf;
			$aProfile = $ObjSession->PerfilID;

			if (($view == md5('ver')) || (($aProfile == $HC->Ejec || $aProfile == $HC->GP) && $aStatus == $HC->EstInf_Aprob)) { ?>
		        $(".btn_guardar").attr('disabled', 'disabled');
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
</body>
</html>
<?php } ?>