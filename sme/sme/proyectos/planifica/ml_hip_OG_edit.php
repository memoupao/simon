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

$urlListado = "ml_hip_OG.php?idProy=$idProy&idVersion=$idVersion";

if ($proc == "save") {
    // --> Hacemos el Insert o Update
    $ReturnPage = false;
    if ($view == "edit") {
        $objML = new BLMarcoLogico();
        $ReturnPage = $objML->ActualizarSupuestosOG();
        $objML = NULL;
    }
    if ($view == "new") {
        $objML = new BLMarcoLogico();
        $ReturnPage = $objML->NuevoSupuestosOG();
        $objML = NULL;
    }
    
    if ($ReturnPage) {
        $Function->Redirect($urlListado);
    }
}

if ($proc == md5("del")) {
    
    $objML = new BLMarcoLogico();
    $id = $Function->__GET('id');
    $ReturnPage = $objML->EliminarSupuestosOG($idProy, $idVersion, $id);
    $objML = NULL;
    $Function->Redirect($urlListado . "&error=" . $objML->Error);
}

$urlSave = "ml_hip_OG_edit.php?idProy=$idProy&idVersion=$idVersion";

?>



<?php
$row = 0;
if ($view == "edit") {
    $idSupuestos = $Function->__GET('id');
    $objML = new BLMarcoLogico();
    $row = $objML->GetSupuestosOG($idProy, $idVersion, $idSupuestos);
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
    $objFunc->SetSubTitle("Supuestos de Proposito  -  Nuevo Registro");
} else {
    $objFunc->SetSubTitle("Supuestos de Proposito -  Editar Registro");
}

?>
<title>Supuestos Objetivo Desarrollo</title>
<style type="text/css">
<!--
#Layer1 {
	position: absolute;
	left: 545px;
	top: 0px;
	width: 182px;
	height: 60px;
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
</head>

<body>
	<!-- InstanceBeginEditable name="tBody" -->
<?php if($ObjSession->MaxVersionProy($idProy) > $idVersion) {$lsDisabled = 'disabled="disabled"' ; } else { $lsDisabled ='';} ?>
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


		<table align="center" class="TableEditReg">
			<tr valign="baseline">
				<td width="116" align="left" nowrap>Codigo</td>
				<td width="598" colspan="5" align="left"><input
					name="t07_cod_prop_sup" type="text" id="t07_cod_prop_sup"
					value="<?php echo($row["t07_cod_prop_sup"]);?>" size="4"
					maxlength="5" readonly></td>
			</tr>


			<tr valign="baseline">
				<td nowrap align="left" valign="top">Supuestos</td>
				<td colspan="5" align="left"><textarea name="t07_sup" cols="90"
						rows="12" id="t07_sup"><?php echo($row["t07_sup"]);?></textarea></td>
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
	  	 <?php if ($view != "edit") {?>
 		 if(formulario.t07_sup.value=="")
		 {
		 	alert("Ingrese Supuestos"); 
			formulario.t07_sup.focus();
		 	return false ;
		 }
		 <?php } ?>
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
mysql_free_result($rsSupuestos);
?>
