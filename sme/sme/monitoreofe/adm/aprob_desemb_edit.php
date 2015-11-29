<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>
<?php

require_once (constant('PATH_CLASS') . "BLFE.class.php");
require_once (constant('PATH_CLASS') . "BLProyecto.class.php");
require_once (constant('PATH_CLASS') . "BLApprDesemb.class.php");
// require_once(constant('PATH_CLASS')."BLHardCode.class.php");

$HC = new HardCode();

$view = $objFunc->__GET('action');
$idProy = $objFunc->__Request('idProy');
$TrimEjec = $objFunc->__Request('idTrimEjec');
$mtoplan = $objFunc->__Request('mtoplan');

$objFunc->SetSubTitle("Aprobación de Desembolsos");

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
  <div style="border: solid 1px #D3D3D3;">
						<div id="toolbar" style="height: 4px;" class="BackColor">
							<table border="0" cellpadding="0" cellspacing="0">
								<tr>
									<td width="9%"><button class="Button accssCtrl" id='saveBtn'
											onclick="GuardarAprobacion(); return false;" value="Guardar">Guardar
										</button></td>
									<td width="9%"><button class="Button"
											onclick="spryPopupDialog01.displayPopupDialog(false); return false;"
											value="Cancelar">Cancelar</button></td>
									<td width="8%">&nbsp;</td>
									<td width="47%" align="right"><?php echo($objFunc->SubTitle) ;?></td>
								</tr>
							</table>
						</div>
						<table border="0" cellpadding="0" cellspacing="0"
							style="width: auto; margin: 0 auto;">
							<tr valign="baseline">
								<td height="136" colspan="2" align="left">
        <?php
        $objFE = new BLFE();
        $iRs = $objFE->ListadoProyectos_AprobDesembolsosParciales('*', '*', $idProy);
        $row = mysqli_fetch_assoc($iRs);
        $iRs->free();
        
        ?>
        <table style="max-width: 580px;" border="0" align="left"
										cellpadding="0" cellspacing="2" class="TableEditReg">
										<tr valign="baseline">
											<td width="115" align="left" nowrap><strong>Codigo Proyecto</strong>:
											</td>
											<td width="105" align="left"><?php echo($row["t02_cod_proy"]);?></td>
											<td width="63"><strong>Institución</strong>:</td>
											<td width="253"><?php echo($row["t01_sig_inst"]);?></td>
										</tr>
										<tr valign="baseline">
											<td align="left" nowrap><strong>Nombre Proyecto</strong>:</td>
											<td colspan="3" align="left"><?php echo($row["t02_nom_proy"]);?>
              <input name="t02_proyecto" type="hidden"
												class="apruebadesemb" id="t02_proyecto" size="20"
												value="<?php echo($idProy); ?>" /> <input
												name="t60_trimestre" type="hidden" class="apruebadesemb"
												id="t60_trimestre" size="20"
												value="<?php echo($TrimEjec); ?>" /> <input
												name="t60_montoplan" type="hidden" class="apruebadesemb"
												id="t60_montoplan" size="20"
												value="<?php echo($mtoplan); ?>" /></td>
										</tr>
										<tr valign="baseline">
											<td align="left" nowrap><strong>Fecha de Inicio</strong>:</td>
											<td align="left"><?php echo($row["t02_fch_ini"]);?></td>
											<td align="left"><strong>F. </strong><strong>Término:</strong></td>
											<td align="left" valign="middle"><?php echo($row["t02_fch_tre"]);?></td>
										</tr>
									</table> <br />
									<div class="TableGrid">
										<table width="570" border="0" cellpadding="0" cellspacing="0">
											<tbody class="data">
												<tr>
													<td width="67" rowspan="2" align="center" valign="middle"
														bgcolor="#E9E9E9"><strong>Periodo Aprobación</strong></td>
													<td width="84" rowspan="2" align="center" valign="middle"
														bgcolor="#E9E9E9"><strong>Presupuesto Fondoempleo</strong></td>
													<td width="80" rowspan="2" align="center" valign="middle"
														bgcolor="#E9E9E9"><strong>Total Ejecutado</strong></td>
													<td width="100" rowspan="2" align="center" valign="middle"
														bgcolor="#E9E9E9"><strong>Excedente por Ejecutar del
															último Informe</strong></td>
													<td colspan="4" align="center" valign="middle"
														bgcolor="#E9E9E9"><strong>Desembolsos</strong></td>
												</tr>
												<tr>
													<td width="94" align="center" valign="middle"
														bgcolor="#E9E9E9"><strong>Último Desembolso</strong></td>
													<td width="94" align="center" valign="middle"
														bgcolor="#E9E9E9"><strong>Total Desem-bolsado</strong></td>
													<td width="112" align="center" valign="middle"
														bgcolor="#E9E9E9"><strong>Importe a Desemb. Según Cron.</strong></td>
													<td width="112" align="center" valign="middle"
														bgcolor="#E9E9E9"><strong>Saldo por<br />Desem-<br />bolsar
													</strong></td>
												</tr>
											</tbody>
											<tbody class="data">
												<tr class="RowData">
													<td align="center" valign="middle"><?php echo("Trimestre ".$TrimEjec );?></td>
													<td align="right" valign="middle"><?php echo( number_format($row['total_aporte_fe'],2));?></td>
													<td align="right" valign="middle" nowrap="nowrap"><?php echo( number_format($row['total_ejecutado'],2));?></td>
													<td align="right" valign="middle"><?php echo( number_format($row['excedente_ejecutar'],2));?></td>
													<td align="right" valign="middle"><?php echo(number_format($row['ultimo_desembolso'],2)); ?></td>
													<td align="right" valign="middle"><?php echo( number_format($row['total_desembolsado'],2));?></td>
													<td align="right" valign="middle"><?php echo( number_format($row['monto_plan_trim'],2));?></td>
													<td align="right" valign="middle"><?php echo( number_format($row['monto_plan_trim'] - $row['mto_desembolsado'],2));?></td>
												</tr>
											</tbody>
											<tfoot>
											</tfoot>
										</table>
									</div>
           <?php
        $porc = (($row['excedente_ejecutar'] * 100) / $row['ultimo_desembolso']);
        if ($porc > 30) {
            $msg_alerta = "Excedente por ejecutar no debe ser mayor al 30% del importe del Ultimo desembolso. Se Tiene el " . number_format($porc, 2) . "%";
        }
        ?>
           <span
									style="color: #F00; font-weight: bold; font-size: 10px;"><?php echo($msg_alerta);?></span>
								</td>
							</tr>

