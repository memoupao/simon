<?php
// --> Limpiamos los espacios en blanco
ob_start('LimpiarBuffer');
header("Content-type: text/xml");
?>
<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauserxml.inc.php"); ?>

<?php
require (constant('PATH_CLASS') . "BLProyecto.class.php");
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
    echo (ListaProyectos());
    exit();
}
// Agregado 30/11/2011
if (md5("listaMT") == $Accion) {
    echo (ListaProyectosMT());
    exit();
}
// Agregado 01/12/2011
if (md5("listaMF") == $Accion) {
    echo (ListaProyectosMF());
    exit();
}
if (md5("buscar") == $Accion) //
{
    echo (BusquedaProyectos());
    exit();
}

?>



<?php
/* Definición de Funciones que escriben el archivo XML */
function ListaProyectos()
{
    $objProy = new BLProyecto();
    $idInst = $_GET['idInst'];
    $rs = $objProy->ProyectosListado($idInst);
    $fields = $objProy->iGetArrayFields($rs);
    $objFun = new Functions();
    $xmlResult = $objFun->iGenerateXML($rs, "proyectos", $fields);
    
    return $xmlResult;
}

function ListaProyectosMT()
{
    $objProy = new BLProyecto();
    $idInst = $_GET['idInst'];
    $rs = $objProy->ProyectosListadoMT($idInst);
    $fields = $objProy->iGetArrayFields($rs);
    $objFun = new Functions();
    $xmlResult = $objFun->iGenerateXML($rs, "proyectos", $fields);
    return $xmlResult;
}
// modificado 01/12/2011
function ListaProyectosMF()
{
    $objProy = new BLProyecto();
    $idInst = $_GET['idInst'];
    $rs = $objProy->ProyectosListadoMF($idInst);
    $fields = $objProy->iGetArrayFields($rs);
    $objFun = new Functions();
    $xmlResult = $objFun->iGenerateXML($rs, "proyectos", $fields);
    return $xmlResult;
}

function BusquedaProyectos()
{
    $objProy = new BLProyecto();
    // $idInst = $_GET['idInst'];
    $rs = $objProy->ProyectosPopup($objProy->Session->UserID);
    
    $fields = $objProy->iGetArrayFields($rs);
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