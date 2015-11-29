<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>

<?php
// require(constant("PATH_CLASS")."BLInformes.class.php");
require_once (constant("PATH_CLASS") . "BLMonitoreoFinanciero.class.php");
require_once (constant("PATH_CLASS") . "BLTablasAux.class.php");
require_once (constant("PATH_CLASS") . "HardCode.class.php");

// $objInf = new BLInformes();
$objInf = new BLMonitoreoFinanciero();
$HardCode = new HardCode();

// error_reporting("E_PARSE");
$idProy = $objFunc->__Request('idProy');
$idInforme = $objFunc->__Request('idnum');

$row = $objInf->Inf_visita_MF_Seleccionar($idProy, $idInforme);

/*
 * echo("<pre style='text-align=left;'>"); print_r($row);
 */

$action = $objFunc->__GET('mode');

if (md5("ajax_new") == $action) {
    $objFunc->SetSubTitle('Informe de Visita del Monitor Financiero - Nuevo Registro');
    $row = NULL;
    $row['t52_fch_pre'] = date('d/m/Y');
    // $idInforme = 0;
}
if (md5("ajax_edit") == $action) {
    $objFunc->SetSubTitle('Informe de Visita del Monitor Financiero - Editar Registro');
}

if ($objFunc->__QueryString() == "") {
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
<script src="../../../jquery.ui-1.5.2/ui/ui.datepicker.js"
	type="text/javascript"></script>
<link href="../../../jquery.ui-1.5.2/themes/ui.datepicker.css"
	rel="stylesheet" type="text/css" />
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
							onclick="Guardar_InformeCab(); return false;" value="Guardar">Guardar
						</button></td>
					<td width="25%"><button class="Button"
							onclick="btnCancelar_Clic(); return false;" value="Cancelar"
							style="white-space: nowrap;">Cerrar y Volver</button></td>
					<td width="5%" nowrap="nowrap">&nbsp;</td>
					<td width="61%" align="right"><?php echo($objFunc->SubTitle) ;?></td>
				</tr>
			</table>
		</div>

		<div>

			<div id="divCabeceraInforme">
				<table width="100%" border="0" cellspacing="0" cellpadding="0"
					class="TableEditReg">
					<tr>
						<td colspan="7"><strong>1. Caratula</strong></td>
					</tr>
					<tbody>
						<tr>
							<td width="18%" height="24" nowrap="nowrap">Tipo de Visita <input
								type="hidden" id="t52_num" name="t52_num"
								value="<?php echo($row['t52_num']);?>" />
							</td>
							<td colspan="3" align="left" nowrap="nowrap"><select
								name="cbocuestionario" id="cbocuestionario"
								style="width: 400px; font-size: 11px;"
								onchange="MostrarCuestionario(this.value);">
									<option value="1"
										<?php if($row['t52_tipo_vis']=='1'){echo('selected="selected"');} ?>
										style="font-size: 10px; color: #00C"
										title="Visita de Supervisión Previa al Inicio del Proyecto">Visita
										Previa al Inicio del Proyecto</option>
									<option value="2"
										<?php if($row['t52_tipo_vis']=='2'){echo('selected="selected"');} ?>
										style="font-size: 10px; color: #F00;"
										title="Visita de Supervisión en la Ejecución del Proyecto">
										Visita durante la Ejecución del proyecto</option>
							</select></td>
							<td width="9%" align="right" nowrap="nowrap">&nbsp;</td>
							<td width="40%" align="left" nowrap="nowrap">&nbsp;</td>
							<td width="1%" align="left" nowrap="nowrap">&nbsp;</td>
						</tr>
						<tr>
							<td height="24" nowrap="nowrap">Fecha de Presentación</td>
							<td align="left" nowrap="nowrap"><input name="t52_fch_pre"
								type="text" class="Cabecera" id="t52_fch_pre"
								value="<?php echo($row['t52_fch_pre']); ?>" size="20"
								maxlength="12" readonly="readonly" /></td>
							<td align="left" nowrap="nowrap">&nbsp;</td>
							<td align="left" nowrap="nowrap">&nbsp;</td>
							<td align="right" nowrap="nowrap">Estado</td>
							<td align="left" nowrap="nowrap"><select name="cboestado"
								id="cboestado" style="width: 130px;" class="InformeM">
									<option value=""></option>
          <?php
        $objTablas = new BLTablasAux();
        $rs = $objTablas->EstadoInformesFinanc();
        $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $row['t52_estado']);
        $objTablas = NULL;
        ?>
        </select></td>
							<td align="left" nowrap="nowrap">&nbsp;</td>
						</tr>
						<tr>
							<td colspan="4"><font style="font-weight: bold; color: #00F;">Observaciones
									del Coordinador de Monitoreo Financiero </font> <br> <textarea
									id="txtObsCMT" style="padding: 0px; width: 100%;" rows="2"
									cols="2500" name="txtObsCMT"
									<?php if($ObjSession->PerfilID!=$HardCode->CMF){echo 'disabled';} ?>><?php print $row['t52_obs_cmt'] ?></textarea>
							</td>
						</tr>
					</tbody>
				</table>
				<table width="100%" border="0" cellspacing="0" cellpadding="0"
					class="TableEditReg" id="tbPeriodo">
					<tbody>
						<tr>
							<td width="10%" height="25" nowrap="nowrap">Periodo de Referencia</td>
							<td width="12%" nowrap="nowrap">Desde<br /> <select
								name="cboper_ini" class="Cabecera" id="cboper_ini"
								style="width: 150px;">
									<option value="" selected="selected"></option>
          <?php
        $rs = $objInf->ListadoPeriodosEjecutados($idProy);
        $objFunc->llenarComboI($rs, 'codigo', 'periodo', $row['t52_per_ini']);
        ?>
        </select></td>
							<td width="3%" align="center">&nbsp;</td>
							<td width="24%">Hasta <br /> <select name="cboper_fin"
								class="Cabecera" id="cboper_fin" style="width: 150px;">
									<option value="" selected="selected"></option>
          <?php
        $rs = $objInf->ListadoPeriodosEjecutados($idProy);
        $objFunc->llenarComboI($rs, 'codigo', 'periodo', $row['t52_per_fin']);
        ?>
        </select></td>
							<td width="9%">&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td height="27">&nbsp;</td>
							<td nowrap="nowrap">&nbsp;</td>
							<td align="center" nowrap="nowrap">&nbsp;</td>
							<td colspan="5">&nbsp;</td>
						</tr>
						<tr>
							<td colspan="8" align="right"><a
								href="javascript:AvancePresup();">Ver Cuadro Avance Presupuestal
							</a> &nbsp;&nbsp;&nbsp;&nbsp; <a href="javascript:AvanceTec();">Ver
									Cuadro Avance Técnico</a></td>
						</tr>
					</tbody>
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td colspan="5">&nbsp;</td>
					</tr>
				</table>
			</div>
	<?php