<?php
// Retrieves Partial Approbals from DB.
// On one variable stores the LIST of partial approbals
// and on the other the latest/current partial approbal.

$aBloApprobals = new BLApprDesemb();
$aSaldoAppr = null;
$aMtoAprobAcum = 0;
list ($aParList, $aCurrApp) = $aBloApprobals->Aprobacion_Desemb_Parciales($idProy, $TrimEjec);
if (! $aCurrApp) {
    $aCurrApp = array(
        't60_id_aprob' => count($aParList) ? $aParList[0]['t60_id_aprob'] : 0,
        't60_nro_aprob' => 0,
        't60_apro_mt' => '0',
        't60_obs_mt' => '',
        't60_apro_mf' => '0',
        't60_obs_mf' => '',
        't60_apro_cmt' => '0',
        't60_obs_cmt' => '',
        't60_apro_cmf' => '0',
        't60_obs_cmf' => ''
    );
}

?>
<?php

if ($aParList && isset($aParList) && is_array($aParList)) {
    ?>
<tr valign="baseline" class='aprobDesembLst'>
								<td>
									<div class='apprLstDiv'>
										<table>
											<thead>
												<tr>
													<th>Monto</th>
													<th>App. GP</th>
													<?php /* ?>
													<th>App. MF</th>
													<?php */ ?>
													<th>App. RA</th>
													<?php /*?>
													<th>App. CMF</th>
													<?php */ ?>
													<th>Aprobado</th>
												</tr>
											</thead>
											<tbody>
				<?php
    foreach ($aParList as $aAppRow) {
        $aMtoAprobAcum += $aAppRow['t60_fch_aprob'] ? $aAppRow['t60_mto_par_aprob'] : 0;
        //$aFlgAprob = $aAppRow['t60_apro_cmt'] == '1' && $aAppRow['t60_apro_cmf'] == '1';
        $aFlgAprob = $aAppRow['t60_apro_cmt'] == '1';
        ?>
					<tr>
													<td><?php echo number_format($aAppRow['t60_mto_par_aprob'], 2); ?></td>
													<td><?php echo $aAppRow['t60_fch_apro_mt']; ?></td>
													<?php /*?>
													<td><?php echo $aAppRow['t60_fch_apro_mf']; ?></td>
													<?php */?>
													<td><?php echo $aAppRow['t60_fch_apro_cmt']; ?></td>
													<?php /*?>
													<td><?php echo $aAppRow['t60_fch_apro_cmf']; ?></td>
													<?php */?>
													<td><input type='checkbox' disabled='disabled'
														<?php echo ($aFlgAprob ? 'checked' : ''); ?> /></td>
												</tr>
				<?php
    } // foreach
    
    if (count($aParList) == 3 || (count($aParList) == 2 && $aCurrApp['t60_nro_aprob'] == '0')) {
        $aSaldoAppr = $row['monto_plan_trim'] - $aMtoAprobAcum;
        $aCurrApp['t60_nro_aprob'] = 3;
    }
    if ($aMtoAprobAcum >= $row['monto_plan_trim'] || $view == md5("ajax_view"))
        $aCurrApp = $aParList[count($aParList) - 1];
    ?>
				</tbody>
										</table>
									</div>
								</td>
							</tr>
<?php
} // if
?>

