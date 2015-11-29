<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>

<?php
require (constant('PATH_CLASS') . "BLTablas.class.php");
require (constant('PATH_CLASS') . "BLTablasAux.class.php");
require (constant('PATH_CLASS') . "BLBene.class.php");
require_once (constant('PATH_CLASS') . "HardCode.class.php");

$objHC = new HardCode();


$OjbTab = new BLTablasAux();
$view = $objFunc->__GET('mode');
$row = 0;
if ($view == md5("ajax_edit")) {
    $objFunc->SetSubTitle("Padrón de Beneficiarios / Editar Registro");
    $idProy = $objFunc->__GET('idProy');
    $id = $objFunc->__GET('id');
    $objBene = new BLBene();
    
    $row = $objBene->BeneSeleccionar($idProy, $id);
    
    $t02_cod_proy = $row['t02_cod_proy'];
    $t11_cod_bene = $row['t11_cod_bene'];
    $t11_dni = $row['t11_dni'];
    $t11_ape_pat = $row['t11_ape_pat'];
    $t11_ape_mat = $row['t11_ape_mat'];
    $t11_nom = $row['t11_nom'];
    $t11_sexo = $row['t11_sexo'];
    $t11_edad = $row['t11_edad'];
    $t11_nivel_educ = $row['t11_nivel_educ'];
    $t11_direccion = $row['t11_direccion'];
    $t11_dpto = $row['t11_dpto'];
    $t11_prov = $row['t11_prov'];
    $t11_dist = $row['t11_dist'];
    $t11_ciudad = $row['t11_ciudad'];
    $t11_case = $row['t11_case'];
    $t11_telefono = $row['t11_telefono'];
    $t11_celular = $row['t11_celular'];
    $t11_mail = $row['t11_mail'];
    $t11_act_princ = $row['t11_act_princ'];
    $t11_estado = $row['t11_estado'];
    $t11_obs = $row['t11_obs'];
    $t11_especialidad = $row['t11_especialidad'];
    $t11_fec_ini = $row['t11_fec_ini'];
    $t11_fec_ter = $row['t11_fec_ter'];
    
    $t11_sec_prod_main = $row['t11_sec_prod_main'];    
    $t11_sec_prod = $row['t11_sec_prod'];
    $t11_subsector = $row['t11_subsector'];
    $t11_nom_prod = $row['t11_nom_prod'];
    
    $t11_unid_prod_1 = $row['t11_unid_prod_1'];
    $t11_nro_up_b = $row['t11_nro_up_b'];
    $t11_sec_prod_2 = $row['t11_sec_prod_2'];
    $t11_tot_unid_prod = $row['t11_tot_unid_prod'];
    $t11_tot_unid_prod_2 = $row['t11_tot_unid_prod_2'];
    
    $t11_sec_prod_main_2 = $row['t11_sec_prod_main_2'];
    $t11_subsec_prod_2 = $row['t11_subsec_prod_2'];
    $t11_unid_prod_2 = $row['t11_unid_prod_2'];
    $t11_nro_up_b_2 = $row['t11_nro_up_b_2'];
    
    $t11_sec_prod_main_3 = $row['t11_sec_prod_main_3'];
    $t11_sec_prod_3 = $row['t11_sec_prod_3'];
    $t11_subsec_prod_3 = $row['t11_subsec_prod_3'];
    $t11_unid_prod_3 = $row['t11_unid_prod_3'];
    $t11_tot_unid_prod_3 = $row['t11_tot_unid_prod_3'];
    $t11_nro_up_b_3 = $row['t11_nro_up_b_3'];
    $usr_crea = $row['usr_crea'];
    $fch_crea = $row['fch_crea'];
    $est_audi = $row['est_audi'];
    $t11_esp_otro = $row['t11_esp_otro'];
    $objBene = NULL;
    
    // Se va a modificar el registro !!
} else {
    $objFunc->SetSubTitle("Padrón de Beneficiarios / Nuevo Registro");
    $t02_cod_proy = $objFunc->__GET('idProy');
}

?>

