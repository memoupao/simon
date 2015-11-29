<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>

<?php
require_once (constant("PATH_CLASS") . "Functions.class.php");
require_once (constant("PATH_CLASS") . "BLMarcoLogico.class.php");
require_once (constant("PATH_CLASS") . "BLPresupuesto.class.php");
require_once (constant("PATH_CLASS") . "BLTablasAux.class.php");

error_reporting("E_PARSE");
$Function = new Functions();
$proc = $Function->__GET('proc');
$view = $Function->__GET('action');

$idProy = $Function->__GET('idProy');
$idVersion = $Function->__GET('idVersion');
$idComp = $Function->__GET('idComp');
$idActiv = $Function->__GET('idActiv');
$idSActiv = $Function->__GET('idSActiv');
$idCosto = $Function->__GET('idcosto');

$retcodigo = 0;

if ($proc == md5("save")) {
    // --> Hacemos el Insert o Update
    $ReturnPage = false;
    $objPresup = new BLPresupuesto();
    if ($view == md5("edit")) {
        $ReturnPage = $objPresup->ActualizarFuentesFinan();
    }
    $proc = md5("reload");
    $objPresup = NULL;
}

if ($proc == md5("reload")) {
    $idProy = $Function->__POST('t02_cod_proy');
    $idVersion = $Function->__POST('t02_version');
    $idComp = $Function->__POST('t08_cod_comp');
    $idActiv = $Function->__POST('t09_cod_act');
    $idSActiv = $Function->__POST('t09_cod_sub');
    $idCosto = $Function->__POST('t10_cod_cost');
    $view == md5("edit");
}

?>
<?php

