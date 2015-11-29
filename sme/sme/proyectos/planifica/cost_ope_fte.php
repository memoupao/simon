<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>

<?php
require_once (constant("PATH_CLASS") . "Functions.class.php");
require_once (constant("PATH_CLASS") . "BLMarcoLogico.class.php");
require_once (constant("PATH_CLASS") . "BLPresupuesto.class.php");
require_once (constant("PATH_CLASS") . "BLTablasAux.class.php");

error_reporting("E_PARSE");

$view = $objFunc->__GET('mode');

$idProy = $objFunc->__GET('idProy');
$idVersion = $objFunc->__GET('idVersion');
$idComp = $objFunc->__GET('idComp');
$idActiv = $objFunc->__GET('idActiv');
$idSActiv = $objFunc->__GET('idSActiv');
$idCosto = $objFunc->__GET('idcosto');

error_reporting("E_PARSE");
$retcodigo = 0;

$row = 0;
$objPresup = new BLPresupuesto();
$row = $objPresup->GetSubActividad($idProy, $idVersion, $idComp, $idActiv, $idSActiv);
if ($view == md5("fte")) {
    $rowCosteo = $objPresup->GetCosteo($idProy, $idVersion, $idComp, $idActiv, $idSActiv, $idCosto);
    $categoria = $rowCosteo['t10_cate_cost'];
}
$objFunc->SetSubTitle("Registro de Fuentes de  Financiamiento")?>



