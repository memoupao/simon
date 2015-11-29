<?php
/**
 * CticServices
 *
 * Procesa los mantenimientos de tasas derivados de:
 * man_tasa.php
 *
 * @package	Admin
 * @author	DA
 * @since	Version 2.0
 *
 * @param	string $_GET['action'] Nombre de la acción a procesar.
 *
 */
include ("../includes/constantes.inc.php");
include ("../includes/validauseradm.inc.php");
require (constant('PATH_CLASS') . "BLMantenimiento.class.php");

$objMante = new BLMantenimiento();
$action = $objFunc->__GET('action');
$concepto = 'tasa';
$title = 'Tasas';

$row = 0;
if ($action == md5("ajax_edit")) {
    $objFunc->SetSubTitle("Editando " . $title);
    $id = $objFunc->__GET('id');
    $row = $objMante->seleccionar($concepto, $id);
} else {
    $row = 0;
    $objFunc->SetSubTitle("Nuevo " . $title);
}
?>
<?php if($action=='') { ?>
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
<script src="../jquery.ui-1.5.2/jquery-1.2.6.js" type="text/javascript"></script>
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
	<div id="EditForm" style="width: 700px; border: solid 1px #D3D3D3;">
						<br />
						<div id="toolbar" style="height: 4px;" class="BackColor">
							<table width="100%" border="0" cellpadding="0" cellspacing="0">
								<tr>
									<td width="9%"><button class="Button"
											onclick="btnGuardar_Clic(); return false;" value="Guardar">Guardar
										</button></td>
									<td width="9%"><button class="Button"
											onclick="btnCancelar_Clic(); return false;" value="Cancelar">Cancelar</button></td>
									<td width="31%">&nbsp;</td>
									<td width="2%">&nbsp;</td>
									<td width="2%">&nbsp;</td>
									<td width="2%">&nbsp;</td>
									<td width="47%" align="right"><?php echo($objFunc->SubTitle) ;?></td>
								</tr>
							</table>
						</div>
						<table width="700" border="0" cellpadding="0" cellspacing="0"
							class="TableEditReg">
							<tr>
								<td width="542">
									<fieldset>
										<legend>Datos Generales</legend>
										<table width="100%" border="0" cellspacing="1" cellpadding="0">
											<tr>
												<td width="18%">Número</td>
												<td width="39%"><input type="text" disabled="disabled"
													value="<?php echo($row['numero']); ?>" size="5"
													style="text-align: center;" /> <input name="numero"
													type="hidden" id="numero"
													value="<?php echo($row['numero']); ?>" /></td>
												<td width="9%">&nbsp;</td>
												<td width="34%">&nbsp;</td>
											</tr>
											<tr>
												<td>Nombre Tasa</td>
												<td><input name="abreviatura" type="text" id="abreviatura"
													value="<?php echo($row['abreviatura']); ?>" size="50"
													maxlength="15" required /></td>
												<td>&nbsp;</td>
												<td>&nbsp;</td>
											</tr>
											<tr>
												<td nowrap="nowrap">Valor Tasa</td>
												<td><input name="nombre" type="text" id="nombre"
													value="<?php echo( $row['nombre']); ?>" size="50"
													maxlength="50" required /></td>
												<td>&nbsp;</td>
												<td>&nbsp;</td>
											</tr>
											<tr>
												<td>&nbsp;</td>
												<td>&nbsp;</td>
												<td align="right">&nbsp;</td>
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
	    if( $('#abreviatura').val()=="" ) {alert("Ingrese el Nombre de la Tasa"); $('#abreviatura').focus(); return false;}
	    if( $('#nombre').val()=="" ) {alert("Ingrese el Valor (%) de la Tasa"); $('#nombre').focus(); return false;}

	// -------------------------------------------------->
    // DA 2.0 [04-11-2013 12:54]
    // Validacion de longitud minima en formulario de edicion y nuevo 	
    if (typeof(isValidFormsMantenimiento) == "function") {		    
	    	if( ! isValidFormsMantenimiento() ) return false;
	}
	// --------------------------------------------------<
		
	 
	 var BodyForm = $("#FormData").serialize() ;
	 var sURL = "man_concepto_process.php?action=<?php echo($action);?>&concepto=<?php echo($concepto);?>"
	 var req = Spry.Utils.loadURL("POST", sURL, true, MySuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
	return false;
	}
  </script>
 <?php if($action=='') { ?>
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