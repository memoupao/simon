<?php
/**
 * CticServices
 *
 * Procesa los mantenimientos de conceptos derivados de:
 * man_parametros_edit.php
 *
 * @package	Admin
 * @author	DA
 * @since	Version 2.0
 *
 */
include ("../includes/constantes.inc.php");
include ("../includes/validauseradm.inc.php");
require (constant('PATH_CLASS') . "BLMantenimiento.class.php");

$action = $objFunc->__GET('action');
$concepto = 'parametro';

if ($action == '') {
    echo (Error());
    exit();
}

if (md5("ajax_new") == $action || md5("ajax_edit") == $action) {
    guardar($action, $concepto);
    exit();
}

if (md5("ajax_del") == $action) {
    eliminar($concepto);
    exit();
}

/**
 * Guarda en la base de datos el concepto.
 *
 * @author DA
 * @since Version 2.0
 * @access public
 * @param string $tipo Concepto
 * @param string $concepto Concepto
 * @return boolean
 *
 */
function guardar($tipo, $concepto)
{
    $objMante = new BLMantenimiento();
    $bret = false;
    
    if ($tipo == md5("ajax_new")) {
        $bret = $objMante->guardar($concepto);
    }
    
    if ($tipo == md5("ajax_edit")) {
        $bret = $objMante->actualizar($concepto);
    }
    
    ob_clean();
    ob_start();
    
    if ($bret) {
        echo ("Exito Se Guardaron los Datos correctamente.");
    } else {
        echo ("ERROR : \n" . $objMante->GetError());
    }
    
    return $bret;
}

/**
 * Elimina de la base de datos el concepto.
 *
 * @author DA
 * @since Version 2.0
 * @access public
 * @param string $concepto Concepto
 * @return boolean
 *
 */
function eliminar($concepto)
{
    ob_clean();
    ob_start();
    
    $objMante = new BLMantenimiento();
    $bret = false;
    $id = $_POST['id'];
    $bret = $objMante->eliminar($concepto, $id);
    
    if ($bret) {
        echo ("Exito Se Elimino correctamente [" . $id . "]!");
    } else {
        echo ("ERROR : \n" . $objMante->GetError());
    }
    return $bret;
}

ob_end_flush();
