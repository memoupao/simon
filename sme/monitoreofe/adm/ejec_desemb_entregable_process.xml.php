<?php
// --> Limpiamos los espacios en blanco
ob_start('LimpiarBuffer');
header("Content-type: text/xml");

include("../../../includes/constantes.inc.php"); 
include("../../../includes/validauserxml.inc.php"); 

require (constant('PATH_CLASS') . "BLFE.class.php");

$Accion = $objFunc->__GET('action');

if ($Accion == '') {
    echo (Error());
    exit();
}

// Si la accion encriptada el Lista entonces, Selecionamos
if (md5("lista") == $Accion) // ecd2ec43c588ae012e3550fcc55d6009
{
    echo ListaDesembolsos();
    exit;
}

/* Definición de Funciones que escriben el archivo XML */
function ListaDesembolsos()
{
	$objList = new BLFE();
	$idProy = $_GET['idProy'];
    $rs = $objList->listadoEjecDesemPorEntregables($idProy);
    $fields = $objList->iGetArrayFields($rs);
    
    $objFun = new Functions();
    $xmlResult = $objFun->iGenerateXML($rs, "proyectos", $fields);
    
    return $xmlResult;
}



function BusquedaProyectos()
{
    $objList = new BLFE();
    $idInst = $_GET['idInst'];
    $rs = $objProy->listadoEjecDesemPorEntregables($idInst);
    
    $fields = $objList->iGetArrayFields($rs);
    $objFun = new Functions();
    $xmlResult = $objFun->iGenerateXML($rs, "proyectos", $fields);
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