<tr valign="baseline" class='aprobDesemb'>
								<td>
									<div id='AprobListDiv' class='AprobItemDiv'>
										<fieldset id='histAprob'>
											<legend>Aprobaciones</legend>
				<?php
    if ($aCurrApp['t60_id_aprob'] != 0) {
        ?>
				<table>
												<tbody>
						<?php
        if ($aCurrApp['t60_apro_mt'] == '1' || $ObjSession->PerfilID == $HC->GP) {
            ?>
						<tr>
														<td><a href="javascript:void(0)" class='mtAppLnk'>Gestor de Proyectos</a></td>
														<td><a href="javascript:void(0)" class='mtAppLnk'><?php echo $aCurrApp['t60_fch_apro_mt']; ?></a></td>
													</tr>
						<?php
        }
        /*if ($aCurrApp['t60_apro_mf'] == '1' || $ObjSession->PerfilID == $HC->MF) {
            ?>
						<tr>
														<td><a href="javascript:void(0)" class='mfAppLnk'>Monitor
																Financiero</a></td>
														<td><a href="javascript:void(0)" class='mfAppLnk'><?php echo $aCurrApp['t60_fch_apro_mf']; ?></a></td>
													</tr>
						<?php
        } */ 
        if ($aCurrApp['t60_apro_cmt'] == '1' || $ObjSession->PerfilID == $HC->RA) {
            ?>
						<tr>
														<td><a href="javascript:void(0)" class='cmtAppLnk'>Responsable de Area</a></td>
														<td><a href="javascript:void(0)" class='cmtAppLnk'><?php echo $aCurrApp['t60_fch_apro_cmt']; ?></a></td>
													</tr>
						<?php
        }
        /*if ($aCurrApp['t60_apro_cmf'] == '1' || $ObjSession->PerfilID == $HC->CMF) {
            ?>
						<tr>
														<td><a href="javascript:void(0)" class='cmfAppLnk'>Coordinador
																de Monitoreo Financiero</a></td>
														<td><a href="javascript:void(0)" class='cmfAppLnk'><?php echo $aCurrApp['t60_fch_apro_cmf']; ?></a></td>
													</tr>
						<?php
        }*/ 
        ?>
					</tbody>
											</table>
				<?php
    }     // if
    else {
        ?>
				<div id='noAppRecordsDiv'>No hay registro de aprobaciones para ésta
												Autorización de Desembolso.</div>
				<?php
    } // else
    ?>
			</fieldset>
									</div>

									<div id='AprobMtDiv' class='AprobItemDiv'>
										<fieldset>
											<legend>Gestor de Proyectos</legend>
											<table cellspacing="0" cellpadding="0" border="0"
												class="TableEditReg">
												<tbody>
													<tr>
														<td style="width: 80px;"><input type="checkbox"
															name="chk_aprueba_mt" class="apruebadesemb accssCtrl"
															id="chk_aprueba_mt" value="1"> <label
															for="chk_aprueba_mt">V&ordm;B&ordm;</label></td>
														<td style='width: auto'><label for="txt_obs_mt" style="">
																Observaciones</label><br /> <textarea id="txt_obs_mt"
																class="apruebadesemb accssCtrl" name="txt_obs_mt"></textarea>
														</td>
													</tr>
												</tbody>
											</table>
										</fieldset>
									</div>
									<?php /* ?>
									<div id='AprobMfDiv' class='AprobItemDiv'>
										<fieldset>
											<legend>Monitor Financiero</legend>
											<table cellspacing="0" cellpadding="0" border="0"
												class="TableEditReg">
												<tbody>
													<tr>
														<td style="width: 80px;"><input type="checkbox"
															name="chk_aprueba_mf" class="apruebadesemb accssCtrl"
															id="chk_aprueba_mf" value="1"> <label
															for="chk_aprueba_mf">V&ordm;B&ordm;</label></td>
														<td style='width: auto'><label for="txt_obs_mf" style="">
																Observaciones</label><br /> <textarea id="txt_obs_mf"
																class="apruebadesemb accssCtrl" name="txt_obs_mf"></textarea>
														</td>
													</tr>
												</tbody>
											</table>
										</fieldset>
									</div>
									<?php */ ?>
									<div id='AprobCmtDiv' class='AprobItemDiv'>
										<fieldset>
											<legend>Responsable de Area</legend>
											<table cellspacing="0" cellpadding="0" border="0"
												class="TableEditReg">
												<tbody>
													<tr>
														<td style="width: 80px;"><input type="checkbox"
															name="chk_aprueba_cmt" class="apruebadesemb accssCtrl"
															id="chk_aprueba_cmt" value="1"> <label
															for="chk_aprueba_cmt">Aprobar</label></td>
														<td style='width: auto'><label for="txt_obs_cmt" style="">
																Observaciones</label><br /> <textarea id="txt_obs_cmt"
																class="apruebadesemb accssCtrl" name="txt_obs_cmt"></textarea>
														</td>
													</tr>
												</tbody>
											</table>
										</fieldset>
									</div>

									<?php /* ?>
									<div id='AprobCmfDiv' class='AprobItemDiv'>
										<fieldset>
											<legend>Coordinador de Monitoreo Financiero</legend>
											<table cellspacing="0" cellpadding="0" border="0"
												class="TableEditReg">
												<tbody>
													<tr>
														<td style="width: 80px;"><input type="checkbox"
															name="chk_aprueba_cmf" class="apruebadesemb accssCtrl"
															id="chk_aprueba_cmf" value="1"> <label
															for="chk_aprueba_cmf">Aprobar</label></td>
														<td style='width: auto'><label for="txt_obs_cmf" style="">
																Observaciones</label><br /> <textarea id="txt_obs_cmf"
																class="apruebadesemb accssCtrl" name="txt_obs_cmf"></textarea>
														</td>
													</tr>
												</tbody>
											</table>
										</fieldset>
									</div>
									<?php */ ?>
								</td>
							</tr>
							<tr valign="baseline">
								<td height="34" colspan="2" align="left" nowrap="nowrap">
									<fieldset style="padding: 1px; width: 565px;">
										<legend>Resultados</legend>
										<table width="473" border="0" class="TableEditReg"
											style="padding: 1px; margin: 0 auto;">
											<tr>
												<td width="126" align="center"><strong>Desembolsar</strong></td>
												<td width="172" align="center"><strong>Monto a Desembolsar</strong></td>
												<td width="161" align="center"><strong>Fecha de Aprobación</strong></td>
											</tr>
											<tr>
												<td align="center"><input name="chk_result_desemb"
													type="checkbox" class="apruebadesemb"
													id="chk_result_desemb" value="1" disabled="disabled" /><br />
												</td>
												<td align="center"><input name="txt_mto_desemb" type="text"
													class="apruebadesemb accssCtrl" id="txt_mto_desemb"
													style="text-align: center" size="25"
													value="<?php echo number_format(isset($aSaldoAppr) && count($aParList) == 3 ? $aSaldoAppr : $aCurrApp["t60_mto_par_aprob"], 2);?>" />
												</td>
												<td align="center"><input name="txt_fec_aprob" type="text"
													disabled="disabled" class="apruebadesemb"
													id="txt_fec_aprob" size="20"
													value="<?php echo($aCurrApp["t60_fch_aprob"]);?>" /></td>
											</tr>
										</table>
									</fieldset>
								</td>
							</tr>
						</table>
					</div>
					<input type='hidden' name='t60_id_aprob' id='t60_id_aprob'
						value='<?php echo $aCurrApp['t60_id_aprob']?>' /> <input
						type='hidden' name='t60_nro_aprob' id='t60_nro_aprob'
						value='<?php echo $aCurrApp['t60_nro_aprob']; ?>' /> <input
						type='hidden' name='t60_mto_par_aprob' id='t60_mto_par_aprob'
						value='<?php echo round(isset($aSaldoAppr) ? $aSaldoAppr : $aCurrApp["t60_mto_par_aprob"], 2);?>' />
					<input type='hidden' name='monto_desemb_acum'
						id='monto_desemb_acum' value='<?php echo $aMtoAprobAcum; ?>' /> <input
						type='hidden' name='monto_plan_trim' id='monto_plan_trim'
						value='<?php echo $row['monto_plan_trim']; ?>' />
  
  <?php
