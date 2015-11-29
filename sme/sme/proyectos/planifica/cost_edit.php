<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>
<?php

require_once (constant("PATH_CLASS") . "Functions.class.php");
require_once (constant("PATH_CLASS") . "BLMarcoLogico.class.php");
require_once (constant("PATH_CLASS") . "BLPresupuesto.class.php");
require_once (constant("PATH_CLASS") . "BLTablasAux.class.php");

$view = $objFunc->__GET('mode');
$idProy = $objFunc->__Request('idProy');
$idVersion = $objFunc->__Request('idVersion');
$idComp = $objFunc->__Request('idComp');
$idActiv = $objFunc->__Request('idActiv');
$idSActiv = $objFunc->__Request('idSActiv');
$categoria = $objFunc->__Request('categ');
$idCosto = $objFunc->__Request('idcosto');

$row = 0;
$objPresup = new BLPresupuesto();
$row = $objPresup->GetSubActividad($idProy, $idVersion, $idComp, $idActiv, $idSActiv);

if ($view == md5("edit")) {
    $objFunc->SetSubTitle("Costeo de Actividades - Editar Registro");

    $rowCosteo = $objPresup->GetCosteo($idProy, $idVersion, $idComp, $idActiv, $idSActiv, $idCosto);
    $categoria = $rowCosteo['t10_cate_cost'];
} else {
    $objFunc->SetSubTitle("Costeo de Actividades - Nuevo Registro");
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
											onclick="Guardar_CostoOperativo(); return false;"
											value="Guardar">Guardar</button></td>
									<td width="9%"><button class="Button"
											onclick="spryPopupDialog01.displayPopupDialog(false); return false;"
											value="Cancelar">Cancelar</button></td>
									<td width="8%">&nbsp;</td>
									<td width="47%" align="right"><?php echo($objFunc->SubTitle) ;?></td>
								</tr>
							</table>
						</div>
						<table width="516" align="center" class="TableEditReg">
							<tr valign="baseline">
								<td height="13" align="left" nowrap="nowrap">&nbsp;</td>
								<td colspan="2" align="left">&nbsp;</td>
								<td width="52" align="right" valign="middle" nowrap="nowrap">&nbsp;</td>
								<td colspan="2" align="left" valign="middle">&nbsp;</td>
							</tr>
							<tr valign="baseline">
								<td width="91" height="32" align="left" valign="middle"
									nowrap="nowrap"><strong>Actividad</strong></td>
								<td colspan="5" align="left" valign="middle" nowrap="nowrap"><input
									name="t09_cod_sub2" type="text" class="costosoperativos"
									id="t09_cod_sub2" style="text-align: center;"
									value="<?php  echo($row["t08_cod_comp"].".".$row["t09_cod_act"].".".$row["t09_cod_sub"]);?>"
									size="2" maxlength="5" readonly="readonly" /> <input
									name="t09_sub" type="text" class="costosoperativos"
									id="t09_sub" value="<?php echo($row["t09_sub"]);?>" size="75"
									maxlength="100" readonly="readonly" /></td>
							</tr>
							<tr valign="baseline">
								<td height="30" align="left" valign="middle" nowrap="nowrap"><strong>Categoria
										Gasto</strong></td>
								<td colspan="3" align="left" valign="middle"><select
									name="cbocatgasto" class="costosoperativos" id="cbocatgasto"
									style="width: 250px;">
										<option value=""></option>
        <?php
        $objTablas = new BLTablasAux();
        $rs = $objTablas->TipoCategoriaGastos();
        $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $categoria);
        ?>
      </select></td>
								<td colspan="2" align="left" valign="middle"
									style="font-size: 12px; font-weight: bold;"><?php echo($rowCosteo["t10_cod_cost"]);?></td>
							</tr>
							<tr valign="baseline">
								<td height="29" align="left" valign="middle" nowrap="nowrap"><strong>Gasto</strong></td>
								<td colspan="5" align="left"><input name="t10_cost" type="text"
									class="costosoperativos" id="t10_cost"
									value="<?php echo($rowCosteo["t10_cost"]);?>" size="70"
									maxlength="200" /></td>
							</tr>
							<tr valign="baseline">
								<td height="28" align="left" valign="middle" nowrap="nowrap"><strong>Unidad
										Medida</strong></td>
								<td colspan="5" align="left"><input name="t10_um" type="text"
									class="costosoperativos" id="t10_um"
									value="<?php echo($rowCosteo["t10_um"]);?>" size="30" /></td>
							</tr>
							<tr>
								<td height="32" align="left" valign="middle" nowrap="nowrap"><strong>Costo
										Unitario</strong></td>
								<td width="79" align="left" valign="middle"><input name="t10_cu"
									type="text" class="costosoperativos" id="t10_cu"
									style="text-align: center" onkeyup="TotalizarPresup();"
									value="<?php echo(number_format($rowCosteo["t10_cu"],2,'.',''));?>"
									size="12" /></td>
								<td width="86" align="center" valign="middle"><strong>Cantidad</strong></td>
								<td valign="middle"><input name="t10_cant" type="text"
									class="costosoperativos" id="t10_cant"
									style="text-align: center;" onkeyup="TotalizarPresup();"
									value="<?php echo(number_format($rowCosteo["t10_cant"],2,'.',''));?>"
									size="10" /></td>
								<td width="99" valign="middle" nowrap="nowrap"><span
									class="TextDescripcion"><strong>Costo Parcial</strong></span></td>
								<td width="128" valign="middle"><input name="t10_cost_parc"
									type="text" class="costosoperativos" id="t10_cost_parc"
									style="text-align: center;"
									value="<?php echo(number_format($rowCosteo["t10_cost_parc"],2,'.',''));?>"
									size="15" /></td>
							</tr>
							<tr>
								<td height="35" align="left" valign="middle"
									class="TextDescripcion"><strong>Meta Total</strong></td>
								<td align="left" valign="middle"><input name="t09_meta"
									type="text" class="costosoperativos" id="t09_meta"
									style="text-align: center"
									value="<?php echo($row["t09_mta"]);?>" size="12" /></td>
								<td align="center" valign="middle" nowrap="nowrap"><span
									class="TextDescripcion"><strong>Costo Total</strong></span></td>
								<td colspan="3" valign="middle"><input name="t10_cost_tot"
									type="text" class="costosoperativos" id="t10_cost_tot"
									style="text-align: center;"
									value="<?php echo(number_format($rowCosteo["t10_cost_tot"],2,'.',''));?>"
									size="18" /></td>
							</tr>
							<tr>
								<td colspan="6" align="center" class="TextDescripcion"><input
									name="t02_cod_proy" type="hidden" class="costosoperativos"
									id="t02_cod_proy" value="<?php echo($idProy);?>" /> <input
									name="t02_version" type="hidden" class="costosoperativos"
									id="t02_version" value="<?php echo($idVersion);?>" /> <input
									name="t08_cod_comp" type="hidden" class="costosoperativos"
									id="t08_cod_comp" value="<?php echo($idComp);?>" /> <input
									name="t09_cod_act" type="hidden" class="costosoperativos"
									value="<?php echo($idActiv);?>" /> <input name="t09_cod_sub"
									type="hidden" class="costosoperativos" id="t09_cod_sub"
									value="<?php echo($idSActiv);?>" /> <input name="t10_cod_cost"
									type="hidden" class="costosoperativos" id="t10_cod_cost"
									value="<?php echo($rowCosteo["t10_cod_cost"]);?>" /></td>
							</tr>
						</table>
						<br /> <br /> <br /> <br /> <br />
					</div>

					<script language="javascript">
  function Guardar_CostoOperativo()
	{
	<?php $ObjSession->AuthorizedPage(); ?>

	 if( $('#cbocatgasto').val()=="" ) {alert("Seleccione Categoria de Gasto"); $('#cbocatgasto').focus(); return false;}
	 if( $('#t10_descrip').val()=="" ) {alert("Ingrese Descripcion del Gasto"); $('#t10_descrip').focus(); return false;}
	 if( $('#t10_um').val()=="" ) {alert("Ingrese Unidad de Medida"); $('#t10_um').focus(); return false;}
	 if( $('#t10_cant').val()=="" ) {alert("Ingrese Cantidad"); $('#t10_cant').focus(); return false;}
 	 if( $('#t10_cu').val()=="" ) {alert("Ingrese Costo Unitario"); $('#t10_cu').focus(); return false;}

	 var BodyForm = $("#FormData .costosoperativos").serialize() ;
	 var sURL = "poa_fin_process.php?mode=<?php echo($view);?>&action=<?php echo(md5("guardar_costo_operativo"))?>" ;
	 var req = Spry.Utils.loadURL("POST", sURL, true, MySuccessGuardarCostoOperativo, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });

	 return false;
	}

	function MySuccessGuardarCostoOperativo(req)
	{
		var respuesta = req.xhRequest.responseText;
		respuesta = respuesta.replace(/^\s*|\s*$/g,"");
		var ret = respuesta.substring(0,5);
		if(ret=="Exito")
		{
		 LoadCostosOperativos();
		 alert(respuesta.replace(ret,""));
		 spryPopupDialog01.displayPopupDialog(false);
		}
		else
		{  alert(respuesta); }
	}

	function TotalizarPresup()
	{
		var cantidad = CNumber($("#t10_cant").val()) ;
		var precio   = CNumber($("#t10_cu").val()) ;
		var meta     = CNumber($("#t09_meta").val());
		var parcial  = (cantidad * precio);
		var total    = (parcial * meta);
		$('#t10_cost_parc').val(parcial.toFixed(2));
		$('#t10_cost_tot').val(total.toFixed(2));
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
  $("#t10_cant").numeric();
  $("#t10_cu").numeric();
  TotalizarPresup();
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

