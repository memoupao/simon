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
$anio = $objFunc->__Request('anio');
$entregable = $objFunc->__Request('entregable');
$idVersion = $objFunc->__Request('version');

if ($action == md5("ajax_edit")) {
    $objFunc->SetSubTitle("Editar Visita");
    $rs = $objMoni->getDetailPlanVisitasProyecto($idProy, $idVersion, $anio, $entregable);
    $row = mysql_fetch_array($rs);
    
    $t02_cod_proy = $row['t02_cod_proy'];
    $t25_anio = $row['t25_anio'];
    $t25_entregable = $row['t25_entregable'];
                
} else {
	exit;
}


$objTablas = new BLTablasAux();

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
			<form id="frmVisita" name="frmVisita" method="POST">
			<table width="500" border="0" cellspacing="2" cellpadding="0"
				class="TableEditReg">

				<tbody>
				
					<tr>
						<td width="96"><strong>Año</strong></td>
						<td width="406">
							<?php echo($row['anio']);?>
						</td>
					</tr>
					
					<tr>
						<td width="96"><strong>Entregable</strong></td>
						<td width="406">
							<?php echo($row['entregable']);?>
						</td>
					</tr>
					<tr>
						<td><strong>Periodo</strong></td>
						<td>
							<?php echo($row['periodo']);?>   				
        				</td>
					</tr>
					<tr>
						<td><strong>Estado</strong></td>
						<td>
							<select name="estado" class="Boton" id="estado" style="width: 150px;" >
								<option value="" selected="selected"></option>
                                <?php
                                	$rs = $objTablas->getValoresEstadoCronogramaVisitas();
                                    $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $row['estado'], 'cod_ext');
                                ?>
                            </select>
        				</td>
					</tr>
					
					<tr>
						<td><strong>Inicio</strong></td>
						<td>
							<input name="fecha_visita_inicio" type="text" id="fecha_visita_inicio" size="20" maxlength="15" value="<?php echo($row['fecha_visita_inicio']);?>" />
        				</td>
					</tr>
					
					<tr>
						<td><strong>Término</strong></td>
						<td>
							<input name="fecha_visita_termino" type="text" id="fecha_visita_termino" size="20" maxlength="15" value="<?php echo($row['fecha_visita_termino']);?>" />
        				</td>
					</tr>
					
					<tr>
						<td width="95"><strong>Primer Pago</strong></td>
						<td width="405"><input name="costo_pago_1" type="text"
							id="costo_pago_1" size="20" maxlength="15"
							style="text-align: right;" 
							value="<?php echo($row['costo_pago_1']);?>" /></td>
					</tr>
					<tr>
						<td><strong>Segundo Pago</strong></td>
						<td><input name="costo_pago_2" type="text" id="costo_pago_2"
							size="20" maxlength="15" style="text-align: right;"							
							value="<?php echo($row['costo_pago_2']);?>" /></td>
					</tr>
					
					<tr>
						<td><strong>Supervisor</strong></td>
						<td>
							<select name="id_supervisor" class="Boton" id="id_supervisor" style="width: 150px;" >
								<option value="" selected="selected"></option>
                                <?php
                                	$arSupervisores = $objMoni->getListaSupervisoresVisita($idProy,$idVersion);                                	
                                    foreach($arSupervisores as $item) { ?>
								<option value="<?php echo $item['codigo'];?>" <?php if ($item['codigo'] == $row['id_supervisor']) echo 'selected';?>>
									<?php echo $item['nombres'];?>
								</option>
								<?php } ?>
                            </select>
        				</td>
					</tr>
					
				</tbody>

				



				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
			</table>
			<input type="hidden" name="t02_cod_proy" value="<?php echo($t02_cod_proy);?>" />
			<input type="hidden" name="t25_anio" value="<?php echo($t25_anio);?>" />
			<input type="hidden" name="t25_entregable" value="<?php echo($t25_entregable);?>" />
			
			
			</form>
			<script language="javascript" type="text/javascript">

function getAlertText(pText)
{
	return $('<div><./div>').html(pText).text();
}
function Guardar_PlanVisita()
{
	 <?php $ObjSession->AuthorizedPage(); ?>	
	 var p1 = CNumber($('#costo_pago_1').val());
	 var p2 = CNumber($('#costo_pago_2').val());
	 	 	 
	 if(p1=='' || p1==null){alert("Ingrese primer pago."); $('#costo_pago_1').focus(); return false;}
	 if (isNaN(parseFloat($('#costo_pago_1').val()))) {alert("Monto ingresado como primer pago no es válido."); $('#t31_mto_v1').focus(); return false;}
	 if(p2=='' || p2==null){alert("Ingrese segundo pago.");$('#costo_pago_2').focus(); return false;}
	 if (isNaN(parseFloat($('#costo_pago_2').val()))) {alert("Monto ingresado como segundo pago no es válido."); $('#t31_mto_v2').focus(); return false;}
	 
	
	 var inicio_fecha = $("#fecha_visita_inicio").val();
	 var termino_fecha = $("#fecha_visita_termino").val();
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

 	
	 var BodyForm=$('#frmVisita').serialize();
	 
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




function CNumber(str)
{
  var numero =0;
  if (str !="" && str !=null)
  { numero = parseFloat(str);}
  
  if(isNaN(numero)) { numero=0;}
 
 return numero;
}

 

$("#costo_pago_1").numeric().pasteNumeric();
$("#costo_pago_2").numeric().pasteNumeric();
$("#fecha_visita_inicio").datepicker();
$("#fecha_visita_termino").datepicker();

</script>
		</div>
<?php if($idProy=="") { ?>
</form>
	<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd -->
</html>
<?php } ?>