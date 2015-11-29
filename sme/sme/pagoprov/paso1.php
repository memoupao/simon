<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauserxml.inc.php"); ?>
<?php

require (constant('PATH_CLASS') . "BLTablasAux.class.php");
require (constant('PATH_CLASS') . "BLEjecutor.class.php");

$action = $objFunc->__Request('action');

$OjbTab = new BLTablasAux();
$view = $objFunc->__GET('mode');
$row = 0;

if ($action == md5("importar")) {
    $objFunc->SetSubTitle("Importar Plantilla de Proyectos");
}

if ($action == "") {
    ?>

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

	<div id="toolbar" style="height: 4px;" class="BackColor">
						<table width="700" border="0" cellpadding="0" cellspacing="0">
							<tr>
								<td width="9%">&nbsp;</td>
								<td width="9%">&nbsp;</td>
								<td width="9%">&nbsp;</td>
								<td width="3%">&nbsp;</td>
								<td width="3%" align="left">&nbsp;</td>
								<td width="67%" align="right"><?php echo($objFunc->SubTitle) ;?></td>
							</tr>
						</table>
					</div>
    
  <?php
if ($action == md5("importar")) {
    
    ?>
  <fieldset style="text-align: left; background-color: #FFF;">
						<legend></legend>
						<table width="680" border="0" cellspacing="1" cellpadding="0">
							<tr>
								<td height="30" colspan="3"><font
									style="text-align: justify; font-weight: normal; color: #000;">
										Seleccionar el archivo de Resumen de Proyectos a efectuar los
										pagos. y hacer click en <strong>Cargar</strong>
								</font></td>
							</tr>
							<tr>
								<td width="10%" height="48" align="right" class="TableEditReg"><strong>Archivo</strong></td>
								<td width="75%" class="TableEditReg"><input name="txtarchivo"
									type="file" id="txtarchivo" size="75" /></td>
								<td width="15%" align="left">
									<button type="button" id="btnCargar"
										style="cursor: pointer; width: 55px; text-align: center; background-color: #FFF; border: 1px solid #CCC;"
										title="Cargar Archivo" onclick="CargarFormatoXLS();">
										<img src="../../img/upload.jpg" /> <font
											style="color: navy; font-weight: bold; font-size: 9px;">
											Cargar </font>
									</button>
								</td>
							</tr>
						</table>
						<br />
						<div id="divLoadingFile" align="center"
							style="display: none; width: 100%; background-color: #FFF; color: #333;">
							<br /> <img src="../../img/indicator.gif" width="16" height="16" />
							<br>Cargando Archivo...
						</div>
						<br />
					</fieldset>
					<div style="padding-left: 10px; text-align: left;">
						<br /> <font style="color: red; font-weight: bold;">NOTA:</font> <br />
						<font
							style="text-align: justify; font-weight: normal; color: #036;">
							El archivo a importar,tiene que tener el formato adecuado<br />
							con el número de columnas completos estalecidos <br> Para poder
							ver el formato adecuado, haga clic en <strong><a
								href="PlantillaPagoProv.xls">Descargar</a></strong>.
						</font>
					</div>

					<iframe id="ifrmUploadFileXLS" name="ifrmUploadFileXLS"
						style="display: none; width: 100%;"></iframe>

					<script language="javascript" type="text/javascript">
function CargarFormatoXLS()
{ 
 <?php $ObjSession->AuthorizedPage(); ?>
 
 var archivo = $('#txtarchivo').val();
 if(archivo=='' || archivo==null){alert("No ha seleccionado el archivo a cargar");return false;}

 $("#divLoadingFile").show('fast');

 var f = document.getElementById("FormData");
 f.action="process.php?action=<?php echo(md5('ajax_importar_file_xls'));?>" ;
 f.target="ifrmUploadFileXLS" ;
 f.encoding="multipart/form-data";
 f.submit() ;
 f.target='_self';
 return false;
}

function SuccessCargarFormatXLS(urlfile)
{
	//$("#divLoadingFile").css('display','none');
	$("#divLoadingFile").hide('fast');
	if(urlfile==""){return false ;}
	
	$("#txturlxls").val(urlfile);
	$("#txtarchivo").attr("disabled","disabled");
	$("#btnCargar").attr("disabled","disabled");
	setTimeout("TabbedPanels1.showPanel(1);",500);  	 //Abrir TAB de Validacion
	LoadPaso2(urlfile);
	/*
	var BodyForm = "mode=<?php echo(md5("leer_xls"));?>&idProy=<?php echo($idProy);?>&idAnio=<?php echo($idAnio);?>&idMes=<?php echo($idMes);?>&UrlFile="+urlfile;
	var sURL = "inf_financ_imp_gastos.php";
	$('#divImportarGastos').html("<br /> <br /> <p align='center'><img src='../../../img/indicator.gif' width='16' height='16' /><br>Abriendo archivo cargado..<br></p>");
	var req = Spry.Utils.loadURL("POST", sURL, true, SuccessImportarGastos, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
	*/
}
</script>
<?php  exit();  }  ?>
    
<script language="javascript" type="text/javascript">
/*
function LoadImportarGastos04()
{
	var BodyForm = "mode=<?php echo(md5("validar_xls_hoja"));?>&idProy=<?php echo($idProy);?>&idAnio=<?php echo($idAnio);?>&idMes=<?php echo($idMes);?>&UrlFile=<?php echo($urlFile);?>&NumHoja="+$("#cboHojasFile").val()+"&idFte="+$("#cboImportFuentes").val();
	var sURL = "inf_financ_imp_gastos.php";
	$('#cboImportFuentes').attr("disabled", true); 
	
	$('#divDetailExcel').html("<br /> <br /> <p align='center'><img src='../../../img/indicator.gif' width='16' height='16' /><br>Validando Datos..<br></p>");
	var req = Spry.Utils.loadURL("POST", sURL, true, SuccessImpGastoLeerHojaXLS, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
}

function CancelarImportarGastos04()
{
	LoadImportarGastos();
}

function CancelarImportarGastos05()
{
	$('#cboImportFuentes').removeAttr("disabled"); 
	LoadImportarGastos03();
}

function MostrarCuentasErroneas()
{
	$("#divTableErroneos").toggle();
}

function ImportarGastos05() //Esta es la que procesa la Informacion a la BD
{
	var BodyForm = "idProy=<?php echo($idProy);?>&idAnio=<?php echo($idAnio);?>&idMes=<?php echo($idMes);?>&idFte="+$("#cboImportFuentes").val();
	var sURL = "inf_financ_process.php?action=<?php echo(md5("ajax_importar_finalizar"));?>";
	var req = Spry.Utils.loadURL("POST", sURL, true, SucessImportarGastos05, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
}
function SucessImportarGastos05(req)
{
  var respuesta = req.xhRequest.responseText;
  respuesta = respuesta.replace(/^\s*|\s*$/g,"");
  var ret = respuesta.substring(0,5);
  if(ret=="Exito")
  {
	alert(respuesta.replace(ret,""));
	$('#divImportarGastos').html('');
	spryPopupImportar.displayPopupDialog(false);
	LoadPresupuesto();
  }
  else
  {alert(respuesta);}  
}
*/
</script>

<?php
if ($action == "") {
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