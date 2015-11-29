<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>
<?php

require_once (constant('PATH_CLASS') . "BLManejoProy.class.php");
require_once (constant('PATH_CLASS') . "BLProyecto.class.php");
require_once (constant('PATH_CLASS') . "BLPOA.class.php");
require_once (constant('PATH_CLASS') . "BLTablasAux.class.php");
require_once (constant('PATH_CLASS') . "HardCode.class.php");

$objMan = new BLManejoProy();
$objProy = new BLProyecto();
$HC = new HardCode();

$view = $objFunc->__GET('mode');
$idProy = $objFunc->__Request('idProy');
$idVersion = $objFunc->__Request('idVersion');
$idPer = 0;

$AnioPOA = $objMan->GetAnioPOA($idProy, $idVersion);

$aPoaDao = new BLPOA();
$aCurrentPoa = $aPoaDao->POA_Seleccionar($idProy, $AnioPOA);

$row = 0;
if ($view == md5("ajax_edit")) {
    $objFunc->SetSubTitle("Personal del Proyecto - Editando Registro");
    $idPer = $objFunc->__Request('idPer');
    $row = $objMan->Personal_Seleccionar($idProy, $idVersion, $idPer);
} else {
    $row = 0;
    $objFunc->SetSubTitle("Personal del Proyecto - Nuevo Registro");
}
$anio = $objFunc->__Request('anio');

$aFchIni = strtotime($objProy->FechaInicioProy($idProy, $idVersion));
$aFchTer = strtotime($objProy->FechaTerminoProy($idProy, $idVersion));
$NumAniosProy = ceil(($aFchTer - $aFchIni) / 31104000);

// $NumAniosProy = $objProy->NumeroAniosProy($idProy);
?>

<?php if($view=='') { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/tplAjaxForm.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<!-- InstanceBeginEditable name="doctitle" -->
<!-- InstanceEndEditable -->
<?php
    
$objFunc->verifyAjax();
    if (! $objFunc->Ajax) {
        ?>
<meta name="keywords" content="<?php echo($objFunc->Keywords); ?>" />
<meta name="description" content="<?php echo($objFunc->MetaTags); ?>" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo($objFunc->Title);?></title>
<link href="../../../css/template.css" rel="stylesheet" media="all" />
<SCRIPT src="../../../jquery.ui-1.5.2/jquery-1.2.6.js"
	type=text/javascript></SCRIPT>
<script src="../../../jquery.ui-1.5.2/ui/ui.datepicker.js"
	type="text/javascript"></script>
<link href="../../../jquery.ui-1.5.2/themes/ui.datepicker.css"
	rel="stylesheet" type="text/css" />

<!-- InstanceBeginEditable name="head" -->
<!-- InstanceEndEditable -->

<?php } ?>
</head>
<body class="oneColElsCtrHdr">
	<!-- Inicio de Container Page-->
	<div id="container">
		<div id="mainContent">
			<form id="FormData" method="post"
				enctype="application/x-www-form-urlencoded"
				action="<?php echo($_SERVER['PHP_SELF']);?>">
				<!-- InstanceBeginEditable name="TemplateEditDetails" -->

				<!-- InstanceEndEditable -->
				<div id="divContent">
					<!-- InstanceBeginEditable name="Contenidos" -->
 <?php } ?>
 <style>
#AdjuntarTDR {
	width: 100px;
	height: 20px;
	overflow: hidden;
	background-color: #FFF;
	position: relative;
}

#AdjuntarTDR label {
	overflow: hidden;
	position: absolute;
	font-weight: bold;
	color: #009;
	font-size: 11px;
	text-decoration: underline;
	z-index: 1;
	*z-index: 2;
	cursor: pointer;
}

