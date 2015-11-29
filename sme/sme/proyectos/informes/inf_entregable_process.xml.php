<?php
/**
 * CticServices
 *
 * Obtiene Datos en XML del Informe de Entregable
 *
 * @package     sme/proyectos/informes
 * @author      AQ
 * @since       Version 2.0
 *
 */
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

if (md5("lista_inf_entregable") == $Accion)
{
    echo (listarInformesEntregable());
    exit();
}

if (md5("lista_inf_entregable_vb") == $Accion)
{
    echo (listarInformesEntregableSinVB());
    exit();
}

function listarInformesEntregable()
{
    $objInf = new BLInformes();
    $rs = $objInf->listarInformesEntregable($_GET['idProy'], $_GET['idVersion']);
    $fields = $objInf->iGetArrayFields($rs);
    $objFun = new Functions();
    $xmlResult = $objFun->iGenerateXML($rs, "informes", $fields);
    $objFun = NULL;
    return $xmlResult;
}

function listarInformesEntregableSinVB()
{
    $objInf = new BLInformes();
    $rs = $objInf->listarInformesEntregableSinVB();
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