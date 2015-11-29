<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauserxml.inc.php"); ?>

<?php
require (constant('PATH_CLASS') . "BLPOA.class.php");
?>

<?php

$Accion = $objFunc->__GET('action');
if ($Accion == '') {
    echo (Error());
    exit();
}

if (md5("ajax_save_plan_cab") == $Accion) {
    GuardarPlanAT();
    exit();
}

if (md5("ajax_del_plan_at") == $Accion) {
    EliminarPlanAT();
    exit();
}

?>
<?php


function GuardarPlanAT()
{
    $objPOA = new BLPOA();
    $bret = false;
    
    $bret = $objPOA->PlanAT_Guardar();
    
    ob_clean();
    ob_start();
    if ($bret) {
        echo ("Exito Se Guardaron los Datos correctamente !!!");
    } else {
        echo ("ERROR : \n" . $objPOA->GetError());
    }
    return $bret;
}

function EliminarPlanAT()
{
    ob_clean();
    ob_start();
    $objPOA = new BLPOA();
    
    $bret = false;
    $bret = $objPOA->PlanAT_Eliminar();
    if ($bret) {
        echo ("Éxito se eliminó correctamente el Plan de Capacitación para la Actividad [" . $_POST['idComp'] . '.' . $_POST['idAct'] . '.' . $_POST['idSub'] . "] !!!");
    } else {
        echo ("ERROR : \n" . $objPOA->GetError());
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