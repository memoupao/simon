<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>

<?php
// require(constant("PATH_CLASS")."BLInformes.class.php");
require_once (constant("PATH_CLASS") . "BLMonitoreoFinanciero.class.php");

// $objInf = new BLInformes();
$objInf = new BLMonitoreoFinanciero();

// error_reporting("E_PARSE");
$idProy = $objFunc->__Request('idProy');
$idInforme = $objFunc->__Request('idnum');

$action = $objFunc->__GET('mode');

if (md5("ajax_new") == $action) {
    $objFunc->SetSubTitle('Informe Financiero - Nuevo Registro');
    $row = 0;
    // $idInforme = 0;
}
if (md5("ajax_edit") == $action) {
    $objFunc->SetSubTitle('Informe Financiero - Editar Registro');
    $row = $objInf->Inf_MF_Seleccionar($idProy, $idInforme);
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
					<td width="9%"><button class="Button"
							onclick="Guardar_InformeCab(); return false;" value="Guardar">Guardar
						</button></td>
					<td width="25%"><button class="Button"
							onclick="btnCancelar_Clic(); return false;" value="Cancelar">
							Cerrar y Volver</button></td>
					<td width="9%" nowrap="nowrap">&nbsp;</td>
					<td width="2%">&nbsp;</td>
					<td width="2%">&nbsp;</td>
					<td width="2%">&nbsp;</td>
					<td width="47%" align="right"><?php echo($objFunc->SubTitle) ;?></td>
				</tr>
			</table>
		</div>
		<div style="width: 740px;">
			<div id="divCabeceraInforme">
				<table width="700" border="0" cellspacing="0" cellpadding="0"
					class="TableEditReg" align="left">
					<tr>
						<td colspan="4"><strong>1. Caratula</strong></td>
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
						<td align="center" nowrap="nowrap">&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td colspan="4" align="right"><a href="#">Ver Cuadro Avance
								Presupuestal </a> &nbsp;&nbsp;&nbsp;&nbsp; <a href="#">Ver
								Cuadro Avance Técnico</a></td>
					</tr>
					<tr>
						<td colspan="4" align="right">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="4" align="right">&nbsp;</td>
					</tr>
				</table>

				<div style="width: 740px;">
					<div id="ssTabInforme" class="TabbedPanels">
						<ul class="TabbedPanelsTabGroup">
							<li class="TabbedPanelsTab" tabindex="0">Cuestionario</li>
							<li class="TabbedPanelsTab" tabindex="0">Conclusiones</li>
							<li class="TabbedPanelsTab" tabindex="0">Anexos</li>
						</ul>

						<div class="TabbedPanelsContentGroup">
							<div class="TabbedPanelsContent">
								<div>
									<table width="650" height="177" border="0" align="left"
										cellpadding="0" cellspacing="0">
										<tr>
											<td height="59">Tipo Cuestionario</td>
											<td><select name="cbocuestionario" id="cbocuestionario"
												style="width: 400px; font-size: 11px;"
												onchange="MostrarCuestionario(this.value);">
													<option value="" selected="selected"></option>
													<option value="1" style="font-size: 10px; color: #00C">Cuestionario
														- Visita de Supervisión Previa al Inicio del Proyecto:</option>
													<option value="2" style="font-size: 10px; color: #F00;">Cuestionario
														- Visita de Supervisión en la Ejecución del Proyecto:</option>
											</select></td>
										</tr>
										<tr>
											<td height="59" colspan="2">
												<fieldset>
													<legend>Persona Entrevistada</legend>
													<table border="0" cellspacing="0" cellpadding="0"
														width="700" style="padding: 1px;" class="TableEditReg">
														<tr>
															<td width="174" height="30" valign="top"><strong>Nombre:</strong></td>
															<td width="483" valign="top"><input name="txtpersona"
																type="text" class="Cabecera" id="txtpersona"
																value="<?php echo($row['fecpre'])?>" size="100"
																maxlength="100" /></td>
														</tr>
														<tr>
															<td width="174" height="25" valign="top"><strong>Cargo:</strong></td>
															<td width="483" valign="top"><input name="txtpersona2"
																type="text" class="Cabecera" id="txtpersona2"
																value="<?php echo($row['fecpre'])?>" size="50"
																maxlength="50" /></td>
														</tr>
													</table>
												</fieldset>
											</td>
										</tr>
										<tr>
											<td height="59" colspan="2"><fieldset>
													<legend>Cuestionario</legend>
													<div id="divCuestionarioInicial" class="TableGrid"
														style="display: none;">
														<table width="700" border="0" cellpadding="0"
															cellspacing="0" class="TableEditReg">
															<tbody style="border: solid 1px #666;" class="data";>
																<tr style="border: solid 1px, #666;">
																	<td width="39%" height="21" valign="top"
																		style="background-color: #E9E9E9;"><strong><em>DETALLE</em></strong></td>
																	<td width="8%" valign="top"
																		style="background-color: #E9E9E9;"><strong><em>SI /NO</em></strong></td>
																	<td width="52%" valign="top"
																		style="background-color: #E9E9E9;"><strong><em>COMENTARIOS</em></strong></td>
																</tr>
																<tr>
																	<td height="21" colspan="3" valign="top"><em><strong>Información
																				Contable Presupuestal</strong></em></td>
																</tr>
																<tr>
																	<td width="39%" height="35" valign="top"><p>
																			La Institución cuenta con un <br /> programa de
																			contabilidad adecuado?
																		</p></td>
																	<td width="8%" align="center" valign="middle"><label> <input
																			name="chkopcion1" type="checkbox" id="chkopcion1"
																			onclick="HabilitarOpcion1(this.id);" value="1" />
																	</label></td>
																	<td width="52%" valign="middle"><label> <textarea
																				name="txtquest1" id="txtquest1" cols="60" rows="2"></textarea>
																	</label></td>
																</tr>
																<tr>
																	<td width="39%"><em
																		style="padding-left: 30px; display: block;">a) La
																			contabilidad institucional&nbsp;es llevada con el
																			sistema contable referido?</em></td>
																	<td width="8%" align="center" valign="middle"><input
																		name="chkopcion1_1" type="checkbox" id="chkopcion1_1"
																		value="1" disabled="disabled" /></td>
																	<td width="52%" valign="middle"><textarea
																			name="txtquest1_1" cols="60" rows="2"
																			disabled="disabled" id="txtquest1_1"></textarea></td>
																</tr>
																<tr>
																	<td height="26"><em
																		style="padding-left: 30px; display: block;">b) El
																			programa de contabilidad&nbsp; permite manejar la
																			ejecución presupuestal por proyectos de acuerdo a lo
																			requerido por FE?</em></td>
																	<td align="center" valign="middle"><input
																		name="chkopcion1_2" type="checkbox" id="chkopcion1_2"
																		value="1" disabled="disabled" /></td>
																	<td valign="middle"><textarea name="txtquest1_2"
																			cols="60" rows="2" disabled="disabled"
																			id="txtquest1_2"></textarea></td>
																</tr>
																<tr>
																	<td height="25"><em
																		style="padding-left: 30px; display: block;">c) La
																			contabilidad institucional se encuentra al día?<br />
																	</em></td>
																	<td align="center" valign="middle"><input
																		name="chkopcion1_3" type="checkbox" id="chkopcion1_3"
																		value="1" disabled="disabled" /></td>
																	<td valign="middle"><textarea name="txtquest1_3"
																			cols="60" rows="2" disabled="disabled"
																			id="txtquest1_3"></textarea></td>
																</tr>
																<tr>
																	<td width="39%" height="35" valign="top">Los registros
																		contables de las operaciones institucionales y
																		de&nbsp; los proyectos se realizan de acuerdo a las
																		normas y principios contables?</td>
																	<td width="8%" align="center" valign="middle"><input
																		name="chkopcion2" type="checkbox" id="chkopcion2"
																		value="1" /></td>
																	<td width="52%" valign="middle"><textarea
																			name="txtquest2" id="txtquest2" cols="60" rows="2"></textarea></td>
																</tr>
																<tr>
																	<td width="39%" valign="top">La Institución ha
																		implementado las cuentas contables y/o anexos&nbsp;
																		dentro del sistema contable institucional, que permita
																		diferenciar las operaciones del proyecto de las
																		operaciones ajenas ( de otros proyectos o&nbsp;
																		propios de LA INSTITUCION),</td>
																	<td width="8%" align="center" valign="middle"><input
																		name="chkopcion3" type="checkbox" id="chkopcion3"
																		value="1" /></td>
																	<td width="52%" valign="middle"><textarea
																			name="txtquest3" id="txtquest3" cols="60" rows="2"></textarea></td>
																</tr>
																<tr>
																	<td width="39%" valign="top">Los análisis de cuentas
																		que arroje el sistema contable sirven de base para los
																		Anexos solicitados por FONDOEMPLEO</td>
																	<td width="8%" align="center" valign="middle"><input
																		name="chkopcion4" type="checkbox" id="chkopcion4"
																		value="1" /></td>
																	<td width="52%" valign="middle"><textarea
																			name="txtquest4" id="txtquest4" cols="60" rows="2"></textarea></td>
																</tr>
															</tbody>
														</table>
													</div>
													<div id="divCuestionarioEjecucion" class="TableGrid"
														style="display: none;">
														<table width="700" border="0" cellpadding="0"
															cellspacing="0" class="TableEditReg">
															<tbody style="border: solid 1px #666;" class="data";>
																<tr style="border: solid 1px, #666;">
																	<td width="39%" height="21" valign="top"
																		style="background-color: #E9E9E9;"><strong><em>DETALLE</em></strong></td>
																	<td width="8%" valign="top"
																		style="background-color: #E9E9E9;"><strong><em>SI /NO</em></strong></td>
																	<td width="52%" valign="top"
																		style="background-color: #E9E9E9;"><strong><em>COMENTARIOS</em></strong></td>
																</tr>
																<tr>
																	<td height="21" colspan="3" valign="top"><strong>I.
																			Información Administrativa </strong></td>
																</tr>
																<tr>
																	<td width="39%" height="35" valign="top"><p>&iquest;Se
																			cuenta con la relación de todo el personal técnico
																			y administrativo de la Institución?</p></td>
																	<td width="8%" align="center" valign="middle"><label> <input
																			name="chkopcion1" type="checkbox" id="chkopcion1"
																			onclick="HabilitarOpcion1(this.id);" value="1" />
																	</label></td>
																	<td width="52%" valign="middle"><label> <textarea
																				name="txtquest1" id="txtquest1" cols="60" rows="2"></textarea>
																	</label></td>
																</tr>
																<tr>
																	<td>&iquest;Cuenta un registro adecuado de control de
																		personal?</td>
																	<td align="center" valign="middle"><label> <input
																			name="chkopcion1" type="checkbox" id="chkopcion1"
																			onclick="HabilitarOpcion1(this.id);" value="1" />
																	</label></td>
																	<td valign="middle"><label> <textarea name="txtquest1"
																				id="txtquest1" cols="60" rows="2"></textarea>
																	</label></td>
																</tr>
																<tr>
																	<td>&iquest;La institución cumple con los
																		procedimientos Administrativos según su manual
																		enviado a FE?</td>
																	<td align="center" valign="middle"><label> <input
																			name="chkopcion1" type="checkbox" id="chkopcion1"
																			onclick="HabilitarOpcion1(this.id);" value="1" />
																	</label></td>
																	<td valign="middle"><label> <textarea name="txtquest1"
																				id="txtquest1" cols="60" rows="2"></textarea>
																	</label></td>
																</tr>
																<tr>
																	<td colspan="3"><strong>II. Información contable
																			presupuestal y Control Interno</strong></td>
																</tr>
																<tr>
																	<td width="39%"><em
																		style="padding-left: 30px; display: block;">a) La
																			contabilidad institucional&nbsp;es llevada con el
																			sistema contable referido?</em></td>
																	<td width="8%" align="center" valign="middle"><input
																		name="chkopcion1_1" type="checkbox" id="chkopcion1_1"
																		value="1" /></td>
																	<td width="52%" valign="middle"><textarea
																			name="txtquest1_1" cols="60" rows="2"
																			id="txtquest1_1"></textarea></td>
																</tr>
																<tr>
																	<td height="26"><em
																		style="padding-left: 30px; display: block;">b) El
																			programa de contabilidad&nbsp; permite manejar la
																			ejecución presupuestal por proyectos de acuerdo a lo
																			requerido por FE?</em></td>
																	<td align="center" valign="middle"><input
																		name="chkopcion1_2" type="checkbox" id="chkopcion1_2"
																		value="1" /></td>
																	<td valign="middle"><textarea name="txtquest1_2"
																			cols="60" rows="2" id="txtquest1_2"></textarea></td>
																</tr>
																<tr>
																	<td height="25"><em
																		style="padding-left: 30px; display: block;">c) La
																			contabilidad institucional se encuentra al día?<br />
																	</em></td>
																	<td align="center" valign="middle"><input
																		name="chkopcion1_3" type="checkbox" id="chkopcion1_3"
																		value="1" /></td>
																	<td valign="middle"><textarea name="txtquest1_3"
																			cols="60" rows="2" id="txtquest1_3"></textarea></td>
																</tr>
																<tr>
																	<td width="39%" height="35" valign="top">Los registros
																		contables de las operaciones institucionales y
																		de&nbsp; los proyectos se realizan de acuerdo a las
																		normas y principios contables?</td>
																	<td width="8%" align="center" valign="middle"><input
																		name="chkopcion2" type="checkbox" id="chkopcion2"
																		value="1" /></td>
																	<td width="52%" valign="middle"><textarea
																			name="txtquest2" id="txtquest2" cols="60" rows="2"></textarea></td>
																</tr>
																<tr>
																	<td width="39%" valign="top">La Institución ha
																		implementado las cuentas contables y/o anexos&nbsp;
																		dentro del sistema contable institucional, que permita
																		diferenciar las operaciones del proyecto de las
																		operaciones ajenas ( de otros proyectos o&nbsp;
																		propios de LA INSTITUCION),</td>
																	<td width="8%" align="center" valign="middle"><input
																		name="chkopcion3" type="checkbox" id="chkopcion3"
																		value="1" /></td>
																	<td width="52%" valign="middle"><textarea
																			name="txtquest3" id="txtquest3" cols="60" rows="2"></textarea></td>
																</tr>
																<tr>
																	<td width="39%" valign="top">Los análisis de cuentas
																		que arroje el sistema contable sirven de base para los
																		Anexos solicitados por FONDOEMPLEO</td>
																	<td width="8%" align="center" valign="middle"><input
																		name="chkopcion4" type="checkbox" id="chkopcion4"
																		value="1" /></td>
																	<td width="52%" valign="middle"><textarea
																			name="txtquest4" id="txtquest4" cols="60" rows="2"></textarea></td>
																</tr>
																<tr>
																	<td valign="top">Se han presentado los Informes
																		Financieros de Ejecución Presupuestal en las fechas
																		establecidas</td>
																	<td align="center" valign="middle"><input
																		name="chkopcion4" type="checkbox" id="chkopcion4"
																		value="1" /></td>
																	<td valign="middle"><textarea name="txtquest4"
																			id="txtquest4" cols="60" rows="2"></textarea></td>
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
																		name="chkopcion1_4" type="checkbox" id="chkopcion1_6"
																		value="1" disabled="disabled" /></td>
																	<td valign="middle"><textarea name="txtquest1_4"
																			cols="60" rows="2" disabled="disabled"
																			id="txtquest1_6"></textarea></td>
																</tr>
																<tr>
																	<td height="26"><ul>
																			<li>Las adquisiciones mayores a S/. 7,000.00&nbsp;
																				cuentan con la aprobación previa de FONDOEMPLEO.</li>
																		</ul></td>
																	<td align="center" valign="middle"><input
																		name="chkopcion1_4" type="checkbox" id="chkopcion1_5"
																		value="1" disabled="disabled" /></td>
																	<td valign="middle"><textarea name="txtquest1_4"
																			cols="60" rows="2" disabled="disabled"
																			id="txtquest1_5"></textarea></td>
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
																		name="chkopcion1_5" type="checkbox" id="chkopcion1_7"
																		value="1" disabled="disabled" /></td>
																	<td valign="middle"><textarea name="txtquest1_5"
																			cols="60" rows="2" disabled="disabled"
																			id="txtquest1_7"></textarea></td>
																</tr>
																<tr>
																	<td height="26"><ul>
																			<li>Se han realizado operaciones de transferencias a
																				otras cuentas no relacionadas con el proyecto.</li>
																		</ul></td>
																	<td align="center" valign="middle"><input
																		name="chkopcion1_5" type="checkbox" id="chkopcion1_4"
																		value="1" disabled="disabled" /></td>
																	<td valign="middle"><textarea name="txtquest1_5"
																			cols="60" rows="2" disabled="disabled"
																			id="txtquest1_4"></textarea></td>
																</tr>
																<tr>
																	<td valign="top"><ul>
																			<li>La institución ha realizado arqueos al Fondos
																				fijo destinado al proyecto</li>
																		</ul></td>
																	<td align="center" valign="middle"><input
																		name="chkopcion1_5" type="checkbox" id="chkopcion1_4"
																		value="1" disabled="disabled" /></td>
																	<td valign="middle"><textarea name="txtquest1_5"
																			cols="60" rows="2" disabled="disabled"
																			id="txtquest1_4"></textarea></td>
																</tr>
																<tr>
																	<td valign="top"><ul>
																			<li>Las entregas a rendir están siendo liquidadas en
																				los plazos establecidos (tres días máximo de
																				efectuado el gasto).</li>
																		</ul></td>
																	<td align="center" valign="middle"><input
																		name="chkopcion1_5" type="checkbox" id="chkopcion1_4"
																		value="1" disabled="disabled" /></td>
																	<td valign="middle"><textarea name="txtquest1_5"
																			cols="60" rows="2" disabled="disabled"
																			id="txtquest1_4"></textarea></td>
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
																		name="chkopcion1_6" type="checkbox" id="chkopcion1_9"
																		value="1" disabled="disabled" /></td>
																	<td valign="middle"><textarea name="txtquest1_6"
																			cols="60" rows="2" disabled="disabled"
																			id="txtquest1_9"></textarea></td>
																</tr>
																<tr>
																	<td height="26"><ul>
																			<li>Los Activos fijos adquiridos están siendo usados
																				para los fines del proyecto.</li>
																		</ul></td>
																	<td align="center" valign="middle"><input
																		name="chkopcion1_6" type="checkbox" id="chkopcion1_8"
																		value="1" disabled="disabled" /></td>
																	<td valign="middle"><textarea name="txtquest1_6"
																			cols="60" rows="2" disabled="disabled"
																			id="txtquest1_8"></textarea></td>
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
																		name="chkopcion1_7" type="checkbox" id="chkopcion1_11"
																		value="1" disabled="disabled" /></td>
																	<td valign="middle"><textarea name="txtquest1_7"
																			cols="60" rows="2" disabled="disabled"
																			id="txtquest1_11"></textarea></td>
																</tr>
																<tr>
																	<td height="26"><ul>
																			<li>La Cuenta con un Manual de Manejo de créditos.</li>
																		</ul></td>
																	<td align="center" valign="middle"><input
																		name="chkopcion1_7" type="checkbox" id="chkopcion1_10"
																		value="1" disabled="disabled" /></td>
																	<td valign="middle"><textarea name="txtquest1_7"
																			cols="60" rows="2" disabled="disabled"
																			id="txtquest1_10"></textarea></td>
																</tr>
																<tr>
																	<td><ul>
																			<li>El manejo del crédito, tanto de los gastos e
																				ingresos por recuperaciones están siendo&nbsp;
																				manejados en una cuenta bancaria separada y
																				exclusiva para este fin.</li>
																		</ul></td>
																	<td align="center" valign="middle"><input
																		name="chkopcion1_7" type="checkbox" id="chkopcion1_13"
																		value="1" disabled="disabled" /></td>
																	<td valign="middle"><textarea name="txtquest1_7"
																			cols="60" rows="2" disabled="disabled"
																			id="txtquest1_13"></textarea></td>
																</tr>
																<tr>
																	<td height="26"><ul>
																			<li>Cuenta con una lista de beneficiarios que
																				apliquen el crédito.</li>
																		</ul></td>
																	<td align="center" valign="middle"><input
																		name="chkopcion1_7" type="checkbox" id="chkopcion1_12"
																		value="1" disabled="disabled" /></td>
																	<td valign="middle"><textarea name="txtquest1_7"
																			cols="60" rows="2" disabled="disabled"
																			id="txtquest1_12"></textarea></td>
																</tr>
																<tr>
																	<td valign="top"><ul>
																			<li>Los créditos que otorgue la Institución a los
																				beneficiarios del proyecto, están siendo reportados
																				a FONDOEMPLEO dentro de los informes
																				mensuales,&nbsp; tal como se establece en el Anexo D
																				del Convenio,</li>
																		</ul></td>
																	<td align="center" valign="middle"><input
																		name="chkopcion1_7" type="checkbox" id="chkopcion1_12"
																		value="1" disabled="disabled" /></td>
																	<td valign="middle"><textarea name="txtquest1_7"
																			cols="60" rows="2" disabled="disabled"
																			id="txtquest1_12"></textarea></td>
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
												</fieldset></td>
										</tr>
									</table>
									<br /> <br />
								</div>
							</div>
							<div class="TabbedPanelsContent">Contenido 2</div>
							<div class="TabbedPanelsContent">Contenido 3</div>
						</div>
					</div>
				</div>

			</div>

			<hr />
			<br />

		</div>

		<br />

		<script language="javascript" type="text/javascript">
   function HabilitarOpcion1(id)
   {
	   if($('#'+id).attr("checked"))
	   {
		   $('#chkopcion1_1').removeAttr("disabled");
		   $('#chkopcion1_2').removeAttr("disabled");
		   $('#chkopcion1_3').removeAttr("disabled");
		   $('#txtquest1_1').removeAttr("disabled");
		   $('#txtquest1_2').removeAttr("disabled");
		   $('#txtquest1_3').removeAttr("disabled");
		   
		}
	   else
	   {
	   		$('#chkopcion1_1').attr("disabled", "disabled");
			$('#chkopcion1_2').attr("disabled", "disabled");
			$('#chkopcion1_3').attr("disabled", "disabled");
			$('#txtquest1_1').attr("disabled", "disabled");
			$('#txtquest1_2').attr("disabled", "disabled");
			$('#txtquest1_3').attr("disabled", "disabled");
	   }
   }
  
   function MostrarCuestionario(valor)
   {
		if(valor=='1')   
		{
			$('#divCuestionarioInicial').css("display","block"); 
			$('#divCuestionarioEjecucion').css("display","none"); 
			
		}
		else
		{
			$('#divCuestionarioInicial').css("display","none");
			$('#divCuestionarioEjecucion').css("display","block"); 
		}
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
   
   
   
    function Guardar_InformeCab		()
	{
	<?php $ObjSession->AuthorizedPage(); ?>	
	
	var ini = $('#cboper_ini').val();
	var fin = $('#cboper_fin').val();
	var est = $('#t51_estado').val();

	if(ini=="" || ini==null){alert("Seleccione Periodo Inicial del Informe"); return false;}
	if(fin=="" || fin==null){alert("Seleccione Periodo Final del Informe");  return false;}
	if(est=="" || est==null){alert("Seleccione Estado del Informe"); return false;}
	
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
		alert(respuesta.replace(num,""));
		num = num.replace(ret,"");
		btnEditar_Clic(num);
	  }
	  else
	  {alert(respuesta);}  
	  
	}
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
				
		var BodyForm = "action=<?php echo(md5("lista_Evaluacion"));?>&idProy=<?php echo($idProy);?>&idnum=<?php echo($idInforme);?>";
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
		
		var BodyForm = "action=<?php echo(md5("lista_observa"));?>&idProy=<?php echo($idProy);?>&idNum=<?php echo($idInforme);?>";
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
		
		//var anio  = $('#cboanio').val();
		//var mes   = $('#cbomes').val();
		
		var BodyForm = "action=<?php echo(md5("lista_conclusiones"));?>&idProy=<?php echo($idProy);?>&idNum=<?php echo($idInforme);?>";
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
	
	LoadEvaluacion(true);
	
  </script>
  <?php if($idProy=="") { ?>
</form>
	<script type="text/javascript">
<!--
var TabsInforme = new Spry.Widget.TabbedPanels("ssTabInforme");
//-->
</script>
	<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd -->
</html>
<?php } ?>