if ($idInforme) {
    
    ?>
	
    <div id="ssTabInforme" class="TabbedPanels">
				<ul class="TabbedPanelsTabGroup">
					<li class="TabbedPanelsTab" tabindex="0">Cuestionario</li>
					<li class="TabbedPanelsTab" tabindex="1">Conclusiones</li>
					<li class="TabbedPanelsTab" tabindex="2"
						onclick="LoadAnexos(false);">Anexos</li>
				</ul>
				<div class="TabbedPanelsContentGroup">
					<div class="TabbedPanelsContent">
						<div style="display: inline-block;">
							<table width="650" border="0" align="left" cellpadding="0"
								cellspacing="0">
								<tr>
									<td height="36"><strong style="font-size: 14px;"
										id="lbltitleCuestionario">Tipo Cuestionario</strong></td>
								</tr>
								<tr>
									<td height="59">
										<fieldset style="background-color: #FFF;">
											<legend>Persona Entrevistada</legend>
											<table border="0" cellspacing="0" cellpadding="0" width="700"
												style="padding: 1px;" class="TableEditReg">
												<tr>
													<td width="174" height="30" valign="top"><strong>Nombre:</strong></td>
													<td width="483" valign="top"><input name="txtpersona"
														type="text" class="Cabecera" id="txtpersona"
														value="<?php echo($row['t52_nom_per_ent'])?>" size="60"
														maxlength="50" /></td>
												</tr>
												<tr>
													<td width="174" height="25" valign="top"><strong>Cargo:</strong></td>
													<td width="483" valign="top"><input name="txtcargoper"
														type="text" class="Cabecera" id="txtcargoper"
														value="<?php echo($row['t52_car_per_ent'])?>" size="50"
														maxlength="30" /></td>
												</tr>
											</table>
										</fieldset>
									</td>
								</tr>
								<tr>
									<td height="59">
										<fieldset style="background-color: #FFF;">
											<legend>Cuestionario</legend>
											<div id="divCuestionarioInicial">
												<table width="700" border="0" cellpadding="0"
													cellspacing="0" class="TableEditReg">
													<tbody style="border: solid 1px #666;" class="data">
														<tr style="border: solid 1px, #666;">
															<td width="39%" height="21" valign="top"
																style="background-color: #E9E9E9;"><strong>DETALLE</strong></td>
															<td width="8%" valign="top"
																style="background-color: #E9E9E9;"><strong>SI /NO</strong></td>
															<td width="52%" valign="top"
																style="background-color: #E9E9E9;"><strong>COMENTARIOS</strong></td>
														</tr>
														<tr>
															<td height="21" colspan="3" valign="top"><strong>Información
																	Contable Presupuestal</strong></td>
														</tr>
														<tr>
															<td width="39%" height="35" valign="top"><p>
																	La Institución cuenta con un <br /> programa de
																	contabilidad adecuado?
																</p></td>
															<td width="8%" align="center" valign="middle"><input
																name="chkopcion1" type="checkbox"
																<?php if($row['t52_quest_01']=='1'){echo('checked="checked"');} ?>
																id="chkopcion1" onclick="HabilitarOpcion1(this.id);"
																value="1" /></td>
															<td width="52%" valign="middle"><textarea
																	name="txtquest1" id="txtquest1" cols="60" rows="2"><?php echo($row['t52_obs_01']);?></textarea>
															</td>
														</tr>
														<tr>
															<td width="39%"><em
																style="padding-left: 30px; display: block;">a) La
																	contabilidad institucional&nbsp;es llevada con el
																	sistema contable referido?</td>
															<td width="8%" align="center" valign="middle"><input
																name="chkopcion2" type="checkbox"
																<?php if($row['t52_quest_02']=='1'){echo('checked="checked"');} ?>
																id="chkopcion2" value="1" disabled="disabled" /></td>
															<td width="52%" valign="middle"><textarea
																	name="txtquest2" cols="60" rows="2" id="txtquest2"><?php echo($row['t52_obs_02']);?></textarea></td>
														</tr>
														<tr>
															<td height="26"><em
																style="padding-left: 30px; display: block;">b) El
																	programa de contabilidad&nbsp; permite manejar la
																	ejecución presupuestal por proyectos de acuerdo a lo
																	requerido por FE?</td>
															<td align="center" valign="middle"><input
																name="chkopcion3" type="checkbox"
																<?php if($row['t52_quest_03']=='1'){echo('checked="checked"');} ?>
																id="chkopcion3" value="1" disabled="disabled" /></td>
															<td valign="middle"><textarea name="txtquest3" cols="60"
																	rows="2" id="txtquest3"><?php echo($row['t52_obs_03']);?></textarea></td>
														</tr>
														<tr>
															<td height="25"><em
																style="padding-left: 30px; display: block;">c) La
																	contabilidad institucional se encuentra al día?<br /></td>
															<td align="center" valign="middle"><input
																name="chkopcion4" type="checkbox"
																<?php if($row['t52_quest_04']=='1'){echo('checked="checked"');} ?>
																id="chkopcion4" value="1" disabled="disabled" /></td>
															<td valign="middle"><textarea name="txtquest4" cols="60"
																	rows="2" id="txtquest4"><?php echo($row['t52_obs_04']);?></textarea></td>
														</tr>
														<tr>
															<td width="39%" height="35" valign="top">Los registros
																contables de las operaciones institucionales y de&nbsp;
																los proyectos se realizan de acuerdo a las normas y
																principios contables?</td>
															<td width="8%" align="center" valign="middle"><input
																name="chkopcion5" type="checkbox"
																<?php if($row['t52_quest_05']=='1'){echo('checked="checked"');} ?>
																id="chkopcion5" value="1" /></td>
															<td width="52%" valign="middle"><textarea
																	name="txtquest5" id="txtquest5" cols="60" rows="2"><?php echo($row['t52_obs_05']);?></textarea></td>
														</tr>
														<tr>
															<td width="39%" valign="top">La Institución ha
																implementado las cuentas contables y/o anexos&nbsp;
																dentro del sistema contable institucional, que permita
																diferenciar las operaciones del proyecto de las
																operaciones ajenas ( de otros proyectos o&nbsp; propios
																de LA INSTITUCION),</td>
															<td width="8%" align="center" valign="middle"><input
																name="chkopcion6" type="checkbox"
																<?php if($row['t52_quest_06']=='1'){echo('checked="checked"');} ?>
																id="chkopcion6" value="1" /></td>
															<td width="52%" valign="middle"><textarea
																	name="txtquest6" id="txtquest6" cols="60" rows="2"><?php echo($row['t52_obs_06']);?></textarea></td>
														</tr>
														<tr>
															<td width="39%" valign="top">Los análisis de cuentas que
																arroje el sistema contable sirven de base para los
																Anexos solicitados por FONDOEMPLEO</td>
															<td width="8%" align="center" valign="middle"><input
																name="chkopcion7" type="checkbox"
																<?php if($row['t52_quest_07']=='1'){echo('checked="checked"');} ?>
																id="chkopcion7" value="1" /></td>
															<td width="52%" valign="middle"><textarea
																	name="txtquest7" id="txtquest7" cols="60" rows="2"><?php echo($row['t52_obs_07']);?></textarea></td>
														</tr>
													</tbody>
												</table>
											</div>
											<div id="divCuestionarioEjecucion">
												<table width="700" border="0" cellpadding="0"
													cellspacing="0" class="TableEditReg">
													<tbody style="border: solid 1px #666;" class="data";>
														<tr style="border: solid 1px, #666;">
															<td width="39%" height="21" valign="top"
																style="background-color: #E9E9E9;"><strong>DETALLE</strong></td>
															<td width="8%" valign="top"
																style="background-color: #E9E9E9;"><strong>SI /NO</strong></td>
															<td width="52%" valign="top"
																style="background-color: #E9E9E9;"><strong>COMENTARIOS</strong></td>
														</tr>
														<tr>
															<td height="21" colspan="3" valign="top"><strong>I.
																	Información Administrativa </strong></td>
														</tr>
														<tr>
															<td width="39%" height="35" valign="top"><p>&iquest;Se
																	cuenta con la relación de todo el personal técnico y
																	administrativo de la Institución?</p></td>
															<td width="8%" align="center" valign="middle"><input
																name="chkopcion1" type="checkbox"
																<?php if($row['t52_quest_01']=='1'){echo('checked="checked"');} ?>
																id="chkopcion1" onclick="HabilitarOpcion1(this.id);"
																value="1" /></td>
															<td width="52%" valign="middle"><textarea
																	name="txtquest1" id="txtquest1" cols="60" rows="2"><?php echo($row['t52_obs_01']);?></textarea>
															</td>
														</tr>
														<tr>
															<td>&iquest;Cuenta un registro adecuado de control de
																personal?</td>
															<td align="center" valign="middle"><input
																name="chkopcion2" type="checkbox"
																<?php if($row['t52_quest_02']=='1'){echo('checked="checked"');} ?>
																id="chkopcion2" onclick="HabilitarOpcion1(this.id);"
																value="1" /></td>
															<td valign="middle"><textarea name="txtquest2"
																	id="txtquest2" cols="60" rows="2"><?php echo($row['t52_obs_02']);?></textarea>
															</td>
														</tr>
														<tr>
															<td>&iquest;La institución cumple con los procedimientos
																Administrativos según su manual enviado a FE?</td>
															<td align="center" valign="middle"><input
																name="chkopcion3" type="checkbox"
																<?php if($row['t52_quest_03']=='1'){echo('checked="checked"');} ?>
																id="chkopcion3" onclick="HabilitarOpcion1(this.id);"
																value="1" /></td>
															<td valign="middle"><textarea name="txtquest3"
																	id="txtquest3" cols="60" rows="2"><?php echo($row['t52_obs_03']);?></textarea>
															</td>
														</tr>
														<tr>
															<td colspan="3"><strong>II. Información contable
																	presupuestal y Control Interno</strong></td>
														</tr>
														<tr>
															<td width="39%"><em
																style="padding-left: 30px; display: block;">a) La
																	contabilidad institucional&nbsp;es llevada con el
																	sistema contable referido?</td>
															<td width="8%" align="center" valign="middle"><input
																name="chkopcion4" type="checkbox"
																<?php if($row['t52_quest_04']=='1'){echo('checked="checked"');} ?>
																id="chkopcion4" value="1" /></td>
															<td width="52%" valign="middle"><textarea
																	name="txtquest4" cols="60" rows="2" id="txtquest4"><?php echo($row['t52_obs_04']);?></textarea></td>
														</tr>
														<tr>
															<td height="26"><em
																style="padding-left: 30px; display: block;">b) El
																	programa de contabilidad&nbsp; permite manejar la
																	ejecución presupuestal por proyectos de acuerdo a lo
																	requerido por FE?</td>
															<td align="center" valign="middle"><input
																name="chkopcion5" type="checkbox"
																<?php if($row['t52_quest_05']=='1'){echo('checked="checked"');} ?>
																id="chkopcion5" value="1" /></td>
															<td valign="middle"><textarea name="txtquest5" cols="60"
																	rows="2" id="txtquest5"><?php echo($row['t52_obs_05']);?></textarea></td>
														</tr>
														<tr>
															<td height="25"><em
																style="padding-left: 30px; display: block;">c) La
																	contabilidad institucional se encuentra al día?<br /></td>
															<td align="center" valign="middle"><input
																name="chkopcion6" type="checkbox"
																<?php if($row['t52_quest_06']=='1'){echo('checked="checked"');} ?>
																id="chkopcion6" value="1" /></td>
															<td valign="middle"><textarea name="txtquest6" cols="60"
																	rows="2" id="txtquest6"><?php echo($row['t52_obs_06']);?></textarea></td>
														</tr>
														<tr>
															<td width="39%" height="35" valign="top">Los registros
																contables de las operaciones institucionales y de&nbsp;
																los proyectos se realizan de acuerdo a las normas y
																principios contables?</td>
															<td width="8%" align="center" valign="middle"><input
																name="chkopcion7" type="checkbox"
																<?php if($row['t52_quest_07']=='1'){echo('checked="checked"');} ?>
																id="chkopcion7" value="1" /></td>
															<td width="52%" valign="middle"><textarea
																	name="txtquest7" id="txtquest7" cols="60" rows="2"><?php echo($row['t52_obs_07']);?></textarea></td>
														</tr>
														<tr>
															<td width="39%" valign="top">La Institución ha
																implementado las cuentas contables y/o anexos&nbsp;
																dentro del sistema contable institucional, que permita
																diferenciar las operaciones del proyecto de las
																operaciones ajenas ( de otros proyectos o&nbsp; propios
																de LA INSTITUCION),</td>
															<td width="8%" align="center" valign="middle"><input
																name="chkopcion8" type="checkbox"
																<?php if($row['t52_quest_08']=='1'){echo('checked="checked"');} ?>
																id="chkopcion8" value="1" /></td>
															<td width="52%" valign="middle"><textarea
																	name="txtquest8" id="txtquest8" cols="60" rows="2"><?php echo($row['t52_obs_08']);?></textarea></td>
														</tr>
														<tr>
															<td width="39%" valign="top">Los análisis de cuentas que
																arroje el sistema contable sirven de base para los
																Anexos solicitados por FONDOEMPLEO</td>
															<td width="8%" align="center" valign="middle"><input
																name="chkopcion9" type="checkbox"
																<?php if($row['t52_quest_09']=='1'){echo('checked="checked"');} ?>
																id="chkopcion9" value="1" /></td>
															<td width="52%" valign="middle"><textarea
																	name="txtquest9" id="txtquest9" cols="60" rows="2"><?php echo($row['t52_obs_09']);?></textarea></td>
														</tr>
														<tr>
															<td valign="top">Se han presentado los Informes
																Financieros de Ejecución Presupuestal en las fechas
																establecidas</td>
															<td align="center" valign="middle"><input
																name="chkopcion10" type="checkbox"
																<?php if($row['t52_quest_10']=='1'){echo('checked="checked"');} ?>
																id="chkopcion10" value="1" /></td>
															<td valign="middle"><textarea name="txtquest10"
																	id="txtquest10" cols="60" rows="2"><?php echo($row['t52_obs_10']);?></textarea></td>
														</tr>
														<tr>
															<td colspan="3" valign="top"><h3>Control interno.</h3></td>
														</tr>
														<tr>
															<td colspan="3" valign="top"><p>
																	<strong>Logística o Abastecimiento</strong>
																</p></td>
														</tr>
														<tr>
															<td><ul>
																	<li>Las compra mayor a S/. 2,000.00 cuentan con las
																		tres cotizaciones.</li>
																</ul></td>
															<td align="center" valign="middle"><input
																name="chkopcion11" type="checkbox"
																<?php if($row['t52_quest_11']=='1'){echo('checked="checked"');} ?>
																id="chkopcion11" value="1" /></td>
															<td valign="middle"><textarea name="txtquest11" cols="60"
																	rows="2" id="txtquest11"><?php echo($row['t52_obs_11']);?></textarea></td>
														</tr>
														<tr>
															<td height="26"><ul>
																	<li>Las adquisiciones mayores a S/. 7,000.00&nbsp;
																		cuentan con la aprobación previa de FONDOEMPLEO.</li>
																</ul></td>
															<td align="center" valign="middle"><input
																name="chkopcion12" type="checkbox"
																<?php if($row['t52_quest_12']=='1'){echo('checked="checked"');} ?>
																id="chkopcion12" value="1" /></td>
															<td valign="middle"><textarea name="txtquest12" cols="60"
																	rows="2" id="txtquest12"><?php echo($row['t52_obs_12']);?></textarea></td>
														</tr>
														<tr>
															<td colspan="3" valign="top"><p>
																	<strong>Tesorería</strong>
																</p></td>
														</tr>
														<tr>
															<td><ul>
																	<li>Cuenta con un control detallado por cada cuenta
																		bancaria que maneje para fines del Proyecto.</li>
																</ul></td>
															<td align="center" valign="middle"><input
																name="chkopcion13" type="checkbox"
																<?php if($row['t52_quest_13']=='1'){echo('checked="checked"');} ?>
																id="chkopcion13" value="1" /></td>
															<td valign="middle"><textarea name="txtquest13" cols="60"
																	rows="2" id="txtquest13"><?php echo($row['t52_obs_13']);?></textarea></td>
														</tr>
														<tr>
															<td height="26"><ul>
																	<li>Se han realizado operaciones de transferencias a
																		otras cuentas no relacionadas con el proyecto.</li>
																</ul></td>
															<td align="center" valign="middle"><input
																name="chkopcion14" type="checkbox"
																<?php if($row['t52_quest_14']=='1'){echo('checked="checked"');} ?>
																id="chkopcion14" value="1" /></td>
															<td valign="middle"><textarea name="txtquest14" cols="60"
																	rows="2" id="txtquest14"><?php echo($row['t52_obs_14']);?></textarea></td>
														</tr>
														<tr>
															<td valign="top"><ul>
																	<li>La institución ha realizado arqueos al Fondos fijo
																		destinado al proyecto</li>
																</ul></td>
															<td align="center" valign="middle"><input
																name="chkopcion15" type="checkbox"
																<?php if($row['t52_quest_15']=='1'){echo('checked="checked"');} ?>
																id="chkopcion15" value="1" /></td>
															<td valign="middle"><textarea name="txtquest15" cols="60"
																	rows="2" id="txtquest15"><?php echo($row['t52_obs_15']);?></textarea></td>
														</tr>
														<tr>
															<td valign="top"><ul>
																	<li>Las entregas a rendir están siendo liquidadas en
																		los plazos establecidos (tres días máximo de
																		efectuado el gasto).</li>
																</ul></td>
															<td align="center" valign="middle"><input
																name="chkopcion16" type="checkbox"
																<?php if($row['t52_quest_16']=='1'){echo('checked="checked"');} ?>
																id="chkopcion16" value="1" /></td>
															<td valign="middle"><textarea name="txtquest16" cols="60"
																	rows="2" id="txtquest16"><?php echo($row['t52_obs_16']);?></textarea></td>
														</tr>
														<tr>
															<td colspan="3" valign="top"><p>
																	<strong>Activos Fijos</strong>
																</p></td>
														</tr>
														<tr>
															<td><ul>
																	<li>La institución cuenta registro de los Activos
																		Fijos adquiridos con fondos del proyecto.</li>
																</ul></td>
															<td align="center" valign="middle"><input
																name="chkopcion17" type="checkbox"
																<?php if($row['t52_quest_17']=='1'){echo('checked="checked"');} ?>
																id="chkopcion17" value="1" /></td>
															<td valign="middle"><textarea name="txtquest17" cols="60"
																	rows="2" id="txtquest17"><?php echo($row['t52_obs_17']);?></textarea></td>
														</tr>
														<tr>
															<td height="26"><ul>
																	<li>Los Activos fijos adquiridos están siendo usados
																		para los fines del proyecto.</li>
																</ul></td>
															<td align="center" valign="middle"><input
																name="chkopcion18" type="checkbox"
																<?php if($row['t52_quest_18']=='1'){echo('checked="checked"');} ?>
																id="chkopcion18" value="1" /></td>
															<td valign="middle"><textarea name="txtquest18" cols="60"
																	rows="2" id="txtquest18"><?php echo($row['t52_obs_18']);?></textarea></td>
														</tr>
														<tr>
															<td colspan="3"><strong>III. Fondo de Crédito</strong></td>
														</tr>
														<tr>
															<td><ul>
																	<li>&iquest;La institución ha tenido alguna
																		experiencia en manejo de fondos de crédito?</li>
																</ul></td>
															<td align="center" valign="middle"><input
																name="chkopcion19" type="checkbox"
																<?php if($row['t52_quest_19']=='1'){echo('checked="checked"');} ?>
																id="chkopcion19" value="1" /></td>
															<td valign="middle"><textarea name="txtquest19" cols="60"
																	rows="2" id="txtquest19"><?php echo($row['t52_obs_19']);?></textarea></td>
														</tr>
														<tr>
															<td height="26"><ul>
																	<li>La Cuenta con un Manual de Manejo de créditos.</li>
																</ul></td>
															<td align="center" valign="middle"><input
																name="chkopcion20" type="checkbox"
																<?php if($row['t52_quest_20']=='1'){echo('checked="checked"');} ?>
																id="chkopcion20" value="1" /></td>
															<td valign="middle"><textarea name="txtquest20" cols="60"
																	rows="2" id="txtquest20"><?php echo($row['t52_obs_20']);?></textarea></td>
														</tr>
														<tr>
															<td><ul>
																	<li>El manejo del crédito, tanto de los gastos e
																		ingresos por recuperaciones están siendo&nbsp;
																		manejados en una cuenta bancaria separada y exclusiva
																		para este fin.</li>
																</ul></td>
															<td align="center" valign="middle"><input
																name="chkopcion21" type="checkbox"
																<?php if($row['t52_quest_21']=='1'){echo('checked="checked"');} ?>
																id="chkopcion21" value="1" /></td>
															<td valign="middle"><textarea name="txtquest21" cols="60"
																	rows="2" id="txtquest21"><?php echo($row['t52_obs_21']);?></textarea></td>
														</tr>
														<tr>
															<td height="26"><ul>
																	<li>Cuenta con una lista de beneficiarios que apliquen
																		el crédito.</li>
																</ul></td>
															<td align="center" valign="middle"><input
																name="chkopcion22" type="checkbox"
																<?php if($row['t52_quest_22']=='1'){echo('checked="checked"');} ?>
																id="chkopcion22" value="1" /></td>
															<td valign="middle"><textarea name="txtquest22" cols="60"
																	rows="2" id="txtquest22"><?php echo($row['t52_obs_22']);?></textarea></td>
														</tr>
														<tr>
															<td valign="top"><ul>
																	<li>Los créditos que otorgue la Institución a los
																		beneficiarios del proyecto, están siendo reportados a
																		FONDOEMPLEO dentro de los informes mensuales,&nbsp;
																		tal como se establece en el Anexo D del Convenio,</li>
																</ul></td>
															<td align="center" valign="middle"><input
																name="chkopcion23" type="checkbox"
																<?php if($row['t52_quest_23']=='1'){echo('checked="checked"');} ?>
																id="chkopcion23" value="1" /></td>
															<td valign="middle"><textarea name="txtquest23" cols="60"
																	rows="2" id="txtquest23"><?php echo($row['t52_obs_23']);?></textarea></td>
														</tr>
														<tr>
															<td valign="top">&nbsp;</td>
															<td align="center" valign="middle">&nbsp;</td>
															<td valign="middle">&nbsp;</td>
														</tr>
														<tr>
															<td valign="top">&nbsp;</td>
															<td align="center" valign="middle">&nbsp;</td>
															<td valign="middle">&nbsp;</td>
														</tr>
													</tbody>
												</table>
											</div>
										</fieldset>

									</td>
								</tr>
							</table>
							<p>&nbsp;</p>
						</div>
					</div>
					<div class="TabbedPanelsContent">
						<div id="divConclusiones">
							<table width="750" border="0" cellspacing="1" cellpadding="0">
								<tr>
									<td width="82%" class="TableEditReg">&nbsp;</td>
									<td width="8%" rowspan="2" align="center">&nbsp;</td>
									<td width="10%" rowspan="2" align="right" valign="middle">
										<!--div style="text-align:center; color:navy; font-weight:bold; font-size:10px; cursor:pointer; width:50px;" title="Guardar Problemas y Soluciones"  onclick="Guardar_InformeCab(); return false;" > <img src="../../../img/aplicar.png" alt="" width="22" height="22" /><br />
                  Guardar </div osktgui--> <input type="button"
										value="Guardar" class="btn_save_custom"
										title="Guardar Problemas y Soluciones"
										onclick="Guardar_InformeCab(); return false;" />
									</td>
								</tr>
								<tr>
									<td><span style="font-weight: bold;">Conclusiones y
											Calificación</span></td>
								</tr>
							</table>
							<table width="750" cellpadding="0" cellspacing="0"
								class="TableEditReg">
								<thead>
								</thead>
								<tbody class="data" bgcolor="#FFFFFF">
									<tr>
										<td align="left" valign="middle"><span
											style="font-weight: bold; font-size: 12px;">Conclusiones</span>
											<br /> <textarea name="t52_conclu" rows="15" id="t52_conclu"
												style="padding: 0px; width: 100%;"><?php echo($row['t52_conclu']);?></textarea></td>
									</tr>
									<tr>
										<td align="left" valign="middle"><span
											style="font-weight: bold; font-size: 12px;">Calificación</span><br /></td>
									</tr>
									<tr>
										<td align="left" valign="middle">Efectuada la revisión de los
											reportes recibidos, se califica la información presentada
											por <br /> <select name="t52_califica" id="t52_califica"
											style="width: 160px;" class="Conclusiones"
											onchange="CalcularResultado();">
												<option value="" selected='selected' title='3'></option>
                      <?php
    $objTablas = new BLTablasAux();
    $rs = $objTablas->ValoraInformesME();
    $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $row['t52_califica'], 'cod_ext');
    ?>
                    </select>

										</td>
									</tr>
									<tr>
										<td align="left" valign="middle"><span
											style="font-weight: bold; color: #FF0000;"
											id="mensaje_califica"></span></td>
									</tr>
								</tbody>
								<tfoot>
								</tfoot>
							</table>
						</div>
					</div>
					<div class="TabbedPanelsContent">
						<div id="divAnexos"></div>
					</div>
				</div>
			</div>
	<?php }else{ ?>
			<br />
			<span
				style="font-weight: bold; background-color: #EEEEEE; margin-right: 15px; font-size: 12px; float: right; color: #999999; padding: 5px 10px; border: solid 1px #999999;">Guardar
				la Caratula para continuar con el llenado de datos</span>
	<?php } ?>
	
    <p>&nbsp;</p>
		</div>

		<script>
