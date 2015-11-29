<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauserxml.inc.php"); ?>
<?php

require_once (constant("PATH_CLASS") . "BLPagoProv.class.php");

$action = $objFunc->__Request('action');
$idCuenta = $objFunc->__Request('idcuenta');

$objPProv = new BLPagoProv();

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

 

<?php if($action==md5("mostrar_txt_bcp")) {  ?>
<br />
					<fieldset style="text-align: left; background-color: #FFF;">
						<legend
							style="color: navy; font-family: Arial, Helvetica, sans-serif; font-size: 11px;">
							Resultados Generados </legend>
						<table width="100%" border="0" cellpadding="0" cellspacing="0"
							class="TableEditReg">
							<tr>
								<td align="center" valign="top">
      <?php
    $iRS = $objPProv->ListaTXTGenerado($idCuenta);
    $txtResult = "";
    while ($row = mysqli_fetch_assoc($iRS)) {
        $txtResult .= $row['salida'] . "\n";
    }
    ?>
      <textarea name="txtcontents" id="txtcontents"
										style="width: 97%; height: 300px;" readonly="readonly"
										wrap="off"><?php echo($txtResult); ?></textarea>
								</td>
							</tr>
							<tr>
								<td><table width="676" border="0" cellspacing="1"
										cellpadding="0">
										<tr>
											<td width="116">&nbsp;</td>
											<td width="91" nowrap="nowrap"><span
												style="text-align: center; color: navy; font-weight: bold; font-size: 10px;">Volver
													a Generar &nbsp;</span></td>
											<td width="15"><img src="../../img/btnRecuperar.gif" alt=""
												width="15" height="15" style="cursor: pointer;"
												onclick="LoadPaso3('<?php echo($idCuenta);?>');"
												title="Volver a Generar TXT" /></td>
											<td width="342" align="center" nowrap="nowrap">&nbsp;</td>
											<td width="29" nowrap="nowrap">&nbsp;</td>
											<td width="60" height="21" align="right" nowrap="nowrap"><span
												style="text-align: center; color: navy; font-weight: bold; font-size: 10px;">Guardar
													TXT</span></td>
											<td width="15" align="center" nowrap="nowrap"><img
												src="../../img/file.gif" alt="" width="14" height="17"
												style="cursor: pointer;" onclick="DownloadTXT();"
												title="Descargar TXT" /><br /></td>
										</tr>
									</table></td>
							</tr>
						</table>

					</fieldset>
					<script>
function DownloadTXT()
{
	 $('#FormData').attr('action','exportTXT.php');
	 $('#FormData').attr('target','ifrmUploadFileXLS');
	 $('#FormData').submit();
	 $('#FormData').attr('target','_self');
}
</script>

<?php  }  ?>
    
    
    
    
<?php
if($action==""){
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