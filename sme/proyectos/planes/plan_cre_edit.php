<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>

<?php
require (constant("PATH_CLASS") . "BLPOA.class.php");
require (constant("PATH_CLASS") . "BLTablasAux.class.php");

$objPOA = new BLPOA();

error_reporting("E_PARSE");
$idProy = $objFunc->__Request('idProy');
$idVersion = $objFunc->__Request('idVersion');
$idComp = $objFunc->__Request('idComp');
$idAct = $objFunc->__Request('idAct');
$idSub = $objFunc->__Request('idSub');

$rowcab = $objPOA->PlanCred_Seleccionar($idProy, $idVersion, $idComp, $idAct, $idSub);
$idSub2 = $rowcab['subplan'];

$action = $objFunc->__Request('action');

if ($action == "") {
    ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/templateIframe.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->

<title></title>
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
   
  <div>
			<div id="toolbar" style="height: 4px;" class="BackColor">
				<table width="100%" border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td width="9%"><button class="Button"
								onclick="Guardar_Plan(); return false;" value="Guardar">Guardar
							</button></td>
						<td width="25%"><button class="Button"
								onclick="LoadPlanes(); return false;" value="Cancelar">Cerrar y
								Volver</button></td>
						<td width="9%">&nbsp;</td>
						<td width="2%">&nbsp;</td>
						<td width="2%">&nbsp;</td>
						<td width="2%">&nbsp;</td>
						<td width="47%" align="right">Plan de Créditos a Beneficiarios</td>
					</tr>
				</table>
			</div>
			<div>
				<table width="788" border="0" cellpadding="0" cellspacing="1"
					class="TableEditReg">
					<tr>
						<td width="12%" height="35" nowrap="nowrap"><strong>Actividad</strong></td>
						<td colspan="5">
							<div style="border: 1px solid #7F9FAA; width: 90%;">
        <?php echo( "<b>".$rowcab["codigo"]."</b>.- ".$rowcab["subact"]); ?>
        </div>
						</td>
					</tr>
					<tr>
						<td height="33"><strong>N&ordm; Productores que recibiran el
								credito</strong></td>
						<td width="18%"><input name="txtnprods" type="text" id="txtnprods"
							value="<?php echo($rowcab['t12_nro_ben']);?>" size="15"
							maxlength="8" style="text-align: center;" /></td>
						<td width="17%"><strong>Monto máximo del Crédito x Beneficiario</strong></td>
						<td width="16%"><input name="txtmonto" type="text" id="txtmonto"
							value="<?php echo($rowcab['t12_mto_ben']);?>" size="20"
							maxlength="12" style="text-align: center;" /></td>
						<td width="17%"><strong>Numero máximo de Cuotas x Beneficiario</strong></td>
						<td width="20%"><input name="txtcuotas" type="text" id="txtcuotas"
							value="<?php echo($rowcab['t12_nro_cuo']);?>" size="15"
							maxlength="2" style="text-align: center;" /></td>
					</tr>
					<tr>
						<td height="66"><strong>Observaciones </strong></td>
						<td colspan="5"><textarea name="txtobservaciones" cols="95"
								rows="3" id="txtobservaciones"><?php echo($rowcab['t12_obs']);?></textarea></td>
					</tr>
					<tr>
						<td colspan="6">
							<fieldset>
								<legend style="font-size: 10px; color: #003; font-weight: bold;">Beneficiarios
									que recibiran el credito para la Actividad</legend>
								<div id="divBenefCred"></div>
							</fieldset>
						</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td colspan="5"><input type="hidden" name="t02_cod_proy"
							value="<?php echo($idProy);?>" /> <input type="hidden"
							name="t02_version" value="<?php echo($idVersion);?>" /> <input
							type="hidden" name="t08_cod_comp" value="<?php echo($idComp);?>" />
							<input type="hidden" name="t09_cod_act"
							value="<?php echo($idAct);?>" /> <input type="hidden"
							name="t09_cod_sub" value="<?php echo($idSub);?>" /></td>
					</tr>
				</table>
			</div>


		</div>
		<script language="javascript" type="text/javascript">
    function Guardar_Plan()
	{
		 <?php $ObjSession->AuthorizedPage(); ?>	
		 var nprods = $('#txtnprods').val();
		 var monto = $('#txtmonto').val();
		 
		 if(nprods=='' || nprods==null){alert("Ingrese Numero de Beneficiarios que accederan al Credito"); $('#txtnprods').focus(); return false;}
		 if(monto=='' || monto==null){alert("Ingrese Monto del Credito por Beneficiario"); $('#txtmonto').focus(); return false;}

		var arrParams = new Array();
			arrParams[0] = "idProy=<?php echo($idProy); ?>" ; 
			arrParams[1] = "idVersion=<?php echo($idVersion); ?>" ;
			arrParams[2] = "idComp=<?php echo($idComp); ?>" ;
			arrParams[3] = "idAct=<?php echo($idAct); ?>" ;
			arrParams[4] = "idSub=<?php echo($idSub); ?>" ;
			arrParams[5] = "txtnprods=" + nprods ;
			arrParams[6] = "txtmonto=" + monto ;
			arrParams[7] = "txtcuotas=" + $("#txtcuotas").val() ;
			arrParams[8] = "txtobservaciones=" + $("#txtobservaciones").val() ;
		
		  var BodyForm = arrParams.join("&");
		  var sURL = "plan_cre_process.php?action=<?php echo(md5("ajax_save_plan_cab"));?>";
		 
		  var req = Spry.Utils.loadURL("POST", sURL, true, GuardarPlanCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
	}
   function GuardarPlanCallback	(req)
	{
	  var respuesta = req.xhRequest.responseText;
	  respuesta = respuesta.replace(/^\s*|\s*$/g,"");
	  var ret = respuesta.substring(0,5);
	  if(ret=="Exito")
	  {
		EditarPlanCred('<?php echo($idComp); ?>','<?php echo($idAct); ?>','<?php echo($idSub); ?>');
		alert(respuesta.replace(ret,""));
	  }
	  else
	  {alert(respuesta);}  
	}
	
	function onErrorLoad			(req)
	{
		alert("Ocurrio un error al cargar los datos \n" +  req.xhRequest.responseText);
	}
	
	
	function LoadPlanCreditosBenef()
	{
		var BodyForm = "action=<?php echo(md5("lista_at"));?>&idProy=<?php echo($idProy);?>&idVersion=<?php echo($idVersion);?>&idComp=<?php echo($idComp);?>&idAct=<?php echo($idAct);?>&idSub=<?php echo($idSub);?>";
	 	var sURL = "plan_cre_benef.php";
		$('#divBenefCred').html("<p align='center'><img src='<?php echo(constant("PATH_IMG"));?>indicator.gif' width='16' height='16' /><br>Cargando..<br></p>");
	 	var req = Spry.Utils.loadURL("POST", sURL, true, SuccessPlanCreditosBenef, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
	}
	function SuccessPlanCreditosBenef		(req)
	{
	   var respuesta = req.xhRequest.responseText;
	   $("#divBenefCred").html(respuesta);
 	   return;
	}

	LoadPlanCreditosBenef() ;
	
	$('#txtnprods').numeric().pasteNumeric();
	$('#txtmonto').numeric().pasteNumeric();
	$('#txtcuotas').numeric().pasteNumeric();
	
  </script>
   
 
  <?php if($action=="") { ?>
</form>
	<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd -->
</html>
<?php } ?>