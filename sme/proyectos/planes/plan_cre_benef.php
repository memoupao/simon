<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>

<?php
require (constant("PATH_CLASS") . "BLPOA.class.php");

$objPOA = new BLPOA();

$action = $objFunc->__Request('action');

error_reporting("E_PARSE");
$idProy = $objFunc->__Request('idProy');
$idVersion = $objFunc->__Request('idVersion');
$idComp = $objFunc->__Request('idComp');
$idAct = $objFunc->__Request('idAct');
$idSub = $objFunc->__Request('idSub');

if ($idProy == "") {
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
<script src="../../../jquery.ui-1.5.2/jquery-1.2.6.js"
	type="text/javascript"></script>
<SCRIPT src="../../../jquery.ui-1.5.2/jquery.numeric.js"
	type="text/javascript"></SCRIPT>
<!-- InstanceEndEditable -->
<link href="../../../css/itemplate.css" rel="stylesheet" type="text/css"
	media="all" />
</head>

<body>
	<!-- InstanceBeginEditable name="tBody" -->
	<form action="#" method="post" enctype="multipart/form-data"
		name="frmMain" id="frmMain">
<?php
}
?>
<div id="divTableLista" class="TableGrid">
			<table width="700" border="0" cellpadding="0" cellspacing="0">
				<tr style="border: solid 1px #CCC; background-color: #eeeeee;">
					<th width="40" align="center" valign="middle"
						style="border: solid 1px #CCC;"><img
						src="<?php echo(constant("PATH_IMG"));?>addicon.gif" alt=""
						width="16" height="16" border="0" style="cursor: pointer;"
						title="Agregar Beneficiarios" onclick="AddBeneficiarios();" /></th>
					<th width="365" height="28" align="center" valign="middle"
						bgcolor="#E9E9E9" style="border: solid 1px #CCC;">Nombre del
						Beneficiario</th>
					<th width="115" align="center" valign="middle"
						style="border: solid 1px #CCC;">Monto</th>
					<th width="62" align="center" valign="middle"
						style="border: solid 1px #CCC;">N&deg; Cuotas</th>
					<th width="76" align="center" valign="middle"
						style="border: solid 1px #CCC;">Tasa Interés</th>
					<th width="40" align="center" valign="middle"
						style="border: solid 1px #CCC;">&nbsp;</th>
					<th width="40" align="center" valign="middle"
						style="border: solid 1px #CCC;">&nbsp;</th>
				</tr>
				<tbody class="data" bgcolor="#FFFFFF">
      <?php
    $sumbenef = 0;
    $summontos = 0;
    $iRs = $objPOA->PlanCreditos_ListadoBenef($idProy, $idVersion, $idComp, $idAct, $idSub);
    if ($iRs->num_rows > 0) {
        while ($row = mysqli_fetch_assoc($iRs)) {
            $sumbenef ++;
            $summontos += $row['t12_mto_ben'];
            
            ?>
     
      <tr style="padding: 0px;" class="RowData">
						<td align="center" valign="middle" nowrap="nowrap">&nbsp;</td>
						<td height="14" align="left" valign="middle" nowrap="nowrap"><input
							type="hidden" name="txtbenef[]" id="txtbenef[]"
							value="<?php echo($row['t11_cod_benef']); ?>"
							class="PlanCredBenef" /><?php echo( $row['nombres']);?></td>
						<td align="center" valign="middle"><input name="txtmontos[]"
							type="text" class="PlanCredBenef" id="txtmontos[]"
							style="text-align: center;"
							value="<?php echo($row['t12_mto_ben']); ?>" size="20"
							maxlength="10" /></td>
						<td align="center" valign="middle"><input name="txtcuotas[]"
							type="text" class="PlanCredBenef" id="txtcuotas[]"
							style="text-align: center;"
							value="<?php echo($row['t12_nro_cuo']); ?>" size="10"
							maxlength="2" /></td>
						<td align="center" valign="middle"><input name="txttasa[]"
							type="text" class="PlanCredBenef" id="txttasa[]"
							style="width: 99%; text-align: center;"
							value="<?php echo($row['t12_tasa_int']); ?>" size="12"
							maxlength="5" /></td>
						<td align="center" valign="middle" nowrap="nowrap"><span
							style="text-align: center; color: navy; font-weight: bold; font-size: 10px; cursor: pointer; width: 50px;">
								<!-- <img src="../../../img/aplicar.png" alt="" width="16" height="16" title="Guardar Montos asignados  " onclick="GuardarBenef();" /> -->
								<input class="btn_save" width="16" height="16"
								onclick="GuardarBenef();" title="Guardar Montos asignados "
								value="Guardar">
						</span></td>
						<td align="center" valign="middle" nowrap="nowrap"><img
							src="../../../img/bt_elimina.gif" width="13" height="13"
							title="Eliminar Registro" border="0"
							onclick="EliminarBeneficiario('<?php echo($row['t11_cod_benef']);?>', '<?php echo( $row['nombres'] );?>');"
							style="cursor: pointer;" /></td>
					</tr> 
      <?php } ?>
      <?php
    }
    $iRs->free();
    ?>
    </tbody>
				<tfoot>
					<tr style="color: #FFF; font-weight: bold;">
						<td height="24" align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;Numero de Beneficiarios: <?php echo( $sumbenef);?></td>
						<td align="center" valign="middle">&nbsp;<?php echo(number_format($summontos,2));?></td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td height="24" align="center" valign="middle">&nbsp;</td>
					</tr>
				</tfoot>
			</table>
			<script language="javascript" type="text/javascript">
