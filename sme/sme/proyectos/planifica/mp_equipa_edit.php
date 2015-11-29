<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>
<?php

require (constant('PATH_CLASS') . "BLManejoProy.class.php");
require_once (constant('PATH_CLASS') . "BLProyecto.class.php");

$objMan = new BLManejoProy();
$objProy = new BLProyecto();
$HC = new HardCode();

$view = $objFunc->__GET('mode');
$idProy = $objFunc->__Request('idProy');
$idVersion = $objFunc->__Request('idVersion');
$idEqui = 0;

$row = 0;
if ($view == md5("ajax_edit")) {
    $objFunc->SetSubTitle("Equipamiento del Proyecto - Editando Registro");
    $idEqui = $objFunc->__Request('idEqui');
    $row = $objMan->Equipamiento_Seleccionar($idProy, $idVersion, $idEqui);
} else {
    $row = array(
        "t03_cant" => 1
    );
    $objFunc->SetSubTitle("Equipamiento del Proyecto - Nuevo Registro");
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
  <div style="width: 99%; border: solid 1px #D3D3D3;">
						<div id="toolbar" style="height: 4px;" class="BackColor">
							<table border="0" cellpadding="0" cellspacing="0">
								<tr>
									<td width="9%"><button class="Button"
											onclick="Guardar_Equipamiento(); return false;"
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
							<tr valign="bottom">
								<td height="17" valign="middle" nowrap>&nbsp;</td>
								<td colspan="5" align="left" valign="middle">&nbsp;</td>
							</tr>
							<tr valign="bottom">
								<td width="86" height="25" valign="middle" nowrap>Equipo</td>
								<td colspan="5" align="left" valign="middle"><input
									name="t03_id_equi" type="text" id="t03_id_equi"
									value="<?php echo($row["t03_id_equi"]);?>" size="1"
									maxlength="5" readonly="readonly" style="text-align: center" />
									<input name="t03_nom_equi" type="text" id="t03_nom_equi"
									value="<?php echo($row["t03_nom_equi"]);?>" size="70"
									maxlength="70" tabindex="0"></td>
							</tr>
							<tr valign="baseline">
								<td nowrap align="left">Unidad Medida</td>
								<td colspan="3" align="left"><input name="t03_um" type="text"
									id="t03_um" value="<?php echo($row["t03_um"]);?>" size="30"
									maxlength="30" style="text-align: center" /></td>
								<td width="78" align="left">&nbsp;</td>
								<td width="348" align="left">&nbsp;</td>
							</tr>
							<tr valign="baseline">
								<td height="38" align="left" valign="middle" nowrap>Costo
									Unitario</td>
								<td width="65" align="left" valign="middle"><input name="t03_cu"
									type="text" id="t03_cu" class="roundDec"
									value="<?php echo($row["t03_cu"]);?>" size="10" maxlength="10"
									style="text-align: center"
									onkeyup="CalcularCostoParcial(); CalcularPagoTotal();" /></td>
								<td width="53" align="left" valign="middle" nowrap="nowrap">Cantidad</td>
								<td width="55" align="left" valign="middle"><input
									name="t03_cant" type="text" id="t03_cant" class="roundDec"
									value="<?php echo($row["t03_cant"]);?>" size="10"
									maxlength="10" style="text-align: center"
									onkeyup="CalcularCostoParcial(); CalcularPagoTotal();" /></td>
								<td align="left" valign="middle" nowrap="nowrap">Costo Parcial</td>
								<td align="left" valign="middle"><input name="t03_costo"
									type="text" id="t03_costo" style="text-align: center"
									onkeyup="CalcularPagoTotal();"
									value="<?php echo($row["t03_costo"]);?>" size="20"
									maxlength="10" readonly="readonly" /></td>
							</tr>
							<tr valign="baseline">
								<td colspan="6" align="left" valign="top" nowrap
									style="padding: 0px;">
									<fieldset style="padding-left: 1px; padding-right: 0px;">
										<legend>Cronograma / Metas</legend>
										<div class="TableGrid">
            <?php
            $arrPeriodo = $objProy->ResultToArray($objProy->PeriodosxAnio($idProy, 1));
            ?>
              <table width="560" border="0" cellpadding="0"
												cellspacing="0">
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
														1 <br /> <font style="font-size: 9px;">(<?php echo($arrPeriodo[0]['nom_abrev']);?>) </font>
													</td>
													<td width="17" align="center" valign="middle"
														style="border: 1px #333 solid; background-color: #DBDEF9;">
														2 <br /> <font style="font-size: 9px;">(<?php echo($arrPeriodo[1]['nom_abrev']);?>) </font>
													</td>
													<td width="17" align="center" valign="middle"
														style="border: 1px #333 solid; background-color: #DBDEF9;">
														3 <br /> <font style="font-size: 9px;">(<?php echo($arrPeriodo[2]['nom_abrev']);?>) </font>
													</td>
													<td width="17" align="center" valign="middle"
														style="border: 1px #333 solid; background-color: #DBDEF9;">
														4 <br /> <font style="font-size: 9px;">(<?php echo($arrPeriodo[3]['nom_abrev']);?>) </font>
													</td>
													<td width="17" align="center" valign="middle"
														style="border: 1px #333 solid; background-color: #DBDEF9;">
														5 <br /> <font style="font-size: 9px;">(<?php echo($arrPeriodo[4]['nom_abrev']);?>) </font>
													</td>
													<td width="17" align="center" valign="middle"
														style="border: 1px #333 solid; background-color: #DBDEF9;">
														6 <br /> <font style="font-size: 9px;">(<?php echo($arrPeriodo[5]['nom_abrev']);?>) </font>
													</td>
													<td width="17" align="center" valign="middle"
														style="border: 1px #333 solid; background-color: #DBDEF9;">
														7 <br /> <font style="font-size: 9px;">(<?php echo($arrPeriodo[6]['nom_abrev']);?>) </font>
													</td>
													<td width="17" align="center" valign="middle"
														style="border: 1px #333 solid; background-color: #DBDEF9;">
														8 <br /> <font style="font-size: 9px;">(<?php echo($arrPeriodo[7]['nom_abrev']);?>) </font>
													</td>
													<td width="17" align="center" valign="middle"
														style="border: 1px #333 solid; background-color: #DBDEF9;">
														9 <br /> <font style="font-size: 9px;">(<?php echo($arrPeriodo[8]['nom_abrev']);?>) </font>
													</td>
													<td width="17" align="center" valign="middle"
														style="border: 1px #333 solid; background-color: #DBDEF9;">
														10 <br /> <font style="font-size: 9px;">(<?php echo($arrPeriodo[9]['nom_abrev']);?>) </font>
													</td>
													<td width="17" align="center" valign="middle"
														style="border: 1px #333 solid; background-color: #DBDEF9;">
														11 <br /> <font style="font-size: 9px;">(<?php echo($arrPeriodo[10]['nom_abrev']);?>) </font>
													</td>
													<td width="17" align="center" valign="middle"
														style="border: 1px #333 solid; background-color: #DBDEF9;">
														12 <br /> <font style="font-size: 9px;">(<?php echo($arrPeriodo[11]['nom_abrev']);?>) </font>
													</td>
												</tr>
												<tbody class="data" bgcolor="#FFFFFF">
		  <?php
    $AnioPOA = $objMan->GetAnioPOA($idProy, $idVersion);
    
    $sumaTotal = 0;
    $rsMetas = $objMan->Equipamiento_Listado_Metas($idProy, $idVersion, $idEqui);
    while ($rowMeta = mysqli_fetch_assoc($rsMetas)) {
        
        if ($AnioPOA > 0 && $AnioPOA != $rowMeta['t02_anio']) {
            $Disbled = "disabled=\"disabled\"";
        } else {
            $Disbled = "";
        }
        
        $NombMes = "t03_anio_" . $rowMeta['t02_anio'] . "_mes[]";
        $sumaTotal += $rowMeta["t03_tot_anio"];
        ?>
                  <tr class="RowData">
														<td align="left" nowrap="nowrap"><input name="t03_anios[]"
															type="hidden" id="t03_anios[]"
															value="<?php echo($rowMeta['t02_anio']);?>"
															<?php echo($Disbled);?> />
                      <?php echo($rowMeta['anio']); ?>
                    </td>
														<td align="center" valign="middle" style="padding: 1px;"><input
															name="<?php echo($NombMes);?>" type="text"
															id="<?php echo($NombMes);?>"
															onkeyup="TotalMetaMeses('<?php echo($rowMeta['t02_anio']);?>');"
															value="<?php echo($rowMeta["t03_mes1"]);?>" size="1"
															maxlength="5" anio='<?php echo($rowMeta['t02_anio']);?>'
															total='1' style="text-align: center; width: 30px;"
															<?php echo($Disbled);?>
															<?php if($arrPeriodo[0]['mes_ok']==0 && $Disbled=="") {echo("disabled=\"disabled\"");} ?>
															class="roundDec" /></td>
														<td align="center" valign="middle" style="padding: 1px;"><input
															name="<?php echo($NombMes);?>" type="text"
															id="<?php echo($NombMes);?>"
															onkeyup="TotalMetaMeses('<?php echo($rowMeta['t02_anio']);?>');"
															value="<?php echo($rowMeta["t03_mes2"]);?>" size="1"
															maxlength="5" anio='<?php echo($rowMeta['t02_anio']);?>'
															total='1' style="text-align: center; width: 30px;"
															<?php echo($Disbled);?>
															<?php if($arrPeriodo[1]['mes_ok']==0 && $Disbled=="") {echo("disabled=\"disabled\"");} ?>
															class="roundDec" /></td>
														<td align="center" valign="middle" style="padding: 1px;"><input
															name="<?php echo($NombMes);?>" type="text"
															id="<?php echo($NombMes);?>"
															onkeyup="TotalMetaMeses('<?php echo($rowMeta['t02_anio']);?>');"
															value="<?php echo($rowMeta["t03_mes3"]);?>" size="1"
															maxlength="5" anio='<?php echo($rowMeta['t02_anio']);?>'
															total='1' style="text-align: center; width: 30px;"
															<?php echo($Disbled);?>
															<?php if($arrPeriodo[2]['mes_ok']==0 && $Disbled=="") {echo("disabled=\"disabled\"");} ?>
															class="roundDec" /></td>
														<td align="center" valign="middle" style="padding: 1px;"><input
															name="<?php echo($NombMes);?>" type="text"
															id="<?php echo($NombMes);?>"
															onkeyup="TotalMetaMeses('<?php echo($rowMeta['t02_anio']);?>');"
															value="<?php echo($rowMeta["t03_mes4"]);?>" size="1"
															maxlength="5" anio='<?php echo($rowMeta['t02_anio']);?>'
															total='1' style="text-align: center; width: 30px;"
															<?php echo($Disbled);?>
															<?php if($arrPeriodo[3]['mes_ok']==0 && $Disbled=="") {echo("disabled=\"disabled\"");} ?>
															class="roundDec" /></td>
														<td align="center" valign="middle" style="padding: 1px;"><input
															name="<?php echo($NombMes);?>" type="text"
															id="<?php echo($NombMes);?>"
															onkeyup="TotalMetaMeses('<?php echo($rowMeta['t02_anio']);?>');"
															value="<?php echo($rowMeta["t03_mes5"]);?>" size="1"
															maxlength="5" anio='<?php echo($rowMeta['t02_anio']);?>'
															total='1' style="text-align: center; width: 30px;"
															<?php echo($Disbled);?>
															<?php if($arrPeriodo[4]['mes_ok']==0 && $Disbled=="") {echo("disabled=\"disabled\"");} ?>
															class="roundDec" /></td>
														<td align="center" valign="middle" style="padding: 1px;"><input
															name="<?php echo($NombMes);?>" type="text"
															id="<?php echo($NombMes);?>"
															onkeyup="TotalMetaMeses('<?php echo($rowMeta['t02_anio']);?>');"
															value="<?php echo($rowMeta["t03_mes6"]);?>" size="1"
															maxlength="5" anio='<?php echo($rowMeta['t02_anio']);?>'
															total='1' style="text-align: center; width: 30px;"
															<?php echo($Disbled);?>
															<?php if($arrPeriodo[5]['mes_ok']==0 && $Disbled=="") {echo("disabled=\"disabled\"");} ?>
															class="roundDec" /></td>
														<td align="center" valign="middle" style="padding: 1px;"><input
															name="<?php echo($NombMes);?>" type="text"
															id="<?php echo($NombMes);?>"
															onkeyup="TotalMetaMeses('<?php echo($rowMeta['t02_anio']);?>');"
															value="<?php echo($rowMeta["t03_mes7"]);?>" size="1"
															maxlength="5" anio='<?php echo($rowMeta['t02_anio']);?>'
															total='1' style="text-align: center; width: 30px;"
															<?php echo($Disbled);?>
															<?php if($arrPeriodo[6]['mes_ok']==0 && $Disbled=="") {echo("disabled=\"disabled\"");} ?>
															class="roundDec" /></td>
														<td align="center" valign="middle" style="padding: 1px;"><input
															name="<?php echo($NombMes);?>" type="text"
															id="<?php echo($NombMes);?>"
															onkeyup="TotalMetaMeses('<?php echo($rowMeta['t02_anio']);?>');"
															value="<?php echo($rowMeta["t03_mes8"]);?>" size="1"
															maxlength="5" anio='<?php echo($rowMeta['t02_anio']);?>'
															total='1' style="text-align: center; width: 30px;"
															<?php echo($Disbled);?>
															<?php if($arrPeriodo[7]['mes_ok']==0 && $Disbled=="") {echo("disabled=\"disabled\"");} ?>
															class="roundDec" /></td>
														<td align="center" valign="middle" style="padding: 1px;"><input
															name="<?php echo($NombMes);?>" type="text"
															id="<?php echo($NombMes);?>"
															onkeyup="TotalMetaMeses('<?php echo($rowMeta['t02_anio']);?>');"
															value="<?php echo($rowMeta["t03_mes9"]);?>" size="1"
															maxlength="5" anio='<?php echo($rowMeta['t02_anio']);?>'
															total='1' style="text-align: center; width: 30px;"
															<?php echo($Disbled);?>
															<?php if($arrPeriodo[8]['mes_ok']==0 && $Disbled=="") {echo("disabled=\"disabled\"");} ?>
															class="roundDec" /></td>
														<td align="center" valign="middle" style="padding: 1px;"><input
															name="<?php echo($NombMes);?>" type="text"
															id="<?php echo($NombMes);?>"
															onkeyup="TotalMetaMeses('<?php echo($rowMeta['t02_anio']);?>');"
															value="<?php echo($rowMeta["t03_mes10"]);?>" size="1"
															maxlength="5" anio='<?php echo($rowMeta['t02_anio']);?>'
															total='1' style="text-align: center; width: 30px;"
															<?php echo($Disbled);?>
															<?php if($arrPeriodo[9]['mes_ok']==0 && $Disbled=="")  {echo("disabled=\"disabled\"");} ?>
															class="roundDec" /></td>
														<td align="center" valign="middle" style="padding: 1px;"><input
															name="<?php echo($NombMes);?>" type="text"
															id="<?php echo($NombMes);?>"
															onkeyup="TotalMetaMeses('<?php echo($rowMeta['t02_anio']);?>');"
															value="<?php echo($rowMeta["t03_mes11"]);?>" size="1"
															maxlength="5" anio='<?php echo($rowMeta['t02_anio']);?>'
															total='1' style="text-align: center; width: 30px;"
															<?php echo($Disbled);?>
															<?php if($arrPeriodo[10]['mes_ok']==0 && $Disbled=="") {echo("disabled=\"disabled\"");} ?>
															class="roundDec" /></td>
														<td align="center" valign="middle" style="padding: 1px;"><input
															name="<?php echo($NombMes);?>" type="text"
															id="<?php echo($NombMes);?>"
															onkeyup="TotalMetaMeses('<?php echo($rowMeta['t02_anio']);?>');"
															value="<?php echo($rowMeta["t03_mes12"]);?>" size="1"
															maxlength="5" anio='<?php echo($rowMeta['t02_anio']);?>'
															total='1' style="text-align: center; width: 30px;"
															<?php echo($Disbled);?>
															<?php if($arrPeriodo[11]['mes_ok']==0 && $Disbled=="") {echo("disabled=\"disabled\"");} ?>
															class="roundDec" /></td>
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
														<th colspan="12" align="right"></th>
														<th align="center"><input type="text"
															id="txtSumaTotalMeses" name="txtSumaTotalMeses"
															value="<?php echo($sumaTotal);?>"
															style="width: 100%; background-color: #333; color: #FFF; border: none; text-align: center; font-weight: bold;"
															readonly="readonly" /> <input type="text" id="txtmta"
															name="txtmta" value="<?php echo($sumaTotal);?>"
															style="width: 100%; background-color: #333; color: #FFF; border: none; text-align: center; font-weight: bold; visibility: hidden"
															readonly="readonly" /> <input type="text" id="txtmpar"
															name="txtmpar" value="0"
															style="width: 100%; background-color: #333; color: #FFF; border: none; text-align: center; font-weight: bold; visibility: hidden;"
															readonly="readonly" /> <input type="text" id="txtmprog"
															name="txtmprog" value="0"
															style="width: 100%; background-color: #333; color: #FFF; border: none; text-align: center; font-weight: bold; visibility: hidden;"
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
											<td width="82%" align="center" nowrap="nowrap">&nbsp;</td>
											<td width="18%" align="center" nowrap="nowrap">&nbsp;</td>
										</tr>
										<tr>
											<td align="right"><strong>Costo Total</strong></td>
											<td align="center"><input name="t03_equi_gas_tot" type="text"
												id="t03_equi_gas_tot" value="<?php echo($row["t03_mta"]);?>"
												size="25" readonly="readonly" style="text-align: center" /></td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</div>

					<script language="javascript">
  	$(document).ready(function() {
  		bindRoundDecimals();
  	});

  function Guardar_Equipamiento()
	{
	<?php $ObjSession->AuthorizedPage(); ?>

	 if( $('#t03_nom_equi').val()=="" ) {alert("Ingrese Nombre del Bien o Equipo"); $('#t03_nom_equi').focus(); return false;}
	 if( $('#t03_um').val()=="" ) {alert("Ingrese Unidad de Medida"); $('#t03_um').focus(); return false;}
	 if( $('#t03_costo').val()=="" ) {alert("Ingrese Costo Unitario del Bien o Equipo"); $('#t03_costo').focus(); return false;}

	 var BodyForm = $("#FormData").serialize() ;
	 var sURL = "mp_equipa_process.php?action=<?php echo($view);?>" ;
	 var req = Spry.Utils.loadURL("POST", sURL, true, MySuccessGuardarEquipamiento, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });

	 return false;
	}

	function MySuccessGuardarEquipamiento(req)
	{
		var respuesta = req.xhRequest.responseText;
		respuesta = respuesta.replace(/^\s*|\s*$/g,"");
		var ret = respuesta.substring(0,5);
		if(ret=="Exito")
		{
		 LoadEquipamiento(true);
		 var idequi = respuesta.substring(0,7);
		 alert(respuesta.replace(idequi,""));
		 idequi = idequi.replace(ret,"");
		 //setTimeout("EditarEquipamiento("+idequi+");",5);
		 spryPopupDialog01.displayPopupDialog(false);
		}
		else
		{  alert(respuesta); }
	}

	function CalcularCostoParcial()
	{
		var cu = CNumber($('#t03_cu').val());
		var cant = CNumber($('#t03_cant').val());
		var costo= (cu * cant) ;
		$('#t03_costo').val(costo.toFixed(2));
	}

	function TotalMetaMeses(anio)
	{
		var tot = 0 ;
		$("input[anio='"+anio+"']").each(function() {
	            tot += CNumber(this.value);
	           });

		var idsum = "#sum_"+anio;
		$(idsum).html(tot);
		SumaTotalMeses();
	}
	function SumaTotalMeses()
	{
		var tot = 0 ;
		$("input[total='1']:not(input:disabled')").each(function() {
	            tot += CNumber(this.value);
	           });
		$('#txtSumaTotalMeses').val(tot);
		$('#txtmta').val(tot);
		CalcularPagoTotal();
	}
	function CalcularPagoTotal()
	{
		var num_mes = $('#txtSumaTotalMeses').val();
		var costo = CNumber($('#t03_costo').val());
		var GastoTotal= (costo * num_mes) ;
		$('#t03_equi_gas_tot').val(GastoTotal.toFixed(2));
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


  $("#t03_cant").numeric().pasteNumeric();
  $("#t03_cu").numeric().pasteNumeric();
  $("#t03_costo").numeric().pasteNumeric();
  $("input[total='1']").numeric().pasteNumeric();
  SumaTotalMeses();
  CalcularPagoTotal();
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

