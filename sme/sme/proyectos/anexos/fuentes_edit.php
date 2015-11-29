<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>

<?php
require (constant('PATH_CLASS') . "BLTablasAux.class.php");
require (constant('PATH_CLASS') . "BLFuentes.class.php");

$OjbTab = new BLTablasAux();
$view = $objFunc->__GET('mode');
$row = 0;
$objFuentes = new BLFuentes();
if ($view == md5("ajax_edit")) {
    $objFunc->SetSubTitle("Fuente de Financiamiento / Editar Registro");
    $idProy = $objFunc->__GET('idProy');
    $id = $objFunc->__GET('id');
    
    $row = $objFuentes->ContactosSeleccionar($idProy, $id);
    
    $t02_cod_proy = $row['t02_cod_proy'];
    $t01_id_inst = $row['t01_id_inst'];
    $t02_obs_fte = $row['t02_obs_fte'];
    $usr_crea = $row['usr_crea'];
    $fch_crea = $row['fch_crea'];
    $est_audi = $row['est_audi'];
    // $objFuentes = NULL;
    // Se va a modificar el registro !!
} else {
    $objFunc->SetSubTitle("Fuente de Financiamiento / Nuevo Registro");
    $t02_cod_proy = $objFunc->__GET('idProy');
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/tplAjaxForm.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<!-- InstanceBeginEditable name="doctitle" -->
<?php
$objFunc->SetTitle("Ejecutores - Contactos");
?>
<!-- InstanceEndEditable -->
<?php

$objFunc->verifyAjax();
if (! $objFunc->Ajax) {
    ?>
<meta name="keywords" content="<?php echo($objFunc->Keywords); ?>" />
<meta name="description" content="<?php echo($objFunc->MetaTags); ?>" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo($objFunc->Title);?></title>
<link href="../../css/template.css" rel="stylesheet" media="all" />
<SCRIPT src="../../jquery.ui-1.5.2/jquery-1.2.6.js" type=text/javascript></SCRIPT>
<script src="../../jquery.ui-1.5.2/ui/ui.datepicker.js"
	type="text/javascript"></script>
<link href="../../jquery.ui-1.5.2/themes/ui.datepicker.css"
	rel="stylesheet" type="text/css" />

<!-- InstanceBeginEditable name="head" -->
<script src="../../../SpryAssets/xpath.js" type="text/javascript"></script>
<script src="../../../SpryAssets/SpryData.js" type="text/javascript"></script>
<script src="../../../SpryAssets/SpryPagedView.js"
	type="text/javascript"></script>

<script src="../../../SpryAssets/SpryPopupDialog.js"
	type="text/javascript"></script>
<script src="../../../SpryAssets/SpryTabbedPanels.js"
	type="text/javascript"></script>
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
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td width="1%">&nbsp;</td>
						<td width="73%">&nbsp;</td>
						<td width="26%">&nbsp;</td>
					</tr>
					<tr>
						<td height="18">&nbsp;</td>
						<td><b style="text-decoration: underline"> </b> &nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
				</table>
				<!-- InstanceEndEditable -->
				<div id="divContent">
					<!-- InstanceBeginEditable name="Contenidos" -->

					<div id="EditForm"
						style="width: 700px; border: solid 1px #D3D3D3; font-weight: bold;">
						<br />
						<div id="toolbar" style="height: 4px;" class="BackColor">
							<table width="100%" border="0" cellpadding="0" cellspacing="0">
								<tr>
									<td width="9%"><button class="Button"
											onclick="btnGuardar_Clic(); return false;" value="Guardar">Guardar
										</button></td>
									<td width="9%"><button class="Button"
											onclick="btnCancelar_Clic(); return false;" value="Cancelar">
											Cancelar</button></td>
									<td width="31%">&nbsp;</td>
									<td width="2%">&nbsp;</td>
									<td width="2%">&nbsp;</td>
									<td width="2%">&nbsp;</td>
									<td width="47%" align="right"><?php echo($objFunc->SubTitle) ;?></td>
								</tr>
							</table>
						</div>
						<table width="100%" border="0" cellpadding="0" cellspacing="2"
							class="TableEditReg">
							<tr>
								<td width="1%">&nbsp;</td>
								<td colspan="6">
      <?php
    
if ($id == "") {
        $sURL = "fuentes_process.php?action=" . md5("ajax_new");
    } else {
        $sURL = "fuentes_process.php?action=" . md5("ajax_edit");
    }
    ?>
      <input type="hidden" name="txturlsave" id="txturlsave"
									value="<?php echo($sURL); ?>" /> <input type="hidden"
									name="t02_cod_proy" id="t02_cod_proy"
									value="<?php echo($t02_cod_proy); ?>" /> <input type="hidden"
									name="t01_id_inst2" id="t01_id_inst2"
									value="<?php echo($t01_id_inst); ?>" />
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td align="right">&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td width="13%">&nbsp;</td>
								<td width="19%">&nbsp;</td>
								<td width="15%" align="right">&nbsp;</td>
								<td width="17%">&nbsp;</td>
								<td width="11%" align="right">&nbsp;</td>
								<td width="24%">&nbsp;</td>
							</tr>

							<tr>
								<td>&nbsp;</td>
								<td>Institución</td>
								<td colspan="5"><select name="t01_id_inst" id="t01_id_inst"
									style="width: 510px;">
										<option value=""></option>
      <?php
    $rs = $objFuentes->Directorio();
    $objFunc->llenarCombo($rs, 't01_id_inst', 't01_sig_inst', $t01_id_inst);
    ?>
    </select> <?php if($view == md5("ajax_new")) {?>
      <input type="image" name="btnBuscar" id="btnBuscar"
									src="../../../img/gosearch.gif" width="14" height="15"
									class="Image" onclick="AgregarInst(); return false;"
									title="Agregar Instituciones" style="display: none;" />
      <?php }?>
      </td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>Observación</td>
								<td colspan="5"><textarea name="t02_obs_fte" cols="100" rows="4"
										id="t02_obs_fte"><?php echo($t02_obs_fte); ?></textarea></td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td colspan="5">&nbsp;</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td colspan="5">&nbsp;</td>
							</tr>
						</table>

						<br />
					</div>


					<script language="JavaScript" type="text/javascript">

		
</script>
					<!-- InstanceEndEditable -->
				</div>
			</form>
		</div>
		<!-- Fin de Container Page-->
	</div>

</body>
<!-- InstanceEnd -->
</html>