function CalcularResultado()
{
	var eva1 = $("#t52_califica option[value='"+$("#t52_califica").val()+"']").attr("title");

	var resultado =parseInt(eva1) ;
	
	if(resultado==2) {
	
	$("#mensaje_califica").html("El monitor considera que el proyecto se encuentra ejecut&#225;ndose adecuadamente y se prevé que cumpla sus metas en el periodo establecido");
	
	}
	if(resultado !=2) {$("#mensaje_califica").html("");}
	
}
CalcularResultado();

function HabilitarOpcion1(id)
{
   if($('#'+id).attr("checked"))
   {
	   $('#chkopcion2').removeAttr("disabled");
	   $('#chkopcion3').removeAttr("disabled");
	   $('#chkopcion4').removeAttr("disabled");
	   $('#txtquest2').removeAttr("disabled");
	   $('#txtquest3').removeAttr("disabled");
	   $('#txtquest4').removeAttr("disabled");
	   
	}
   else
   {
		$('#chkopcion2').attr("disabled", "disabled");
		$('#chkopcion3').attr("disabled", "disabled");
		$('#chkopcion4').attr("disabled", "disabled");
		$('#txtquest2').attr("disabled", "disabled");
		$('#txtquest3').attr("disabled", "disabled");
		$('#txtquest4').attr("disabled", "disabled");
   }
}


