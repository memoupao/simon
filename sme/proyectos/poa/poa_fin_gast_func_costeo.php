<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>
<?php

require (constant('PATH_CLASS') . "BLManejoProy.class.php");
require (constant('PATH_CLASS') . "BLTablasAux.class.php");

$objMan = new BLManejoProy();
$HC = new HardCode();

$view = $objFunc->__GET('mode');
$idProy = $objFunc->__Request('idProy');
$idVersion = $objFunc->__Request('idVersion');
$Partida = 0;
$idGasto = 0;
$row = 0;
if ($view == md5("ajax_edit_gasto")) {
    $objFunc->SetSubTitle("Costeo - Gastos de Funcionamiento - Editando Registro");
    $Partida = $objFunc->__Request('idPartida');
    $idGasto = $objFunc->__Request('idGasto');
    $row = $objMan->GastFunc_SeleccionarCosteo($idProy, $idVersion, $Partida, $idGasto);
} else {
    $row = 0;
    $objFunc->SetSubTitle("Costeo - Gastos de Funcionamiento - Nuevo Registro");
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
											onclick="Guardar_CostoFunc(); return false;" value="Guardar">Guardar
										</button></td>
									<td width="9%"><button class="Button"
											onclick="spryPopupDialog01.displayPopupDialog(false); return false;"
											value="Cancelar">Cancelar</button></td>
									<td width="8%">&nbsp;</td>
									<td width="47%" align="right"><?php echo($objFunc->SubTitle) ;?></td>
								</tr>
							</table>
						</div>
						<table width="580" align="center" class="TableEditReg">
							<tr valign="bottom">
								<td width="118" height="17" valign="middle" nowrap>&nbsp;</td>
								<td colspan="4" align="left" valign="middle">&nbsp;</td>
							</tr>
							<tr valign="baseline">
								<td height="23" align="left" valign="middle" nowrap="nowrap"><strong>Partida</strong></td>
								<td colspan="2" align="left" valign="middle"><select
									name="cbopartida" id="cbopartida" style="width: 270px;"
									<?php if($view == md5("ajax_edit_gasto")){echo("disabled");}?>
									onchange="MostrarMeta();">
        <?php
        $Rs = $objMan->GastFunc_Listado_Partidas($idProy, $idVersion);
        $objFunc->llenarComboI($Rs, "t03_partida", "partida", $row['t03_partida'], 'meta');
        ?>
        </select></td>
								<td width="39" align="left" valign="middle"
									style="font-size: 12px; font-weight: bold;">Meta</td>
								<td width="118" align="left" valign="middle"
									style="font-size: 12px; font-weight: bold;"><input
									name="t03_meta" type="text" disabled="disabled" id="t03_meta"
									style="text-align: center;"
									value="<?php echo($row["t03_meta"]);?>" size="10"
									readonly="readonly" /></td>
							</tr>
							<tr valign="baseline">
								<td height="30" align="left" valign="middle" nowrap="nowrap"><strong>Categoria
										Gasto</strong></td>
								<td colspan="2" align="left" valign="middle"><select
									name="cbocatgasto" class="TextDescripcion" id="cbocatgasto"
									style="width: 196px;">
										<option value=""></option>
          <?php
        $objTablas = new BLTablasAux();
        $rs = $objTablas->TipoCategoriaGastos();
        $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $row["t03_cat_gast"]);
        $objTablas = NULL;
        ?>
          </select></td>
								<td colspan="2" align="left" valign="middle"
									style="font-size: 12px; font-weight: bold;"><input
									name="t03_id_gasto" type="hidden" id="t03_id_gasto"
									value="<?php echo($row["t03_id_gasto"]);?>" size="1"
									maxlength="5" style="text-align: center" /> <input
									name="t03_partida" type="hidden" id="t03_partida"
									value="<?php echo($row["t03_partida"]);?>" size="1"
									maxlength="5" style="text-align: center" /></td>
							</tr>
							<tr valign="baseline">
								<td nowrap="nowrap" align="left" valign="middle"><strong>Descripción
										Gasto</strong></td>
								<td colspan="4" align="left"><input name="t03_descrip"
									type="text" id="t03_descrip"
									value="<?php echo($row["t03_descrip"]);?>" size="60"
									maxlength="150" /></td>
							</tr>
							<tr valign="baseline">
								<td nowrap="nowrap" align="left" valign="middle"><strong>Unidad
										Medida</strong></td>
								<td width="102" align="left"><input name="t03_um" type="text"
									id="t03_um" value="<?php echo($row["t03_um"]);?>" size="20" /></td>
								<td width="179" align="left">&nbsp;</td>
								<td colspan="2" align="left">&nbsp;</td>
							</tr>
							<tr>
								<td align="left" valign="top"><strong>Cantidad</strong></td>
								<td align="left" valign="top"><input name="t03_cant" type="text"
									id="t03_cant" style="text-align: right;"
									value="<?php echo($row["t03_cant"]);?>" size="20"
									onkeyup="TotalizarPresup();" /></td>
								<td align="left" valign="top">&nbsp;</td>
								<td colspan="2" align="left" valign="top">&nbsp;</td>
							</tr>
							<tr>
								<td align="left" valign="middle" nowrap="nowrap"><strong>Costo
										Unitario</strong></td>
								<td align="left" valign="baseline"><input name="t03_cu"
									type="text" id="t03_cu" style="text-align: right"
									value="<?php echo($row["t03_cu"]);?>" size="20"
									onkeyup="TotalizarPresup();" /></td>
								<td valign="top">&nbsp;</td>
								<td colspan="2" valign="top">&nbsp;</td>
							</tr>
							<tr>
								<td align="left" class="TextDescripcion"><strong>Costo Parcial</strong></td>
								<td align="left"><input name="t03_cost_parc" type="text"
									id="t03_cost_parc" style="text-align: right"
									value="<?php echo($row["t03_cost_parc"]);?>" size="20"
									readonly="readonly" /></td>
								<td valign="top">&nbsp;</td>
								<td colspan="2" valign="top">&nbsp;</td>
							</tr>
							<tr>
								<td align="left" class="TextDescripcion"><strong>Costo Total</strong></td>
								<td align="left"><input name="t03_cost_tot" type="text"
									id="t03_cost_tot" style="text-align: right"
									value="<?php echo($row["t03_cost_tot"]);?>" size="20"
									readonly="readonly" /></td>
								<td valign="top">&nbsp;</td>
								<td colspan="2" valign="top">&nbsp;</td>
							</tr>
							<tr valign="baseline">
								<td align="left" nowrap>&nbsp;</td>
								<td align="left" nowrap>&nbsp;</td>
								<td align="left" nowrap>&nbsp;</td>
								<td colspan="2" align="left" nowrap>&nbsp;</td>
							</tr>
						</table>
						<br /> <br /> <br /> <br /> <br />
					</div>

					<script language="javascript">
  function Guardar_CostoFunc()
	{
	<?php $ObjSession->AuthorizedPage(); ?>	
	 
	 if( $('#cbopartida').val()=="" ) {alert("Seleccione la Partida a Registrar"); $('#cbopartida').focus(); return false;}	
	 if( $('#cbocatgasto').val()=="" ) {alert("Seleccione Categoria de Gasto"); $('#cbocatgasto').focus(); return false;}	
	 if( $('#t03_descrip').val()=="" ) {alert("Ingrese Descripcion del Gasto"); $('#t03_descrip').focus(); return false;}	
	 if( $('#t03_um').val()=="" ) {alert("Ingrese Unidad de Medida"); $('#t03_um').focus(); return false;}	
	 if( $('#t03_cant').val()=="" ) {alert("Ingrese Cantidad"); $('#t03_cant').focus(); return false;}	
 	 if( $('#t03_cu').val()=="" ) {alert("Ingrese Costo Unitario"); $('#t03_cu').focus(); return false;}	
	 
	 var BodyForm = $("#FormData").serialize() ;
	 var sURL = "mp_gast_func_process.php?action=<?php echo($view);?>" ;
	 var req = Spry.Utils.loadURL("POST", sURL, true, MySuccessGuardarCostoFunc, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });

	 return false;
	}
	
	function MySuccessGuardarCostoFunc(req)
	{
		var respuesta = req.xhRequest.responseText;
		respuesta = respuesta.replace(/^\s*|\s*$/g,"");
		var ret = respuesta.substring(0,5);
		if(ret=="Exito")
		{
		 LoadGastoFuncion(true);	
		 alert(respuesta.replace(ret,""));
		 spryPopupDialog01.displayPopupDialog(false);
		}
		else
		{  alert(respuesta); }
	}
	
	function TotalizarPresup()
	{
		var cantidad = CNumber($("#t03_cant").val()) ;
		var precio   = CNumber($("#t03_cu").val()) ;
		var meta     = CNumber($("#t03_meta").val());
		var parcial  = (cantidad * precio);
		var total    = (parcial * meta);
		$('#t03_cost_parc').val(parcial.toFixed(2));
		$('#t03_cost_tot').val(total.toFixed(2));
	}
	
	function MostrarMeta()
	{
		var meta = $("#cbopartida option[value='"+$("#cbopartida").val()+"']").attr("title");
		$("#t03_meta").val(meta);
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
  $("#t03_cant").numeric();
  $("#t03_cu").numeric();
  MostrarMeta();
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

