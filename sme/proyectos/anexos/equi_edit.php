<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>

<?php
require (constant('PATH_CLASS') . "BLTablasAux.class.php");
require (constant('PATH_CLASS') . "BLEquipo.class.php");

$OjbTab = new BLTablasAux();
$view = $objFunc->__GET('mode');
$row = 0;
if ($view == md5("ajax_edit")) {
    
    $objFunc->SetSubTitle("Equipo / Editar Registro");
    $idProy = $objFunc->__GET('idProy');
    $id = $objFunc->__GET('id');
    $objEqui = new BLEquipo();
    
    $row = $objEqui->ContactosSeleccionar($idProy, $id);
    
    $t02_cod_proy = $row['t02_cod_proy'];
    $t04_id_equi = $row['t04_id_equi'];
    $t04_dni_equi = $row['t04_dni_equi'];
    $t04_ape_pat = $row['t04_ape_pat'];
    $t04_ape_mat = $row['t04_ape_mat'];
    $t04_nom_equi = $row['t04_nom_equi'];
    $t04_sexo_equi = $row['t04_sexo_equi'];
    $t04_edad_equi = $row['t04_edad_equi'];
    $t04_cali_equi = $row['t04_cali_equi'];
    $t04_telf_equi = $row['t04_telf_equi'];
    $t04_mail_equi = $row['t04_mail_equi'];
    $t04_cel_equi = $row['t04_cel_equi'];
    $t04_carg_equi = $row['t04_carg_equi'];
    $t04_esp_equi = $row['t04_esp_equi'];
    $t04_fec_ini = $row['t04_fec_ini'];
    $t04_fec_ter = $row['t04_fec_ter'];
    $t04_func_equi = $row['t04_func_equi'];
    $t04_exp_lab = $row['t04_exp_lab'];
    $t04_estado = $row['t04_estado'];
    $usr_crea = $row['usr_crea'];
    $fch_crea = $row['fch_crea'];
    $est_audi = $row['est_audi'];
    $t04_esp_otro = $row['t04_esp_otro'];
    $objEqui = NULL;
    // Se va a modificar el registro !!
} else {
    $objFunc->SetSubTitle("Contactos / Nuevo Registro");
    $t02_cod_proy = $objFunc->__GET('idProy');
}

?>

