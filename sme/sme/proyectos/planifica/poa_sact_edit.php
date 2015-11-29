<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>

<?php
require_once ("../../../clases/Functions.class.php");
require_once ("../../../clases/BLMarcoLogico.class.php");
require_once ("../../../clases/BLPOA.class.php");

$Function = new Functions();
$proc = $Function->__GET('proc');
$view = $Function->__GET('action');

$idProy = $Function->__GET('idProy');
$idVersion = $Function->__GET('idVersion');
$idComp = $Function->__GET('idComp');
$idActiv = $Function->__GET('idActiv');
$idSActiv = $Function->__GET('idSActiv');

$modif = $Function->__GET('m');
$solove = false;
if (md5("solo_ve_no_edita") == $modif) {
	$solove = true;
}


$objPOA = new BLPOA();
$row = $objPOA->GetSubActividad($idProy, $idVersion, $idComp, $idActiv, $idSActiv);

if ($proc == "save") {
    // --> Hacemos el Insert o Update
    $ReturnPage = false;
    if ($view == md5("edit")) {
        $objPOA = new BLPOA();
        $ReturnPage = $objPOA->ActualizarSubActividad();
        $objPOA = NULL;
    }
    if ($view == md5("new")) {
        // $Function->Debug(true);
        $objPOA = new BLPOA();
        $ReturnPage = $objPOA->NuevoSubActividad();
        $objPOA = NULL;
    }

    if ($ReturnPage) {
        $script = "alert('Se grabó correctamente el Registro'); \n";
        $script .= "parent.btnSuccess(); \n";
        $Function->Javascript($script);
        exit(1);
    }
}

if ($proc == md5("del")) {

    $objPOA = new BLPOA();
    $ReturnPage = $objPOA->EliminarSubActividad();

    if ($ReturnPage) {
        $script = "alert('Se Elimino el registro correctamente'); \n";
        $script .= "parent.btnSuccess(); \n";
        $Function->Javascript($script);
        exit(1);
    } else {
        $script = "alert(\"" . $objPOA->Error . "\"); \n";
        $Function->Javascript($script);
        exit(1);
    }

    $objPOA = NULL;
}

$urlSave = "poa_sact_edit.php?idProy=$idProy&idVersion=$idVersion&idComp=$idComp&idActiv=$idActiv";

?>



<?php
$row = 0;
if ($view == md5("edit")) {
    $idSActiv = $Function->__GET('idSActiv');
    $objPOA = new BLPOA();
    $row = $objPOA->GetSubActividad($idProy, $idVersion, $idComp, $idActiv, $idSActiv);
    $objPOA = NULL;
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

if ($view == md5("new")) {
    $objFunc->SetSubTitle("Actividades  -  Nuevo Registro");
} else {
    $objFunc->SetSubTitle("Actividades -  Editar Registro");
}

?>
<title>Actividades - Plan Operativo</title>
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->
<script language="javascript"
	src="../../../jquery.ui-1.5.2/jquery-1.2.6.js">
</script>
<script language="javascript"
	src="../../../jquery.ui-1.5.2/jquery.numeric.js">
</script>
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
		<br />

		<table width="393" align="center" class="TableEditReg">
			<tr valign="baseline">
				<td width="83" align="left" nowrap>Codigo</td>
				<td colspan="3" align="left"><input name="t09_cod_sub" type="hidden"
					id="t09_cod_sub" value="<?php echo($row["t09_cod_sub"]);?>"
					size="2" maxlength="5" readonly style="text-align: center;"> <input
					name="t09_cod_sub2" type="text" id="t09_cod_sub2"
					value="<?php  echo($row["t08_cod_comp"].".".$row["t09_cod_act"].".".$row["t09_cod_sub"]);?>"
					size="2" maxlength="5" disabled style="text-align: center;"></td>
			</tr>
			<tr valign="baseline">
				<td nowrap align="left" valign="top">Actividad</td>
				<td colspan="3" align="left"><input name="t09_sub" type="text"
					id="t09_sub" value="<?php echo($row["t09_sub"]);?>" size="75"
					maxlength="250" /></td>
			</tr>
			<tr valign="baseline">
				<td nowrap align="left" valign="top">Unidad Medida</td>
				<td width="160" align="left"><input name="t09_um" type="text"
					id="t09_um" value="<?php echo($row["t09_um"]);?>" size="25"
					maxlength="30" /></td>
				<td width="92" align="left" nowrap="nowrap">Metas Globales</td>
				<td width="84" align="left"><input name="t09_mta" type="text"
					id="t09_mta" value="<?php echo($row["t09_mta"]);?>" size="18"
					maxlength="30" /></td>
			</tr>
			<tr valign="baseline">
				<td align="left" valign="top">Fuentes de Verificación</td>
				<td colspan="3" align="left"><textarea name="t09_fv" cols="72"
						rows="3" id="t09_fv"><?php echo($row["t09_fv"]);?></textarea></td>
			</tr>
			<tr valign="baseline">
				<td nowrap align="left" valign="top">Tipo Actividad</td>
				<td align="left"><select name="t09_tipo_sub" id="t09_tipo_sub"
					style="width: 160px;">
						<option value="" selected="selected"></option>
        <?php
        require ("../../../clases/BLTablasAux.class.php");
        $objTablas = new BLTablasAux();
        $rs = $objTablas->TipoSubActividades();
        $Function->llenarCombo($rs, 'codigo', 'descripcion', $row['t09_tipo_sub']);
        $objTablas = NULL;
        ?>
        </select></td>
				<td align="left" nowrap="nowrap">Actividad Critica</td>
				<td align="left"><input name="t09_act_crit" type="checkbox"
					id="t09_act_crit" value="1"
					<?php if($row['t09_act_crit']=='1'){echo('checked');}?> /></td>
			</tr>
			<tr valign="baseline">
				<td nowrap align="left" valign="top">Observaciones</td>
				<td colspan="3" align="left"><textarea name="t09_obs" cols="72"
						rows="3" id="t09_obs"><?php echo($row["t09_obs"]);?></textarea></td>
			</tr>
		</table>
		<input type="hidden" name="t02_cod_proy"
			value="<?php echo($idProy);?>"> <input type="hidden"
			name="t02_version" value="<?php echo($idVersion);?>" /> <input
			type="hidden" name="t08_cod_comp" value="<?php echo($idComp);?>"> <input
			type="hidden" name="t09_cod_act" value="<?php echo($idActiv);?>">

	</form>
	<script language="javascript" type="text/javascript">
	<?php if($solove): ?>
	$(document).ready(function(){
		$('#frmMain').find('input, select, textarea').attr('disabled','disabled');
		$('#frmMain').find('button[value="Guardar"]').remove();		
	});
	<?php endif;?>
	

	  function Cancelar()
	  {
		 parent.btnCancel_Clic();
		 return false;
	  }

	  function Guardar()
	  {
	     <?php $ObjSession->AuthorizedPage(); ?>
	  	 var formulario = document.getElementById("frmMain") ;
 		/*
		 if(formulario.t08_sup.value=="")
		 {
		 	alert("Ingrese Supuestos");
			formulario.t08_sup.focus();
		 	return false ;
		 }*/

		 formulario.action = "<?php echo($urlSave);?>&proc=save&action=<?php echo($view);?>";
		 //formulario.submit() ;
		 return true ;
	  }

	 $('#t09_mta').numeric();

   </script>

	<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd -->
</html>
