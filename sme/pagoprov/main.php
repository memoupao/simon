<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauser.inc.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/tplMainLista.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<!-- InstanceBeginEditable name="doctitle" -->
<?php
$objFunc->SetTitle("Pago Masivo a Proveedores - via BCP");
$objFunc->SetSubTitle("Generación de Pago Masivo a Proveedores");
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
<script src="../../SpryAssets/SpryData.js" type="text/javascript"></script>
<!-- InstanceEndEditable -->
<SCRIPT src="../../jquery.ui-1.5.2/jquery-1.2.6.js" type=text/javascript></SCRIPT>
<SCRIPT src="../../js/s3Slider.js" type=text/javascript></SCRIPT>
<!-- InstanceBeginEditable name="jQuery" -->
<script src="../../SpryAssets/SpryTabbedPanels.js"
	type="text/javascript"></script>
<link href="../../SpryAssets/SpryTabbedPanels.css" rel="stylesheet"
	type="text/css" />
<!-- InstanceEndEditable -->
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
		<div class="AccesosDirecto">
        <?php include("../../includes/accesodirecto.php"); ?>
    </div>

		<div id="mainContent">
			<form id="FormData" method="post"
				enctype="application/x-www-form-urlencoded"
				action="<?php echo($_SERVER['PHP_SELF']);?>">
				<!-- InstanceBeginEditable name="Contenidos" -->
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td width="1%">&nbsp;</td>
						<td width="73%">&nbsp;</td>
						<td width="26%">&nbsp;</td>
					</tr>
					<tr>
						<td height="18">&nbsp;</td>
						<td rowspan="2" style="text-align: justify;"><font
							style="color: #333; font-size: 11px;">Mediante esta opción vamos
								a generar un archivo de texto formateado requerido por el BCP de
								tal forma que el sistema Telecredito del BCP lo pueda leer. La
								estructura del archivo de texto solicitado es un estándar que
								el BCP pone a disposición de los usuarios del Telecrédito.</font></td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
				</table>
				<br />

				<table width="100%" border="0" cellpadding="0" cellspacing="0">
					<tr></tr>
					<tr>
						<td align="center"><input type="hidden" id="txturlxls"
							name="txturlxls" value="" />
							<div style="width: 98%;">
								<div id="sstabMain" class="TabbedPanels">
									<ul class="TabbedPanelsTabGroup">
										<li class="TabbedPanelsTab" tabindex="0"><b
											style="color: red;">Paso 1</b> - Importar Datos</li>
										<li class="TabbedPanelsTab" tabindex="1"><b
											style="color: red;">Paso 2</b> - Validar datos</li>
										<li class="TabbedPanelsTab" tabindex="2"><b
											style="color: red;">Paso 3</b> - Resultados Generados</li>
									</ul>
									<div class="TabbedPanelsContentGroup">
										<div class="TabbedPanelsContent">
											<div id="divPaso1"></div>
										</div>
										<div class="TabbedPanelsContent">
											<div id="divPaso2"></div>
										</div>
										<div class="TabbedPanelsContent">
											<div id="divPaso3"></div>
										</div>
									</div>
								</div>
							</div></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
					</tr>
				</table>
				<p>&nbsp;</p>
				<p>&nbsp;</p>
				<p>&nbsp;</p>


				<script language="javascript" type="text/javascript">  
  var htmlLoading = "<br /> <br /> <p align='center'><img src='<?php echo(constant("PATH_IMG"));?>indicator.gif' width='16' height='16' /><br>Cargando..<br></p>" ;
  function CancelarAll()
  {
	  if(confirm("¿Está seguro de cancelar todos los datos trabajados ?"))
	  {
		  $('#divPaso1').html("");
		  $('#divPaso2').html("");
		  $('#divPaso3').html("");
		  TabbedPanels1.showPanel(0);
		  LoadPaso1();
	  }
  }
  
  function LoadPaso1()
	{
		var BodyForm = "action=<?php echo(md5("importar"));?>";
	 	var sURL = "paso1.php";
		$("#divPaso1").html(htmlLoading);
	 	var req = Spry.Utils.loadURL("POST", sURL, true, SuccessLoadImportar, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
	}
	function SuccessLoadImportar(req)
	{
		var respuesta = req.xhRequest.responseText;
	    $("#divPaso1").html(respuesta);
 	    return;
	}
	
	function onErrorLoad(req)
	{
		var respuesta = req.xhRequest.responseText;
	    alert(respuesta);
 	    return;
	}
	
	LoadPaso1();
	
	
	function LoadPaso2(urlfile)
	{
		var BodyForm = "action=<?php echo(md5("leer_xls"));?>&UrlFile="+urlfile;
		var sURL = "paso2.php";
		$('#divPaso2').html(htmlLoading);
		var req = Spry.Utils.loadURL("POST", sURL, true, SuccessLoadPaso2, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
	}
	function SuccessLoadPaso2(req)
	{
		var respuesta = req.xhRequest.responseText;
		$("#divPaso2").html(respuesta);
		return;
	}
	
	function LoadPaso2_1()
	{
		var BodyForm = "action=<?php echo(md5("leer_xls_hoja"));?>&UrlFile="+$("#txturlxls").val()+"&NumHoja="+$("#cboHojasFile").val();
		var sURL = "paso2.php";
		$('#divDetailExcel').html(htmlLoading);
		var req = Spry.Utils.loadURL("POST", sURL, true, SuccessLoadPaso2_1, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
	}
	
	function SuccessLoadPaso2_1(req)
	{
		var respuesta = req.xhRequest.responseText;
		$("#divDetailExcel").html(respuesta);
		return;
	}
	
	
	function LoadMostrarDatosValidados()
	{
		var BodyForm = $('#FormData').serialize();
		var sURL = "paso2_1.php?action=<?php echo(md5("mostrar_datos_validados"));?>";
		$('#divPaso2').html(htmlLoading);
		var req = Spry.Utils.loadURL("POST", sURL, true, SuccessLoadMostrarDatosValidados, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
	}
	
	function SuccessLoadMostrarDatosValidados(req)
	{
		var respuesta = req.xhRequest.responseText;
		$("#divPaso2").html(respuesta);
		return;
	}
	
	function LoadPaso3(idcuenta)
	{
		var BodyForm = "idcuenta=" + idcuenta ;
		var sURL = "paso3.php?action=<?php echo(md5("mostrar_txt_bcp"));?>";
		$('#divPaso3').html(htmlLoading);
		TabbedPanels1.showPanel(2);
		var req = Spry.Utils.loadURL("POST", sURL, true, SuccessLoadPaso3, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
	}
	function SuccessLoadPaso3(req)
	{
		var respuesta = req.xhRequest.responseText;
		$("#divPaso3").html(respuesta);
		return;
	}

  </script>


				<script type="text/javascript">
	<!--
	var TabbedPanels1 = new Spry.Widget.TabbedPanels("sstabMain");
	//-->
  </script>
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
