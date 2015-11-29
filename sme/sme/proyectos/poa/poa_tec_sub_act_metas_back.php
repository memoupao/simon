<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>

<?php
require_once (constant("PATH_CLASS") . "BLMarcoLogico.class.php");
require_once (constant("PATH_CLASS") . "BLPOA.class.php");

error_reporting("E_PARSE");
$objFunc = new Functions();
$proc = $objFunc->__GET('proc');
$view = $objFunc->__GET('action');

$idProy = $objFunc->__GET('idProy');
$idVersion = $objFunc->__GET('idVersion');
$idComp = $objFunc->__GET('idComp');
$idActiv = $objFunc->__GET('idActiv');
$idSActiv = $objFunc->__GET('idSActiv');
$anio = $objFunc->__GET('anio');

if ($proc == md5("save")) {
    // --> Hacemos el Insert o Update
    $ReturnPage = false;
    if ($view == md5("edit")) {
        $replicarMetas = $objFunc->__POST('chkReplicarGuardar');
        $objPOA = new BLPOA();
        
        if ($replicarMetas == "1") {
            $ReturnPage = $objPOA->ReplicarMetasSubActividad();
        } else {
            $ReturnPage = $objPOA->ActualizarMetasSubActividad();
        }
        
        $objPOA = NULL;
        $proc = md5("reload");
    }
}

if ($proc == md5("reload")) {
    $idProy = $objFunc->__POST('t02_cod_proy');
    $idVersion = $objFunc->__POST('t02_version');
    $idComp = $objFunc->__POST('t08_cod_comp');
    $idActiv = $objFunc->__POST('t09_cod_act');
    $idSActiv = $objFunc->__POST('t09_cod_sub');
    $anio = $objFunc->__POST('cboAnios');
}

?>



<?php
$row = 0;
if ($view == md5("edit")) {
    $objPOA = new BLPOA();
    $row = $objPOA->GetMetasSubActividad($idProy, $idVersion, $idComp, $idActiv, $idSActiv, $anio);
    $rowAcum = $objPOA->GetAcumMetas($idProy, $idVersion, $idComp, $idActiv, $idSActiv, $anio);
    // $objPOA = NULL;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/templateIframe.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<?php

if ($view == "new") {
    $objFunc->SetSubTitle("Actividades  - Metas ");
} else {
    $objFunc->SetSubTitle("Actividades - Metas ");
}

?>
<title>Supuestos Objetivo Especifico</title>
<style type="text/css">
<!--
#Layer1 {
	position: absolute;
	left: 613px;
	top: 0px;
	width: 134px;
	height: 55px;
	z-index: 0;
	visibility: visible;
}
-->
</style>
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->
<script language="javascript" type="text/javascript"
	src="../../../jquery.ui-1.5.2/jquery-1.2.6.js"></script>
