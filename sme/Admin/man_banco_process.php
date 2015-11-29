<?php
include ("../includes/constantes.inc.php");
include ("../includes/validauseradm.inc.php");
require (constant('PATH_CLASS') . "BLMantenimiento.class.php");

$Accion = $objFunc->__GET('action');
$concepto = $objFunc->__GET('concepto');

if ($Accion == '') {
    echo (Error());
    exit();
}

if (md5("ajax_new") == $Accion || md5("ajax_edit") == $Accion) {
    guardar($Accion, $concepto);
    exit();
}

if (md5("ajax_del") == $Accion) {
    eliminar($concepto);
    exit();
}

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
?>