function MostrarCuestionario(valor)
{
	var titulo = $("#cbocuestionario option:selected").attr("title");
	$("#lbltitleCuestionario").html(titulo);
	
	if(valor=='1')   
	{
		$('#divCuestionarioInicial').css("display","block"); 
		$('#divCuestionarioEjecucion').css("display","none"); 
		$('#tbPeriodo').css("display","none"); 
		return;
	}
	if(valor=='2')   
	{
		$('#divCuestionarioInicial').css("display","none");
		$('#divCuestionarioEjecucion').css("display","block"); 
		$('#tbPeriodo').css("display","block"); 
		return;
	}
	else
	{
		$('#divCuestionarioInicial').css("display","none");
		$('#divCuestionarioEjecucion').css("display","none"); 
		$('#tbPeriodo').css("display","none"); 
	}
}



function AvancePresup()
{
var ls_urlRpt = "<?php echo(constant("PATH_SME")."reportes/");?>rpt_avc_financ_presup.php";
var ls_urlPar = "&idProy=<?php echo($idProy)?>&idFuente=10&ini=" + $("#cboper_ini").val() + "&ter=" + $("#cboper_fin").val() ; 
var ls_urlVie = "<?php echo(constant("PATH_RPT"));?>reportviewer.php?link=" + ls_urlRpt + "&title=Avance Presupuestal - Periodo ("+ $("#cboper_ini").val()+ " - " + $("#cboper_fin").val() + ")" + ls_urlPar;
window.open(ls_urlVie,"AvancePresup","",true);
}

