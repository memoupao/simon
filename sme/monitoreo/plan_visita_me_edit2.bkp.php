<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauser.inc.php"); ?>

<?php
require (constant("PATH_CLASS") . "BLMonitoreo.class.php");
require (constant("PATH_CLASS") . "BLTablasAux.class.php");
require (constant("PATH_CLASS") . "HardCode.class.php");

$action = $objFunc->__Request('action');

$HC = new HardCode();
$objMoni = new BLMonitoreo();

error_reporting("E_PARSE");
$idProy = $objFunc->__Request('idProy');
$id = $objFunc->__Request('t31_id');

if ($action == md5("ajax_edit")) {
    $objFunc->SetSubTitle("Editar Visita");
    $row = $objMoni->PlanVisitasSeleccionar($idProy, $id);
    $solicita = false;
}
if ($action == md5("ajax_nuevo")) {
    $objFunc->SetSubTitle("Nueva Visita");
    $row = NULL;
    $solicita = false;
}

if ($action == md5("ajax_solicita_viaje")) {
    $objFunc->SetSubTitle("Solicitud de Viaje");
    $row = $objMoni->PlanVisitasSeleccionar($idProy, $id);
    $solicita = true;
}

if ($objFunc->__QueryString() == "") {
    ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/templateIframe.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->

<title>Coronograma Visitas</title>
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->
<script language="javascript" type="text/javascript"
	src="../../jquery.ui-1.5.2/jquery-1.2.6.js"></script>
<!-- InstanceEndEditable -->
<link href="../../css/itemplate.css" rel="stylesheet" type="text/css"
	media="all" />
</head>

<body>
	<!-- InstanceBeginEditable name="tBody" -->
	<form action="#" method="post" enctype="multipart/form-data"
		name="frmMain" id="frmMain">
<?php
}
?>
<script src="../../jquery.ui-1.5.2/ui/ui.datepicker.js"
			type="text/javascript"></script>
		<link href="../../jquery.ui-1.5.2/themes/ui.datepicker.css"
			rel="stylesheet" type="text/css" />
		<script src="../../jquery.ui-1.5.2/jquery.numeric.js"
			type="text/javascript"></script>
		<script src="../../js/commons.js" type="text/javascript"></script>

		<div id="toolbar" style="height: 4px;" class="BackColor">
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td width="7%"><button class="Button"
							onclick="Guardar_PlanVisita(); return false;" value="Guardar">Guardar
						</button></td>
					<td width="27%"><button class="Button"
							onclick="spryPopupDialog01.displayPopupDialog(false); return false;"
							value="Cancelar" style="white-space: nowrap;">Cerrar y Volver</button></td>
					<td width="10%">&nbsp;</td>
					<td width="3%">&nbsp;</td>
					<td width="3%">&nbsp;</td>
					<td width="3%">&nbsp;</td>
					<td width="47%" align="right"><?php echo($objFunc->SubTitle) ;?></td>
				</tr>
			</table>
		</div>



		<div>
			<br />
			<table width="500" border="0" cellspacing="2" cellpadding="0"
				class="TableEditReg">

				<tbody <?php if($solicita){echo('style="display:none;"');}?>>
					<tr>
						<td width="96"><strong>N&deg; Visita</strong></td>
						<td width="406"><input name="t31_id" type="text" id="t31_id"
							value="<?php echo($row['t31_id']);?>" size="3" maxlength="4"
							readonly="readonly" style="text-align: center;" /></td>
					</tr>
					<tr>
						<td><strong>Periodo Visita</strong></td>
						<td><select name="t31_mes" id="t31_mes" style="width: 100px;">
								<option value="" selected="selected"></option>
        <?php
        $objTablas = new BLTablasAux();
        $rsMeses = $objTablas->ListadoMesesCalendario();
        $objFunc->llenarCombo($rsMeses, 'codigo', 'descripcion', $row['t31_mes']);
        
        ?>
        </select> <input name="t31_anio" type="text" id="t31_anio"
							size="10" maxlength="4" style="text-align: center;"
							value="<?php echo($row['t31_anio']);?>" /></td>
					</tr>
					<tr>
						<td colspan="2">
							<fieldset>
								<legend style="padding: 1px; color: #00F; font-weight: bold;">Costo
									de la Visita</legend>
								<table width="500" border="0" cellspacing="2" cellpadding="0"
									style="padding: 1px;">
									<tr>
										<td width="95"><strong>Primer Pago</strong></td>
										<td width="405"><input name="t31_mto_v1" type="text"
											id="t31_mto_v1" size="20" maxlength="15"
											style="text-align: right;" onkeyup="CalcularTotalCosto();"
											value="<?php echo($row['t31_mto_v1']);?>" /></td>
									</tr>
									<tr>
										<td><strong>Segundo Pago</strong></td>
										<td><input name="t31_mto_v2" type="text" id="t31_mto_v2"
											size="20" maxlength="15" style="text-align: right;"
											onkeyup="CalcularTotalCosto();"
											value="<?php echo($row['t31_mto_v2']);?>" /></td>
									</tr>
									<tr>
										<td><strong>Costo Total </strong></td>
										<td><input name="t31_mto_tot" type="text" disabled="disabled"
											id="t31_mto_tot" style="text-align: right;" size="20"
											maxlength="20"
											value="<?php echo(number_format($row['t31_mto_tot'],2));?>" /></td>
									</tr>
								</table>

							</fieldset>
						</td>
					</tr>
					<tr>
						<td><strong>Observaciones</strong></td>
						<td align="left"><textarea name="t31_obs" cols="70" rows="3"
								id="t31_obs" style="text-align: justify;"> <?php echo($row['t31_obs']);?> </textarea></td>
					</tr>
				</tbody>

				<tbody <?php if(!$solicita){echo('style="display:none;"');}?>>
					<tr>
						<td colspan="2"><fieldset>
								<legend style="padding: 1px; color: #00F; font-weight: bold;">Solicitud
									de Viaje de la Visita Programada </legend>
								<table width="500" border="0" cellspacing="2" cellpadding="0"
									style="padding: 1px;">
									<tr>
										<td width="106"><strong>Inicio de Visita</strong></td>
										<td width="388"><input name="t31_fec_vis" type="text"
											id="t31_fec_vis" size="15" maxlength="10"
											style="text-align: center;"
											value="<?php echo($row['t31_fec_vis']);?>" /></td>
									</tr>
									<tr>
										<td><strong>Termino de Visita</strong></td>
										<td><input name="t31_fec_ter" type="text" id="t31_fec_ter"
											size="15" maxlength="10" style="text-align: center;"
											value="<?php echo($row['t31_fec_ter']);?>" /></td>
									</tr>
          
          <?php if($ObjSession->PerfilID==$HC->GP) { ?>
          							<tr>
										<td><strong>V.B. Gestor de Proyectos</strong></td>
										<td><input name="t31_vb_v1" type="checkbox" id="t31_vb_v1"
											value="1"
											<?php if($row['t31_vb_v1']=='1'){echo('checked="checked"');} ?> />											
										</td>
									</tr>
          <?php }  ?>

          </table>
							</fieldset></td>
					</tr>
				</tbody>



				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
			</table>
			<input type="hidden" name="t02_cod_proy"
				value="<?php echo($idProy);?>" />
			<script language="javascript" type="text/javascript">

