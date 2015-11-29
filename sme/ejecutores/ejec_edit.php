<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauserxml.inc.php"); ?>
<?php

require_once (constant('PATH_CLASS') . "BLTablasAux.class.php");
require_once (constant('PATH_CLASS') . "BLEjecutor.class.php");
require_once (constant('PATH_CLASS') . "HardCode.class.php");

$action = $objFunc->__Request('action');

$OjbTab = new BLTablasAux();
$HC = new HardCode();

$view = $objFunc->__GET('mode');
$accion = $objFunc->__GET('accion');
$id = $objFunc->__GET('id');

$objEjec = new BLEjecutor();
$row = 0;

if ($view == md5("ajax_edit")) {
    if ($accion == md5("editar")) {
        $objFunc->SetSubTitle("Instituciones - Editar Registro");
    } else {
        $objFunc->SetSubTitle("Instituciones - Ver Registro");
    }

    $row = $objEjec->EjecutorSeleccionar($id);

    $t01_id_inst = $row['t01_id_inst'];
    $t01_ruc_inst = $row['t01_ruc_inst'];
    $t01_sig_inst = $row['t01_sig_inst'];
    $t01_nom_inst = $row['t01_nom_inst'];
    $t01_fch_fund = $row['t01_fch_fund'];
    $t01_pres_anio = $row['t01_pres_anio'];
    $t01_dire_inst = $row['t01_dire_inst'];
    $t01_ciud_inst = $row['t01_ciud_inst'];
    $t01_fono_inst = $row['t01_fono_inst'];
    $t01_fax_inst = $row['t01_fax_inst'];
    $t01_mail_inst = $row['t01_mail_inst'];
    $t01_web_inst = $row['t01_web_inst'];
    $t01_ape_rep = $row['t01_ape_rep'];
    $t01_nom_rep = $row['t01_nom_rep'];
    $t01_carg_rep = $row['t01_carg_rep'];
    $usr_crea = $row['usr_crea'];
    $fch_crea = $row['fch_crea'];
    $usr_actu = $row['usr_actu'];
    $fch_actu = $row['fch_actu'];
    $est_audi = $row['est_audi'];
    $t01_tipo_inst = $row['t01_tipo_inst'];
    $t01_fon2_inst = $row['t01_fono2_inst'];
    $t01_rpm_inst = $row['t01_rpm_inst'];
    $t01_rpc_inst = $row['t01_rpc_inst'];
    $t01_nex_inst = $row['t01_next_inst'];

    $dpto = $row['t01_dpto'];
    $prov = $row['t01_prov'];
    $dist = $row['t01_dist'];
    $esta = $row['est_audi'];

    // Se va a modificar el registro !!
} else {
    $objFunc->SetSubTitle("Instituciones - Nuevo Registro");
}

