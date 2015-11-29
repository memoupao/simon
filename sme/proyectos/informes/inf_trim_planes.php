<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>

<?php
require (constant("PATH_CLASS") . "BLInformes.class.php");

error_reporting("E_PARSE");
$idProy = $objFunc->__POST('idProy');
$idVersion = $objFunc->__POST('t25_ver_inf');
$idAnio = $objFunc->__POST('idAnio');
$idTrim = $objFunc->__POST('idTrim');

if ($idProy == "" && $idAnio == "" && $idTrim == "") {
    $idProy = $objFunc->__GET('idProy');
    $idVersion = $objFunc->__GET('t25_ver_inf');
    $idAnio = $objFunc->__GET('idAnio');
    $idTrim = $objFunc->__GET('idTrim');
}

// $idAnio -= 1;

if ($idProy == "") {
    ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/templateIframe.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->

<title>Actividades</title>
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->
<script language="javascript" type="text/javascript"
	src="../../../jquery.ui-1.5.2/jquery-1.2.6.js"></script>
<script src="../../../SpryAssets/SpryTabbedPanels.js"
	type="text/javascript"></script>
<link href="../../../SpryAssets/SpryTabbedPanels.css" rel="stylesheet"
	type="text/css" />

<!-- InstanceEndEditable -->
<link href="../../../css/itemplate.css" rel="stylesheet" type="text/css"
	media="all" />
</head>

<body>
	<!-- InstanceBeginEditable name="tBody" -->
	<form id="frmMain" name="frmMain" method="post" action="#">
<?php
}
?>
<?php

$objInf = new BLInformes();
$row = $objInf->ListaAnalisisInfTrim($idProy, $idAnio, $idTrim, $idVersion);

?>
 
