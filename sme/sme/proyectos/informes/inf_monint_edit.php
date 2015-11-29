<?php
include("../../../includes/constantes.inc.php");
include("../../../includes/validauser.inc.php");
require_once (constant("PATH_CLASS") . "BLInformes.class.php");
require_once (constant("PATH_CLASS") . "BLTablasAux.class.php");
require_once (constant("PATH_CLASS") . "BLMonitoreoFinanciero.class.php");
require_once (constant("PATH_CLASS") . "HardCode.class.php");

$objInf = new BLInformes();
$HardCode = new HardCode();
$objProy = new BLProyecto();

$idProy = $objFunc->__Request('idProy');
$anio = $objFunc->__Request('anio');
$idEntregable = $objFunc->__Request('idEntregable');
$idVersion = $objFunc->__Request('idVersion');

$action = $objFunc->__Request('mode');
$pageMode = '';

if (md5("ajax_new") == $action) {
    $objFunc->SetSubTitle('Informe de Supervisión - Nuevo Registro');
    $pageMode = 'new';
    $row = $objInf->getSiguienteInfSI($idProy, $idVersion);
    $anio = $row['anio'];
    $idEntregable = $row['entregable'];
    $periodo = $row['periodo'];
    $fchPresentacion = $row['fch_pre'];
    $estadoInf = $row['estado'];
}

if (md5("ajax_edit") == $action || md5("ajax_view") == $action) {
    if (md5("ajax_edit") == $action)
        $objFunc->SetSubTitle('Informe de Supervisión Interna - Editar Registro');
    else
        $objFunc->SetSubTitle('Informe de Supervisión Interna - Ver Registro');

    $pageMode = md5("ajax_edit") == $action ? 'edit' : 'view';
    $row = $objInf->getInfSI($idProy, $anio, $idEntregable);

    $periodo = $row['periodo'];
    $fchPresentacion = $row['fch_pre'];
    $estadoInf = $row['estado'];
}