/*$mtRoleId = $HC->MT;
$mfRoleId = $HC->MF; */
$mtRoleId = $HC->GP;
$mfRoleId = $HC->GP; 
/*$cmtRoleId = $HC->CMT;
$cmfRoleId = $HC->CMF; */
$cmtRoleId =  $HC->RA;
$cmfRoleId =  $HC->RA;



$usrRoleId = $ObjSession->PerfilID;
?>
  
 <script type="text/javascript">
 	$('#histAprob table tbody tr:odd').css('background-color', '#E0ECF8');
 	$('.accssCtrl').attr('disabled', 'disabled');
 	var aUsrProfile	= '<?php echo $usrRoleId; ?>';
 	var aFlgDesemb	= '<?php echo $aCurrApp['t60_flg_desemb']; ?>';
 	var aFlgSaldo	= parseFloat($('#monto_desemb_acum').val()) < parseFloat($('#monto_plan_trim').val());
 	/*var aAccss		= {	'mt'  : '<?php echo $mtRoleId; ?>', 'mf'  : '<?php echo $mfRoleId; ?>',
 						'cmt' : '<?php echo $cmtRoleId; ?>','cmf' : '<?php echo $cmfRoleId; ?>'};  */
	var aAccss		= {	'mt'  : '<?php echo $mtRoleId; ?>', 'cmt' : '<?php echo $cmtRoleId; ?>'};
 	
 	var aCurAppDiv = $('#AprobMtDiv');
 	/*var aAppList = {'mt'  : {'app': '<?php echo $aCurrApp{'t60_apro_mt'}; ?>', 'obs': '<?php echo $aCurrApp{'t60_obs_mt'}; ?>'},
					'mf'  : {'app': '<?php echo $aCurrApp{'t60_apro_mf'}; ?>', 'obs': '<?php echo $aCurrApp{'t60_obs_mf'}; ?>'},
					'cmt' : {'app': '<?php echo $aCurrApp{'t60_apro_cmt'}; ?>','obs': '<?php echo $aCurrApp{'t60_obs_cmt'}; ?>'},
					'cmf' : {'app': '<?php echo $aCurrApp{'t60_apro_cmf'}; ?>','obs': '<?php echo $aCurrApp{'t60_obs_cmf'}; ?>'}};
	var aControls ={'mt'  : {'chk': '#chk_aprueba_mt',  'txt':'#txt_obs_mt',  'div':'#AprobMtDiv',  'lnk':'.mtAppLnk'},
					'mf'  : {'chk': '#chk_aprueba_mf',  'txt':'#txt_obs_mf',  'div':'#AprobMfDiv',  'lnk':'.mfAppLnk'},
					'cmt' : {'chk': '#chk_aprueba_cmt', 'txt':'#txt_obs_cmt', 'div':'#AprobCmtDiv', 'lnk':'.cmtAppLnk'},
					'cmf' : {'chk': '#chk_aprueba_cmf', 'txt':'#txt_obs_cmf', 'div':'#AprobCmfDiv', 'lnk':'.cmfAppLnk'}};
	*/

	var aAppList = {'mt'  : {'app': '<?php echo $aCurrApp{'t60_apro_mt'}; ?>', 'obs': '<?php echo $aCurrApp{'t60_obs_mt'}; ?>'},			
			'cmt' : {'app': '<?php echo $aCurrApp{'t60_apro_cmt'}; ?>','obs': '<?php echo $aCurrApp{'t60_obs_cmt'}; ?>'}};
	var aControls = {'mt'  : {'chk': '#chk_aprueba_mt',  'txt':'#txt_obs_mt',  'div':'#AprobMtDiv',  'lnk':'.mtAppLnk'},			
			'cmt' : {'chk': '#chk_aprueba_cmt', 'txt':'#txt_obs_cmt', 'div':'#AprobCmtDiv', 'lnk':'.cmtAppLnk'}};

			
	$.each(aAppList, function(pIdx, pVal){
		if (pVal.app == '1') {
			$(aControls[pIdx].chk).attr('checked', 'checked');
			$(aControls[pIdx].txt).val(pVal.obs);
			aCurAppDiv = $(aControls[pIdx].div);
		}
		$(aControls[pIdx].lnk).click(function(pEvent){
			var aDiv = aControls[pIdx].div;
			$(getCurAppDiv()).fadeOut('fast', function(){
				$(aDiv).fadeIn('fast');
			});
			setCurAppDiv(aDiv);
		});
	});
	
	$('#txt_mto_desemb').focusout(function(pEvent){
		var aElem = $(pEvent.target);
		aElem.val(parseFloat(aElem.val().replace(/[^\d.]/g, '')).toFixed(2));
	});
	
	
 	if (aFlgDesemb != '1') {
	 	switch (aUsrProfile) {
	 		case aAccss.mt:	if (aAppList.mt.app != '1' && aFlgSaldo)
	 							$('#saveBtn, #chk_aprueba_mt, #txt_obs_mt').removeAttr('disabled');
	 						aCurAppDiv = $(aControls.mt.div);
	 						break;
	 		case aAccss.mf: if (aAppList.mf.app != '1' && aFlgSaldo) {
	 							$('#saveBtn, #chk_aprueba_mf, #txt_obs_mf').removeAttr('disabled');
	 							if ($('#t60_nro_aprob').val() != 3)
	 								$('#txt_mto_desemb').removeAttr('disabled');
	 						}
	 						aCurAppDiv = $(aControls.mf.div);
	 						break;
	 		case aAccss.cmt:if (aAppList.cmt.app != '1' && aFlgSaldo)
	 							$('#saveBtn, #chk_aprueba_cmt, #txt_obs_cmt').removeAttr('disabled');
	 						aCurAppDiv = $(aControls.cmt.div);
	 						break;
	 		/*case aAccss.cmf:if (aAppList.cmf.app != '1' && aFlgSaldo) {
	 							console.log("aAppList.cmf.app: " + aAppList.cmf.app);
	 							console.log("aFlgSaldo: " + aFlgSaldo);
	 							$('#saveBtn, #chk_aprueba_cmf, #txt_obs_cmf').removeAttr('disabled');
	 							if ($('#t60_nro_aprob').val() != 3)
	 								$('#txt_mto_desemb').removeAttr('disabled');
					 		}
					 		aCurAppDiv = $(aControls.cmf.div);
	 						break;
	 						*/
	 		case aAccss.adm: $('.accssCtrl').removeAttr('disabled');
	 						break;
	 	}
 	}
 	
 	aCurAppDiv.show();

 	function getCurAppDiv() { return aCurAppDiv; }
 	function setCurAppDiv(pDiv) { aCurAppDiv = pDiv; }
 	
 	$('#chk_aprueba_cmt, #chk_aprueba_cmf').click(setResultDesembChk);
 	
 	function setResultDesembChk()
 	{
		if ($('#chk_aprueba_cmt').attr('checked') && $('#chk_aprueba_cmf').attr('checked')) {
			$("#chk_result_desemb").attr('checked','checked');
		}
		else {
			$("#chk_result_desemb").removeAttr('checked');
		}
 	}
 	
 	setResultDesembChk();
 	
 </script>

					<script language="javascript">
