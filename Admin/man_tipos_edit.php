<?php include("../includes/constantes.inc.php"); ?>
<?php include("../includes/validauseradm.inc.php"); ?>
<?php

require (constant('PATH_CLASS') . "BLTablasAux.class.php");
$objTablas = new BLTablasAux();
$view = $objFunc->__GET('mode');
$Tabla = $objFunc->__GET('idtabla');

$row = 0;

if ($view == md5("ajax_edit")) {
    
    $objFunc->SetSubTitle("Editando Tipo");
    $id = $objFunc->__GET('id');
    $row = $objTablas->TipoSeleccionar($id);
} else {
    $row = 0;
    $objFunc->SetSubTitle("Nuevo Tipo");
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
<link href="../css/template.css" rel="stylesheet" media="all" />
<SCRIPT src="../jquery.ui-1.5.2/jquery-1.2.6.js" type=text/javascript></SCRIPT>
<script src="../jquery.ui-1.5.2/ui/ui.datepicker.js"
	type="text/javascript"></script>
<link href="../jquery.ui-1.5.2/themes/ui.datepicker.css"
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
 
	<div id="EditForm" style="width: 780px; border: solid 1px #D3D3D3;">
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
						<table width="100%" border="0" cellpadding="0" cellspacing="0"
							class="TableEditReg">
							<tr>
								<td width="542">
									<fieldset>
										<legend>Datos Generales</legend>
										<table width="100%" border="0" cellspacing="1" cellpadding="0">
											<tr>
												<td width="13%">Codigo</td>
												<td width="54%"><input name="txtcodigo" type="text"
													disabled="disabled" id="txtcodigo"
													value="<?php echo($row['codi']); ?>" size="8" maxlength="8"
													readonly="readonly"
													<?php if($view == md5("ajax_edit")){echo('disabled="disabled"');}?> />
													<input name="txtcodigo" type="hidden" id="txtcodigo"
													value="<?php echo($row['codi']); ?>"> <input type="hidden"
													name="txtidtabla" id="txtidtabla"
													value="<?php echo($Tabla);?>" /></td>
												<td width="13%">&nbsp;</td>
												<td width="20%" rowspan="7" align="left" valign="top">&nbsp;</td>
											</tr>
											<tr>
												<td height="27" nowrap="nowrap">Descripción</td>
												<td><input name="txtnombre" type="text" id="txtnombre"
													value="<?php echo($row['descrip']); ?>" size="80"
													maxlength="100" required /></td>
												<td>&nbsp;</td>
											</tr>
											<tr>
												<td height="31">Abreviatura</td>
												<td><input name="txtabrev" type="text" id="txtabrev"
													value="<?php echo($row['abrev']); ?>" size="30"
													maxlength="15" required /></td>
												<td>&nbsp;</td>
											</tr>
											<tr>
												<td nowrap="nowrap">Codigo Externo</td>
												<td><input name="txtexterno" type="text" id="txtexterno"
													value="<?php echo($row['cod_ext']); ?>" size="10"
													maxlength="20" /></td>
												<td>&nbsp;</td>
											</tr>
											<tr>
												<td height="28">Tabla Auxiliar</td>
												<td><select name="cbotablaaux" id="cbotablaaux"
													style="width: 220px">
														<option value=""></option>
            <?php
            $idTablaAux = $row['idTabla'];
            if ($idTablaAux == "") {
                $idTablaAux = $Tabla;
            }
            
            $rs = $objTablas->ListaTablas();
            $objFunc->llenarCombo($rs, 'cod_tabla', 'nom_tabla', $idTablaAux);
            ?>
            </select></td>
												<td>&nbsp;</td>
											</tr>
											<tr>
												<td height="31">Orden</td>
												<td><input name="txtorden" type="text" id="txtorden"
													value="<?php echo($row['orden']); ?>" size="3"
													maxlength="3" required /></td>
												<td>&nbsp;</td>
											</tr>
											<tr>
												<td>Activo</td>
												<td><input type="checkbox" name="chkactivo" id="chkactivo"
													value="1"
													<?php if($row['flg_act']=='1'){echo('checked');}?> /></td>
												<td>&nbsp;</td>
											</tr>

										</table>
									</fieldset>
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
							</tr>
						</table>
					</div>

<script language="javascript">
function btnGuardar_Clic()
{
    if( $('#txtnombre').val()=="" ) {
        alert("Ingrese por favor la Descripción");
        $('#txtnombre').focus();
        return false;
    }

    // -------------------------------------------------->
    // DA 2.0 [04-11-2013 12:54]
    // Validacion de longitud minima en formulario de edicion y nuevo 	
    if (typeof(isValidFormsMantenimiento) == "function") {		    
	    	if( ! isValidFormsMantenimiento() ) return false;
	}
	// --------------------------------------------------<
	
    var BodyForm = $("#FormData").serialize() ;
    var sURL = "man_tipos_process.php?action=<?php echo($view);?>"
    var req = Spry.Utils.loadURL("POST", sURL, true, MySuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
	
    return false;
	
}
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

