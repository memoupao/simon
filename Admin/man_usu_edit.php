<?php include("../includes/constantes.inc.php"); ?>
<?php include("../includes/validauseradm.inc.php"); ?>
<?php

require (constant('PATH_CLASS') . "BLMantenimiento.class.php");
$objMante = new BLMantenimiento();
$view = $objFunc->__GET('mode');

$row = 0;

if ($view == md5("ajax_edit")) {
    $objFunc->SetSubTitle("Editando Usuario");
    $idUser = $objFunc->__GET('id');
    
    $row = $objMante->UsuarioSeleccionar($idUser);
} else {
    $row = 0;
    $objFunc->SetSubTitle("Nuevo Usuario");
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
 
	<div id="EditForm" style="width: 700px; border: solid 1px #D3D3D3;">
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
						<table width="700" border="0" cellpadding="0" cellspacing="0"
							class="TableEditReg">
							<tr>
								<td width="542">
									<fieldset>
										<legend>Datos Generales</legend>
										<table width="100%" border="0" cellspacing="1" cellpadding="0">
											<tr>
												<td width="20%">Usuario</td>
												<td width="41%"><input name="coduser" type="text"
													id="coduser" value="<?php echo($row['coduser']); ?>"
													size="30" maxlength="20"
													<?php if($view == md5("ajax_edit")){echo('disabled="disabled"');}?> />
												</td>
												<td width="9%">&nbsp;</td>
												<td width="30%">&nbsp;</td>
											</tr>
        <?php if($view == md5("ajax_new")) { ?>
        <tr>
												<td>Contraseña</td>
												<td><input name="clave_user1" type="password"
													id="clave_user1" value="<?php echo($row['clave_user']); ?>"
													size="30" maxlength="20" /></td>
												<td>&nbsp;</td>
												<td>&nbsp;</td>
											</tr>
											<tr>
												<td>Confirmar Contraseña</td>
												<td><input name="clave_user2" type="password"
													id="clave_user2" value="<?php echo($row['clave_user']); ?>"
													size="30" maxlength="20" /></td>
												<td>&nbsp;</td>
												<td>&nbsp;</td>
											</tr>
        <?php
        
} else {
            ?>
 			  <input name="coduser" type="hidden" id="coduser"
												value="<?php echo($row['coduser']); ?>" />       
        <?php } ?>
        
        <tr>
												<td nowrap="nowrap">Nombre de Usuario</td>
												<td><input name="nom_user" type="text" id="nom_user"
													value="<?php echo($row['nom_user']); ?>" size="50"
													maxlength="50" /></td>
												<td>&nbsp;</td>
												<td>&nbsp;</td>
											</tr>
											<tr>
												<td>E-mail</td>
												<td><input name="mail" type="text" id="mail"
													value="<?php echo($row['mail']); ?>" size="50"
													maxlength="60" /></td>
												<td>&nbsp;</td>
												<td>&nbsp;</td>
											</tr>
											<tr>
												<td>Perfil</td>
												<td><select name="tipo_user" id="tipo_user"
													style="width: 190px;" onchange="CargarAsociarUsuario();"
													class="Usuario">
														<option value=""></option>
            <?php
            $rs = $objMante->ListaPerfiles();
            $objFunc->llenarComboI($rs, 'codigo', 'descripcion', $row['idperfil']);
            ?>
          </select></td>
												<td align="right"><input name="chkActivo" type="checkbox"
													id="chkActivo" value="1"
													<?php if($row['estado']=='1'){echo('checked');}?> /></td>
												<td>Activo</td>
											</tr>

										</table>
									</fieldset>
								</td>
							</tr>
							<tr>
								<td>
									<fieldset>
										<legend>Asociar Usuario a: </legend>
										<table border="0" cellpadding="0" cellspacing="0"
											class="TableEditReg">
											<tr style="display: none;">
												<td width="120" nowrap="nowrap">Institución</td>
												<td width="52"><input name="t01_id_inst" id="t01_id_inst"
													type="text" value="<?php echo($row['t01_id_uni']); ?>"
													size="5" maxlength="10" /></td>
												<td width="16" align="center"><input type="image"
													name="btnBuscar2" id="btnBuscar2" src="../img/gosearch.gif"
													width="14" height="15" class="Image"
													onclick="BuscarInst(); return false;"
													title="Seleccionar Institucion Ejecutora" /></td>
												<td colspan="2"><input name="mail2" type="text"
													disabled="disabled" id="mail2"
													value="<?php echo($row['nominst']); ?>" size="50"
													maxlength="60" /></td>
											</tr>

											<tr>
												<td height="30" width="200"><span id="spnCaptionAsociar">Asociar</span></td>
												<td colspan="4"><select name="cboPersonal" id="cboPersonal"
													style="width: 250px" onchange="ValidateInst();">
														<option value="*">Todos</option>
												</select></td>
											</tr>

											<tr>
												<td>Proyecto</td>
												<td><input name="t02_cod_proy" id="t02_cod_proy" type="text"
													size="10" maxlength="10"
													value="<?php echo($row['t02_cod_proy']); ?>" /></td>
												<td align="center"><input type="image" name="btnBuscar"
													id="btnBuscar" src="../img/gosearch.gif" width="14"
													height="15" class="Image"
													onclick="BuscarProy(); return false;"
													title="Seleccionar Proyecto" /></td>
												<td>
												    &nbsp;												     
												    <input name="txt_paramsret" type="hidden" id="txt_paramsret" value="" />
												</td>
												<td>&nbsp;</td>
											</tr>

										</table>
									</fieldset>
								</td>
							</tr>
						</table>
					</div>

					<script language="javascript">
function CargarAsociarUsuario()
{
var user = $("#coduser").val();
var tipousuario = $("#tipo_user.Usuario").val();
var nomtipouser = $("#tipo_user.Usuario").find("option:selected").text();

$("#cboPersonal").html("<option>Cargando...</option>");
$("#spnCaptionAsociar").text( nomtipouser );
//$("#trPersonalAsociado").css("visibility","visible");
if(nomtipouser==''){ $("#cboPersonal").attr("disabled", "disabled");} else { $("#cboPersonal").removeAttr("disabled");}


var pURL = "man_usu_process.php?idUser="+user+"&idTipoUser="+tipousuario+"&action=<?php echo(md5("ajax_carga_asociar_usuario"));?>";
var req = Spry.Utils.loadURL("GET", pURL, true, MySuccessAsociarUsuario, { headers: {"Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback});
}

function MySuccessAsociarUsuario(req)
{
	var respuesta = req.xhRequest.responseText;
	$("#cboPersonal").html(respuesta);
	setTimeout("ValidateInst()",100);
}

function btnGuardar_Clic()
{
	var aRegex = /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/;
	
	if( $('#coduser').val()=="" ) {alert("Ingrese Usuario"); $('#coduser').focus(); return false;}	
 
 
 <?php if($view == md5("ajax_new")) { ?>
	 var pwd1 = $("#clave_user1").val();
	 var pwd2 = $("#clave_user2").val();
	 if(pwd1==''){alert("Ingrese Contraseña"); return false ;}
	 if(pwd1 != pwd2) {alert("Las Contraseñas no coinciden"); return false ;}
	 $("#clave_user1").focus();
 <?php } ?>
	
	if ($('#nom_user').val().trim() == "" ){alert("Ingrese Nombre de Usuario"); $('#nom_user').focus(); return false;}
	if ($('#mail').val().trim() == "" ){alert("Ingrese E-mail"); $('#mail').focus(); return false;}
	if (!$('#mail').val().trim().match(aRegex)) {alert($('<div></div>').html("Dirección E-mail no es válida.").text()); $('#mail').focus(); return false;}
	if ($('#tipo_user').val().trim() == "" ){alert("Seleccione Perfil"); $('#tipo_user').focus(); return false;}
 
 var BodyForm = $("#FormData").serialize() ;
 var sURL = "man_usu_process.php?action=<?php echo($view);?>"
 var req = Spry.Utils.loadURL("POST", sURL, true, MySuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });

return false;

}

function BuscarInst()
{ 
	alert("En Desarrollo");
}

function BuscarProy()
{
var arrayControls = new Array();
arrayControls[0] = "ctrl_idinst=t02_cod_proy";
arrayControls[2] = "ctrl_idversion=txt_paramsret";
arrayControls[3] = "ctrl_ejecutor=txt_paramsret";
arrayControls[4] = "ctrl_proyecto=txt_paramsret";
arrayControls[6] = "ctrl_idproy=t02_cod_proy";

var params = "?" + arrayControls.join("&"); 
var sUrl = "<?php echo(constant("DOCS_PATH"));?>sme/proyectos/datos/lista_proyectos.php" + params;
window.open(sUrl,"BuscaProy", "width=603, height=400,menubar=no, scrollbars=yes, location=no, resizable=no, status=no",true);
}

function ValidateInst()
{
var idvalor = $("#cboPersonal").val();
if(idvalor=='' || idvalor==null ){idvalor='*';}
$("#t01_id_inst").val(idvalor);
return;
}



CargarAsociarUsuario();

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

