<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>
<?php

// require_once(constant('PATH_CLASS')."BLManejoProy.class.php");
require_once (constant('PATH_CLASS') . "BLMarcoLogico.class.php");
require_once (constant("PATH_CLASS") . "BLPOA.class.php");
require_once (constant('PATH_CLASS') . "BLTablasAux.class.php");

error_reporting("E_PARSE");

// $objML = new BLMarcoLogico();
$objPOA = new BLPOA();
$objProy = new BLProyecto();
$objHC = new HardCode();
$view = $objFunc->__GET('mode');

$idProy = $objFunc->__Request('idProy');
$idVersion = $objFunc->__Request('idVersion');
$idComp = $objFunc->__Request('idComp');
$idActiv = $objFunc->__Request('idActiv');
$idSActiv = $objFunc->__Request('idSActiv');
$anio = $objFunc->__Request('anio');

$aFchIni = strtotime($objProy->FechaInicioProy($idProy, $idVersion));
$aFchTer = strtotime($objProy->FechaTerminoProy($idProy, $idVersion));
$NumAniosProy = ceil(($aFchTer - $aFchIni) / 31104000);

// $NumAniosProy = $objProy->NumeroAniosProy($idProy);

$Partida = 0;

$row = 0;
if ($view == md5("edit")) {
    $objFunc->SetSubTitle("POA Actividades - Editando Registro");
    $Partida = $objFunc->__Request('idPartida');
    $row = $objPOA->GetSubActividad($idProy, $idVersion, $idComp, $idActiv, $idSActiv);
} else {
    $row = 0;
    $objFunc->SetSubTitle("POA Actividades - Nuevo Registro");
}

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