if ($OjbTab == NULL) {
    ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/tplAjaxForm.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<!-- InstanceBeginEditable name="doctitle" -->

<?php
    $objFunc->SetTitle("Instituciones");

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

				<!-- InstanceEndEditable -->
				<div id="divContent">
					<!-- InstanceBeginEditable name="Contenidos" -->
 <?php
}
?>
<SCRIPT src="../../js/utils.js" type=text/javascript></SCRIPT>
<script src="../../jquery.ui-1.5.2/ui/ui.datepicker.js" type="text/javascript"></script>
<link href="../../jquery.ui-1.5.2/themes/ui.datepicker.css" rel="stylesheet" type="text/css" />
<script src="../../jquery.ui-1.5.2/jquery.maskedinput.js" type="text/javascript"></script>
<script src="../../jquery.ui-1.5.2/jquery.numeric.js" type="text/javascript"></script>
<script src="../../js/commons.js" type="text/javascript"></script>

					<div id="toolbar" style="height: 4px;" class="BackColor">
						<table width="782" border="0" cellpadding="0" cellspacing="0">
							<tr>
								<td width="9%"><button id="btnGuardar" class="Button"
										onclick="btnGuardar_Clic(); return false;" value="Guardar">Guardar
									</button></td>
								<td width="24%"><button class="Button"
										onclick="btnCancelar_Clic(); return false;" value="Cancelar"
										style="white-space: nowrap;">Cerrar y Volver</button></td>
								<td width="16%">&nbsp;</td>
								<td width="2%">&nbsp;</td>
								<td width="2%" align="left">&nbsp;</td>
								<td width="47%" align="right"><?php echo($objFunc->SubTitle) ;?></td>
							</tr>
						</table>
					</div>
					<table width="780" border="0" cellspacing="2" cellpadding="0"
						class="TableEditReg">
						<tr>
							<td>
								<fieldset style="width: 700px;">
									<legend>Datos Generales</legend>
									<table border="0" cellpadding="0" cellspacing="2"
										style="padding: 1px; width: 700px;">
										<tr>
											<td width="15%" height="22"><strong>Siglas</strong></td>
											<td colspan="3" nowrap="nowrap"><strong>Nombre de la
													Institución </strong></td>
											<td width="38%"><strong>RUC</strong></td>
										</tr>
										<tr>
											<td><input name="t01_sig_inst" type="text" class="DirecInst"
												id="t01_sig_inst" style="text-transform: uppercase;"
												value="<?php echo($t01_sig_inst); ?>" maxlength="30" /></td>
											<td colspan="3"><input name="t01_nom_inst" type="text"
												class="DirecInst" id="t01_nom_inst"
												value="<?php echo($t01_nom_inst); ?>" size="66"
												maxlength="200" /></td>
											<td><input name="t01_ruc_inst" type="text" id="t01_ruc_inst"
												maxlength="11" value="<?php echo($t01_ruc_inst); ?>"
												class="DirecInst" /></td>
										</tr>
										<tr>
											<td height="25"><strong>Fec.Fundaci&ograve;n</strong></td>
											<td width="17%"><strong>Presup. Anual</strong></td>
											<td width="1%">&nbsp;</td>
											<td width="29%"><strong>Tipo Institución</strong></td>
											<td><input type="hidden" name="t01_id_inst" id="t01_id_inst"
												value="<?php echo($t01_id_inst); ?>" /></td>
										</tr>
										<tr>
											<td><input name="t01_fch_fund" type="text" id="t01_fch_fund"
												size="14" value="<?php echo($t01_fch_fund); ?>"
												style="text-align: center" class="DirecInst" /></td>
											<td><input name="t01_pres_anio" type="text"
												id="t01_pres_anio" size="14"
												value="<?php echo($t01_pres_anio); ?>"
												style="text-align: right;" class="DirecInst" /></td>
											<td>&nbsp;</td>
											<td colspan="2"><select name="t01_tipo_inst"
												id="t01_tipo_inst" style="width: 300px" class="DirecInst">
													<option value=""></option>
	      <?php
    $rs = $OjbTab->TipoUnidades();
    $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $t01_tipo_inst);
    ?>
	      </select></td>
										</tr>
									</table>
								</fieldset>
								<fieldset style="width: 700px;">
									<legend>Relación de Institución con Fondoempleo</legend>
            <?php
            $irsTipo = $objEjec->TipoIntsRelFE($id);
            $numcorte = 4;
            $cont = 0;
            ?>
            <table width="700" border="0" cellspacing="0"
										cellpadding="0" style="padding: 1px;">
										<tr>
              	<?php

