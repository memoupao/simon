<?php
include_once ("../includes/constantes.inc.php");
include_once ("../includes/validauser.inc.php");

if (isset($_GET["action"]) && !empty($_GET["action"])) {
	$action = $_GET["action"];
	
	if ($action === md5("setProyDefault")) {
	
		$_SESSION["idProy"] = $_REQUEST["idProy"];
		echo ($_SESSION["idProy"]);
	
		if (isset($_REQUEST["idVersion"])) {
			$_SESSION["idVersion"] = $_REQUEST["idVersion"];
			echo ($_SESSION["idVersion"]);
		}
	}
	
}