<?php } ?>
</head>
<body class="oneColElsCtrHdr">
	<div id="container">
		<div id="mainContent">
			<form id="FormData" method="post" enctype="application/x-www-form-urlencoded" action="<?php echo($_SERVER['PHP_SELF']);?>">
				<div id="divContent">
 <?php } ?>
					<div style="width: 99%; border: solid 1px #D3D3D3;">
						<div id="toolbar" style="height: 4px;" class="BackColor">
							<table border="0" cellpadding="0" cellspacing="0">
								<tr>
									<td width="9%"><button class="Button"
											onclick="Guardar_MetasSubAct(); return false;"
											value="Guardar">Guardar</button></td>
									<td width="9%"><button class="Button"
											onclick="spryPopupDialog01.displayPopupDialog(false); return false;"
											value="Cancelar">Cancelar</button></td>
									<td width="8%">&nbsp;</td>
									<td width="47%" align="right"><?php echo($objFunc->SubTitle) ;?></td>
								</tr>
							</table>
						</div>
						<table width="580" align="center" class="TableEditReg">
							<tr valign="baseline">
								<td width="103" align="left" nowrap="nowrap"><strong>Actividad</strong></td>
								<td colspan="3" align="left"><input name="t09_cod_sub2"
									type="text" id="t09_cod_sub2"
									value="<?php  echo($row["t08_cod_comp"].".".$row["t09_cod_act"].".".$row["t09_cod_sub"]);?>"
									size="2" maxlength="5" disabled="disabled"
									style="text-align: center;" /> <input name="t09_sub"
									type="text" disabled="disabled" id="t09_sub"
									value="<?php echo($row["t09_sub"]);?>" size="75"
									maxlength="100" /></td>
							</tr>
							<tr valign="baseline">
								<td height="30" align="left" valign="middle" nowrap="nowrap"><strong>Unidad
										de Medida</strong></td>
								<td width="155" align="left" valign="middle"><input
									name="t09_um" type="text" disabled="disabled" id="t09_um"
									value="<?php echo($row["t09_um"]);?>" size="30" /></td>
								<td width="75" align="left" valign="middle" nowrap="nowrap">Tipo
									Actividad</td>
								<td width="288" align="left" valign="middle">
									<select name="t09_tipo_sub" id="t09_tipo_sub" style="width: 160px;" disabled="disabled">
										<option value="" selected="selected"></option>
								        <?php
								        $objTablas = new BLTablasAux();
								        $rs = $objTablas->TipoSubActividades();
								        $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $row['t09_tipo_sub']);
								        $objTablas = NULL;
								        ?>
								      </select>
							  	</td>
							</tr>
							<tr valign="bottom">
								<td height="17" colspan="4" align="center" valign="middle">
									<div class="TableGrid">
										<table width="538" border="0" cellpadding="0" cellspacing="0">
											<thead>
											</thead>
											<tbody class="data">
												<tr>
													<td width="52" height="15" align="center" valign="middle"
														style="font-size: 10px; background-color: #F3F3F3;">Meta Física Inicial</td>
													<td height="15" align="center" valign="middle"
														style="font-size: 10px; background-color: #F3F3F3;">Meta Proyectada del Año Anterior</td>
													<td height="15" align="center" valign="middle"
														style="font-size: 10px; background-color: #F3F3F3;">Meta Física Total Vigente</td>
													<td height="15" align="center" valign="middle"
														style="font-size: 10px; background-color: #F3F3F3;">Meta Ejecutada Años Anteriores</td>
													<td height="15" align="center" valign="middle"
														style="font-size: 10px; background-color: #F3F3F3;">Meta Total por Ejecutar</td>
													<td height="15" align="center" valign="middle"
														style="font-size: 10px; background-color: #F3F3F3;">Total del Año <?php echo($idAnio);?></td>
													<td height="15" align="center" valign="middle"
														style="font-size: 10px; background-color: #F3F3F3;">Meta Proyectada Años Restantes</td>
													<td height="15" align="center" valign="middle"
														style="font-size: 10px; background-color: #F3F3F3;">Meta Reprogram</td>
													<td height="15" align="center" valign="middle"
														style="font-size: 10px; background-color: #F3F3F3;">Variac</td>
												</tr>

									   		    <?php
										        $objPOA = new BLPOA();
										        
										        $iRs = $objPOA->POA_ListadoSubActividades($idProy, $idVersion, $idComp, $row["t09_cod_act"], $anio);
										        $RowIndex = 0;
										        
										        while ($row1 = mysqli_fetch_assoc($iRs)) {
										            
										            if ($row1['codigo'] == ($idComp . '.' . $idActiv . '.' . $idSActiv)) {
										                
										            	$mftv = $anio==1?$row1['mfi']:$row1['mftv'];
											            $mtpe = $mftv - $row1['meaa'];
											            $mvar = ($row1['meaa'] + $row1['meta_poa'] + $row1['mpar']) - $mftv;
											            $row1['mreprog'] = $row1['meaa'] + $row1['meta_poa'] + $row1['mpar'];
										                $ObservaMT = $row['t09_obs_mt'];
										                ?>
							                    <tr>
													<td align="center" valign="middle" nowrap="nowrap"><input
														name="txtmfi" type="text" class="subactividad" id="txtmfi"
														style="text-align: center; border: none;"
														value="<?php echo(number_format($row1['mfi']));?>"
														valor="<?php echo($row1['mfi']);?>" size="4" maxlength="5"
														readonly="readonly" /></td>
													<td width="80" align="center" valign="middle"><input
														name="txtmpaa" type="text" class="subactividad"
														id="txtmpaa" style="text-align: center; border: none;"
														value="<?php echo(number_format($row1['mpaa']));?>"
														valor="<?php echo($row1['mpaa']);?>" size="4"
														maxlength="5" readonly="readonly" /></td>
													<td width="80" align="center" valign="middle"><input
														name="txtmftv" type="text" class="subactividad"
														id="txtmftv" style="text-align: center; border: none;"
														value="<?php echo(number_format($mftv));?>"
														valor="<?php echo($mftv);?>" size="4"
														maxlength="5" readonly="readonly" /></td>
													<td width="82" align="center" valign="middle"><input
														name="txtmeaa" type="text" class="subactividad"
														id="txtmeaa" style="text-align: center; border: none;"
														value="<?php echo(number_format($row1['meaa']));?>"
														valor="<?php echo($row1['meaa']);?>" size="4"
														maxlength="5" readonly="readonly" /></td>
													<td width="67" align="center" valign="middle"><input
														name="txtmtpe" type="text" class="subactividad"
														id="txtmtpe" style="text-align: center; border: none;"
														value="<?php echo(number_format($mtpe));?>"
														size="4" maxlength="5" readonly="readonly" /></td>
													<td width="52" align="center" valign="middle"><input
														name="txtmta" type="text" class="subactividad" id="txtmta"
														style="text-align: center; border: none; font-weight: bold; color: red;"
														value="<?php echo(number_format($row1['meta_poa']));?>"
														size="4" maxlength="5" readonly="readonly" /></td>

													<td width="90" align="center" valign="middle"><input
														name="txtmpar" type="text" id="txtmpar"
														onkeyup="TotalMetaMeses('<?php echo($anio);?>');"
														value="<?php echo($row1['mpar']);?>" size="4"
														maxlength="5"
														style="text-align: center; font-weight: bold; color: navy;"
														class="subactividad"
														<?php if($anio==$NumAniosProy){echo('readonly="readonly"');}?> /></td>

													<td width="68" align="center" valign="middle"><input
														name="txtmprog" type="text" class="subactividad"
														id="txtmprog" style="text-align: center; border: none;"
														value="<?php echo(number_format($row1['mreprog']));?>"
														size="4" maxlength="5" readonly="readonly" /></td>
													<td width="45" align="center" valign="middle"><input
														name="txtmvar" type="text" class="subactividad"
														id="txtmvar" style="text-align: center; border: none;"
														value="<?php echo(number_format($mvar));?>"
														size="4" maxlength="5" readonly="readonly" /></td>
												</tr>

             <?php
            } // End if
        }
        
        $iRs->free();
        ?>
             </tbody>
										</table>
									</div>

								</td>
							</tr>
							<tr valign="baseline">
								<td colspan="4" align="left" valign="top" nowrap
									style="padding: 0px;">
									<fieldset style="padding-left: 1px; padding-right: 0px;">
										<legend>Cronograma / Metas</legend>
										<div class="TableGrid">
											<table width="560" border="0" cellpadding="0" cellspacing="0">
												<tr class="SubtitleTable">
													<td width="50" rowspan="2" align="center" valign="middle"
														style="border: 1px #333 solid; background-color: #DBDEF9;">Año</td>
													<td height="17" colspan="12" align="center" valign="middle"
														style="border: 1px #333 solid; background-color: #DBDEF9;">Meses</td>
													<td width="304" rowspan="2" align="center" valign="middle"
														style="border: 1px #333 solid; background-color: #DBDEF9;">Total</td>
												</tr>
												<tr class="SubtitleTable">
													<td width="17" height="17" align="center" valign="middle"
														style="border: 1px #333 solid; background-color: #DBDEF9;">
														1</td>
													<td width="17" align="center" valign="middle"
														style="border: 1px #333 solid; background-color: #DBDEF9;">
														2</td>
													<td width="17" align="center" valign="middle"
														style="border: 1px #333 solid; background-color: #DBDEF9;">
														3</td>
													<td width="17" align="center" valign="middle"
														style="border: 1px #333 solid; background-color: #DBDEF9;">
														4</td>
													<td width="17" align="center" valign="middle"
														style="border: 1px #333 solid; background-color: #DBDEF9;">
														5</td>
													<td width="17" align="center" valign="middle"
														style="border: 1px #333 solid; background-color: #DBDEF9;">
														6</td>
													<td width="17" align="center" valign="middle"
														style="border: 1px #333 solid; background-color: #DBDEF9;">
														7</td>
													<td width="17" align="center" valign="middle"
														style="border: 1px #333 solid; background-color: #DBDEF9;">
														8</td>
													<td width="17" align="center" valign="middle"
														style="border: 1px #333 solid; background-color: #DBDEF9;">
														9</td>
													<td width="17" align="center" valign="middle"
														style="border: 1px #333 solid; background-color: #DBDEF9;">
														10</td>
													<td width="17" align="center" valign="middle"
														style="border: 1px #333 solid; background-color: #DBDEF9;">
														11</td>
													<td width="17" align="center" valign="middle"
														style="border: 1px #333 solid; background-color: #DBDEF9;">
														12</td>
												</tr>
												<tbody class="data" bgcolor="#FFFFFF">
                  <?php
                $sumaTotal = 0;
                $rsMetas = $objPOA->POA_ListadoSubActividadesMetas($idProy, $idVersion, $idComp, $idActiv, $idSActiv, $anio);
                
                while ($rowMeta = mysqli_fetch_assoc($rsMetas)) {
                    $irsPeriodo = $objProy->PeriodosxAnio($idProy, $rowMeta['t02_anio']);
                    $arrPeriodo = $objProy->ResultToArray($irsPeriodo);
                    
                    $NombMes = "anio_" . $rowMeta['t02_anio'] . "_mes[]";
                    $sumaTotal += $rowMeta["tot_anio"];
                    
                    if ($rowMeta['t02_anio'] == $anio) {
                        $disabled = '';
                    } else {
                        $disabled = 'disabled';
                    }
                    
                    ?>
                  <tr class="RowData">
														<td align="left" nowrap="nowrap"><input name="anios[]"
															type="hidden" id="anios[]"
															value="<?php echo($rowMeta['t02_anio']);?>"
															<?php echo($disabled);?> class="subactividad" />
                      <?php echo($rowMeta['anio']); ?>
                      </td>
														<td align="center" valign="middle" style="padding: 1px;"><input
															name="<?php echo($NombMes);?>" type="text"
															id="<?php echo($NombMes);?>"
															onkeyup="TotalMetaMeses('<?php echo($rowMeta['t02_anio']);?>');"
															value="<?php echo($rowMeta["mes1"]);?>" size="1"
															maxlength="5" anio='<?php echo($rowMeta['t02_anio']);?>'
															total='1' style="text-align: center; width: 30px;"
															<?php echo($disabled);?> class="subactividad"
															<?php if($arrPeriodo[0]['mes_ok']=="0"){echo('inactivo="1"');}?> /></td>
														<td align="center" valign="middle" style="padding: 1px;"><input
															name="<?php echo($NombMes);?>" type="text"
															id="<?php echo($NombMes);?>"
															onkeyup="TotalMetaMeses('<?php echo($rowMeta['t02_anio']);?>');"
															value="<?php echo($rowMeta["mes2"]);?>" size="1"
															maxlength="5" anio='<?php echo($rowMeta['t02_anio']);?>'
															total='1' style="text-align: center; width: 30px;"
															<?php echo($disabled);?> class="subactividad"
															<?php if($arrPeriodo[1]['mes_ok']=="0"){echo('inactivo="1"');}?> /></td>
														<td align="center" valign="middle" style="padding: 1px;"><input
															name="<?php echo($NombMes);?>" type="text"
															id="<?php echo($NombMes);?>"
															onkeyup="TotalMetaMeses('<?php echo($rowMeta['t02_anio']);?>');"
															value="<?php echo($rowMeta["mes3"]);?>" size="1"
															maxlength="5" anio='<?php echo($rowMeta['t02_anio']);?>'
															total='1' style="text-align: center; width: 30px;"
															<?php echo($disabled);?> class="subactividad"
															<?php if($arrPeriodo[2]['mes_ok']=="0"){echo('inactivo="1"');}?> /></td>
														<td align="center" valign="middle" style="padding: 1px;"><input
															name="<?php echo($NombMes);?>" type="text"
															id="<?php echo($NombMes);?>"
															onkeyup="TotalMetaMeses('<?php echo($rowMeta['t02_anio']);?>');"
															value="<?php echo($rowMeta["mes4"]);?>" size="1"
															maxlength="5" anio='<?php echo($rowMeta['t02_anio']);?>'
															total='1' style="text-align: center; width: 30px;"
															<?php echo($disabled);?> class="subactividad"
															<?php if($arrPeriodo[3]['mes_ok']=="0"){echo('inactivo="1"');}?> /></td>
														<td align="center" valign="middle" style="padding: 1px;"><input
															name="<?php echo($NombMes);?>" type="text"
															id="<?php echo($NombMes);?>"
															onkeyup="TotalMetaMeses('<?php echo($rowMeta['t02_anio']);?>');"
															value="<?php echo($rowMeta["mes5"]);?>" size="1"
															maxlength="5" anio='<?php echo($rowMeta['t02_anio']);?>'
															total='1' style="text-align: center; width: 30px;"
															<?php echo($disabled);?> class="subactividad"
															<?php if($arrPeriodo[4]['mes_ok']=="0"){echo('inactivo="1"');}?> /></td>
														<td align="center" valign="middle" style="padding: 1px;"><input
															name="<?php echo($NombMes);?>" type="text"
															id="<?php echo($NombMes);?>"
															onkeyup="TotalMetaMeses('<?php echo($rowMeta['t02_anio']);?>');"
															value="<?php echo($rowMeta["mes6"]);?>" size="1"
															maxlength="5" anio='<?php echo($rowMeta['t02_anio']);?>'
															total='1' style="text-align: center; width: 30px;"
															<?php echo($disabled);?> class="subactividad"
															<?php if($arrPeriodo[5]['mes_ok']=="0"){echo('inactivo="1"');}?> /></td>
														<td align="center" valign="middle" style="padding: 1px;"><input
															name="<?php echo($NombMes);?>" type="text"
															id="<?php echo($NombMes);?>"
															onkeyup="TotalMetaMeses('<?php echo($rowMeta['t02_anio']);?>');"
															value="<?php echo($rowMeta["mes7"]);?>" size="1"
															maxlength="5" anio='<?php echo($rowMeta['t02_anio']);?>'
															total='1' style="text-align: center; width: 30px;"
															<?php echo($disabled);?> class="subactividad"
															<?php if($arrPeriodo[6]['mes_ok']=="0"){echo('inactivo="1"');}?> /></td>
														<td align="center" valign="middle" style="padding: 1px;"><input
															name="<?php echo($NombMes);?>" type="text"
															id="<?php echo($NombMes);?>"
															onkeyup="TotalMetaMeses('<?php echo($rowMeta['t02_anio']);?>');"
															value="<?php echo($rowMeta["mes8"]);?>" size="1"
															maxlength="5" anio='<?php echo($rowMeta['t02_anio']);?>'
															total='1' style="text-align: center; width: 30px;"
															<?php echo($disabled);?> class="subactividad"
															<?php if($arrPeriodo[7]['mes_ok']=="0"){echo('inactivo="1"');}?> /></td>
														<td align="center" valign="middle" style="padding: 1px;"><input
															name="<?php echo($NombMes);?>" type="text"
															id="<?php echo($NombMes);?>"
															onkeyup="TotalMetaMeses('<?php echo($rowMeta['t02_anio']);?>');"
															value="<?php echo($rowMeta["mes9"]);?>" size="1"
															maxlength="5" anio='<?php echo($rowMeta['t02_anio']);?>'
															total='1' style="text-align: center; width: 30px;"
															<?php echo($disabled);?> class="subactividad"
															<?php if($arrPeriodo[8]['mes_ok']=="0"){echo('inactivo="1"');}?> /></td>
														<td align="center" valign="middle" style="padding: 1px;"><input
															name="<?php echo($NombMes);?>" type="text"
															id="<?php echo($NombMes);?>"
															onkeyup="TotalMetaMeses('<?php echo($rowMeta['t02_anio']);?>');"
															value="<?php echo($rowMeta["mes10"]);?>" size="1"
															maxlength="5" anio='<?php echo($rowMeta['t02_anio']);?>'
															total='1' style="text-align: center; width: 30px;"
															<?php echo($disabled);?> class="subactividad"
															<?php if($arrPeriodo[9]['mes_ok']=="0"){echo('inactivo="1"');}?> /></td>
														<td align="center" valign="middle" style="padding: 1px;"><input
															name="<?php echo($NombMes);?>" type="text"
															id="<?php echo($NombMes);?>"
															onkeyup="TotalMetaMeses('<?php echo($rowMeta['t02_anio']);?>');"
															value="<?php echo($rowMeta["mes11"]);?>" size="1"
															maxlength="5" anio='<?php echo($rowMeta['t02_anio']);?>'
															total='1' style="text-align: center; width: 30px;"
															<?php echo($disabled);?> class="subactividad"
															<?php if($arrPeriodo[10]['mes_ok']=="0"){echo('inactivo="1"');}?> /></td>
														<td align="center" valign="middle" style="padding: 1px;"><input
															name="<?php echo($NombMes);?>" type="text"
															id="<?php echo($NombMes);?>"
															onkeyup="TotalMetaMeses('<?php echo($rowMeta['t02_anio']);?>');"
															value="<?php echo($rowMeta["mes12"]);?>" size="1"
															maxlength="5" anio='<?php echo($rowMeta['t02_anio']);?>'
															total='1' style="text-align: center; width: 30px;"
															<?php echo($disabled);?> class="subactividad"
															<?php if($arrPeriodo[11]['mes_ok']=="0"){echo('inactivo="1"');}?> /></td>
														<td align="center" valign="middle"><span
															id="sum_<?php echo($rowMeta['t02_anio']);?>"
															style="text-align: center"><?php echo($rowMeta["tot_anio"]);?></span></td>
													</tr>
                  <?php
                
}
                if ($rsMetas) {
                    $rsMetas->free();
                }
                ?>


                  </tbody>
												<tfoot>
													<tr>
														<th>&nbsp;</th>
														<th colspan="12" align="right"></th>
														<th align="center"><input type="text"
															id="txtSumaTotalMeses" name="txtSumaTotalMeses"
															value="<?php echo($sumaTotal);?>"
															style="width: 100%; background-color: #333; color: #FFF; border: none; text-align: center; font-weight: bold;"
															readonly="readonly" /></th>
													</tr>
												</tfoot>
											</table>
										</div>
										<input name="t02_cod_proy" type="hidden" class="subactividad"
											id="t02_cod_proy" value="<?php echo($idProy);?>"> <input
											name="t02_version" type="hidden" class="subactividad"
											id="t02_version" value="<?php echo($idVersion);?>" /> <input
											name="t08_cod_comp" type="hidden" class="subactividad"
											id="t08_cod_comp" value="<?php echo($idComp);?>"> <input
											name="t09_cod_act" type="hidden" class="subactividad"
											id="t09_cod_act" value="<?php echo($idActiv);?>"> <input
											name="t09_cod_sub" type="hidden" class="subactividad"
											id="t09_cod_sub" value="<?php echo($idSActiv);?>"> <input
											name="t02_anio" type="hidden" class="subactividad"
											id="t02_anio" value="<?php echo($anio);?>" />
									</fieldset>
								</td>
							</tr>
							<tr valign="baseline">
								<td colspan="4" align="left">
        <?php if($objHC->MT==$ObjSession->PerfilID || $objHC->CMT==$ObjSession->PerfilID || $objHC->Admin==$ObjSession->PerfilID) { ?>
          <fieldset>
										<legend>Observaciones</legend>
										<textarea name="txtobs_mt" cols="100" rows="2" id="txtobs_mt"
											class="subactividad"><?php echo($ObservaMT);?></textarea>
									</fieldset>
          <?php } ?>
          <?php if($objHC->Ejec==$ObjSession->PerfilID) { ?>
          <input type="hidden" name="txtobs_mt" id="txtobs_mt"
									class="subactividad" value="<?php echo($ObservaMT);?>" />
									<fieldset>
										<legend>Observaciones de Monitoreo Técnico</legend>
										<span style="text-align: justify; color: #F00;">
          <?php echo(nl2br($ObservaMT));?>
          </span>
									</fieldset>
          <?php } ?>
        </td>
							</tr>
						</table>
					</div>

					<script language="javascript">
  function Guardar_MetasSubAct()
	{
	<?php $ObjSession->AuthorizedPage(); ?>

	 if( $('#cbopartida').val()=="" ) {alert("Seleccione la Partida a Registrar"); $('#cbopartida').focus(); return false;}
	 if( $('#um').val()=="" ) {alert("Ingrese Unidad de Medida"); $('#um').focus(); return false;}
	 if ( isNaN(parseFloat($('#txtmpar').val())) ) { alert($('<div></div>').html("Meta Proyectada Años Restantes no es válido").text()); $('#txtmpar').focus(); return false; }

	 var noError = true;
	 $('input[id^=anio_2_mes]').each(function(pIndex, pElement) {
	 	if (noError) {
	 		var aValue = $(this).val();
	 		if (aValue.trim() != "" && isNaN(parseFloat(aValue))) {
		 		alert($('<div></div>').html("Valor de Meta no es válido").text());
		 		$(pElement).focus();
		 		noError = false;
	 		}
	 	}
	 });
	 if (!noError) return false;

	 var BodyForm = $("#FormData .subactividad").serialize() ;
	 var sURL = "poa_tec_process.php?action=<?php echo(md5("ajax_sub_actividad_metas"));?>" ;
	 var req = Spry.Utils.loadURL("POST", sURL, true, SuccessMetasSubAct, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });

	 return false;
	}

	function SuccessMetasSubAct(req)
	{
		var respuesta = req.xhRequest.responseText;
		respuesta = respuesta.replace(/^\s*|\s*$/g,"");
		var ret = respuesta.substring(0,5);
		if(ret=="Exito")
		{
		 LoadSubActividades(true);
		 alert(respuesta.replace(ret,""));
		 spryPopupDialog01.displayPopupDialog(false);
		}
		else
		{  alert(respuesta); }
	}

	function TotalMetaMeses(anio)
	{
		var tot = 0 ;
		$("input[anio='"+anio+"']").each(function() {
	            tot += CNumber(this.value);
	           });

		var idsum = "#sum_"+anio;
		$(idsum).html(tot);
		$("#txtmta").val(tot);
		SumaTotalMeses();
	}
	function SumaTotalMeses()
	{
		var tot = 0 ;
		$("input[total='1']").each(function() {
	            tot += CNumber(this.value);
	           });
		$('#txtSumaTotalMeses').val(tot);
		TotalMetaProgramada();
	}
	function TotalMetaProgramada()
	{
		console.log("aaaaa");
		var ini = CNumber($('#txtmfi').attr("valor"));
		var d =  CNumber($('#txtmeaa').attr("valor"));
		var v = CNumber($('#txtmftv').attr("valor"));
		var e =  CNumber($('#txtmtpe').val());
		var f =  CNumber($("#txtmta").val());
		var g =  CNumber($("#txtmpar").val());
		var h = (d + f + g);
		var i = h - v ;
		$("#txtmprog").val(h);
		$("#txtmvar").val(i);

		console.log("eeee");
	}

	function CNumber(str)
	  {
		  var numero =0;
		  if (str !="" && str !=null)
		  { numero = parseFloat(str);}
		  if(isNaN(numero)) { numero=0;}
		 return numero;
	  }

  </script>

					<script>
  $('.subactividad:input[type="text"]').numeric().pasteNumeric();
  $('.subactividad:input[inactivo="1"]').css("background-color", "#eeeecc") ;
  $('.subactividad:input[inactivo="1"]').attr("disabled", "disabled") ;

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