while ($rtip = mysqli_fetch_assoc($irsTipo)) {

                if ($cont == $numcorte) {
                    echo ("</tr><tr>");
                    $cont = 0;
                }
                ?>
                <td><input type="checkbox"
												id="chktipoinst_<?php echo($rtip['codi']);?>"
												name="chktipoinst[]" value="<?php echo($rtip['codi']);?>"
												<?php if($rtip['estado']=='1') { echo('checked'); } ?> /> <label
												for="chktipoinst_<?php echo($rtip['codi']);?>"> <?php echo($rtip['descrip']);?> </label>
											</td>
                 <?php  $cont++; } ?>
				<?php if($view == md5("ajax_edit")) { ?>
				 <td><button
													style="background-color: #003366; border: 1px solid #999999; cursor: pointer; font-family: Helvetica, sans-serif, Arial; font-size: 11px; font-weight: bold; padding: 2px 8px; color: #ffffff;"
													id="btnGuardarRelacion"
													onclick="btnGuardarRelacion_Clic(); return false;"
													value="Guardar Relacion">Guardar Relacion</button></td>
				<?php } ?>
			  </tr>
									</table>


									<!-- <table width="700" border="0" cellspacing="0" cellpadding="0">
            <?php for($fila=1;$fila<=$numrows;$fila++) {  ?>
              <tr>
              	<?php for($col=1;$col<=3;$col++) {  ?>
                <td>
                	<?php if($cont <= $irsTipo->num_rows) { $rtip = mysqli_fetch_assoc($irsTipo);  ?>
                	<input type="checkbox" id="chktipoinst" name="chktipoinst" value="<?php echo($rtip['codi']);?>" /> <?php echo($rtip['descrip']);?>
                    <?php } ?>
                 </td>
                <?php $cont++; } ?>
              </tr>
              <?php  $cont++ ; } ?>
            </table>-->

								</fieldset>
								<fieldset style="width: 700px;">
									<legend>Ubicación de la Oficina Principal de la Institución</legend>
									<table width="700" height="192" border="0" cellpadding="0"
										cellspacing="2" style="padding: 1px;">
										<tr>
											<td colspan="6">
												<table width="100%" border="0" cellspacing="0"
													cellpadding="0" style="padding: 0px;">
													<tr>
														<td width="27%" height="21" valign="bottom"><strong>Departamento</strong></td>
														<td width="32%" valign="bottom"><strong>Provincia</strong></td>
														<td width="41%" valign="bottom"><strong>Distrito</strong></td>
													</tr>
													<tr>
														<td><select name="cbodpto" id="cbodpto"
															style="width: 150px;" onchange="LoadProv();"
															class="DirecInst">
																<option value="" selected="selected"></option>
	          <?php
        $objTablas = new BLTablasAux();
        $rsDpto = $objTablas->ListaDepartamentos();
        $objFunc->llenarComboI($rsDpto, 'codigo', 'descripcion', $dpto);
        ?>
	          </select></td>
														<td><select name="cboprov" id="cboprov"
															style="width: 150px;" onchange="LoadDist();"
															class="DirecInst">
																<option value="" selected="selected"></option>
	          <?php
        $rsDpto = $objTablas->ListaProvincias($dpto);
        $objFunc->llenarComboI($rsDpto, 'codigo', 'descripcion', $prov);
        ?>
	          </select></td>
														<td><select name="cbodist" id="cbodist"
															style="width: 150px;" class="DirecInst">
																<option value="" selected="selected"></option>
	          <?php
        $rsDpto = $objTablas->ListaDistritos($dpto, $prov);
        $objFunc->llenarComboI($rsDpto, 'codigo', 'descripcion', $dist);
        ?>
	          </select></td>
													</tr>
												</table>
											</td>
										</tr>
										<tr>
											<td height="22" colspan="3"><strong>Direccion <br />
											</strong></td>
											<td width="19%">&nbsp;</td>
											<td colspan="2"><strong>Ciudad</strong></td>
										</tr>
										<tr>
											<td height="37" colspan="4" valign="top"><input
												name="t01_dire_inst" type="text" class="DirecInst"
												id="t01_dire_inst" value="<?php echo($t01_dire_inst); ?>"
												size="70" maxlength="200" /></td>
											<td colspan="2" valign="top"><input name="t01_ciud_inst"
												type="text" id="t01_ciud_inst" size="35"
												value="<?php echo($t01_ciud_inst); ?>" class="DirecInst" /></td>
										</tr>
										<tr>
											<td width="10%" align="left"><strong>Telefono 1</strong></td>
											<td width="10%" align="left"><strong>Telefono 2</strong></td>
											<td width="22%" align="left"><strong>Fax</strong></td>
											<td align="left"><strong>RPM</strong></td>
											<td width="18%"><strong>RPC</strong></td>
											<td width="21%"><strong>Nextel</strong></td>
										</tr>
										<tr>
											<td align="left"><input name="t01_fono_inst" type="text"
												class="DirecInst" id="t01_fono_inst"
												value="<?php echo($t01_fono_inst); ?>" size="14"
												maxlength="50" /></td>
											<td align="left"><input name="t01_fon2_inst" type="text"
												class="DirecInst" id="t01_fon2_inst"
												value="<?php echo($t01_fon2_inst); ?>" size="14"
												maxlength="50" /></td>
											<td align="left"><input name="t01_fax_inst" type="text"
												class="DirecInst" id="t01_fax_inst"
												value="<?php echo($t01_fax_inst); ?>" size="14"
												maxlength="50" /></td>
											<td align="left"><input name="t01_rpm_inst" type="text"
												class="DirecInst" id="t01_rpm_inst"
												value="<?php echo($t01_rpm_inst); ?>" size="14"
												maxlength="10" /></td>
											<td><input name="t01_rpc_inst" type="text" class="DirecInst"
												id="t01_rpc_inst" value="<?php echo($t01_rpc_inst); ?>"
												size="14" maxlength="15" /></td>
											<td><input name="t01_nex_inst" type="text" class="DirecInst"
												id="t01_nex_inst" value="<?php echo($t01_nex_inst); ?>"
												size="14" maxlength="10" /></td>
										</tr>
										<tr>
											<td height="23" colspan="3" align="left" valign="bottom"><strong>Mail
													Institucional</strong></td>
											<td colspan="3" align="left" valign="bottom"><strong>Web
													Institucional </strong></td>
										</tr>
										<tr>
											<td colspan="3" align="left"><input name="t01_mail_inst"
												type="text" class="DirecInst" id="t01_mail_inst"
												value="<?php echo($t01_mail_inst); ?>" size="40"
												maxlength="50" style="text-transform: lowercase;" /></td>
											<td colspan="3" align="left"><input name="t01_web_inst"
												type="text" class="DirecInst" id="t01_web_inst"
												value="<?php echo($t01_web_inst); ?>" size="50"
												maxlength="100" style="text-transform: lowercase;" /></td>
										</tr>

       <?php

