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
    GuardarPlanOtros();
    exit();
}

if (md5("ajax_del_plan_otros") == $Accion) {
    EliminarPlanOtros();
    exit();
}

?>
<?php


function GuardarPlanOtros()
{
    $objPOA = new BLPOA();
    $bret = false;
    
    $bret = $objPOA->PlanOtros_Guardar();
    
    ob_clean();
    ob_start();
    if ($bret) {
        echo ("Exito Se Guardaron los Datos correctamente !!!");
    } else {
        echo ("ERROR : \n" . $objPOA->GetError());
    }
    return $bret;
}

function EliminarPlanOtros()
{
    ob_clean();
    ob_start();
    $objPOA = new BLPOA();
    
    $bret = false;
    $bret = $objPOA->PlanOtros_Eliminar();
    if ($bret) {
        echo ("Éxito se eliminó correctamente el Plan  para la Actividad [" . $_POST['idComp'] . '.' . $_POST['idAct'] . '.' . $_POST['idSub'] . "] !!!");
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