function AvanceTec()
{
var ls_urlRpt = "<?php echo(constant("PATH_SME")."reportes/");?>rpt_avc_financ_fisico.php";
var ls_urlPar = "&idProy=<?php echo($idProy)?>&ini=" + $("#cboper_ini").val() + "&ter=" + $("#cboper_fin").val() ; 
var ls_urlVie = "<?php echo(constant("PATH_RPT"));?>reportviewer.php?link=" + ls_urlRpt + "&title=Avance Fisico - Periodo ("+ $("#cboper_ini").val()+ " - " + $("#cboper_fin").val() + ")" + ls_urlPar;
window.open(ls_urlVie,"AvancePresup","",true);
}

function Guardar_InformeCab()
{
<?php $ObjSession->AuthorizedPage(); ?>	

	var valor = $("#cbocuestionario").val();
	var est = $('#cboestado').val();
	var ini = $('#cboper_ini').val();
	var fin = $('#cboper_fin').val();
	
	if (est=="" || est==null) {alert("Seleccione Estado del Informe"); $('#cboestado').focus(); return false;}
	if (valor == "2") {
		if (ini=="" || ini==null) {alert("Seleccione Periodo Inicial del Informe"); $('#cboper_ini').focus(); return false;}
		if (fin=="" || fin==null) {alert("Seleccione Periodo Final del Informe"); $('#cboper_fin').focus(); return false;}
		if (parseInt(ini) >= parseInt(fin)) {alert("Periodo inicial no puede ser mayor que el periodo final"); $("#cboper_ini").focus(); return false;}
	}
	
	if(valor == "") {alert("Seleccione Tipo de Visita!!"); return;}
	
	var htmlCuestionario = "";
	
	if(valor=='1')  { htmlCuestionario = $('#divCuestionarioEjecucion').html();  $('#divCuestionarioEjecucion').html(""); }
	if(valor=='2')  { htmlCuestionario = $('#divCuestionarioInicial').html();	 $('#divCuestionarioInicial').html(""); }
		
	var BodyForm = $("#FormData").serialize() ; //arrParams.join("&");
	if(valor=='1') {$('#divCuestionarioEjecucion').html(htmlCuestionario);}
	if(valor=='2') {$('#divCuestionarioInicial').html(htmlCuestionario);}
	
	var sURL = "inf_visita_financ_process.php?action=<?php echo($action);?>";
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
	alert(respuesta.replace(num,""));
	num = num.replace(ret,"");
	btnEditar_Clic(num);
  }
  else
  {alert(respuesta);}  
  
}


