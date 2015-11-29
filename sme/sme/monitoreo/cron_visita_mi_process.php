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
    GuardarVisitaMI($Accion);
    exit();
}

if (md5("ajax_del") == $Accion) {
    EliminarVisitaMI();
    exit();
}

?>
<?php
// lan de Vistas de Monitoreo Externo
function GuardarVisitaMI($tipo)
{
    $objMoni = new BLMonitoreo();
    $bret = false;
    $retvs = 0; // Version del Informe
    
    if ($tipo == md5("ajax_new")) {
        $bret = $objMoni->PlanVisitasMINuevo($retvs);
    }
    
    if ($tipo == md5("ajax_edit")) {
        $bret = $objMoni->PlanVisitasMIActualizar($retvs);
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

function EliminarVisitaMI()
{
    ob_clean();
    ob_start();
    $objMoni = new BLMonitoreo();
    
    $bret = false;
    $bret = $objMoni->PlanVisitasMIEliminar();
    if ($bret) {
        echo ("Exito Se Elimino correctamente la Visita Seleccionado !!!");
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