function getAlertText(pText)
{
	return $('<div><./div>').html(pText).text();
}
function Guardar_PlanVisita()
{
	 <?php $ObjSession->AuthorizedPage(); ?>	
	 var anio = $('#t31_anio').val();
	 var mes = $('#t31_mes').val();
	 
	 var p1 = CNumber($('#t31_mto_v1').val());
	 var p2 = CNumber($('#t31_mto_v2').val());
	 
	 if (anio=='' || anio==null){alert(getAlertText("Ingrese Año planeado para la visita.")); $("t31_anio").focus(); return false;}
	 if ( isNaN(parseInt(anio)) ) {alert(getAlertText("Año planeado para la visita no es válido."));$("t31_anio").focus(); return false;}
	 if(mes=='' || mes==null){alert("Seleccione Mes planeado para la visita.");return false;}
	 if(p1=='' || p1==null){alert("Ingrese primer pago."); $('#t31_mto_v1').focus(); return false;}
	 if (isNaN(parseFloat($('#t31_mto_v1').val()))) {alert("Monto ingresado como primer pago no es válido."); $('#t31_mto_v1').focus(); return false;}
	 if(p2=='' || p2==null){alert("Ingrese segundo pago.");$('#t31_mto_v2').focus(); return false;}
	 if (isNaN(parseFloat($('#t31_mto_v2').val()))) {alert("Monto ingresado como segundo pago no es válido."); $('#t31_mto_v2').focus(); return false;}
	 
	
	 var inicio_fecha = $("#t31_fec_vis").val();
	 var termino_fecha = $("#t31_fec_ter").val();
	if(inicio_fecha!="" && termino_fecha!=""){
	
		var inicio = inicio_fecha.split("/");
		var termino = termino_fecha.split("/");
		
		var dateInicio = new Date(inicio[2], inicio[1], inicio[0]);
		var dateTermino = new Date(termino[2], termino[1], termino[0]);
		var diferencia = dateTermino.getTime() - dateInicio.getTime();
		var dias = Math.floor(diferencia / (1000 * 60 * 60 * 24));
		if(dias < 3){
			alert("El período de visita debe ser mayor o igual a 4 dias");
			return false;
		}
		if(dias>30){
			alert("Fecha de la Visita: No esta en el plazo de los 30 dias");
			return false;
		}
}

 
 if($('#txtCodProy').val()==""){alert("Error: Seleccione Proyecto !!!"); return false;}
 
 
 var BodyForm=$('#FormData').serialize();
 
 <?php if($action==md5("ajax_edit") || $action==md5("ajax_solicita_viaje")) {?>
 var sURL = "plan_visita_me_process.php?action=<?php echo(md5("ajax_edit"));?>";
 <?php } else {?>
 var sURL = "plan_visita_me_process.php?action=<?php echo(md5("ajax_new"));?>";
 <?php }?>
 
 var req = Spry.Utils.loadURL("POST", sURL, true, PlanVisitaSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });

}
function PlanVisitaSuccessCallback	(req)
	{
	  var respuesta = req.xhRequest.responseText;
	  respuesta = respuesta.replace(/^\s*|\s*$/g,"");
	  var ret = respuesta.substring(0,5);
	  if(ret=="Exito")
	  {
		LoadDataGrid('<?php echo($idProy);?>');
		spryPopupDialog01.displayPopupDialog(false);
		alert(respuesta.replace(ret,""));
	  }
	  else
	  {alert(respuesta);}  
	}


