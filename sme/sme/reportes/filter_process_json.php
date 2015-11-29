<?php
header("Content-Type: application/json");

/**
 * Retorna informacion en formato JSON dependiendo del parametro 'action' enviado en el request.
 */

require_once ("../../includes/constantes.inc.php");
require_once ("../../includes/validauserxml.inc.php");
require_once (constant('PATH_CLASS') . "BLInformes.class.php");
require_once (constant('PATH_CLASS') . "BLMonitoreoFinanciero.class.php");
require_once (constant('PATH_CLASS') . "BLEquipo.class.php");

ob_start('LimpiarBuffer');

$anAction = $objFunc->__GET('action');
if ($anAction == '') {
    echo (Error());
    exit();
}

switch ($anAction) {
    case 'infMensOpts':
        echo getInfMensDates();
        break;
    case 'infTrimOpts':
        echo getInfTrimDates();
        break;
    case 'infFinOpts':
        echo getInfFinDates();
        break;
    case 'evalfinfe':
        echo getEvalFinanFEPeriodos();
        break;
    case 'infvismt':
        echo getInfVisitaMIDates();
        exit();
    case 'infvismf':
        echo getInfVisitaMFDates();
        exit();
    case 'infvisme':
        echo getInfVisitaMEDates();
        exit();
    case 'noc':
        echo getNoObjecionDeComprasLista();
        break;
    case 'cf':
        echo getCartaFianzaLista();
        break;
    case 'cp':
        echo getCambioPersonalLista();
        break;
    case 'infSupEntrOpts':
        echo getInfSupEntreDates();
        break;
        
}

exit();

function getInfMensDates()
{
    $objInf = new BLInformes();
    $aCodProy = $_GET['idProy'];
    $aRes = $objInf->ListaPeriodoInformesMensuales($aCodProy);
    $aResult = array();
    
    while ($aRow = mysql_fetch_assoc($aRes)) {
        $aResult[] = array(
            'anio' => $aRow['t20_anio'],
            'mes' => $aRow['t20_mes'],
            'verInf' => $aRow['t20_ver_inf']
        );
    }
    mysql_free_result($aRes);
    
    return json_encode($aResult);
}

function getInfTrimDates()
{
    $objInf = new BLInformes();
    $aCodProy = $_GET['idProy'];
    $aRes = $objInf->ListaPeriodoInformesTrimestrales($aCodProy);
    $aResult = array();
    
    while ($aRow = mysql_fetch_assoc($aRes)) {
        $aResult[] = array(
            'anio' => $aRow['t25_anio'],
            'trim' => $aRow['t25_trim'],
            'verInf' => $aRow['t25_ver_inf']
        );
    }
    mysql_free_result($aRes);
    
    return json_encode($aResult);
}


function getInfSupEntreDates()
{
	$objInf = new BLInformes();
	$aCodProy = $_GET['idProy'];
	$idVersion = $_GET['idVersion'];
	$aRes = $objInf->ListaPeriodoInformesSupervEntre($aCodProy, $idVersion);
	$aResult = array();

	while ($aRow = mysql_fetch_assoc($aRes)) {
		$aResult[] = array(
				'anio' => $aRow['t25_anio'],
				'mes' => $aRow['t25_entregable'],
				'verInf' => $aRow['t02_version'],
				'entregable' => $aRow['entregable'],
				'identregable' => $aRow['t25_entregable']
		);
	}
	mysql_free_result($aRes);

	return json_encode($aResult);
}

function getInfFinDates()
{
    $objInf = new BLInformes();
    $aCodProy = $_GET['idProy'];
    $aRes = $objInf->ListaPeriodoInformesFinanciros($aCodProy);
    $aResult = array();
    
    while ($aRow = mysql_fetch_assoc($aRes)) {
        $aResult[] = array(
            'anio' => $aRow['t40_anio'],
            'mes' => $aRow['t40_mes'],
            'verInf' => $aRow['t40_ver_inf']
        );
    }
    mysql_free_result($aRes);
    
    return json_encode($aResult);
}

