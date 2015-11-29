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

$view = $Function->__GET('view');

$urlListado = "ml_ind_OD.php?idProy=$idProy&idVersion=$idVersion";

if ($proc == "save") {
    // --> Hacemos el Insert o Update
    $ReturnPage = false;
    if ($view == "edit") {
        $objML = new BLMarcoLogico();
        $ReturnPage = $objML->ActualizarIndicadorOD();
        $objML = NULL;
    }
    if ($view == "new") {
        $objML = new BLMarcoLogico();
        $ReturnPage = $objML->NuevoIndicadorOD();
        $objML = NULL;
    }
    
    if ($ReturnPage) {
        $Function->Redirect($urlListado);
    }
}

if ($proc == md5("del")) {
    
    $objML = new BLMarcoLogico();
    $id = $Function->__GET('id');
    $ReturnPage = $objML->EliminarIndicadorOD($idProy, $idVersion, $id);
    $objML = NULL;
    $Function->Redirect($urlListado . "&error=" . $objML->Error);
}

$urlSave = "ml_ind_OD_edit.php?idProy=$idProy&idVersion=$idVersion";

?>



<?php
$row = 0;
if ($view == "edit") {
    $idIndicador = $Function->__GET('id');
    $objML = new BLMarcoLogico();
    $row = $objML->GetIndicadorOD($idProy, $idVersion, $idIndicador);
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
    $objFunc->SetSubTitle("Indicadores de Finalidad  -  Nuevo Registro");
} else {
    $objFunc->SetSubTitle("Indicadores de Finalidad  -  Editar Registro");
}

?>
<title>Indicadores Objetivo Desarrollo</title>
<style type="text/css">
<!--
#Layer1 {
	position: absolute;
	left: 545px;
	top: 0px;
	width: 182px;
	height: 56px;
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
   			$('#t06_mta').numeric().pasteNumeric();
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
					<td width="10%"><button class="Button" value="Cancelar"
							onclick="return Cancelar();">Cancelar</button></td>
					<td align="center" style="color: #00F;"><?php echo($objFunc->SubTitle) ;?></td>
				</tr>
			</table>
		</div>
		<br />
		<table align="center" class="TableEditReg">
			<tr valign="baseline">
				<td width="116" align="left" nowrap>Codigo</td>
				<td colspan="5" align="left"><input name="t06_cod_fin_ind"
					type="text" value="<?php echo($row["t06_cod_fin_ind"]);?>" size="4"
					maxlength="5" readonly></td>
			</tr>
			<tr valign="baseline">
				<td nowrap align="left">Indicador</td>
				<td colspan="5" align="left"><input type="text" name="t06_ind"
					value="<?php echo($row["t06_ind"]);?>" size="80"></td>
			</tr>
			<tr valign="baseline">
				<td nowrap align="left">Unidad Medida</td>
				<td width="152" align="left"><input type="text" name="t06_um"
					id="t06_um" value="<?php echo($row["t06_um"]);?>" size="30"></td>
				<td width="28" align="left">Meta</td>
				<td width="52" align="left"><input name="t06_mta" id="t06_mta"
					type="text" value="<?php echo($row["t06_mta"]);?>" size="12"
					maxlength="8" /></td>
				<td width="63" align="left">&nbsp;</td>
				<td width="303" align="left">&nbsp;</td>
			</tr>

			<tr valign="baseline">
				<td nowrap align="left" valign="top">Fuentes Verificacion</td>
				<td colspan="5" align="left"><textarea name="t06_fv" cols="76"
						rows="5"><?php echo($row["t06_fv"]);?></textarea></td>
			</tr>

			<tr valign="baseline">
				<td nowrap align="left" valign="top">Observaciones</td>
				<td colspan="5" align="left"><textarea name="t06_obs" cols="76"
						rows="5"><?php echo($row["t06_obs"]);?></textarea></td>
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
		 //formulario.submit() ;
		 return true;
	  }
	  
	  function Guardar()
	  {
	     <?php $ObjSession->AuthorizedPage(); ?>	
	  	 var formulario = document.getElementById("frmMain") ;
	  	 <?php if ($view != "edit") { ?>
 		 if(formulario.t06_ind.value=="")
		 {
		 	alert("Ingrese Nombre del Indicador"); 
			formulario.t06_ind.focus();
		 	return false ;
		 }

 		if ($('#t06_um').val().trim() == "") {
		 	alert("Ingrese Unidad de Medida");
		 	$('#t06_um').focus();
		 	return false;
		 }
		 
		 <?php } else { ?>
		 
		 if ($('#t06_um').val().trim() == "") {
			 $('#t06_um').val('-');
		 }
		<?php }?>


		 
		 
		 
		 
		 formulario.action = "<?php echo($urlSave);?>&proc=save&view=<?php echo($view);?>"; 
		 //formulario.submit() ;
		 return true ;
	  }
	  
   </script>

	<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd -->
</html>
<?php
mysql_free_result($rsIndicadores);
?>
