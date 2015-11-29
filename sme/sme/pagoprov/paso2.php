<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauserxml.inc.php"); ?>
<?php

$action = $objFunc->__Request('action');
$urlFile = $objFunc->__Request('UrlFile');

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

<?php if($action==md5("leer_xls")) {  ?>
<div id="toolbar" style="height: 15px;" class="BackColor">
						<table width="700" border="0" cellpadding="0" cellspacing="0">
							<tr>
								<td width="67%">Validacion del Archivo XLS importado</td>
								<td width="9%">&nbsp;</td>
								<td width="9%">&nbsp;</td>
								<td width="3%">&nbsp;</td>
								<td width="3%" align="left">&nbsp;</td>
								<td width="9%" align="right">
									<div
										style="text-align: center; color: navy; font-weight: bold; font-size: 10px; cursor: pointer; width: 60px;"
										title="Recargar Archivo Importado"
										onclick="LoadPaso2('<?php echo($urlFile);?>');">
										<img src="../../img/gestion.jpg" width="18" height="18" /><br />Refrescar
									</div>
								</td>
							</tr>
						</table>
					</div>
<?php } ?>
  
<fieldset style="text-align: left; background-color: #FFF;">
						<legend></legend>
    
<?php
require_once (constant("PATH_CLASS") . "ExcelReader.class.php");

$folderXLS = constant("PATH_TEMP_UPLOAD");
$ls_file = $folderXLS . $urlFile;
{ /* Cargando Archivo de Excel */
    $XLSdata = new Spreadsheet_Excel_Reader();
    
    $XLSdata->setOutputEncoding('CP1251');
    $XLSdata->setUTFEncoder('mb');
    /*
     * $XLSdata->setUTFEncoder('iconv'); $XLSdata->setOutputEncoding('UTF-8');
     */
    $XLSdata->read($ls_file);
}
error_reporting(E_ALL ^ E_NOTICE);

$NumHoja = $objFunc->__Request('NumHoja');

?>
<?php if($action==md5("leer_xls")) {  ?>
<div style="padding-left: 5px;">
							<table width="684" border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td width="73" height="32" align="center"><strong>Archivo:</strong></td>
									<td width="378"
										style="font-size: 11px; font-weight: bold; color: #00C;">&nbsp;<?php echo( str_replace($ObjSession->UserID."_","",$urlFile));?>
      <?php
    $action = md5("leer_xls_hoja");
    $NumHoja = 0;
    ?></td>
									<td width="26" align="center"><strong>Hoja:</strong></td>
									<td width="190"><select name="cboHojasFile" id="cboHojasFile"
										style="width: 200px; font-size: 11px; font-weight: normal;"
										class="importargastos" onchange="LoadPaso2_1();">
    <?php
    
    for ($ax = 0; $ax < count($XLSdata->sheets); $ax ++) {
        if ($ax == $hoja) {
            $sel = "selected";
        } else {
            $sel = "";
        }
        echo ("<option value='" . $ax . "' " . $sel . ">" . $XLSdata->boundsheets[$ax]["name"] . "</option>");
    }
    ?>
    </select></td>
									<td width="17"><img src="../../img/btnRecuperar.gif" width="15"
										height="15" style="cursor: pointer;" onclick="LoadPaso2_1();"
										title="Refrescar Archivo" /></td>
								</tr>
							</table>
						</div>
<?php  }  ?>

