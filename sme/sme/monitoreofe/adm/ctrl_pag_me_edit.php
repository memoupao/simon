<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>
<?php

require_once (constant('PATH_CLASS') . "BLFE.class.php");
require_once (constant('PATH_CLASS') . "BLProyecto.class.php");
// require_once(constant('PATH_CLASS')."BLHardCode.class.php");

$HC = new HardCode();

$view = $objFunc->__GET('action');
$idProy = $objFunc->__Request('idProy');
$idVisita = $objFunc->__Request('idVisita');
$idAprueba = $objFunc->__Request('idAprob');

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
									<td width="9%"><button class="Button"
											onclick="GuardarAprobacion(); return false;" value="Guardar"
											<?php if($ObjSession->PerfilID!=$HC->GP && $ObjSession->PerfilID!=$HC->RA) {echo('disabled="disabled"');} ?>>Guardar
										</button></td>
									<td width="9%"><button class="Button"
											onclick="spryPopupDialog01.displayPopupDialog(false); return false;"
											value="Cancelar">Cancelar</button></td>
									<td width="8%">&nbsp;</td>
									<td width="47%" align="right"><?php echo($objFunc->SubTitle) ;?></td>
								</tr>
							</table>
						</div>
						<script src="../../../jquery.ui-1.5.2/jquery.numeric.js"
							type="text/javascript">></script>
						<table width="90%" border="0" cellpadding="0" cellspacing="0"
							style="width: 99%">
							<tr valign="baseline">
								<td width="790" height="82" colspan="2" align="left">
        <?php
        
        $objProy = new BLProyecto();
        $row = $objProy->ProyectoSeleccionar($idProy, $objProy->MaxVersion($idProy));
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
												value="<?php echo($idProy); ?>" /> <input name="t60_visita"
												type="hidden" class="apruebadesemb" id="t60_visita"
												size="20" value="<?php echo($idVisita); ?>" /> <input
												name="t60_pago" type="hidden" class="apruebadesemb"
												id="t60_pago" size="20" value="<?php echo($idAprueba); ?>" /></td>
										</tr>
										<tr valign="baseline">
											<td align="left" nowrap><strong>Fecha de Inicio</strong>:</td>
											<td align="left"><?php echo($row["ini"]);?></td>
											<td align="left"><strong>F. </strong><strong>Término:</strong></td>
											<td align="left" valign="middle"><?php echo($row["fin"]);?></td>
										</tr>
									</table>

								</td>
							</tr>
							<tr valign="baseline">
								<td height="29" colspan="2" align="left">
									<div class="TableGrid">
      <?php if($idAprueba==1) { ?>
      <table width="578" border="0" cellpadding="0" cellspacing="0">
											<tbody class="data">
												<tr>
													<td width="200" rowspan="2" align="center" valign="middle"
														nowrap="nowrap" bgcolor="#E9E9E9"><strong>Entidad Supervisora</strong></td>
													<td width="88" rowspan="2" align="center" valign="middle"
														bgcolor="#E9E9E9"><strong>Importe del Contrato <br />S/.
													</strong></td>
													<td width="44" rowspan="2" align="center" valign="middle"
														bgcolor="#E9E9E9"><strong>N&deg; de Visita a Ejecutar</strong></td>
													<td colspan="2" align="center" valign="middle"
														bgcolor="#E9E9E9"><strong> Primer Pago</strong></td>
												</tr>
												<tr>
													<td width="70" align="center" valign="middle"
														bgcolor="#E9E9E9"><strong>Importe de Visita<br />S/.
													</strong></td>
													<td width="64" align="center" valign="middle"
														bgcolor="#E9E9E9"><strong>Autorizado Si/ NO</strong></td>
												</tr>
											</tbody>
											<tbody class="data">
      <?php
        $objFE = new BLFE();
        $iRs = $objFE->ListadoControlPagoME("*", "*", $idProy);
        
        $RowIndex = 0;
        if ($iRs->num_rows > 0) {
            while ($rowVisita = mysqli_fetch_assoc($iRs)) {
                $montoVisita = $rowVisita['importe_visita01'];
                $aprueba = $rowVisita['visita01_vb'];
                ?>
      <tr class="RowData">
													<td height="30" align="left" valign="middle"><?php echo($rowVisita['inst_moni_sig']);?></td>
													<td align="right" valign="middle"><?php echo(number_format($rowVisita['costo_tot_visita'],2));?></td>
													<td align="center" valign="middle"><?php echo($rowVisita['nro_visita_ejec']);?></td>
													<td align="right" valign="middle"><?php echo( number_format($rowVisita['importe_visita01'],2));?></td>
													<td align="center" valign="middle" nowrap="nowrap"><?php if($rowVisita['visita01_vb']=='1') {echo("Si");} ?></td>
												</tr>
      
      <?php
                $RowIndex ++;
            }
            $iRs->free();
        }
        ?>

      </tbody>
										</table>
  	  <?php } ?>
      <?php if($idAprueba == 2) { ?>
  	  <table width="578" border="0" cellpadding="0" cellspacing="0">
											<tbody class="data">
												<tr>
													<td width="200" rowspan="2" align="center" valign="middle"
														nowrap="nowrap" bgcolor="#E9E9E9"><strong>Entidad Supervisora</strong></td>
													<td width="88" rowspan="2" align="center" valign="middle"
														bgcolor="#E9E9E9"><strong>Importe del Contrato <br />S/.
													</strong></td>
													<td width="44" rowspan="2" align="center" valign="middle"
														bgcolor="#E9E9E9"><strong>N&deg; de Visita a Ejecutar</strong></td>
													<td colspan="2" align="center" valign="middle"
														bgcolor="#E9E9E9"><strong>Segundo Pago</strong></td>
												</tr>
												<tr>
													<td width="71" align="center" valign="middle"
														bgcolor="#E9E9E9"><strong>Importe de Visita<br /> S/.
													</strong></td>
													<td width="63" align="center" valign="middle"
														bgcolor="#E9E9E9"><strong>Autorizado Si/ NO</strong></td>
												</tr>
											</tbody>
											<tbody class="data">
      <?php
        $objFE = new BLFE();
        $iRs = $objFE->ListadoControlPagoME("*", "*", $idProy);
        
        $RowIndex = 0;
        if ($iRs->num_rows > 0) {
            while ($rowVisita = mysqli_fetch_assoc($iRs)) {
                $montoVisita = $rowVisita['importe_visita02'];
                $aprueba = $rowVisita['visita02_vb'];
                ?>
      <tr class="RowData">
													<td height="30" align="left" valign="middle"><?php echo($rowVisita['inst_moni_sig']);?></td>
													<td align="right" valign="middle"><?php echo(number_format($rowVisita['costo_tot_visita'],2));?></td>
													<td align="center" valign="middle"><?php echo($rowVisita['nro_visita_ejec']);?></td>
													<td align="right" valign="middle"><?php echo( number_format($rowVisita['importe_visita02'],2));?></td>
													<td align="center" valign="middle" nowrap="nowrap"><?php if($rowVisita['visita02_vb']=='1') {echo("Si");} ?></td>
												</tr>
      
      <?php
                $RowIndex ++;
            }
            $iRs->free();
        }
        ?>

      </tbody>
										</table>
	  <?php } ?>
      </div>


								</td>
							</tr>
							<tr valign="baseline">
								<td height="34" colspan="2" align="left">
									<fieldset style="padding: 1px;">
										<legend>Autorización para el Desembolso</legend>
										<table width="100%" border="0" cellpadding="0" cellspacing="0"
											class="TableEditReg" style="padding: 1px;">
											<tr>
												<td width="63" align="center"><strong>Desemb. <br />Si / No
												</strong></td>
												<td width="114" align="center"><strong>Monto a Desembolsar</strong></td>
												<td width="105" align="center"><strong>Fecha de Aprobación</strong></td>
												<td width="278" align="center"><strong>
                <?php
                $rowAprob = $objFE->SeleccionarAprobacionPagoME($idProy, $idVisita, $idAprueba);
                
                if ($ObjSession->PerfilID == $HC->GP || $ObjSession->PerfilID == $HC->RA) {
                    $disbledMT = "";
                } else {
                    $disbledMT = "disabled";
                }
                ?>
                Observaciones</strong></td>
											</tr>
											<tr>
												<td align="center" valign="middle" nowrap="nowrap"><input
													name="chk_aprueba"
													<?php if($rowAprob["t60_apro_mt"]=='1'){echo("checked");}else{if($aprueba=='1' && isset($row)){echo("checked");}}?>
													type="checkbox" class="apruebadesemb" id="chk_aprueba"
													onclick="ApruebaDesembolso();" value="1"
													<?php echo($disbledMT)?> /> <br /></td>
												<td align="center" valign="middle"><input
													name="txt_mto_desemb" type="text" class="apruebadesemb"
													id="txt_mto_desemb" style="text-align: center" size="11"
													value="<?php echo($rowAprob["t31_mto_aprob"]);?>" /></td>
												<td align="center" valign="middle"><input
													name="txt_fec_aprob" type="text" disabled="disabled"
													class="apruebadesemb" id="txt_fec_aprob" size="12"
													value="<?php echo($rowAprob["t31_fec_aprob"]);?>" /></td>
												<td align="center"><textarea name="txt_obs_mt" style="width: 95%; resize: none;" 
														rows="2" class="apruebadesemb" id="txt_obs_mt"
														<?php echo($disbledMT)?>><?php echo($rowAprob["t31_obs_aprob"]);?></textarea></td>
											</tr>
										</table>
									</fieldset> <br />
								</td>
							</tr>
						</table>
					</div>


					<script language="javascript">
  
  function ApruebaDesembolso()
  {
	  var montodefault = "<?php if($rowAprob['t31_mto_aprob'] >0){echo($rowAprob['t31_mto_aprob']);}else{echo($montoVisita);} ?>";
	  var fechaAprob   = "<?php echo($rowAprob['t31_fec_aprob']);?>";
	  
	  if($("#chk_aprueba").attr('checked'))
	  {
		  $("#txt_mto_desemb").removeAttr('disabled');
		  $("#txt_mto_desemb").val(montodefault) ;
		  $("#txt_mto_desemb").val(montodefault) ;
	  }
	  else
	  {
		   $("#txt_mto_desemb").attr('disabled','disabled');
		   $("#txt_mto_desemb").val("0") ;
		   $("#txt_mto_desemb").val("") ;
	  }
	  
	  $('.apruebadesemb:input[disabled="disabled"]').css("background-color", "#eeeecc") ;
  }
  
  ApruebaDesembolso();
  
  function GuardarAprobacion()
	{
	<?php $ObjSession->AuthorizedPage(); ?>	
	
	 var aMtoDes = $('#txt_mto_desemb').val().trim();
	 
	 if ( aMtoDes != "" && isNaN(parseFloat(aMtoDes))){
 		alert($("<div></div>").html("Monto a Desembolsar no es válido.").text());
 		$('#$txt_mto_desemb').focus();
 		return false;
	 }
	 
	 var BodyForm = $("#FormData").serialize() ;
	 var sURL = "ctrl_pago_me_process.php?action=<?php echo(md5("guardar"));?>" ;
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

	$("#txt_mto_desemb").numeric();

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

