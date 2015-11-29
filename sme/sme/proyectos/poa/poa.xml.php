<?php
// --> Limpiamos los espacios en blanco
ob_start('LimpiarBuffer');
header("Content-type: text/xml");
?>
<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauserxml.inc.php"); ?>

<?php
require (constant('PATH_CLASS') . "BLPOA.class.php");
?>

<?php
$Accion = $objFunc->__GET('action');

if ($Accion == '') {
    echo (Error());
    exit();
}

// Si la accion encriptada el Lista entonces, Selecionamos
if (md5("lista_poa") == $Accion) {
    echo (ListaPOAs());
    exit();
}

if (md5("lista_poa_tec_vb") == $Accion) {
    echo (listarPOAsTecSinVB());
    exit();
}

if (md5("lista_poa_fin_vb") == $Accion) {
    echo (listarPOAsFinSinVB());
    exit();
}

/* Definición de Funciones que escriben el archivo XML */
function ListaPOAs()
{
    $objPOA = new BLPOA();
    $idProy = $_GET['idProy'];

    $rs = $objPOA->POA_Listado($idProy);
    $fields = $objPOA->iGetArrayFields($rs);
    $objFun = new Functions();
    $xmlResult = $objFun->iGenerateXML($rs, "poa", $fields);
    return $xmlResult;
}

function listarPOAsTecSinVB()
{
    $objPOA = new BLPOA();
    $rs = $objPOA->listarPOAsTecSinVB();
    $fields = $objPOA->iGetArrayFields($rs);
    $objFun = new Functions();
    $xmlResult = $objFun->iGenerateXML($rs, "poa", $fields);
    return $xmlResult;
}

function listarPOAsFinSinVB()
{
    $objPOA = new BLPOA();
    $rs = $objPOA->listarPOAsFinSinVB();
    $fields = $objPOA->iGetArrayFields($rs);
    $objFun = new Functions();
    $xmlResult = $objFun->iGenerateXML($rs, "poa", $fields);
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
?>
<?php ob_end_flush();  ?>