function onErrorLoad			(req)
{
	alert("Ocurrio un error al cargar los datos");
}
function LoadAvancePresupuestal		()
{
	var comp  = $('#cbocomponente_fe').val();
	var idNum = "<?php echo($idInforme)?>";
	var BodyForm = "action=<?php echo(md5("lista_presupuesto"));?>&idProy=<?php echo($idProy);?>&idComp="+ comp  + "&idNum="+idNum ;
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


function LoadAnexos(recargar)
{
	if($('#divAnexos').html()!="")
	{
		if(!recargar){return false;}
	}
	var idInf = "<?php echo($idInforme);?>";
	
	var BodyForm = "action=<?php echo(md5("lista_anexos"));?>&idProy=<?php echo($idProy);?>&idNum="+idInf;
	var sURL = "inf_visita_financ_anx.php";
	$('#divAnexos').html("<p align='center'><img src='../../../img/indicator.gif' width='16' height='16' /><br>Cargando..<br></p>");
	var req = Spry.Utils.loadURL("POST", sURL, true, SuccessAnexos, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
}
function SuccessAnexos(req)
{
   var respuesta = req.xhRequest.responseText;
   $("#divAnexos").html(respuesta);
   return;
}


	<?php if(md5("ajax_new")==$action) { ?>
		$("#cboestado").val("<?php echo($HardCode->EstInf_ElaF);?>");
	<?php } ?>
	
	$("#cboestado option").attr('disabled','disabled');
	$("#cboestado option:selected").removeAttr('disabled');
	
	var estado = $("#cboestado").val();
	var Elaboracion= "<?php echo($HardCode->EstInf_ElaF);?>";
	var AprobadoCMF = "<?php echo($HardCode->EstInf_AprobF);?>";
	var Correccion = "<?php echo($HardCode->EstInf_CorrF);?>";
	var Revision   = "<?php echo($HardCode->EstInf_RevF);?>";
		
	<?php if($ObjSession->PerfilID == $HardCode->CMF )  { ?>
	
		if(estado==Revision) 
		 {
		
			  $('#cboestado option[value="'+Correccion+'"]').removeAttr('disabled');
			  $('#cboestado option[value="'+AprobadoCMF+'"]').removeAttr('disabled');
		 }
	<?php } ?>
	
	<?php if($ObjSession->PerfilID == $HardCode->MF || $ObjSession->PerfilID == $HardCode->Admin) { ?>
		if(estado==Elaboracion || estado==Correccion) 
		  {
			  $('#cboestado option[value="'+Revision+'"]').removeAttr('disabled');
		  }
	<?php } ?>
	
	

MostrarCuestionario( $("#cbocuestionario").val() ) ;
if($("#cbocuestionario").val()=='1') { HabilitarOpcion1('chkopcion1') ; }
 $("#t52_fch_pre").datepicker(); 
</script>


  
<?php if($objFunc->__QueryString()=="") { ?>
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