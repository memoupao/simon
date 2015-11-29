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
    GuardarPlanCapacitacion();
    exit();
}

if (md5("ajax_del_plan_capac") == $Accion) {
    EliminarPlanCapacitacion();
    exit();
}

// uardar Temas Especificos
if (md5("ajax_tema_new") == $Accion || md5("ajax_tema_edit") == $Accion) {
    GuardarTemas((md5("ajax_tema_new") == $Accion ? true : false));
    exit();
}
if (md5("ajax_tema_del") == $Accion) {
    EliminarTema();
    exit();
}

?>
<?php


function GuardarPlanCapacitacion()
{
    $objPOA = new BLPOA();
    $bret = false;
    
    $bret = $objPOA->PlanCapacitacion_Guardar();
    
    ob_clean();
    ob_start();
    if ($bret) {
        echo ("Exito Se Guardaron los Datos correctamente !!!");
    } else {
        echo ("ERROR : \n" . $objPOA->GetError());
    }
    return $bret;
}

function EliminarPlanCapacitacion()
{
    ob_clean();
    ob_start();
    $objPOA = new BLPOA();
    
    $bret = false;
    $bret = $objPOA->PlanCapacitacion_Eliminar();
    if ($bret) {
        echo ("Éxito se eliminó correctamente el Plan de Capacitación para la Actividad [" . $_POST['idComp'] . '.' . $_POST['idAct'] . '.' . $_POST['idSub'] . "] !!!");
    } else {
        echo ("ERROR : \n" . $objPOA->GetError());
    }
    return $bret;
}

function GuardarTemas($new)
{
    ob_clean();
    ob_start();
    $objPOA = new BLPOA();
    
    $bret = false;
    $bret = $objPOA->PlanCapacitacion_GuardarTema($new);
    
    if ($bret) {
        echo ("Exito Se guardó correctamente, el Taller del Plan de Capacitación");
    } else {
        echo ("ERROR : \n" . $objPOA->GetError());
    }
    return $bret;
}

function EliminarTema()
{
    ob_clean();
    ob_start();
    $objPOA = new BLPOA();
    
    $bret = false;
    $bret = $objPOA->PlanCapacitacion_EliminarTema();
    if ($bret) {
        echo ("Exito Se Elimino correctamente el Taller Seleccionado !!!");
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