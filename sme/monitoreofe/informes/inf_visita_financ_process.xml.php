<?php
ob_start('LimpiarBuffer');
header("Content-type: text/xml");
?>
<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauserxml.inc.php"); ?>

<?php
require (constant('PATH_CLASS') . "BLMonitoreoFinanciero.class.php");
?>

<?php
$Accion = $objFunc->__GET('action');

if ($Accion == '') {
    echo (Error());
    exit();
}

if (md5("lista") == $Accion) {
    echo (ListaInformes());
    exit();
}

?>



<?php
/* Definición de Funciones que escriben el archivo XML */
function ListaInformes()
{
    $objInf = new BLMonitoreoFinanciero();
    $idProy = $_GET['idProy'];
    
    $rs = $objInf->Inf_visita_MF_Listado($idProy);
    
    $fields = $objInf->iGetArrayFields($rs);
    
    $objFun = new Functions();
    $xmlResult = $objFun->iGenerateXML($rs, "informes", $fields);
    
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