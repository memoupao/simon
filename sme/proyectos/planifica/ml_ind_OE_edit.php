<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>

<?php
require_once ("../../../clases/Functions.class.php");
require_once ("../../../clases/BLMarcoLogico.class.php");
error_reporting("E_PARSE");
$Function = new Functions();
$proc = $Function->__GET('proc');

$idProy = $Function->__GET('idProy');
$idVersion = $Function->__GET('idVersion');
$idComp = $Function->__GET('idOE');

$view = $Function->__GET('view');

$urlListado = "ml_ind_OE.php?idProy=$idProy&idVersion=$idVersion&t08_cod_comp=$idComp";

if ($proc == "save") {
    // --> Hacemos el Insert o Update
    $ReturnPage = false;
    if ($view == "edit") {
        $objML = new BLMarcoLogico();
        $ReturnPage = $objML->ActualizarIndicadoresOE();
        $objML = NULL;
    }
    if ($view == "new") {
        $objML = new BLMarcoLogico();
        $ReturnPage = $objML->NuevoIndicadoresOE();
        $objML = NULL;
    }

    if ($ReturnPage) {
        $Function->Redirect($urlListado);
    }
}

if ($proc == md5("del")) {

    $objML = new BLMarcoLogico();
    $id = $Function->__GET('id');
    $ReturnPage = $objML->EliminarIndicadoresOE($idProy, $idVersion, $idComp, $id);
    $objML = NULL;
    $Function->Redirect($urlListado . "&error=" . $objML->Error);
}

$urlSave = "ml_ind_OE_edit.php?idProy=$idProy&idVersion=$idVersion&idOE=$idComp";

?>



<?php
$row = 0;
if ($view == "edit") {
    $idIndicador = $Function->__GET('id');
    $objML = new BLMarcoLogico();
    $row = $objML->GetIndicadoresOE($idProy, $idVersion, $idComp, $idIndicador);
    $objML = NULL;
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
    $objFunc->SetSubTitle("Indicadores de Componentes  -  Nuevo Registro");
} else {
    $objFunc->SetSubTitle("Indicadores de Componentes -  Editar Registro");
}

?>
<title>Indicadores Objetivo Especifico</title>
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
<!-- InstanceEndEditable -->
<link href="../../../css/itemplate.css" rel="stylesheet" type="text/css"
	media="all" />
<SCRIPT src="../../../jquery.ui-1.5.2/jquery-1.2.6.js"
	type="text/javascript"></SCRIPT>
<script src="../../../jquery.ui-1.5.2/jquery.numeric.js"
	type="text/javascript"></script>
<script src="../../../js/commons.js" type="text/javascript"></script>
</head>

<body>
	<!-- InstanceBeginEditable name="tBody" -->
<?php if($ObjSession->MaxVersionProy($idProy) > $idVersion) {$lsDisabled = 'disabled="disabled"' ; } else { $lsDisabled ='';} ?>
   <script type="text/javascript">
   <!--
   		jQuery(document).ready(function(){
   			$('#t08_mta').numeric().pasteNumeric();
   		});
   // -->
   </script>

	<form id="frmMain" name="frmMain" method="post" action="#">
		<div id="toolbar" style="height: 4px;" class="Subtitle">
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td width="6%"><button class="Button"
							onclick="return Guardar(); return false;" value="Guardar"
							<?php echo($lsDisabled)?>>Guardar</button></td>
					<td width="12%"><button class="Button" value="Cancelar"
							onclick="return Cancelar();">Cancelar</button></td>

					<td width="82%" align="center" style="color: #00F;"><?php echo($objFunc->SubTitle) ;?></td>
				</tr>
			</table>
		</div>
		<br />
		<table width="760" align="center" class="TableEditReg">
			<tr valign="baseline">
				<td align="left" nowrap class="TextDescripcion"><b>Componente</b></td>
				<td colspan="5" align="left"><select name="t08_cod_comp"
					id="t08_cod_comp" style="width: 500px;"
					<?php if($view=="edit") {echo("disabled");}?>>
          <?php
        $objML = new BLMarcoLogico();
        $rsOE = $objML->ListadoDefinicionOE($idProy, $idVersion);

        $idComp = $Function->__POST("t08_cod_comp");
        if ($idComp == "") {
            $idComp = $Function->__GET("t08_cod_comp");
        }
        $Function->llenarCombo($rsOE, 't08_cod_comp', 'descripcion', $idComp);
        ?>
        </select>
		<?php