<?php if($view=='') { ?>
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
<link href="../../../css/template.css" rel="stylesheet" media="all" />
<SCRIPT src="../../../jquery.ui-1.5.2/jquery-1.2.6.js"
	type=text/javascript></SCRIPT>
<script src="../../../jquery.ui-1.5.2/ui/ui.datepicker.js"
	type="text/javascript"></script>
<link href="../../../jquery.ui-1.5.2/themes/ui.datepicker.css"
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
<script src="../../../jquery.ui-1.5.2/ui/ui.datepicker.js"
						type="text/javascript"></script>
					<link href="../../../jquery.ui-1.5.2/themes/ui.datepicker.css"
						rel="stylesheet" type="text/css" />
					<script src="../../../jquery.ui-1.5.2/jquery.maskedinput.js"
						type="text/javascript"></script>
					<script src="../../../jquery.ui-1.5.2/jquery.numeric.js"
						type="text/javascript"></script>
					<script language="javascript">   
   	jQuery("#t04_fec_ini").datepicker();	
	jQuery("#t04_fec_ter").datepicker();
</script>

					<div id="EditForm" class="grid-width edit-form">
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
								<td width="0%">&nbsp;</td>
								<td colspan="6">
      <?php
    
if ($id == "") {
        $sURL = "process_equi.php?action=" . md5("ajax_new");
    } else {
        $sURL = "process_equi.php?action=" . md5("ajax_edit");
    }
    ?>
      <input type="hidden" name="txturlsave" id="txturlsave"
									value="<?php echo($sURL); ?>" /> <input type="hidden"
									name="t02_cod_proy" id="t02_cod_proy"
									value="<?php echo($t02_cod_proy); ?>" /> <input type="hidden"
									name="t04_id_equi" id="t04_id_equi"
									value="<?php echo($t04_id_equi); ?>" />
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>DNI</td>
								<td><input name="t04_dni_equi" type="text" id="t04_dni_equi"
									value="<?php echo($t04_dni_equi); ?>" size="15" maxlength="8" /></td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td align="right">Estado</td>
								<td><select name="t04_estado" id="t04_estado"
									style="width: 180px;">
      <?php
    $rs = $OjbTab->EstadoParti(14);
    $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $t04_estado);
    ?>
    </select></td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td width="12%">Ap. Paterno</td>
								<td width="17%"><input name="t04_ape_pat" type="text"
									id="t04_ape_pat" value="<?php echo($t04_ape_pat); ?>" size="20" /></td>
								<td width="14%" align="right" nowrap="nowrap">Ap. Materno</td>
								<td width="21%"><input name="t04_ape_mat" type="text"
									id="t04_ape_mat" value="<?php echo($t04_ape_mat); ?>" size="20" /></td>
								<td width="10%" align="right">Nombres</td>
								<td width="26%"><input name="t04_nom_equi" type="text"
									id="t04_nom_equi" value="<?php echo($t04_nom_equi); ?>"
									size="30" /></td>
							</tr>

							<tr>
								<td>&nbsp;</td>
								<td>Sexo</td>
								<td><select name="t04_sexo_equi" id="t04_sexo_equi"
									style="width: 130px;">
										<option value=""></option>
      <?php
    $rs = $OjbTab->Sexo(12);
    $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $t04_sexo_equi);
    ?>
    </select></td>
								<td align="right">Edad</td>
								<td><input name="t04_edad_equi" type="text" id="t04_edad_equi"
									value="<?php echo($t04_edad_equi); ?>" size="20" /></td>
								<td align="right">Instrucción</td>
								<td><select name="t04_cali_equi" id="t04_cali_equi"
									style="width: 180px;">
										<option value=""></option>
      <?php
    $rs = $OjbTab->NivelEdu(13);
    $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $t04_cali_equi);
    ?>
    </select></td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>Telefono</td>
								<td><input name="t04_telf_equi" type="text" id="t04_telf_equi"
									value="<?php echo($t04_telf_equi); ?>" size="20" /></td>
								<td align="right">Celular</td>
								<td><input name="t04_cel_equi" type="text" id="t04_cel_equi"
									value="<?php echo($t04_cel_equi); ?>" size="20" /></td>
								<td align="right">Mail</td>
								<td><input name="t04_mail_equi" type="text" id="t04_mail_equi"
									value="<?php echo($t04_mail_equi); ?>" size="30" /></td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>Cargo</td>
								<td colspan="3"><select name="t04_carg_equi" id="t04_carg_equi"
									style="width: 250px;" onchange="mostrarDatosRet();">
										<option value=""></option>
      <?php
    require (constant('PATH_CLASS') . "BLManejoProy.class.php");
    $objMP = new BLManejoProy();
    $rs = $objMP->Personal_Listado($t02_cod_proy, 1);
    $objFunc->llenarComboI($rs, 'codigo', 'cargo', $t04_carg_equi);
    ?>
    </select></td>
								<td align="right">&nbsp;</td>
								<td>&nbsp;</td>
								<td width="0%">&nbsp;</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>Especialidad:</td>
								<td><select name="t04_especialidad" id="t04_especialidad"
									style="width: 180px;" onchange="Otros_Clic();">
										<option value=""></option>
      <?php
    $rs = $OjbTab->EspecialidadPer();
    $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $t04_esp_equi);
    ?>
    </select></td>
								<td colspan="3" align="left" <?php if($t04_esp_otro=='') {?>
									style="visibility: hidden;" <?php } ?>><label
									id="msg_especifique">Especifique: </label> <input
									id="t04_especialidad_otros" type="text" size="20"
									name="t04_especialidad_otros"
									value="<?php echo($t04_esp_otro); ?>"></td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td colspan="8">
									<div id="divCapa"></div>

								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>Fecha de Inicio</td>
								<td><input name="t04_fec_ini" type="text" id="t04_fec_ini"
									value="<?php echo($t04_fec_ini); ?>" size="20" /></td>
								<td align="right">Fecha de Término</td>
								<td><input name="t04_fec_ter" type="text" id="t04_fec_ter"
									value="<?php echo($t04_fec_ter); ?>" size="20" /></td>
								</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td colspan="5">&nbsp;</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>Experiencia</td>
								<td colspan="5"><textarea name="t04_exp_lab" cols="100" rows="4"
										id="t04_exp_lab"><?php echo($t04_exp_lab); ?></textarea></td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>Función</td>
								<td colspan="5"><textarea name="t04_func_equi" cols="100"
										rows="4" id="t04_func_equi"><?php echo($t04_func_equi); ?></textarea></td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td colspan="5">&nbsp;</td>
							</tr>
						</table>

						<br />
					</div>

					<script language="javascript">
  function mostrarDatosRet(proy,cargo)
	{	
	if(proy==null & cargo==null){		
		var proy	=	$("#t02_cod_proy").attr("value");
		var cargo	=	$("#t04_carg_equi").attr("value");		
			}
		
		var url = "ejec_resp_ret.php?proy="+proy+"&cargo="+cargo+"&action=<?php echo(md5("ajax_datos_ret"));?>";
		loadUrlSpry("divCapa",url);	
	}
  function Otros_Clic(){
	if( $('#t04_especialidad').val()=="255" )
	{
	$('#t04_especialidad_otros').css("visibility", "visible");
	$('#msg_especifique').css("visibility", "visible");
	
	}
	else
	{
	$('#t04_especialidad_otros').css("visibility", "hidden");
	$('#msg_especifique').css("visibility", "hidden");

	}
  }
  
	<?php
	
	if($view == md5("ajax_edit")){
		?>
			mostrarDatosRet('<?php echo($t02_cod_proy); ?>','<?php echo($t04_carg_equi); ?>');
		<?php
		}
	
	?>
	$('#t04_dni_equi').mask('?99999999');
	$('#t04_edad_equi').mask('?999');

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
