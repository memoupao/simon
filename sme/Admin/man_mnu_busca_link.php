<?php include("../includes/constantes.inc.php"); ?>
<?php include("../includes/validauseradm.inc.php"); ?>
<?php

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
  <?php
$path = (isset($_GET['dir'])) ? $_GET['dir'] : "";
$modulo = $objFunc->__Request('txtmodulo');
if ($path == "") {
    if ($modulo == "1") {
        $path = constant("APP_PATH") . "Admin/";
    } else {
        $path = constant("APP_PATH") . "sme/";
    }
}

$folders = split("/", $path);
array_pop($folders);
if ($folders[count($folders) - 1] == "..") {
    array_pop($folders);
    array_pop($folders);
    $folders = join("/", $folders) . "/";
    $path = $folders;
} else {
    $folders = join("/", $folders) . "/";
}

$VirtualPath = str_replace(constant("APP_PATH"), "/" . constant("FOLDER") . "/", $folders);

?>
  <span style="color: #036; font-size: 11px; font-weight: bold;">
  Directorio Actual: <?php echo($VirtualPath);?>
  </span>
						<table width="570" border="0" cellpadding="0" cellspacing="0">
							<thead>
								<tr>
									<th width="46" height="25" align="center">&nbsp;</th>
									<th width="412" align="center">Nombre de la Pagina</th>
									<th width="106" align="center">Tipo</th>
									<th width="84" align="center">Tamaño</th>
								</tr>
							</thead>
							<tbody class="data">
      <?php
    
    $directorio = opendir($path);
    
    $pname = array(); // pila de nombres
    $ptype = array(); // pila de Tipos
    $psize = array(); // pila de tamaños
    $pimag = array(); // imagen
                     
    // bucle para llenar las pilas :P
    while ($archivo = readdir($directorio)) {
        // no mostrar ni "." ni ".." ni el propio "index.php"
        if (($archivo != ".") && ($archivo != "..") && ($archivo != "_notes")) {
            
            if (is_dir($path . $archivo)) {
                $imagen = constant("PATH_IMG") . "folder.gif";
                $tipo = "Directorio";
                
                // $result=explode("\t",exec("du -hs ".$path.$archivo),2);
                $tamanio = $objFunc->get_dir_size($path . $archivo);
                
                // $result[1]==$path ? $result[0] : "error";
                // echo $result[1];
            } else {
                $imagen = constant("PATH_IMG") . "file.gif";
                $tipo = "Archivo";
                $tamanio = (filesize($path . $archivo) / 1024);
            }
            
            array_push($pname, $archivo);
            array_push($ptype, $tipo);
            array_push($psize, $tamanio);
            array_push($pimag, $imagen);
        }
    }
    
    closedir($directorio);
    
    array_multisort($ptype, SORT_DESC, SORT_STRING, $pname, SORT_ASC, SORT_STRING, $psize, SORT_ASC, SORT_NUMERIC, $pimag, SORT_ASC, SORT_STRING);
    ?>
      <?php if($VirtualPath!="/".constant("FOLDER")."/" ) { ?>
      <tr class="RowData">
									<td align="center"><a href="javascript:"
										onclick="AbrirDirectorio('../');"> <img
											src="../img/diratras.gif" style="border: none;" width="15"
											height="16" />
									</a></td>
									<td align="left">Directorio Anterior</td>
									<td align="center">&nbsp;</td>
									<td align="right">&nbsp;</td>
								</tr>
      <?php } ?>
      
       <?php
    
    for ($i = 0; $i < count($pname); $i ++) {
        ?>
       <tr class="RowData">
									<td align="center">
        <?php if($ptype[$i]=="Directorio") { ?>
        <a href="javascript:"
										onclick="AbrirDirectorio('<?php echo($pname[$i]);?>/');"> <img
											src="<?php echo($pimag[$i]);?>" style="border: none;" />
									</a>
        <?php } else { ?>
        <img src="<?php echo($pimag[$i]);?>" style="border: none;" />
        <?php } ?>
        </td>
									<td align="left">
        <?php if($ptype[$i]=="Directorio") { ?>
        <?php echo($pname[$i]);?>
        <?php } else { ?>
        <a href="javascript:"
										onclick="SeleccionaArchivo('<?php echo($pname[$i]);?>');"><?php echo($pname[$i]);?></a>
        <?php } ?>
        </td>
									<td align="center"><?php echo($ptype[$i]);?></td>
									<td align="right"><?php  if($psize[$i]<=1024){echo( number_format($psize[$i],2)." KB");} else { echo( number_format( ($psize[$i]/1024),2)." MB"); } ?> </td>
								</tr>
     <?php } ?> 
     
	 </tbody>
							<tfoot>
								<tr>
									<th width="46">&nbsp;</th>
									<th width="412">&nbsp;</th>
									<th width="106">&nbsp;</th>
									<th width="84">&nbsp;</th>
								</tr>
							</tfoot>
						</table>
					</div>


					<script language="javascript">
	function SeleccionaArchivo(file)
	{
		var path = "<?php echo($VirtualPath);?>" + file ;
		$('#<?php echo($txtRet);?>').val(path);
		spryPopupDialog01.displayPopupDialog(false);
		return false ;
	}
	function AbrirDirectorio(dir)
	{
		var querystring = "<?php echo($objFunc->__QueryString("dir"));?>&dir=<?php echo($path)?>"+dir;
		var url = 'man_mnu_busca_link.php' + querystring ;
		loadPopup("Agregar Pagina Secundaria de [" + $('#txtnombre').val() + "]" ,url)
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

