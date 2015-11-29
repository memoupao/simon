<?php 

include("includes/constantes.inc.php"); 

if (! isset($_SESSION)) {    
    session_start();
}
require (constant('PATH_CLASS') . "Functions.class.php");
$objFunc = new Functions();

// -------------------------------------------------->
// DA 2.0 [16-11-2013 23:11]
// Nueva opcion de Recordar accesos
if (isset($_COOKIE['remember_sgp']) && !empty($_COOKIE['remember_sgp'])) {
    $remember_sgp = $objFunc->decrypt($_COOKIE['remember_sgp']);
    $_SESSION["UserID"] = $remember_sgp;
    
    $urlDefault = ($objFunc->__Request('lasturl') == '' ? 'default.php' : $objFunc->__Request('lasturl'));
    $objFunc->Redirect($urlDefault);
    exit;
}
// --------------------------------------------------<

$ls_user = $objFunc->__POST("txtuser");

require (constant('PATH_CLASS') . "MySQLDB.class.php");
require (constant('PATH_CLASS') . "BLSession.class.php");
$con = new DB_mysql();
$con->conectar(constant('DB_NAME'), constant('DB_HOST'), constant('DB_USER'), constant('DB_PWD'));
$ObjSession = new BLSession($con->Conexion_ID);
$_SESSION['ObjSession'] = $ObjSession;

