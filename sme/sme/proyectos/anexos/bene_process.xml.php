<?php
// --> Limpiamos los espacios en blanco
ob_start('LimpiarBuffer');
header("Content-type: text/xml");
?>
<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauserxml.inc.php"); ?>

<?php
require (constant('PATH_CLASS') . "BLBene.class.php");
?>

<?php
$Accion = $objFunc->__GET('action');

if ($Accion == '') {
    echo (Error());
    exit();
}

// Si la accion encriptada el Lista entonces, Selecionamos
if (md5("lista") == $Accion) // ecd2ec43c588ae012e3550fcc55d6009
{
    echo (ListaBene());
    exit();
}

?>



<?php
/* Definición de Funciones que escriben el archivo XML */
function ListaBene()
{
    $objBene = new BLBene();
    $idProy = $_GET['idProy'];
    
    $rs = $objBene->BeneListado($idProy);
    $fields = $objBene->GetArrayFields($rs);
    $objFun = new Functions();
    $xmlResult = $objFun->GenerateXML($rs, "bene", $fields);
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