function getEvalFinanFEPeriodos()
{
    $objMon = new BLMonitoreoFinanciero();
    $aCodProy = $_GET['idProy'];
    $aRes = $objMon->InformeMFListaPeriodo($aCodProy);
    $aResult = array();
    
    while ($aRow = mysql_fetch_assoc($aRes)) {
        $aResult[$aRow['t51_num']] = $aRow['t51_periodo'];
    }
    mysql_free_result($aRes);
    
    return json_encode($aResult);
}

function getInfVisitaMIDates()
{
    $objMon = new BLInformes();
    $aCodProy = $_GET['idProy'];
    $aRes = $objMon->ListaPeriodoInformesVisitaMI($aCodProy);
    $aResult = array();
    
    while ($aRow = mysql_fetch_assoc($aRes)) {
        $aResult[$aRow['t45_id']] = $aRow['t45_periodo'];
    }
    mysql_free_result($aRes);
    
    return json_encode($aResult);
}

function getInfVisitaMFDates()
{
    $objMon = new BLMonitoreoFinanciero();
    $aCodProy = $_GET['idProy'];
    $aRes = $objMon->ListaPeriodoInformesVisitaMF($aCodProy);
    $aResult = array();
    
    while ($aRow = mysql_fetch_assoc($aRes)) {
        $aResult[$aRow['t52_num']] = "Informe " . $aRow['t52_num'] . (strlen(trim($aRow['t52_periodo'])) > 0 ? ' - ' . trim($aRow['t52_periodo']) : '');
    }
    mysql_free_result($aRes);
    
    return json_encode($aResult);
}

function getInfVisitaMEDates()
{
    $objMon = new BLInformes();
    $aCodProy = $_GET['idProy'];
    $aRes = $objMon->InformeMEListado($aCodProy);
    $aResult = array();
    
    while ($aRow = mysqli_fetch_assoc($aRes)) {
        $aResult[$aRow['num']] = $aRow['periodo'];
    }
    if ($aRes)
        $aRes->free();
    
    return json_encode($aResult);
}

function getArrayData($pRes, $pKey, $pVal)
{
    $aResult = array();
    
    while ($aRow = mysql_fetch_assoc($pRes))
        $aResult[$aRow[$pKey]] = $aRow[$pVal];
    mysql_free_result($pRes);
    
    return $aResult;
}

function getArrayDataI($pRes, $pKey, $pVal)
{
    $aResult = array();
    
    while ($aRow = mysqli_fetch_assoc($pRes))
        $aResult[$aRow[$pKey]] = $aRow[$pVal];
    if ($pRes)
        $pRes->free();
    
    return $aResult;
}

function getNoObjecionDeComprasLista()
{
    $objProy = new BLProyecto();
    $aCodProy = $_GET['idProy'];
    $aRes = $objProy->NoObjecionCompra_ListaSols($aCodProy);
    $aResult = getArrayData($aRes, 't02_id_noc', 't02_id_noc');
    return json_encode($aResult);
}

function getCartaFianzaLista()
{
    $objProy = new BLProyecto();
    $aCodProy = $_GET['idProy'];
    $aRes = $objProy->CartaFianzaListaPorProy($aCodProy);
    $aResult = getArrayData($aRes, 't02_id_cf', 't02_des_cf');
    return json_encode($aResult);
}

function getCambioPersonalLista()
{
    $objEquipo = new BLEquipo();
    $aCodProy = $_GET['idProy'];
    $aRes = $objEquipo->CambioPersonal_Listado($aCodProy);
    $aResult = getArrayDataI($aRes, 'nro', 'nro');
    return json_encode($aResult);
}

function Error($errormsg = "Error al Recibir Parametros")
{
    return "{'error'':$errormsg}";
}
?>