if ($id == "") {
        $sURL = "process.php?action=" . md5("ajax_new");
    } else {
        $sURL = "process.php?action=" . md5("ajax_edit");
    }
    ?>
      <input type="hidden" name="txturlsave" id="txturlsave" value="<?php echo($sURL); ?>" />

									</table>
								</fieldset>
	
    <span style="color: #F00; font-size: 11px; font-weight: bold;" id="spnMensaje">
    <?php if ($esta != '1' && $view == md5("ajax_edit")) { ?>
		<br /> La Institución esta Inactiva, debe registrar al menos un Responsable. <br /> <br />
	<?php } ?>
	</span>
    

   <?php if($view != md5("ajax_new")) { ?>
   <fieldset>
									<legend>Responsables de la Institución</legend>
									<div id="divResponsables"></div>
								</fieldset>
   <?php  } ?>

   <br />


   <?php if ($ObjSession->PerfilID == $HC->SP) { ?>
                                <fieldset>
									<legend>Cuentas Bancarias</legend>
									<div id="divCtasBancarias"></div>
								</fieldset>
   <?php } ?>

    </td>
						</tr>

					</table>

					</table>

					<script language="javascript">

  <?php if($accion!=md5("editar") && $view == md5("ajax_edit")) { ?>
  $('#btnGuardar').attr("disabled","disabled");
  $('.DirecInst').attr("disabled","disabled");
  <?php } ?>


function CancelEdit()
{
	$("#divContentEdit").fadeOut("slow");
	$("#divContent").fadeIn("slow");
	$("#divContent").css('display', 'block');
	$("#divContentEdit").css('display', 'none');
	return true;
}