<div>
			<div id="ssTabPlanes" class="TabbedPanels">
				<ul class="TabbedPanelsTabGroup">
					<li class="TabbedPanelsTab" tabindex="0">Avances en Capacitación</li>
					<li class="TabbedPanelsTab" tabindex="1"
						onclick="LoadPlanAT(false);">Avances en Asistencia Técnica</li>
					<li class="TabbedPanelsTab" tabindex="2"
						onclick="LoadPlanCreditos(false);">Avances en Creditos</li>
					<li class="TabbedPanelsTab" tabindex="0"
						onclick="LoadPlanOtros(false);">Otros Servicios</li>
				</ul>
				<div class="TabbedPanelsContentGroup">
					<div class="TabbedPanelsContent">
						<div id="divPlanCapacitacion"></div>
					</div>
					<div class="TabbedPanelsContent">
						<div id="divPlanAT"></div>
					</div>
					<div class="TabbedPanelsContent">
						<div id="divPlanCreditos"></div>
					</div>
					<div class="TabbedPanelsContent">
						<div id="divPlanOtros"></div>
					</div>
				</div>

			</div>
		</div>

		<input type="hidden" name="t02_cod_proy"
			value="<?php echo($idProy);?>" /> <input type="hidden"
			name="t02_version" value="<?php echo($idVersion);?>" /> <input
			name="t25_anio" type="hidden" id="t25_anio"
			value="<?php echo($idAnio);?>" /> <input name="t25_trim"
			type="hidden" id="t25_trim" value="<?php echo($idTrim);?>" />

		<script language="javascript" type="text/javascript">
   
   function LoadPlanesCapacitacion(recargar)
	{
		if($('#divPlanCapacitacion').html()!="")
		{ if(!recargar){return false;}	}
		
		var anio = $('#cboanio').val();
		var trim = $('#cbotrim').val();
		if(anio=="" || anio==null){alert("Seleccione A\u00f1o del Informe"); return false;}
		if(trim=="" || trim==null){alert("Seleccione Trimestre, del Informe");  return false;}

		var BodyForm = "action=<?php echo(md5("lista_ind_prop"));?>&idProy=<?php echo($idProy);?>&idAnio="+anio+"&idTrim="+trim+"&t25_ver_inf="+$("#t25_ver_inf").val();
	 	var sURL = "inf_trim_plan_capac.php";
		$('#divPlanCapacitacion').html("<p align='center'><img src='../../../img/indicator.gif' width='16' height='16' /><br>Cargando..<br></p>");
	 	var req = Spry.Utils.loadURL("POST", sURL, true, SuccessPlanesEspecificos, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
        
	}
	function SuccessPlanesEspecificos		(req)
	{
	   var respuesta = req.xhRequest.responseText;
	   $("#divPlanCapacitacion").html(respuesta);
	   configSubPages();
 	   return;
	}

 	function LoadPlanAT(recargar)
	{
		if($('#divPlanAT').html()!="")
		{ if(!recargar){return false;}	}
		
		var anio = $('#cboanio').val();
		var trim = $('#cbotrim').val();
		if(anio=="" || anio==null){alert("Seleccione A\u00f1o del Informe"); return false;}
		if(trim=="" || trim==null){alert("Seleccione Trimestre, del Informe");  return false;}

		var BodyForm = "action=<?php echo(md5("lista_at"));?>&idProy=<?php echo($idProy);?>&idAnio="+anio+"&idTrim="+trim+"&t25_ver_inf="+$('#t25_ver_inf').val();
	 	var sURL = "inf_trim_plan_at.php";
		$('#divPlanAT').html("<p align='center'><img src='../../../img/indicator.gif' width='16' height='16' /><br>Cargando..<br></p>");
	 	var req = Spry.Utils.loadURL("POST", sURL, true, SuccessPlanAT, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
	}
	function SuccessPlanAT		(req)
	{
	   var respuesta = req.xhRequest.responseText;
	   $("#divPlanAT").html(respuesta);
	   configSubPages();
 	   return;
	}
	
	function LoadPlanCreditos(recargar)
	{
		if($('#divPlanCreditos').html()!="")
		{ if(!recargar){return false;}	}
		
		var anio = $('#cboanio').val();
		var trim = $('#cbotrim').val();
		if(anio=="" || anio==null){alert("Seleccione A\u00f1o del Informe"); return false;}
		if(trim=="" || trim==null){alert("Seleccione Trimestre, del Informe");  return false;}

		var BodyForm = "action=<?php echo(md5("lista_at"));?>&idProy=<?php echo($idProy);?>&idAnio="+anio+"&idTrim="+trim+"&t25_ver_inf="+$('#t25_ver_inf').val();
	 	var sURL = "inf_trim_plan_cred.php";
		$('#divPlanCreditos').html("<p align='center'><img src='../../../img/indicator.gif' width='16' height='16' /><br>Cargando..<br></p>");
	 	var req = Spry.Utils.loadURL("POST", sURL, true, SuccessPlanCreditos, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
	}
	function SuccessPlanCreditos		(req)
	{
	   var respuesta = req.xhRequest.responseText;
	   $("#divPlanCreditos").html(respuesta);
	   configSubPages();
 	   return;
	}
	
	
	function LoadPlanOtros(recargar)
	{
		if($('#divPlanOtros').html()!="")
		{ if(!recargar){return false;}	}
		
		var anio = $('#cboanio').val();
		var trim = $('#cbotrim').val();
		if(anio=="" || anio==null){alert("Seleccione A\u00f1o del Informe"); return false;}
		if(trim=="" || trim==null){alert("Seleccione Trimestre, del Informe");  return false;}

		var BodyForm = "action=<?php echo(md5("lista_otros"));?>&idProy=<?php echo($idProy);?>&idAnio="+anio+"&idTrim="+trim+"&t25_ver_inf="+$('#t25_ver_inf').val();
	 	var sURL = "inf_trim_plan_otros.php";
		$('#divPlanOtros').html("<p align='center'><img src='../../../img/indicator.gif' width='16' height='16' /><br>Cargando..<br></p>");
	 	var req = Spry.Utils.loadURL("POST", sURL, true, SuccessPlanOtros, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
	}
	function SuccessPlanOtros		(req)
	{
	   var respuesta = req.xhRequest.responseText;
	   $("#divPlanOtros").html(respuesta);
	   configSubPages();
 	   return;
	}
	
	var TabsPlanes = new Spry.Widget.TabbedPanels("ssTabPlanes", {defaultTab:0});
	
	LoadPlanesCapacitacion(false);
	
</script>

<?php if($idProy=="") { ?>
</form>
	<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd -->
</html>
<?php } ?>