<?php
if ($ReturnPage) {
    $script = "alert('Se grabó correctamente el Registro'); \n";
    $script .= "parent.document.getElementById('cboAnios').value = '" . $anio . "';  \n";
    $script .= "parent.LoadSubActividades(''); \n";
    $objFunc->Javascript($script);
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

		<table width="393" align="center" class="TableEditReg">
			<tr valign="baseline">
				<td align="left" nowrap>Codigo</td>
				<td align="left"><input name="t09_cod_sub2" type="text"
					id="t09_cod_sub2"
					value="<?php  echo($row["t08_cod_comp"].".".$row["t09_cod_act"].".".$row["t09_cod_sub"]);?>"
					size="2" maxlength="5" disabled style="text-align: center;"> <input
					name="t09_cod_sub" type="hidden" id="t09_cod_sub"
					value="<?php echo($row["t09_cod_sub"]);?>" size="2" maxlength="5"
					readonly /></td>
				<td align="right" valign="middle">&nbsp;</td>
				<td colspan="3" align="left" valign="middle">&nbsp;</td>
			</tr>
			<tr valign="baseline">
				<td width="70" align="left" nowrap>Actividad</td>
				<td colspan="5" align="left"><input name="t09_sub" type="text"
					id="t09_sub" value="<?php echo($row["t09_sub"]);?>" size="80"
					maxlength="100" readonly="readonly" /></td>
			</tr>
			<tr valign="baseline">
				<td height="30" align="left" valign="middle" nowrap>Año</td>
				<td width="80" align="left" valign="middle"><select name="cboAnios"
					class="TextDescripcion" id="cboAnios" style="width: 80px;"
					onchange="cboAnios_OnChange();" disabled="disabled">
          <?php
        
        $rs = $objPOA->Proyecto->ListaAniosProyecto($idProy, $idVersion);
        $finAnio = $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $anio);
        ?>
          </select> <input type="hidden" id="txtFinAnio"
					name="txtFinAnio" value="<?php echo($finAnio);?>" /></td>
				<td width="56" align="left" valign="middle" nowrap="nowrap">Meta
					Total</td>
				<td width="77" align="left" valign="middle" nowrap="nowrap"><input
					name="t09_mta" type="text" id="t09_mta" style="text-align: center"
					value="<?php echo($row["t09_mta"]);?>" size="15"
					readonly="readonly" /></td>
				<td width="15" align="right" valign="middle" nowrap="nowrap">&nbsp;</td>
				<td width="168" align="left" valign="middle" nowrap="nowrap">&nbsp;</td>
			</tr>
			<tr valign="baseline">
				<td nowrap align="left" valign="middle">Metas</td>
				<td colspan="5" align="left">
					<table width="250" border="0" cellpadding="0" cellspacing="0"
						style="width: 250px;">
						<tr>
							<th colspan="2" align="center">Trim 1</th>
							<th colspan="2" align="center">Trim 2</th>
							<th colspan="2" align="center">Trim 3</th>
							<th colspan="2" align="center">Trim 4</th>
						</tr>
						<tr>
							<td width="35" height="28" nowrap="nowrap">Mes 1</td>
							<td width="28" align="left"><input name="t09_mes1" type="text"
								id="t09_mes1" value="<?php echo($row["t09_mes1"]);?>" size="3"
								maxlength="10" style="text-align: center"
								onkeyup="TotalizarMetas();" tabindex="5" /></td>
							<td width="33" nowrap="nowrap">Mes 4</td>
							<td width="29" align="left"><input name="t09_mes4" type="text"
								id="t09_mes4" value="<?php echo($row["t09_mes4"]);?>" size="3"
								maxlength="10" style="text-align: center"
								onkeyup="TotalizarMetas();" tabindex="8" /></td>
							<td width="33" nowrap="nowrap">Mes 7</td>
							<td width="22" align="left"><input name="t09_mes7" type="text"
								id="t09_mes7" value="<?php echo($row["t09_mes7"]);?>" size="3"
								maxlength="10" style="text-align: center"
								onkeyup="TotalizarMetas();" tabindex="11" /></td>
							<td width="41" nowrap="nowrap">Mes 10</td>
							<td width="29" align="left"><input name="t09_mes10" type="text"
								id="t09_mes10" value="<?php echo($row["t09_mes10"]);?>" size="3"
								maxlength="10" style="text-align: center"
								onkeyup="TotalizarMetas();" tabindex="14" /></td>
						</tr>
						<tr>
							<td height="31" nowrap="nowrap">Mes 2</td>
							<td align="left"><input name="t09_mes2" type="text" id="t09_mes2"
								value="<?php echo($row["t09_mes2"]);?>" size="3" maxlength="10"
								style="text-align: center" onkeyup="TotalizarMetas();"
								tabindex="6" /></td>
							<td nowrap="nowrap">Mes 5</td>
							<td align="left"><input name="t09_mes5" type="text" id="t09_mes5"
								value="<?php echo($row["t09_mes5"]);?>" size="3" maxlength="10"
								style="text-align: center" onkeyup="TotalizarMetas();"
								tabindex="9" /></td>
							<td nowrap="nowrap">Mes 8</td>
							<td align="left"><input name="t09_mes8" type="text" id="t09_mes8"
								value="<?php echo($row["t09_mes8"]);?>" size="3" maxlength="10"
								style="text-align: center" onkeyup="TotalizarMetas();"
								tabindex="12" /></td>
							<td nowrap="nowrap">Mes 11</td>
							<td align="left"><input name="t09_mes11" type="text"
								id="t09_mes11" value="<?php echo($row["t09_mes11"]);?>" size="3"
								maxlength="10" style="text-align: center"
								onkeyup="TotalizarMetas();" tabindex="15" /></td>
						</tr>
						<tr>
							<td nowrap="nowrap">Mes 3</td>
							<td align="left"><input name="t09_mes3" type="text" id="t09_mes3"
								value="<?php echo($row["t09_mes3"]);?>" size="3" maxlength="10"
								style="text-align: center" onkeyup="TotalizarMetas();"
								tabindex="7" /></td>
							<td nowrap="nowrap">Mes 6</td>
							<td align="left"><input name="t09_mes6" type="text" id="t09_mes6"
								value="<?php echo($row["t09_mes6"]);?>" size="3" maxlength="10"
								style="text-align: center" onkeyup="TotalizarMetas();"
								tabindex="10" /></td>
							<td nowrap="nowrap">Mes 9</td>
							<td align="left"><input name="t09_mes9" type="text" id="t09_mes9"
								value="<?php echo($row["t09_mes9"]);?>" size="3" maxlength="10"
								style="text-align: center" onkeyup="TotalizarMetas();"
								tabindex="13" /></td>
							<td nowrap="nowrap">Mes 12</td>
							<td align="left"><input name="t09_mes12" type="text"
								id="t09_mes12" value="<?php echo($row["t09_mes12"]);?>" size="3"
								maxlength="10" style="text-align: center"
								onkeyup="TotalizarMetas();" tabindex="16" /></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr valign="baseline">
				<td colspan="2" align="left" valign="top" nowrap>Acumulado al Año <?php echo($anio);?></td>
				<td colspan="2" align="left" nowrap="nowrap">Meta del Año</td>
				<td colspan="2" align="left" nowrap="nowrap">Total Acumulado</td>
			</tr>
			<tr valign="baseline">
				<td colspan="2" align="left" valign="top" nowrap><input
					name="t09_acum_anio" type="text" id="t09_acum_anio"
					value="<?php echo($rowAcum["MetaAcumAnio"]);?>" size="15"
					maxlength="10" readonly="readonly" style="text-align: center" /></td>
				<td colspan="2" align="left" nowrap="nowrap"><input
					name="t09_mta_anio" type="text" id="t09_mta_anio"
					value="<?php echo($row["t09_mta_anio"]);?>" size="13"
					maxlength="10" readonly="readonly" style="text-align: center" /></td>
				<td colspan="2" align="left" nowrap="nowrap"><input
					name="total_acumulado" type="text" id="total_acumulado"
					value="<?php echo( ($rowAcum["MetaTotalAcum"] + $row["t09_mta_anio"]));?>"
					size="15" maxlength="10" readonly="readonly"
					style="text-align: center" /> <input name="t09_tot_acum"
					type="hidden" id="t09_tot_acum"
					value="<?php echo($rowAcum["MetaTotalAcum"]);?>" /></td>
			</tr>

			<tr>
				<td colspan="6" valign="top">&nbsp;</td>
			</tr>
		</table>
		<input type="hidden" name="t02_cod_proy"
			value="<?php echo($idProy);?>" /> <input type="hidden"
			name="t02_version" value="<?php echo($idVersion);?>" /> <input
			type="hidden" name="t08_cod_comp" value="<?php echo($idComp);?>" /> <input
			type="hidden" name="t09_cod_act" value="<?php echo($idActiv);?>" />
	</form>

	<script language="javascript" type="text/javascript">
	  function cboAnios_OnChange()
	  {
	  	 var formulario = document.getElementById("frmMain") ;
		 formulario.action = "poa_meta_edit.php?&proc=<?php echo(md5("reload"));?>&action=<?php echo(md5("edit"));?>";
		 formulario.submit();
		 return true ;
	  }

	  function Cancelar()
	  {
		 parent.btnCancel_Clic();
		 return false;
	  }

	  function Guardar()
	  {
	     <?php $ObjSession->AuthorizedPage(); ?>
	  	 var formulario = document.getElementById("frmMain") ;
 		 if(formulario.cboAnios.value=="")
		 {
		 	alert("Ingrese Año");
			formulario.cboAnios.focus();
		 	return false ;
		 }

		 var mtaTotal = $('#t09_mta').val();
		 var mtaTotalAcum = $('#total_acumulado').val();

		 var Replicar = $('#chkReplicarGuardar').is(':checked');
		 if(Replicar)
		 {
		 	var bret = confirm("¿Estás Seguro de Replicar las Metas para los Siguientes Años ?\nLas Metas de la Actividad ["+$('#t09_cod_sub2').val()+"], existentes en los Siguientes años, serán eliminadas...");
			if(!bret)
			{return false;}
		 }


		 var mtaTotal = $('#t09_mta').val();
		 var mtaTotalAcum = $('#total_acumulado').val();

		 if( CNumber(mtaTotalAcum) > CNumber(mtaTotal))
		 { alert("La Meta Acumulada , Supera la Meta Total Planificada.") ; return false; }

		 formulario.action = "poa_meta_edit.php?&proc=<?php echo(md5("save"));?>&action=<?php echo(md5("edit"));?>";
		 return true ;
	  }

   </script>

	<script language="javascript" type="text/javascript">
	  function TotalizarMetas()
	  {
		  var m1 = $('#t09_mes1').val();
		  var m2 = $('#t09_mes2').val();
		  var m3 = $('#t09_mes3').val();
		  var m4 = $('#t09_mes4').val();
		  var m5 = $('#t09_mes5').val();
		  var m6 = $('#t09_mes6').val();
		  var m7 = $('#t09_mes7').val();
		  var m8 = $('#t09_mes8').val();
		  var m9 = $('#t09_mes9').val();
		  var m10 = $('#t09_mes10').val();
		  var m11 = $('#t09_mes11').val();
		  var m12 = $('#t09_mes12').val();
		  var total = (CNumber(m1) + CNumber(m2) + CNumber(m3) + CNumber(m4) + CNumber(m5) + CNumber(m6) + CNumber(m7) + CNumber(m8) + CNumber(m9) + CNumber(m10) + CNumber(m11) + CNumber(m12));
		  var acumOtrosAnios = $('#t09_tot_acum').val();
		  $('#t09_mta_anio').val(total);

		  var Replicar = $('#chkReplicarGuardar').is(':checked');
		  if(Replicar)
		  {
		 	var numAnios =$('#txtFinAnio').val();
		    var AnioActual = $('#cboAnios').val();
		    var DiffAnios = CNumber(numAnios) - CNumber(AnioActual);
			acumOtrosAnios = (DiffAnios * total ) ;
		  }
		   var totalmetas = (CNumber(acumOtrosAnios) + total);
		  $('#total_acumulado').val(totalmetas);
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
