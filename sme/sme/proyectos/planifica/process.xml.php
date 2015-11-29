<?php
// --> Limpiamos los espacios en blanco
ob_start('LimpiarBuffer');
header("Content-type: text/xml");
?>
<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauserxml.inc.php"); ?>

<?php
require (constant('PATH_CLASS') . "BLMarcoLogico.class.php");
?>

<?php
$Accion = $objFunc->__GET('action');

if ($Accion == '') {
    echo (Error());
    exit();
}

// Si la accion encriptada el Lista entonces, Selecionamos
if (md5("lista_componentes") == $Accion) // ecd2ec43c588ae012e3550fcc55d6009
{
    echo (ListaComponentes());
    exit();
}

if (md5("lista_actividades") == $Accion) //
{
    echo (ListaActividades());
    exit();
}

if (md5("lista_subactividades") == $Accion) //
{
    echo (ListaSubActividades());
    exit();
}

?>



<?php
/* Definición de Funciones que escriben el archivo XML */
function ListaComponentes()
{
    $objML = new BLMarcoLogico();
    $idProy = $_GET['idProy'];
    $idVersion = $_GET['idVersion'];
    
    $rs = $objML->ListadoDefinicionOE($idProy, $idVersion);
    $fields = $objML->GetArrayFields($rs);
    $objFun = new Functions();
    $xmlResult = $objFun->GenerateXML($rs, "componente", $fields);
    return $xmlResult;
}

function ListaActividades()
{
    $objML = new BLMarcoLogico();
    $idProy = $_GET['idProy'];
    $idVersion = $_GET['idVersion'];
    $idComp = $_GET['idComp'];
    $rs = $objML->ListadoActividadesOE($idProy, $idVersion, $idComp);
    $fields = $objML->GetArrayFields($rs);
    $objFun = new Functions();
    $xmlResult = $objFun->GenerateXML($rs, "actividades", $fields);
    return $xmlResult;
}

function ListaSubActividades()
{
    $objML = new BLMarcoLogico();
    $idProy = $_GET['idProy'];
    $idVersion = $_GET['idVersion'];
    $idComp = $_GET['idComp'];
    $idActiv = $_GET['idActiv'];
    $idAnio = $_GET['anio'];
    $rs = $objML->ListaSubActividades($idProy, $idVersion, $idComp, $idActiv, $idAnio);
    $fields = $objML->GetArrayFields($rs);
    $objFun = new Functions();
    $xmlResult = $objFun->GenerateXML($rs, "subactividades", $fields);
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