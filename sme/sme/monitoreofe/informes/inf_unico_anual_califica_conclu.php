<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>

<?php
require (constant("PATH_CLASS") . "BLInformes.class.php");
require (constant("PATH_CLASS") . "BLTablasAux.class.php");
require (constant("PATH_CLASS") . "HardCode.class.php");

error_reporting("E_PARSE");
$idProy = $objFunc->__POST('idProy');
$idNum = $objFunc->__POST('idNum');
$objHC = new HardCode();
if ($idProy == "" && $idNum == "" && $idTrim == "") {
    $idProy = $objFunc->__GET('idProy');
    $idNum = $objFunc->__GET('idNum');
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

<title>Información Adicional</title>
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->
<script language="javascript" type="text/javascript"
	src="../../../jquery.ui-1.5.2/jquery-1.2.6.js"></script>
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
<?php

$objInf = new BLInformes();
$row = $objInf->Inf_UA_Seleccionar($idProy, $idNum);
$objTablas = new BLTablasAux();
?>
<table width="750" border="0" cellspacing="1" cellpadding="0">
			<tr>
				<td width="50%" class="TableEditReg"><span
					style="font-weight: bold;">Informacion Adicional</span></td>
				<td>Terminado CMT <input
					<?php if($ObjSession->PerfilID != $objHC->CMT ){ echo 'disabled'; } ?>
					id="t55_cmt" type="checkbox" value="1"
					<?php if($row['t55_cmt']=='1'){echo('checked="checked"');} ?>
					name="t55_cmt" class="InformacionAdicional cmtData"></br> Terminado
					CMF <input
					<?php if($ObjSession->PerfilID != $objHC->CMF ){ echo 'disabled'; } ?>
					id="t55_cmf" type="checkbox" value="1"
					<?php if($row['t55_cmf']=='1'){echo('checked="checked"');} ?>
					name="t55_cmf" class="InformacionAdicional cmfData">
				</td>
				<td width="8%" align="center" class="TableEditReg"><input
					type="button" value="Refrescar" class="btn_save_custom"
					title="Refrescar datos de Información Adicional"
					onclick="LoadInfAdicional(true);" /></td>
				<td width="10%" align="right" valign="middle"><input type="button"
					value="Guardar" class="btn_save_custom cmtData cmfData"
					title="Guardar Información Adicional"
					onclick="Guardar_InfAdicional();" /></td>
			</tr>
		</table>
		<div id="divTableLista" class="TableGrid">
			<table width="750" cellpadding="0" cellspacing="0">
				<thead>
				</thead>
				<tbody class="data" bgcolor="#FFFFFF">
					<tr>
						<td align="left" valign="middle"><span
							style="font-weight: bold; font-size: 12px;">Calificación del
								Proyecto</span> <br />
      <?php
    $isMtAllowed = ($ObjSession->PerfilID == $objHC->CMT || $ObjSession->PerfilID == $objHC->MT || $ObjSession->PerfilID == $objHC->Admin);
    $isMfAllowed = ($ObjSession->PerfilID == $objHC->CMF || $ObjSession->PerfilID == $objHC->MF || $ObjSession->PerfilID == $objHC->Admin);
    ?>	
      <table width="100%" border="0" cellpadding="0" cellspacing="0"
								class="TableEditReg">
								<tr>
									<td width="39%">&nbsp;</td>
									<td width="22%" align="center"><b>Valoración</b></td>
									<td width="39%" align="center"><b>Comentarios</b></td>
								</tr>

								<tr>
									<td align="left">Relación planificado y ejecutado operativo</td>
									<td align="center"><select
										<?php if(!$isMtAllowed){ echo 'disabled'; } ?> name="t55_eva1"
										id="t55_eva1" style="width: 150px;"
										onchange="CalcularResultado();"
										class="InformacionAdicional cmtData">
											<option value="" selected="selected" title="2"></option>
          <?php
        $rs = $objTablas->ValoraInformesME();
        $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $row['t55_eva1'], 'cod_ext');
        ?>
          </select></td>
									<td align="center"><textarea name="t55_eva1_obs" rows="2"
											id="t55_eva1_obs" style="padding: 0px; width: 100%;"
											class="InformacionAdicional cmtData"
											<?php if(!$isMtAllowed){ echo 'disabled'; } ?>><?php echo($row['t55_eva1_obs']);?></textarea>
									</td>
								</tr>

								<tr>
									<td align="left">Relación entre ejecución financiera y
										ejecución técnica.</td>
									<td align="center"><select
										<?php if(!$isMfAllowed){ echo 'disabled'; } ?> name="t55_eva2"
										id="t55_eva2" style="width: 150px;"
										onchange="CalcularResultado();"
										class="InformacionAdicional cmfData">
											<option value="" selected="selected"></option>
            <?php
            $rs = $objTablas->ValoraInformesME();
            $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $row['t55_eva2'], 'cod_ext');
            ?>
          </select></td>
									<td align="center"><textarea name="t55_eva2_obs" rows="2"
											id="t55_eva2_obs" style="padding: 0px; width: 100%;"
											class="InformacionAdicional cmfData"
											<?php if(!$isMfAllowed){ echo 'disabled'; } ?>><?php echo($row['t55_eva2_obs']);?></textarea>
									</td>
								</tr>

								<tr>
									<td align="left">Avance de actividades críticas</td>
									<td align="center"><select
										<?php if(!$isMtAllowed){ echo 'disabled'; } ?> name="t55_eva3"
										id="t55_eva3" style="width: 150px;"
										onchange="CalcularResultado();"
										class="InformacionAdicional cmtData">
											<option value="" selected="selected"></option>
            <?php
            $rs = $objTablas->ValoraInformesME();
            $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $row['t55_eva3'], 'cod_ext');
            ?>
          </select></td>
									<td align="center"><textarea name="t55_eva3_obs" cols=""
											rows="2" class="InformacionAdicional cmtData"
											id="t55_eva3_obs" style="padding: 0px; width: 100%;"
											<?php if(!$isMtAllowed){ echo 'disabled'; } ?>><?php echo($row['t55_eva3_obs']);?></textarea>
									</td>
								</tr>

								<tr>
									<td align="left">Calidad de las Capacitaciones</td>
									<td align="center"><select
										<?php if(!$isMtAllowed){ echo 'disabled'; } ?> name="t55_eva4"
										id="t55_eva4" style="width: 150px;"
										onchange="CalcularResultado();"
										class="InformacionAdicional cmtData">
											<option value="" selected="selected"></option>
            <?php
            $rs = $objTablas->ValoraInformesME();
            $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $row['t55_eva4'], 'cod_ext');
            ?>
          </select></td>
									<td align="center"><textarea name="t55_eva4_obs" cols=""
											rows="2" class="InformacionAdicional cmtData"
											id="t55_eva4_obs" style="padding: 0px; width: 100%;"
											<?php if(!$isMtAllowed){ echo 'disabled'; } ?>><?php echo($row['t55_eva4_obs']);?></textarea>
									</td>
								</tr>

								<tr>
									<td align="left">Desempeño del equipo técnico</td>
									<td align="center"><select
										<?php if(!$isMtAllowed){ echo 'disabled'; } ?> name="t55_eva5"
										id="t55_eva5" style="width: 150px;"
										onchange="CalcularResultado();"
										class="InformacionAdicional cmtData">
											<option value="" selected="selected"></option>
            <?php
            $rs = $objTablas->ValoraInformesME();
            $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $row['t55_eva5'], 'cod_ext');
            ?>
          </select></td>
									<td align="center"><textarea name="t55_eva5_obs" cols=""
											rows="2" class="InformacionAdicional cmtData"
											id="t55_eva5_obs" style="padding: 0px; width: 100%;"
											<?php if(!$isMtAllowed){ echo 'disabled'; } ?>><?php echo($row['t55_eva5_obs']);?></textarea>
									</td>
								</tr>

								<tr>
									<td align="left">Opinión de los beneficiarios respecto al
										proyecto y sus resultados</td>
									<td align="center"><select
										<?php if(!$isMtAllowed){ echo 'disabled'; } ?> name="t55_eva6"
										id="t55_eva6" style="width: 150px;"
										onchange="CalcularResultado();"
										class="InformacionAdicional cmtData">
											<option value="" selected="selected"></option>
              <?php
            $rs = $objTablas->ValoraInformesME();
            $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $row['t55_eva6'], 'cod_ext');
            ?>
              </select></td>
									<td align="center"><textarea name="t55_eva6_obs" cols=""
											rows="2" class="InformacionAdicional cmtData"
											id="t55_eva6_obs" style="padding: 0px; width: 100%;"
											<?php if(!$isMtAllowed){ echo 'disabled'; } ?>><?php echo($row['t55_eva6_obs']);?></textarea>
									</td>
								</tr>

								<tr>
									<td align="left">Gestión Financiera del Proyecto</td>
									<td align="center"><select
										<?php if(!$isMfAllowed){ echo 'disabled'; } ?> name="t55_eva7"
										id="t55_eva7" style="width: 150px;"
										onchange="CalcularResultado();"
										class="InformacionAdicional cmfData">
											<option value="" selected="selected"></option>
              <?php
            $rs = $objTablas->ValoraInformesME();
            $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $row['t55_eva7'], 'cod_ext');
            ?>
              </select></td>
									<td align="center"><textarea name="t55_eva7_obs" cols=""
											rows="2" class="InformacionAdicional cmfData"
											id="t55_eva7_obs" style="padding: 0px; width: 100%;"
											<?php if(!$isMfAllowed){ echo 'disabled'; } ?>><?php echo($row['t55_eva7_obs']);?></textarea>
									</td>
								</tr>

								<tr>
        <?php
        $aprobado = $objFunc->calificacionInforme($row['puntaje']);
        ?>
          <td align="right"><strong>RESULTADO</strong></td>
									<td align="center"><span id="spanTotResult"><?php echo($row['puntaje'])?>&nbsp;</span></td>
									<td align="left"><span id="spanTotResult2"><?php echo($aprobado)?>&nbsp;</span></td>
								</tr>
							</table> <br></td>
					</tr>
					<tr>
						<td align="left" valign="middle">
							<fieldset>
								<legend>Conclusiones</legend>
								<span style="font-weight: bold; font-size: 12px;">Logros </span><br>
								<textarea name="t55_logros" cols="" rows="4"
									class="InformacionAdicional" id="t55_logros"
									style="padding: 0px; width: 100%;"><?php echo($row['t55_logros']);?></textarea>
								<br /> <span style="font-weight: bold; font-size: 12px;">Dificultades
								</span><br>
								<textarea name="t55_dificul" cols="" rows="4"
									class="InformacionAdicional" id="t55_dificul"
									style="padding: 0px; width: 100%;"><?php echo($row['t55_dificultades']);?></textarea>
								<br />
							</fieldset> <br />
							<fieldset>
								<legend>Recomendaciones</legend>
								<span style="font-weight: bold; font-size: 12px;">Recomendaciones
									al Proyecto para el próximo POA</span><br>
								<textarea name="t55_reco_proy" cols="" rows="4"
									class="InformacionAdicional" id="t55_reco_proy"
									style="padding: 0px; width: 100%;"><?php echo($row['t55_reco_proy']);?></textarea>
								<br /> <span style="font-weight: bold; font-size: 12px;">Recomendaciones
									a Fondoempleo </span><br>
								<textarea name="t55_reco_fe" cols="" rows="4"
									class="InformacionAdicional" id="t55_reco_fe"
									style="padding: 0px; width: 100%;"><?php echo($row['t55_reco_fe']);?></textarea>
								<br />
							</fieldset> <br />
							<fieldset>
								<legend>Calificación</legend>
								<span style="font-weight: bold; font-size: 11px;">Ingresar
									comentario respectivo de acuerdo a la calificación dada </span><br>
								<textarea name="t55_califica" cols="" rows="5"
									class="InformacionAdicional" id="t55_califica"
									style="padding: 0px; width: 100%;"><?php echo($row['t55_recomendaciones']);?></textarea>
								<br />
							</fieldset>
						</td>
					</tr>
				</tbody>
				<tfoot>
				</tfoot>
			</table>
			<input name="t02_cod_proy" type="hidden"
				value="<?php echo($idProy);?>" class="InformacionAdicional" /> <input
				name="t55_id" type="hidden" value="<?php echo($idNum);?>"
				class="InformacionAdicional" /> <input type='hidden' id='isMt2'
				value=<?php echo ($isMtAllowed ? '1' : '0'); ?> /> <input
				type='hidden' id='isMf2'
				value=<?php echo ($isMfAllowed ? '1' : '0'); ?> />

			<script language="javascript" type="text/javascript">

	$(document).ready(function() {
		$('#t55_mt_flg').val('<?php echo $row['t55_mt']; ?>');
		$('#t55_mf_flg').val('<?php echo $row['t55_mf']; ?>');
		$('#t55_cmt_flg').val('<?php echo $row['t55_cmt']; ?>');
		$('#t55_cmf_flg').val('<?php echo $row['t55_cmf']; ?>');
		checkMonitorAccess();
	});
	
	function checkMonitorAccess()
	{
		var aUsrPrf = $('#usrPrf').val();
		var aMtPrf  = '<?php echo $objHC->MT;?>';
		var aMfPrf  = '<?php echo $objHC->MF;?>';
		var aCmtPrf  = '<?php echo $objHC->CMT;?>';
		var aCmfPrf  = '<?php echo $objHC->CMF;?>';
		var aAdmPrf  = '<?php echo $objHC->Admin;?>';
		
		if ((aUsrPrf == aMtPrf && $('#t55_mt_flg').val() == '1') ||
			(aUsrPrf == aCmtPrf && $('#t55_cmt_flg').val() == '1'))
			$('.cmtData').attr('disabled', 'disabled');
		if ((aUsrPrf == aMfPrf && $('#t55_mf_flg').val() == '1') ||
			(aUsrPrf == aCmfPrf && $('#t55_cmf_flg').val() == '1'))
			$('.cmfData').attr('disabled', 'disabled');
		if (aUsrPrf == aAdmPrf) {
			$('.cmtData, .cmfData').removeAttr("disabled");
		}
	}