<?php if($idProy=='') { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/templateIframe.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Fuentes de Financiamiento</title>
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
 <?php } ?>

  <script src="../../../jquery.ui-1.5.2/jquery.numeric.js"
			type="text/javascript"></script>
		<script src="../../../js/commons.js" type="text/javascript"></script>

		<div id="toolbar" style="height: 4px;" class="Subtitle">
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td width="47%" align="right"><?php echo($objFunc->SubTitle) ;?></td>
					<td width="31%">&nbsp;</td>
					<td width="2%">&nbsp;</td>
					<td width="2%">&nbsp;</td>
					<td width="2%">&nbsp;</td>
					<td width="9%"><button class="Button"
							onclick="GuardarFTE(); return false;" value="Guardar">Guardar</button></td>
					<td width="9%"><button class="Button" value="Cancelar"
							onclick="return CancelarFTE();">Cancelar</button></td>
				</tr>
			</table>
		</div>
		<table width="516" align="center" class="TableEditReg">
			<tr valign="baseline">
				<td height="17" colspan="4" align="left" nowrap="nowrap"><strong>Actividad</strong></td>
			</tr>
			<tr valign="baseline">
				<td height="25" colspan="4" align="left" valign="middle"
					nowrap="nowrap"><input name="t09_cod_sub2" type="text"
					id="t09_cod_sub2" style="text-align: center;"
					value="<?php  echo($row["t08_cod_comp"].".".$row["t09_cod_act"].".".$row["t09_cod_sub"]);?>"
					size="4" maxlength="5" readonly="readonly"
					class="costosoperativos_fte" /> <input name="t09_sub" type="text"
					class="costosoperativos_fte" id="t09_sub"
					value="<?php echo($row["t09_sub"]);?>" size="80" maxlength="100"
					readonly="readonly" /></td>
			</tr>
			<tr valign="baseline">
				<td width="82" height="17" align="left" valign="middle"
					nowrap="nowrap"><strong>Categoria Gasto</strong></td>
				<td align="left" valign="middle"><strong>Descripción del Gasto</strong></td>
				<td align="center" valign="middle"><strong>Meta Total</strong></td>
				<td width="102" align="center" valign="middle"><strong>Costo Total</strong></td>
			</tr>
			<tr valign="baseline">
				<td align="left" valign="middle" nowrap="nowrap"><select
					name="cbocatgasto" class="costosoperativos_fte" id="cbocatgasto"
					style="width: 170px;" disabled="disabled">
						<option value=""></option>
         <?php
        $objTablas = new BLTablasAux();
        $rs = $objTablas->TipoCategoriaGastos();
        $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $categoria);
        ?>
       </select></td>
				<td width="202" align="left"><input name="t10_cost" type="text"
					class="costosoperativos_fte" id="t10_cost"
					value="<?php echo($rowCosteo["t10_cost"]);?>" size="40"
					maxlength="100" readonly="readonly" /></td>
				<td width="57" align="center"><input name="t09_mta" type="text"
					class="costosoperativos_fte" id="t09_mta"
					style="text-align: center" value="<?php echo($row["t09_mta"]);?>"
					size="6" readonly="readonly" /></td>
				<td align="center"><input name="t10_cost_tot" type="text"
					class="costosoperativos_fte" id="t10_cost_tot"
					style="text-align: right"
					value="<?php echo round($rowCosteo["t10_cost_tot"],2,PHP_ROUND_HALF_UP);?>" size="11"
					readonly="readonly" /></td>
			</tr>
			<tr valign="baseline">
				<td colspan="4" align="left" valign="middle" nowrap="nowrap"><div
						id="divTableFinan" class="TableGrid">
						<table width="100%" border="0" cellpadding="0" cellspacing="0">
							<thead>
								<tr>
									<th width="5%" height="18" align="center">#</th>
									<th width="69%" align="center">Fuente de Financiamiento</th>
									<th width="19%" align="center">Monto</th>
									<th width="7%" align="center">%</th>
								</tr>
							</thead>
							<tbody class="data">
             <?php
            // ListaFuentesFinanc
            $rsFte = $objPresup->ListaFuentesFinanc($idProy, $idVersion, $idComp, $idActiv, $idSActiv, $idCosto);
            $index = 1;
            $suma = 0;
            while ($rowFTE = mysqli_fetch_assoc($rsFte)) {
                ?>
             <tr class="RowData">
									<td align="center" valign="middle"><?php echo($index);?></td>
									<td><input name="txtInstit[]" type="hidden"
										class="costosoperativos_fte" id="txtInstit[]"
										value="<?php echo($rowFTE['t01_id_inst']);?>" />
                 <?php echo($rowFTE['t01_sig_inst']);?></td>
									<td align="right"><input name="txtmonto[]" type="text"
										class="costosoperativos_fte" id="txtmonto[]"
										style="text-align: right" onkeyup="CalcularTotales();"
										value="<?php echo($rowFTE["mtofinan"]);?>" size="17"
										maxlength="10" Numerico='1' /></td>
									<td align="center" valign="middle" nowrap="nowrap"><span
										id="porcFTE_<?php echo($index);?>"><?php echo($rowFTE['porcentaje']);?></span></td>
								</tr>
             <?php

$suma = $suma + $rowFTE["mtofinan"];
                $index ++;
            }
            ?>
             <tr>
									<td align="center" valign="middle">&nbsp;</td>
									<td>&nbsp;</td>
									<td align="right"><b id="bSumaTotal"><?php echo(number_format($suma,2));?></b></td>
									<td align="center" valign="middle">&nbsp;</td>
								</tr>
							</tbody>
							<tfoot>
								<tr>
									<td align="center" valign="middle">&nbsp;</td>
									<td>&nbsp;</td>
									<td align="right">&nbsp;</td>
									<td align="center" valign="middle">&nbsp;</td>
								</tr>
							</tfoot>
						</table>
					</div></td>
			</tr>
		</table>
		<input name="t02_cod_proy" type="hidden" class="costosoperativos_fte"
			id="t02_cod_proy" value="<?php echo($idProy);?>" /> <input
			name="t02_version" type="hidden" class="costosoperativos_fte"
			id="t02_version" value="<?php echo($idVersion);?>" /> <input
			name="t08_cod_comp" type="hidden" class="costosoperativos_fte"
			id="t08_cod_comp" value="<?php echo($idComp);?>" /> <input
			name="t09_cod_act" type="hidden" class="costosoperativos_fte"
			id="t09_cod_act" value="<?php echo($idActiv);?>" /> <input
			name="t09_cod_sub" type="hidden" class="costosoperativos_fte"
			id="t09_cod_sub" value="<?php echo($idSActiv);?>" /> <input
			name="t10_cod_cost" type="hidden" class="costosoperativos_fte"
			id="t10_cod_cost" value="<?php echo($idCosto);?>" />

		<script language="javascript" type="text/javascript">
	  function CancelarFTE()
	  {
		 spryPopupDialog01.displayPopupDialog(false);
		 return false;
	  }

	  function GuardarFTE()
	  {
	     <?php $ObjSession->AuthorizedPage(); ?>

		 if($('#t10_cod_cost').val()=="")
		 { alert("No se ha Cargado correctamente el gasto a financiar"); return false ;}

		 var sum = CNumber($('#bSumaTotal').html());
		 var costotot = CNumber($('#t10_cost_tot').val());


		 if(costotot >=0 )
		 {
		   if(sum<=0) {alert("No se ha ingresado ningun monto a Financiar !!!"); return ;} ;
		   if(sum>costotot) {alert("El Monto a Financiar por las Fuentes de Financiamiento, se exceden del Costo Total"); return ;} ;
		 }

		 var BodyForm = $("#FormData .costosoperativos_fte").serialize() ;
		 var sURL = "cost_ope_process.php?action=<?php echo(md5("guardar_fuentes_financ_cost_ope"))?>" ;
		 var req = Spry.Utils.loadURL("POST", sURL, true, MySuccessGuardarFTE, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorCosteo});

		 return false;
	  }

	  function MySuccessGuardarFTE(req)
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

   </script>

		<script language="javascript" type="text/javascript">
	  function CalcularTotales()
	  {
		  var costotot = $('#t10_cost_tot').val();
		   var lsindex = 1 ;
		   var sum = 0;
		   var porc =0;
	        $("input[Numerico='1']").each(function() {
	            if(!isNaN(this.value) && this.value.length!=0)
				{ 	sum += CNumber(this.value);
					porc = (this.value * 100) / CNumber(costotot); }
				else
				{ porc=0;}
				$('#porcFTE_'+lsindex).html(porc.toFixed(2));
				lsindex++;
				} );
			<?php
$entero = floor($rowCosteo["t10_cost_tot"]);
$decimales = str_replace($entero, "", $rowCosteo["t10_cost_tot"]);
$numDecimales = strlen($decimales) - 1;
if ($numDecimales <= 0) {
    $numDecimales = 2;
}
?>

			$('#bSumaTotal').html(sum.toFixed(<?php echo($numDecimales);?>));
			if(sum>costotot) {alert("Se esta excediendo del Monto Total")} ;
	  }

	  function CNumber(str)
	  {
		  var numero =0;
		  if (str !="" && str !=null)
		  { numero = parseFloat(str);}
		  if(isNaN(numero)) { numero=0;}
		 return numero;
	  }

	 <?php if($rowCosteo["t10_cost_tot"]==0) { ?>
		$("input[Numerico='1']").attr("disabled","disabled");
	 <?php } ?>

	 $("input[Numerico='1']").numeric().pasteNumeric();
  	 $('.costosoperativos_fte:input[readonly="readonly"]').css("background-color", "#eeeecc") ;



 </script>

<?php if($view=='') { ?>
</form>
	<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd -->
</html>
<?php } ?>
