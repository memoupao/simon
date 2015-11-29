<?php include("../includes/constantes.inc.php"); ?>
<?php include("../includes/validauseradm.inc.php"); ?>
<?php

// require(constant('PATH_CLASS')."BLMantenimiento.class.php");
// $objMante = new BLMantenimiento();

$view = $objFunc->__GET('mode');
$txtRet = $objFunc->__Request('txtReturn');

$row = 0;

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

  <div class="TableGrid">
						<table width="550" border="0" cellpadding="0" cellspacing="0">
							<thead>
								<tr>
									<th width="45" height="25" align="center">Imagen</th>
									<th width="163" align="center">Nombre de Imagen</th>
									<th width="188" align="center">Tipo Imagen</th>
									<th width="92" align="center">Dimensiones</th>
									<th width="60" align="center">Tamaño</th>
								</tr>
							</thead>
							<tbody class="data">
      <?php
    $imgdir = constant("PATH_IMG");
    $imgVirtual = str_replace(constant("VIRTUAL_PATH"), "", constant("PATH_IMG"));
    $dir = constant("APP_PATH") . "img";
    $directorio = opendir($dir);
    while ($archivo = readdir($directorio)) {
        $filename = $dir . '/' . $archivo;
        $tamanio = number_format((filesize($filename) / 1024), 2);
        $datos = getimagesize($filename);
        
        if (! is_dir($filename)) {
            if ($tamanio < 5 && $tamanio > 0) {
                ?>
      <tr class="RowData">
									<td align="center"><img src="<?php echo($imgdir.$archivo);?>"
										width="16" height="16" /></td>
									<td align="left"><a href="javascript:"
										onclick="SeleccionaImagen('<?php echo($archivo);?>');"><?php echo($archivo);?></a></td>
									<td align="center"><?php echo($datos["mime"]);?></td>
									<td align="center"><?php echo($datos[0]." x ".$datos[1]);?></td>
									<td align="right"><?php echo($tamanio);?> KB</td>
								</tr>
       <?php
            }
        }
    }
    
    closedir($directorio);
    
    ?> 
        
	 </tbody>
							<tfoot>
								<tr>
									<th width="45">&nbsp;</th>
									<th width="163">&nbsp;</th>
									<th width="188">&nbsp;</th>
									<th width="92">&nbsp;</th>
									<th width="60">&nbsp;</th>
								</tr>
							</tfoot>
						</table>
					</div>


					<script language="javascript">
	function SeleccionaImagen(img)
	{
		$('#<?php echo($txtRet);?>').val(img);
		spryPopupDialog01.displayPopupDialog(false);
		return false ;
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