function Guardar_InfAdicional()
{
<?php $ObjSession->AuthorizedPage(); ?>

	var BodyForm=$("#FormData .InformacionAdicional").serialize();
	
	if ($('#isMt2').val() == '1') {
		BodyForm += '&' + $('.cmfData').removeAttr('disabled').serialize();
	}
	if ($('#isMf2').val() == '1') {
		BodyForm += '&' + $('.cmtData').removeAttr('disabled').serialize();
	}
	if (($('#t55_cmt').attr('checked') == 1 && $('#t55_cmf').attr('checked') == 1) && 
		($('#t55_estado').val() == '<?php echo $objHC->EstInf_Rev; ?>' || 
		 $('#t55_estado').val() == '<?php echo $objHC->EstInf_Aprob; ?>')) {
		BodyForm += '&t55_estado=' + '<?php echo $objHC->EstInf_AprobCMT; ?>';
	}
	console.log(BodyForm);
	
	if(confirm("Estas seguro de Guardar la Informacion adicional, para el Informe ?"))
	{
		var sURL = "inf_unico_anual_process.php?action=<?php echo(md5('ajax_informacion_adicional'));?>";
		var req = Spry.Utils.loadURL("POST", sURL, true, InformacionAdicionalSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
	}
}
function InformacionAdicionalSuccessCallback	(req)
{
var respuesta = req.xhRequest.responseText;
respuesta = respuesta.replace(/^\s*|\s*$/g,"");
var ret = respuesta.substring(0,5);
if(ret=="Exito")
{
	var aEstado = $('#t55_cmt').attr('checked') == 1 && $('#t55_cmf').attr('checked') == 1 ? '<?php echo $objHC->EstInf_AprobCMT; ?>' : $('#t55_estado').val();
$('#t55_estado').val( aEstado );
LoadInfAdicional(true);
alert(respuesta.replace(ret,""));
}
else
{alert(respuesta);}  
}


function CalcularResultado()
{
	var eva1 = $("#t55_eva1 option[value='"+$("#t55_eva1").val()+"']").attr("title");
	var eva2 = $("#t55_eva2 option[value='"+$("#t55_eva2").val()+"']").attr("title");
	var eva3 = $("#t55_eva3 option[value='"+$("#t55_eva3").val()+"']").attr("title");
	var eva4 = $("#t55_eva4 option[value='"+$("#t55_eva4").val()+"']").attr("title");
	var eva5 = $("#t55_eva5 option[value='"+$("#t55_eva5").val()+"']").attr("title");
	var eva6 = $("#t55_eva6 option[value='"+$("#t55_eva6").val()+"']").attr("title");
	var eva7 = $("#t55_eva7 option[value='"+$("#t55_eva7").val()+"']").attr("title");
	
	if(isNaN(eva1) || eva1==null || eva1==''){eva1=0;}
	if(isNaN(eva2) || eva2==null || eva2==''){eva2=0;}
	if(isNaN(eva3) || eva3==null || eva3==''){eva3=0;}
	if(isNaN(eva4) || eva4==null || eva4==''){eva4=0;}
	if(isNaN(eva5) || eva5==null || eva5==''){eva5=0;}
	if(isNaN(eva6) || eva6==null || eva6==''){eva6=0;}
	if(isNaN(eva7) || eva7==null || eva7==''){eva7=0;}
	
	var resultado = parseInt(eva1) + parseInt(eva2) + parseInt(eva3) + parseInt(eva4) + parseInt(eva5) + parseInt(eva6) + parseInt(eva7) ;
	var resultext = "";
	
	if(resultado < 6) {resultext = "Desaprobado" ;}
	if(resultado >= 6 && resultado <= 10) {resultext = "Aprobado con Reservas" ;}
	if(resultado > 10) {resultext = "Aprobado" ;}
	
	$("#spanTotResult").html(resultado);
	$("#spanTotResult2").html(resultext);

}

</script>

		</div>
<?php if($idProy=="") { ?>
</form>
	<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd -->
</html>
<?php } ?>