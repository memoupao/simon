<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauser.inc.php"); ?>

<?php
require (constant('PATH_CLASS') . "BLTablasAux.class.php");
require (constant('PATH_CLASS') . "BLFE.class.php");

$OjbTab = new BLTablasAux();
$view = $objFunc->__GET('mode');
$row = 0;
if ($view == md5("ajax_edit")) {
    $objFunc->SetSubTitle("Equipo Fondoempleo / Editar Registro");
    $id = $objFunc->__GET('id');
    $objEqui = new BLFE();
    
    $row = $objEqui->EquipoSeleccionar($id);
    
    $t90_id_equi = $row['t90_id_equi'];
    $t90_dni_equi = $row['t90_dni_equi'];
    $t90_ape_pat = $row['t90_ape_pat'];
    $t90_ape_mat = $row['t90_ape_mat'];
    $t90_nom_equi = $row['t90_nom_equi'];
    
    $t90_sexo_equi = $row['t90_sexo_equi'];
    $t90_edad_equi = $row['t90_edad_equi'];
    $t90_cali_equi = $row['t90_cali_equi'];
    $t90_mail_equi = $row['t90_mail_equi'];
    $t90_telf_equi = $row['t90_telf_equi'];
    $t90_unid_fe = $row['t90_unid_fe'];
    $t90_carg_equi = $row['t90_carg_equi'];
    $t90_func_equi = $row['t90_func_equi'];
    $t90_direccion = $row['t90_direccion'];
    $t90_estado = $row['t90_estado'];
    $usr_crea = $row['usr_crea'];
    $fch_crea = $row['fch_crea'];
    $est_audi = $row['est_audi'];
    
    $objEqui = NULL;
} else {
    $objFunc->SetSubTitle("Contactos / Nuevo Registro");
}

?>

<?php if($view=='') {?>
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
	<?php } ?>
    
  	<div id="EditForm" style="width: 100%; border: solid 1px #D3D3D3; font-weight: bold;">
						
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
								<td width="0%">&nbsp;</td>
								<td colspan="5">
      <?php
    
if ($id == "") {
        $sURL = "process_equi.php?action=" . md5("ajax_new");
    } else {
        $sURL = "process_equi.php?action=" . md5("ajax_edit");
    }
    ?>
      <input type="hidden" name="txturlsave" id="txturlsave"
									value="<?php echo($sURL); ?>" /> <input type="hidden"
									name="t90_id_equi" id="t90_id_equi"
									value="<?php echo($t90_id_equi); ?>" />
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>DNI</td>
								<td>Sexo</td>
								<td>Edad</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td nowrap="nowrap"><input name="t90_dni_equi" type="text"
									id="t90_dni_equi" value="<?php echo($t90_dni_equi); ?>"
									size="15" maxlength="8" required="required" /></td>
								<td><select name="t90_sexo_equi" id="t90_sexo_equi"
									style="width: 150px;">
										<option value=""></option>
      <?php
    $rs = $OjbTab->Sexo();
    $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $t90_sexo_equi);
    ?>
    </select></td>
								<td><input name="t90_edad_equi" type="text" id="t90_edad_equi"
									value="<?php echo($t90_edad_equi); ?>" size="5" maxlength="2" required="required" /></td>
								<td nowrap="nowrap">&nbsp;</td>
								<td nowrap="nowrap">&nbsp;</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td width="23%" nowrap="nowrap">Apellido Paterno</td>
								<td width="24%">Apellido Materno</td>
								<td width="26%">Nombres</td>
								<td width="8%">&nbsp;</td>
								<td width="19%">&nbsp;</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td nowrap="nowrap"><input name="t90_ape_pat" type="text"
									id="t90_ape_pat" value="<?php echo($t90_ape_pat); ?>" size="25" required="required" /></td>
								<td><input name="t90_ape_mat" type="text" id="t90_ape_mat"
									value="<?php echo($t90_ape_mat); ?>" size="25" required="required" /></td>
								<td><input name="t90_nom_equi" type="text" id="t90_nom_equi"
									value="<?php echo($t90_nom_equi); ?>" size="25" required="required" /></td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td nowrap="nowrap">&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>Unidad</td>
								<td>Cargo</td>
								<td>Email</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td><select name="t90_unid_fe" id="t90_unid_fe"
									style="width: 150px;">
										<option value=""></option>
      <?php
    $rs = $OjbTab->TipoUnidadesFE();
    $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $t90_unid_fe);
    ?>
    </select></td>
								<td><select name="t90_carg_equi" id="t90_carg_equi"
									style="width: 150px;">
										<option value=""></option>
      <?php
    $rs = $OjbTab->TipoCargosFE();
    $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $t90_carg_equi);
    ?>
    </select></td>
								<td><input name="t90_mail_equi" type="text" id="t90_mail_equi"
									value="<?php echo($t90_mail_equi); ?>" size="35" required="required" /></td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>Telefono</td>
								<td>Direccion</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td><input name="t90_telf_equi" type="text" id="t90_telf_equi"
									value="<?php echo($t90_telf_equi); ?>" size="20" /></td>
								<td colspan="4"><input name="t90_direccion" type="text"
									id="t90_direccion" value="<?php echo($t90_direccion); ?>"
									size="60" /></td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>Calificación</td>
								<td>Funciones Principales</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td valign="top">
								    <input name="t90_cali_equi" type="hidden" id="t90_cali_equi" value="<?php echo($t90_cali_equi); ?>" />
								    <select name="lstInstruccion" id="lstInstruccion" onchange="changeListInstruccion()">
								        <option></option>
								        <?php
                                        $rs = $OjbTab->getListNivelInstruccion();
                                        $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $t90_cali_equi);
                                        ?>
								    </select>
								</td>
								<td colspan="4" valign="top"><textarea name="t90_func_equi"
										cols="60" rows="3" id="t90_func_equi"><?php echo($t90_func_equi); ?></textarea></td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td valign="top">&nbsp;</td>
								<td colspan="2" valign="top">&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
						</table>

						<br />
<script type="text/javascript">

function changeListInstruccion()
{
	$('#t90_cali_equi').val($('#lstInstruccion').val());
    
}

$("#t90_dni_equi").numeric().pasteNumeric();
$("#t90_edad_equi").numeric().pasteNumeric();
</script>

					</div>
  <?php if($view=='') {?>
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