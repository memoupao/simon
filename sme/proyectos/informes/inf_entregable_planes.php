<?php
/**
 * CticServices
 *
 * Permite el registro de los Planes Específicos
 * del Informe de Entregable
 *
 * @package     sme/proyectos/informes
 * @author      AQ
 * @since       Version 2.0
 *
 */
include("../../../includes/constantes.inc.php");
include("../../../includes/validauser.inc.php");
require (constant("PATH_CLASS") . "BLInformes.class.php");

error_reporting("E_PARSE");
$idProy = $objFunc->__POST('idProy');
$idVersion = $objFunc->__POST('idVersion');
$anio = $objFunc->__POST('anio');
$idEntregable = $objFunc->__POST('idEntregable');

if ($idProy == "" && $anio == "" && $idEntregable == "") {
    $idProy = $objFunc->__GET('idProy');
    $idVersion = $objFunc->__GET('idVersion');
    $anio = $objFunc->__GET('anio');
    $idEntregable = $objFunc->__GET('idEntregable');
}

if ($idProy == "") {
    ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=charset=utf-8" />
    <title>Actividades</title>
    <script language="javascript" type="text/javascript" src="../../../jquery.ui-1.5.2/jquery-1.2.6.js"></script>
    <script src="../../../SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
    <link href="../../../SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />
    <link href="../../../css/itemplate.css" rel="stylesheet" type="text/css" media="all" />
</head>
<body>
	<form id="frmMain" name="frmMain" method="post" action="#">
<?php
}
?>
        <div>
			<div id="ssTabPlanes" class="TabbedPanels">
				<ul class="TabbedPanelsTabGroup">
					<li class="TabbedPanelsTab" tabindex="0">Capacitación</li>
					<li class="TabbedPanelsTab" tabindex="1" onclick="LoadPlanAT(false);">Asistencia Técnica</li>
					<li class="TabbedPanelsTab hide" tabindex="2" onclick="LoadPlanCreditos(false);">Creditos</li>
					<li class="TabbedPanelsTab" tabindex="0" onclick="LoadPlanOtros(false);">Otros Servicios</li>
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

		<input type="hidden" name="idProy" id="idProy" value="<?php echo($idProy);?>" />
		<input type="hidden" name="idVersion" id="idVersion" value="<?php echo($idVersion);?>" />
		<input type="hidden" name="anio" id="anio" value="<?php echo($anio);?>" />
		<input type="hidden" name="idEntregable" id="idEntregable" value="<?php echo($idEntregable);?>" />

		<script language="javascript" type="text/javascript">

    	    function LoadPlanesCapacitacion(recargar)
            {
            	if($('#divPlanCapacitacion').html()!="")
            	{ if(!recargar){return false;}	}

            	var anio = $('#cboanio').val();
            	var entregable = $('#cboentregable').val();
            	if(anio=="" || anio==null){alert("Seleccione Año del Informe"); return false;}
            	if(entregable=="" || entregable==null){alert("Seleccione Entregable");  return false;}

            	var BodyForm = "idProy=<?php echo($idProy);?>&anio="+anio+"&idEntregable="+entregable+"&idVersion="+$("#idVersion").val();
             	var sURL = "inf_entregable_plan_capac.php";

            	$('#divPlanCapacitacion').html("<p align='center'><img src='../../../img/indicator.gif' width='16' height='16' /><br>Cargando..<br></p>");
             	var req = Spry.Utils.loadURL("POST", sURL, true, SuccessPlanesEspecificos, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
            }

        	function SuccessPlanesEspecificos(req)
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
        		var entregable = $('#cboentregable').val();
        		if(anio=="" || anio==null){alert("Seleccione Año del Informe"); return false;}
        		if(entregable=="" || entregable==null){alert("Seleccione Entregable");  return false;}

        		var BodyForm = "action=<?php echo(md5("lista_at"));?>&idProy=<?php echo($idProy);?>&anio="+anio+"&idEntregable="+entregable+"&idVersion="+$('#idVersion').val();
        	 	var sURL = "inf_entregable_plan_at.php";
        		$('#divPlanAT').html("<p align='center'><img src='../../../img/indicator.gif' width='16' height='16' /><br>Cargando..<br></p>");
        	 	var req = Spry.Utils.loadURL("POST", sURL, true, SuccessPlanAT, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
        	}

        	function SuccessPlanAT(req)
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
        		var entregable = $('#cboentregable').val();
        		if(anio=="" || anio==null){alert("Seleccione Año del Informe"); return false;}
        		if(entregable=="" || entregable==null){alert("Seleccione Entregable");  return false;}

        		var BodyForm = "action=<?php echo(md5("lista_at"));?>&idProy=<?php echo($idProy);?>&anio="+anio+"&idEntregable="+entregable+"&idVersion="+$('#idVersion').val();
        	 	var sURL = "inf_entregable_plan_cred.php";
        		$('#divPlanCreditos').html("<p align='center'><img src='../../../img/indicator.gif' width='16' height='16' /><br>Cargando..<br></p>");
        	 	var req = Spry.Utils.loadURL("POST", sURL, true, SuccessPlanCreditos, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
        	}

        	function SuccessPlanCreditos(req)
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
        		var entregable = $('#cboentregable').val();
        		if(anio=="" || anio==null){alert("Seleccione Año del Informe"); return false;}
        		if(entregable=="" || entregable==null){alert("Seleccione Entregable");  return false;}

        		var BodyForm = "action=<?php echo(md5("lista_otros"));?>&idProy=<?php echo($idProy);?>&anio="+anio+"&idEntregable="+entregable+"&idVersion="+$('#idVersion').val();
        	 	var sURL = "inf_entregable_plan_otros.php";
        		$('#divPlanOtros').html("<p align='center'><img src='../../../img/indicator.gif' width='16' height='16' /><br>Cargando..<br></p>");
        	 	var req = Spry.Utils.loadURL("POST", sURL, true, SuccessPlanOtros, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
        	}

        	function SuccessPlanOtros(req)
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
</body>
</html>
<?php } ?>