$ret = ($objFunc->__POST("txtuser") != "");
$msgError = $objFunc->__Request("error");
if ($ret) {
    // -------------------------------------------------->
    // DA 2.0 [16-11-2013 16:29]
    // Nueva validacion de Captcha
        
	require_once 'lib/captcha/securimage.php';
	$imgCaptcha = new Securimage();
	
	$cword = $_POST['cword'];
	$valid = $imgCaptcha->check($cword);
	
	if ($valid) {
    // --------------------------------------------------<
    
		$user = $objFunc->__POST("txtuser");
		$pwd = $objFunc->__POST("txtpwd");
		
		$recordar = (int) $objFunc->__POST("recordar");
		
		$ObjSession->Login($user, $pwd, session_id());
		if ($ObjSession->Authorized()) {
			$_SESSION["UserID"] = $ObjSession->UserID;
			
			// -------------------------------------------------->
			// DA 2.0 [16-11-2013 23:11]
			// Nueva opcion de Recordar accesos
			
			if ($recordar == 1) {
			    $remember_sgp = $objFunc->encrypt($_SESSION["UserID"]);			    
			    setcookie('remember_sgp', $remember_sgp, time() + 60*60*24*30);
			}
			// --------------------------------------------------<
			
			// $objFunc->MsgBox("Bienvenido Usuario: ".$ObjSession->UserName);
			$urlDefault = ($objFunc->__Request('lasturl') == '' ? 'default.php' : $objFunc->__Request('lasturl'));
			$objFunc->Redirect($urlDefault);
		} else {
			$msgError = 'Usuario o Password incorrectos !!!';
		}
	// -------------------------------------------------->
	// DA 2.0 [16-11-2013 16:29]
	// Nueva validacion de Captcha		
	} else {
		$msgError = 'El codigo ingresado no es correcto !!!';
	}
	// --------------------------------------------------<


    
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php
$objFunc->SetTitle("Iniciar Sesión");
?>
    <meta name="keywords" content="<?php echo($objFunc->Keywords); ?>" />
<meta name="description" content="<?php echo($objFunc->MetaTags); ?>" />
<!-- AQ 2.0 [22-10-2013 16:05]
     Eliminación de charset=UTF-8. -->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo($objFunc->Title);?></title>
<link href="css/template.css" rel="stylesheet" media="all" />
<script src="SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
<link href="SpryAssets/SpryMenuBarHorizontal.css" rel="stylesheet"
	type="text/css" />
<link rel="shortcut icon" href="img/feicon.ico" type="image/x-icon">
<script src="jquery.ui-1.5.2/jquery-1.2.6.js" type=text/javascript></script>
<!-- AQ 2.0 [22-10-2013 12:42]
         Eliminación efecto de banner.
         Mejora del formato. -->
<style type="text/css">
<!--
.oneColElsCtrHdr #container #mainContent #FormData table tr td .contentbox #LayerSession table tr td span
{
	color: #FF0000;
    display: block;
    margin-bottom: 10px;
}
-->
</style>
<script src="SpryAssets/SpryData.js" type="text/javascript"></script>
<script src="SpryAssets/SpryPopupDialog.js" type="text/javascript"></script>
<link href="SpryAssets/SpryPopupDialog.css" rel="stylesheet"
	type="text/css" />
</head>
<body class="oneColElsCtrHdr">
	<div id="container">
		<div id="banner">
			<div id="slider">
				<ul id="sliderContent">
					<li class="sliderImage"><img src="img/banner1.jpg" /> <span
						class="top">Sistema de Monitoreo y Evaluación</span></li>
					<li class="sliderImage"><img src="img/banner2.jpg" /> <span
						class="bottom">Monitoreo Operativo</span></li>
					<li class="sliderImage"><img src="img/banner3.jpg" /> <span
						class="top">Monitoreo Estrategico</span></li>
					<li class="sliderImage"><img src="img/banner4.jpg" /> <span
						class="bottom">Monitoreo FONDOEMPLEO</span></li>

					<div class="clear sliderImage"></div>
				</ul>
			</div>
		</div>
		<div class="MenuBarHorizontalBack">
			<ul id="MenuBar1" class="MenuBarHorizontal">
			</ul>
		</div>

		<div class="Subtitle">
			<span class="title">Inicio de Sesión</span>
		</div>

		<div id="mainContent">
			<form id="FormData" method="post"
				enctype="application/x-www-form-urlencoded"
				action="<?php echo($_SERVER['PHP_SELF']);?>">
				<table width="70%" border="0" cellspacing="0" cellpadding="10"
					align="center">
					<tr>
						<td width="50%" height="10">&nbsp;</td>
						<td width="50%">&nbsp;</td>
					</tr>
					<tr>
						<td align="right">
							<div align="center" class="sistema">
								<img src="img/FE.jpg" /><br />
								<h3>Sistema de Gestión de Proyectos</h3>
							</div>
						</td>
						<td>
							<div class="caption">
								<h3></h3>
								<p>:: Ingreso de Usuarios</p>
							</div>
							<div class="contentbox">
								<div id="LayerSession">
									<table width="100%" border="0" cellpadding="0" cellspacing="0">
										<tr>
											<td width="50%">&nbsp;</td>
											<td width="36%">&nbsp;</td>
										</tr>
										<tr>
											<?php
        if ($objFunc->__Request('changePWD') == '1') {
            $ls_user = $ObjSession->UserID;
        }
        ?>
											<td height="27" valign="top"><strong>Usuario</strong></td>
											<td valign="top"><input name="txtuser" type="text"
												class="TextDescripcion" id="txtuser" size="20"
												value='<?php echo($ls_user);?>' autocomplete='off'
												required='required' /></td>
										</tr>
										<tr>
											<td height="27" valign="top"><strong>Contraseña</strong>
											</td>
											<td valign="top"><input name="txtpwd" type="password"
												class="TextDescripcion" id="txtpwd" size="20"
												autocomplete='off' required='required' /></td>
										</tr>
<?php 

// -------------------------------------------------->
// DA 2.0 [20-10-2013 16:34]
// Nuevo campo de Captcha para ingreso al sistema

?>										
										<tr>
											<td height="85" valign="top">
												<strong>Ingresa el código</strong>
												<small>
												No puedes leer el código ?<br>
												
													<a id="captchaShuffleLink" href="javascript:void(0);">Trata con otro código diferente</a>
												</small>
											</td>
											<td valign="top">
												<input type="text" name="cword" id="cword" value="" size="10" maxlength="6"  autocomplete="off" required='required'><br /> 
												<img src="lib/captcha/securimage_show.php?sid=<?php echo md5(uniqid()) ?>" alt="captcha" name="cimg" id="cimg" style="margin-top:5px;">
											</td>
										</tr>
<?php
// --------------------------------------------------< 
?>										
                                        <tr>
                                            <td  valign="top">
                                            </td>
                                            <td  valign="top">
                                                <label>
                                                    <input type="checkbox" name="recordar" id="recordar" value="1"/> 
                                                    Recordar acceso 
                                                </label>
                                            </td>
                                        
                                        </tr>


										<tr>
											<td>&nbsp;</td>
											<td valign="top"><p align="right">
													<input name="btnlogin" type="image" class="Boton"
														id="btnlogin" value="Iniciar Sesion"
														src="img/close-icon_files/forward-icon.png" width="23"
														height="23" style="border: none;" />
												</p></td>
										</tr>
										<tr>
											<td  colspan="2" align="center" valign="middle">
    											<span>
    											     <?php echo($msgError);?>
    												<input type="hidden" name="lasturl" id="lasturl" value="<?php echo($objFunc->__Request('lasturl')); ?>" /> 
    											</span>
											</td>
										</tr>
									</table>
								</div>


							</div>
						</td>
					</tr>
					<tr>
						<td height="120" rowspan="3">&nbsp;</td>
						<td height="50" align="right" valign="bottom"><a href="#"
							onclick="ChangePwd();" title="Cambiar Contraseña"
							style="color: #666; font-size: 11px; font-weight: bold;">Cambiar
								Contraseña </a></td>
					</tr>
					<tr>
						<td height="22" align="right"><a href="javascript:SendPwd();"
							title="Enviar Contraseña por Correo"
							style="color: #666; font-size: 11px; font-weight: bold; display:none;">He
								Olvidado mi Contraseña</a></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
					</tr>
				</table>


				<div id="panelPWD" class="popupContainer">
					<div class="popupBox">
						<div class="popupBar">
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td width="100%">Cambiar Contraseña</td>
									<td align="right"><a class="popupClose" href="javascript:;"
										onclick="spryPopupDialogPWD.displayPopupDialog(false);"><b>X</b>
									</a></td>
								</tr>
							</table>
						</div>

						<div class="popupContent" style="background-color: #FFF;">
							<div id="popupText"></div>

							<div id="divChangePWD"></div>

						</div>
					</div>
				</div>
				<script language="JavaScript" type="text/javascript">
      var spryPopupDialogPWD= new Spry.Widget.PopupDialog("panelPWD", {modal:true, allowScroll:true, allowDrag:true});
      </script>


			</form>
		</div>

		<script language="javascript" type="text/javascript">
  function ChangePwd()
  {
	var iduser = $('#txtuser').val();

	if(iduser==""){alert("Ingrese el usuario que desee cambiar la contraseña."); return false;}

	var sURL = '<?php echo(constant("DOCS_PATH"));?>Admin/man_usu_pwd2.php';
	var BodyForm = 'mode=<?php echo(md5("change_pwd"));?>&id='+iduser ;
	$('#divChangePWD').html("<p align='center'><img src='img/indicator.gif' width='16' height='16' /><br>Cargando..<br></p>");
	var req = Spry.Utils.loadURL("POST", sURL, true, SuccessLoadChangePWD, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });

	spryPopupDialogPWD.displayPopupDialog(true);
	return false ;

  }
  function SuccessLoadChangePWD(req)
	{
	   var respuesta = req.xhRequest.responseText;
	   if(respuesta.replace(/^\s*|\s*$/g,"")=="" || respuesta==null )
	   {alert("El Usuario no es correcto, ingrese el usuario correctamente del cual desea cambiar la Contraseña"); return false;}

	   $("#divChangePWD").html(respuesta);
 	   return;
	}
  function onErrorLoad()
  { alert("Error al cargar la pagina");}

  function SendPwd()
  {
	  alert("En Desarrollo") ;
	  return ;
  }
  
  $('#captchaShuffleLink').click(function(e){
	e.preventDefault();
	$('#cimg').attr('src','lib/captcha/securimage_show.php?sid=' + Math.random());
	return true;
		
  });

  $('#recordar').change(function(){
	  if ($(this).is(':checked')) {
		  if (!navigator.cookieEnabled) {
			  alert('Su Navegador no admite cookies, por lo tanto \nno funcionará la opción de "Recordar acceso".');
			  
			  var msgCookie = $('<p></p>').text('Su Navegador no admite cookies, por lo tanto no funcionará la opción de "Recordar acceso".');
			  $('#lasturl').parent().find('p').remove();
			  $('#lasturl').parent().prepend(msgCookie);
			  $(this).removeAttr('checked');
		  } 
		  
	  } 
  });

   <?php
if ($objFunc->__Request('changePWD') == '1') {
    echo ("ChangePwd();");
}
?>


  </script>

		<div id="footer">
			<?php include("includes/Footer.php"); ?>
		</div>

	</div>

</body>
</html>