if ($view == "edit") {
    echo ("<input type='hidden' name='t08_cod_comp' id='t08_cod_comp' value='" . $idComp . "'>");
}
?>
		</td>
			</tr>

			<tr valign="baseline">
				<td nowrap align="left">Codigo</td>
				<td colspan="5" align="left"><input name="t08_cod_comp_ind"
					type="text" value="<?php echo($row["t08_cod_comp_ind"]);?>"
					size="2" maxlength="5" readonly="readonly" /></td>
			</tr>
			<tr valign="baseline">
				<td nowrap align="left"><b>Indicador</b></td>
				<td colspan="5" align="left"><input type="text" name="t08_ind"
					value="<?php echo($row["t08_ind"]);?>" size="85"></td>
			</tr>
			<tr valign="baseline">
				<td nowrap align="left"><b>Unidad Medida</b></td>
				<td width="152" align="left"><input type="text" name="t08_um"
					id="t08_um" value="<?php echo($row["t08_um"]);?>" size="30"></td>
				<td width="28" align="left"><b>Meta</b></td>
				<td width="52" align="left"><input type="text" name="t08_mta"
					id="t08_mta" value="<?php echo($row["t08_mta"]);?>" size="12"
					maxlength="8" /></td>
				<td width="63" align="left">&nbsp;</td>
				<td width="303" align="left">&nbsp;</td>
			</tr>

			<tr valign="baseline">
				<td nowrap align="left" valign="top"><b>Fuentes Verificacion</b></td>
				<td colspan="5" align="left"><textarea name="t08_fv" id="t08_fv" cols="76"
						rows="5"><?php echo($row["t08_fv"]);?></textarea></td>
			</tr>

			<tr valign="baseline">
				<td nowrap align="left" valign="top">Observaciones</td>
				<td colspan="5" align="left"><textarea name="t08_obs" id="t08_obs" cols="76"
						rows="5"><?php echo($row["t08_obs"]);?></textarea></td>
			</tr>
			<tr valign="baseline">
				<td nowrap align="left">Fecha registro:</td>
				<td colspan="5" align="left"><input type="text" name="fch_crea"
					value="<?php echo($row["fch_crea"]);?>" size="25" disabled />
					Usuario: <input type="text" name="usr_crea"
					value="<?php echo($row["usr_crea"]);?>" size="30" disabled /></td>
			</tr>

			<tr valign="baseline">
				<td nowrap align="left">&nbsp;</td>
				<td colspan="5"></td>
			</tr>
		</table>
		<input type="hidden" name="t02_cod_proy"
			value="<?php echo($idProy);?>"> <input type="hidden"
			name="t02_version" value="<?php echo($idVersion);?>">
	</form>

	<script language="javascript" type="text/javascript">

	function Cancelar()
	{
		var formulario = document.getElementById("frmMain") ;
		formulario.action = "<?php echo($urlListado);?>"
		return true;
	}

	function Guardar()
	{
		<?php $ObjSession->AuthorizedPage(); ?>
		var formulario = document.getElementById("frmMain") ;
<?php if ($view != "edit") { ?>
		if (typeof($('#t08_cod_comp').val())!="undefined") {
			if ($('#t08_cod_comp').val().trim() == "") {
			 	alert("Seleccione el Componente");
			 	$('#t08_cod_comp').focus();
			 	return false;
			}

			if(formulario.t08_ind.value=="") {
				alert("Ingrese Nombre del Indicador");
				formulario.t08_ind.focus();
				return false ;
			}
			if ($('#t08_um').val().trim() == "") {
			 	alert("Ingrese Unidad de Medida");
			 	$('#t08_um').focus();
			 	return false;
			}

			if ($('#t08_mta').val().trim() == "") {
			 	alert("Ingrese la Meta");
			 	$('#t08_mta').focus();
			 	return false;
			}

			if ($('#t08_fv').val().trim() == "") {
			 	alert("Ingrese Fuentes Verificacion");
			 	$('#t08_fv').focus();
			 	return false;
			}

			
		}
		
		
		
<?php } ?>
		formulario.action = "<?php echo($urlSave);?>&proc=save&view=<?php echo($view);?>";
		return true ;
	}

</script>
	<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd -->
</html>