#txtFileUploadTDR {
	position: absolute;
	cursor: pointer;
	-moz-opacity: 0;
	filter: alpha(opacity : 0);
	opacity: 0;
	z-index: 2;
	*z-index: 1;
	*height: 0;
}
</style>
					<div style="width: 99%; border: solid 1px #D3D3D3;">
						<div id="toolbar" style="height: 4px;" class="BackColor">
							<table border="0" cellpadding="0" cellspacing="0">
								<tr>
									<td width="9%"><button class="Button"
											onclick="Guardar_Personal(); return false;" value="Guardar">Guardar
										</button></td>
									<td width="9%"><button class="Button"
											onclick="spryPopupDialog01.displayPopupDialog(false); return false;"
											value="Cancelar">Cancelar</button></td>
									<td width="31%">&nbsp;</td>
									<td width="2%">&nbsp;</td>
									<td width="2%">&nbsp;</td>
									<td width="2%">&nbsp;</td>
									<td width="47%" align="right"><?php echo($objFunc->SubTitle) ;?></td>
								</tr>
							</table>
						</div>
						<table width="580" align="center" class="TableEditReg">
							<tr valign="bottom">
								<td width="90" height="25" valign="middle" nowrap>Cargo Personal</td>
								<td colspan="5" align="left" valign="middle"><input
									name="t03_id_per" type="text" id="t03_id_per"
									value="<?php echo($row["t03_id_per"]);?>" size="1"
									maxlength="5" readonly="readonly" style="text-align: center" />
									<input name="t03_nom_per" type="text" id="t03_nom_per"
									value="<?php echo($row["t03_nom_per"]);?>" size="70"></td>
							</tr>
							<tr valign="baseline">
								<td nowrap align="left">Unidad Medida</td>
								<td width="120" align="left"><select name="t03_per_um"
									id="t03_per_um" style="width: 120px;"
									onchange="CalcularPagoTotal();">
        <?php
        $objTablas = new BLTablasAux();
        $Rs = $objTablas->TipoRemuneracion();
        $objFunc->llenarCombo($Rs, "codigo", "descripcion", $row['t03_um'], 'cod_ext');
        $objTablas = NULL;
        ?>
        </select></td>
								<td width="114" align="left" nowrap="nowrap">Remuneración Bruta</td>
								<td width="134" align="left"><input name="t03_remu" type="text"
									id="t03_remu" class="roundDec"
									value="<?php echo($row["t03_remu"]);?>" size="20"
									maxlength="10" style="text-align: center"
									onkeyup="CalcularPagoTotal();" /></td>
								<td width="60" align="left">&nbsp;</td>
								<td width="98" align="left">&nbsp;</td>
							</tr>
							<tr valign="baseline">
								<td nowrap align="left">% Dedicación</td>
								<td align="left"><input name="t03_dedica" type="text"
									id="t03_dedica" value="<?php echo($row["t03_dedica"]);?>"
									size="15" maxlength="9" style="text-align: center"
									onkeyup="CalcularPagoTotal();" /></td>
								<td align="left" nowrap="nowrap">Permanencia Zona</td>
								<td align="left"><input name="t03_perma" type="text"
									id="t03_perma" class="roundDec"
									value="<?php echo($row["t03_perma"]);?>" size="20"
									maxlength="5" style="text-align: center" /></td>
								<td align="left">&nbsp;</td>
								<td align="left">&nbsp;</td>
							</tr>
							<tr valign="baseline">
								<td nowrap align="left" valign="top">TDR - Resumen</td>
								<td colspan="5" align="left">
									<table width="100%" border="0" cellspacing="0" cellpadding="0"
										style="padding: 0px;">
										<tr>
											<td width="64%" height="43"><textarea name="t03_tdr"
													cols="70" rows="2" id="t03_tdr"><?php echo($row["t03_tdr"]);?></textarea>

											</td>
											<td width="36%" align="center">
            <?php if($row['t03_tdr_file']!="") { ?>
			<div id="divShowTDR">
													<span
														style="cursor: pointer; font-weight: bold; color: #990; font-size: 11px; text-decoration: underline; float: left; margin-left: 10px;"
														onclick="ShowFileTDR();">Ver TDR</span> <br /> <br />
												</div>
            <?php } ?>
            <!--<span id="#AdjuntarTDR" style="cursor:pointer; font-weight:bold; color:#009; font-size:11px; text-decoration:underline; visibility:hidden" onclick="AdjuntarTDR();">Adjuntar TDR</span>-->
												<div id="AdjuntarTDR">
													<input name="txtFileUploadTDR" id="txtFileUploadTDR"
														type="file" onchange="AdjuntarTDR();" /> <label
														for="txtFileUploadTDR">Adjuntar_TDR</label>
												</div>


											</td>
										</tr>
									</table>


								</td>

							</tr>

							<tr valign="bottom"
								<?php if($view!= md5("ajax_edit")) { echo 'style="display:none;"'; }?>>
								<td height="17" colspan="4" align="center" valign="middle">
									<div class="TableGrid">
										<table width="538" border="0" cellpadding="0" cellspacing="0">
											<thead>
											</thead>
											<tbody class="data">
												<tr>
													<td width="52" height="15" align="center" valign="middle"
														style="font-size: 10px; background-color: #F3F3F3;">Meta
														Fisica Inicial</td>
													<td height="15" align="center" valign="middle"
														style="font-size: 10px; background-color: #F3F3F3;">Meta
														Proyectada del Año Anterior</td>
													<td height="15" align="center" valign="middle"
														style="font-size: 10px; background-color: #F3F3F3;">Meta
														Ejecutada Años Anteriores</td>
													<td height="15" align="center" valign="middle"
														style="font-size: 10px; background-color: #F3F3F3;">Meta
														Total por Ejecutar</td>
													<td height="15" align="center" valign="middle"
														style="font-size: 10px; background-color: #F3F3F3;">Total del Año <?php echo($idAnio);?></td>
													<td height="15" align="center" valign="middle"
														style="font-size: 10px; background-color: #F3F3F3;">Meta
														Proyectada Años Restantes</td>
													<td height="15" align="center" valign="middle"
														style="font-size: 10px; background-color: #F3F3F3;">Meta
														Reprogram</td>
													<td height="15" align="center" valign="middle"
														style="font-size: 10px; background-color: #F3F3F3;">Variac</td>
												</tr>

   		    <?php
        
        $iRs = $objMan->Man_ListadoPersonal($idProy, $idVersion, $idPer, $AnioPOA);
        $RowIndex = 0;
        while ($row = mysqli_fetch_assoc($iRs)) {
            
            ?>

					<tr>
													<td align="center" valign="middle" nowrap="nowrap"><input
														name="txtmfi" type="text" class="subactividad" id="txtmfi"
														style="text-align: center; border: none;"
														value="<?php echo(number_format($row['mfi']));?>"
														valor="<?php echo($row['mfi']);?>" size="4" maxlength="5"
														readonly="readonly" /></td>
													<td width="80" align="center" valign="middle"><input
														name="txtmpaa" type="text" class="subactividad"
														id="txtmpaa" style="text-align: center; border: none;"
														value="<?php echo(number_format($row['mpaa']));?>"
														valor="<?php echo($row['mpaa']);?>" size="4" maxlength="5"
														readonly="readonly" /></td>
													<td width="82" align="center" valign="middle"><input
														name="txtmeaa" type="text" class="subactividad"
														id="txtmeaa" style="text-align: center; border: none;"
														value="<?php echo(number_format($row['meaa']));?>"
														valor="<?php echo($row['meaa']);?>" size="4" maxlength="5"
														readonly="readonly" /></td>
													<td width="67" align="center" valign="middle"><input
														name="txtmtpe" type="text" class="subactividad"
														id="txtmtpe" style="text-align: center; border: none;"
														value=" <?php echo(number_format($row['mtpe']));?>"
														size="4" maxlength="5" readonly="readonly" /></td>
													<td width="52" align="center" valign="middle"><input
														name="txtmta" type="text" class="subactividad" id="txtmta"
														style="text-align: center; border: none; font-weight: bold; color: red;"
														value="<?php echo(number_format($row['meta_poa']));?>"
														size="4" maxlength="5" readonly="readonly" /></td>

													<td width="90" align="center" valign="middle"><input
														name="txtmpar" type="text" id="txtmpar"
														onkeyup="TotalMetaProgramada();"
														value="<?php echo($row['mpar']);?>" size="4" maxlength="5"
														style="text-align: center; font-weight: bold; color: navy;"
														class="subactividad roundDec"
														<?php if($AnioPOA ==$NumAniosProy){echo('readonly="readonly"');}?> /></td>

													<td width="68" align="center" valign="middle"><input
														name="txtmprog" type="text" class="subactividad"
														id="txtmprog" style="text-align: center; border: none;"
														value="<?php echo(number_format($row['mprog']));?>"
														size="4" maxlength="5" readonly="readonly" /></td>
													<td width="45" align="center" valign="middle"><input
														name="txtmvar" type="text" class="subactividad"
														id="txtmvar" style="text-align: center; border: none;"
														value="<?php echo(number_format($row['mvar']));?>"
														size="4" maxlength="5" readonly="readonly" /></td>

												</tr>
             <?php
        }
        $iRs->free();
        
        ?>
             </tbody>
										</table>
									</div>
								</td>

							</tr>


							<tr valign="baseline">
								<td colspan="6" align="left" valign="top" nowrap>
									<fieldset>
										<legend>Participación durante el Proyecto</legend>
										<div class="TableGrid">
               <?php
            $arrPeriodo = $objProy->ResultToArray($objProy->PeriodosxAnio($idProy, 1));
            ?>
              <table width="560" border="0" cellpadding="0"
												cellspacing="0">
												<tr class="SubtitleTable">
													<td width="19" rowspan="2" align="center" valign="middle"
														style="border: 1px #333 solid; background-color: #DBDEF9;">&nbsp;</td>
													<td width="156" rowspan="2" align="center" valign="middle"
														style="border: 1px #333 solid; background-color: #DBDEF9;">Año</td>
													<td height="17" colspan="12" align="center" valign="middle"
														style="border: 1px #333 solid; background-color: #DBDEF9;">Meses</td>
													<td width="37" rowspan="2" align="center" valign="middle"
														style="border: 1px #333 solid; background-color: #DBDEF9;">Total</td>
												</tr>
												<tr class="SubtitleTable">
													<td width="31" height="17" align="center" valign="middle"
														style="border: 1px #333 solid; background-color: #DBDEF9;">
														1 <br /> <font style="font-size: 9px;">(<?php echo($arrPeriodo[0]['nom_abrev']);?>) </font>
													</td>
													<td width="31" align="center" valign="middle"
														style="border: 1px #333 solid; background-color: #DBDEF9;">
														2 <br /> <font style="font-size: 9px;">(<?php echo($arrPeriodo[1]['nom_abrev']);?>) </font>
													</td>
													<td width="31" align="center" valign="middle"
														style="border: 1px #333 solid; background-color: #DBDEF9;">
														3 <br /> <font style="font-size: 9px;">(<?php echo($arrPeriodo[2]['nom_abrev']);?>) </font>
													</td>
													<td width="31" align="center" valign="middle"
														style="border: 1px #333 solid; background-color: #DBDEF9;">
														4 <br /> <font style="font-size: 9px;">(<?php echo($arrPeriodo[3]['nom_abrev']);?>) </font>
													</td>
													<td width="31" align="center" valign="middle"
														style="border: 1px #333 solid; background-color: #DBDEF9;">
														5 <br /> <font style="font-size: 9px;">(<?php echo($arrPeriodo[4]['nom_abrev']);?>) </font>
													</td>
													<td width="31" align="center" valign="middle"
														style="border: 1px #333 solid; background-color: #DBDEF9;">
														6 <br /> <font style="font-size: 9px;">(<?php echo($arrPeriodo[5]['nom_abrev']);?>) </font>
													</td>
													<td width="31" align="center" valign="middle"
														style="border: 1px #333 solid; background-color: #DBDEF9;">
														7 <br /> <font style="font-size: 9px;">(<?php echo($arrPeriodo[6]['nom_abrev']);?>) </font>
													</td>
													<td width="31" align="center" valign="middle"
														style="border: 1px #333 solid; background-color: #DBDEF9;">
														8 <br /> <font style="font-size: 9px;">(<?php echo($arrPeriodo[7]['nom_abrev']);?>) </font>
													</td>
													<td width="31" align="center" valign="middle"
														style="border: 1px #333 solid; background-color: #DBDEF9;">
														9 <br /> <font style="font-size: 9px;">(<?php echo($arrPeriodo[8]['nom_abrev']);?>) </font>
													</td>
													<td width="31" align="center" valign="middle"
														style="border: 1px #333 solid; background-color: #DBDEF9;">
														10 <br /> <font style="font-size: 9px;">(<?php echo($arrPeriodo[9]['nom_abrev']);?>) </font>
													</td>
													<td width="31" align="center" valign="middle"
														style="border: 1px #333 solid; background-color: #DBDEF9;">
														11 <br /> <font style="font-size: 9px;">(<?php echo($arrPeriodo[10]['nom_abrev']);?>) </font>
													</td>
													<td width="31" align="center" valign="middle"
														style="border: 1px #333 solid; background-color: #DBDEF9;">
														12 <br /> <font style="font-size: 9px;">(<?php echo($arrPeriodo[11]['nom_abrev']);?>) </font>
													</td>
												</tr>
												<tbody class="data" bgcolor="#FFFFFF">
            <?php
            
            $sumaTotal = 0;
            $rsMetas = $objMan->Personal_Listado_Metas($idProy, $idVersion, $idPer);
            
            while ($rowMeta = mysqli_fetch_assoc($rsMetas)) {
                
                if ($AnioPOA > 0 && $AnioPOA != $rowMeta['t02_anio']) {
                    $Disbled = "disabled=\"disabled\"";
                    if (($AnioPOA - 1) == $rowMeta['t02_anio'] && $aCurrentPoa['t02_unsuspend_flg'] == '1')
                        $Disbled = "";
                } else {
                    $Disbled = "";
                }
                
                $NombMes = "t03_anio_" . $rowMeta['t02_anio'] . "_mes[]";
                $sumaTotal += $rowMeta["t03_tot_anio"];
                ?>
                  <tr class="RowData">
														<td align="left" nowrap="nowrap"><input name="ChkAll[]"
															type="checkbox" id="ChkAll[]" value="1"
															<?php if($rowMeta["t03_tot_anio"]==12){echo("checked");}?>
															onclick="ActivaMesesAnio('<?php echo($rowMeta['t02_anio']);?>',this.checked);"
															<?php echo($Disbled);?> /></td>
														<td align="left" nowrap="nowrap"><input name="t03_anios[]"
															type="hidden" id="t03_anios[]"
															value="<?php echo($rowMeta['t02_anio']);?>"
															<?php echo($Disbled);?> />
                       <?php echo($rowMeta['anio']); ?>
                    </td>
                    <?php
                $onClickCode = $rowMeta['t02_anio'] == $AnioPOA ? "onclick=TotalMesesactivados(" . $rowMeta['t02_anio'] . ")" : "";
                ?>
                    <td align="center" valign="middle"><input
															name="<?php echo($NombMes);?>" type="checkbox"
															id="<?php echo($NombMes);?>" value="1"
															<?php if($rowMeta["t03_mes1"]=="1"){echo("checked");}?>
															<?php echo $onClickCode; ?>
															anio='<?php echo($rowMeta['t02_anio']);?>' total='1'
															<?php echo($Disbled);?>
															<?php if($arrPeriodo[0]['mes_ok']==0 && $Disbled=="") {echo("disabled=\"disabled\"");} ?> /></td>
														<td align="center" valign="middle"><input
															name="<?php echo($NombMes);?>" type="checkbox"
															id="<?php echo($NombMes);?>" value="2"
															<?php if($rowMeta["t03_mes2"]=="1"){echo("checked");}?>
															<?php echo $onClickCode; ?>
															anio='<?php echo($rowMeta['t02_anio']);?>' total='1'
															<?php echo($Disbled);?>
															<?php if($arrPeriodo[1]['mes_ok']==0 && $Disbled=="") {echo("disabled=\"disabled\"");} ?> /></td>
														<td align="center" valign="middle"><input
															name="<?php echo($NombMes);?>" type="checkbox"
															id="<?php echo($NombMes);?>" value="3"
															<?php if($rowMeta["t03_mes3"]=="1"){echo("checked");}?>
															<?php echo $onClickCode; ?>
															anio='<?php echo($rowMeta['t02_anio']);?>' total='1'
															<?php echo($Disbled);?>
															<?php if($arrPeriodo[2]['mes_ok']==0 && $Disbled=="") {echo("disabled=\"disabled\"");} ?> /></td>
														<td align="center" valign="middle"><input
															name="<?php echo($NombMes);?>" type="checkbox"
															id="<?php echo($NombMes);?>" value="4"
															<?php if($rowMeta["t03_mes4"]=="1"){echo("checked");}?>
															<?php echo $onClickCode; ?>
															anio='<?php echo($rowMeta['t02_anio']);?>' total='1'
															<?php echo($Disbled);?>
															<?php if($arrPeriodo[3]['mes_ok']==0 && $Disbled=="") {echo("disabled=\"disabled\"");} ?> /></td>
														<td align="center" valign="middle"><input
															name="<?php echo($NombMes);?>" type="checkbox"
															id="<?php echo($NombMes);?>" value="5"
															<?php if($rowMeta["t03_mes5"]=="1"){echo("checked");}?>
															<?php echo $onClickCode; ?>
															anio='<?php echo($rowMeta['t02_anio']);?>' total='1'
															<?php echo($Disbled);?>
															<?php if($arrPeriodo[4]['mes_ok']==0 && $Disbled=="") {echo("disabled=\"disabled\"");} ?> /></td>
														<td align="center" valign="middle"><input
															name="<?php echo($NombMes);?>" type="checkbox"
															id="<?php echo($NombMes);?>" value="6"
															<?php if($rowMeta["t03_mes6"]=="1"){echo("checked");}?>
															<?php echo $onClickCode; ?>
															anio='<?php echo($rowMeta['t02_anio']);?>' total='1'
															<?php echo($Disbled);?>
															<?php if($arrPeriodo[5]['mes_ok']==0 && $Disbled=="") {echo("disabled=\"disabled\"");} ?> /></td>
														<td align="center" valign="middle"><input
															name="<?php echo($NombMes);?>" type="checkbox"
															id="<?php echo($NombMes);?>" value="7"
															<?php if($rowMeta["t03_mes7"]=="1"){echo("checked");}?>
															<?php echo $onClickCode; ?>
															anio='<?php echo($rowMeta['t02_anio']);?>' total='1'
															<?php echo($Disbled);?>
															<?php if($arrPeriodo[6]['mes_ok']==0 && $Disbled=="") {echo("disabled=\"disabled\"");} ?> /></td>
														<td align="center" valign="middle"><input
															name="<?php echo($NombMes);?>" type="checkbox"
															id="<?php echo($NombMes);?>" value="8"
															<?php if($rowMeta["t03_mes8"]=="1"){echo("checked");}?>
															<?php echo $onClickCode; ?>
															anio='<?php echo($rowMeta['t02_anio']);?>' total='1'
															<?php echo($Disbled);?>
															<?php if($arrPeriodo[7]['mes_ok']==0 && $Disbled=="") {echo("disabled=\"disabled\"");} ?> /></td>
														<td align="center" valign="middle"><input
															name="<?php echo($NombMes);?>" type="checkbox"
															id="<?php echo($NombMes);?>" value="9"
															<?php if($rowMeta["t03_mes9"]=="1"){echo("checked");}?>
															<?php echo $onClickCode; ?>
															anio='<?php echo($rowMeta['t02_anio']);?>' total='1'
															<?php echo($Disbled);?>
															<?php if($arrPeriodo[8]['mes_ok']==0 && $Disbled=="") {echo("disabled=\"disabled\"");} ?> /></td>
														<td align="center" valign="middle"><input
															name="<?php echo($NombMes);?>" type="checkbox"
															id="<?php echo($NombMes);?>" value="10"
															<?php if($rowMeta["t03_mes10"]=="1"){echo("checked");}?>
															<?php echo $onClickCode; ?>
															anio='<?php echo($rowMeta['t02_anio']);?>' total='1'
															<?php echo($Disbled);?>
															<?php if($arrPeriodo[9]['mes_ok']==0 && $Disbled=="") {echo("disabled=\"disabled\"");} ?> /></td>
														<td align="center" valign="middle"><input
															name="<?php echo($NombMes);?>" type="checkbox"
															id="<?php echo($NombMes);?>" value="11"
															<?php if($rowMeta["t03_mes11"]=="1"){echo("checked");}?>
															<?php echo $onClickCode; ?>
															anio='<?php echo($rowMeta['t02_anio']);?>' total='1'
															<?php echo($Disbled);?>
															<?php if($arrPeriodo[10]['mes_ok']==0 && $Disbled=="") {echo("disabled=\"disabled\"");} ?> /></td>
														<td align="center" valign="middle"><input
															name="<?php echo($NombMes);?>" type="checkbox"
															id="<?php echo($NombMes);?>" value="12"
															<?php if($rowMeta["t03_mes12"]=="1"){echo("checked");}?>
															<?php echo $onClickCode; ?>
															anio='<?php echo($rowMeta['t02_anio']);?>' total='1'
															<?php echo($Disbled);?>
															<?php if($arrPeriodo[11]['mes_ok']==0 && $Disbled=="") {echo("disabled=\"disabled\"");} ?> /></td>
														<td align="center" valign="middle"><span
															id="sum_<?php echo($rowMeta['t02_anio']);?>"
															style="text-align: center"><?php echo($rowMeta["t03_tot_anio"]);?></span></td>
													</tr>
                  <?php
                $arrPeriodo = $objProy->ResultToArray($objProy->PeriodosxAnio($idProy, $rowMeta['t02_anio'] + 1));
            }
            if ($rsMetas) {
                $rsMetas->free();
            }
            ?>


                  </tbody>
												<tfoot>
													<tr>
														<th>&nbsp;</th>
														<th>&nbsp;</th>
														<th colspan="12" align="right"><iframe id="ifrmUploadFile"
																name="ifrmUploadFile" style="display: none;"></iframe></th>
														<th align="center"><input type="text"
															id="txtSumaTotalMeses" name="txtSumaTotalMeses"
															value="<?php echo($sumaTotal);?>"
															style="width: 100%; background-color: #333; color: #FFF; border: none; text-align: center; font-weight: bold;"
															readonly="readonly" /></th>
													</tr>
												</tfoot>
											</table>
										</div>
									</fieldset>

								</td>
							</tr>
							<tr valign="baseline">
								<td colspan="6" align="left" nowrap>
									<table width="100%" border="0" cellspacing="1" cellpadding="0"
										style="padding: 0px;">
										<tr>
											<td width="14%" align="center" nowrap="nowrap">Rem. Mensual</td>
											<td width="11%" align="center">Gratificación</td>
											<td width="10%" align="center">ESSALUD</td>
											<td width="10%" align="center">CTS</td>
											<td width="15%" align="center" nowrap="nowrap">Prom Mensual</td>
											<td width="40%" align="center" nowrap="nowrap">Gasto Total</td>
										</tr>
										<tr>
											<td align="center"><input name="t03_per_rem_mes" type="text"
												id="t03_per_rem_mes" value="" size="12" readonly="readonly"
												style="text-align: center" /></td>
											<td align="center"><input name="t03_per_grati" type="text"
												id="t03_per_grati" value="" size="12" readonly="readonly"
												style="text-align: center" /></td>
											<td align="center"><input name="t03_per_essalud" type="text"
												id="t03_per_essalud" value="" size="12" readonly="readonly"
												style="text-align: center" /></td>
											<td align="center"><input name="t03_per_cts" type="text"
												id="t03_per_cts" value="" size="12" readonly="readonly"
												style="text-align: center" /></td>
											<td align="center"><input name="t03_per_prom_mes" type="text"
												id="t03_per_prom_mes" value="" size="15" readonly="readonly"
												style="text-align: center" /></td>
											<td align="center"><input name="t03_per_gas_tot" type="text"
												id="t03_per_gas_tot" value="" size="15" readonly="readonly"
												style="text-align: center" /></td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</div>

					<script language="javascript">
  	$(document).ready(function() {
  		bindRoundDecimals();
  		$(':input[anio=' + <?php echo $AnioPOA; ?> + ']').click(function() { TotalMesesactivados(<?php echo $AnioPOA; ?>); });
  		$(':input[anio=' + <?php echo $AnioPOA-1; ?> + ']').click(function() {
			$('#sum_' + <?php echo $AnioPOA-1; ?>).text($(':input[anio='+<?php echo $AnioPOA-1; ?>+']:checked').size());
			CalcularTotalM();
  		});
  	});

  function Guardar_Personal()
	{
	<?php $ObjSession->AuthorizedPage(); ?>

	 if( $('#t03_nom_per').val()=="" ) {alert("Ingrese Cargo del Personal"); $('#t03_nom_per').focus(); return false;}
	 if( $('#t03_remu').val()=="" ) {alert("Ingrese Remuneracion"); $('#t03_remu').focus(); return false;}
	 if( $('#t03_dedica').val()=="" ) {alert("Ingrese Porcentaje de Dedicacion"); $('#t03_dedica').focus(); return false;}

	 TotalMesesactivados(<?php echo $AnioPOA; ?>);
	 var BodyForm = $("#FormData").serialize() ;
	 var sURL = "poa_fin_personal_process.php?action=<?php echo($view);?>" ;
	 var req = Spry.Utils.loadURL("POST", sURL, true, MySuccessGuardarPersonal, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });

	 return false;
	}

	function MySuccessGuardarPersonal(req)
	{
		var respuesta = req.xhRequest.responseText;
		respuesta = respuesta.replace(/^\s*|\s*$/g,"");
		var ret = respuesta.substring(0,5);
		if(ret=="Exito")
		{
		 LoadPersonal(true);
		 var idper = respuesta.substring(0,7);
		 alert(respuesta.replace(idper,""));
		 idper = idper.replace(ret,"");
		 setTimeout("EditarPersonal("+idper+");",5);
		}
		else
		{  alert(respuesta); }
	}

	function TotalMesesactivados(anio)
	{
		var idsum = "#sum_"+anio;
		var tot = $("input[anio='"+anio+"']:checked").length ;
		$(idsum).html(tot);

		SumaTotalMeses();
		CalcularPagoTotal(anio);
		CalcularTotalAnio(anio);
		TotalMetaProgramada();
	}
	function ActivaMesesAnio(anio,valor)
	{
		$("input[anio='"+anio+"']").attr('checked', valor);
		TotalMesesactivados(anio);
	}
	function SumaTotalMeses()
	{
		var tot = $("input[total='1']:not(input:disabled'):checked").length ;
			// $('#txtSumaTotalMeses').val(tot);

		CalcularTotalM();
	}
	function CalcularTotalM() //calcula el total de todos los años
	{
		var aTotal = 0;
		$('span[id^=sum_]').each(function() {
		    var aVal=$(this).text();
		    if (!isNaN(parseFloat(aVal)))
		        aTotal += parseFloat(aVal);
		});
		$('#txtSumaTotalMeses').val(aTotal);
	}

	function CalcularTotalAnio(anio){
		var idmta = "#txtmta";
		var tot = $("input[anio='"+anio+"']:checked").length ;
		$(idmta).val(tot);

	}
	function TotalMetaProgramada()
	{
		var ini = CNumber($('#txtmfi').attr("valor"));
		var d =  CNumber($('#txtmeaa').attr("valor"));
		var e =  CNumber($('#txtmtpe').val());
		var f =  CNumber($("#txtmta").val());
		var g =  CNumber($("#txtmpar").val());
		var h = (d + f + g);
		var i = h - ini ;
		$("#txtmprog").val(h);
		$("#txtmvar").val(i);
	}
	function CNumber(str)
	  {
		  var numero =0;
		  if (str !="" && str !=null)
		  { numero = parseFloat(str);}
		  if(isNaN(numero)) { numero=0;}
		 return numero;
	  }

	function CalcularPagoTotal(anio)
	{
		var um 	  = $("#t03_per_um option[value='"+$("#t03_per_um").val()+"']").attr("title");
		// var num_mes = $('#txtSumaTotalMeses').val();
		 var num_mes = parseInt($("#sum_"+anio).html());
		var bruto = $('#t03_remu').val();
		var dedica= $('#t03_dedica').val();
		var p_gra = parseFloat('<?php echo($HC->gratificacion);?>' ) ;
		var p_cts = parseFloat('<?php echo($HC->porc_CTS);?>'      ) ;
		var p_ess = parseFloat('<?php echo($HC->porc_ESS);?>'      ) ;

		if(isNaN(bruto) || bruto==null || bruto==''){bruto=0;}
		if(isNaN(dedica) || dedica==null || dedica==''){dedica=0;}

		var Remun = parseFloat(bruto) *  (parseFloat(dedica)/100) ;

		var Grati =0;
		var CTS =0;
		var Ess =0;

		if(um=='1') //Planilla
		{
			Grati = (Remun / p_gra);
			CTS = (((Grati + Remun) * p_cts)/100) ;
			Ess = (((Grati + Remun) * p_ess)/100) ;
		}
		else  //RH
		{
			Grati = 0;
			CTS = 0;
			Ess = 0;
		}

		var GastoProm = (Remun + Grati + CTS + Ess) ;
		var GastoTotal= (GastoProm * num_mes) ;

		$('#t03_per_rem_mes').val(Remun.toFixed(2));
		$('#t03_per_grati').val(Grati.toFixed(2));
		$('#t03_per_essalud').val(Ess.toFixed(2));
		$('#t03_per_cts').val(CTS.toFixed(2));
		$('#t03_per_prom_mes').val(GastoProm.toFixed(2));
		$('#t03_per_gas_tot').val(GastoTotal.toFixed(2));
	}

	function AdjuntarTDR()
	{

		if($("#t03_id_per").val()=='')
		{alert("Primero debe Guardar el Personal, para Adjuntar el TDR");return false ;}

		var strFile = $('#txtFileUploadTDR').val();
		if(strFile!="")
		{
			//Procedemos a Validar el Archivo Adjunto Extencion (*.doc, *.docx, *.pdf)
			var extArray = new Array("doc", "docx", "pdf", "xls", "xlsx", "txt");
			var arrExt = strFile.split(".");
			var ext = arrExt[arrExt.length - 1];
			var allowSubmit = false ;
			for (var i = 0; i < extArray.length; i++)
			{
				if (extArray[i] == ext) { allowSubmit = true; break; }
			}
			if(allowSubmit)
			{
				 var urlPost = "poa_fin_personal_process.php?action=<?php echo(md5('ajax_save_TDR'));?>" ;
				 $('#FormData').attr({target: "ifrmUploadFile"});
				 $('#FormData').attr({action: urlPost});
				 $('#FormData').attr({encoding: "multipart/form-data"});
				 $('#FormData').submit();
				 $('#FormData').attr({target: "_self"});
			}
			else
			{
				alert("Error: El archivo ["+strFile+"] \n No es Permitido !!!") ;
			}

		}

	}

	function ShowFileTDR()
	{
		<?php
			$urlFile =  $row['t03_tdr_file'];
			$filename = $row['t03_tdr_file'];
			$path = constant('APP_PATH').$HC->FolderUploadTDR;
			$href = constant("DOCS_PATH")."download.php?filename=".$filename."&fileurl=".$urlFile."&path=".$path;
		?>
		var url = "<?php echo($filename);?>";
		if(url=="") {alert("No se ha establecido el archivo adjunto"); return false;}
		var url = "<?php echo($href);?>";
		window.open(url,"DownloadTDR",null,true);
		return;
	}

  </script>

					<script>
  $("#t03_remu").numeric().pasteNumeric();
  $("#t03_dedica").numeric().pasteNumeric();
  $("#t03_perma").numeric().pasteNumeric();
  TotalMesesactivados(<?php echo $AnioPOA ; ?>);
  CalcularPagoTotal(<?php echo $AnioPOA ; ?>);

  </script>

 <?php if($view=='') { ?>
  <!-- InstanceEndEditable -->
				</div>
			</form>
		</div>
		<!-- Fin de Container Page-->
	</div>

</body>
<!-- InstanceEnd -->
</html>
<?php } ?>