function btnGuardarRelacion_Clic(){
	var BodyForm = $("#FormData").serialize() ;
	var sURL = "process.php?action=<?php echo(md5("ajax_edit_relacion"));?>";
	var req = Spry.Utils.loadURL("POST", sURL, true, MySuccessCallbackInst, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
	return false;
}


function btnGuardar_Clic()
{
	<?php
if ($view == md5("ajax_edit")) {
    $ObjSession->AuthorizedPage("EDITAR");
} else {
    $ObjSession->AuthorizedPage("NUEVO");
}
?>

	if( $('#t01_sig_inst').val()=="" ) {alert("Ingrese Siglas de la Institución"); $('#t01_sig_inst').focus(); return false;}
	if( $('#t01_nom_inst').val()=="" ) {alert("Ingrese Nombre de la Institución"); $('#t01_nom_inst').focus(); return false;}
	if( $('#t01_ruc_inst').val()=="" ) {alert("Ingrese RUC de la Institución"); $('#t01_ruc_inst').focus(); return false;}
	if( $('#t01_fch_fund').val()=="" )  {alert("Ingrese Fecha de Fundación de la Institución"); $('#t01_fch_fund').focus(); return false;}
	if( $('#t01_tipo_inst').val()=="" ) {alert("Seleccione Tipo de Institución"); $('#t01_tipo_inst').focus(); return false;}
	if( $('#t01_fono_inst').val()=="" ) {alert("Ingrese Telefono de la Institución"); $('#t01_fono_inst').focus(); return false;}
	if( $('#t01_mail_inst').val()=="" ) {alert("Ingrese Mail Institucional"); $('#t01_mail_inst').focus(); return false;}
	if(!isValidEmail($('#t01_mail_inst').val())) {
		alert("Mail Institucional incorrecto"); $('#t01_mail_inst').focus(); return false;
	}

<?php 
// -------------------------------------------------->
// DA 2.0 [14-11-2013 21:57]
// Actualizacion del algoritmo para comprobar el RUC:
// Fuente: Pagina de SUNAT
?>	
	if (!esrucok($('#t01_ruc_inst').val())) {
		alert("El número de RUC no es válido."); 
		$('#t01_ruc_inst').focus(); 
		return false;
	}
<?php 
// --------------------------------------------------<
?>
	var BodyForm = $("#FormData").serialize() ;

	var sURL = $('#txturlsave').val();

	var req = Spry.Utils.loadURL("POST", sURL, true, MySuccessCallbackInst, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
	return false;

}

function MySuccessCallbackInst(req)
{
  var respuesta = req.xhRequest.responseText;
  respuesta = respuesta.replace(/^\s*|\s*$/g,"");
  var ret = respuesta.substring(0,5);
  if(ret=="Exito")
  {
	var idinst = respuesta.substring(0,9);
	alert($('<div></div>').html(respuesta.replace(idinst,"")).text());
	idinst = idinst.replace(ret,"");
	<?php
if ($view == md5("ajax_edit")) {
    echo ("ReloadLista();  return ;");
} else {
    echo ("	dsLista.loadData(); btnEditar_Clic(idinst, '" . md5("editar") . "');");
}
?>
  }
  else
  {
  	alert($('<div></div>').html(respuesta).text());
  }

}

function LoadProv()
{
	var BodyForm = "dpto=" + $('#cbodpto').val();
	var sURL = "<?php echo(constant("PATH_SME")."proyectos/anexos/");?>amb_geo_process.php?action=<?php echo(md5("lista_provincias"))?>" ;
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
	var sURL = "<?php echo(constant("PATH_SME")."proyectos/anexos/");?>amb_geo_process.php?action=<?php echo(md5("lista_distritos"))?>" ;
	$('#cbodist').html('<option> Cargando ... </option>');
	var req = Spry.Utils.loadURL("POST", sURL, true, DistSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
}
function DistSuccessCallback(req)
{
  var respuesta = req.xhRequest.responseText;
  $('#cbodist').html(respuesta);
  $('#cbodist').focus();
}

<?php if($view != md5("ajax_new")) { ?>
LoadResponsables();
LoadCuentasBancarias();
<?php } else { ?>
HideCuentasBancarias();
<?php } ?>

<?php //if($id == $HC->codigo_Fondoempleo) { 
//LoadCuentasBancarias();
 //} ?>

 function HideCuentasBancarias()
 {
	 $('#divCtasBancarias').parent().hide();
 }
 
function LoadResponsables()
{
	var url = "ejec_resp_list.php?action=<?php echo(md5("lista"));?>&id=<?php echo($id);?>";
	$("#divResponsables").html('<p align="center"><img src="<?php echo(constant("PATH_IMG"))?>indicator.gif" width="16" height="16" /><br>Cargando...</p>');
	var req = Spry.Utils.loadURL("GET", url, true, MySuccessLoadResponsables, { headers: {"Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCall});
}

function MySuccessLoadResponsables(req)
{
  var respuesta = req.xhRequest.responseText;
  $("#divResponsables").html(respuesta);
    <?php if($accion!=md5("editar") && $view == md5("ajax_edit")) { ?>
  	$('.RespInst').attr("disabled","disabled");
    <?php } ?>
  return;
}

function LoadCuentasBancarias()
{
	var url = "ejec_ctas_bco.php?action=<?php echo(md5("lista"));?>&id=<?php echo($id);?>";
	$("#divCtasBancarias").html('<p align="center"><img src="<?php echo(constant("PATH_IMG"))?>indicator.gif" width="16" height="16" /><br>Cargando...</p>');
	var req = Spry.Utils.loadURL("GET", url, true, MySuccessLoadCuentasBancarias, { headers: {"Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCall});
}

function MySuccessLoadCuentasBancarias(req)
{
  var respuesta = req.xhRequest.responseText;
  $("#divCtasBancarias").html(respuesta);
    <?php if($accion!=md5("editar") && $view == md5("ajax_edit")) { ?>
  	$('.CtaBanco').attr("disabled","disabled");
    <?php } ?>
  return;
}

  </script>

					<script type="text/javascript">
$("#t01_fch_fund").mask('99/99/9999');
$('#t01_pres_anio').numeric().pasteNumeric();

//$.mask.definitions['*']='[*#]';
//$.mask.definitions['0']='[0123456789 ]';

//$('#t01_ruc_inst').mask('99999999999');
$('#t01_ruc_inst').numeric().pasteNumeric();
/*
$('#t01_fono_inst').mask('(0?0)999-9999');
$('#t01_fon2_inst').mask('(0?0)999-9999');
$('#t01_fax_inst').mask('(0?0)999-9999');
$('#t01_rpm_inst').mask('*999999?000');
$('#t01_rpc_inst').mask('999-999-999');
$('#t01_nex_inst').mask('999 * 9999');
*/
</script>


<?php
if($OjbTab==NULL){
?>
  <!-- InstanceEndEditable -->
				</div>
			</form>
		</div>
		<!-- Fin de Container Page-->
	</div>

</body>
<!-- InstanceEnd -->
</html>

<?php
 }
 ?>