<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>
<?php

require (constant('PATH_CLASS') . "BLManejoProy.class.php");
require_once (constant('PATH_CLASS') . "BLProyecto.class.php");
require (constant('PATH_CLASS') . "BLTablasAux.class.php");

$objMan = new BLManejoProy();
$objProy = new BLProyecto();
$HC = new HardCode();

$view = $objFunc->__GET('mode');
$idProy = $objFunc->__Request('idProy');
$idVersion = $objFunc->__Request('idVersion');
$Partida = 0;

$row = 0;
if ($view == md5("ajax_edit")) {
    $objFunc->SetSubTitle("Gastos de Funcionamiento - Editando Registro");
    $Partida = $objFunc->__Request('idPartida');
    $row = $objMan->GastFunc_Seleccionar($idProy, $idVersion, $Partida);
} else {
    $row = 0;
    $objFunc->SetSubTitle("Gastos de Funcionamiento - Nuevo Registro");
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
											onclick="Guardar_GastFunc(); return false;" value="Guardar">Guardar
										</button></td>
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
								<td width="112" height="25" valign="middle" nowrap>Partida</td>
								<td colspan="5" align="left" valign="middle"><select
									name="cbopartida" id="cbopartida" style="width: 270px;">
										<option value="" selected="selected"></option>
		<?php
$objTablas = new BLTablasAux();
$Rs = $objTablas->TipoPartidasMP();
$objFunc->llenarCombo($Rs, "codigo", "descripcion", $row['t03_partida']);
$objTablas = NULL;
?>

        </select> <input name="t03_partida" type="hidden"
									id="t03_partida" value="<?php echo($row["t03_partida"]);?>"
									size="1" maxlength="10" style="text-align: center" /></td>
							</tr>
							<tr valign="baseline">
								<td height="33" align="left" nowrap>Unidad Medida</td>
								<td width="129" align="left"><input name="t03_um" type="text"
									id="t03_um" value="<?php echo($row["t03_um"]);?>" size="20"
									maxlength="30" style="text-align: center" /></td>
								<td width="128" align="left" nowrap="nowrap">&nbsp;</td>
								<td width="151" align="left">&nbsp;</td>
								<td width="63" align="left">&nbsp;</td>
								<td width="102" align="left">&nbsp;</td>
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
            $rsMetas = $objMan->GastFunc_Listado_Metas($idProy, $idVersion, $Partida);
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
															maxlength="10" anio='<?php echo($rowMeta['t02_anio']);?>'
															total='1' style="text-align: center; width: 30px;"
															<?php echo($Disbled);?>
															<?php if($arrPeriodo[0]['mes_ok']==0 && $Disbled=="") {echo("disabled=\"disabled\"");} ?>
															class="roundDec" /></td>
														<td align="center" valign="middle" style="padding: 1px;"><input
															name="<?php echo($NombMes);?>" type="text"
															id="<?php echo($NombMes);?>"
															onkeyup="TotalMetaMeses('<?php echo($rowMeta['t02_anio']);?>');"
															value="<?php echo($rowMeta["t03_mes2"]);?>" size="1"
															maxlength="10" anio='<?php echo($rowMeta['t02_anio']);?>'
															total='1' style="text-align: center; width: 30px;"
															<?php echo($Disbled);?>
															<?php if($arrPeriodo[1]['mes_ok']==0 && $Disbled=="") {echo("disabled=\"disabled\"");} ?>
															class="roundDec" /></td>
														<td align="center" valign="middle" style="padding: 1px;"><input
															name="<?php echo($NombMes);?>" type="text"
															id="<?php echo($NombMes);?>"
															onkeyup="TotalMetaMeses('<?php echo($rowMeta['t02_anio']);?>');"
															value="<?php echo($rowMeta["t03_mes3"]);?>" size="1"
															maxlength="10" anio='<?php echo($rowMeta['t02_anio']);?>'
															total='1' style="text-align: center; width: 30px;"
															<?php echo($Disbled);?>
															<?php if($arrPeriodo[2]['mes_ok']==0 && $Disbled=="") {echo("disabled=\"disabled\"");} ?>
															class="roundDec" /></td>
														<td align="center" valign="middle" style="padding: 1px;"><input
															name="<?php echo($NombMes);?>" type="text"
															id="<?php echo($NombMes);?>"
															onkeyup="TotalMetaMeses('<?php echo($rowMeta['t02_anio']);?>');"
															value="<?php echo($rowMeta["t03_mes4"]);?>" size="1"
															maxlength="10" anio='<?php echo($rowMeta['t02_anio']);?>'
															total='1' style="text-align: center; width: 30px;"
															<?php echo($Disbled);?>
															<?php if($arrPeriodo[3]['mes_ok']==0 && $Disbled=="") {echo("disabled=\"disabled\"");} ?>
															class="roundDec" /></td>
														<td align="center" valign="middle" style="padding: 1px;"><input
															name="<?php echo($NombMes);?>" type="text"
															id="<?php echo($NombMes);?>"
															onkeyup="TotalMetaMeses('<?php echo($rowMeta['t02_anio']);?>');"
															value="<?php echo($rowMeta["t03_mes5"]);?>" size="1"
															maxlength="10" anio='<?php echo($rowMeta['t02_anio']);?>'
															total='1' style="text-align: center; width: 30px;"
															<?php echo($Disbled);?>
															<?php if($arrPeriodo[4]['mes_ok']==0 && $Disbled=="") {echo("disabled=\"disabled\"");} ?>
															class="roundDec" /></td>
														<td align="center" valign="middle" style="padding: 1px;"><input
															name="<?php echo($NombMes);?>" type="text"
															id="<?php echo($NombMes);?>"
															onkeyup="TotalMetaMeses('<?php echo($rowMeta['t02_anio']);?>');"
															value="<?php echo($rowMeta["t03_mes6"]);?>" size="1"
															maxlength="10" anio='<?php echo($rowMeta['t02_anio']);?>'
															total='1' style="text-align: center; width: 30px;"
															<?php echo($Disbled);?>
															<?php if($arrPeriodo[5]['mes_ok']==0 && $Disbled=="") {echo("disabled=\"disabled\"");} ?>
															class="roundDec" /></td>
														<td align="center" valign="middle" style="padding: 1px;"><input
															name="<?php echo($NombMes);?>" type="text"
															id="<?php echo($NombMes);?>"
															onkeyup="TotalMetaMeses('<?php echo($rowMeta['t02_anio']);?>');"
															value="<?php echo($rowMeta["t03_mes7"]);?>" size="1"
															maxlength="10" anio='<?php echo($rowMeta['t02_anio']);?>'
															total='1' style="text-align: center; width: 30px;"
															<?php echo($Disbled);?>
															<?php if($arrPeriodo[6]['mes_ok']==0 && $Disbled=="") {echo("disabled=\"disabled\"");} ?>
															class="roundDec" /></td>
														<td align="center" valign="middle" style="padding: 1px;"><input
															name="<?php echo($NombMes);?>" type="text"
															id="<?php echo($NombMes);?>"
															onkeyup="TotalMetaMeses('<?php echo($rowMeta['t02_anio']);?>');"
															value="<?php echo($rowMeta["t03_mes8"]);?>" size="1"
															maxlength="10" anio='<?php echo($rowMeta['t02_anio']);?>'
															total='1' style="text-align: center; width: 30px;"
															<?php echo($Disbled);?>
															<?php if($arrPeriodo[7]['mes_ok']==0 && $Disbled=="") {echo("disabled=\"disabled\"");} ?>
															class="roundDec" /></td>
														<td align="center" valign="middle" style="padding: 1px;"><input
															name="<?php echo($NombMes);?>" type="text"
															id="<?php echo($NombMes);?>"
															onkeyup="TotalMetaMeses('<?php echo($rowMeta['t02_anio']);?>');"
															value="<?php echo($rowMeta["t03_mes9"]);?>" size="1"
															maxlength="10" anio='<?php echo($rowMeta['t02_anio']);?>'
															total='1' style="text-align: center; width: 30px;"
															<?php echo($Disbled);?>
															<?php if($arrPeriodo[8]['mes_ok']==0 && $Disbled=="") {echo("disabled=\"disabled\"");} ?>
															class="roundDec" /></td>
														<td align="center" valign="middle" style="padding: 1px;"><input
															name="<?php echo($NombMes);?>" type="text"
															id="<?php echo($NombMes);?>"
															onkeyup="TotalMetaMeses('<?php echo($rowMeta['t02_anio']);?>');"
															value="<?php echo($rowMeta["t03_mes10"]);?>" size="1"
															maxlength="10" anio='<?php echo($rowMeta['t02_anio']);?>'
															total='1' style="text-align: center; width: 30px;"
															<?php echo($Disbled);?>
															<?php if($arrPeriodo[9]['mes_ok']==0 && $Disbled=="") {echo("disabled=\"disabled\"");} ?>
															class="roundDec" /></td>
														<td align="center" valign="middle" style="padding: 1px;"><input
															name="<?php echo($NombMes);?>" type="text"
															id="<?php echo($NombMes);?>"
															onkeyup="TotalMetaMeses('<?php echo($rowMeta['t02_anio']);?>');"
															value="<?php echo($rowMeta["t03_mes11"]);?>" size="1"
															maxlength="10" anio='<?php echo($rowMeta['t02_anio']);?>'
															total='1' style="text-align: center; width: 30px;"
															<?php echo($Disbled);?>
															<?php if($arrPeriodo[10]['mes_ok']==0 && $Disbled=="") {echo("disabled=\"disabled\"");} ?>
															class="roundDec" /></td>
														<td align="center" valign="middle" style="padding: 1px;"><input
															name="<?php echo($NombMes);?>" type="text"
															id="<?php echo($NombMes);?>"
															onkeyup="TotalMetaMeses('<?php echo($rowMeta['t02_anio']);?>');"
															value="<?php echo($rowMeta["t03_mes12"]);?>" size="1"
															maxlength="10" anio='<?php echo($rowMeta['t02_anio']);?>'
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
															style="width: 100%; background-color: #333; color: #FFF; border: none; text-align: center; font-weight: bold; visibility: hidden;"
															readonly="readonly" /> <input type="text" id="txtmpar"
															name="txtmpar" value="0"
															style="width: 100%; background-color: #333; color: #FFF; border: none; text-align: center; font-weight: bold; visibility: hidden;"
															readonly="readonly" /> <input type="text" id="txtmta"
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
								<td colspan="6" align="left" nowrap>&nbsp;</td>
							</tr>
						</table>
					</div>

					<script language="javascript">
	$(document).ready(function() {
		bindRoundDecimals();
	});

  function Guardar_GastFunc()
	{
	<?php $ObjSession->AuthorizedPage(); ?>

	 if( $('#cbopartida').val()=="" ) {alert("Seleccione la Partida a Registrar"); $('#cbopartida').focus(); return false;}
	 if( $('#t03_um').val()=="" ) {alert("Ingrese Unidad de Medida"); $('#t03_um').focus(); return false;}

	 var BodyForm = $("#FormData").serialize() ;
	 var sURL = "mp_gast_func_process.php?action=<?php echo($view);?>" ;
	 var req = Spry.Utils.loadURL("POST", sURL, true, MySuccessGuardarGastFunc, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });

	 return false;
	}

	function MySuccessGuardarGastFunc(req)
	{
		var respuesta = req.xhRequest.responseText;
		respuesta = respuesta.replace(/^\s*|\s*$/g,"");
		var ret = respuesta.substring(0,5);
		if(ret=="Exito")
		{
		 LoadGastoFuncion(true);
		 var idPartida = respuesta.substring(0,8);
		 alert(respuesta.replace(idPartida,""));
		 //idPartida = idPartida.replace(ret,"");
		 //setTimeout("EditarGastFunc("+idPartida+");",5);
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
  $("input[total='1']").numeric().pasteNumeric();
  SumaTotalMeses();
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