if ($objFunc->__QueryString() == "") {
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=charset=utf-8" />
    <title>Detalle del Informe de Supervisión Interna</title>
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
                    <?php if ($pageMode != 'view') {?>
                    <td width="7%">
						<button class="Button" onclick="Guardar_InformeCab(); return false;">Guardar</button>
					</td>
                    <?php } ?>
                    <td width="27%">
                        <button class="Button" onclick="btnCancelar_Clic(); return false;" style="white-space: nowrap;">Cerrar y Volver</button>
                    </td>
					<td width="10%">&nbsp;</td>
					<td width="3%">&nbsp;</td>
					<td width="3%">&nbsp;</td>
					<td width="3%">&nbsp;</td>
					<td width="47%" align="right"><?php echo($objFunc->SubTitle);?></td>
				</tr>
			</table>
		</div>

		<div>
			<div id="divCabeceraInforme">
				<table width="100%" border="0" cellspacing="0" cellpadding="0" class="TableEditReg">
					<tr>
						<td colspan="4"><b>1. Caratula</b></td>
					</tr>
					<tr>
						<td width="9%" height="25">Año</td>
						<td width="32%">
						    <select name="cboanio" id="cboanio" style="width: 100px;" class="Cabecera">
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
						    <select name="cboentregable" id="cboentregable" style="width: 130px;" class="Cabecera">
								<option value="" selected="selected"></option>
                                <?php
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
						    <input name="periodo" type="text" class="Cabecera center" id="periodo" value="<?php echo($periodo)?>" size="30" maxlength="50" />
					    </td>
						<td nowrap="nowrap">Fecha de Presentación</td>
						<td>
						    <input name="fchPresentacion" type="text" class="Cabecera center" id="fchPresentacion" value="<?php echo($fchPresentacion)?>" size="20" maxlength="12" />
					    </td>
					</tr>
					<tr>
						<td height="26">Estado</td>
						<td colspan="3">
						    <select name="estadoInf" class="Cabecera" id="estadoInf" style="width: 130px;">
								<option value=""></option>
                                <?php
                                $rs = $objTablas->EstadoInformesMonExt();
                                $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $estadoInf);
                                $objTablas = NULL;
                                ?>
                            </select>
                        </td>
                    </tr>
					<tr>
						<td height="36" colspan="2">
							Inicio de Visita
							<input name="iniVisita" type="text" id="iniVisita" value="<?php echo($row['iniVisita'])?>" size="15"
								maxlength="12" style="text-align: center;" />
						</td>
						<td height="36" colspan="2">
							Término de Visita
							<input name="terVisita" type="text" id="terVisita" value="<?php echo($row['terVisita'])?>" size="15"
								maxlength="12" style="text-align: center;" />
						</td>
					</tr>
					<tr>
						<td colspan="4"><span id="mensaje" class="nota"></span></td>
					</tr>
      <?php
    if ($estadoInf != $HardCode->EstInf_ElaME) {
        $aObsDisFlg = ($ObjSession->PerfilID != $HardCode->GP && $ObjSession->PerfilID != $HardCode->RA && $ObjSession->PerfilID != $HardCode->GP) ? "disabled" : "";
        ?>
                    <tr>
						<td height="27" nowrap="nowrap" colspan='2'>Observaciones:</td>
						<td colspan="5">&nbsp;</td>
					</tr>
					<tr>
						<td colspan='7'>
						<textarea rows="3" cols="128" name='obsGP' id='obsGP' <?php echo $aObsDisFlg; ?>><?php echo $row['obsGP']; ?></textarea>
						</td>
					</tr>
      <?php
    }
    ?>
    	            <tr>
						<td colspan='7'>&nbsp;</td>
					</tr>
				</table>
			</div>
    <?php
    $objInf = new BLInformes();
    $mostrar = false;
    $mostrar = $objInf->existeInfSI($idProy, $anio, $idEntregable);
    if ($mostrar) {
        ?>
	        <input type='hidden' id='pageMode' value='<?php echo $pageMode; ?>' />
			<div id="ssTabInforme" class="TabbedPanels">
				<ul class="TabbedPanelsTabGroup">
					<li class="TabbedPanelsTab" tabindex="0">Introducción</li>
					<li class="TabbedPanelsTab" tabindex="1">Componentes</li>
					<li class="TabbedPanelsTab" tabindex="2">Productos</li>
					<li class="TabbedPanelsTab" tabindex="3">Actividades</li>
					<li class="TabbedPanelsTab" tabindex="4" onclick="LoadConclusiones(false);">Conclusiones</li>
					<li class="TabbedPanelsTab" tabindex="5" onclick="LoadCalificacion(false);">Calificación</li>
					<li class="TabbedPanelsTab" tabindex="6" onclick="LoadAnexosSI(false);">Anexos</li>
				</ul>
				<div class="TabbedPanelsContentGroup">
					<div class="TabbedPanelsContent">
						<div id="divIntroduccion" class="TableGrid">
							<table width="750" cellpadding="0" cellspacing="0">
								<tbody class="data" bgcolor="#FFFFFF">
									<tr>
										<td align="left" valign="middle">
										    <b>Introducción</b>
											<br/>
											<textarea name="intro" rows="10" id="intro" style="padding: 0px; width: 100%;"><?php echo($row['t45_intro']);?></textarea>
										</td>
									</tr>
									<tr>
										<td align="left" valign="middle">
										    <b>Métodos y Fuentes de Información utilizadas</b><br/>
										    <textarea name="fuentes" rows="10" id="fuentes" style="padding: 0px; width: 100%;"><?php echo($row['t45_fuentes']);?></textarea>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
					<div class="TabbedPanelsContent">
						<table width="100%" border="0" cellspacing="0" cellpadding="0"
							class="TableEditReg">
							<tr>
								<td width="8%">&nbsp;</td>
								<td colspan="2">&nbsp;</td>
								<td width="36%" rowspan="3" align="left" valign="middle"><input
									type="button" value="Guardar"
									title="Guardar Avance de Indicadores de Componente"
									onclick="Guardar_AvanceIndComp();" class="btn_save_custom"
									<?php echo ($pageMode == 'view') ? 'disabled' : '';?> /></td>
							</tr>
							<tr>
								<td><b>Componente</b></td>
								<td width="53%">
								    <select name="cbocomponente_ind" id="cbocomponente_ind" style="width: 500px;" onchange="LoadIndicadoresComponente();">
										<option value="" selected="selected"></option>
                                        <?php
                                            $rs = $objInf->ListaComponentes($idProy);
                                            $objFunc->llenarComboI($rs, 't08_cod_comp', 'componente', '');
                                            ?>
                                    </select>
                                </td>
								<td width="3%" align="center">
								    <input type="button" value="Refrescar" class="btn_save_custom" onclick="LoadIndicadoresComponente();"/>
							    </td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td colspan="2">&nbsp;</td>
							</tr>
						</table>
						<div id="divAvanceIndicadoresComponentes" class="TableGrid"></div>
					</div>
					<div class="TabbedPanelsContent">
						<table width="100%" border="0" cellspacing="0" cellpadding="0"
							class="TableEditReg">
							<tr>
								<td width="8%">&nbsp;</td>
								<td colspan="2">&nbsp;</td>
								<td width="36%" rowspan="3" align="left" valign="middle">
								    <input type="button" value="Guardar" onclick="Guardar_AvanceIndProd();" class="btn_save_custom"
									<?php echo ($pageMode == 'view') ? 'disabled' : '';?> /></td>
							</tr>
							<tr>
								<td><b>Producto</b>&nbsp;</td>
								<td width="53%">
								    <select name="cboprod_ind" id="cboprod_ind" style="width: 500px;" onchange="LoadIndicadoresProd();">
										<option value=""></option>
                                        <?php
                                        $rs = $objInf->listarProductosEnEntregable($idProy, $idVersion, $anio, $idEntregable);
                                        $objFunc->llenarComboI($rs, 'codigo', 'producto');
                                        ?>
                                    </select>
                                </td>
								<td width="3%" align="center">
								    <input type="button" value="Refrescar" class="btn_save_custom" onclick="LoadIndicadoresProd();" title="Refrescar Indicadores" /></td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td colspan="2">&nbsp;</td>
							</tr>
						</table>
						<div id="divAvanceProductos" class="TableGrid">
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
									<td width="36%" rowspan="3" align="left" valign="middle">
									    <input type="button" value="Guardar" onclick="Guardar_AvanceSubAct();" class="btn_save_custom"
										<?php echo ($pageMode == 'view') ? 'disabled' : '';?> />
									</td>
								</tr>
								<tr>
									<td><b>Producto</b></td>
									<td width="53%">
									    <select name="cboprod" id="cboprod" style="width: 500px;" onchange="LoadSubActividades();">
											<option value=""></option>
                                            <?php
                                            $rs = $objInf->ListaActividades($idProy);
                                            $objFunc->llenarComboI($rs, 'codigo', 'actividad');
                                            ?>
                                        </select>
                                    </td>
									<td width="3%" align="center">
									    <input type="button" value="Refrescar" class="btn_save_custom" onclick="LoadSubActividades();"/>
								    </td>
								</tr>
								<tr>
									<td colspan="3">&nbsp;</td>
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
						<div id="divConclusiones"></div>
					</div>
					<div class="TabbedPanelsContent">
						<div id="divCalificacion"></div>
					</div>
					<div class="TabbedPanelsContent">
						<div id="divAnexosFotograficos"></div>
					</div>
				</div>
			</div>
	<?php } else{?>
			<br />
			<span
				style="font-weight: bold; background-color: #EEEEEE; margin-right: 15px; font-size: 12px; float: right; color: #999999; padding: 5px 10px; border: solid 1px #999999;">Guardar
				la Carátula para continuar con el llenado de datos</span>
	<?php } ?>
   <p>&nbsp;</p>
		</div>
		<script language="javascript" type="text/javascript">

	    document.getElementById("terVisita").onchange=msjFLimite;

	    var estado = $("#estadoInf").val();
    	var AprobadoMT = "<?php echo($HardCode->EstInf_AprobMIT);?>";
    	var Correccion = "<?php echo($HardCode->EstInf_CorrMIT);?>";
    	var Elaboracion = "<?php echo($HardCode->EstInf_ElaME);?>";
    	var Revision	= "<?php echo($HardCode->EstInf_RevME);?>";
    	var Correc_ME	= "<?php echo($HardCode->EstInf_CorME);?>";
    	var VistoBno	= "<?php echo($HardCode->EstInf_VoBME);?>";
    	var Correc_MT	= "<?php echo($HardCode->EstInf_CorMTME);?>";
    	var AprobadoCMT	= "<?php echo($HardCode->EstInf_ApprCMTME);?>";

	    ConfigInfTrim();
		function ConfigInfTrim()
		{
			$(".Cabecera:text").attr('readonly', 'readonly');
			$("select.Cabecera>option:not(:selected)").attr('disabled', 'disabled');

			$('#estadoInf option[value="'+VistoBno+'"]').remove();
			$('#estadoInf option[value="'+Correc_MT+'"]').remove();
		}

            function Guardar_InformeCab()
        	{
        		//if(valDiasH()){
        		<?php $ObjSession->AuthorizedPage('EDITAR'); ?>
        		var BodyForm="anio="+$("#anio").val()+"&idEntregable="+$("#idEntregable").val()+"&periodo="+$('#periodo').val()+"&fchPresentacion="+$("#fchPresentacion").val()+"&estadoInf="+$('#estadoInf').val()+"&idProy="+$('#txtCodProy').val()+"&obsGP="+$('#obsGP').val()+"&idVersion="+$("#idVersion").val()+"&iniVisita="+$("#iniVisita").val()+"&terVisita="+$("#terVisita").val()+"&intro="+$("#intro").val()+"&fuentes="+$("#fuentes").val();
        		var sURL = "inf_monint_process.php?action=<?php echo($action);?>";
        		var req = Spry.Utils.loadURL("POST", sURL, true, informeSuccessCallback, { postData: BodyForm, headers: {"Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
        		//}
        	}

        	function informeSuccessCallback(req)
        	{
        	  var respuesta = req.xhRequest.responseText;
        	  respuesta = respuesta.replace(/^\s*|\s*$/g,"");
        	  var ret = respuesta.substring(0,5);

        	  if(ret=="Exito")
        	  {
        	    alert(respuesta.replace(ret,""));
        		dsLista.loadData();
        		btnEditar_Clic($('#anio').val(), $('#idEntregable').val());
        	  }
        	  else
        	  {
        		  alert($('<div></div>').html(respuesta).text());
        	  }
        	}

        	function valDiasH(){

        		if($('#terVisita').val()==""){ alert("Ud. no ingreso la Fecha del termino de la visita"); return false;}
        		if($('#iniVisita').val()==""){ alert("Ud. no ingreso la Fecha del inicio de la visita"); return false;}

        		var fPresentacion = ($('#fchPresentacion').val()).split("/");
        		var fVisitaTermino = ($('#terVisita').val()).split("/");
        		var fVisitaInicio = ($('#iniVisita').val()).split("/");

        		var presentacion = new Date(fPresentacion[2],fPresentacion[1]-1,fPresentacion[0] );
        		var vTermino = new Date(fVisitaTermino[2],fVisitaTermino[1]-1,fVisitaTermino[0]);
        		var vInicio = new Date(fVisitaInicio[2],fVisitaInicio[1]-1,fVisitaInicio[0]);
        		var perfin=($("#ffinal>option:selected").text()).split(" ");
        		var mes=perfin[2].slice(1);

        		var anio = perfin[3].slice(0, -1);
        		var nanio=parseInt(anio);
        		var nmes=0;
        		switch (mes) {
        			case 'Ene':
        			   nmes=1
        			   break
        			case 'Feb':
        			   nmes=2
        			   break
        			case 'Mar':
        			   nmes=3
        			   break
        			case 'Abr':
        			   nmes=4
        			case 'May':
        			   nmes=5
        			   break
        			case 'Jun':
        			   nmes=6
        			   break
        			case 'Jul':
        			   nmes=7
        			   break
        			 case 'Ago':
        			   nmes=8
        			   break
        			case 'Set':
        			   nmes=9
        			   break
        			case 'Oct':
        			   nmes=10
        			   break
        			case 'Nov':
        			   nmes=11
        			   break
        			case 'Dic':
        			   nmes=12
        			   break
        			default:
        			   nmes=0
        		}
        		var fper=nanio*12+nmes;
        		var fvin=parseInt(fVisitaInicio[2])*12+parseInt(fVisitaInicio[1]);

        		var d=fvin-fper;
        		if(d>1){alert("Fecha de la Visita: No esta en el plazo de los 30 dias"); return false;}

        		var diferencia = presentacion.getTime() - vTermino.getTime();
        		var diferenciaVisita = vTermino.getTime()- vInicio.getTime();

        		var dias = Math.floor(diferencia / (1000 * 60 * 60 * 24));
        		var diasV = Math.floor(diferenciaVisita / (1000 * 60 * 60 * 24));

        		if(dias <= 0){alert("Aún no termina la fecha de visita"); return false;}
        		/*else if(dias>10){alert("Usted se paso de los 10 dias habiles para reportar su informe"); return false;}*/

        		if(diasV < 3){alert("El periodo de la visita debe ser mayor a 4 dias"); return false;}
        		//if(diasV>30){alert("Fecha de la Visita: No esta en el plazo de los 30 dias"); return false;}
        		return true;
        	}

        	function msjFLimite(){
        		var fecha = document.getElementById("terVisita").value;
        		var fechaPresentacion = document.getElementById("fchPresentacion").value;
        		if(fecha!="")
        		{
        			var ftermino = fecha.split("/");
        			var fpresentacion = fechaPresentacion.split("/");

        			var dateFTermino = new Date(ftermino[2],ftermino[1]-1,ftermino[0]);
        			var datePlazo = new Date(ftermino[2],ftermino[1]-1,(parseInt(ftermino[0])+10));
        			var datePresentacion =  new Date(fpresentacion[2],fpresentacion[1]-1,fpresentacion[0]);
        			var diferencia = datePlazo.getTime() - datePresentacion.getTime();
        			var dias = Math.floor(diferencia/(1000*60*60*24))

        			var mensaje="La Fecha límite para presentar el informe es ";
        			if(dias<0)
        				mensaje="La fecha de presentación no está dentro del plazo establecido. Fecha Limite era :";
        				document.getElementById("mensaje").innerHTML=mensaje+((datePlazo.getDate() > 9) ? datePlazo.getDate() : "0"+datePlazo.getDate())+"/"+
        															(((datePlazo.getMonth()+1) > 9) ? (datePlazo.getMonth()+1) : "0"+(datePlazo.getMonth()+1))+"/"+
        															datePlazo.getFullYear();
        		}
        	}

           function LoadIndicadoresComponente()
        	{
        		var comp = $('#cbocomponente_ind').val();
        		var fecha = $('#fchPresentacion').val();
        		if(fecha=="" || fecha==null){alert("Ingrese fecha del Informe y Guarde los datos");  $('#cbocomponente_ind').val(null); return false;}
        		var BodyForm = "action=<?php echo(md5("lista_ind_comp"));?>&idProy=<?php echo($idProy);?>&idVersion=<?php echo($idVersion);?>&idComp="+comp+"&anio="+$("#anio").val()+"&idEntregable="+$("#idEntregable").val();
        	 	var sURL = "inf_monint_ind_comp.php";
        		$('#divAvanceIndicadoresComponentes').html("<p align='center'><img src='../../../img/indicator.gif' width='16' height='16' /><br>Cargando..<br></p>");
        	 	var req = Spry.Utils.loadURL("POST", sURL, true, SuccessIndicadoresComp, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
        	}

        	function SuccessIndicadoresComp(req)
        	{
        	   var respuesta = req.xhRequest.responseText;
        	   $("#divAvanceIndicadoresComponentes").html(respuesta);
         	   return;
        	}

            function LoadIndicadoresProd()
        	{
        		var BodyForm = "idProy=<?php echo($idProy);?>&idVersion=<?php echo($idVersion);?>&idProd="+ $('#cboprod_ind').val() + "&anio="+$("#anio").val()+"&idEntregable="+$("#idEntregable").val();
        	 	var sURL = "inf_monint_ind_prod.php";
        		$('#divAvanceProductos').html("<p align='center'><img src='../../../img/indicator.gif' width='16' height='16' /><br>Cargando..<br></p>");
        	 	var req = Spry.Utils.loadURL("POST", sURL, true, SuccessIndicadoresAct, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
        	}

        	function SuccessIndicadoresAct(req)
        	{
        	   var respuesta = req.xhRequest.responseText;
        	   $("#divAvanceProductos").html(respuesta);
         	   return;
        	}

        	function onErrorLoad(req)
        	{
        		alert("Ocurrio un error al cargar los datos");
        	}

        	function LoadSubActividades()
        	{
        		var BodyForm = "idProy=<?php echo($idProy);?>&idVersion=<?php echo($idVersion);?>&idProd="+ $('#cboprod').val() + "&anio="+$("#anio").val()+"&idEntregable="+$("#idEntregable").val();
        	 	var sURL = "inf_monint_act.php";
        		$('#divAvanceSubActividades').html("<p align='center'><img src='../../../img/indicator.gif' width='16' height='16' /><br>Cargando..<br></p>");
        	 	var req = Spry.Utils.loadURL("POST", sURL, true, SuccessSubAct, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
        	}
        	function SuccessSubAct(req)
        	{
        	   var respuesta = req.xhRequest.responseText;
        	   $("#divAvanceSubActividades").html(respuesta);
         	   return;
        	}

        	function LoadConclusiones(recargar)
        	{
        		if($('#divConclusiones').html()!="")
        		{
        			if(!recargar){return false;}
        		}

        		var BodyForm = "idProy=<?php echo($idProy);?>&anio="+$('#anio').val()+"&idEntregable="+$('#idEntregable').val();
        	 	var sURL = "inf_monint_conclusiones.php";
        		$('#divConclusiones').html("<p align='center'><img src='../../../img/indicator.gif' width='16' height='16' /><br>Cargando..<br></p>");
        	 	var req = Spry.Utils.loadURL("POST", sURL, true, SuccessConclusiones, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
        	}
        	function SuccessConclusiones(req)
        	{
        	   var respuesta = req.xhRequest.responseText;
        	   $("#divConclusiones").html(respuesta);
         	   return;
        	}
        	function LoadCalificacion(recargar)
        	{
        		if($('#divCalificacion').html()!="")
        		{
        			if(!recargar){return false;}
        		}
        		var BodyForm = "idProy=<?php echo($idProy);?>&anio="+$('#anio').val()+"&idEntregable="+$('#idEntregable').val();
        	 	var sURL = "inf_monint_califica.php";
        		$('#divCalificacion').html("<p align='center'><img src='../../../img/indicator.gif' width='16' height='16' /><br>Cargando..<br></p>");
        	 	var req = Spry.Utils.loadURL("POST", sURL, true, SuccessCalifica, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
        	}

        	function SuccessCalifica(req)
        	{
        	   var respuesta = req.xhRequest.responseText;
        	   $("#divCalificacion").html(respuesta);
         	   return;
        	}

        	function LoadAnexosSI(recargar)
        	{
        		if($('#divAnexosFotograficos').html()!="")
        		{
        			if(!recargar){return false;}
        		}

        		var BodyForm = "idProy=<?php echo($idProy);?>&anio="+$('#anio').val()+"&idEntregable="+$('#idEntregable').val();
        	 	var sURL = "inf_monint_anx.php";
        		$('#divAnexosFotograficos').html("<p align='center'><img src='../../../img/indicator.gif' width='16' height='16' /><br>Cargando..<br></p>");
        	 	var req = Spry.Utils.loadURL("POST", sURL, true, SuccessAnxFotos, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
        	}

        	function SuccessAnxFotos(req)
        	{
        	   var respuesta = req.xhRequest.responseText;
        	   $("#divAnexosFotograficos").html(respuesta);
         	   return;
        	}

        	<?php if(md5("ajax_new")==$action || md5("ajax_edit")==$action) { ?>
        		$("#estadoInf option").attr('disabled','disabled');
        		$("#estadoInf option:selected").removeAttr('disabled');
        		$("#fchPresentacion").attr("readonly","readonly");
        	<?php } ?>

        	$("#fchPresentacion").mask("99/99/9999");
        	$("#iniVisita").datepicker();
        	$("#terVisita").datepicker();

        	$("#iniVisita").mask("99/99/9999");
        	$("#terVisita").mask("99/99/9999");

        	<?php if (md5("ajax_edit")==$action) {
        	        if ($ObjSession->PerfilID == $HardCode->Admin) { ?>
        		$('#estadoInf option').removeAttr('disabled');
        	<?php }

        	    if ($ObjSession->PerfilID == $HardCode->RA) { ?>
        			if(estado == Revision ) {
        				$('#estadoInf option[value="'+Correc_ME+'"]').removeAttr('disabled');
        				$('#estadoInf option[value="'+VistoBno+'"]').removeAttr('disabled');
        				$('#estadoInf option[value="'+AprobadoCMT+'"]').removeAttr('disabled');
        			}
        			else if(estado == VistoBno ) {
        				$('#estadoInf option[value="'+Correc_MT+'"]').removeAttr('disabled');
        				$('#estadoInf option[value="'+AprobadoCMT+'"]').removeAttr('disabled');
        			}
        	<?php } // end if ?>
        	<?php if ($ObjSession->PerfilID == $HardCode->GP) { ?>
        			if(estado == Revision ) {
        				$('#estadoInf option[value="'+Correc_ME+'"]').removeAttr('disabled');
        				$('#estadoInf option[value="'+VistoBno+'"]').removeAttr('disabled');
        			}
        			else if(estado == Correc_MT ) {
        				$('#estadoInf option[value="'+VistoBno+'"]').removeAttr('disabled');
        			}
        	<?php } // end if ?>
        	<?php if ($ObjSession->PerfilID == $HardCode->GP) { ?>
        			if( estado == Elaboracion || estado == Correc_ME) {
        				$('#estadoInf option[value="'+Revision+'"]').removeAttr('disabled');
        			}
        	<?php } // end if ?>
        	<?php } // enf if ?>

        	msjFLimite();
          </script>
<?php if($objFunc->__QueryString()=="") { ?>
</form>
	<script type="text/javascript">
    <!--
    var TabsInforme = new Spry.Widget.TabbedPanels("ssTabInforme", {defaultTab:2});
    //-->
    </script>
</body>
</html>
<?php } ?>