<?php
// --> Limpiamos los espacios en blanco
ob_start('LimpiarBuffer');
header("Content-type: text/xml");
?>
<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauserxml.inc.php"); ?>

<?php
require (constant('PATH_CLASS') . "BLInformes.class.php");
?>

<?php
$Accion = $objFunc->__GET('action');
if ($Accion == '') {
    echo (Error());
    exit();
}

if (md5("lista_inf_trim") == $Accion) // ecd2ec43c588ae012e3550fcc55d6009
{
    echo (ListaInformesTrim());
    exit();
}

?>

<?php
/* Definición de Funciones que escriben el archivo XML */
function ListaInformesTrim()
{
    $objInf = new BLInformes();
    $idProy = $_GET['idProy'];
    
    $rs = $objInf->InformeTrimestralListado($idProy);
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
?>
<?php ob_end_flush();  ?>