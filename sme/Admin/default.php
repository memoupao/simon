<?php include("../includes/constantes.inc.php"); ?>
<?php include("../includes/validauseradm.inc.php"); ?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/tplMain.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<!-- InstanceBeginEditable name="doctitle" -->
<?php
$objFunc->SetTitle("Pagina Principal - Administrador");
$objFunc->SetSubTitle("Modulo Administración - Sistema de Monitoreo y Evaluación");
$ObjSession->MostrarBotonesAccesoDirecto = false;
?>
<!-- InstanceEndEditable -->
<meta name="keywords" content="<?php echo($objFunc->Keywords); ?>" />
<meta name="description" content="<?php echo($objFunc->MetaTags); ?>" />
<!-- AQ 2.0 [22-10-2013 16:05]
     Eliminación de charset=UTF-8. -->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" href="../img/feicon.ico" type="image/x-icon">
<title><?php echo($objFunc->Title);?></title>
<link href="../css/template.css" rel="stylesheet" media="all" />
<script src="../SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
<link href="../SpryAssets/SpryMenuBarHorizontal.css" rel="stylesheet"
	type="text/css" />
<!-- InstanceBeginEditable name="head" -->
<!-- InstanceEndEditable -->
<SCRIPT src="../jquery.ui-1.5.2/jquery-1.2.6.js" type="text/javascript"></SCRIPT>
<!-- AQ 2.0 [22-10-2013 16:32]
     Eliminación de animación del banner. -->
</head>
<body class="oneColElsCtrHdr">
	<!-- Inicio de Container Page-->
	<div id="container">
		<!-- AQ 2.0 [22-10-2013 16:35]
     Eliminación de div del banner. -->
		<div class="MenuBarHorizontalBack">
			<ul id="MenuBar1" class="MenuBarHorizontal">
        <?php include("../includes/MenuBarAdmin.php"); ?>
      </ul>
		</div>
		<script type='text/javascript'>
        var MenuBar1 = new Spry.Widget.MenuBar('MenuBar1');
     </script>

		<div class="Subtitle">
    <?php include("../includes/subtitle.php");?>
    </div>

		<div id="mainContent">
			<form id="FormData" method="post"
				enctype="application/x-www-form-urlencoded"
				action="<?php echo($_SERVER['PHP_SELF']);?>">
				<!-- InstanceBeginEditable name="Contenidos" -->
				<div align="center" style="width: 800px;">
					<br /> <img src="../img/fondo_admin.gif" width="280" height="281" />
				</div>
				<!-- InstanceEndEditable -->
			</form>
		</div>
		<div id="footer">
	<?php include("../includes/Footer.php"); ?>
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
