<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>

<?php
require_once (constant("PATH_CLASS") . "Functions.class.php");
require_once (constant("PATH_CLASS") . "BLPOA.class.php");

error_reporting("E_PARSE");
$Function = new Functions();
$proc = $Function->__GET('proc');
$view = $Function->__GET('action');

$idProy = $Function->__GET('idProy');
$idVersion = $Function->__GET('idVersion');
$idComp = $Function->__GET('idComp');
$idActiv = $Function->__GET('idActiv');
$idSActiv = $Function->__GET('idSActiv');
$anio = $Function->__GET('anio');

$modif = $Function->__GET('m');
$solove = false;
if (md5("solo_ve_no_edita") == $modif) {
	$solove = true;
}


$objProy = new BLProyecto();

if ($proc == md5("save")) {
    // --> Hacemos el Insert o Update
    $ReturnPage = false;
    if ($view == md5("edit")) {
        $replicarMetas = $Function->__POST('chkReplicarGuardar');
        $objPOA = new BLPOA();

        if ($replicarMetas == "1") {
            $ReturnPage = $objPOA->ReplicarMetasSubActividad();
        } else {
            $ReturnPage = $objPOA->ActualizarMetasSubActividad();
        }

        $objPOA = NULL;
        $proc = md5("reload");
    }
}

if ($proc == md5("reload")) {
    $idProy = $Function->__POST('t02_cod_proy');
    $idVersion = $Function->__POST('t02_version');
    $idComp = $Function->__POST('t08_cod_comp');
    $idActiv = $Function->__POST('t09_cod_act');
    $idSActiv = $Function->__POST('t09_cod_sub');
    $anio = $Function->__POST('cboAnios');
}

?>
<?php