<div id="divDetailExcel" style="padding-left: 5px;">
<?php if($action==md5("leer_xls_hoja")) {  ?>
    <div id="tableHeadContainer" class="tableContainer, TableGrid"
								style="overflow-y: scroll; overflow-x: scroll; border: solid 1px #666; width: 720px; height: 400px;">
								<table id="TableRowsID" border="0" cellpadding="0"
									cellspacing="0" width="100%">
									<tbody id="fixedHeader">
										<tr>
          <?php
    $HojaXLS = $XLSdata->sheets[$NumHoja];
    
    $rowCAB = $HojaXLS['cells'][1];
    
    for ($c = 1; $c < count($rowCAB); $c ++) {
        ?>
           <th valign="middle" align="center"
												style="border: 1px solid #333; height: 30px; background-color: #CCC;"><?php echo( $rowCAB[$c]);?></th>
           <?php } ?>
           </tr>
									</tbody>
									<tbody id="scrollContent" class="data" bgcolor="#FFFFFF">
         <?php
    
    $_SESSION['MatrizProyectos'] = NULL;
    $Cells = NULL;
    for ($i = 2; $i <= $HojaXLS['numRows']; $i ++) {
        $concurso = 1;
        $codproy = 2;
        $region = 3;
        $inicio = 4;
        $termino = 5;
        $encargado = 6;
        $ejecutor = 7;
        $ruc = 8;
        $modogiro = 9;
        $banco = 10;
        $tipocuenta = 11;
        $nrocuenta = 12;
        $moncuenta = 13;
        $textoref = 14;
        $montotransf = 15;
        $montranf = 16;
        
        $rowCell = $HojaXLS['cells'][$i];
        // print_r($rowCell);
        $validate = false;
        
        if (count($rowCell) >= 15) {
            // if($rowCell[$codproy]!="" && $rowCell[$ejecutor]!="" && $rowCell[$tipocuenta]!="" && $rowCell[$nrocuenta]!="" && $rowCell[$ruc]!="" && $rowCell[$montotransf]!="" )
            if ($rowCell[$codproy] != "" && $rowCell[$ejecutor] != "") {
                $validate = true;
            }
        }
        
        if ($validate)         // Mostrar solo aquellos registros que cumplan con las condiciones
        {
            ?>
         <tr class="RowData" style="font-weight: normal;">
            <?php
            for ($c = 1; $c < count($rowCAB); $c ++) {
                if ($c == $inicio || $c == $termino) {
                    $valor = ($rowCell[$c] + 1 - 25569.833299) * 86400;
                    $rowCell[$c] = date("Y-m-d", $valor);
                    $valor = date("d/m/Y", $valor);
                } else {
                    $valor = $rowCell[$c];
                }
                
                ?>
             <td nowrap="nowrap"> <?php echo($valor); ?> </td>
             
           <?php
            
}
            $Cells[] = $rowCell;
        }
        ?>
           </tr>
         <?php
    
}
    // $Cells = array(implode('|',$arr_codigo), implode('|',$arr_descri), implode('|',$arr_monto));
    $_SESSION['MatrizProyectos'] = $Cells;
    ?>
        </tbody>
									<tfoot>
									</tfoot>
								</table>
							</div>

							<div align="left">
								<table width="684" border="0" cellspacing="1" cellpadding="0">
									<tr>
										<td width="473" nowrap="nowrap">&nbsp;</td>
										<td width="44" align="center" nowrap="nowrap"><span
											style="text-align: center; color: navy; font-weight: bold; font-size: 10px;">Cancelar</span></td>
										<td width="48" nowrap="nowrap"><img src="../../img/delete.png"
											alt="" width="16" height="16" style="cursor: pointer;"
											onclick="CancelarAll();" title="Cancelar" /></td>
										<td width="97" height="30" align="right" nowrap="nowrap"><span
											style="text-align: center; color: navy; font-weight: bold; font-size: 10px;">Validar
												Gastos</span></td>
										<td width="16" align="center" nowrap="nowrap"><img
											src="../../img/aplicar.png" alt="" width="16" height="16"
											style="cursor: pointer;" onclick="LoadValidarDatos();"
											title="Validar Datos Cargados" /><br /></td>
									</tr>
								</table>
							</div>

							<script type="text/javascript">
function LoadValidarDatos()
{
	var BodyForm = $('#FormData').serialize();
	var sURL = "process.php?action=<?php echo(md5("ajax_validar_xls_bd"));?>";
	$('#divDetailExcel').html(htmlLoading);
	var req = Spry.Utils.loadURL("POST", sURL, true, SuccessLoadValidarDatos, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
}

function SuccessLoadValidarDatos(req)
{
	var respuesta =   jQuery.trim(req.xhRequest.responseText);
	if(respuesta=="Exito")
	{
		//alert("Se Valido correctamente la carga de Registros");
		LoadMostrarDatosValidados();
	}
	else
	{
		$("#divDetailExcel").html(respuesta);
	}
	return;
}



</script>

						</div>
<?php  }  ?>
    
    
    
    
</fieldset>
    

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