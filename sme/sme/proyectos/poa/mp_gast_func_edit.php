<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>
<?php

require (constant('PATH_CLASS') . "BLManejoProy.class.php");
require (constant('PATH_CLASS') . "BLTablasAux.class.php");
require_once (constant('PATH_CLASS') . "BLProyecto.class.php");
require_once (constant('PATH_CLASS') . "BLPOA.class.php");

$objMan = new BLManejoProy();
$objProy = new BLProyecto();
$HC = new HardCode();

$aFchIni = strtotime($objProy->FechaInicioProy($idProy, $idVersion));
$aFchTer = strtotime($objProy->FechaTerminoProy($idProy, $idVersion));
$NumAniosProy = ceil(($aFchTer - $aFchIni) / 31104000);

// $NumAniosProy = $objProy->NumeroAniosProy($idProy);
$view = $objFunc->__GET('mode');
$idProy = $objFunc->__Request('idProy');
$idVersion = $objFunc->__Request('idVersion');
$Partida = 0;

$AnioPOA = $objMan->GetAnioPOA($idProy, $idVersion);

$aPoaDao = new BLPOA();
$aCurrentPoa = $aPoaDao->POA_Seleccionar($idProy, $AnioPOA);

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
        
        $iRs = $objMan->Man_ListadoGastosFunc($idProy, $idVersion, $Partida, $AnioPOA);
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
								<td colspan="6" align="left" valign="top" nowrap
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
            $rsMetas = $objMan->GastFunc_Listado_Metas($idProy, $idVersion, $Partida);
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
														<td align="left" nowrap="nowrap"><input name="t03_anios[]"
															type="hidden" id="t03_anios[]"
															value="<?php echo($rowMeta['t02_anio']);?>"
															<?php echo($Disbled);?> />
                      <?php echo($rowMeta['anio']); ?>
                      </td>
														<td align="center" valign="middle" style="padding: 1px;"><input
															name="<?php echo($NombMes);?>" type="text"
															id="<?php echo($NombMes);?>"
															value="<?php echo($rowMeta["t03_mes1"]);?>" size="1"
															maxlength="10" anio='<?php echo($rowMeta['t02_anio']);?>'
															total='1' style="text-align: center; width: 30px;"
															<?php echo($Disbled);?> /></td>
														<td align="center" valign="middle" style="padding: 1px;"><input
															name="<?php echo($NombMes);?>" type="text"
															id="<?php echo($NombMes);?>"
															value="<?php echo($rowMeta["t03_mes2"]);?>" size="1"
															maxlength="10" anio='<?php echo($rowMeta['t02_anio']);?>'
															total='1' style="text-align: center; width: 30px;"
															<?php echo($Disbled);?> /></td>
														<td align="center" valign="middle" style="padding: 1px;"><input
															name="<?php echo($NombMes);?>" type="text"
															id="<?php echo($NombMes);?>"
															value="<?php echo($rowMeta["t03_mes3"]);?>" size="1"
															maxlength="10" anio='<?php echo($rowMeta['t02_anio']);?>'
															total='1' style="text-align: center; width: 30px;"
															<?php echo($Disbled);?> /></td>
														<td align="center" valign="middle" style="padding: 1px;"><input
															name="<?php echo($NombMes);?>" type="text"
															id="<?php echo($NombMes);?>"
															value="<?php echo($rowMeta["t03_mes4"]);?>" size="1"
															maxlength="10" anio='<?php echo($rowMeta['t02_anio']);?>'
															total='1' style="text-align: center; width: 30px;"
															<?php echo($Disbled);?> /></td>
														<td align="center" valign="middle" style="padding: 1px;"><input
															name="<?php echo($NombMes);?>" type="text"
															id="<?php echo($NombMes);?>"
															value="<?php echo($rowMeta["t03_mes5"]);?>" size="1"
															maxlength="10" anio='<?php echo($rowMeta['t02_anio']);?>'
															total='1' style="text-align: center; width: 30px;"
															<?php echo($Disbled);?> /></td>
														<td align="center" valign="middle" style="padding: 1px;"><input
															name="<?php echo($NombMes);?>" type="text"
															id="<?php echo($NombMes);?>"
															value="<?php echo($rowMeta["t03_mes6"]);?>" size="1"
															maxlength="10" anio='<?php echo($rowMeta['t02_anio']);?>'
															total='1' style="text-align: center; width: 30px;"
															<?php echo($Disbled);?> /></td>
														<td align="center" valign="middle" style="padding: 1px;"><input
															name="<?php echo($NombMes);?>" type="text"
															id="<?php echo($NombMes);?>"
															value="<?php echo($rowMeta["t03_mes7"]);?>" size="1"
															maxlength="10" anio='<?php echo($rowMeta['t02_anio']);?>'
															total='1' style="text-align: center; width: 30px;"
															<?php echo($Disbled);?> /></td>
														<td align="center" valign="middle" style="padding: 1px;"><input
															name="<?php echo($NombMes);?>" type="text"
															id="<?php echo($NombMes);?>"
															value="<?php echo($rowMeta["t03_mes8"]);?>" size="1"
															maxlength="10" anio='<?php echo($rowMeta['t02_anio']);?>'
															total='1' style="text-align: center; width: 30px;"
															<?php echo($Disbled);?> /></td>
														<td align="center" valign="middle" style="padding: 1px;"><input
															name="<?php echo($NombMes);?>" type="text"
															id="<?php echo($NombMes);?>"
															value="<?php echo($rowMeta["t03_mes9"]);?>" size="1"
															maxlength="10" anio='<?php echo($rowMeta['t02_anio']);?>'
															total='1' style="text-align: center; width: 30px;"
															<?php echo($Disbled);?> /></td>
														<td align="center" valign="middle" style="padding: 1px;"><input
															name="<?php echo($NombMes);?>" type="text"
															id="<?php echo($NombMes);?>"
															value="<?php echo($rowMeta["t03_mes10"]);?>" size="1"
															maxlength="10" anio='<?php echo($rowMeta['t02_anio']);?>'
															total='1' style="text-align: center; width: 30px;"
															<?php echo($Disbled);?> /></td>
														<td align="center" valign="middle" style="padding: 1px;"><input
															name="<?php echo($NombMes);?>" type="text"
															id="<?php echo($NombMes);?>"
															value="<?php echo($rowMeta["t03_mes11"]);?>" size="1"
															maxlength="10" anio='<?php echo($rowMeta['t02_anio']);?>'
															total='1' style="text-align: center; width: 30px;"
															<?php echo($Disbled);?> /></td>
														<td align="center" valign="middle" style="padding: 1px;"><input
															name="<?php echo($NombMes);?>" type="text"
															id="<?php echo($NombMes);?>"
															value="<?php echo($rowMeta["t03_mes12"]);?>" size="1"
															maxlength="10" anio='<?php echo($rowMeta['t02_anio']);?>'
															total='1' style="text-align: center; width: 30px;"
															<?php echo($Disbled);?> /></td>
														<td align="center" valign="middle"><span
															id="sum_<?php echo($rowMeta['t02_anio']);?>"
															style="text-align: center"><?php echo($rowMeta["t03_tot_anio"]);?></span></td>
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
  		$(':input[anio=' + <?php echo $AnioPOA; ?> + ']').keyup(function() { TotalMetaMeses(<?php echo $AnioPOA; ?>); });
  		$(':input[anio=' + <?php echo $AnioPOA-1; ?> + ']').keyup(function() {
			var aTotalYr = 0;
			$(':input[anio=' + <?php echo $AnioPOA-1; ?> + ']').each(function() {
			    var aVal = $(this).val();
			    if (!isNaN(parseFloat(aVal)))
			        aTotalYr += parseFloat(aVal);
			} );
			$('#sum_' + <?php echo $AnioPOA-1; ?>).text(aTotalYr);
			CalcularTotalM();
  		});
  	});

  function Guardar_GastFunc()
	{
	<?php $ObjSession->AuthorizedPage(); ?>

	 if( $('#cbopartida').val()=="" ) {alert("Seleccione la Partida a Registrar"); $('#cbopartida').focus(); return false;}
	 if( $('#t03_um').val()=="" ) {alert("Ingrese Unidad de Medida"); $('#t03_um').focus(); return false;}

	 TotalMetaMeses(<?php echo $AnioPOA; ?>);
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
		 idPartida = idPartida.replace(ret,"");
		 setTimeout("EditarGastFunc("+idPartida+");",5);
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
		$("#txtmta").val(tot);
		$(idsum).html(tot);
		SumaTotalMeses();
		CalcularTotalM();
		TotalMetaProgramada();
	}
	function SumaTotalMeses()
	{
		var tot = 0 ;
		$("input[total='1']:not(input:disabled')").each(function() {
	            tot += CNumber(this.value);
	           });
	}
	function CalcularTotalM() //calcula el total de todos años
	{
		var aTotal = 0;
		$('span[id^=sum_]').each(function() {
		    var aVal=$(this).text();
		    if (!isNaN(parseFloat(aVal)))
		        aTotal += parseFloat(aVal);
		});
		$('#txtSumaTotalMeses').val(aTotal);
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

  </script>

					<script>
  $('#txtmpar').numeric().pasteNumeric();
  $("input[total='1']").numeric().pasteNumeric();
  TotalMetaMeses(<?php echo $AnioPOA ; ?>)
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

