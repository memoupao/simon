<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>
<?php

require_once (constant('PATH_CLASS') . "BLFE.class.php");
require_once (constant('PATH_CLASS') . "BLProyecto.class.php");
// require_once(constant('PATH_CLASS')."BLHardCode.class.php");

$HC = new HardCode();

$view = $objFunc->__GET('action');
$idProy = $objFunc->__Request('idProy');
$Aprob = $objFunc->__Request('idAprob');
$TrimEjec = $objFunc->__Request('idTrimEjec');
$mtoplan = $objFunc->__Request('mtoplan');

$objFunc->SetSubTitle("CheckList para Aprobación del Primer Desembolsos");

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
 <script src="../../../jquery.ui-1.5.2/jquery.numeric.js"
						type="text/javascript"></script>
					<div style="border: solid 1px #D3D3D3;">
						<div id="toolbar" style="height: 4px;" class="BackColor">
							<table border="0" cellpadding="0" cellspacing="0">
								<tr>
									<td width="9%"><button class="Button"
											onclick="GuardarAprobacion(); return false;" value="Guardar"
											<?php if($ObjSession->PerfilID!=$HC->RA && $ObjSession->PerfilID!=$HC->GP) {echo('disabled="disabled"');} ?>>Guardar
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
							style="width: 99%">
							<tr valign="baseline">
								<td height="136" align="left">
        <?php
        $objFE = new BLFE();
        $iRs = $objFE->ListadoProyectos_Aprob_Primer_Desembolso('*', '*', $idProy);
        
        $row = mysqli_fetch_assoc($iRs);
        $iRs->free();
        
        if ($row["t59_id_aprob"] == '') {
            $montoaprobado = round($row['monto_plan_trim'], 2);
        }
        
        ?>
        <table style="max-width: 580px;" border="0" align="left"
										cellpadding="0" cellspacing="2">
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
										<table border="0" cellpadding="0" cellspacing="0">
											<tbody class="data">
												<tr>
													<td width="67" rowspan="2" align="center" valign="middle"
														bgcolor="#E9E9E9"><strong>Trimestre</strong></td>
													<td colspan="2" align="center" valign="middle"
														bgcolor="#E9E9E9"><strong>Desembolsos</strong></td>
													<td colspan="4" align="center" valign="middle"
														bgcolor="#E9E9E9"><strong>Condiciones para el Primer
															Desembolso</strong></td>
												</tr>
												<tr>
													<td width="67" align="center" valign="middle"
														bgcolor="#E9E9E9"><strong>Total Desem-bolsado</strong></td>
													<td width="99" align="center" valign="middle"
														bgcolor="#E9E9E9"><strong>Importe a Desembolsar Según
															Cronograma</strong></td>
													<td width="68" align="center" valign="middle"
														bgcolor="#E9E9E9"><strong>Apertura de Cuenta Bancaria</strong></td>
													<td width="56" align="center" valign="middle"
														bgcolor="#E9E9E9"><strong>Carta Fianza Vigente</strong></td>
													<td width="64" align="center" valign="middle"
														bgcolor="#E9E9E9"><strong>Convenio firmado </strong></td>
													<td width="64" align="center" valign="middle"
														bgcolor="#E9E9E9"><strong>Otros Documentos</strong></td>
												</tr>
											</tbody>
											<tbody class="data">
												<tr class="RowData">
													<td align="center" valign="middle">1er Trimestre</td>
													<td align="right" valign="middle"><?php echo( number_format($row['total_desembolsado'],2));?></td>
													<td align="right" valign="middle"><?php echo( number_format($row['monto_plan_trim'],2));?></td>
													<td align="center" valign="middle"><?php if($row['ctabancaria']=='1'){echo("Si");}else{echo("No");} ?> </td>
													<td align="center" valign="middle"><?php if($row['cartafianza']=='1'){echo("Si");}else{echo("No");} ?> </td>
													<td align="center" valign="middle"><?php if($row['conv_firma']=='1'){echo("Si");}else{echo("No");}  ?> </td>
													<td align="center" valign="middle"><?php if($row['otros_docum']=='1'){echo("Si");}else{echo("No");}  ?>  </td>
												</tr>
											</tbody>
											<tfoot>
											</tfoot>
										</table>
									</div>
								</td>
							</tr>
							<tr valign="baseline">
								<td width="542">
	<?php
$rowAPB = $objFE->Aprobacion_Primer_Desemb_Seleccionar($row['t59_id_aprob']);

if (count($rowAPB) > 0) {
    $montoaprobado = round($rowAPB['t59_mto_aprob'], 2);
}

