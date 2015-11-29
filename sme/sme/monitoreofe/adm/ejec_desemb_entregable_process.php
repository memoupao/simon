<?php
/**
 * CticServices
 * 
 * Gestiona los mantenimientos de lo desembolsado.
 * 
 * @package     Admin
 * @author      AQ
 * @since       Version 2.1
 * 
 * @param string $action Acción a ejecutar.
 * @param string $mode Modo de la Acción a ejecutar.
 *
 */

include("../../../includes/constantes.inc.php");
include("../../../includes/validauserxml.inc.php");

require_once (constant('PATH_CLASS') . "BLProyecto.class.php");
require_once (constant('PATH_CLASS') . "BLInterface.class.php");
require_once (constant('PATH_CLASS') . "HardCode.class.php");
require_once (constant('PATH_CLASS') . "BLFE.class.php");
require_once (constant('PATH_CLASS') . "Functions.class.php");

$objFunc = new Functions();
$accion = $objFunc->__GET('action');
$mode = $objFunc->__GET('mode');

if ($accion == '') {
    echo (Error());
    exit();
}

if (md5("guardar") == $accion) {
    guardarDesembolsado($mode);
    exit();
}

if (md5("eliminar") == $accion) {
    eliminarDesembolsado();
    exit();
}

function guardarDesembolsado($mode)
{
    $objFE = new BLFE();
    $bret = false;
    $retcodigo = 0;
    
    $bret = $objFE->guardarDesembolsado($retcodigo);

    ob_clean();
    ob_start();
    if ($bret)
        echo ("Exito Se Guardaron los datos correctamente");
    else
        echo ("ERROR : \n" . $objFE->GetError());
    return $bret;
}

function eliminarDesembolsado()
{
    ob_clean();
    ob_start();
    
    $objFE = new BLFE();
    $bret = false;
    
    $bret = $objFE->eliminarDesembolsado();
    if ($bret)
        echo ("Exito Se eliminó correctamente el Desembolso seleccionado");
    else
        echo ("ERROR : \n" . $objFE->GetError());
}

function Error($errormsg = "Error al Recibir Parametros")
{
    $err = "<b style='color:red'>Error:</b><br>" . $errormsg;
    echo ($err);
    return;
}



// if (md5("ajax_edit") == $accion) {
//     Guardar($accion);
//     exit();
// }

// function Guardar($tipo)
// {

// 	$objFunc = new Functions();
//     $obj = new BLFE();
//     $bret = false;
//     $anErrorMsg = null;

//     try {
    	
//         if ($tipo == md5("ajax_edit")) {
        	
//         	$codigo = $objFunc->__POST('proy');
//         	$montos = $objFunc->__POST('monto');
//         	$anios = $objFunc->__POST('anio');
//         	$meses = $objFunc->__POST('mes');
        	        	        	        	        	     
        	
//         	for($item=0; $item<count($montos); $item++ ) {
        		
//         		$sql = 'UPDATE t02_entregable SET ';
//         		$sql .= ' monto_desemb = '.$montos[$item];
//         		$sql .= " WHERE t02_anio = '".$anios[$item]."' AND t02_mes = '".$meses[$item]."' ";
//         		$sql .=' AND t02_cod_proy = "'.$codigo.'" ';
        		
//         		$bret = $obj->updateDesembolsoPorEntregable($sql);
//         	}
//         }
//     } catch (Exception $e) {
//         $bret = false;
//         $anErrorMsg = $e->getMessage();
//     }

//     /* En PHP v. 5.2 y 5.3 aun no esta implementado el bloque "finally" asi que se tiene que manejar de otra manera */
//     ob_clean();
//     ob_start();

//     if ($bret) {
//         echo ("Exito" . $vs . " Se guardaron los datos correctamente !!!");
//     } else {
//         echo ("ERROR : \n" . ($anErrorMsg ? $anErrorMsg : $objProy->GetError()));
//     }

//     return $bret;
// }


ob_end_flush();