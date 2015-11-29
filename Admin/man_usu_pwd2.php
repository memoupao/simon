<?php include("../includes/constantes.inc.php"); ?>
<?php

if (! isset($_SESSION)) {
    session_start();
}
require (constant('PATH_CLASS') . "MySQLDB.class.php");
require (constant('PATH_CLASS') . "BLSession.class.php");
require (constant('PATH_CLASS') . "Functions.class.php");
require (constant('PATH_CLASS') . "BLMantenimiento.class.php");

$con = new DB_mysql();
$con->conectar(constant('DB_NAME'), constant('DB_HOST'), constant('DB_USER'), constant('DB_PWD'));
$_SESSION['ObjSession'] = new BLSession($con->Conexion_ID);
$objFunc = new Functions();

$action = $objFunc->__GET('action');

if ($action == md5("save_new_pwd")) {
    ob_clean();
    ob_start();
    
    $usuario = $_POST['txtuser'];
    $_SESSION["UserID"] = $usuario;
    
    $pwd_anterior1 = $_POST['txtpwdanterior1'];
    $pwd_anterior2 = $_POST['txtpwdanterior2'];
    if (md5($pwd_anterior2) != $pwd_anterior1) {
        echo ("ERROR : \n" . "La contraseña anterior no es correcta.");
        return false;
    }
    $pwd_new1 = $_POST['txtpwd1'];
    $pwd_new2 = $_POST['txtpwd2'];
    
    if ($pwd_new1 != $pwd_new2) {
        echo ("ERROR : \n" . "Las Nuevas Contraseñas no coinciden, verifique por favor...");
        return false;
    }
    
    $objMante = new BLMantenimiento();
    $bret = false;
    $bret = $objMante->UsuarioCambiarPwd($usuario, $pwd_new1);
    if ($bret) {
        echo ("Exito Se Cambio correctamente la Contraseña del usuario [" . $usuario . "]!!!");
    } else {
        echo ("ERROR : \n" . $objMante->GetError());
    }
    $objMante = NULL;
    exit();
}

?>

<?php

$objMante = new BLMantenimiento();
$view = $objFunc->__POST('mode');
$idUser = $objFunc->__POST('id');

if ($idUser == "") {
    exit();
}
$row = 0;

if ($view == md5("change_pwd")) {
    $row = $objMante->UsuarioSeleccionar($id);
    
    if ($row['coduser'] != "") {
        // $_SESSION["UserID"] = $row['coduser'] ;
    } else {
        exit();
    }
} else {
    $row = 0;
}
$con = NULL;

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
 
  <div id="EditForm" style="width: 500px; border: solid 1px #D3D3D3;"
						align="center">
						<br />
						<table width="500" border="0" cellpadding="0" cellspacing="0"
							class="TableEditReg">
							<tr>
								<td width="400" align="center">
									<fieldset>
										<legend>Cambiar Contraseña </legend>
										<table width="481" border="0" cellspacing="1" cellpadding="0">
											<tr>
												<td height="29" align="left" valign="middle">Usuario</td>
												<td valign="middle"><input name="txtuser2" type="text"
													disabled="disabled" id="txtuser2"
													value="<?php echo($row['coduser']); ?>" size="25"
													maxlength="20" /></td>
												<td width="42%" align="left" valign="middle"><?php echo($row['nom_user']);?>
                  <input name="txtuser" type="hidden" id="txtuser"
													value="<?php echo($row['coduser']); ?>" /></td>
											</tr>
              <?php if($view!=md5('change_pwd_mante')) { ?>
              <tr>
												<td width="31%" height="36" align="left" valign="top">Contraseña
													Anterior</td>
												<td width="27%" valign="top"><input name="txtpwdanterior2"
													type="password" id="txtpwdanterior2" size="25"
													maxlength="20" /></td>
												<td width="42%" align="center" valign="middle"><input
													name="txtpwdanterior1" type="hidden" id="txtpwdanterior1"
													value="<?php echo($row['clave_user']); ?>" size="25"
													maxlength="20" /></td>
											</tr>
               <?php }  ?>
              <tr>

												<td align="left">Nueva Contraseña</td>
												<td><input name="txtpwd1" type="password" id="txtpwd1"
													size="25" maxlength="20" /></td>
												<td width="42%" align="center" valign="middle">&nbsp;</td>
											</tr>
											<tr>
												<td align="left" nowrap="nowrap">Confirmar Contraseña</td>
												<td><input name="txtpwd2" type="password" id="txtpwd2"
													size="25" maxlength="20" /></td>
												<td width="42%" align="center" valign="middle">&nbsp;</td>
											</tr>
											<tr>
												<td nowrap="nowrap">&nbsp;</td>
												<td>&nbsp;</td>
												<td>&nbsp;</td>
											</tr>
											<tr>
												<td align="center">
													<div
														style="text-align: center; color: navy; font-weight: bold; font-size: 10px; cursor: pointer; width: 60px;"
														title="Cambiar Password" onclick="SaveChangePWD();">
														<img src="<?php echo(constant("PATH_IMG"));?>aplicar.png"
															width="24" height="24" /><br /> Aceptar
													</div>
												</td>
												<td><div
														style="text-align: center; color: navy; font-weight: bold; font-size: 10px; cursor: pointer; width: 60px;"
														title="Cambiar Password"
														onclick="spryPopupDialogPWD.displayPopupDialog(false);">
														<img src="<?php echo(constant("PATH_IMG"));?>delete.gif"
															width="16" height="16" /><br /> Cancelar
													</div></td>
												<td>&nbsp;</td>
											</tr>
											<tr>
												<td>&nbsp;</td>
												<td>&nbsp;</td>
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
  function SaveChangePWD()
	{
	 if( $('#coduser').val()=="" ) {alert("Usuario no valido"); return false;}	

	 var pwd1 = $("#txtpwd2").val();
	 var pwd2 = $("#txtpwd2").val();
	 if(pwd1==''){alert("Ingrese Contraseña"); return false ;}
	 if(pwd1 != pwd2) {alert("Las Contraseñas no coinciden"); return false ;}
	 
	 var BodyForm = $("#FormData").serialize() ;
	 var sURL = "<?php echo(constant("DOCS_PATH"));?>Admin/man_usu_pwd2.php?action=<?php echo(md5("save_new_pwd"));?>"
	 var req = Spry.Utils.loadURL("POST", sURL, true, MySuccessChangePWD, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
	
	return false;
	
	}
	
	function MySuccessChangePWD	(req)
	{
	  var respuesta = req.xhRequest.responseText;
	  respuesta = respuesta.replace(/^\s*|\s*$/g,"");
	  var ret = respuesta.substring(0,5);
	  if(ret=="Exito")
	  {
		spryPopupDialogPWD.displayPopupDialog(false);
		alert(respuesta.replace(ret,""));
	  }
	  else
	  {alert(respuesta);}  
	}
	function MyErrorCallback()
	{
		alert("Error al cargar la pagina... Comuniquese con el Administrador");
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

