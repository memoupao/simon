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
    GuardarPlanCreditos();
    exit();
}

if (md5("ajax_del_plan_cred") == $Accion) {
    EliminarPlanCreditos();
    exit();
}

if (md5("ajax_save_plan_cred_benef") == $Accion) {
    GuardarPlanCreditosBenef();
    exit();
}

if (md5("ajax_save_plan_cred_benef_monto") == $Accion) {
    GuardarPlanCreditosBenefMontos();
    exit();
}

if (md5("ajax_del_plan_cre_benef") == $Accion) {
    EliminarPlanCreditosBenefMontos();
    exit();
}

?>
<?php


function GuardarPlanCreditos()
{
    $objPOA = new BLPOA();
    $bret = false;
    
    $bret = $objPOA->PlanCred_Guardar();
    
    ob_clean();
    ob_start();
    if ($bret) {
        echo ("Exito Se Guardaron los Datos correctamente !!!");
    } else {
        echo ("ERROR : \n" . $objPOA->GetError());
    }
    return $bret;
}

function GuardarPlanCreditosBenef()
{
    $objPOA = new BLPOA();
    $bret = false;
    
    $bret = $objPOA->PlanCred_GuardarBenef();
    
    ob_clean();
    ob_start();
    if ($bret) {
        echo ("Exito Se Guardaron los Datos correctamente !!!");
    } else {
        echo ("ERROR : \n" . $objPOA->GetError());
    }
    return $bret;
}

function GuardarPlanCreditosBenefMontos()
{
    $objPOA = new BLPOA();
    $bret = false;
    
    $bret = $objPOA->PlanCred_GuardarBenef_Montos();
    
    ob_clean();
    ob_start();
    if ($bret) {
        echo ("Exito Se Guardaron los Datos correctamente !!!");
    } else {
        echo ("ERROR : \n" . $objPOA->GetError());
    }
    return $bret;
}

function EliminarPlanCreditos()
{
    ob_clean();
    ob_start();
    $objPOA = new BLPOA();
    
    $bret = false;
    $bret = $objPOA->PlanCred_Eliminar();
    if ($bret) {
        echo ("Éxito se eliminó correctamente el Plan de Créditos para la Actividad [" . $_POST['idComp'] . '.' . $_POST['idAct'] . '.' . $_POST['idSub'] . "] !!!");
    } else {
        echo ("ERROR : \n" . $objPOA->GetError());
    }
    return $bret;
}

function EliminarPlanCreditosBenefMontos()
{
    ob_clean();
    ob_start();
    $objPOA = new BLPOA();
    
    $bret = false;
    $bret = $objPOA->PlanCred_EliminarBenef();
    if ($bret) {
        echo ("Éxito se eliminó correctamente el beneficario del Plan de Credito");
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