function GuardarBenef()
{
 <?php $ObjSession->AuthorizedPage(); ?>	
  
var lvadida = true;
var valor = 0 ;
var valormotno =0;
var maxmonto = CNumber($('#txtmonto').val());
var maxcuota = CNumber($('#txtcuotas').val());

$(".PlanCredBenef").each(function() {
									if(lvadida) 
									{
										valor = CNumber(this.value) ;
										switch(this.name)
										{
											case 'txtmontos[]' : 
												valormotno = valor ;
												if(valor<=0) {alert("No ha ingresado Monto, para el Beneficiario "); this.focus(); lvadida = false;}
												if(valor > maxmonto ) {alert("El monto de prestamo al beneficiario, no debe superar al monto maximo definido"); this.focus(); lvadida = false;}
												break ;
											case 'txtcuotas[]' : 
												if(valor<=0){alert("No ha ingresado numero de cuota, para el Beneficiario "); this.focus(); lvadida = false;}
												if(valor > maxcuota ) {alert("El numero de cuotas, no debe superar al numero de cuotas maximo"); this.focus(); lvadida = false;}
												break ;
											case 'txttasa[]' : 
												if(valor<=0){alert("No ha ingresado Tasa de Interes, para el Beneficiario "); this.focus(); lvadida = false;}
												if(valor >= valormotno ) {alert("La Tasa de Interes no puede ser mayor o igual que el monto del credito"); this.focus(); lvadida = false;}
												break ;
										}
									}
   });
 if(!lvadida) { return false;} 

 var arrParams = new Array();
	arrParams[0] = "idProy=<?php echo($idProy); ?>" ; 
	arrParams[1] = "idVersion=<?php echo($idVersion); ?>" ;
	arrParams[2] = "idComp=<?php echo($idComp); ?>" ;
	arrParams[3] = "idAct=<?php echo($idAct); ?>" ;
	arrParams[4] = "idSub=<?php echo($idSub); ?>" ;

 var BodyForm = arrParams.join("&") + "&" + $('#FormData .PlanCredBenef').serialize() ;

 var sURL = "plan_cre_process.php?action=<?php echo(md5("ajax_save_plan_cred_benef_monto"));?>"; 
 var req = Spry.Utils.loadURL("POST", sURL, true, GuardarBenefSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });

}

function GuardarBenefSuccessCallback	(req)
{
  var respuesta = req.xhRequest.responseText;
  respuesta = respuesta.replace(/^\s*|\s*$/g,"");
  var ret = respuesta.substring(0,5);
  if(ret=="Exito")
  {
	LoadPlanCreditosBenef();
	alert(respuesta.replace(ret,""));
  }
  else
  {alert(respuesta);}  
}
	
function EliminarBeneficiario(idBenef, name)
{
	<?php $ObjSession->AuthorizedPage(); ?>	
	
	if(confirm(" Estas seguro de eliminar el Registro seleccionado ["+name+"]?"))
	{
		var arrParams = new Array();
			arrParams[0] = "idProy=<?php echo($idProy); ?>" ; 
			arrParams[1] = "idVersion=<?php echo($idVersion); ?>" ;
			arrParams[2] = "idComp=<?php echo($idComp); ?>" ;
			arrParams[3] = "idAct=<?php echo($idAct); ?>" ;
			arrParams[4] = "idSub=<?php echo($idSub); ?>" ;
			arrParams[5] = "idBenef=" + idBenef ;
	
		 var BodyForm = arrParams.join("&");
		
		var sURL = "plan_cre_process.php?action=<?php echo(md5("ajax_del_plan_cre_benef"))?>";
		var req = Spry.Utils.loadURL("POST", sURL, true, GuardarBenefSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad});

	}
}


function AddBeneficiarios()
{
  var popupurl = "<?php echo(constant("PATH_SME")."proyectos/anexos/bene_list_ubigeo.php")?>?idProy=<?php echo($idProy);?>" ;
  loadPopup("Busqueda de Beneficiarios", popupurl);
}


function SeleccionarBeneficiariosOK(selecioandos) 
{
	<?php $ObjSession->AuthorizedPage(); ?>	
	
	var arrParams = new Array();
		arrParams[0] = "idProy=<?php echo($idProy); ?>" ; 
		arrParams[1] = "idVersion=<?php echo($idVersion); ?>" ;
		arrParams[2] = "idComp=<?php echo($idComp); ?>" ;
		arrParams[3] = "idAct=<?php echo($idAct); ?>" ;
		arrParams[4] = "idSub=<?php echo($idSub); ?>" ;
	
		var BodyForm = arrParams.join("&") + "&" + selecioandos;
		
		var sURL = "plan_cre_process.php?action=<?php echo(md5("ajax_save_plan_cred_benef"))?>";
		var req = Spry.Utils.loadURL("POST", sURL, true, SeleccionarBeneficiariosOKSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad});
}

function SeleccionarBeneficiariosOKSuccessCallback	(req)
	{
	  var respuesta = req.xhRequest.responseText;
	  respuesta = respuesta.replace(/^\s*|\s*$/g,"");
	  var ret = respuesta.substring(0,5);
	  if(ret=="Exito")
	  {
		LoadPlanCreditosBenef();
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
	  
<?php if($sumbenef>0) {	?>
	$(".PlanCredBenef").numeric().pasteNumeric();	
<?php } ?>


</script>

		</div>
<?php if($idProy=="") { ?>
</form>
	<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd -->
</html>
<?php } ?>