<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>

<?php
require_once (constant('PATH_CLASS') . "BLTablasAux.class.php");
require_once (constant('PATH_CLASS') . "BLEquipo.class.php");
require_once (constant('PATH_CLASS') . "HardCode.class.php");
require (constant('PATH_CLASS') . "BLEjecutor.class.php");

$OjbTab = new BLTablasAux();
$HC = new HardCode();
$view = $objFunc->__GET('mode');

$row = 0;
if ($view == md5("ajax_edit")) {
    $objFunc->SetSubTitle("Solicitud de Cambio de Personal / Editar Registro");
    $idProy = $objFunc->__GET('idProy');
    $id = $objFunc->__GET('id');
    $objEqui = new BLEquipo();
    
    $row = $objEqui->CambioPersonal_Seleccionar($idProy, $id);
    
    $t02_cod_proy = $row['t02_cod_proy'];
    
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
    $t04_func_equi = $row['t04_func_equi'];
    $t04_exp_lab = $row['t04_exp_lab'];
    $t04_obs_ejec = $row['t04_obs_ejec'];
    
    $t04_aprob_mt = $row['t04_aprob_mt'];
    $t04_aprob_mf = $row['t04_aprob_mf'];
    $t04_aprob_cmt = $row['t04_aprob_cmt'];
    $t04_aprob_cmf = $row['t04_aprob_cmf'];
    $t04_resp_ejec = $row['t04_resp_ejec'];
    $t04_resp_ejec_obs = $row['t04_resp_ejec_obs'];
    $t04_fec_ini = $row['t04_fec_ini'];
    $usr_crea = $row['usr_crea'];
    $fch_crea = $row['fch_crea'];
    $est_audi = $row['est_audi'];
    
    $t04_num_soli = $row['t04_num_soli'];
    $idEquipoAntes = $row['t04_id_equi_cambio'];
    // $idEquipoAntes = $t04_id_equi_cambioi;
    $rowPer = $objEqui->CambioPersonal_GetPartida($t02_cod_proy, $t04_carg_equi);
    
    $objEqui = NULL;
    
    // Se va a modificar el registro !!
} else {
    $objFunc->SetSubTitle("Solicitud de Cambio de Personal / Nuevo Registro");
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
    $objFunc->SetTitle("Solicitud de Cambio de Personal");
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




		<script src="../../../jquery.ui-1.5.2/jquery.maskedinput.js"
						type="text/javascript"></script>
					<script src="../../../jquery.ui-1.5.2/jquery.numeric.js"
						type="text/javascript"></script>

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
						<h1
							style="color: red; font-weight: bold; font-size: 12px; margin-top: 4px;">&quot;La
							información ingresada tiene carácter de declaración
							jurada&quot;</h1>
						<table width="780" border="0" cellpadding="0" cellspacing="2"
							class="TableEditReg" style="margin-top: -5px;">
							<tr>
								<td width="4">&nbsp;</td>
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
									name="t02_cod_proy" id="t02_cod_proy"
									value="<?php echo($t02_cod_proy); ?>" class="CambioPersonal" />
								</td>
							</tr>
     <?php if($id!="")  {?>
    <tr>
								<td>&nbsp;</td>
								<td width="106" valign="middle">N° Solicitud</td>
								<td colspan="4" valign="middle"><input name="t04_num_soli"
									type="text" id="t04_num_soli"
									value="<?php echo($t04_num_soli); ?>" size="5" maxlength="3"
									readonly="readonly" class="CambioPersonal" />
									&nbsp;&nbsp;&nbsp;<span style="font-weight: normal;"><?php /*if($id!="") {echo("<b>Fecha:</b> ".$row['fch_crea']);}*/?></span>
									&nbsp;&nbsp; &nbsp;</td>
							</tr>
    <?php } ?>		<!--Modificado PS -->
							<tr>
								<td>&nbsp;</td>
								<td nowrap="nowrap">Fecha de Solicitud</td>
								<td><input name="t04_fec_ini" type="text" id="t04_fec_ini"
									value="<?php if($id!=''){echo($row['t04_fec_ini']);}else {echo(date('d/m/Y'));} ?>"
									size="10" readonly="readonly" class="CambioPersonal" /></td>
								<td colspan="4">&nbsp;</td>
							</tr>
							<!--Final de la Modificación-->
							<tr>
								<td>&nbsp;</td>
								<td nowrap="nowrap">Partida a Afectar</td>
								<td width="262"><select name="cboPartida" id="cboPartida"
									style="width: 250px;" onchange="CargarPartida();"
									class="CambioPersonal">
										<option value=""></option>
        <?php
        require (constant('PATH_CLASS') . "BLManejoProy.class.php");
        $objMP = new BLManejoProy();
        $rs = $objMP->Personal_Listado($t02_cod_proy, 1);
        $objFunc->llenarComboI($rs, 'codigo', 'cargo', $t04_carg_equi);
        ?>
      </select></td>
								<td width="165">Saldo de la Partida a Afectar</td>
								<td width="208"><input name="txtsaldopartida" type="text"
									class="CambioPersonal" id="txtsaldopartida"
									value="<?php echo(number_format($rowPer['saldo_partida'],2,'.','')); ?>"
									size="20" readonly="readonly" /> <input type="hidden"
									name="txtidEquipoAntes" id="txtidEquipoAntes"
									value="<?php echo($idEquipoAntes); ?>" class="CambioPersonal" /></td>
								<td width="21" align="right">&nbsp;</td>
							</tr>
							<tr>
								<td height="28">&nbsp;</td>
								<td nowrap="nowrap">Nombre Personal</td>
								<td colspan="3"><input name="txtnompersonal_antes" type="text"
									disabled="disabled" class="CambioPersonal"
									id="txtnompersonal_antes"
									value="<?php echo($rowPer['nom_equi']); ?>" size="60"
									readonly="readonly" /> &nbsp;&nbsp; Remuneración : <input
									name="txtremuneracion" type="text" disabled="disabled"
									class="CambioPersonal" id="txtremuneracion"
									value="<?php echo($rowPer['remuneracion']); ?>" size="20" /></td>
								<td align="right">&nbsp;</td>
							</tr>
							<tr>
								<td height="28">&nbsp;</td>
								<td nowrap="nowrap">Adjuntar Solicitud</td>
								<td colspan="3">      

       <?php
    if ($row['t04_adj_sol'] != "") {
        $filename = constant("DOCS_PATH") . $HC->FolderUploadSolicitudCP . $row['t04_adj_sol'];
        
        echo ("<a href=\"#\" onclick=\"window.open('" . $filename . "');\">" . $row['t04_adj_sol'] . "</a><br>");
    }
    ?>
     
      <input name="txtFileUploadSCP" type="file" id="txtFileUploadSCP"
									size="90" />
								</td>
								<td align="right">&nbsp;</td>
							</tr>
							<tr>
								<td height="28">&nbsp;</td>
								<td valign="top">Comentarios del Ejecutor</td>
								<td colspan="3"><textarea name="txtComentarios" cols="100"
										rows="3" id="txtComentarios" class="CambioPersonal"><?php echo($t04_obs_ejec); ?></textarea></td>
								<td align="right">&nbsp;</td>
							</tr>

							<tr>
								<td height="28">&nbsp;</td>
								<td colspan="5">
									<fieldset style="padding: 1px;">
										<legend>Datos del Nuevo Personal</legend>
										<table class="TableEditReg" style="padding: 1px;">
											<tr>
												<td height="28">&nbsp;</td>
												<td>DNI</td>
												<td><input name="t04_dni_equi" type="text" id="t04_dni_equi"
													value="<?php echo($t04_dni_equi); ?>" size="15"
													maxlength="8" class="CambioPersonal" /></td>
												<td>&nbsp;</td>
												<td>&nbsp;</td>
												<td align="right">&nbsp;</td>
												<td>&nbsp;</td>
											</tr>
											<tr>
												<td>&nbsp;</td>
												<td width="10%">Ap. Paterno</td>
												<td width="19%"><input name="t04_ape_pat" type="text"
													id="t04_ape_pat" value="<?php echo($t04_ape_pat); ?>"
													size="20" class="CambioPersonal" /></td>
												<td width="11%" align="right" nowrap="nowrap">Ap. Materno</td>
												<td width="14%"><input name="t04_ape_mat" type="text"
													id="t04_ape_mat" value="<?php echo($t04_ape_mat); ?>"
													size="20" class="CambioPersonal" /></td>
												<td width="8%" align="right">Nombres</td>
												<td width="38%"><input name="t04_nom_equi" type="text"
													id="t04_nom_equi" value="<?php echo($t04_nom_equi); ?>"
													size="30" class="CambioPersonal" /></td>
											</tr>

											<tr>
												<td>&nbsp;</td>
												<td>Sexo</td>
												<td><select name="t04_sexo_equi" id="t04_sexo_equi"
													style="width: 130px;" class="CambioPersonal">
														<option value=""></option>
              <?php
            $rs = $OjbTab->Sexo(12);
            $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $t04_sexo_equi);
            ?>
            </select></td>
												<td align="right">Edad</td>
												<td><input name="t04_edad_equi" type="text"
													id="t04_edad_equi" value="<?php echo($t04_edad_equi); ?>"
													size="20" class="CambioPersonal" /></td>
												<td align="right">Instrucción</td>
												<td><select name="t04_cali_equi" id="t04_cali_equi"
													style="width: 180px;" class="CambioPersonal">
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
												<td><input name="t04_telf_equi" type="text"
													id="t04_telf_equi" value="<?php echo($t04_telf_equi); ?>"
													size="20" class="CambioPersonal" /></td>
												<td align="right">Celular</td>
												<td><input name="t04_cel_equi" type="text" id="t04_cel_equi"
													value="<?php echo($t04_cel_equi); ?>" size="20"
													class="CambioPersonal" /></td>
												<td align="right">Mail</td>
												<td><input name="t04_mail_equi" type="text"
													id="t04_mail_equi" value="<?php echo($t04_mail_equi); ?>"
													size="30" class="CambioPersonal" /></td>
											</tr>
											<tr>
												<td>&nbsp;</td>
												<td>Experiencia</td>
												<td colspan="5"><textarea name="t04_exp_lab" cols="100"
														rows="3" id="t04_exp_lab" class="CambioPersonal"><?php echo($t04_exp_lab); ?></textarea></td>
											</tr>
											<tr>
												<td>&nbsp;</td>
												<td>Función</td>
												<td colspan="5"><textarea name="t04_func_equi" cols="100"
														rows="3" id="t04_func_equi" class="CambioPersonal"><?php echo($t04_func_equi); ?></textarea></td>
											</tr>
											<tr>
												<td>&nbsp;</td>
												<td>Adjuntar CV</td>
												<td colspan="5">
                 <?php
                if ($row['t04_adj_cv'] != "") {
                    $filename = constant("DOCS_PATH") . $HC->FolderUploadSolicitudCP . $row['t04_adj_cv'];
                    
                    echo ("<a href=\"#\" onclick=\"window.open('" . $filename . "');\">" . $row['t04_adj_cv'] . "</a><br>");
                }
                ?>
           <input name="txtFileUploadCV" type="file"
													id="txtFileUploadCV" class="CambioPersonal" size="85" />
												</td>
											</tr>
										</table>
									</fieldset>
								</td>
							</tr>
  <?php if( $ObjSession->PerfilID!=$HC->Ejec ) { ?>
  <tr>
								<td height="28">&nbsp;</td>
								<td colspan="5">
									<fieldset style="padding: 1px;">
										<legend>Aprobaciones Acerca de la Solicitud de cambio de
											Personal</legend>
										<table width="100%" border="0" align="center" cellpadding="0"
											class="TableEditReg" style="padding: 1px;">
											<tr>
												<td width="15%">Gestor de Proyectos</td>
												<td width="35%"><input name="chkAprobarMT" type="checkbox"
													id="chkAprobarMT" value="1"
													<?php if($t04_aprob_mt=='1'){echo('checked="checked"');} ?>
													class="CambioPersonal"
													<?php if($ObjSession->PerfilID!=$HC->GP) { echo("disabled"); }?> />
												</td>
												
												<?php /* ?>
												<td width="198">Monitor Financiero</td>
												<td width="110"><input name="chkAprobarMF" type="checkbox"
													id="chkAprobarMF" value="1"
													<?php if($t04_aprob_mf=='1'){echo('checked="checked"');} ?>
													class="CambioPersonal"
													<?php if($ObjSession->PerfilID!=$HC->MF) { echo("disabled"); }?> />
												</td>
												<?php */ ?>
												
												
												<td width="22%">Responsable del Area</td>
												<td width="28%"><input name="chkAprobarCMT" type="checkbox"
													id="chkAprobarCMT" value="1"
													<?php if($t04_aprob_cmt=='1'){echo('checked="checked"');} ?>
													class="CambioPersonal"
													<?php if($ObjSession->PerfilID!=$HC->RA) { echo("disabled"); }?>
													onclick="ValidaAprobacion();" /></td>
													
												
											</tr>
											<?php /* ?>
											<tr>
												<td>Coordinador Monitoreo Técnico</td>
												<td><input name="chkAprobarCMT" type="checkbox"
													id="chkAprobarCMT" value="1"
													<?php if($t04_aprob_cmt=='1'){echo('checked="checked"');} ?>
													class="CambioPersonal"
													<?php if($ObjSession->PerfilID!=$HC->CMT) { echo("disabled"); }?>
													onclick="ValidaAprobacion();" /></td>
												<td>Coordinador Monitoreo Financiero</td>
												<td><input name="chkAprobarCMF" type="checkbox"
													id="chkAprobarCMF" value="1"
													<?php if($t04_aprob_cmf=='1'){echo('checked="checked"');} ?>
													class="CambioPersonal"
													<?php if($ObjSession->PerfilID!=$HC->CMF) { echo("disabled"); }?>
													onclick="ValidaAprobacion();" /></td>
											</tr>
											<?php */ ?>
											<tr>
												<td colspan="4">
													<fieldset style="padding: 1px;"
														<?php if($ObjSession->PerfilID!=$HC->GP && $ObjSession->PerfilID!=$HC->RA ) { echo("disabled"); }?>>
														<legend> Respuesta al Ejecutor</legend>
														<div style="display: table-row">
															<input name="chkAprobar" type="checkbox" id="chkAprobar"
																value="1"
																<?php if($t04_resp_ejec=='1'){echo('checked="checked"');} ?>
																class="CambioPersonal" disabled="disabled" /> <label
																for="chkAprobar">Aprobar</label>
														</div>
														<div style="display: table-row">
															<textarea name="txtrespuesta" cols="110" rows="3"
																id="txtrespuesta" class="CambioPersonal"
																<?php if($ObjSession->PerfilID!=$HC->RA) { echo("disabled"); }?>><?php echo($t04_resp_ejec_obs); ?></textarea>
														</div>
													</fieldset>
												</td>
											</tr>
										</table>
									</fieldset>
								</td>
							</tr>
  <?php } ?>
  
  
  
  <tr>
								<td>&nbsp;</td>
								<td colspan="5">
									<div class="TextDescripcion"
										style="float: left; text-align: left; color: #666;">
										<font style="color: #F00; font-weight: bold;"> A tener en
											consideración</font><br> Solo se pueden subir los archivos
										con extensiones pdf, doc, docx,.<br> El tamaño máximo
										por archivo es de 2MB.
									</div>
								</td>
							</tr>
						</table>

					</div>

					<iframe id="ifrmUploadFile" name="ifrmUploadFile"
						style="display: none;"></iframe>

					<script language="javascript">
 function ValidaAprobacion()
 {
	 
	 if($("#chkAprobarCMT").attr("checked"))  
	 {
		 $("#chkAprobar").attr("checked", "checked");
		 
		 SolitaAprobarProy();
	 }
	 else
	 {
		$("#chkAprobar").removeAttr("checked"); 
	 }
	 
 }