$row = 0;
$objPresup = new BLPresupuesto();
$row = $objPresup->GetSubActividad($idProy, $idVersion, $idComp, $idActiv, $idSActiv);
if ($view == md5("edit")) {
    // print_r($_POST);
    // exit();
    $rowCosteo = $objPresup->GetCosteo($idProy, $idVersion, $idComp, $idActiv, $idSActiv, $idCosto);
    $categoria = $rowCosteo['t10_cate_cost'];
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/templateIframe.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<?php $objFunc->SetSubTitle("Costeo - Fuentes de Financiamiento");?>
<title>Costeo - Fuentes de Financiamiento</title>
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->
<script language="javascript" type="text/javascript"
	src="../../../jquery.ui-1.5.2/jquery-1.2.6.js"></script>
<?php
if ($ReturnPage) {
    $script = "alert('Se grabó correctamente el Registro'); \n";
    $script .= "parent.LoadSubActividades(''); \n";
    $Function->Javascript($script);
}
?>
<!-- InstanceEndEditable -->
<link href="../../../css/itemplate.css" rel="stylesheet" type="text/css"
	media="all" />
</head>

<body>
	<!-- InstanceBeginEditable name="tBody" -->
	<form id="frmMain" name="frmMain" method="post" action="#">
		<div id="toolbar" style="height: 4px;" class="Subtitle">
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td width="47%" align="right"><?php echo($objFunc->SubTitle) ;?></td>
					<td width="31%">&nbsp;</td>
					<td width="2%">&nbsp;</td>
					<td width="2%">&nbsp;</td>
					<td width="2%">&nbsp;</td>
					<td width="9%"><button class="Button"
							onclick="return Guardar(); return false;" value="Guardar">Guardar
						</button></td>
					<td width="9%"><button class="Button" value="Cancelar"
							onclick="return Cancelar();">Cancelar</button></td>
				</tr>
			</table>
		</div>

		<table width="516" align="center" class="TableEditReg">
			<tr valign="baseline">
				<td height="17" colspan="4" align="left" nowrap><strong>Actividad</strong></td>
			</tr>
			<tr valign="baseline">
				<td height="25" colspan="4" align="left" valign="middle" nowrap><input
					name="t09_cod_sub2" type="text" id="t09_cod_sub2"
					value="<?php  echo($row["t08_cod_comp"].".".$row["t09_cod_act"].".".$row["t09_cod_sub"]);?>"
					size="4" maxlength="5" disabled style="text-align: center;" /> <input
					name="t09_sub" type="text" disabled="disabled" id="t09_sub"
					value="<?php echo($row["t09_sub"]);?>" size="95" maxlength="100" /></td>
			</tr>
			<tr valign="baseline">
				<td width="82" height="17" align="left" valign="middle" nowrap><strong>Categoria
						Gasto</strong></td>
				<td align="left" valign="middle"><strong>Descripción del Gasto</strong></td>
				<td align="center" valign="middle"><strong>Meta Total</strong></td>
				<td width="102" align="center" valign="middle"><strong>Costo Total</strong></td>
			</tr>
			<tr valign="baseline">
				<td align="left" valign="middle" nowrap><select name="cbocatgasto"
					class="TextDescripcion" id="cbocatgasto" style="width: 170px;"
					disabled="disabled">
						<option value=""></option>
          <?php
        $objTablas = new BLTablasAux();
        $rs = $objTablas->TipoCategoriaGastos();
        $Function->llenarCombo($rs, 'codigo', 'descripcion', $categoria);
        ?>
        </select></td>
				<td width="202" align="left"><input name="t10_cost" type="text"
					disabled="disabled" id="t10_cost"
					value="<?php echo($rowCosteo["t10_cost"]);?>" size="40"
					maxlength="100" /></td>
				<td width="57" align="center"><input name="t09_mta" type="text"
					disabled="disabled" id="t09_mta" style="text-align: center"
					value="<?php echo($row["t09_mta"]);?>" size="6" readonly="readonly" /></td>
				<td align="center"><input name="t10_cost_tot" type="text"
					disabled="disabled" id="t10_cost_tot" style="text-align: right"
					value="<?php echo($rowCosteo["t10_cost_tot"]);?>" size="11"
					readonly="readonly" /></td>
			</tr>

			<tr valign="baseline">
				<td colspan="4" align="left" valign="middle" nowrap>
					<div id="divTableFinan" class="TableGrid">
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
									<td><input name="txtInstit[]" id="txtInstit[]" type="hidden"
										value="<?php echo($rowFTE['t01_id_inst']);?>" /><?php echo($rowFTE['t01_sig_inst']);?></td>
									<td align="right"><input name="txtmonto[]" type="text"
										id="txtmonto[]" style="text-align: right"
										value="<?php echo($rowFTE["mtofinan"]);?>" size="17"
										maxlength="10" onkeyup="CalcularTotales();" class="summonto" /></td>
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
					</div>
				</td>
			</tr>
		</table>
		<input type="hidden" name="t02_cod_proy"
			value="<?php echo($idProy);?>" /> <input type="hidden"
			name="t02_version" value="<?php echo($idVersion);?>" /> <input
			type="hidden" name="t08_cod_comp" value="<?php echo($idComp);?>" /> <input
			type="hidden" name="t09_cod_act" value="<?php echo($idActiv);?>" /> <input
			type="hidden" name="t09_cod_sub" id="t09_cod_sub"
			value="<?php echo($idSActiv);?>" size="2" maxlength="5" readonly /> <input
			type="hidden" name="t10_cod_cost" id="t10_cod_cost"
			value="<?php echo($idCosto);?>" size="2" maxlength="5"
			readonly="readonly" />
	</form>

	<script language="javascript" type="text/javascript">
	  function Cancelar()
	  {
		 parent.btnCancel_Clic();
		 return false;
	  }

	  function Guardar()
	  {
	     <?php $ObjSession->AuthorizedPage(); ?>

		 if($('#t09_sub').val()=="")
		 { alert("No se ha Cargado correctamente la Actividad"); return false ;}

	  	 var formulario = document.getElementById("frmMain") ;
		 formulario.action = "cost_fte_finan.php?&proc=<?php echo(md5("save"));?>&action=<?php echo($view);?>";
		 return true ;
	  }

   </script>

	<script language="javascript" type="text/javascript">
	  function CalcularTotales()
	  {
		  var costotot = $('#t10_cost_tot').val();
		   var lsindex = 1 ;
		   var sum = 0;
		   var porc =0;
	        $(".summonto").each(function() {
	            if(!isNaN(this.value) && this.value.length!=0)
				{ 	sum += CNumber(this.value);
					porc = (this.value * 100) / CNumber(costotot);
				}
				else
				{ porc=0;}
				$('#porcFTE_'+lsindex).html(porc.toFixed(2));
				lsindex++;
				} );
			$('#bSumaTotal').html(sum.toFixed(2));
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

	<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd -->
</html>
