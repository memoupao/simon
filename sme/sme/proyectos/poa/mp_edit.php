<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>

<?php
require (constant("PATH_CLASS") . "BLInformes.class.php");
require (constant("PATH_CLASS") . "BLTablasAux.class.php");

$objInf = new BLInformes();

error_reporting("E_PARSE");
$idProy = $objFunc->__Request('idProy');
$idVersion = $objFunc->__Request('idVersion');

$action = $objFunc->__Request('action');

if ($action == "") {
    ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/templateIframe.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->

<title>Manejo del Proyecto</title>
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
   
  <div>
			<div id="ssTabMP" class="TabbedPanels">
				<ul class="TabbedPanelsTabGroup">
					<li class="TabbedPanelsTab" tabindex="0">Personal Proyecto</li>
					<li class="TabbedPanelsTab" tabindex="1"
						onclick="LoadEquipamiento(false);">Equipamiento Proyecto</li>
					<li class="TabbedPanelsTab" tabindex="2"
						onclick="LoadGastoFuncion(false);">Gastos Funcionamiento</li>
					<li class="TabbedPanelsTab" tabindex="3"
						onclick="LoadGastoAdminis(false);">Costos Administrativos</li>
					<li class="TabbedPanelsTab" tabindex="4"
						onclick="LoadLineaBase(false);">Linea Base / Imprevistos</li>
				</ul>
				<div class="TabbedPanelsContentGroup">
					<div class="TabbedPanelsContent">
						<div id="divPersonal">
							<br /> <br /> <br /> <br /> <br /> <br /> <br /> <br /> <br /> <br />
							<br />
						</div>
					</div>
					<div class="TabbedPanelsContent">
						<div id="divEquipamiento"></div>
					</div>
					<div class="TabbedPanelsContent">
						<div id="divGastosFunc"></div>
					</div>
					<div class="TabbedPanelsContent">
						<div id="divGastosAdmin"></div>
					</div>
					<div class="TabbedPanelsContent">
						<div id="divLineaBase"></div>
					</div>
				</div>
			</div>
			<p>&nbsp;</p>
		</div>
		<script language="javascript" type="text/javascript">
    //Cargar Personal
	function LoadPersonal			()
	{
		var BodyForm = "action=<?php echo(md5("lista_personal"));?>&idProy=<?php echo($idProy);?>&idVersion=<?php echo($idVersion);?>";
	 	var sURL = "mp_personal.php";
		$('#divPersonal').html("<p align='center'><img src='../../../img/indicator.gif' width='16' height='16' /><br>Cargando..<br></p>");
	 	var req = Spry.Utils.loadURL("POST", sURL, true, SuccessPersonal, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
	}
	function SuccessPersonal	 	(req)
	{
	   var respuesta = req.xhRequest.responseText;
	   $("#divPersonal").html(respuesta);
 	   return;
	}
	function onErrorLoad			(req)
	{
		alert("Ocurrio un error al cargar los datos");
	}
	function LoadEquipamiento		(recargar)
	{
		if($('#divEquipamiento').html()!="")
		{
			if(!recargar){return false;}
		}
		var BodyForm = "action=<?php echo(md5("lista_equipamiento"));?>&idProy=<?php echo($idProy);?>&idVersion=<?php echo($idVersion);?>";
	 	var sURL = "mp_equipa.php";
		$('#divEquipamiento').html("<p align='center'><img src='../../../img/indicator.gif' width='16' height='16' /><br>Cargando..<br></p>");
	 	var req = Spry.Utils.loadURL("POST", sURL, true, SuccessEquipamiento, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
	}
	function SuccessEquipamiento	(req)
	{
	   var respuesta = req.xhRequest.responseText;
	   $("#divEquipamiento").html(respuesta);
 	   return;
	}
	function LoadGastoFuncion		(recargar)
	{
		if($('#divGastosFunc').html()!="")
		{
			if(!recargar){return false;}
		}
		var BodyForm = "action=<?php echo(md5("lista_equipamiento"));?>&idProy=<?php echo($idProy);?>&idVersion=<?php echo($idVersion);?>";
	 	var sURL = "mp_gast_func.php";
		$('#divGastosFunc').html("<p align='center'><img src='../../../img/indicator.gif' width='16' height='16' /><br>Cargando..<br></p>");
	 	var req = Spry.Utils.loadURL("POST", sURL, true, SuccessGastoFuncion, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
	}
	function SuccessGastoFuncion	(req)
	{
	   var respuesta = req.xhRequest.responseText;
	   $("#divGastosFunc").html(respuesta);
 	   return;
	}
	
	function LoadGastoAdminis		(recargar)
	{
		if($('#divGastosAdmin').html()!="")
		{
			if(!recargar){return false;}
		}
		var BodyForm = "action=<?php echo(md5("gastos_administ"));?>&idProy=<?php echo($idProy);?>&idVersion=<?php echo($idVersion);?>";
	 	var sURL = "mp_gastos_adm.php";
		$('#divGastosAdmin').html("<p align='center'><img src='../../../img/indicator.gif' width='16' height='16' /><br>Cargando..<br></p>");
	 	var req = Spry.Utils.loadURL("POST", sURL, true, SuccessGastoAdminis, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
	}
	function SuccessGastoAdminis	(req)
	{
	   var respuesta = req.xhRequest.responseText;
	   $("#divGastosAdmin").html(respuesta);
 	   return;
	}
	
	function LoadLineaBase			(recargar)
	{
		if($('#divLineaBase').html()!="")
		{
			if(!recargar){return false;}
		}
		var BodyForm = "action=<?php echo(md5("linea_base"));?>&idProy=<?php echo($idProy);?>&idVersion=<?php echo($idVersion);?>";
	 	var sURL = "mp_linea_base.php";
		$('#divLineaBase').html("<p align='center'><img src='../../../img/indicator.gif' width='16' height='16' /><br>Cargando..<br></p>");
	 	var req = Spry.Utils.loadURL("POST", sURL, true, SuccessLineaBase, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
	}
	function SuccessLineaBase		(req)
	{
	   var respuesta = req.xhRequest.responseText;
	   $("#divLineaBase").html(respuesta);
 	   return;
	}
	
	<?php
if ($idProy != "") {
    echo ("LoadPersonal();");
}
?>
	
	
  </script>
   
 
  <?php if($action=="") { ?>
</form>
	<script type="text/javascript">
<!--
var TabsInforme = new Spry.Widget.TabbedPanels("ssTabMP");
//-->
</script>
	<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd -->
</html>
<?php } ?>