<?php if($objFunc->__QueryString()=='') { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/tplAjaxForm.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<!-- InstanceBeginEditable name="doctitle" -->
<?php
    $objFunc->SetTitle("Proyectos - Padrón de Beneficiarios");
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
<div id="toolbar" style="height: 4px;" class="BackColor">
						<table width="100%" border="0" cellpadding="0" cellspacing="0">
							<tr>
								<td width="9%"><button class="Button"
										onclick="btnGuardar_Clic(); return false;" type="button" value="Guardar">Guardar
									</button></td>
								<td width="9%"><button class="Button"
										onclick="btnCancelar_Clic(); return false;"  type="button" value="Cancelar">
										Cancelar</button></td>
								<td width="31%">&nbsp;</td>
								<td width="2%">&nbsp;</td>
								<td width="2%">&nbsp;</td>
								<td width="2%">&nbsp;</td>
								<td width="47%" align="right"><?php echo($objFunc->SubTitle) ;?></td>
							</tr>
						</table>
					</div>
					<div id="EditForm"
						style="width: 100%; border: solid 1px #D3D3D3; font-weight: bold;">
						<br />
						<table width="782" border="0" cellpadding="0" cellspacing="2"
							class="TableEditReg">
							<tr>
								<td width="2">&nbsp;</td>
								<td colspan="6">
      <?php
    
if ($id == "") {
        $sURL = "bene_process.php?action=" . md5("ajax_new");
    } else {
        $sURL = "bene_process.php?action=" . md5("ajax_edit");
    }
    ?>
      <input type="hidden" name="txturlsave" id="txturlsave"
									value="<?php echo($sURL); ?>" /> <input type="hidden"
									name="t02_cod_proy" id="t02_cod_proy"
									value="<?php echo($t02_cod_proy); ?>" /> <input type="hidden"
									name="t11_cod_bene" id="t11_cod_bene"
									value="<?php echo($t11_cod_bene); ?>" />
								</td>
							</tr>
							<tr height="10">
								<td>&nbsp;</td>
								<td width="76" align="left">DNI</td>
								<td width="130"><input name="t11_dni" type="text" id="t11_dni"
									value="<?php echo($t11_dni); ?>" size="15" maxlength="8" style="width: 130px;"
									<?php if($id!=""){echo('readonly');}?> /></td>
								<td width="127">&nbsp;</td>
								<td width="120">&nbsp;</td>
								<td width="120" align="right">Estado</td>
								<td width="180"><select name="t11_estado" id="t11_estado"
									style="width: 100px;">
										<option value=""></option>
      <?php
    $rs = $OjbTab->EstadoParti(14);
    $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $t11_estado);
    ?>
    </select></td>
							</tr>
							<tr height="10">
								<td>&nbsp;</td>
								<td align="left">Apellido Paterno</td>
								<td><input name="t11_ape_pat" type="text" id="t11_ape_pat"
									value="<?php echo($t11_ape_pat); ?>" size="20" /></td>
								<td align="right">Apellido Materno</td>
								<td><input name="t11_ape_mat" type="text" id="t11_ape_mat"
									value="<?php echo($t11_ape_mat); ?>" size="20" /></td>
								<td align="right">Nombres</td>
								<td><input name="t11_nom" type="text" id="t11_nom"
									value="<?php echo($t11_nom); ?>" size="20" /></td>
							</tr>

							<tr height="10">
								<td>&nbsp;</td>
								<td align="left">Sexo</td>
								<td><select name="t11_sexo" id="t11_sexo" style="width: 130px;">
										<option value=""></option>
      <?php
    $rs = $OjbTab->Sexo(12);
    $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $t11_sexo);
    ?>
    </select></td>
								<td align="right">Edad</td>
								<td><input name="t11_edad" type="text" id="t11_edad"
									value="<?php echo($t11_edad); ?>" size="20" /></td>
								<td align="right">&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr height="10">
								<td>&nbsp;</td>
								<td align="left">&nbsp;</td>
								<td colspan="3">&nbsp;</td>
								<td align="right">&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr height="10">
								<td>&nbsp;</td>
								<td align="left">Telefono</td>
								<td><input name="t11_telefono" type="text" id="t11_telefono"
									value="<?php echo($t11_telefono); ?>" size="20" /></td>
								<td align="right">Celular</td>
								<td><input name="t11_celular" type="text" id="t11_celular"
									value="<?php echo($t11_celular); ?>" size="20" /></td>
								<td align="right">Mail</td>
								<td><input name="t11_mail" type="text" id="t11_mail"
									value="<?php echo($t11_mail); ?>" size="20" /></td>
							</tr>
							<tr height="10">
								<td>&nbsp;</td>
								<td align="left">&nbsp;</td>
								<td>&nbsp;</td>
								<td align="right">&nbsp;</td>
								<td>&nbsp;</td>
								<td align="right">&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr height="10">
								<td height="23">&nbsp;</td>
								<td align="left">Instrucción</td>
								<td><select name="t11_nivel_educ" id="t11_nivel_educ"
									style="width: 130px;">
										<option value=""></option>
      <?php
    $rs = $OjbTab->NivelEdu(13);
    $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $t11_nivel_educ);
    ?>
    </select></td>

								<td align="right">Especialidad</td>
								<td align="left"><select name="t11_especialidad"
									id="t11_especialidad" style="width: 150px;"
									onchange="Otros_Clic();">
										<option value=""></option>
      <?php
    $rs = $OjbTab->EspecialidadPer();
    $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $t11_especialidad);
    ?>
    </select></td>
								<td colspan="2" align="left" <?php if($t11_esp_otro=='') {?>
									style="visibility: hidden;" <?php } ?>><label
									id="msg_especifique">Especifique: </label> <input
									id="t11_especialidad_otros" type="text" size="20"
									name="t11_especialidad_otros"
									value="<?php echo($t11_esp_otro); ?>"></td>
							</tr>
							<tr height="10">
								<td>&nbsp;</td>
								<td align="left">&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr height="10">
								<td>&nbsp;</td>
								<td align="left">Fecha de Inicio</td>
								<td><input name="t11_fec_ini" type="text" id="t11_fec_ini"
									value="<?php echo($t11_fec_ini); ?>" size="20" /></td>
								<td align="right">Fecha de Baja</td>
								<td><input name="t11_fec_ter" type="text" id="t11_fec_ter"
									value="<?php echo($t11_fec_ter); ?>" size="20" /></td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr height="10">
								<td>&nbsp;</td>
								<td align="left">&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr height="10">
								<td>&nbsp;</td>
								<td align="left">Sector<br /> Principal</td>
								<td align="left">Sector<br /> Productivo
								</td>
								<td align="left">Sub Sector</td>
								<td align="left">Unidades de Producción</td>
								<td align="left">Total de Unid. de Producción</td>
								<td>Undiades Prod.<br> con el Proy.</td>
							</tr>
							<tr height="10">
								<td height="26">&nbsp;</td>
								<td align="left">
								    <select name="t11_sec_prod_main" id="t11_sec_prod_main" style="width: 130px;" onchange="LoadSectores();">
										<option value=""></option>
    <?php
    
        $objTablas = new BLTablas();
        $rsSectoresMain = $objTablas->getListaSectoresMain();
        $objFunc->llenarCombo($rsSectoresMain, 'codigo', 'descripcion', $t11_sec_prod_main);
    ?>										
								    </select>
								</td>
								<td>
								    <select name="t11_sec_prod" id="t11_sec_prod" style="width: 130px;" onchange="LoadSubSectores();">
										<option value=""></option>
    <?php
    $rs = $OjbTab->SectoresProductivos($t11_sec_prod_main);
    $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $t11_sec_prod);
    ?>
                                    </select>
                                </td>
								<td align="left"><select name="t11_subsec_prod"
									id="t11_subsec_prod" style="width: 130px" class="Proyectos">
      <?php
    $rsSubSectores = $OjbTab->SubSectoresProductivos($t11_sec_prod);
    $objFunc->llenarCombo($rsSubSectores, 'codigo', 'descripcion', $t11_subsector);
    ?>
    </select></td>
								<td><select name="t11_unid_prod_1" id="t11_unid_prod_1"
									style="width: 150px;">
										<option value=""></option>
      <?php
    $rs = $OjbTab->UnidadesProdBen();
    $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $t11_unid_prod_1);
    ?>
    </select></td>
								<td align="center"><input name="t11_tot_unid_prod" type="text"
									id="t11_tot_unid_prod"
									value="<?php echo($t11_tot_unid_prod); ?>" size="5" /></td>
								<td><input name="t11_nro_up_b" type="text" id="t11_nro_up_b"
									value="<?php echo($t11_nro_up_b); ?>" size="5" /></td>
							</tr>
							<tr height="10">
								<td height="22">&nbsp;</td>
								<td align="left">
								    <select name="t11_sec_prod_main_2" id="t11_sec_prod_main_2" style="width: 130px;" onchange="LoadSectores_2();">
										<option value=""></option>
    <?php
    
        $objTablas = new BLTablas();
        $rsSectoresMain = $objTablas->getListaSectoresMain();
        $objFunc->llenarCombo($rsSectoresMain, 'codigo', 'descripcion', $t11_sec_prod_main_2);
    ?>									
								    </select>
								</td>
								<td>
								    <select name="t11_sec_prod_2" id="t11_sec_prod_2" style="width: 130px;" onchange="LoadSubSectores_2();">
										<option value=""></option>
      <?php
    $rs = $OjbTab->SectoresProductivos($t11_sec_prod_main_2);
    $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $t11_sec_prod_2);
    ?>
                                    </select>
                                </td>
								<td><select name="t11_subsec_prod_2" id="t11_subsec_prod_2"
									style="width: 130px" class="Proyectos">
        <?php
        $rsSubSectores = $OjbTab->SubSectoresProductivos($t11_sec_prod_2);
        $objFunc->llenarCombo($rsSubSectores, 'codigo', 'descripcion', $t11_subsec_prod_2);
        ?>
        </select></td>
								<td><select name="t11_unid_prod_2" id="t11_unid_prod_2"
									style="width: 150px;">
										<option value=""></option>
      <?php
    $rs = $OjbTab->UnidadesProdBen();
    $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $t11_unid_prod_2);
    ?>
      </select></td>
								<td align="center"><input name="t11_tot_unid_prod_2" type="text"
									id="t11_tot_unid_prod_2"
									value="<?php echo($t11_tot_unid_prod_2); ?>" size="5" /></td>
								<td><input name="t11_nro_up_b_2" type="text" id="t11_nro_up_b_2"
									value="<?php echo($t11_nro_up_b_2); ?>" size="5" /></td>
							</tr>
							<tr height="10">
								<td>&nbsp;</td>
								<td align="left">
								    <select name="t11_sec_prod_main_3" id="t11_sec_prod_main_3" style="width: 130px;" onchange="LoadSectores_3();">
										<option value=""></option>
    <?php
    
        $objTablas = new BLTablas();
        $rsSectoresMain = $objTablas->getListaSectoresMain();
        $objFunc->llenarCombo($rsSectoresMain, 'codigo', 'descripcion', $t11_sec_prod_main_3);
    ?>											
								    </select>
								</td>
								<td>
								    <select name="t11_sec_prod_3" id="t11_sec_prod_3" style="width: 130px;" onchange="LoadSubSectores_3();">
										<option value=""></option>
      <?php
    $rs = $OjbTab->SectoresProductivos($t11_sec_prod_main_3);
    $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $t11_sec_prod_3);
    ?>
                                    </select>
                                </td>
								<td><select name="t11_subsec_prod_3" id="t11_subsec_prod_3"
									style="width: 130px" class="Proyectos">
      <?php
    $rsSubSectores = $OjbTab->SubSectoresProductivos($t11_sec_prod_3);
    $objFunc->llenarCombo($rsSubSectores, 'codigo', 'descripcion', $t11_subsec_prod_3);
    ?>
    </select></td>
								<td><select name="t11_unid_prod_3" id="t11_unid_prod_3"
									style="width: 150px;">
										<option value=""></option>
      <?php
    $rs = $OjbTab->UnidadesProdBen();
    $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $t11_unid_prod_3);
    ?>
    </select></td>
								<td align="center"><input name="t11_tot_unid_prod_3" type="text"
									id="t11_tot_unid_prod_3"
									value="<?php echo($t11_tot_unid_prod_3); ?>" size="5" /></td>
								<td><input name="t11_nro_up_b_3" type="text" id="t11_nro_up_b_3"
									value="<?php echo($t11_nro_up_b_3); ?>" size="5" /></td>
							</tr>
							<tr height="10">
								<td>&nbsp;</td>
								<td align="left">&nbsp;</td>
								<td colspan="3">&nbsp;</td>
								<td align="center">&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr height="10">
								<td height="25">&nbsp;</td>
								<td align="left" nowrap="nowrap">&nbsp;</td>
								<td align="center" nowrap="nowrap">Departamento</td>
								<td align="center" nowrap="nowrap">Provincia</td>
								<td align="center">Distrito</td>
								<td align="center">Centro Poblado</td>
								<td>&nbsp;</td>
							</tr>
							<tr>

								<td>&nbsp;</td>
								<td align="left"><input type="hidden" name="t03_prov"
									id="t03_prov" value="<?php echo($row['t02_subsec']);?>" /> <input
									type="hidden" name="t03_dpto" id="t03_dpto"
									value="<?php echo($row['t02_sector']);?>" /></td>
								<td><select name="cbodpto" id="cbodpto" style="width: 120px;"
									onchange="LoadProv();">
										<option value="" selected="selected"></option>
          <?php
        $objTablas = new BLTablasAux();
        $rsDpto = $objTablas->ListaDepartamentos();
        $objFunc->llenarComboI($rsDpto, 'codigo', 'descripcion', $t11_dpto);
        ?>
        </select></td>
								<td><select name="cboprov" id="cboprov" style="width: 120px;"
									onchange="LoadDist();">
										<option value="" selected="selected"></option>
          <?php
        $rsDpto = $objTablas->ListaProvincias($t11_dpto);
        $objFunc->llenarComboI($rsDpto, 'codigo', 'descripcion', $t11_prov);
        ?>
        </select></td>
								<td><select name="cbodist" id="cbodist" style="width: 120px;"
									onchange="LoadCase();">
										<option value="" selected="selected"></option>
            <?php
            $rsDpto = $objTablas->ListaDistritos($t11_dpto, $t11_prov);
            $objFunc->llenarComboI($rsDpto, 'codigo', 'descripcion', $t11_dist);
            ?>
        </select></td>
								<td><select name="cbocase" id="cbocase" style="width: 120px;">
										<option value="" selected="selected">(OTROS)</option>
            <?php
            $rsDpto = $objTablas->ListaCaserios($t11_dpto, $t11_prov, $t11_dist);
            $objFunc->llenarComboI($rsDpto, 'codigo', 'descripcion', $t11_case);
            ?>
        </select></td>
								<td>
								<?php if($ObjSession->PerfilID==$objHC->SP || $ObjSession->PerfilID==$objHC->FE ) { ?>
									<a href="javascript:"> <img src="../../../img/nuevo.gif"
										alt="" width="16" height="16" border="0" title="Nuevo Centro Poblado"
										onclick="btnNuevo_Caserio(); return false;" /></a>
								<?php } ?>
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td align="left">&nbsp;</td>
								<td colspan="3">&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td align="left">Dirección</td>
								<td colspan="3"><input name="t11_direccion" type="text"
									id="t11_direccion" value="<?php echo($t11_direccion); ?>"
									size="60" /></td>
								<td align="center">Ciudad</td>
								<td><input name="t11_ciudad" type="text" id="t11_ciudad"
									value="<?php echo($t11_ciudad); ?>" size="20" /></td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td align="left">&nbsp;</td>
								<td colspan="3">&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td align="left">Actividad principal</td>
								<td colspan="3"><input name="t11_act_princ" type="text"
									id="t11_act_princ" value="<?php echo($t11_act_princ); ?>"
									size="50" /></td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td align="left">Obs.</td>
								<td colspan="5"><textarea name="t11_obs" cols="100" rows="4"
										id="t11_obs"><?php echo($t11_obs); ?></textarea></td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td colspan="5">&nbsp;</td>
							</tr>
						</table>

						<br />
					</div>
					<script type="text/javascript">
	$("#t11_dni").numeric().pasteNumeric();