function SolitaAprobarProy()
		{													
			var url = "../planifica/proy_aprueba.php?idProy=<?php echo($row['t02_cod_proy']);?>&scp=1&idscp=<?php echo($row['t04_num_soli']);?>&action=<?php echo(md5('solicita')); ?>";					
			
			loadPopup("Aprobación de solicitud de Cambio de Personal", url);
		
		}
		
/////
   

function btnGuardarMsg(){
	var f = document.getElementById("FormData");
	f.submit() ;
	return false;
}
//// 
 function btnGuardar_Clic()
	{

	if( $('#cboPartida').val()=="" ) {alert("Seleccione Partida a Afectar"); return false;}	
	//if( $('#txtFileUploadSCP').val()=="" ) {alert("Seleccione Archivo de Solicitud en formato Digital"); return false;}	
	
	if( $('#t04_dni_equi').val()=="" ) {alert("Ingrese el DNI"); return false;}		
	
	 var f = document.getElementById("FormData");
	 
	 f.action="process_equi.php?action=<?php if($view==md5("ajax_edit")){echo(md5('edit_solicitud_cp'));} else {echo(md5('new_solicitud_cp'));}?>" ;
	 f.target="ifrmUploadFile" ;
	 f.encoding="multipart/form-data";
	 f.submit() ;
	 f.target='_self';
	return false;
	
	}
	

  function CargarPartida()
	{	
		var proy	=	$("#t02_cod_proy").val();
		var cargo	=	$("#cboPartida").val();
		var pURL = "process_equi.php?idProy="+proy+"&idPersonal="+cargo+"&action=<?php echo(md5("ajax_partida_personal"));?>";
		//loadUrlSpry("divCapa",url);	
		var req = Spry.Utils.loadURL("GET", pURL, true, MySuccessCargarPartida, { headers: {"Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCall});
	} 
	
	function MySuccessCargarPartida(req)
	{
		try
		  {
		  	var respuesta = req.xhRequest.responseText;
			var obj = jQuery.parseJSON(respuesta);
			$("#txtnompersonal_antes").val(obj.nom_equi);
			$("#txtidEquipoAntes").val(obj.id_equi);
			$("#txtsaldopartida").val(obj.saldo_partida);
			$("#txtremuneracion").val(obj.remuneracion);
		  }
		catch(err)
		  {		
			  $("#cboPartida").val("");			  
		  	  $("#txtsaldopartida").val("");
			  $("#txtremuneracion").val("");
			  $("#txtnompersonal_antes").val("");		  
			  
			  alert("No existen datos del personal para el cargo seleccionado");
		  return false;
		  }

	}
	
	
	function ReturnGuardar(exito, msg)
	{
		alert(msg);
		if(exito)
		{ 
			dsLista.loadData();
			btnCancelar_Clic();
		}
	}
	$('#t04_dni_equi').mask('?99999999');
	$('#t04_edad_equi').mask('?99');
	
	
	
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
