<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauserxml.inc.php"); ?>

<?php
require (constant('PATH_CLASS') . "BLMonitoreo.class.php");
?>

<?php

$Accion = $objFunc->__GET('action');

if ($Accion == '') {
    echo (Error());
    exit();
}

if (md5("ajax_new") == $Accion || md5("ajax_edit") == $Accion) {
    GuardarVisitaME($Accion);
    exit();
}

/*
if (md5("ajax_del") == $Accion) {
    EliminarVisitaME();
    exit();
}*/

?>
<?php
// lan de Vistas de Monitoreo Externo
function GuardarVisitaME($tipo)
{
    $objMoni = new BLMonitoreo();
    $bret = false;
    /*if ($tipo == md5("ajax_new")) {
        $bret = $objMoni->PlanVisitasNuevo($retvs);
    }
    */
    if ($tipo == md5("ajax_edit")) {
        $bret = $objMoni->PlanVisitasActualizar();
    }
    
    ob_clean();
    ob_start();
    if ($bret) {
        echo ("Exito Se Guardaron los Datos correctamente !!!");
    } else {
        echo ("ERROR : \n" . $objMoni->GetError());
    }
    return $bret;
}

function EliminarVisitaME()
{
    ob_clean();
    ob_start();
    $objMoni = new BLMonitoreo();
    
    $bret = false;
    $bret = $objMoni->PlanVisitasEliminar();
    if ($bret) {
        echo ("Exito Se Elimino correctamente el Plan de Visita Seleccionado !!!");
    } else {
        echo ("ERROR : \n" . $objMoni->GetError());
    }
    return $bret;
}
// ---
function Error($errormsg = "Error al Recibir Parametros")
{
    $err = "<b style='color:red'>Error:</b><br>" . $errormsg;
    echo ($err);
    return;
}
?>

<?php ob_end_flush(); ?>