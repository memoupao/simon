<?php 
include("../../../includes/constantes.inc.php");
include("../../../includes/validauser.inc.php");
require_once (constant('PATH_CLASS') . "BLFE.class.php");
require_once (constant('PATH_CLASS') . "BLEjecutor.class.php");
require_once (constant('PATH_CLASS') . "BLTablasAux.class.php");

$HC = new HardCode();
$objFE = new BLFE();
$objTab = new BLTablasAux();

$view = $objFunc->__Request('action');
$idProy = $objFunc->__Request('idProy');
$idVersion = $objFunc->__Request('idVersion');
$anio = $objFunc->__Request('anio');
$mes = $objFunc->__Request('mes');
$idDesemb = $objFunc->__Request('idDesemb');

$id_inst_cta = $HC->codigo_Fondoempleo;
$id_cta = 1;
$tipoPago = '';

if ($view == md5("edit")) {
    $row = $objFE->getDesembolsado($idDesemb);
    $objFunc->SetSubTitle("Editar Desembolso");
    $tipoPago = $row['t60_tip_pago'];
    $id_inst_cta = $row['t60_inst_ori'];
    $id_cta = $row['t60_cta_ori'];
} else {
    $objFunc->SetSubTitle("Nuevo Desembolso");
    $row = 0;
}

?>

