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

$rowcab = $objPOA->PlanOtros_Seleccionar($idProy, $idVersion, $idComp, $idAct, $idSub);
$idSub2 = $rowcab['subplan'];

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
<script language="javascript" type="text/javascript"
	src="../../../jquery.ui-1.5.2/jquery-1.2.6.js"></script>
<SCRIPT src="../../../jquery.ui-1.5.2/jquery.numeric.js"
	type="text/javascript"></SCRIPT>
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
						<td width="47%" align="right">Plan para Otros Servicios</td>
					</tr>
				</table>
			</div>
			<div>
				<table width="788" border="0" cellpadding="0" cellspacing="1"
					class="TableEditReg">
					<tr>
						<td width="13%" height="35" nowrap="nowrap"><strong>Actividad</strong></td>
						<td width="87%">
							<div style="border: 1px solid #7F9FAA; width: 90%;">
        <?php echo( "<b>".$rowcab["codigo"]."</b>.- ".$rowcab["subact"]); ?>
        </div>
						</td>
					</tr>
					<tr>
						<td height="35"><strong>Tipo de Servicio</strong></td>
						<td><select name="cbotipo" id="cbotipo" style="width: 200px;">
								<option value="" selected="selected"></option>
            <?php
            $objTab = new BLTablasAux();
            $rs = $objTab->TipoOtrosServiciosPlanesEspecificos();
            $objFunc->llenarCombo($rs, "codigo", "descripcion", $rowcab["t12_tipo"]);
            $objTab = NULL;
            ?>
            </select></td>
					</tr>
					<tr>
						<td><strong>Nombre <br /> Bien ó Servicio
						</strong></td>
						<td><input name="txtnomprod" type="text" id="txtnomprod"
							value="<?php echo($rowcab['t12_producto']);?>" size="60"
							maxlength="30" /></td>
					</tr>
					<tr>
						<td height="76" colspan="2"><table width="540" border="0"
								cellspacing="0" cellpadding="0">
								<tr>
									<td width="36%" align="center"><strong>N&ordm; de Bienes ó
											Servicios a entregar por Beneficiario</strong></td>
									<td width="26%" align="center"><strong>N&ordm; Beneficiarios</strong></td>
									<td width="38%" align="center"><strong>Total de Bienes o
											Servicios a Entregar</strong></td>
								</tr>
								<tr>
									<td align="center"><input name="txtnumero" type="text"
										id="txtnumero" value="<?php echo($rowcab['t12_nro_ent']);?>"
										size="20" maxlength="8" style="text-align: center"
										onkeyup="CalcularTotal()" /></td>
									<td align="center"><input name="txtbenef" type="text"
										id="txtbenef" value="<?php echo($rowcab['t12_nro_ben']);?>"
										size="20" maxlength="5" style="text-align: center"
										onkeyup="CalcularTotal()" /></td>
									<td align="center"><input name="txttotal" type="text"
										id="txttotal" value="<?php echo($rowcab['t12_tot_ent']);?>"
										size="20" maxlength="10" style="text-align: center" /></td>
								</tr>
							</table></td>
					</tr>
					<tr>
						<td><strong>Contenidos </strong></td>
						<td><textarea name="txtcontenidos" cols="95" rows="3"
								id="txtcontenidos"><?php echo($rowcab['t12_conten']);?></textarea></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td><input type="hidden" name="t02_cod_proy"
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
    function CalcularTotal()
    {
		var numero = CNumber($("#txtnumero").val());
		var nbenef = CNumber($("#txtbenef").val());
		var tot = numero * nbenef ;
		$("#txttotal").val(tot);
	}
	
	function CNumber(str)
	  {
		  var numero =0;
		  if (str !="" && str !=null)
		  { numero = parseFloat(str);}
		  if(isNaN(numero)) { numero=0;}
		 return numero;
	  }
    function Guardar_Plan()
	{
		 <?php $ObjSession->AuthorizedPage(); ?>	
		 var modulo = $('#cbotipo').val();
		 var conten = $('#txtcontenidos').val();
		 var nvisitas= $("#txtnumero").val();
		 var nbenef	 = $("#txtbenef").val();
	 
		 if(modulo=='' || modulo==null){alert("Seleccione Tipo de Bien o Servicio al que corresponde la Actividad"); $('#cbotipo').focus(); return false;}
		 if(nvisitas=='' || nvisitas==null){alert("Ingrese Numero de Bienes o Servicios"); $('#txtnumero').focus(); return false;}
		 if(nbenef=='' || nbenef==null){alert("Ingrese Numero de beneficiarios."); $('#txtbenef').focus(); return false;}
		 

		var arrParams = new Array();
			arrParams[0] = "idProy=<?php echo($idProy); ?>" ; 
			arrParams[1] = "idVersion=<?php echo($idVersion); ?>" ;
			arrParams[2] = "idComp=<?php echo($idComp); ?>" ;
			arrParams[3] = "idAct=<?php echo($idAct); ?>" ;
			arrParams[4] = "idSub=<?php echo($idSub); ?>" ;
			arrParams[5] = "cbotipo=" + modulo ;
			arrParams[6] = "txtcontenidos=" + conten ;
			arrParams[7] = "txtnumero=" + $("#txtnumero").val(); ;
			arrParams[8] = "txttotal=" + $("#txttotal").val();
			arrParams[9] = "txtbenef=" + $("#txtbenef").val();
			arrParams[10] = "txtnomprod=" + $("#txtnomprod").val();
			
		  var BodyForm = arrParams.join("&");
		  var sURL = "plan_otros_process.php?action=<?php echo(md5("ajax_save_plan_cab"));?>";
		 
		  var req = Spry.Utils.loadURL("POST", sURL, true, GuardarPlanCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
	}
   function GuardarPlanCallback	(req)
	{
	  var respuesta = req.xhRequest.responseText;
	  respuesta = respuesta.replace(/^\s*|\s*$/g,"");
	  var ret = respuesta.substring(0,5);
	  if(ret=="Exito")
	  {
		EditarPlanOtros('<?php echo($idComp); ?>','<?php echo($idAct); ?>','<?php echo($idSub); ?>');
		alert(respuesta.replace(ret,""));
	  }
	  else
	  {alert(respuesta);}  
	}

	function onErrorLoad			(req)
	{
		alert("Ocurrio un error al cargar los datos \n" +  req.xhRequest.responseText);
	}
	
	$("#txtnumero").numeric().pasteNumeric();
	$("#txtotal").numeric().pasteNumeric();
	$("#txtbenef").numeric().pasteNumeric();
	
  </script>
   
 
  <?php if($idProy=="") { ?>
</form>
	<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd -->
</html>
<?php } ?>