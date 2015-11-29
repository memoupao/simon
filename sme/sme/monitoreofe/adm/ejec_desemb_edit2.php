<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>
<?php

require_once (constant('PATH_CLASS') . "BLFE.class.php");
require_once (constant('PATH_CLASS') . "BLEjecutor.class.php");
require_once (constant('PATH_CLASS') . "BLTablasAux.class.php");

$HC = new HardCode();
$objFE = new BLFE();

$view = $objFunc->__Request('action');
$idProy = $objFunc->__Request('idProy');
$idTrim = $objFunc->__Request('idTrim');
$idAprobacion = $objFunc->__Request('idAprobacion');
$idDesemb = $objFunc->__Request('idDesemb');

if ($view == md5("ajax_edit")) {
    $row = $objFE->Desembolso_Seleccionar($idProy, $idTrim, $idDesemb);
    $objFunc->SetSubTitle("Editar Desembolso (#" . $idDesemb . ")");
} else {
    $objFunc->SetSubTitle("Nuevo Desembolso");
    $row = 0;
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
 
  <div style="border: solid 1px #D3D3D3;">
						<div id="toolbar" style="height: 4px;" class="BackColor">
							<table border="0" cellpadding="0" cellspacing="0">
								<tr>
									<td width="9%"><button class="Button"
											onclick="GuardarDesembolso(); return false;" value="Guardar"
											<?php if($ObjSession->PerfilID!=$HC->ADM) {echo('disabled="disabled"');} ?>>Guardar
										</button></td>
									<td width="9%"><button class="Button"
											onclick="EditarEjecucion('<?php echo($idProy);?>', '<?php echo($idTrim);?>', '<?php echo($idAprobacion);?>'); return false;"
											value="Cancelar">Cancelar</button></td>
									<td width="8%">&nbsp;</td>
									<td width="47%" align="right"><?php echo($objFunc->SubTitle) ;?></td>
								</tr>
							</table>
						</div>
						<table width="590" cellpadding="0" cellspacing="0"
							class="TableEditReg">
							<tr>
								<td height="20"><strong>Cuenta de Origen FE</strong></td>
								<td><select name="cbo_cta_ori" class="ejecdesemb"
									id="cbo_cta_ori" style="width: 200px">
          <?php
        $objEjec = new BLEjecutor();
        $id_inst_cta = $HC->codigo_Fondoempleo;
        $id_cta = 1;
        
        if ($view == md5("ajax_edit")) {
            $id_inst_cta = $row['t60_inst_ori'];
            $id_cta = $row['t60_cta_ori'];
        }
        
        $rs = $objEjec->ListadoCuentas($id_inst_cta);
        $objFunc->llenarComboI($rs, 't01_id_cta', 'descripcion', $id_cta);
        $objEjec = NULL;
        ?>
        </select> <input type="hidden" name="txtcodigo_desemb"
									id="txtcodigo_desemb" value="<?php echo($idDesemb);?>"
									class="ejecdesemb" /></td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td width="161" height="20"><strong>Modalidad</strong></td>
								<td width="284"><select name="cbomodalidad" class="ejecdesemb"
									id="cbomodalidad" style="width: 180px">
										<option value=""></option>
          <?php
        $objTab = new BLTablasAux();
        $rs = $objTab->ListaTipoPagoDesembolso();
        $objFunc->llenarComboI($rs, 'codigo', 'nombre', $row['t60_tip_pago']);
        $objTab = NULL;
        ?>
        </select></td>
								<td width="153">&nbsp;</td>
							</tr>
							<tr>
								<td height="22"><strong>Fecha de Giro</strong></td>
								<td><input name="txtfecgiro" type="text" class="ejecdesemb"
									id="txtfecgiro" style="text-align: center;"
									value="<?php echo($row['t60_fch_giro']); ?>" size="20"
									maxlength="10" /></td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td height="22"><strong>Importe Desembolsado</strong></td>
								<td><input name="txtmontodesemb" type="text" class="ejecdesemb"
									id="txtmontodesemb" style="text-align: center;"
									value="<?php echo($row['t60_mto_des']);?>" size="20"
									maxlength="10" /></td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td><strong>Nro Cheque / Transferencia</strong></td>
								<td><input name="txtnrocheque" type="text" class="ejecdesemb"
									id="txtnrocheque" style="text-align: center;"
									value="<?php echo($row['t60_nro_cheque']);?>" size="20"
									maxlength="15" /></td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td height="24"><strong>Fecha Deposito</strong></td>
								<td><input name="txtfechadeposito" type="text"
									class="ejecdesemb" id="txtfechadeposito"
									style="text-align: center;"
									value="<?php echo($row['t60_fch_depo']); ?>" size="20"
									maxlength="10" /></td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td height="21"><strong>Observaciones</strong></td>
								<td colspan="2"><textarea name="txtobserva" cols="70" rows="2"
										class="ejecdesemb" id="txtobserva"><?php echo($row['t60_obs_tippago']);?></textarea></td>
							</tr>
						</table>
					</div>


					<script language="javascript">
  
  	$('#txtmontodesemb').focusout(function(pEvent) {
		var aElem = $(pEvent.target);
		aElem.val(parseFloat(aElem.val().replace(/[^\d.]/g, '')).toFixed(2));
  	});
  	
  function GuardarDesembolso()
	{
	<?php $ObjSession->AuthorizedPage(); ?>	
	 
	 if($('#txtmontodesemb').val()==''){alert("Ingrese Monto del Desembolso"); $('#txtmontodesemb').focus(); return;}
	 if($('#txtfecgiro').val()==''){alert("Ingrese la Fecha de Giro"); $('#txtfecgiro').focus(); return;}
	 if($('#txtfechadeposito').val()==''){alert("Ingrese la Fecha del Deposito"); $('#txtfechadeposito').focus(); return;}
	 
	 var monto_aprobado  = parseFloat(CNumber($('#txtmontoaprobado').val().replace(',', '')),2).toFixed(2);
	 var monto_desembolsado_fecha  = parseFloat(CNumber($('#txtmontodesembolsado').val().replace(',', '')),2).toFixed(2);
	 var monto_desembolsar  = parseFloat(CNumber($('#txtmontodesemb').val().replace(',', ''))).toFixed(2);
	 
	 if(monto_desembolsar <=0 ) {alert("Ingrese un Monto mayor a 0"); return; }
	 
	 if ($('#t60_nro_aprob').val()) {
		 if ( parseFloat(monto_desembolsar) > parseFloat(monto_aprobado)) {
			 alert("El monto a desembolsar no es correcto, se esta excediendo del Monto Parcial Aprobado");
			 return ;
		 }
	 }
	 else {
		 if ( parseFloat((parseFloat(monto_desembolsar) + parseFloat(monto_desembolsado_fecha)).toFixed(2)) > parseFloat(monto_aprobado)) {
			 alert("El monto a desembolsar no es correcto, se esta excediendo del Monto Total Aprobado");
			 return ;
		 }
	 }
	 
	 var fgiro = CDate($('#txtfecgiro').val());
	 var fdepo = CDate($('#txtfechadeposito').val());
	 
	 if( fgiro > fdepo )
	 {
		 alert("La Fecha de Giro, no pouede ser posterior a la Fecha de Deposito");
		 return;
	 }
	 
	
	 var BodyForm = $("#FormData .ejecdesemb").serialize() ;
	 
	 var sURL = "ejec_desemb_process.php?action=<?php echo(md5("guardar"));?>&mode=<?php echo($view);?>" ;
	 var req = Spry.Utils.loadURL("POST", sURL, true, MySuccessGuardarDesembolso, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCall });

	 return false;
	}
	
	function MySuccessGuardarDesembolso(req)
	{
		var respuesta = req.xhRequest.responseText;
		respuesta = respuesta.replace(/^\s*|\s*$/g,"");
		var ret = respuesta.substring(0,5);
		if(ret=="Exito")
		{
		 EditarEjecucion('<?php echo($idProy);?>', '<?php echo($idTrim);?>', '<?php echo($idAprobacion);?>');
		 alert(respuesta.replace(ret,""));
		}
		else
		{  alert(respuesta); }
	}
	
	$('.ejecdesemb:input[disabled="disabled"]').css("background-color", "#eeeecc") ;
	
	$("#txtfecgiro ").datepicker();
	$("#txtfechadeposito").datepicker();

	$("#txtmontodesemb").numeric();
	
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