<?php if($view=='') { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php
    
$objFunc->verifyAjax();
    if (! $objFunc->Ajax) {
        ?>
<meta name="keywords" content="<?php echo($objFunc->Keywords); ?>" />
<meta name="description" content="<?php echo($objFunc->MetaTags); ?>" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo($objFunc->Title);?></title>
<link href="../../../css/template.css" rel="stylesheet" media="all" />
<script src="../../../jquery.ui-1.5.2/jquery-1.2.6.js" type=text/javascript></script>
<script src="../../../jquery.ui-1.5.2/ui/ui.datepicker.js" type="text/javascript"></script>
<link href="../../../jquery.ui-1.5.2/themes/ui.datepicker.css" rel="stylesheet" type="text/css" />
<?php } ?>
</head>
<body class="oneColElsCtrHdr">
	<div id="container">
		<div id="mainContent">
			<form id="FormData" method="post" enctype="application/x-www-form-urlencoded" action="<?php echo($_SERVER['PHP_SELF']);?>">
				<div id="divContent">
 <?php } ?>
 
  					<div style="border: solid 1px #D3D3D3;">
						<div id="toolbar" style="height: 4px;" class="BackColor">
							<table border="0" cellpadding="0" cellspacing="0">
								<tr>
									<td width="9%">
										<button class="Button" onclick="guardarDesembolso(); return false;" value="Guardar">Guardar</button>
									</td>
									<td width="9%">
										<button class="Button" 
											onclick="editarDesembolsos(<?php echo($anio);?>, <?php echo($mes);?>); return false;"
											value="Cancelar">Cancelar
										</button>
									</td>
									<td width="8%">&nbsp;</td>
									<td width="47%" align="right"><?php echo($objFunc->SubTitle) ;?></td>
								</tr>
							</table>
						</div>
						<table width="685" cellpadding="2" cellspacing="0" class="TableEditReg">
							<!-- <tr valign="baseline">
								<td><b>Entregable por Desemb.</b></td>
								<td>
									<select name="num_periodo" id="num_periodo" style="width: 200px;">
										<option value="" selected="selected"></option>
									<?php 
										$rs = $objFE->getNombresPeriodos($idProy, $idVersion);
										$objFunc->llenarCombo($rs, 'entregable', 'entregable', '');
									?> 
									</select>
								</td>
								<td><b>Importe Aprobado: </b> <?php echo(number_format(($idTrim==1 ? $row["t59_mto_aprob"] : $row["t60_mto_aprob"]),2))?></td>
							</tr> -->
							<tr>
								<td><b>Tipo Desemb.</b></td>
								<td>
									<select name="dsmb_tip_pago" id="dsmb_tip_pago" class="dsmb" style="width: 200px;">
										<option value="" selected="selected"></option>
		                                <?php
		                                	$rs = $objTab->getTipoDesembolsosSE();
		                                    $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $tipoPago);
		                                ?>
		                            </select>
								</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td height="20"><b>Cuenta de Origen FE</b></td>
								<td>
									<select name="dsmb_cta_ori" class="dsmb" id="dsmb_cta_ori" style="width: 200px">
							        <?php
							        $objEjec = new BLEjecutor();
							        $rs = $objEjec->ListadoCuentas($id_inst_cta);
							        $objFunc->llenarComboI($rs, 't01_id_cta', 'descripcion', $id_cta);
							        $objEjec = NULL;
							        ?>
							        </select> 
								</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td width="161" height="20"><b>Modalidad</b></td>
								<td width="284">
									<select name="dsmb_modalidad" class="dsmb" id="dsmb_modalidad" style="width: 200px">
										<option value=""></option>
								        <?php 
								        $rs = $objTab->ListaTipoPagoDesembolso();
								        $objFunc->llenarComboI($rs, 'codigo', 'nombre', $row['t60_mod_pago']);
								        $objTab = NULL;
								        ?>
							        </select>
							    </td>
								<td width="153">&nbsp;</td>
							</tr>
							<tr>
								<td height="22"><b>Fecha de Giro</b></td>
								<td>
									<input name="dsmb_fec_giro" type="text" class="dsmb" id="dsmb_fec_giro" 
									style="text-align: center;" value="<?php echo($row['t60_fch_giro']); ?>" size="20" maxlength="10" /></td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td height="22"><b>Importe Desembolsado</b></td>
								<td>
									<input name="dsmb_mto" type="text" class="dsmb" id="dsmb_mto" 
									style="text-align: center;" value="<?php echo($row['t60_mto']);?>" size="20" maxlength="10" />
								</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td><b>Nro Cheque / Transferencia</b></td>
								<td>
									<input name="dsmb_cheque" type="text" class="dsmb" id="dsmb_cheque" style="text-align: center;"
									value="<?php echo($row['t60_cheque']);?>" size="20" maxlength="15" />
								</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td height="24"><b>Fecha Deposito</b></td>
								<td>
									<input name="dsmb_fec_deposito" type="text" class="dsmb" id="dsmb_fec_deposito"
									style="text-align: center;" value="<?php echo($row['t60_fch_depo']); ?>" size="20" maxlength="10" />
								</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td height="21"><b>Observaciones</b></td>
								<td colspan="2">
									<textarea name="dsmb_obs" cols="70" rows="2" class="dsmb" id="dsmb_obs"><?php echo($row['t60_obs']);?></textarea>
								</td>
							</tr>
						</table>
					</div>

					<input type="hidden" class="dsmb" name="dsmb_id" id="dsmb_id" value="<?php echo($idDesemb);?>"/>
					<input type="hidden" class="dsmb" name="dsmb_proy" id="dsmb_proy" value="<?php echo($idProy);?>"/>
					<input type="hidden" class="dsmb" name="dsmb_anio" id="dsmb_anio" value="<?php echo($anio);?>"/>
					<input type="hidden" class="dsmb" name="dsmb_mes" id="dsmb_mes" value="<?php echo($mes);?>"/>
					<input type="hidden" class="dsmb" name="dsmb_inst_ori" id="dsmb_inst_ori" value="<?php echo($id_inst_cta);?>"/>

					<script language="javascript">
  
  	$('#dsmb_mto').focusout(function(pEvent) {
		var aElem = $(pEvent.target);
		aElem.val(parseFloat(aElem.val().replace(/[^\d.]/g, '')).toFixed(2));
  	});
  	
  function guardarDesembolso()
	{
	<?php $ObjSession->AuthorizedPage(); ?>	
	 
	 if($('#dsmb_mto').val()==''){alert("Ingrese Monto del Desembolso"); $('#dsmb_mto').focus(); return;}
	 if($('#dsmb_fec_giro').val()==''){alert("Ingrese la Fecha de Giro"); $('#dsmb_fec_giro').focus(); return;}
	 if($('#dsmb_fec_deposito').val()==''){alert("Ingrese la Fecha del Deposito"); $('#dsmb_fec_deposito').focus(); return;}
	 
	 // Debe ser monto_saldo
	 // var monto_aprobado  = parseFloat(CNumber($('#txtmontoaprobado').val().replace(',', '')),2).toFixed(2);
	 
	 var monto_desembolsado_fecha  = parseFloat(CNumber($('#dsmb_mto').val().replace(',', '')),2).toFixed(2);
	 var monto_desembolsar  = parseFloat(CNumber($('#dsmb_mto').val().replace(',', ''))).toFixed(2);
	 
	 if(monto_desembolsar <=0 ) {alert("Ingrese un Monto mayor a 0"); return; }
	 
	 // if ( parseFloat((parseFloat(monto_desembolsar)) > parseFloat(monto_saldo)) {
		//  alert("El monto a desembolsar no es correcto, se esta excediendo del Monto Total Aprobado");
		//  return ;
	 // }
	 
	 var fgiro = CDate($('#dsmb_fec_giro').val());
	 var fdepo = CDate($('#dsmb_fec_deposito').val());
	 
	 if( fgiro > fdepo )
	 {
		 alert("La Fecha de Giro, no pouede ser posterior a la Fecha de Deposito");
		 return;
	 }
	 
	 var BodyForm = $("#FormData .dsmb").serialize();
	 var sURL = "ejec_desemb_entregable_process.php?action=<?php echo(md5("guardar"));?>&mode=<?php echo($view);?>" ;
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
		 alert(respuesta.replace(ret,""));
		 editarDesembolsos(<?php echo($anio);?>, <?php echo($mes);?>)
		 //parent.btnRefrescar.click();
		}
		else
		{  alert(respuesta); }
	}
	
	$('.ejecdesemb:input[disabled="disabled"]').css("background-color", "#eeeecc") ;
	
	$("#dsmb_fec_giro ").datepicker();
	$("#dsmb_fec_deposito").datepicker();

	$("#dsmb_mto").numeric();

	function editarDesembolsos(anio, mes)
    {
        var url = "ejec_desemb_entregable_edit.php?idProy=<?php echo($idProy);?>&idVersion=<?php echo($idVersion);?>&anio="+anio+"&mes="+mes+"&mode=<?php echo(md5("edit"));?>";
        loadPopup("<?php echo('Modificación');?> de Desembolsos", url);
    }
	
</script>


 <?php if($view=='') { ?>
				</div>
			</form>
		</div>
	</div>

</body>
</html>
<?php } ?>