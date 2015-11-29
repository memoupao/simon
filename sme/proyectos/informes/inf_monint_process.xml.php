<?php
// --> Limpiamos los espacios en blanco
ob_start('LimpiarBuffer');
header("Content-type: text/xml");

include("../../../includes/constantes.inc.php");
include("../../../includes/validauserxml.inc.php");
require (constant('PATH_CLASS') . "BLInformes.class.php");

$Accion = $objFunc->__GET('action');
if ($Accion == '') {
    echo (Error());
    exit();
}

if (md5("lista_inf_si") == $Accion) {
    echo (listarInfSI());
    exit();
}

/* Definición de Funciones que escriben el archivo XML */
function listarInfSI()
{
    $objInf = new BLInformes();

    $rs = $objInf->listarInfSI($_GET['idProy'], $_GET['idVersion']);
    $fields = $objInf->iGetArrayFields($rs);

    $objFun = new Functions();
    $xmlResult = $objFun->iGenerateXML($rs, "informes", $fields);
    $objFun = NULL;
    return $xmlResult;
}

function Error($errormsg = "Error al Recibir Parametros")
{
    $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>
		<error>
		  <msgs id=\"1\">
			<msg>" . $errormsg . "</msg>
		  </msgs>
		</error>";
    return $xml;
}

function MsgXML($msg)
{
    $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>
		<msgxml>
		  <msgs id=\"1\">
			<msg>" . $msg . "</msg>
		  </msgs>
		</msgxml>";
    return $xml;
}

function LimpiarBuffer($buffer)
{
    return trim($buffer);
}

ob_end_flush();
?>