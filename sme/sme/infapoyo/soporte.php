<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauser.inc.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/tplMain.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<!-- InstanceBeginEditable name="doctitle" -->
<?php
$objFunc->SetTitle("Información de Apoyo");
$objFunc->SetSubTitle("Soporte");
?>
<!-- InstanceEndEditable -->
<meta name="keywords" content="<?php echo($objFunc->Keywords); ?>" />
<meta name="description" content="<?php echo($objFunc->MetaTags); ?>" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" href="../../img/feicon.ico"
	type="image/x-icon">
<title><?php echo($objFunc->Title);?></title>
<link href="../../css/template.css" rel="stylesheet" media="all" />
<script src="../../SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
<link href="../../SpryAssets/SpryMenuBarHorizontal.css" rel="stylesheet"
	type="text/css" />
<!-- InstanceBeginEditable name="head" -->
<!-- InstanceEndEditable -->
<SCRIPT src="../../jquery.ui-1.5.2/jquery-1.2.6.js" type=text/javascript></SCRIPT>
<SCRIPT src="../../js/s3Slider.js" type=text/javascript></SCRIPT>
<SCRIPT type=text/javascript>
    $(document).ready(function() {
        $('#slider').s3Slider({
            timeOut: 4500
        });
    });
</SCRIPT>
</head>
<body class="oneColElsCtrHdr">
	<!-- Inicio de Container Page-->
	<div id="container">
		<div id="banner">
        <?php include("../../includes/Banner.php"); ?>
</div>
		<div class="MenuBarHorizontalBack">
			<ul id="MenuBar1" class="MenuBarHorizontal">
        <?php include("../../includes/MenuBar.php"); ?>
      </ul>
		</div>
		<script type='text/javascript'>
        var MenuBar1 = new Spry.Widget.MenuBar('MenuBar1');
     </script>

		<div class="Subtitle">
    <?php include("../../includes/subtitle.php");?>
    </div>

		<div id="mainContent">
			<form id="FormData" method="post"
				enctype="application/x-www-form-urlencoded"
				action="<?php echo($_SERVER['PHP_SELF']);?>">
				<!-- InstanceBeginEditable name="Contenidos" -->
				</br>
  <?php
$ObjSession = new BLSession();
$ODatos = $ObjSession->GetUsuario();

$nombre = $ODatos['nom_user'];
$email = $ODatos['mail'];
$perfil = $ODatos['perfil'];

?>
  <table align="center" width="555" border="0" class="RowAlternate">
					<tr>
						<td width="240">&nbsp;</td>
						<td width="300">&nbsp;</td>
					</tr>
					<tr>
						<td valign="top"><strong>Nombre y Apellido:</strong></td>
						<td><input name="nombre" type="text" id="nombre" size="50"
							value="<?php echo($nombre);?>" /></td>
					</tr>
					<tr>
						<td valign="top"><strong>Email:</strong></td>
						<td><input name="email" type="text" id="email" size="50"
							value="<?php echo($email);?>" /></td>
					</tr>
					<tr>
						<td></td>
						<td valign="top">Ejemplo: fondoempleo@fondoempleo.com.pe</td>
					</tr>
					<tr>
						<td valign="top"><strong>Perfil:</strong></td>
						<td><input name="perfil" type="text" id="perfil" size="50"
							value="<?php echo($perfil);?>" /></td>
					</tr>
					<tr>
						<td valign="top"><strong>Mensaje:</strong></td>
						<td><textarea name="mensaje" id="mensaje" cols="38" rows="4"></textarea></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>

						<td colspan="2" align="center"><span id="toolbar"
							style="width: 70px; height: 8px;">
								<button class="Button" name="mensaje" id="mensaje"
									onclick="EnviarMensaje(); return false;" value="Enviar">Enviar
									Mensaje</button>
						</span></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
				</table>
				<iframe id="ifrmUploadFile" name="ifrmUploadFile"
					style="display: none;"></iframe>



				<script language="javascript" type="text/javascript">
function EnviarMensaje()
{
 <?php $ObjSession->AuthorizedPage(); ?>	
 var nombre = $('#nombre').val();
 var email = $('#email').val();
 var perfil = $('#perfil').val();
 var mensaje = $('#mensaje').val();
 var re=/^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,3})$/;	
 
 if(nombre=='' || nombre==null){alert("Ingrese el nombre y apellido");return false;}
 if(email=='' || email==null){alert("Ingrese el correo electrónico");return false;}
 if(!re.exec(email)){alert("El correo electronico es incorrecto");return false;}
 if(perfil=='' || perfil==null){alert("Ingrese la institución");return false;}
 if(mensaje=='' || mensaje==null){alert("Ingrese la consulta");return false;}


 var f = document.getElementById("FormData");
 f.action="inf_soporte_process.php?action=<?php echo(md5('enviar_email'));?>" ;
 f.target="ifrmUploadFile"; 
 f.encoding="multipart/form-data";
 f.submit();
 f.target='_self';
 $('#mensaje').attr('value','');
 
}
</script>

				<br />
				<!-- InstanceEndEditable -->
			</form>
		</div>
		<div id="footer">
	<?php include("../../includes/Footer.php"); ?>
  </div>

		<!-- Fin de Container Page-->
	</div>

	<script language="javascript" type="text/javascript">
//FormData : Formulario Principal
var FormData = document.getElementById("FormData");
function CloseSesion()
{
	if(confirm("Estas seguro de Cerrar la Sesion de <?php echo($ObjSession->UserName);?>"))
	  {
			FormData.action = "<?php echo(constant("DOCS_PATH"));?>closesesion.php";
			FormData.submit();
	  }
	return true;
}
</script>
</body>
<!-- InstanceEnd -->
</html>