$row = 0;
if ($view == md5("edit")) {
    $objPOA = new BLPOA();
    $row = $objPOA->GetMetasSubActividad($idProy, $idVersion, $idComp, $idActiv, $idSActiv, $anio);
    $rowAcum = $objPOA->GetAcumMetas($idProy, $idVersion, $idComp, $idActiv, $idSActiv, $anio);
    // $objPOA = NULL;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/templateIframe.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<?php

if ($view == "new") {
    $objFunc->SetSubTitle("Actividades  - Metas ");
} else {
    $objFunc->SetSubTitle("Actividades - Metas ");
}

?>
<title>Supuestos Objetivo Especifico</title>
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->
<?php
if ($ReturnPage) {
    $script = "alert('Se grabó correctamente el Registro'); \n";
    // $script .= "parent.document.getElementById('cboAnios').value = '".$anio."'; \n" ;
    // -------------------------------------------------->
    // AQ 2.0 [29-11-2013 14:07]
    // Cerrar la ventana de edición y actualizas actividades
    // --------------------------------------------------<
    //$script .= "parent.LoadSubActividades(); \n";
    $script .= "parent.btnSuccess(); \n";
    $Function->Javascript($script);
}
?>
<script src="../../../SpryAssets/xpath.js" type="text/javascript"></script>
<script src="../../../SpryAssets/SpryData.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript"
	src="../../../jquery.ui-1.5.2/jquery-1.2.6.js"></script>
<script language="javascript"
	src="../../../jquery.ui-1.5.2/jquery.numeric.js"></script>
<script src="../../../js/commons.js" type="text/javascript"></script>

<!-- InstanceEndEditable -->
<link href="../../../css/itemplate.css" rel="stylesheet" type="text/css"
	media="all" />
</head>

<body>
	<!-- InstanceBeginEditable name="tBody" -->
	<form id="frmMain" name="frmMain" method="post" action="#">
		<div id="toolbar" style="height: 4px;" class="Subtitle">
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td width="47%" align="right"><?php echo($objFunc->SubTitle) ;?></td>
					<td width="31%">&nbsp;</td>
					<td width="2%">&nbsp;</td>
					<td width="2%">&nbsp;</td>
					<td width="2%">&nbsp;</td>
					<td width="9%"><button class="Button"
							onclick="return Guardar(); return false;" value="Guardar">Guardar
						</button></td>
					<td width="9%"><button class="Button" value="Cancelar"
							onclick="return Cancelar();">Cancelar</button></td>
				</tr>
			</table>
		</div>
		<table width="540" align="center" class="TableEditReg">
			<tr valign="baseline">
				<td width="546" colspan="5" align="left"><fieldset
						style="padding: 1px;">
						<table width="524" align="center" class="TableEditReg">
							<tr valign="baseline">
								<td align="left" nowrap><strong>Codigo Proyecto</strong>:</td>
								<td align="left"><?php echo($row["t02_cod_proy"]);?></td>
								<td><strong>Institución</strong>:</td>
								<td colspan="3"><?php echo($row["t01_nom_inst"]);?></td>
							</tr>
							<tr valign="baseline">
								<td align="left" nowrap><strong>Nombre Proyecto</strong>:</td>
								<td colspan="5" align="left"><?php echo($row["t02_nom_proy"]);?></td>
							</tr>
							<tr valign="baseline">
								<td align="left" nowrap><strong>Fecha de Inicio</strong>:</td>
								<td colspan="2" align="left"><?php echo($row["fch_inicio"]);?></td>
								<td colspan="2" align="right" valign="middle"><strong>Fecha de
										Término</strong>:</td>
								<td align="left"><?php echo($row["fch_termino"]);?></td>
							</tr>
							<tr valign="baseline">
								<td width="105" align="left" nowrap><strong>Actividad</strong>:</td>
								<td colspan="5" align="left"><input name="t09_sub" type="text"
									id="t09_sub" class="edit_poam"
									value="<?php echo($row["t08_cod_comp"].".".$row["t09_cod_act"].".".$row["t09_cod_sub"].' '.$row["t09_sub"]);?>"
									size="80" maxlength="100" readonly="readonly" /> <br />
								<strong>Unidad de Medida:</strong> <strong style="color: #009;"><?php echo($row["t09_um"]);?></strong>
								</td>
							</tr>
							<tr valign="baseline">
								<td height="30" align="left" valign="middle" nowrap><strong>Año</strong>:</td>
								<td width="80" align="left" valign="middle"><?php
        $AnioPOA = $objProy->VerificarAnioPOA($idProy, $idVersion);
        if ($AnioPOA > 0 && $idVersion > 1) {
            $Disbled = "disabled=\"disabled\"";
            $anio = $AnioPOA;
        } else {
            $Disbled = "";
        }
        ?>
             <select name="cboAnios" class="TextDescripcion"
									id="cboAnios" style="width: 80px;"
									onchange="cboAnios_OnChange();" <?php echo($Disbled); ?>>
               <?php
            $rs = $objPOA->Proyecto->ListaAniosProyecto($idProy, $idVersion);
            $finAnio = $Function->llenarCombo($rs, 'codigo', 'descripcion', $anio);
            ?>
             </select>
             <?php
            if ($Disbled != "") {
                echo ("<input type=\"hidden\" id=\"cboAnios\" name=\"cboAnios\" value=\"" . $AnioPOA . "\"/>");
            }

            $irsPeriodo = $objProy->PeriodosxAnio($idProy, $anio);
            $arrPeriodo = NULL;
            $cont = 1;
            while ($r = mysqli_fetch_assoc($irsPeriodo)) {
                $arrPeriodo[$cont] = $r;
                $cont ++;
            }
            $irsPeriodo->free();
            ?>

             <input type="hidden" id="txtFinAnio" name="txtFinAnio"
									value="<?php echo($finAnio);?>" /></td>
								<td width="62" align="left" valign="middle" nowrap="nowrap"><strong>Meta
										Total</strong>:</td>
								<td width="77" align="left" valign="middle" nowrap="nowrap"><input
									name="t09_mta" type="text" id="t09_mta" class="edit_poam"
									style="text-align: center"
									value="<?php echo($row["t09_mta"]);?>" size="15"
									readonly="readonly" /></td>
								<td width="21" align="right" valign="middle" nowrap="nowrap"><input
									name="chkReplicarGuardar" type="checkbox" class="Boton"
									id="chkReplicarGuardar" value="1" style="border: none;"
									<?php echo($Disbled); ?> /></td>
								<td width="155" align="left" valign="middle" nowrap="nowrap"><label
									for="chkReplicarGuardar" class="Boton">Replicar Metas para los
										proximos<br /> años
								</label></td>
							</tr>
							<tr>
								<td style="padding: 1px;" colspan="6" align="left" valign="top">
									<fieldset>
										<legend>Metas</legend>
										<table width="250" border="0" cellpadding="0" cellspacing="0"
											style="padding-left: 0px; padding-right: 0px; padding-top: 5px; padding-bottom: 5px;">
											<!--
                                            // -------------------------------------------------- >
                                            // AQ 2.0 [20-10-2013 11:07]
                                            // Se retira refencia a Trimestres
											<tr>
												<th colspan="2" align="center">Trim 1</th>
												<th colspan="2" align="center">Trim 2</th>
												<th colspan="2" align="center">Trim 3</th>
												<th colspan="2" align="center">Trim 4</th>
											</tr>
											// -------------------------------------------------- <
											-->
											<tr>
												<td nowrap="nowrap" style="font-size: 9px">Mes 1 (<?php echo($arrPeriodo[1]['nom_abrev']."-".$arrPeriodo[1]['num_anio']);?>) </td>
												<td width="28" align="left"><input name="t09_mes1"
													type="text" id="t09_mes1"
													value="<?php echo($row["t09_mes1"]);?>" size="3"
													maxlength="8" style="text-align: center"
													onkeyup="TotalizarMetas();"
													onchange="ValidarMetasExceso();" tabindex="5"
													class="edit_poam"
													<?php if($arrPeriodo[1]['mes_ok']=="0"){echo('readonly="readonly"');}?> /></td>
												<td nowrap="nowrap" style="font-size: 9px" width="33">&nbsp;&nbsp;Mes 4 (<?php echo($arrPeriodo[4]['nom_abrev']."-".$arrPeriodo[4]['num_anio']);?>)</td>
												<td width="29" align="left"><input name="t09_mes4"
													type="text" id="t09_mes4"
													value="<?php echo($row["t09_mes4"]);?>" size="3"
													maxlength="8" style="text-align: center"
													onkeyup="TotalizarMetas();"
													onchange="ValidarMetasExceso();" tabindex="8"
													class="edit_poam"
													<?php if($arrPeriodo[4]['mes_ok']=="0"){echo('readonly="readonly"');}?> /></td>
												<td nowrap="nowrap" style="font-size: 9px" width="33">&nbsp;&nbsp;Mes 7 (<?php echo($arrPeriodo[7]['nom_abrev']."-".$arrPeriodo[7]['num_anio']);?>)</td>
												<td width="22" align="left"><input name="t09_mes7"
													type="text" id="t09_mes7"
													value="<?php echo($row["t09_mes7"]);?>" size="3"
													maxlength="8" style="text-align: center"
													onkeyup="TotalizarMetas();"
													onchange="ValidarMetasExceso();" tabindex="11"
													class="edit_poam"
													<?php if($arrPeriodo[7]['mes_ok']=="0"){echo('readonly="readonly"');}?> /></td>
												<td nowrap="nowrap" style="font-size: 9px" width="41">&nbsp;&nbsp;Mes 10 (<?php echo($arrPeriodo[10]['nom_abrev']."-".$arrPeriodo[10]['num_anio']);?>)</td>
												<td width="29" align="left"><input name="t09_mes10"
													type="text" id="t09_mes10"
													value="<?php echo($row["t09_mes10"]);?>" size="3"
													maxlength="8" style="text-align: center"
													onkeyup="TotalizarMetas();"
													onchange="ValidarMetasExceso();" tabindex="14"
													class="edit_poam"
													<?php if($arrPeriodo[10]['mes_ok']=="0"){echo('readonly="readonly"');}?> /></td>
											</tr>
											<tr>
												<td nowrap="nowrap" style="font-size: 9px">Mes 2 (<?php echo($arrPeriodo[2]['nom_abrev']."-".$arrPeriodo[2]['num_anio']);?>)</td>
												<td align="left"><input name="t09_mes2" type="text"
													id="t09_mes2" value="<?php echo($row["t09_mes2"]);?>"
													size="3" maxlength="80" style="text-align: center"
													onkeyup="TotalizarMetas();"
													onchange="ValidarMetasExceso();" tabindex="6"
													class="edit_poam"
													<?php if($arrPeriodo[2]['mes_ok']=="0"){echo('readonly="readonly"');}?> /></td>
												<td style="font-size: 9px" nowrap="nowrap">&nbsp;&nbsp;Mes 5 (<?php echo($arrPeriodo[5]['nom_abrev']."-".$arrPeriodo[5]['num_anio']);?>)</td>
												<td align="left"><input name="t09_mes5" type="text"
													id="t09_mes5" value="<?php echo($row["t09_mes5"]);?>"
													size="3" maxlength="8" style="text-align: center"
													onkeyup="TotalizarMetas();"
													onchange="ValidarMetasExceso();" tabindex="9"
													class="edit_poam"
													<?php if($arrPeriodo[5]['mes_ok']=="0"){echo('readonly="readonly"');}?> /></td>
												<td style="font-size: 9px" nowrap="nowrap">&nbsp;&nbsp;Mes 8 (<?php echo($arrPeriodo[8]['nom_abrev']."-".$arrPeriodo[8]['num_anio']);?>)</td>
												<td align="left"><input name="t09_mes8" type="text"
													id="t09_mes8" value="<?php echo($row["t09_mes8"]);?>"
													size="3" maxlength="8" style="text-align: center"
													onkeyup="TotalizarMetas();"
													onchange="ValidarMetasExceso();" tabindex="12"
													class="edit_poam"
													<?php if($arrPeriodo[8]['mes_ok']=="0"){echo('readonly="readonly"');}?> /></td>
												<td style="font-size: 9px" nowrap="nowrap">&nbsp;&nbsp;Mes 11 (<?php echo($arrPeriodo[11]['nom_abrev']."-".$arrPeriodo[11]['num_anio']);?>)</td>
												<td align="left"><input name="t09_mes11" type="text"
													id="t09_mes11" value="<?php echo($row["t09_mes11"]);?>"
													size="3" maxlength="10" style="text-align: center"
													onkeyup="TotalizarMetas();"
													onchange="ValidarMetasExceso();" tabindex="15"
													class="edit_poam"
													<?php if($arrPeriodo[11]['mes_ok']=="0"){echo('readonly="readonly"');}?> /></td>
											</tr>
											<tr>
												<td nowrap="nowrap" style="font-size: 9px">Mes 3 (<?php echo($arrPeriodo[3]['nom_abrev']."-".$arrPeriodo[3]['num_anio']);?>)</td>
												<td align="left"><input name="t09_mes3" type="text"
													id="t09_mes3" value="<?php echo($row["t09_mes3"]);?>"
													size="3" maxlength="10" style="text-align: center"
													onkeyup="TotalizarMetas();"
													onchange="ValidarMetasExceso();" tabindex="7"
													class="edit_poam"
													<?php if($arrPeriodo[3]['mes_ok']=="0"){echo('readonly="readonly"');}?> /></td>
												<td style="font-size: 9px" nowrap="nowrap">&nbsp;&nbsp;Mes 6 (<?php echo($arrPeriodo[6]['nom_abrev']."-".$arrPeriodo[6]['num_anio']);?>)</td>
												<td align="left"><input name="t09_mes6" type="text"
													id="t09_mes6" value="<?php echo($row["t09_mes6"]);?>"
													size="3" maxlength="10" style="text-align: center"
													onkeyup="TotalizarMetas();"
													onchange="ValidarMetasExceso();" tabindex="10"
													class="edit_poam"
													<?php if($arrPeriodo[6]['mes_ok']=="0"){echo('readonly="readonly"');}?> /></td>
												<td style="font-size: 9px" nowrap="nowrap">&nbsp;&nbsp;Mes 9 (<?php echo($arrPeriodo[9]['nom_abrev']."-".$arrPeriodo[9]['num_anio']);?>)</td>
												<td align="left"><input name="t09_mes9" type="text"
													id="t09_mes9" value="<?php echo($row["t09_mes9"]);?>"
													size="3" maxlength="10" style="text-align: center"
													onkeyup="TotalizarMetas();"
													onchange="ValidarMetasExceso();" tabindex="13"
													class="edit_poam"
													<?php if($arrPeriodo[9]['mes_ok']=="0"){echo('readonly="readonly"');}?> /></td>
												<td style="font-size: 9px" nowrap="nowrap">&nbsp;&nbsp;Mes 12 (<?php echo($arrPeriodo[12]['nom_abrev']."-".$arrPeriodo[12]['num_anio']);?>)</td>
												<td align="left"><input name="t09_mes12" type="text"
													id="t09_mes12" value="<?php echo($row["t09_mes12"]);?>"
													size="3" maxlength="10" style="text-align: center"
													onkeyup="TotalizarMetas();"
													onchange="ValidarMetasExceso();" tabindex="16"
													class="edit_poam"
													<?php if($arrPeriodo[12]['mes_ok']=="0"){echo('readonly="readonly"');}?> /></td>
											</tr>
										</table>
									</fieldset>
								</td>
							</tr>
							<tr valign="baseline">
								<td colspan="6" align="left" valign="top" nowrap><table
										width="100%" border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td width="21%" align="center">Acumulado Anterior</td>
											<td width="8%" align="center">&nbsp;</td>
											<td width="13%" align="center">Meta del Año <?php echo($anio);?></td>
											<td width="9%" align="center">&nbsp;</td>
											<td width="17%" align="center">Años Posteriores</td>
											<td width="11%" align="center">&nbsp;</td>
											<td width="21%" align="center">Total</td>
										</tr>
										<tr>
											<td align="center"><input name="t09_acum_anio" type="text"
												id="t09_acum_anio" class="edit_poam"
												value="<?php echo($rowAcum["MetaAcumAnio"]);?>" size="13"
												maxlength="10" readonly="readonly"
												style="text-align: center" /></td>
											<td align="center"><font
												style="font-size: 16px; font-weight: bold; color: #00C;">+</font></td>
											<td align="center"><input name="t09_mta_anio" type="text"
												id="t09_mta_anio" class="edit_poam"
												value="<?php echo($row["t09_mta_anio"]);?>" size="13"
												maxlength="10" readonly="readonly"
												style="text-align: center" /></td>
											<td align="center"><font
												style="font-size: 16px; font-weight: bold; color: #00C;">+</font></td>
											<td align="center"><input name="total_posterior" type="text"
												class="edit_poam" id="total_posterior"
												value="<?php echo(   ($rowAcum["MetaTotalAcum"] + $row["t09_mta_anio"]) -  ($rowAcum["MetaAcumAnio"] + $row["t09_mta_anio"])   );?>"
												size="15" maxlength="10" readonly="readonly"
												style="text-align: center" /></td>
											<td align="center"><font
												style="font-size: 16px; font-weight: bold; color: #00C;">=</font></td>
											<td align="center"><input name="total_acumulado" type="text"
												class="edit_poam" id="total_acumulado"
												value="<?php echo( ($rowAcum["MetaTotalAcum"] + $row["t09_mta_anio"]));?>"
												size="15" maxlength="10" readonly="readonly"
												style="text-align: center" /> <input name="t09_tot_acum"
												type="hidden" id="t09_tot_acum"
												value="<?php echo($rowAcum["MetaTotalAcum"]);?>" /></td>
										</tr>
									</table></td>
							</tr>
							<tr>
								<td colspan="6" valign="top">&nbsp;</td>
							</tr>

						</table>
					</fieldset></td>
			</tr>
		</table>
		<input type="hidden" name="t02_cod_proy"
			value="<?php echo($idProy);?>" /> <input type="hidden"
			name="t02_version" value="<?php echo($idVersion);?>" /> <input
			type="hidden" name="t08_cod_comp" value="<?php echo($idComp);?>" /> <input
			type="hidden" name="t09_cod_act" value="<?php echo($idActiv);?>" /> <input
			type="hidden" name="t09_cod_sub" value="<?php echo($idSActiv);?>" />



	</form>
	<script language="javascript" type="text/javascript">
	  function cboAnios_OnChange()
	  {
	  	 var formulario = document.getElementById("frmMain") ;
		 formulario.action = "poa_meta_edit.php?&proc=<?php echo(md5("reload"));?>&action=<?php echo(md5("edit"));?>";
		 formulario.submit();
		 return true ;
	  }

	  function Cancelar()
	  {
		 parent.btnCancel_Clic();
		 return false;
	  }

	  function Guardar()
	  {
	     <?php $ObjSession->AuthorizedPage(); ?>

	  	 var formulario = document.getElementById("frmMain");

 		 if($('#cboAnios').val()=="")
		 {
		 	alert("Ingrese Año");
			$('#cboAnios').focus();
			return false ;
		 }

		 var ver 		=	CNumber("<?php echo($idVersion);?>") ;
	     var MetTot 	= 	CNumber($('#t09_mta').val()) ;
		 var metaAnio 	=	CNumber($('#t09_mta_anio').val());
		 var TotAcum 	= 	CNumber($('#total_acumulado').val());

		 if (ver == 1){
			 if(TotAcum > MetTot) {alert("El Total acumulado es mayor que la meta Total"); return false;}
			 }
		 if (ver > 1){
			 if(metaAnio > MetTot) {alert("La meta del año es mayor que la meta Total"); return false;}
			 }

		 var Replicar = $('#chkReplicarGuardar').is(':checked');

		 if(Replicar)
		 {
		 	/*var bret = confirm("¿ Estás seguro de Replicar las Metas para los Siguientes Años ?\nLas Metas de la Actividad ["+$('#t09_cod_sub2').val()+"], existentes en los Siguientes años, serán eliminadas...");*/
		 	var bret = confirm("¿ Estás seguro de Replicar las Metas para los Siguientes Años ?\nLas Metas de la Actividad ["+$('#t09_sub').val()+"], existentes en los Siguientes años, serán eliminadas...");
			if(!bret)
			{return false;}
			else
			{
				var anios = CNumber("<?php echo($finAnio);?>");
				var nummeses = anios * 12 ;
				var duraproy = CNumber("<?php echo($objProy->NumeroMesesProy($idProy));?>");

				if(nummeses > duraproy)
				{
					alert('No es posible replicar las metas para los años restantes, porque el Numero de meses del proyecto es: '+duraproy+'\npara replicar las metas se requiere un proyecto de ' + nummeses + ' meses' );
					return false;
				}

			}
		 }

		 /*
		 if( CNumber(mtaTotalAcum) > CNumber(mtaTotal))
		 { alert("La Meta Acumulada , Supera la Meta Total Planificada.") ; return false; }
		 */

		 formulario.action = "poa_meta_edit.php?&proc=<?php echo(md5("save"));?>&action=<?php echo(md5("edit"));?>";
		 return true ;
	  }

   </script>

	<script language="javascript" type="text/javascript">
	<?php if($solove): ?>
	$(document).ready(function(){
		$('#frmMain').find('input, select, textarea').attr('disabled','disabled');
		$('#frmMain').find('button[value="Guardar"]').remove();		
	});
	<?php endif;?>
	
	  function TotalizarMetas()
	  {
		  var m1 = $('#t09_mes1').val();
		  var m2 = $('#t09_mes2').val();
		  var m3 = $('#t09_mes3').val();
		  var m4 = $('#t09_mes4').val();
		  var m5 = $('#t09_mes5').val();
		  var m6 = $('#t09_mes6').val();
		  var m7 = $('#t09_mes7').val();
		  var m8 = $('#t09_mes8').val();
		  var m9 = $('#t09_mes9').val();
		  var m10 = $('#t09_mes10').val();
		  var m11 = $('#t09_mes11').val();
		  var m12 = $('#t09_mes12').val();
		  var total = (CNumber(m1) + CNumber(m2) + CNumber(m3) + CNumber(m4) + CNumber(m5) + CNumber(m6) + CNumber(m7) + CNumber(m8) + CNumber(m9) + CNumber(m10) + CNumber(m11) + CNumber(m12));
		  var acumOtrosAnios = $('#t09_tot_acum').val();
		  $('#t09_mta_anio').val(total);

		  var Replicar = $('#chkReplicarGuardar').is(':checked');
		  if(Replicar)
		  {
		 	var numAnios =$('#txtFinAnio').val();
		    var AnioActual = $('#cboAnios').val();
		    var DiffAnios = CNumber(numAnios) - CNumber(AnioActual);
			acumOtrosAnios = (DiffAnios * total ) ;
		  }
		   var totalmetas = (CNumber(acumOtrosAnios) + total);

		 var ver 		=	<?php echo($idVersion);?>;
		 var MetTot 	= 	$('#t09_mta').val();
		 var metaAnio 	=	$('#t09_mta_anio').val();

		  $('#total_acumulado').val(totalmetas);
	  }

	  function ValidarMetasExceso()
	  {
		 var ver 		=	<?php echo($idVersion);?>;
		 var MetTot 	= 	CNumber($('#t09_mta').val());
		 var metaAnio 	=	CNumber($('#t09_mta_anio').val());
		 var TotAcum 	= 	CNumber($('#total_acumulado').val());

		if (ver == 1 )
		{
			if(TotAcum > MetTot) {alert("El Total acumulado es mayor que la meta Total"); return false;}
		}
		if (ver > 1 )
		{
			if(metaAnio > MetTot) {alert("La meta del año es mayor que la meta Total"); return false;}
		}
	   }

	  function CNumber(str)
	  {
		  var numero =0;
		  if (str !="" && str !=null)
		  { numero = parseFloat(str);}

		  if(isNaN(numero)) { numero=0;}

		 return numero;
	  }
		$('#t09_mes1').numeric().pasteNumeric();
		$('#t09_mes2').numeric().pasteNumeric();
		$('#t09_mes3').numeric().pasteNumeric();
		$('#t09_mes4').numeric().pasteNumeric();
		$('#t09_mes5').numeric().pasteNumeric();
		$('#t09_mes6').numeric().pasteNumeric();
		$('#t09_mes7').numeric().pasteNumeric();
		$('#t09_mes8').numeric().pasteNumeric();
		$('#t09_mes9').numeric().pasteNumeric();
		$('#t09_mes10').numeric().pasteNumeric();
		$('#t09_mes11').numeric().pasteNumeric();
		$('#t09_mes12').numeric().pasteNumeric();

		$('.edit_poam:input[readonly="readonly"]').css("background-color", "#eeeecc") ;
 </script>
	<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd -->
</html>