function CalcularTotalCosto()
{
	var v1 = CNumber($("#t31_mto_v1").val()) ;
	var v2 = CNumber($("#t31_mto_v2").val()) ;
	var tot = v1 + v2 ;
	$("#t31_mto_tot").val(tot.toFixed(2));
}


function CNumber(str)
{
  var numero =0;
  if (str !="" && str !=null)
  { numero = parseFloat(str);}
  
  if(isNaN(numero)) { numero=0;}
 
 return numero;
}
function planV(pEvent){
	var inicio_fecha = $("#t31_fec_vis").val();
	var termino_fecha = $("#t31_fec_ter").val();
	if(inicio_fecha!="" && termino_fecha!=""){

	var inicio = inicio_fecha.split("/");
	var termino = termino_fecha.split("/");
	
	var dateInicio = new Date(inicio[2], inicio[1], inicio[0]);
	var dateTermino = new Date(termino[2], termino[1], termino[0]);
	var diferencia = dateTermino.getTime() - dateInicio.getTime();
	var dias = Math.floor(diferencia / (1000 * 60 * 60 * 24));
	if(dias < 3){
		console.log("dias: " + dias);
		alert("El período de visita debe ser mayor o igual a 4 dias");
		if (pEvent) $(pEvent.target).val();
		return false;
	}
	if(dias>30){
		alert("Fecha de la Visita: No esta en el plazo de los 30 dias");
		return false;
	}
}

}

$("#t31_fec_vis").change(function(pEvent){
	planV(pEvent);
});
$("#t31_fec_ter").change(function(pEvent){
	planV(pEvent);
});

$('#t31_anio').numeric().pasteNumeric();
$("#t31_mto_v1").numeric().pasteNumeric();
$("#t31_mto_v2").numeric().pasteNumeric();
$("#t31_fec_vis").datepicker();
$("#t31_fec_ter").datepicker();

</script>
		</div>
<?php if($idProy=="") { ?>
</form>
	<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd -->
</html>
<?php } ?>