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
    GuardarPlanVisita($Accion);
    exit();
}

if (md5("ajax_del") == $Accion) {
    EliminarPlanVisita();
    exit();
}

if (md5("ajax_act_new") == $Accion || md5("ajax_act_edit") == $Accion) {
    GuardarActividadPlanVisita($Accion);
    exit();
}

if (md5("ajax_act_del") == $Accion) {
    EliminarActividadPlanVisita();
    exit();
}

?>
<?php
// lan de Vistas de ESpecifico
function GuardarPlanVisita($tipo)
{
    $objMoni = new BLMonitoreo();
    $bret = false;
    $ret = 0; // Retorna Numero del Plan
    
    if ($tipo == md5("ajax_new")) {
        $bret = $objMoni->PlanVisitaActivNuevo($ret);
    }
    
    if ($tipo == md5("ajax_edit")) {
        $bret = $objMoni->PlanVisitaActivActualizar($ret);
    }
    
    ob_clean();
    ob_start();
    if ($bret) {
        echo ("Exito" . $ret . " Se Guardaron correctamente los datos del Plan de Visita!!!");
    } else {
        echo ("ERROR : \n" . $objMoni->GetError());
    }
    return $bret;
}

function EliminarPlanVisita()
{
    ob_clean();
    ob_start();
    $objMoni = new BLMonitoreo();
    
    $bret = false;
    $bret = $objMoni->PlanVisitaActivEliminar();
    if ($bret) {
        echo ("Exito Se Elimino correctamente el Plan de Visita Seleccionado !!!");
    } else {
        echo ("ERROR : \n" . $objMoni->GetError());
    }
    return $bret;
}
// ---

// ctividad del Pland e Visita especifico
function GuardarActividadPlanVisita($tipo)
{
    $objMoni = new BLMonitoreo();
    $bret = false;
    
    if ($tipo == md5("ajax_act_new")) {
        $bret = $objMoni->PlanVisitaActivNuevoDet();
    }
    
    if ($tipo == md5("ajax_act_edit")) {
        $bret = $objMoni->PlanVisitaActivActualizarDet();
    }
    
    ob_clean();
    ob_start();
    if ($bret) {
        echo ("Exito  Se Guardaron correctamente los datos de la Actividad!!!");
    } else {
        echo ("ERROR : \n" . $objMoni->GetError());
    }
    return $bret;
}

function EliminarActividadPlanVisita()
{
    ob_clean();
    ob_start();
    $objMoni = new BLMonitoreo();
    
    $bret = false;
    $bret = $objMoni->PlanVisitaActivEliminarDet();
    if ($bret) {
        echo ("Exito Se Elimino correctamente la Actividad del Plan de Visita !!!");
    } else {
        echo ("ERROR : \n" . $objMoni->GetError());
    }
    return $bret;
}

function Error($errormsg = "Error al Recibir Parametros")
{
    $err = "<b style='color:red'>Error:</b><br>" . $errormsg;
    echo ($err);
    return;
}
?>

<?php ob_end_flush(); ?>