//  
//  function ApruebaDesembolso()
//  {
//	  if($("#chk_aprueba_cmt").attr('checked') && $("#chk_aprueba_cmf").attr('checked'))
//	  {
//		  $("#chk_result_desemb").attr('checked','checked');
//		  $("#txt_mto_desemb").removeAttr('disabled');
//	  }
//	  else
//	  {
//		   $("#chk_result_desemb").removeAttr('checked');
//		   $("#txt_mto_desemb").attr('disabled','disabled');
//		   $("#txt_mto_desemb").val('0') ;
//	  }
//	  
//	  $('.apruebadesemb:input[disabled="disabled"]').css("background-color", "#eeeecc") ;
//  }
  
 // ApruebaDesembolso();
  
  function GuardarAprobacion()
	{
	<?php $ObjSession->AuthorizedPage(); ?>	
	
	 var BodyForm = $("#FormData").serialize() ;
	 var sURL = "aprob_desemb_process.php?action=<?php echo(md5("guardar"));?>" ;
	 console.log('BodyForm: ' + BodyForm);
	 console.log('sURL: ' + sURL);
	 var req = Spry.Utils.loadURL("POST", sURL, true, MySuccessGuardarAprobacion, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCall });

	 return false;
	}
	
	function MySuccessGuardarAprobacion(req)
	{
		var respuesta = req.xhRequest.responseText;
		respuesta = respuesta.replace(/^\s*|\s*$/g,"");
		var ret = respuesta.substring(0,5);
		if(ret=="Exito")
		{
		 LoadDataGrid();	
		 alert(respuesta.replace(ret,""));
		 spryPopupDialog01.displayPopupDialog(false);
		}
		else
		{  alert(respuesta); }
	}
	
	$('.apruebadesemb:input[disabled="disabled"]').css("background-color", "#eeeecc") ;


<?php if($view==md5("ajax_view")) { ?>
$("#FormData .apruebadesemb").attr("disabled","disabled") ;
$("#FormData .Button").attr("disabled","disabled") ;
<?php  } ?>

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

<script type='text/javascript'>
	/* Fixes height of the Dialog */
	var h1 = $('#popupText').height();
	var h2 = $('.popupContent').height();
	if (h1 > h2) {
	    var h4 = $('.popupBox').height();
	    var t1 = $('.popupBox').css('top');
	    var h3 = h1-h2;
	    $('.popupContent').height(h1);
	    $('.popupBox').css({'height':(h4 + h3), 'top':(t1 - h3)});
	}
</script>