?>
      
      <?php
    if ($ObjSession->PerfilID == $HC->GP || $ObjSession->PerfilID == $HC->RA) {
        $disbledMF = "";
    } else {
        $disbledMF = "disabled";
    }
    ?>
        <fieldset style="width: 400px; padding: 1px; font-size: 10px;">
										<legend>Aprobación Monitor Financiero</legend>
										<table border="0" cellpadding="0" cellspacing="0"
											class="TableEditReg" style="padding: 1px; height: 70px;">
											<tr>
												<td width="286">Constancia de Apertura de Cuenta Bancaria</td>
												<td width="84" align="center"><input name="chk_cta"
													type="checkbox" class="apruebadesemb" id="chk_cta"
													style="border: none;" onclick="ApruebaDesembolso();"
													value="1"
													<?php if($row['ctabancaria']=='1'){echo("checked=\"checked\"");} ?>
													disabled="disabled" /> <input name="chk_cta" type="hidden"
													class="apruebadesemb"
													value="<?php echo($row['ctabancaria']); ?>" /></td>
											</tr>
											<tr>
												<td width="286">Carta Fianza</td>
												<td width="84" align="center"><input name="chk_cf"
													type="checkbox" class="apruebadesemb" readonly="readonly"
													id="chk_cf" style="border: none;"
													onclick="ApruebaDesembolso();" value="1"
													<?php if($row['cartafianza']=='1'){echo("checked=\"checked\"");} ?>
													disabled="disabled" /> <input name="chk_cf" type="hidden"
													class="apruebadesemb"
													value="<?php echo($row['cartafianza']); ?>" /></td>
											</tr>
											<tr>
												<td width="286">Convenio firmado</td>
												<td width="84" align="center"><input name="chk_cvf"
													type="checkbox" class="apruebadesemb" readonly="readonly"
													id="chk_cvf" style="border: none;"
													onclick="ApruebaDesembolso();" value="1"
													<?php if($row['conv_firma']=='1'){echo("checked=\"checked\"");} ?>
													disabled="disabled" /> <input name="chk_cvf" type="hidden"
													class="apruebadesemb"
													value="<?php echo($row['conv_firma']); ?>" /></td>
											</tr>
											<tr>
												<td width="286">Otros documentos…</td>
												<td width="84" align="center"><input name="chk_od"
													type="checkbox" class="apruebadesemb" id="chk_od"
													style="border: none;" onclick="ApruebaDesembolso();"
													value="1"
													<?php if($rowAPB['t59_otros_docum']=='1'){echo("checked=\"checked\"");} ?>
													<?php echo($disbledMF);?> /></td>
											</tr>
										</table>
									</fieldset>
								</td>
							</tr>
							<tr valign="baseline">
								<td align="left">
									<fieldset style="padding: 1px;">
										<legend>Resultados</legend>
										<table width="553" border="0" class="TableEditReg"
											style="padding: 1px;">
											<tr>
												<td width="87" align="center"><strong>Desembolsar</strong></td>
												<td width="117" align="center"><strong>Monto a Desembolsar</strong></td>
												<td width="65" align="center"><strong>Fecha de Aprobación</strong></td>
												<td width="266" align="center"><strong>Observaciones</strong></td>
											</tr>
											<tr>
												<td align="center"><input name="chk_result_desemb"
													type="checkbox" class="apruebadesemb"
													id="chk_result_desemb" value="1" disabled="disabled" /> <br /></td>
												<td align="center"><input name="txt_mto_desemb" type="text"
													class="apruebadesemb" id="txt_mto_desemb"
													style="text-align: center" size="20"
													value="<?php echo($montoaprobado);?>" /></td>
												<td align="center"><input name="txt_fec_aprob" type="text"
													disabled="disabled" class="apruebadesemb"
													id="txt_fec_aprob" size="10"
													value="<?php echo($rowAPB["t59_fch_aprob"]);?>" /></td>
												<td align="center"><textarea name="txt_obs_mf" cols="45"
														rows="2" class="apruebadesemb" id="txt_obs_mf"
														<?php echo($disbledMF)?>><?php echo($rowAPB["t59_obs_aprob"]);?></textarea></td>
											</tr>
										</table>
									</fieldset>
								</td>
							</tr>
						</table>
					</div>


					<script language="javascript">
  function ApruebaDesembolso()
  {
	  if($("#chk_cta").attr('checked') && $("#chk_cf").attr('checked') && $("#chk_cvf").attr('checked') && $("#chk_od").attr('checked'))
	  {
		  $("#chk_result_desemb").attr('checked','checked');
		  $("#txt_mto_desemb").removeAttr('disabled');
	  }
	  else
	  {
		   $("#chk_result_desemb").removeAttr('checked');
		   $("#txt_mto_desemb").attr('disabled','disabled');
		   //$("#txt_mto_desemb").val('0') ;
	  }
	  
	  $('.apruebadesemb:input[disabled="disabled"]').css("background-color", "#eeeecc") ;
  }
  
  ApruebaDesembolso();
  
  function GuardarAprobacion()
	{
	<?php $ObjSession->AuthorizedPage('EDITAR'); ?>	
	
	 var BodyForm = $("#FormData").serialize() ;
	 if (BodyForm.indexOf("txt_mto_desemb") < 0) {
	 	BodyForm += "&txt_mto_desemb=" + $("#txt_mto_desemb").val();
	 }
	 console.log(BodyForm);
	 var sURL = "prim_desemb_process.php?action=<?php echo(md5("guardar"));?>" ;
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
	
	$("#txt_mto_desemb").numeric();
	
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

