<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauserxml.inc.php"); ?>

<?php
require (constant('PATH_CLASS') . "BLMonitoreoFinanciero.class.php");
// require(constant('PATH_CLASS')."HardCode.class.php");
?>

<?php

$Accion = $objFunc->__GET('action');

if ($Accion == '') {
    echo (Error());
    exit();
}

if (md5("ajax_new") == $Accion || md5("ajax_edit") == $Accion) {
    GuardarInforme($Accion);
    exit();
}

if (md5("ajax_del") == $Accion) {
    EliminarInforme();
    exit();
}

if (md5("ajax_save_anexos") == $Accion) {
    GuardarAnexos();
    exit();
}

if (md5("ajax_del_anexos") == $Accion) {
    EliminarAnexos();
    exit();
}

?>
<?php
// nforme Mensual - Cabecera
function GuardarInforme($tipo)
{
    $objInf = new BLMonitoreoFinanciero();
    $bret = false;
    
    $NumeroInforme = 0;
    
    if ($tipo == md5("ajax_new")) {
        $bret = $objInf->Informe_visita_MF_NuevoCab($NumeroInforme);
    }
    
    if ($tipo == md5("ajax_edit")) {
        $bret = $objInf->Informe_visita_MF_ActualizaCab($NumeroInforme);
    }
    
    ob_clean();
    ob_start();
    if ($bret) {
        echo ("Exito" . $NumeroInforme . " Se guardó correctamente, los datos del Informe !!!");
    } else {
        echo ("ERROR : \n" . $objInf->GetError());
    }
    return $bret;
}

function EliminarInforme()
{
    ob_clean();
    ob_start();
    $objInf = new BLMonitoreoFinanciero();
    
    $bret = false;
    $bret = $objInf->Informe_visita_MF_Eliminar();
    if ($bret) {
        echo ("Exito Se Elimino correctamente el Informe de Visita de Monitor Financiero !!!");
    } else {
        echo ("ERROR : \n" . $objInf->GetError());
    }
    return $bret;
}

function GuardarAnexos()
{
    $objInf = new BLMonitoreoFinanciero();
    $oFn = new Functions();
    
    $bret = false;
    $bret = $objInf->Inf_visita_MF_GuardarAnexos();
    
    ob_clean();
    ob_start();
    if ($bret) {
        // echo("Exito Se Guardaron correctamente los Anexos Fotograficos !!!");
        $HardCode = "alert('" . "Se Guardaron correctamente los Anexos del Informe de Monitoreo Financiero !!!" . "'); \n";
        $HardCode .= "parent.LoadAnexos(true); \n";
        $oFn->Javascript($HardCode);
    } else {
        $HardCode = "alert('" . $objInf->GetError() . "'); \n";
        $HardCode .= "parent.LoadAnexos(true); \n";
        $oFn->Javascript($HardCode);
        // echo("ERROR : \n".$objInf->GetError());
    }
    return $bret;
}

function EliminarAnexos()
{
    ob_clean();
    ob_start();
    $objInf = new BLMonitoreoFinanciero();
    $bret = false;
    $bret = $objInf->Inf_visita_MF_EliminarAnexos();
    if ($bret) {
        echo ("Exito Se Elimino correctamente el Anexo del Informe de Monitoreo Financiero !!!");
    } else {
        echo ("ERROR : \n" . $objInf->GetError());
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