</script>

					<script language="javascript" type="text/javascript">
 
function LoadProv()
{
	var BodyForm = "dpto=" + $('#cbodpto').val();
	var sURL = "amb_geo_process.php?action=<?php echo(md5("lista_provincias"))?>" ;
	$('#cboprov').html('<option> Cargando ... </option>');
	$('#cbodist').html('');
	var req = Spry.Utils.loadURL("POST", sURL, true, ProvSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
}
function ProvSuccessCallback(req)
{
  var respuesta = req.xhRequest.responseText;
  $('#cboprov').html(respuesta);
  $('#cboprov').focus();
}
function LoadDist()
{
	var BodyForm = "dpto=" + $('#cbodpto').val() + "&prov=" + $('#cboprov').val() ;
	var sURL = "amb_geo_process.php?action=<?php echo(md5("lista_distritos"))?>" ;
	$('#cbodist').html('<option> Cargando ... </option>');
	var req = Spry.Utils.loadURL("POST", sURL, true, DistSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
}
function DistSuccessCallback(req)
{
  var respuesta = req.xhRequest.responseText;
  $('#cbodist').html(respuesta);
  $('#cbodist').focus();
}
function LoadCase(valor)
{
	var BodyForm = "dpto=" + $('#cbodpto').val() + "&prov=" + $('#cboprov').val()+ "&dist=" + $('#cbodist').val() + "&case=" + valor;
	var sURL = "amb_geo_process.php?action=<?php echo(md5("lista_caserios"))?>" ;
	$('#cbocase').html('<option> Cargando ... </option>');
	var req = Spry.Utils.loadURL("POST", sURL, true, CaseSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
}
function CaseSuccessCallback(req)
{
  var respuesta = req.xhRequest.responseText;
  $('#cbocase').html(respuesta);
  $('#cbocase').focus();
}

 function btnCancel_Clic()
	  {
	  spryPopupDialogEditReg.displayPopupDialog(false);
	  return true;
	  }

 
 function LoadSectores()
 {
 	var BodyForm = "sector=" + $('#t11_sec_prod_main').val();
 	var sURL = "sect_prod_process.php?action=<?php echo(md5("lista_sector_main"))?>" ;
 	$('#t11_sec_prod').html('<option> Cargando ... </option>');
 	$('#t11_subsec_prod').html('<option></option>');
 	var req = Spry.Utils.loadURL("POST", sURL, true, function(req){

 		var respuesta = req.xhRequest.responseText;
 		  $('#t11_sec_prod').html(respuesta);
 		  $('#t11_sec_prod').focus();


 		}, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
 	
 }


function  LoadSectores_2()
{
    var BodyForm = "sector=" + $('#t11_sec_prod_main_2').val();
    var sURL = "sect_prod_process.php?action=<?php echo(md5("lista_sector_main"))?>" ;
    $('#t11_sec_prod_2').html('<option> Cargando ... </option>');
    $('#t11_subsec_prod_2').html('<option></option>');
    var req = Spry.Utils.loadURL("POST", sURL, true, function(req){
    
    	var respuesta = req.xhRequest.responseText;
    	  $('#t11_sec_prod_2').html(respuesta);
    	  $('#t11_sec_prod_2').focus();
    
    
    	}, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
	 
 }

function  LoadSectores_3()
{
    var BodyForm = "sector=" + $('#t11_sec_prod_main_3').val();
    var sURL = "sect_prod_process.php?action=<?php echo(md5("lista_sector_main"))?>" ;
    $('#t11_sec_prod_3').html('<option> Cargando ... </option>');
    $('#t11_subsec_prod_3').html('<option></option>');
    var req = Spry.Utils.loadURL("POST", sURL, true, function(req){
    
    	var respuesta = req.xhRequest.responseText;
    	  $('#t11_sec_prod_3').html(respuesta);
    	  $('#t11_sec_prod_3').focus();
    
    
    	}, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
		
	 
 }
 
 
 function LoadSubSectores()
	{
		var BodyForm = "sector=" + $('#t11_sec_prod').val();
		var sURL = "sect_prod_process.php?action=<?php echo(md5("lista_subsector"))?>" ;
		$('#t11_subsec_prod').html('<option> Cargando ... </option>');
		var req = Spry.Utils.loadURL("POST", sURL, true, SubSectorSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
	}
	function SubSectorSuccessCallback(req)
	{
	  var respuesta = req.xhRequest.responseText;
	  $('#t11_subsec_prod').html(respuesta);
	  $('#t11_subsec_prod').focus();
	}

 function LoadSubSectores_2()
	{
		var BodyForm = "sector=" + $('#t11_sec_prod_2').val();
		var sURL = "sect_prod_process.php?action=<?php echo(md5("lista_subsector"))?>" ;
		$('#t11_subsec_prod_2').html('<option> Cargando ... </option>');
		var req = Spry.Utils.loadURL("POST", sURL, true, SubSector2SuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
	}
	function SubSector2SuccessCallback(req)
	{
	  var respuesta = req.xhRequest.responseText;
	  $('#t11_subsec_prod_2').html(respuesta);
	  $('#t11_subsec_prod_2').focus();
	}
 function LoadSubSectores_3()
	{
		var BodyForm = "sector=" + $('#t11_sec_prod_3').val();
		var sURL = "sect_prod_process.php?action=<?php echo(md5("lista_subsector"))?>" ;
		$('#t11_subsec_prod_3').html('<option> Cargando ... </option>');
		var req = Spry.Utils.loadURL("POST", sURL, true, SubSector3SuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
	}
	function SubSector3SuccessCallback(req)
	{
	  var respuesta = req.xhRequest.responseText;
	  $('#t11_subsec_prod_3').html(respuesta);
	  $('#t11_subsec_prod_3').focus();
	}
  function Otros_Clic(){
	if( $('#t11_especialidad').val()=="255" )
	{
	$('#t11_especialidad_otros').css("visibility", "visible");
	$('#msg_especifique').css("visibility", "visible");
	
	}
	else
	{
	$('#t11_especialidad_otros').css("visibility", "hidden");
	$('#msg_especifique').css("visibility", "hidden");

	}
  }	
	
  </script>
<?php if($objFunc->__QueryString()=='') { ?>
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